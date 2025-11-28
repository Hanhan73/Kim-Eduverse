@extends('admin.digital.layouts.app')

@section('title', 'Detail Angket - Admin Digital')
@section('page-title', 'Detail Angket')

@section('content')
<div style="margin-bottom: 20px; display: flex; justify-content: space-between; align-items: center;">
    <a href="{{ route('admin.digital.questionnaires.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Kembali
    </a>
    <div style="display: flex; gap: 10px;">
        <a href="{{ route('admin.digital.questionnaires.edit', $questionnaire->id) }}" class="btn btn-primary">
            <i class="fas fa-edit"></i> Edit Angket
        </a>
        <a href="{{ route('admin.digital.questions.index', ['questionnaire_id' => $questionnaire->id]) }}" class="btn btn-warning">
            <i class="fas fa-question-circle"></i> Kelola Pertanyaan
        </a>
    </div>
</div>

<!-- Stats -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon primary">
            <i class="fas fa-layer-group"></i>
        </div>
        <div class="stat-content">
            <h4>{{ $questionnaire->dimensions->count() }}</h4>
            <p>Dimensi</p>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon info">
            <i class="fas fa-question-circle"></i>
        </div>
        <div class="stat-content">
            <h4>{{ $questionnaire->questions->count() }}</h4>
            <p>Pertanyaan</p>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon success">
            <i class="fas fa-check-circle"></i>
        </div>
        <div class="stat-content">
            <h4>{{ $responseStats['completed'] }}</h4>
            <p>Respons Selesai</p>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon warning">
            <i class="fas fa-clock"></i>
        </div>
        <div class="stat-content">
            <h4>{{ $responseStats['pending'] }}</h4>
            <p>Respons Pending</p>
        </div>
    </div>
</div>

