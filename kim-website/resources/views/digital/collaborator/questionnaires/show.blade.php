@extends('layouts.collaborator')

@section('title', 'Detail Angket - Collaborator Digital')
@section('page-title', 'Detail Angket')

@section('styles')
<style>
.dimension-card {
    background: #f7fafc;
    border-radius: 10px;
    padding: 20px;
    margin-bottom: 15px;
    border-left: 4px solid var(--primary);
}

.question-item {
    background: white;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    padding: 15px;
    margin-bottom: 10px;
    display: flex;
    gap: 15px;
    align-items: flex-start;
}

.question-number {
    background: var(--primary);
    color: white;
    width: 30px;
    height: 30px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    flex-shrink: 0;
}

.interpretation-badge {
    display: inline-block;
    padding: 8px 15px;
    border-radius: 8px;
    font-size: 0.85rem;
    margin-right: 5px;
    margin-bottom: 5px;
}

.interpretation-badge.low {
    background: #c6f6d5;
    color: #22543d;
}

.interpretation-badge.medium {
    background: #feebc8;
    color: #744210;
}

.interpretation-badge.high {
    background: #fed7d7;
    color: #742a2a;
}
</style>
@endsection

@section('content')
<div style="margin-bottom: 20px; display: flex; justify-content: space-between; align-items: center;">
    <a href="{{ route('digital.collaborator.questionnaires.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Kembali
    </a>
    <div style="display: flex; gap: 10px;">
        <a href="{{ route('digital.collaborator.questionnaires.edit', $questionnaire->id) }}" class="btn btn-primary">
            <i class="fas fa-edit"></i> Edit Angket
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
    <!-- Main Content -->
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

        <!-- Dimensions Section -->
        @if($questionnaire->has_dimensions)
        <div class="card" style="margin-bottom: 25px;">
            <div class="card-header">
                <h3><i class="fas fa-layer-group"></i> Dimensi Pengukuran ({{ $questionnaire->dimensions->count() }})
                </h3>
                <button type="button" class="btn btn-sm btn-primary"
                    onclick="document.getElementById('addDimensionModal').classList.add('show')">
                    <i class="fas fa-plus"></i> Tambah Dimensi
                </button>
            </div>
            <div class="card-body">
                @if($questionnaire->dimensions->count() > 0)
                @foreach($questionnaire->dimensions as $dimension)
                <div class="dimension-card">
                    <div
                        style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 15px;">
                        <div>
                            <h4 style="color: var(--dark); margin-bottom: 5px;">
                                {{ $dimension->order }}. {{ $dimension->name }}
                            </h4>
                            <span class="badge badge-info">{{ $dimension->code }}</span>
                            <span class="badge badge-primary">{{ $dimension->questions->count() }} pertanyaan</span>
                        </div>
                        <div style="display: flex; gap: 5px;">
                            <a href="{{ route('digital.collaborator.dimensions.edit', $dimension->id) }}"
                                class="btn btn-sm btn-warning">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('digital.collaborator.questionnaires.deleteDimension', $dimension->id) }}"
                                method="POST" style="display: inline;"
                                onsubmit="return confirmDelete('Hapus dimensi ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-icon btn-danger" title="Hapus">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>

                    @if($dimension->description)
                    <p style="color: var(--gray); font-size: 0.9rem; margin-bottom: 15px;">{{ $dimension->description }}
                    </p>
                    @endif

                    <!-- Interpretations -->
                    <div style="margin-top: 15px;">
                        <small style="color: var(--gray); font-weight: 600;">Interpretasi:</small>
                        <div style="margin-top: 8px;">
                            @if($dimension->interpretations)
                            @foreach(['low' => 'Rendah', 'medium' => 'Sedang', 'high' => 'Tinggi'] as $level => $label)
                            @if(isset($dimension->interpretations[$level]))
                            <span class="interpretation-badge {{ $level }}">
                                {{ $dimension->interpretations[$level]['level'] ?? $label }}
                            </span>
                            @endif
                            @endforeach
                            @else
                            <span style="color: var(--gray); font-size: 0.85rem;">Belum diatur</span>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
                @else
                <div class="empty-state" style="padding: 30px;">
                    <i class="fas fa-layer-group" style="font-size: 2rem;"></i>
                    <p>Belum ada dimensi. Tambahkan dimensi untuk mengkategorikan pertanyaan.</p>
                </div>
                @endif
            </div>
        </div>
        @endif

        <!-- Questions Section -->
        <div class="card">
            <div class="card-header">
                <h3><i class="fas fa-question-circle"></i> Daftar Pertanyaan ({{ $questionnaire->questions->count() }})
                </h3>
                <button type="button" class="btn btn-sm btn-primary"
                    onclick="document.getElementById('addQuestionModal').classList.add('show')">
                    <i class="fas fa-plus"></i> Tambah Pertanyaan
                </button>
            </div>
            <div class="card-body">
                @if($questionnaire->questions->count() > 0)
                @foreach($questionnaire->questions as $question)
                <div class="question-item">
                    <div class="question-number">{{ $question->order }}</div>
                    <div style="flex: 1;">
                        <p style="margin: 0 0 10px; color: var(--dark);">{{ $question->question_text }}</p>
                        <div style="display: flex; gap: 5px; flex-wrap: wrap;">
                            @if($question->dimension)
                            <span class="badge badge-primary">{{ $question->dimension->name }}</span>
                            @endif
                            @if($question->is_reverse_scored)
                            <span class="badge badge-warning">Reverse Scored</span>
                            @endif
                        </div>
                    </div>
                    <form action="{{ route('digital.collaborator.questionnaires.deleteQuestion', $question->id) }}"
                        method="POST" style="display: inline;" onsubmit="return confirmDelete('Hapus pertanyaan ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-icon btn-danger" title="Hapus">
                            <i class="fas fa-trash"></i>
                        </button>
                    </form>
                </div>
                @endforeach
                @else
                <div class="empty-state" style="padding: 40px;">
                    <i class="fas fa-question-circle" style="font-size: 2rem;"></i>
                    <h3>Belum Ada Pertanyaan</h3>
                    <p>Tambahkan pertanyaan untuk angket ini</p>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Sidebar -->
    <div>
        <!-- Products using this questionnaire -->
        @if($products->count() > 0)
        <div class="card" style="margin-bottom: 20px;">
            <div class="card-header">
                <h3><i class="fas fa-box"></i> Produk Terkait</h3>
            </div>
            <div class="card-body" style="padding: 0;">
                @foreach($products as $product)
                <div style="padding: 15px; border-bottom: 1px solid #edf2f7;">
                    <strong>{{ $product->name }}</strong>
                    <br>
                    <small style="color: var(--gray);">Rp {{ number_format($product->price, 0, ',', '.') }}</small>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Recent Responses -->
        <div class="card" style="margin-bottom: 20px;">
            <div class="card-header">
                <h3><i class="fas fa-chart-bar"></i> Respons Terbaru</h3>
            </div>
            <div class="card-body" style="padding: 0;">
                @if($questionnaire->responses->count() > 0)
                @foreach($questionnaire->responses->take(5) as $response)
                <div style="padding: 15px; border-bottom: 1px solid #edf2f7;">
                    <strong style="color: var(--dark);">{{ $response->respondent_name }}</strong>
                    <br>
                    <small style="color: var(--gray);">{{ $response->respondent_email }}</small>
                    @if($response->completed_at)
                    <br>
                    <small style="color: var(--gray);">{{ $response->completed_at->format('d M Y H:i') }}</small>
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
        <div class="card">
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
                    <div
                        style="font-family: monospace; background: #f7fafc; padding: 5px 10px; border-radius: 5px; font-size: 0.85rem;">
                        {{ $questionnaire->slug }}</div>
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
    </div>
