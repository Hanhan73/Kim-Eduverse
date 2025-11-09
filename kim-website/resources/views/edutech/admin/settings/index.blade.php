@extends('layouts.admin')

@section('title', 'Settings')

@section('page-title', '⚙️ Settings')

@section('content')
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon purple">
            <i class="fas fa-users"></i>
        </div>
        <div class="stat-content">
            <h3>{{ $stats['total_users'] ?? 0 }}</h3>
            <p>Total Users</p>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon success">
            <i class="fas fa-book"></i>
        </div>
        <div class="stat-content">
            <h3>{{ $stats['total_courses'] ?? 0 }}</h3>
            <p>Total Courses</p>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon warning">
            <i class="fas fa-user-graduate"></i>
        </div>
        <div class="stat-content">
            <h3>{{ $stats['total_enrollments'] ?? 0 }}</h3>
            <p>Total Enrollments</p>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon info">
            <i class="fas fa-database"></i>
        </div>
        <div class="stat-content">
            <h3>{{ $stats['storage_used'] ?? '0 MB' }}</h3>
            <p>Storage Used</p>
        </div>
    </div>
</div>

<div class="content-card">
    <div class="card-header">
        <h3>Site Settings</h3>
    </div>
    <div class="card-body">
        <form action="{{ route('edutech.admin.settings.update') }}" method="POST">
            @csrf
            @method('PUT')

            <div style="margin-bottom: 30px;">
                <h4 style="color: var(--dark); font-size: 1.2rem; margin-bottom: 20px; padding-bottom: 10px; border-bottom: 2px solid var(--primary);">
                    <i class="fas fa-info-circle"></i> General Information
                </h4>
                <div style="display: grid; gap: 20px;">
                    <div>
                        <label style="display: block; color: var(--dark); font-weight: 600; margin-bottom: 8px;">
                            Site Name <span style="color: var(--danger);">*</span>
                        </label>
                        <input type="text" name="site_name" value="{{ old('site_name', $settings['site_name']) }}" required
                            style="width: 100%; padding: 12px 15px; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 1rem;">
                    </div>

                    <div>
                        <label style="display: block; color: var(--dark); font-weight: 600; margin-bottom: 8px;">
                            Site Description
                        </label>
                        <textarea name="site_description" rows="3"
                            style="width: 100%; padding: 12px 15px; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 1rem; resize: vertical;">{{ old('site_description', $settings['site_description']) }}</textarea>
                    </div>
                </div>
            </div>

            <div style="margin-bottom: 30px;">
                <h4 style="color: var(--dark); font-size: 1.2rem; margin-bottom: 20px; padding-bottom: 10px; border-bottom: 2px solid var(--primary);">
                    <i class="fas fa-address-book"></i> Contact Information
                </h4>
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px;">
                    <div>
                        <label style="display: block; color: var(--dark); font-weight: 600; margin-bottom: 8px;">
                            Contact Email <span style="color: var(--danger);">*</span>
                        </label>
                        <input type="email" name="contact_email" value="{{ old('contact_email', $settings['contact_email']) }}" required
                            style="width: 100%; padding: 12px 15px; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 1rem;">
                    </div>

                    <div>
                        <label style="display: block; color: var(--dark); font-weight: 600; margin-bottom: 8px;">
                            Contact Phone
                        </label>
                        <input type="text" name="contact_phone" value="{{ old('contact_phone', $settings['contact_phone']) }}"
                            style="width: 100%; padding: 12px 15px; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 1rem;">
                    </div>
                </div>
            </div>

            <div style="margin-bottom: 30px;">
                <h4 style="color: var(--dark); font-size: 1.2rem; margin-bottom: 20px; padding-bottom: 10px; border-bottom: 2px solid var(--primary);">
                    <i class="fas fa-share-alt"></i> Social Media Links
                </h4>
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px;">
                    <div>
                        <label style="display: block; color: var(--dark); font-weight: 600; margin-bottom: 8px;">
                            <i class="fab fa-facebook" style="color: #1877f2;"></i> Facebook URL
                        </label>
                        <input type="url" name="facebook_url" value="{{ old('facebook_url', $settings['facebook_url']) }}"
                            style="width: 100%; padding: 12px 15px; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 1rem;">
                    </div>

                    <div>
                        <label style="display: block; color: var(--dark); font-weight: 600; margin-bottom: 8px;">
                            <i class="fab fa-twitter" style="color: #1da1f2;"></i> Twitter URL
                        </label>
                        <input type="url" name="twitter_url" value="{{ old('twitter_url', $settings['twitter_url']) }}"
                            style="width: 100%; padding: 12px 15px; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 1rem;">
                    </div>

                    <div>
                        <label style="display: block; color: var(--dark); font-weight: 600; margin-bottom: 8px;">
                            <i class="fab fa-instagram" style="color: #e4405f;"></i> Instagram URL
                        </label>
                        <input type="url" name="instagram_url" value="{{ old('instagram_url', $settings['instagram_url']) }}"
                            style="width: 100%; padding: 12px 15px; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 1rem;">
                    </div>

                    <div>
                        <label style="display: block; color: var(--dark); font-weight: 600; margin-bottom: 8px;">
                            <i class="fab fa-linkedin" style="color: #0a66c2;"></i> LinkedIn URL
                        </label>
                        <input type="url" name="linkedin_url" value="{{ old('linkedin_url', $settings['linkedin_url']) }}"
                            style="width: 100%; padding: 12px 15px; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 1rem;">
                    </div>
                </div>
            </div>

            <div style="margin-bottom: 30px;">
                <h4 style="color: var(--dark); font-size: 1.2rem; margin-bottom: 20px; padding-bottom: 10px; border-bottom: 2px solid var(--primary);">
                    <i class="fas fa-cogs"></i> System Preferences
                </h4>
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px;">
                    <div>
                        <label style="display: block; color: var(--dark); font-weight: 600; margin-bottom: 8px;">
                            Max Upload Size
                        </label>
                        <input type="text" name="max_upload_size" value="{{ old('max_upload_size', $settings['max_upload_size']) }}"
                            style="width: 100%; padding: 12px 15px; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 1rem;">
                    </div>

                    <div>
                        <label style="display: block; color: var(--dark); font-weight: 600; margin-bottom: 8px;">
                            Default Course Price (Rp)
                        </label>
                        <input type="number" name="default_course_price" value="{{ old('default_course_price', $settings['default_course_price']) }}" min="0"
                            style="width: 100%; padding: 12px 15px; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 1rem;">
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px; margin-top: 20px;">
                    <div style="display: flex; align-items: center; gap: 10px; padding: 12px; background: #f7fafc; border-radius: 8px;">
                        <input type="checkbox" name="enable_enrollment_approval" value="1" {{ $settings['enable_enrollment_approval'] ? 'checked' : '' }}
                            style="width: 20px; height: 20px; cursor: pointer;">
                        <span style="color: var(--dark); font-weight: 500;">Enable Enrollment Approval</span>
                    </div>

                    <div style="display: flex; align-items: center; gap: 10px; padding: 12px; background: #f7fafc; border-radius: 8px;">
                        <input type="checkbox" name="enable_certificates" value="1" {{ $settings['enable_certificates'] ? 'checked' : '' }}
                            style="width: 20px; height: 20px; cursor: pointer;">
                        <span style="color: var(--dark); font-weight: 500;">Enable Certificates</span>
                    </div>
                </div>
            </div>

            <div style="display: flex; gap: 10px; padding-top: 20px; border-top: 1px solid #e2e8f0;">
                <button type="submit" style="background: var(--primary); color: white; padding: 12px 30px; border-radius: 8px; border: none; font-weight: 600; cursor: pointer; font-size: 1rem;">
                    <i class="fas fa-save"></i> Save Settings
                </button>
            </div>
        </form>
    </div>
