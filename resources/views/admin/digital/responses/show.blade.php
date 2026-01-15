@extends('layouts.admin-digital')

@section('title', 'Detail Respons - Admin Digital')
@section('page-title', 'Detail Respons')

@section('content')
<div style="margin-bottom: 20px;">
    <a href="{{ route('admin.digital.responses.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Kembali ke Daftar
    </a>
</div>

<div style="display: grid; grid-template-columns: 2fr 1fr; gap: 25px;">
    <!-- Main Content -->
    <div>
        <!-- Respondent Info -->
        <div class="card" style="margin-bottom: 25px;">
            <div class="card-header">
                <h3><i class="fas fa-user"></i> Informasi Responden</h3>
                <span class="badge {{ $response->is_completed ? 'badge-success' : 'badge-warning' }}">
                    {{ $response->is_completed ? 'Selesai' : 'Pending' }}
                </span>
            </div>
            <div class="card-body">
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                    <div>
                        <small style="color: var(--gray);">Nama</small>
                        <div style="font-weight: 600; font-size: 1.1rem;">{{ $response->respondent_name }}</div>
                    </div>
                    <div>
                        <small style="color: var(--gray);">Email</small>
                        <div>{{ $response->respondent_email }}</div>
                    </div>
                    <div>
                        <small style="color: var(--gray);">Order Number</small>
                        <div>
                            @if($response->order)
                                <a href="{{ route('admin.digital.orders.show', $response->order->id) }}" style="color: var(--primary);">
                                    {{ $response->order->order_number }}
                                </a>
                            @else
                                -
                            @endif
                        </div>
                    </div>
                    <div>
                        <small style="color: var(--gray);">Tanggal Selesai</small>
                        <div>{{ $response->completed_at ? $response->completed_at->format('d M Y H:i') : '-' }}</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Scores -->
        @if($response->is_completed && $response->result_summary)
        <div class="card" style="margin-bottom: 25px;">
            <div class="card-header">
                <h3><i class="fas fa-chart-pie"></i> Hasil Analisis</h3>
            </div>
            <div class="card-body">
                @php
                    $resultSummary = is_array($response->result_summary) ? $response->result_summary : json_decode($response->result_summary, true);
                @endphp

                @foreach($resultSummary as $dimensionCode => $result)
                <div style="background: #f7fafc; border-radius: 10px; padding: 20px; margin-bottom: 15px; border-left: 4px solid {{ isset($result['interpretation']['class']) && $result['interpretation']['class'] == 'level-rendah' ? '#48bb78' : (isset($result['interpretation']['class']) && $result['interpretation']['class'] == 'level-sedang' ? '#ed8936' : '#f56565') }};">
                    <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 15px;">
                        <div>
                            <h4 style="margin: 0 0 5px; color: var(--dark);">
                                {{ $result['dimension_name'] ?? ucfirst($dimensionCode) }}
                            </h4>
                            <span class="badge badge-info">Kode: {{ $dimensionCode }}</span>
                        </div>
                        <div style="text-align: right;">
                            <div style="font-size: 2rem; font-weight: 700; color: var(--primary);">
                                {{ $result['score'] ?? 0 }}
                            </div>
                            <small style="color: var(--gray);">poin</small>
                        </div>
                    </div>

                    @if(isset($result['interpretation']))
                    <div style="background: white; padding: 15px; border-radius: 8px;">
                        <div style="margin-bottom: 10px;">
                            <span class="badge" style="padding: 8px 15px; {{ isset($result['interpretation']['class']) && $result['interpretation']['class'] == 'level-rendah' ? 'background: #c6f6d5; color: #22543d;' : (isset($result['interpretation']['class']) && $result['interpretation']['class'] == 'level-sedang' ? 'background: #feebc8; color: #744210;' : 'background: #fed7d7; color: #742a2a;') }}">
                                {{ $result['interpretation']['level'] ?? 'N/A' }}
                            </span>
                        </div>
                        <p style="color: var(--dark); margin: 0 0 15px;">
                            {{ $result['interpretation']['description'] ?? '' }}
                        </p>
                        
                        @if(isset($result['interpretation']['suggestions']) && count($result['interpretation']['suggestions']) > 0)
                        <div style="background: #ebf8ff; padding: 15px; border-radius: 8px;">
                            <strong style="color: #2b6cb0;">💡 Saran:</strong>
                            <ul style="margin: 10px 0 0 20px; color: #2c5282;">
                                @foreach($result['interpretation']['suggestions'] as $suggestion)
                                <li>{{ $suggestion }}</li>
                                @endforeach
                            </ul>
                        </div>
                        @endif
                    </div>
                    @endif
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Answers Detail -->
        @if($response->is_completed && $response->answers)
        <div class="card">
            <div class="card-header">
                <h3><i class="fas fa-list-ol"></i> Detail Jawaban</h3>
            </div>
            <div class="card-body" style="padding: 0;">
                @php
                    $answers = is_array($response->answers) ? $response->answers : json_decode($response->answers, true);
                @endphp

                @foreach($response->questionnaire->questions as $question)
                <div style="padding: 15px 20px; border-bottom: 1px solid #edf2f7; display: flex; gap: 15px;">
                    <div style="background: var(--primary); color: white; width: 30px; height: 30px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 600; flex-shrink: 0;">
                        {{ $question->order }}
                    </div>
                    <div style="flex: 1;">
                        <p style="margin: 0 0 10px; color: var(--dark);">{{ $question->question_text }}</p>
                        <div style="display: flex; gap: 10px; align-items: center; flex-wrap: wrap;">
                            @if($question->dimension)
                            <span class="badge badge-primary">{{ $question->dimension->name }}</span>
                            @endif
                            @if($question->is_reverse_scored)
                            <span class="badge badge-warning">Reverse</span>
                            @endif
                        </div>
                    </div>
                    <div style="text-align: right;">
                        @php
                            $answerValue = $answers[$question->id] ?? null;
                            $labels = [1 => 'Sangat Tidak Setuju', 2 => 'Tidak Setuju', 3 => 'Netral', 4 => 'Setuju', 5 => 'Sangat Setuju'];
                        @endphp
                        <div style="font-size: 1.5rem; font-weight: 700; color: var(--primary);">
                            {{ $answerValue ?? '-' }}
                        </div>
                        <small style="color: var(--gray);">{{ $labels[$answerValue] ?? '' }}</small>
                        @if($question->is_reverse_scored && $answerValue)
                        <div style="margin-top: 5px;">
                            <small style="color: var(--warning);">Skor: {{ 6 - $answerValue }}</small>
                        </div>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>

    <!-- Sidebar -->
    <div>
        <!-- Actions -->
        <div class="card" style="margin-bottom: 20px;">
            <div class="card-header">
                <h3><i class="fas fa-cog"></i> Aksi</h3>
            </div>
            <div class="card-body">
                @if($response->is_completed)
                    @if($response->result_pdf_path)
                    <a href="{{ Storage::url($response->result_pdf_path) }}" target="_blank" class="btn btn-primary" style="width: 100%; margin-bottom: 10px;">
                        <i class="fas fa-download"></i> Download PDF
                    </a>
                    <form action="{{ route('admin.digital.responses.regenerate', $response->id) }}" method="POST" style="margin-bottom: 10px;">
                        @csrf
                        <button type="submit" class="btn btn-secondary" style="width: 100%;">
                            <i class="fas fa-sync"></i> Regenerate PDF
                        </button>
                    </form>
                    @else
                    <form action="{{ route('admin.digital.responses.regenerate', $response->id) }}" method="POST" style="margin-bottom: 10px;">
                        @csrf
                        <button type="submit" class="btn btn-primary" style="width: 100%;">
                            <i class="fas fa-file-pdf"></i> Generate PDF
                        </button>
                    </form>
                    @endif

                    <form action="{{ route('admin.digital.responses.resend', $response->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-success" style="width: 100%;">
                            <i class="fas fa-envelope"></i> Kirim Ulang Email
                        </button>
                    </form>
                @else
                    <div style="background: #feebc8; padding: 15px; border-radius: 10px; text-align: center;">
                        <i class="fas fa-clock" style="font-size: 2rem; color: #dd6b20; margin-bottom: 10px;"></i>
                        <p style="margin: 0; color: #744210;">Respons belum selesai diisi</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Questionnaire Info -->
        <div class="card" style="margin-bottom: 20px;">
            <div class="card-header">
                <h3><i class="fas fa-clipboard-list"></i> CEKMA</h3>
            </div>
            <div class="card-body">
                <h4 style="margin-bottom: 10px; color: var(--dark);">{{ $response->questionnaire->name }}</h4>
                <span class="badge badge-info">{{ ucfirst($response->questionnaire->type) }}</span>
                <p style="color: var(--gray); font-size: 0.9rem; margin-top: 10px;">
                    {{ Str::limit($response->questionnaire->description, 100) }}
                </p>
                <a href="{{ route('admin.digital.questionnaires.show', $response->questionnaire_id) }}" style="color: var(--primary);">
                    Lihat Detail CEKMA →
                </a>
            </div>
        </div>

        <!-- Email Status -->
        <div class="card" style="margin-bottom: 20px;">
            <div class="card-header">
                <h3><i class="fas fa-envelope"></i> Status Email</h3>
            </div>
            <div class="card-body">
                @if($response->result_sent)
                <div style="background: #c6f6d5; padding: 15px; border-radius: 10px; text-align: center;">
                    <i class="fas fa-check-circle" style="font-size: 2rem; color: #22543d; margin-bottom: 10px;"></i>
                    <p style="margin: 0; color: #22543d; font-weight: 600;">Email Terkirim</p>
                    @if($response->result_sent_at)
                    <small style="color: #276749;">{{ $response->result_sent_at->format('d M Y H:i') }}</small>
                    @endif
                </div>
                @else
                <div style="background: #feebc8; padding: 15px; border-radius: 10px; text-align: center;">
                    <i class="fas fa-clock" style="font-size: 2rem; color: #dd6b20; margin-bottom: 10px;"></i>
                    <p style="margin: 0; color: #744210;">Belum Terkirim</p>
                </div>
                @endif
            </div>
        </div>

        <!-- Meta -->
        <div class="card">
            <div class="card-header">
                <h3><i class="fas fa-info-circle"></i> Info Sistem</h3>
            </div>
            <div class="card-body">
                <div style="margin-bottom: 15px;">
                    <small style="color: var(--gray);">ID Respons</small>
                    <div style="font-weight: 600;">{{ $response->id }}</div>
                </div>
                <div style="margin-bottom: 15px;">
                    <small style="color: var(--gray);">Dibuat</small>
                    <div>{{ $response->created_at->format('d M Y H:i') }}</div>
                </div>
                <div style="margin-bottom: 15px;">
                    <small style="color: var(--gray);">Selesai</small>
                    <div>{{ $response->completed_at ? $response->completed_at->format('d M Y H:i') : '-' }}</div>
                </div>
                @if($response->result_pdf_path)
                <div>
                    <small style="color: var(--gray);">Path PDF</small>
                    <div style="font-size: 0.8rem; word-break: break-all; background: #f7fafc; padding: 8px; border-radius: 5px;">
                        {{ $response->result_pdf_path }}
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
