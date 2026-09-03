<?php

namespace App\Http\Controllers;

use App\Models\EbookAccess;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class EbookController extends Controller
{
    /**
     * Halaman verifikasi email sebelum bisa akses e-book
     */
    public function verify(Request $request, $token)
    {
        $access = EbookAccess::where('access_token', $token)
            ->with(['product', 'order'])
            ->firstOrFail();

        if (!$access->isValid()) {
            if ($access->isExpired()) {
                return view('digital.ebook.expired', compact('access'));
            }
            abort(403, 'Akses tidak valid');
        }

        // Kalau sudah terverifikasi di session, langsung ke viewer
        if ($this->isSessionVerified($request, $token)) {
            return redirect()->route('ebook.view', $token);
        }

        return view('digital.ebook.verify', compact('access', 'token'));
    }

    /**
     * Proses verifikasi email
     */
    public function processVerify(Request $request, $token)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $access = EbookAccess::where('access_token', $token)
            ->with(['product', 'order'])
            ->firstOrFail();

        if (!$access->isValid()) {
            if ($access->isExpired()) {
                return view('digital.ebook.expired', compact('access'));
            }
            abort(403, 'Akses tidak valid');
        }

        $registeredEmail = $access->order->customer_email ?? $access->customer_email;

        if (strtolower(trim($request->email)) !== strtolower(trim($registeredEmail))) {
            return back()
                ->withErrors(['email' => 'Email tidak cocok dengan data pembelian. Silakan coba lagi.'])
                ->withInput();
        }

        // Simpan status verifikasi di session (per token)
        session([$this->sessionKey($token) => true]);

        return redirect()->route('ebook.view', $token);
    }

    /**
     * View e-book dengan token akses
     */
    public function view(Request $request, $token)
    {
        $access = EbookAccess::where('access_token', $token)
            ->with(['product', 'order'])
            ->firstOrFail();

        // Check if access is valid
        if (!$access->isValid()) {
            if ($access->isExpired()) {
                return view('digital.ebook.expired', compact('access'));
            }

            abort(403, 'Akses tidak valid');
        }

        // Wajib verifikasi email dulu sebelum bisa lihat konten
        if (!$this->isSessionVerified($request, $token)) {
            return redirect()->route('ebook.verify', $token);
        }

        // Record access
        $access->recordAccess($request->ip());

        // Get e-book content
        $product = $access->product;

        // Extract Google Drive file ID from URL
        $fileId = $this->extractGoogleDriveFileId($product->file_url);

        if (!$fileId) {
            abort(400, 'Invalid Google Drive URL');
        }

        return view('digital.ebook.viewer', compact('access', 'product', 'fileId'));
    }

    /**
     * Get PDF content (proxy untuk prevent download)
     */
    public function getContent(Request $request, $token)
    {
        $access = EbookAccess::where('access_token', $token)
            ->with(['order'])
            ->firstOrFail();

        if (!$access->isValid()) {
            abort(403, 'Access expired or invalid');
        }

        // Jangan izinkan akses konten langsung tanpa verifikasi email di session
        if (!$this->isSessionVerified($request, $token)) {
            abort(403, 'Email belum diverifikasi untuk akses ini');
        }

        $fileId = $this->extractGoogleDriveFileId($access->product->file_url);

        // Google Drive preview URL
        $previewUrl = "https://drive.google.com/uc?export=view&id={$fileId}";

        try {
            $response = Http::timeout(30)->get($previewUrl);

            return response($response->body())
                ->header('Content-Type', 'application/pdf')
                ->header('Content-Disposition', 'inline')
                ->header('X-Frame-Options', 'SAMEORIGIN')
                ->header('X-Content-Type-Options', 'nosniff');

        } catch (\Exception $e) {
            abort(500, 'Failed to load e-book content');
        }
    }

    /**
     * Session key unik per token, supaya verifikasi 1 token tidak
     * otomatis membuka token lain di browser yang sama
     */
    private function sessionKey($token)
    {
        return 'ebook_verified_' . $token;
    }

    private function isSessionVerified(Request $request, $token)
    {
        return $request->session()->get($this->sessionKey($token)) === true;
    }

    /**
     * Extract Google Drive file ID from URL
     */
    private function extractGoogleDriveFileId($url)
    {
        $patterns = [
            '/\/file\/d\/([a-zA-Z0-9_-]+)/',
            '/[?&]id=([a-zA-Z0-9_-]+)/',
            '/\/d\/([a-zA-Z0-9_-]+)/',
        ];

        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $url, $matches)) {
                return $matches[1];
            }
        }

        return null;
    }
}