</div>

<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 25px;">
    <div class="content-card">
        <div class="card-header">
            <h3>System Maintenance</h3>
        </div>
        <div class="card-body">
            <p style="color: var(--gray); margin-bottom: 20px;">Clear application cache to improve performance</p>
            <form action="{{ route('edutech.admin.settings.clear-cache') }}" method="POST">
                @csrf
                <button type="submit" style="background: var(--warning); color: white; padding: 12px 24px; border-radius: 8px; border: none; font-weight: 600; cursor: pointer; width: 100%;">
                    <i class="fas fa-trash-alt"></i> Clear Cache
                </button>
            </form>
        </div>
    </div>

    <div class="content-card">
        <div class="card-header">
            <h3>Maintenance Mode</h3>
        </div>
        <div class="card-body">
            <p style="color: var(--gray); margin-bottom: 20px;">Put the site in maintenance mode</p>
            <form action="{{ route('edutech.admin.settings.maintenance') }}" method="POST">
                @csrf
                <button type="submit" style="background: var(--danger); color: white; padding: 12px 24px; border-radius: 8px; border: none; font-weight: 600; cursor: pointer; width: 100%;">
                    <i class="fas fa-tools"></i> Toggle Maintenance
                </button>
            </form>
        </div>
    </div>
</div>
@endsection