@extends('layouts.admin-digital')
@section('title', 'Detail Seminar')
@section('page-title', $seminar->title)

@section('content')
<div class="page-header">
    <div>
        <a href="{{ route('admin.digital.seminars.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>
    <div style="display: flex; gap: 10px;">
        <form action="{{ route('admin.digital.seminars.toggle-featured', $seminar) }}" method="POST"
            style="display: inline;">
            @csrf
            @method('PATCH')
            <button type="submit" class="btn {{ $seminar->is_featured ? 'btn-warning' : 'btn-secondary' }}">
                <i class="fas fa-star"></i> {{ $seminar->is_featured ? 'Unfeatured' : 'Set Featured' }}
            </button>
        </form>
        <a href="{{ route('admin.digital.seminars.edit', $seminar) }}" class="btn btn-primary">
            <i class="fas fa-edit"></i> Edit
        </a>
    </div>
</div>

<!-- Stats -->
<div class="stats-grid" style="margin-bottom: 25px;">
    <div class="stat-card">
        <div class="stat-icon primary">
            <i class="fas fa-users"></i>
        </div>
        <div class="stat-content">
            <h4>{{ $stats['total_enrollments'] }}</h4>
            <p>Total Peserta</p>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon success">
            <i class="fas fa-check-circle"></i>
        </div>
        <div class="stat-content">
            <h4>{{ $stats['completed'] }}</h4>
            <p>Selesai</p>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon warning">
            <i class="fas fa-hourglass-half"></i>
        </div>
        <div class="stat-content">
            <h4>{{ $stats['in_progress'] }}</h4>
            <p>Sedang Berjalan</p>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon info">
            <i class="fas fa-chart-line"></i>
        </div>
        <div class="stat-content">
            <h4>{{ $stats['avg_pre_test'] }}%</h4>
            <p>Avg Pre-Test</p>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon info">
            <i class="fas fa-chart-line"></i>
        </div>
        <div class="stat-content">
            <h4>{{ $stats['avg_post_test'] }}%</h4>
            <p>Avg Post-Test</p>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon success">
            <i class="fas fa-money-bill-wave"></i>
        </div>
        <div class="stat-content">
            <h4>Rp {{ number_format($seminar->price * $seminar->sold_count, 0, ',', '.') }}</h4>
            <p>Total Revenue</p>
        </div>
    </div>
</div>

