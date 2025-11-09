@extends('layouts.admin')

@section('title', 'Certificate Detail')

@section('page-title', '🏆 Certificate Detail')

@section('content')
<div class="content-card">
    <div class="card-header">
        <h3>Certificate Information</h3>
    </div>
    <div class="card-body">
        <div style="text-align: center; padding: 40px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 12px; margin-bottom: 30px;">
            <div style="font-size: 4rem; color: white; margin-bottom: 20px;">
                <i class="fas fa-certificate"></i>
            </div>
            <h2 style="color: white; font-size: 2rem; margin-bottom: 10px;">Certificate of Completion</h2>
            <p style="color: rgba(255,255,255,0.9); font-size: 1.2rem; font-weight: 600; letter-spacing: 2px;">
                {{ $certificate->certificate_number }}
            </p>
        </div>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-bottom: 30px;">
            <div>
                <label style="display: block; color: var(--gray); font-size: 0.9rem; margin-bottom: 5px;">Student Name</label>
                <p style="font-size: 1.2rem; font-weight: 600; color: var(--dark);">{{ $certificate->student->name }}</p>
            </div>
            <div>
                <label style="display: block; color: var(--gray); font-size: 0.9rem; margin-bottom: 5px;">Email</label>
                <p style="font-size: 1rem; font-weight: 600; color: var(--dark);">{{ $certificate->student->email }}</p>
            </div>
        </div>

        <div style="margin-bottom: 30px; padding: 20px; background: #f7fafc; border-radius: 12px;">
            <label style="display: block; color: var(--gray); font-size: 0.9rem; margin-bottom: 10px;">Course</label>
            <p style="font-size: 1.3rem; font-weight: 600; color: var(--dark); margin-bottom: 5px;">{{ $certificate->course->title }}</p>
            <p style="font-size: 1rem; color: var(--gray);">Instructor: {{ $certificate->course->instructor->name }}</p>
        </div>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 30px;">
            <div>
                <label style="display: block; color: var(--gray); font-size: 0.9rem; margin-bottom: 5px;">Issued Date</label>
                <p style="font-size: 1.1rem; font-weight: 600; color: var(--dark);">{{ $certificate->certificate_issued_at->format('d F Y') }}</p>
            </div>
            <div>
                <label style="display: block; color: var(--gray); font-size: 0.9rem; margin-bottom: 5px;">Completed Date</label>
                <p style="font-size: 1.1rem; font-weight: 600; color: var(--dark);">{{ $certificate->completed_at->format('d F Y') }}</p>
            </div>
            <div>
                <label style="display: block; color: var(--gray); font-size: 0.9rem; margin-bottom: 5px;">Progress</label>
                <p style="font-size: 1.1rem; font-weight: 600; color: var(--success);">100% Completed</p>
            </div>
        </div>

        <div style="display: flex; gap: 10px; padding-top: 20px; border-top: 1px solid #e2e8f0;">
            <a href="{{ route('edutech.admin.certificates.download', $certificate->id) }}" 
                style="background: var(--success); color: white; padding: 12px 24px; border-radius: 8px; text-decoration: none; font-weight: 600; display: inline-flex; align-items: center; gap: 8px;">
                <i class="fas fa-download"></i> Download PDF
            </a>
            <form action="{{ route('edutech.admin.certificates.revoke', $certificate->id) }}" method="POST" style="display: inline;">
                @csrf
                <button type="submit" onclick="return confirm('Are you sure you want to revoke this certificate?')"
                    style="background: var(--danger); color: white; padding: 12px 24px; border-radius: 8px; border: none; font-weight: 600; cursor: pointer; display: inline-flex; align-items: center; gap: 8px;">
                    <i class="fas fa-ban"></i> Revoke Certificate
                </button>
            </form>
            <a href="{{ route('edutech.admin.certificates') }}" 
                style="background: var(--gray); color: white; padding: 12px 24px; border-radius: 8px; text-decoration: none; font-weight: 600; display: inline-flex; align-items: center; gap: 8px;">
                <i class="fas fa-arrow-left"></i> Back to List
            </a>
        </div>
    </div>
</div>
@endsection