<div style="display: grid; grid-template-columns: 2fr 1fr; gap: 25px;">
    <!-- Main Info -->
    <div>
        <!-- Basic Info -->
        <div class="card" style="margin-bottom: 25px;">
            <div class="card-header">
                <h3><i class="fas fa-clipboard-list"></i> Informasi Angket</h3>
                <span class="badge {{ $questionnaire->is_active ? 'badge-success' : 'badge-danger' }}">
                    {{ $questionnaire->is_active ? 'Aktif' : 'Nonaktif' }}
                </span>
            </div>
            <div class="card-body">
                <h2 style="margin-bottom: 15px; color: var(--dark);">{{ $questionnaire->name }}</h2>
                
                <div style="display: flex; gap: 10px; margin-bottom: 20px;">
                    <span class="badge badge-info">{{ ucfirst($questionnaire->type) }}</span>
                    @if($questionnaire->duration_minutes)
                    <span class="badge badge-primary">{{ $questionnaire->duration_minutes }} menit</span>
                    @endif
                    @if($questionnaire->has_dimensions)
                    <span class="badge badge-success">Memiliki Dimensi</span>
                    @endif
                </div>

                <div style="margin-bottom: 20px;">
                    <h4 style="color: var(--gray); font-size: 0.9rem; margin-bottom: 5px;">Deskripsi</h4>
                    <p style="color: var(--dark); line-height: 1.6;">{{ $questionnaire->description }}</p>
                </div>

                @if($questionnaire->instructions)
                <div style="background: #f7fafc; padding: 15px; border-radius: 10px;">
                    <h4 style="color: var(--gray); font-size: 0.9rem; margin-bottom: 5px;">Petunjuk Pengisian</h4>
                    <p style="color: var(--dark); line-height: 1.6; margin: 0;">{{ $questionnaire->instructions }}</p>
                </div>
                @endif
            </div>
        </div>

        <!-- Dimensions -->
        @if($questionnaire->has_dimensions && $questionnaire->dimensions->count() > 0)
        <div class="card" style="margin-bottom: 25px;">
            <div class="card-header">
                <h3><i class="fas fa-layer-group"></i> Dimensi Pengukuran</h3>
                <a href="{{ route('admin.digital.dimensions.create', ['questionnaire_id' => $questionnaire->id]) }}" class="btn btn-sm btn-primary">
                    <i class="fas fa-plus"></i> Tambah
                </a>
            </div>
            <div class="card-body" style="padding: 0;">
                @foreach($questionnaire->dimensions as $dimension)
                <div style="padding: 20px; border-bottom: 1px solid #edf2f7;">
                    <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 10px;">
                        <div>
                            <h4 style="color: var(--dark); margin-bottom: 5px;">
                                {{ $dimension->order }}. {{ $dimension->name }}
                            </h4>
                            <span class="badge badge-info">{{ $dimension->code }}</span>
                            <span class="badge badge-primary">{{ $dimension->questions->count() }} pertanyaan</span>
                        </div>
                        <a href="{{ route('admin.digital.dimensions.edit', $dimension->id) }}" class="btn btn-sm btn-secondary">
                            <i class="fas fa-edit"></i>
                        </a>
                    </div>
                    @if($dimension->description)
                    <p style="color: var(--gray); font-size: 0.9rem; margin-bottom: 15px;">{{ $dimension->description }}</p>
                    @endif

                    <!-- Interpretations -->
                    <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 10px;">
                        @foreach(['low' => 'Rendah', 'medium' => 'Sedang', 'high' => 'Tinggi'] as $level => $label)
                        @if(isset($dimension->interpretations[$level]))
                        <div style="background: {{ $level == 'low' ? '#c6f6d5' : ($level == 'medium' ? '#feebc8' : '#fed7d7') }}; padding: 10px; border-radius: 8px;">
                            <strong style="font-size: 0.8rem;">{{ $label }}</strong>
                            <p style="font-size: 0.8rem; margin: 5px 0 0; color: var(--dark);">
                                {{ Str::limit($dimension->interpretations[$level]['description'] ?? '', 100) }}
                            </p>
                        </div>
                        @endif
                        @endforeach
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Questions -->
        <div class="card">
            <div class="card-header">
                <h3><i class="fas fa-question-circle"></i> Daftar Pertanyaan</h3>
                <a href="{{ route('admin.digital.questions.create', ['questionnaire_id' => $questionnaire->id]) }}" class="btn btn-sm btn-primary">
                    <i class="fas fa-plus"></i> Tambah
                </a>
            </div>
            <div class="card-body" style="padding: 0;">
                @if($questionnaire->questions->count() > 0)
                    @foreach($questionnaire->questions as $question)
                    <div style="padding: 15px 20px; border-bottom: 1px solid #edf2f7; display: flex; gap: 15px; align-items: start;">
                        <div style="background: var(--primary); color: white; width: 30px; height: 30px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 600; flex-shrink: 0;">
                            {{ $question->order }}
                        </div>
                        <div style="flex: 1;">
                            <p style="margin: 0 0 5px; color: var(--dark);">{{ $question->question_text }}</p>
                            <div style="display: flex; gap: 5px; flex-wrap: wrap;">
                                @if($question->dimension)
                                <span class="badge badge-primary">{{ $question->dimension->name }}</span>
                                @endif
                                @if($question->is_reverse_scored)
                                <span class="badge badge-warning">Reverse Scored</span>
                                @endif
                            </div>
                        </div>
                        <a href="{{ route('admin.digital.questions.edit', $question->id) }}" class="btn btn-sm btn-icon btn-secondary">
                            <i class="fas fa-edit"></i>
                        </a>
                    </div>
                    @endforeach
                @else
                <div class="empty-state" style="padding: 40px;">
                    <i class="fas fa-question-circle" style="font-size: 2rem;"></i>
                    <h3>Belum Ada Pertanyaan</h3>
                    <a href="{{ route('admin.digital.questions.create', ['questionnaire_id' => $questionnaire->id]) }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Tambah Pertanyaan
                    </a>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Sidebar -->
    <div>
        <!-- Recent Responses -->
        <div class="card">
            <div class="card-header">
                <h3><i class="fas fa-chart-bar"></i> Respons Terbaru</h3>
                <a href="{{ route('admin.digital.responses.index', ['questionnaire_id' => $questionnaire->id]) }}" class="btn btn-sm btn-secondary">
                    Semua
                </a>
            </div>
            <div class="card-body" style="padding: 0;">
                @if($questionnaire->responses->count() > 0)
                    @foreach($questionnaire->responses as $response)
                    <div style="padding: 15px; border-bottom: 1px solid #edf2f7;">
                        <div style="display: flex; justify-content: space-between; align-items: start;">
                            <div>
                                <strong style="color: var(--dark);">{{ $response->respondent_name }}</strong>
                                <br>
                                <small style="color: var(--gray);">{{ $response->respondent_email }}</small>
                            </div>
                            @if($response->is_completed)
                            <span class="badge badge-success">Selesai</span>
                            @else
                            <span class="badge badge-warning">Pending</span>
                            @endif
                        </div>
                        @if($response->completed_at)
                        <small style="color: var(--gray); display: block; margin-top: 5px;">
                            <i class="fas fa-clock"></i> {{ $response->completed_at->format('d M Y H:i') }}
                        </small>
                        @endif
                    </div>
                    @endforeach
                @else
                <div style="padding: 30px; text-align: center; color: var(--gray);">
                    <i class="fas fa-inbox" style="font-size: 2rem; margin-bottom: 10px;"></i>
                    <p>Belum ada respons</p>
                </div>
                @endif
            </div>
        </div>

        <!-- Meta Info -->
        <div class="card" style="margin-top: 20px;">
            <div class="card-header">
                <h3><i class="fas fa-info-circle"></i> Info Sistem</h3>
            </div>
            <div class="card-body">
                <div style="margin-bottom: 15px;">
                    <small style="color: var(--gray);">ID</small>
                    <div style="font-weight: 600;">{{ $questionnaire->id }}</div>
                </div>
                <div style="margin-bottom: 15px;">
                    <small style="color: var(--gray);">Slug</small>
                    <div style="font-family: monospace; background: #f7fafc; padding: 5px 10px; border-radius: 5px;">{{ $questionnaire->slug }}</div>
                </div>
                <div style="margin-bottom: 15px;">
                    <small style="color: var(--gray);">Dibuat</small>
                    <div>{{ $questionnaire->created_at->format('d M Y H:i') }}</div>
                </div>
                <div>
                    <small style="color: var(--gray);">Terakhir diubah</small>
                    <div>{{ $questionnaire->updated_at->format('d M Y H:i') }}</div>
                </div>
            </div>
        </div>

        <!-- Danger Zone -->
        <div class="card" style="margin-top: 20px; border: 1px solid #fed7d7;">
            <div class="card-header" style="background: #fff5f5;">
                <h3 style="color: #c53030;"><i class="fas fa-exclamation-triangle"></i> Zona Berbahaya</h3>
            </div>
            <div class="card-body">
                <p style="color: var(--gray); font-size: 0.9rem; margin-bottom: 15px;">
                    Tindakan ini tidak dapat dibatalkan. Pastikan Anda yakin sebelum menghapus angket.
                </p>
                <form action="{{ route('admin.digital.questionnaires.destroy', $questionnaire->id) }}" method="POST" onsubmit="return confirmDelete('Apakah Anda yakin ingin menghapus angket ini? Semua data termasuk dimensi dan pertanyaan akan ikut terhapus.')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger" style="width: 100%;">
                        <i class="fas fa-trash"></i> Hapus Angket
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
