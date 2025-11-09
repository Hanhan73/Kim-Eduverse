<?php

namespace App\Http\Controllers\Edutech\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class SettingsController extends Controller
{
    public function index()
    {
        $settings = [
            'site_name' => config('app.name', 'KIM Edutech'),
            'site_description' => 'Platform pembelajaran online terlengkap',
            'contact_email' => 'admin@kimedutech.com',
            'contact_phone' => '+62 123 456 7890',
            'facebook_url' => 'https://facebook.com/kimedutech',
            'twitter_url' => 'https://twitter.com/kimedutech',
            'instagram_url' => 'https://instagram.com/kimedutech',
            'linkedin_url' => 'https://linkedin.com/company/kimedutech',
            'enable_enrollment_approval' => false,
            'enable_certificates' => true,
            'max_upload_size' => '10MB',
            'default_course_price' => 0,
        ];

        $stats = [
            'total_users' => \App\Models\User::count(),
            'total_courses' => \App\Models\Course::count(),
            'total_enrollments' => \App\Models\Enrollment::count(),
            'storage_used' => '0 MB',
        ];

        return view('edutech.admin.settings.index', compact('settings', 'stats'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'site_name' => 'required|string|max:255',
            'site_description' => 'nullable|string',
            'contact_email' => 'required|email',
            'contact_phone' => 'nullable|string',
            'facebook_url' => 'nullable|url',
            'twitter_url' => 'nullable|url',
            'instagram_url' => 'nullable|url',
            'linkedin_url' => 'nullable|url',
            'enable_enrollment_approval' => 'boolean',
            'enable_certificates' => 'boolean',
            'max_upload_size' => 'nullable|string',
            'default_course_price' => 'nullable|numeric|min:0',
        ]);

        Cache::put('site_settings', $validated, now()->addYears(1));

        return redirect()->back()
            ->with('success', 'Settings updated successfully!');
    }

    public function clearCache()
    {
        Cache::flush();

        return redirect()->back()
            ->with('success', 'Cache cleared successfully!');
    }

    public function maintenance()
    {
        if (app()->isDownForMaintenance()) {
            \Artisan::call('up');
            $message = 'Maintenance mode disabled';
        } else {
            \Artisan::call('down');
            $message = 'Maintenance mode enabled';
        }

        return redirect()->back()
            ->with('success', $message);
    }
}