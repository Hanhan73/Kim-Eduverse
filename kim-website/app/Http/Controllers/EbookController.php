<?php

namespace App\Http\Controllers;

use App\Models\EbookAccess;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class EbookController extends Controller
{
    /**
     * View e-book dengan token akses
     */
    public function view(Request $request, $token)
    {
        $access = EbookAccess::where('access_token', $token)
            ->with(['product'])
            ->firstOrFail();

        // Check if access is valid
        if (!$access->isValid()) {
            if ($access->isExpired()) {
                return view('ebook.expired', compact('access'));
            }
            
            abort(403, 'Akses tidak valid');
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
        $access = EbookAccess::where('access_token', $token)->firstOrFail();

        if (!$access->isValid()) {
            abort(403, 'Access expired or invalid');
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
     * Extract Google Drive file ID from URL
     */
    private function extractGoogleDriveFileId($url)
    {
        // Pattern: https://drive.google.com/file/d/{FILE_ID}/view
        // Pattern: https://drive.google.com/open?id={FILE_ID}
        // Pattern: https://drive.google.com/uc?id={FILE_ID}
        
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