@extends('layouts.student')

@section('title', 'Certificates Student')

@section('content')

<!-- Main Content -->
<main class="main-content">
    <!-- Top Bar -->
    <div class="top-bar">
        <h1>🏆 My Certificates</h1>
        <div class="user-info">
            <div class="user-avatar">
                {{ strtoupper(substr(session('edutech_user_name'), 0, 1)) }}
            </div>
            <form action="{{ route('edutech.logout') }}" method="POST" style="display: inline;">
                @csrf
                <button type="submit" class="btn-logout">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </button>
            </form>
        </div>
    </div>

    @if($certificates->count() > 0)
    <!-- Stats Banner -->
    <div class="stats-banner">
        <i class="fas fa-trophy"></i>
        <h2>{{ $certificates->count() }}</h2>
        <p>Certificates Earned</p>
    </div>

    <!-- Certificates Grid -->
    <div class="certificates-grid">
        @foreach($certificates as $certificate)
        <div class="certificate-card">
            <span class="verified-badge">
                <i class="fas fa-check-circle"></i>
                Verified
            </span>

            <div class="certificate-header">
                <div class="certificate-icon">
                    <i class="fas fa-award"></i>
                </div>
                <div class="certificate-number">
                    Certificate No: {{ $certificate->certificate_number }}
                </div>
            </div>

            <div class="certificate-body">
                <h3 class="certificate-course">{{ $certificate->course->title }}</h3>

                <div class="certificate-meta">
                    <div class="certificate-info">
                        <i class="fas fa-calendar-check"></i>
                        <span>Issued: {{ $certificate->issued_at->format('d F Y') }}</span>
                    </div>
                    <div class="certificate-info">
                        <i class="fas fa-chalkboard-teacher"></i>
                        <span>Instructor: {{ $certificate->course->instructor->name }}</span>
                    </div>
                    <div class="certificate-info">
                        <i class="fas fa-tag"></i>
                        <span>Category: {{ $certificate->course->category }}</span>
                    </div>
                </div>

                <div class="certificate-actions">
                    <a href="{{ route('edutech.student.certificate.download', $certificate->id) }}"
                        class="btn-download">
                        <i class="fas fa-download"></i>
                        Download PDF
                    </a>
                    <a href="#" class="btn-share" title="Share Certificate">
                        <i class="fas fa-share-alt"></i>
                    </a>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @else
    <!-- Empty State -->
    <div class="empty-state">
        <i class="fas fa-certificate"></i>
        <h3>Belum Ada Sertifikat</h3>
        <p>Selesaikan course dan dapatkan sertifikat profesional yang diakui industri!</p>
        <a href="{{ route('edutech.student.my-courses') }}" class="btn-primary">
            <i class="fas fa-book"></i> Lihat My Courses
        </a>
    </div>
    @endif
</main>
@endsection