</div>

<!-- Add Dimension Modal -->
<div id="addDimensionModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Tambah Dimensi</h3>
            <button class="modal-close"
                onclick="document.getElementById('addDimensionModal').classList.remove('show')">&times;</button>
        </div>
        <form action="{{ route('digital.collaborator.questionnaires.addDimension', $questionnaire->id) }}" method="POST">
            @csrf
            <div class="modal-body">
                <div class="form-group">
                    <label>Nama Dimensi <span style="color: var(--danger);">*</span></label>
                    <input type="text" name="name" class="form-control" required
                        placeholder="Contoh: Kelelahan Emosional">
                </div>
                <div class="form-group">
                    <label>Kode <span style="color: var(--danger);">*</span></label>
                    <input type="text" name="code" class="form-control" required placeholder="Contoh: exhaustion">
                    <small style="color: var(--gray);">Gunakan huruf kecil tanpa spasi</small>
                </div>
                <div class="form-group">
                    <label>Deskripsi</label>
                    <textarea name="description" class="form-control" rows="3"
                        placeholder="Jelaskan apa yang diukur..."></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary"
                    onclick="document.getElementById('addDimensionModal').classList.remove('show')">Batal</button>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Simpan
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Add Question Modal -->
<div id="addQuestionModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Tambah Pertanyaan</h3>
            <button class="modal-close"
                onclick="document.getElementById('addQuestionModal').classList.remove('show')">&times;</button>
        </div>
        <form action="{{ route('digital.collaborator.questionnaires.addQuestion', $questionnaire->id) }}" method="POST">
            @csrf
            <div class="modal-body">
                @if($questionnaire->has_dimensions && $questionnaire->dimensions->count() > 0)
                <div class="form-group">
                    <label>Dimensi</label>
                    <select name="dimension_id" class="form-control">
                        <option value="">Tanpa Dimensi</option>
                        @foreach($questionnaire->dimensions as $dimension)
                        <option value="{{ $dimension->id }}">{{ $dimension->name }}</option>
                        @endforeach
                    </select>
                </div>
                @endif
                <div class="form-group">
                    <label>Pertanyaan <span style="color: var(--danger);">*</span></label>
                    <textarea name="question_text" class="form-control" rows="3" required
                        placeholder="Tulis pertanyaan angket..."></textarea>
                </div>
                <div class="form-group">
                    <div class="form-check">
                        <input type="checkbox" name="is_reverse_scored" id="is_reverse_scored" value="1">
                        <label for="is_reverse_scored">Reverse Scored</label>
                    </div>
                    <small style="color: var(--gray);">Centang jika skor dibalik (5 menjadi 1, dst.)</small>
                </div>

                <div style="background: #f7fafc; padding: 15px; border-radius: 10px;">
                    <small style="color: var(--gray);">Opsi jawaban default (Skala Likert 1-5):</small>
                    <div style="display: flex; gap: 5px; flex-wrap: wrap; margin-top: 8px;">
                        <span class="badge badge-primary">1 - STS</span>
                        <span class="badge badge-primary">2 - TS</span>
                        <span class="badge badge-primary">3 - N</span>
                        <span class="badge badge-primary">4 - S</span>
                        <span class="badge badge-primary">5 - SS</span>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary"
                    onclick="document.getElementById('addQuestionModal').classList.remove('show')">Batal</button>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Simpan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
function editDimension(dimension) {
    alert(
        'Fitur edit dimensi akan segera tersedia. Untuk saat ini, hapus dan buat ulang dimensi dengan data yang benar.'
        );
}

// Close modal on outside click
document.querySelectorAll('.modal').forEach(function(modal) {
    modal.addEventListener('click', function(e) {
        if (e.target === modal) {
            modal.classList.remove('show');
        }
    });
});
</script>
@endsection