<div style="display: grid; grid-template-columns: 2fr 1fr; gap: 25px;">
    <!-- Main Content -->
    <div>
        <!-- Seminar Info -->
        <div class="card">
            <div class="card-header">
                <h3><i class="fas fa-info-circle"></i> Informasi Seminar</h3>
            </div>
            <div class="card-body">
                @if($seminar->thumbnail)
                <div style="margin-bottom: 20px;">
                    <img src="{{ Storage::url($seminar->thumbnail) }}"
                        style="width: 100%; max-height: 300px; object-fit: cover; border-radius: 12px;"
                        alt="{{ $seminar->title }}">
                </div>
                @endif

                <div class="info-row">
                    <strong>Judul:</strong>
                    <span>{{ $seminar->title }}</span>
                </div>

                <div class="info-row">
                    <strong>Slug:</strong>
                    <span><code>{{ $seminar->slug }}</code></span>
                </div>

                <div class="info-row">
                    <strong>Deskripsi:</strong>
                    <span>{{ $seminar->description }}</span>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-top: 15px;">
                    <div class="info-box">
                        <i class="fas fa-money-bill-wave"></i>
                        <div>
                            <strong>{{ $seminar->formatted_price }}</strong>
                            <span>Harga</span>
                        </div>
                    </div>

                    <div class="info-box">
                        <i class="fas fa-clock"></i>
                        <div>
                            <strong>{{ $seminar->duration_minutes }} menit</strong>
                            <span>Durasi</span>
                        </div>
                    </div>
                </div>

                <div style="display: flex; gap: 10px; margin-top: 15px;">
                    @if($seminar->is_active)
                    <span class="badge badge-success"><i class="fas fa-check"></i> Aktif</span>
                    @else
                    <span class="badge badge-danger"><i class="fas fa-times"></i> Nonaktif</span>
                    @endif

                    @if($seminar->is_featured)
                    <span class="badge badge-warning"><i class="fas fa-star"></i> Featured</span>
                    @endif

                    <span class="badge badge-info">{{ $seminar->sold_count }} terjual</span>
                </div>
            </div>
        </div>

        <!-- Instructor -->
        <div class="card">
            <div class="card-header">
                <h3><i class="fas fa-user-tie"></i> Instruktur</h3>
            </div>
            <div class="card-body">
                <div class="info-row">
                    <strong>Nama:</strong>
                    <span>{{ $seminar->instructor_name }}</span>
                </div>

                @if($seminar->instructor_bio)
                <div class="info-row">
                    <strong>Bio:</strong>
                    <span>{{ $seminar->instructor_bio }}</span>
                </div>
                @endif
            </div>
        </div>

        <!-- Material -->
        <div class="card">
            <div class="card-header">
                <h3><i class="fas fa-file-pdf"></i> Materi</h3>
            </div>
            <div class="card-body">
                @if($seminar->material_pdf_path)
                <div class="info-row">
                    <strong>Link Materi:</strong>
                    <a href="{{ $seminar->material_pdf_path }}" target="_blank" style="color: var(--primary);">
                        <i class="fas fa-external-link-alt"></i> Buka Google Drive
                    </a>
                </div>
                @endif

                @if($seminar->material_description)
                <div class="info-row">
                    <strong>Deskripsi:</strong>
                    <span>{{ $seminar->material_description }}</span>
                </div>
                @endif
            </div>
        </div>

        <!-- Tests -->
        <div class="card">
            <div class="card-header">
                <h3><i class="fas fa-clipboard-check"></i> Pre-Test & Post-Test</h3>
            </div>
            <div class="card-body">
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                    <!-- Pre Test -->
                    <div style="background: #f0f9ff; border: 2px solid #0284c7; border-radius: 10px; padding: 20px;">
                        <h4 style="margin-bottom: 15px; color: #0c4a6e;">
                            <i class="fas fa-clipboard-list"></i> Pre-Test
                        </h4>
                        @if($seminar->preTest)
                        <div style="margin-bottom: 10px;">
                            <strong>{{ $seminar->preTest->title }}</strong>
                        </div>
                        <div style="display: flex; gap: 15px; font-size: 0.9rem; color: #0c4a6e;">
                            <span><i class="fas fa-question-circle"></i> {{ $seminar->preTest->questions->count() }}
                                soal</span>
                            <span><i class="fas fa-clock"></i> {{ $seminar->preTest->duration_minutes }} menit</span>
                        </div>
                        <div style="margin-top: 10px;">
                            <span class="badge badge-info">Passing: {{ $seminar->preTest->passing_score }}%</span>
                        </div>
                        @else
                        <p style="color: #64748b;">Belum ada pre-test</p>
                        @endif
                    </div>

                    <!-- Post Test -->
                    <div style="background: #f0fdf4; border: 2px solid #22c55e; border-radius: 10px; padding: 20px;">
                        <h4 style="margin-bottom: 15px; color: #166534;">
                            <i class="fas fa-clipboard-check"></i> Post-Test
                        </h4>
                        @if($seminar->postTest)
                        <div style="margin-bottom: 10px;">
                            <strong>{{ $seminar->postTest->title }}</strong>
                        </div>
                        <div style="display: flex; gap: 15px; font-size: 0.9rem; color: #166534;">
                            <span><i class="fas fa-question-circle"></i> {{ $seminar->postTest->questions->count() }}
                                soal</span>
                            <span><i class="fas fa-clock"></i> {{ $seminar->postTest->duration_minutes }} menit</span>
                        </div>
                        <div style="margin-top: 10px;">
                            <span class="badge badge-success">Passing: {{ $seminar->postTest->passing_score }}%</span>
                        </div>
                        @else
                        <p style="color: #64748b;">Belum ada post-test</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Enrollments -->
        <div class="card">
            <div class="card-header">
                <h3><i class="fas fa-users"></i> Peserta Terbaru</h3>
                <a href="{{ route('admin.digital.seminars.enrollments', $seminar) }}" class="btn btn-sm btn-primary">
                    Lihat Semua
                </a>
            </div>
            <div class="card-body" style="padding: 0;">
                @if($seminar->enrollments->count() > 0)
                <div class="table-responsive">
                    <table>
                        <thead>
                            <tr>
                                <th>Email</th>
                                <th>Progress</th>
                                <th>Pre-Test</th>
                                <th>Post-Test</th>
                                <th>Status</th>
                                <th>Tanggal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($seminar->enrollments->take(5) as $enrollment)
                            <tr>
                                <td>{{ $enrollment->customer_email }}</td>
                                <td>
                                    <div style="display: flex; align-items: center; gap: 10px;">
                                        <div
                                            style="flex: 1; background: #e2e8f0; border-radius: 10px; height: 8px; overflow: hidden;">
                                            <div
                                                style="background: linear-gradient(90deg, var(--primary), var(--secondary)); height: 100%; width: {{ $enrollment->progress_percentage }}%;">
                                            </div>
                                        </div>
                                        <span
                                            style="font-weight: 600; color: var(--primary);">{{ $enrollment->progress_percentage }}%</span>
                                    </div>
                                </td>
                                <td>
                                    @if($enrollment->pre_test_passed)
                                    <span class="badge badge-success">{{ round($enrollment->pre_test_score) }}%</span>
                                    @else
                                    <span class="badge badge-secondary">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if($enrollment->post_test_passed)
                                    <span class="badge badge-success">{{ round($enrollment->post_test_score) }}%</span>
                                    @else
                                    <span class="badge badge-secondary">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if($enrollment->is_completed)
                                    <span class="badge badge-success">Selesai</span>
                                    @else
                                    <span class="badge badge-warning">Progress</span>
                                    @endif
                                </td>
                                <td>{{ $enrollment->created_at->format('d M Y') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="empty-state">
                    <i class="fas fa-users"></i>
                    <h3>Belum Ada Peserta</h3>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Sidebar -->
    <div>
        <!-- Certificate -->
        <div class="card">
            <div class="card-header">
                <h3><i class="fas fa-certificate"></i> Sertifikat</h3>
            </div>
            <div class="card-body">
                <div class="info-row">
                    <strong>Template:</strong>
                    <span>{{ $seminar->certificate_template ?? 'Default' }}</span>
                </div>

                <div
                    style="background: #fef3c7; border: 2px solid #fbbf24; border-radius: 8px; padding: 15px; margin-top: 15px;">
                    <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 10px;">
                        <i class="fas fa-certificate" style="color: #92400e; font-size: 1.5rem;"></i>
                        <strong style="color: #92400e;">Sertifikat Diterbitkan</strong>
                    </div>
                    <div style="font-size: 2rem; font-weight: 700; color: #92400e;">
                        {{ $stats['completed'] }}
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="card">
            <div class="card-header">
                <h3><i class="fas fa-bolt"></i> Quick Actions</h3>
            </div>
            <div class="card-body">
                <a href="{{ route('admin.digital.seminars.enrollments', $seminar) }}" class="btn btn-primary btn-block">
                    <i class="fas fa-users"></i> Lihat Semua Peserta
                </a>

                <a href="{{ route('digital.catalog') }}" target="_blank" class="btn btn-secondary btn-block">
                    <i class="fas fa-external-link-alt"></i> Preview di Katalog
                </a>

                <form action="{{ route('admin.digital.seminars.toggle-active', $seminar) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <button type="submit"
                        class="btn {{ $seminar->is_active ? 'btn-danger' : 'btn-success' }} btn-block">
                        <i class="fas fa-power-off"></i> {{ $seminar->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                    </button>
                </form>
            </div>
        </div>

        <!-- Meta Info -->
        <div class="card">
            <div class="card-header">
                <h3><i class="fas fa-info-circle"></i> Meta Info</h3>
            </div>
            <div class="card-body">
                <div class="info-row">
                    <strong>Dibuat:</strong>
                    <span>{{ $seminar->created_at->format('d M Y H:i') }}</span>
                </div>

                <div class="info-row">
                    <strong>Terakhir Update:</strong>
                    <span>{{ $seminar->updated_at->format('d M Y H:i') }}</span>
                </div>

                <div class="info-row">
                    <strong>Urutan:</strong>
                    <span>{{ $seminar->order }}</span>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .info-row {
        display: flex;
        justify-content: space-between;
        padding: 12px 0;
        border-bottom: 1px solid #e2e8f0;
    }

    .info-row:last-child {
        border-bottom: none;
    }

    .info-box {
        background: #f8f9fa;
        border-radius: 10px;
        padding: 15px;
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .info-box i {
        font-size: 2rem;
        color: var(--primary);
    }

    .info-box strong {
        display: block;
        font-size: 1.3rem;
        color: var(--dark);
    }

    .info-box span {
        display: block;
        font-size: 0.85rem;
        color: var(--gray);
    }
</style>
@endsection