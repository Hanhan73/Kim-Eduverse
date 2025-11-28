@extends('layouts.admin-digital')

@section('title', 'Detail Dimensi - Admin Digital')
@section('page-title', 'Detail Dimensi')

@section('content')
<div style="margin-bottom: 20px; display: flex; justify-content: space-between; align-items: center;">
    <a href="{{ route('admin.digital.dimensions.index', ['questionnaire_id' => $dimension->questionnaire_id]) }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Kembali
    </a>
    <a href="{{ route('admin.digital.dimensions.edit', $dimension->id) }}" class="btn btn-primary">
        <i class="fas fa-edit"></i> Edit Dimensi
    </a>
</div>

<div style="display: grid; grid-template-columns: 2fr 1fr; gap: 25px;">
    <!-- Main Content -->
    <div>
        <!-- Basic Info -->
        <div class="card" style="margin-bottom: 25px;">
            <div class="card-header">
                <h3><i class="fas fa-layer-group"></i> Informasi Dimensi</h3>
            </div>
            <div class="card-body">
                <h2 style="margin-bottom: 10px; color: var(--dark);">{{ $dimension->name }}</h2>
                <div style="display: flex; gap: 10px; margin-bottom: 15px;">
                    <span class="badge badge-info">{{ $dimension->code }}</span>
                    <span class="badge badge-primary">Urutan: {{ $dimension->order }}</span>
                </div>
                @if($dimension->description)
                <p style="color: var(--gray); line-height: 1.6;">{{ $dimension->description }}</p>
                @endif
            </div>
        </div>

        <!-- Interpretations -->
        <div class="card" style="margin-bottom: 25px;">
            <div class="card-header">
                <h3><i class="fas fa-chart-pie"></i> Interpretasi Skor</h3>
            </div>
            <div class="card-body">
                @php $interpretations = $dimension->interpretations ?? []; @endphp

                @foreach(['low' => ['label' => 'RENDAH', 'bg' => '#c6f6d5', 'color' => '#22543d', 'icon' => 'fa-smile'], 'medium' => ['label' => 'SEDANG', 'bg' => '#feebc8', 'color' => '#744210', 'icon' => 'fa-meh'], 'high' => ['label' => 'TINGGI', 'bg' => '#fed7d7', 'color' => '#742a2a', 'icon' => 'fa-frown']] as $level => $config)
                @if(isset($interpretations[$level]))
                <div style="background: {{ $config['bg'] }}; border-radius: 10px; padding: 20px; margin-bottom: 15px;">
                    <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 15px;">
                        <i class="fas {{ $config['icon'] }}" style="font-size: 1.5rem; color: {{ $config['color'] }};"></i>
                        <h4 style="margin: 0; color: {{ $config['color'] }};">
                            {{ $interpretations[$level]['level'] ?? $config['label'] }}
                        </h4>
                        <span class="badge" style="background: {{ $config['color'] }}; color: white;">
                            {{ $interpretations[$level]['class'] ?? '' }}
                        </span>
                    </div>
                    
                    <p style="color: {{ $config['color'] }}; margin-bottom: 15px;">
                        {{ $interpretations[$level]['description'] ?? '' }}
                    </p>

                    @if(isset($interpretations[$level]['suggestions']) && count($interpretations[$level]['suggestions']) > 0)
                    <div style="background: rgba(255,255,255,0.5); padding: 15px; border-radius: 8px;">
                        <strong style="color: {{ $config['color'] }};">💡 Saran:</strong>
                        <ul style="margin: 10px 0 0 20px; color: {{ $config['color'] }};">
                            @foreach($interpretations[$level]['suggestions'] as $suggestion)
                            <li>{{ $suggestion }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif
                </div>
                @endif
                @endforeach
            </div>
        </div>

        <!-- Questions -->
        <div class="card">
            <div class="card-header">
                <h3><i class="fas fa-question-circle"></i> Pertanyaan dalam Dimensi Ini ({{ $dimension->questions->count() }})</h3>
                <a href="{{ route('admin.digital.questions.create', ['questionnaire_id' => $dimension->questionnaire_id, 'dimension_id' => $dimension->id]) }}" class="btn btn-sm btn-primary">
                    <i class="fas fa-plus"></i> Tambah
                </a>
            </div>
            <div class="card-body" style="padding: 0;">
                @if($dimension->questions->count() > 0)
                    @foreach($dimension->questions as $question)
                    <div style="padding: 15px 20px; border-bottom: 1px solid #edf2f7; display: flex; gap: 15px; align-items: start;">
                        <div style="background: var(--primary); color: white; width: 30px; height: 30px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 600; flex-shrink: 0;">
                            {{ $question->order }}
                        </div>
                        <div style="flex: 1;">
                            <p style="margin: 0 0 5px; color: var(--dark);">{{ $question->question_text }}</p>
                            @if($question->is_reverse_scored)
                            <span class="badge badge-warning">Reverse Scored</span>
                            @endif
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
                    <a href="{{ route('admin.digital.questions.create', ['questionnaire_id' => $dimension->questionnaire_id, 'dimension_id' => $dimension->id]) }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Tambah Pertanyaan
                    </a>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Sidebar -->
    <div>
        <!-- Questionnaire Info -->
        <div class="card" style="margin-bottom: 20px;">
            <div class="card-header">
                <h3><i class="fas fa-clipboard-list"></i> Angket</h3>
            </div>
            <div class="card-body">
                <h4 style="margin-bottom: 10px; color: var(--dark);">{{ $dimension->questionnaire->name }}</h4>
                <span class="badge badge-info">{{ ucfirst($dimension->questionnaire->type) }}</span>
                <a href="{{ route('admin.digital.questionnaires.show', $dimension->questionnaire_id) }}" style="display: block; margin-top: 15px; color: var(--primary);">
                    Lihat Detail Angket →
                </a>
            </div>
        </div>

        <!-- Stats -->
        <div class="card" style="margin-bottom: 20px;">
            <div class="card-header">
                <h3><i class="fas fa-chart-bar"></i> Statistik</h3>
            </div>
            <div class="card-body">
                <div style="display: flex; justify-content: space-between; padding: 10px 0; border-bottom: 1px solid #edf2f7;">
                    <span style="color: var(--gray);">Total Pertanyaan</span>
                    <strong>{{ $dimension->questions->count() }}</strong>
                </div>
                <div style="display: flex; justify-content: space-between; padding: 10px 0; border-bottom: 1px solid #edf2f7;">
                    <span style="color: var(--gray);">Reverse Scored</span>
                    <strong>{{ $dimension->questions->where('is_reverse_scored', true)->count() }}</strong>
                </div>
                <div style="display: flex; justify-content: space-between; padding: 10px 0;">
                    <span style="color: var(--gray);">Normal</span>
                    <strong>{{ $dimension->questions->where('is_reverse_scored', false)->count() }}</strong>
                </div>
            </div>
        </div>

        <!-- Meta -->
        <div class="card">
            <div class="card-header">
                <h3><i class="fas fa-info-circle"></i> Info Sistem</h3>
            </div>
            <div class="card-body">
                <div style="margin-bottom: 15px;">
                    <small style="color: var(--gray);">ID</small>
                    <div style="font-weight: 600;">{{ $dimension->id }}</div>
                </div>
                <div style="margin-bottom: 15px;">
                    <small style="color: var(--gray);">Kode</small>
                    <div style="font-family: monospace; background: #f7fafc; padding: 5px 10px; border-radius: 5px;">{{ $dimension->code }}</div>
                </div>
                <div style="margin-bottom: 15px;">
                    <small style="color: var(--gray);">Dibuat</small>
                    <div>{{ $dimension->created_at->format('d M Y H:i') }}</div>
                </div>
                <div>
                    <small style="color: var(--gray);">Terakhir diubah</small>
                    <div>{{ $dimension->updated_at->format('d M Y H:i') }}</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
