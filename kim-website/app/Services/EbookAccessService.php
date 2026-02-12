<?php

namespace App\Services;

use App\Models\EbookAccess;
use App\Models\DigitalProduct;
use App\Models\DigitalOrder;
use App\Models\User;
use App\Mail\EbookAccessMail;
use Illuminate\Support\Facades\Mail;

class EbookAccessService
{
    /**
     * Create ebook access for user after purchase
     */
    public function createAccessForOrder(DigitalOrder $order)
    {
        \Log::info('=== CREATE EBOOK ACCESS START ===', ['order_id' => $order->id]);
        
        $ebookItems = $order->items()->whereHas('product', function($q) {
            $q->where('type', 'ebook');
        })->get();

        \Log::info('Found ebook items to process', ['count' => $ebookItems->count()]);

        foreach ($ebookItems as $item) {
            $product = $item->product;
            
            \Log::info('Processing ebook item', [
                'product_id' => $product->id,
                'product_name' => $product->name
            ]);
            
            // Hitung expiry date berdasarkan setting produk
            $expiresAt = now()->addDays($product->ebook_access_duration_days ?? 90);

            // Buat access token
            $access = EbookAccess::create([
                'product_id' => $product->id,
                'customer_email' => $order->customer_email,                
                'order_id' => $order->id,
                'expires_at' => $expiresAt,
                'is_active' => true,
            ]);

            \Log::info('Ebook access created', [
                'access_id' => $access->id,
                'token' => $access->access_token,
                'expires_at' => $access->expires_at
            ]);

            // Kirim email dengan link akses
            $this->sendAccessEmail($access, $order->customer_email);
        }
        
        \Log::info('=== CREATE EBOOK ACCESS COMPLETE ===');
    }

    /**
     * Send access email to customer
     */
    public function sendAccessEmail(EbookAccess $access, $email)
    {
        try {
            \Log::info('Sending ebook access email', [
                'access_id' => $access->id,
                'email' => $email,
                'product' => $access->product->name
            ]);
            
            Mail::to($email)->send(new EbookAccessMail($access));
            
            \Log::info('Ebook access email sent successfully');
        } catch (\Exception $e) {
            \Log::error('Failed to send ebook access email', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            throw $e; // Re-throw untuk debugging
        }
    }

    /**
     * Extend ebook access
     */
    public function extendAccess(EbookAccess $access, int $days)
    {
        $access->extend($days);
        
        // Kirim email notifikasi perpanjangan
        $this->sendExtensionEmail($access);
    }

    /**
     * Revoke ebook access
     */
    public function revokeAccess(EbookAccess $access)
    {
        $access->revoke();
    }

    /**
     * Get all accesses for a product
     */
    public function getProductAccesses(DigitalProduct $product)
    {
        return EbookAccess::where('product_id', $product->id)
            ->with(['user', 'order'])
            ->latest()
            ->get();
    }

    /**
     * Get active accesses for a user
     */
    public function getUserActiveAccesses(User $user)
    {
        return EbookAccess::where('user_id', $user->id)
            ->where('is_active', true)
            ->where('expires_at', '>', now())
            ->with('product')
            ->get();
    }

    /**
     * Check if user has access to ebook
     */
    public function userHasAccess(User $user, DigitalProduct $product): bool
    {
        return EbookAccess::where('user_id', $user->id)
            ->where('product_id', $product->id)
            ->where('is_active', true)
            ->where('expires_at', '>', now())
            ->exists();
    }

    /**
     * Send extension notification email
     */
    private function sendExtensionEmail(EbookAccess $access)
    {
        // You can create a separate mail class for this
        Mail::to($access->order->customer_email)->send(
            new \App\Mail\EbookAccessExtendedMail($access)
        );
    }

    /**
     * Send expiry reminder email (can be called by scheduled task)
     */
    public function sendExpiryReminders()
    {
        // Get accesses expiring in 7 days
        $expiringAccesses = EbookAccess::where('is_active', true)
            ->whereBetween('expires_at', [now(), now()->addDays(7)])
            ->with(['product', 'order'])
            ->get();

        foreach ($expiringAccesses as $access) {
            Mail::to($access->order->customer_email)->send(
                new \App\Mail\EbookAccessExpiryReminderMail($access)
            );
        }
    }
}