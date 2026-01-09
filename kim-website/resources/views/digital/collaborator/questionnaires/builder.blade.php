@extends('layouts.collaborator')

@section('content')
<div class="main-content">
    <div class="top-bar">
        <h1>Builder: {{ $questionnaire->name }}</h1>
        <div class="user-info">
            <a href="{{ route('digital.collaborator.questionnaires.index') }}" class="btn-secondary">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success">
        <i class="fas fa-check-circle"></i> {{ session('success') }}
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger">
        <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
    </div>
    @endif

    <!-- Questionnaire Info -->
    <div class="content-section" style="margin-bottom: 20px;">
        <div class="section-header">
            <h2>Informasi Angket</h2>
        </div>
        <form action="{{ route('digital.collaborator.questionnaires.update', $questionnaire) }}" method="POST">
            @csrf
            @method('PUT')
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 15px;">
                <div>
                    <label style="display: block; font-weight: 600; margin-bottom: 8px;">Nama Angket</label>
                    <input type="text" name="name" value="{{ $questionnaire->name }}" required
                        style="width: 100%; padding: 10px; border: 2px solid #e2e8f0; border-radius: 8px;">
                </div>
                <div>
                    <label style="display: block; font-weight: 600; margin-bottom: 8px;">Tipe</label>
                    <input type="text" name="type" value="{{ $questionnaire->type }}" required
                        style="width: 100%; padding: 10px; border: 2px solid #e2e8f0; border-radius: 8px;">
                </div>
            </div>
            <div style="margin-bottom: 15px;">
                <label style="display: block; font-weight: 600; margin-bottom: 8px;">Deskripsi</label>
                <textarea name="description" rows="3" required
                    style="width: 100%; padding: 10px; border: 2px solid #e2e8f0; border-radius: 8px;">{{ $questionnaire->description }}</textarea>
            </div>
            <div style="display: flex; gap: 10px; align-items: center;">
                <label style="display: flex; align-items: center; gap: 8px;">
                    <input type="checkbox" name="is_active" {{ $questionnaire->is_active ? 'checked' : '' }}>
                    <span>Aktif</span>
                </label>
                <button type="submit" class="btn-primary">
                    <i class="fas fa-save"></i> Update Info
                </button>
            </div>
        </form>
    </div>

    @if($questionnaire->has_dimensions)
    <!-- Dimensions Section -->
    <div class="content-section" style="margin-bottom: 20px;">
        <div class="section-header">
            <h2>Dimensi ({{ $questionnaire->dimensions->count() }})</h2>
            <button onclick="document.getElementById('addDimensionModal').style.display='block'" class="btn-primary">
                <i class="fas fa-plus"></i> Tambah Dimensi
            </button>
        </div>

        @if($questionnaire->dimensions->count() > 0)
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="background: #f7fafc;">
                    <th style="padding: 12px; text-align: left; border-bottom: 2px solid #e2e8f0;">Urutan</th>
                    <th style="padding: 12px; text-align: left; border-bottom: 2px solid #e2e8f0;">Kode</th>
                    <th style="padding: 12px; text-align: left; border-bottom: 2px solid #e2e8f0;">Nama</th>
                    <th style="padding: 12px; text-align: left; border-bottom: 2px solid #e2e8f0;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($questionnaire->dimensions as $dimension)
                <tr>
                    <td style="padding: 12px; border-bottom: 1px solid #e2e8f0;">{{ $dimension->order }}</td>
                    <td style="padding: 12px; border-bottom: 1px solid #e2e8f0;"><code>{{ $dimension->code }}</code>
                    </td>
                    <td style="padding: 12px; border-bottom: 1px solid #e2e8f0;">{{ $dimension->name }}</td>
                    <td style="padding: 12px; border-bottom: 1px solid #e2e8f0;">
                        <form
                            action="{{ route('digital.collaborator.questionnaires.dimensions.destroy', $dimension->id) }}"
                            method="POST" style="display: inline;"
                            onsubmit="return confirm('Yakin hapus dimensi ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                style="background: #fed7d7; color: #742a2a; border: none; padding: 6px 12px; border-radius: 6px; cursor: pointer;">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <div style="text-align: center; padding: 40px; color: #a0aec0;">
            <i class="fas fa-layer-group" style="font-size: 3rem; margin-bottom: 10px;"></i>
            <p>Belum ada dimensi. Tambahkan dimensi untuk mengelompokkan pertanyaan.</p>
        </div>
        @endif
    </div>
    @endif

    <!-- Questions Section -->
    <div class="content-section">
        <div class="section-header">
            <h2>Pertanyaan ({{ $questionnaire->questions->count() }})</h2>
            <button onclick="document.getElementById('addQuestionModal').style.display='block'" class="btn-primary">
                <i class="fas fa-plus"></i> Tambah Pertanyaan
            </button>
        </div>

        @if($questionnaire->questions->count() > 0)
        <div style="display: flex; flex-direction: column; gap: 15px;">
            @foreach($questionnaire->questions as $question)
            <div style="background: #f7fafc; padding: 15px; border-radius: 8px; border-left: 4px solid var(--primary);">
                <div style="display: flex; justify-content: space-between; align-items: start;">
                    <div style="flex: 1;">
                        <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 8px;">
                            <span
                                style="background: var(--primary); color: white; width: 30px; height: 30px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 600;">
                                {{ $question->order }}
                            </span>
                            @if($question->dimension)
                            <span
                                style="background: #bee3f8; color: #2c5282; padding: 4px 10px; border-radius: 12px; font-size: 0.85rem; font-weight: 600;">
                                {{ $question->dimension->name }}
                            </span>
                            @endif
                            @if($question->is_reverse_scored)
                            <span
                                style="background: #feebc8; color: #744210; padding: 4px 10px; border-radius: 12px; font-size: 0.85rem; font-weight: 600;">
                                Reverse
                            </span>
                            @endif
                        </div>
                        <p style="margin: 0; color: var(--dark);">{{ $question->question_text }}</p>
                    </div>
                    <form action="{{ route('digital.collaborator.questionnaires.questions.destroy', $question->id) }}"
                        method="POST" onsubmit="return confirm('Yakin hapus pertanyaan ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            style="background: #fed7d7; color: #742a2a; border: none; padding: 8px 12px; border-radius: 6px; cursor: pointer;">
                            <i class="fas fa-trash"></i>
                        </button>
                    </form>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div style="text-align: center; padding: 40px; color: #a0aec0;">
            <i class="fas fa-question-circle" style="font-size: 3rem; margin-bottom: 10px;"></i>
            <p>Belum ada pertanyaan. Tambahkan pertanyaan untuk angket Anda.</p>
        </div>
        @endif
    </div>
</div>

<!-- Add Dimension Modal -->
<div id="addDimensionModal"
    style="display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5);">
    <div
        style="background: white; margin: 100px auto; padding: 0; width: 500px; border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.2);">
        <div style="padding: 20px 30px; border-bottom: 2px solid var(--light);">
            <h2 style="margin: 0; font-size: 1.3rem;">Tambah Dimensi</h2>
        </div>
        <form action="{{ route('digital.collaborator.questionnaires.dimensions.store', $questionnaire) }}"
            method="POST">
            @csrf
            <div style="padding: 30px;">
                <div style="margin-bottom: 20px;">
                    <label style="display: block; font-weight: 600; margin-bottom: 8px;">Kode Dimensi *</label>
                    <input type="text" name="code" required placeholder="contoh: exhaustion"
                        style="width: 100%; padding: 10px; border: 2px solid #e2e8f0; border-radius: 8px;">
                    <small style="color: var(--gray); display: block; margin-top: 5px;">Huruf kecil tanpa spasi</small>
                </div>
                <div style="margin-bottom: 20px;">
                    <label style="display: block; font-weight: 600; margin-bottom: 8px;">Nama Dimensi *</label>
                    <input type="text" name="name" required placeholder="contoh: Kelelahan Emosional"
                        style="width: 100%; padding: 10px; border: 2px solid #e2e8f0; border-radius: 8px;">
                </div>
                <div style="margin-bottom: 20px;">
                    <label style="display: block; font-weight: 600; margin-bottom: 8px;">Deskripsi</label>
                    <textarea name="description" rows="3"
                        style="width: 100%; padding: 10px; border: 2px solid #e2e8f0; border-radius: 8px;"></textarea>
                </div>
            </div>
            <div
                style="padding: 20px 30px; border-top: 2px solid var(--light); display: flex; justify-content: flex-end; gap: 10px;">
                <button type="button" onclick="document.getElementById('addDimensionModal').style.display='none'"
                    class="btn-secondary">
                    Batal
                </button>
                <button type="submit" class="btn-primary">
                    <i class="fas fa-save"></i> Simpan
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Add Question Modal -->
<div id="addQuestionModal"
    style="display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5);">
    <div
        style="background: white; margin: 100px auto; padding: 0; width: 600px; border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.2);">
        <div style="padding: 20px 30px; border-bottom: 2px solid var(--light);">
            <h2 style="margin: 0; font-size: 1.3rem;">Tambah Pertanyaan</h2>
        </div>
        <form action="{{ route('digital.collaborator.questionnaires.questions.store', $questionnaire) }}" method="POST">
            @csrf
            <div style="padding: 30px;">
                @if($questionnaire->has_dimensions && $questionnaire->dimensions->count() > 0)
                <div style="margin-bottom: 20px;">
                    <label style="display: block; font-weight: 600; margin-bottom: 8px;">Dimensi</label>
                    <select name="dimension_id"
                        style="width: 100%; padding: 10px; border: 2px solid #e2e8f0; border-radius: 8px;">
                        <option value="">Tanpa Dimensi</option>
                        @foreach($questionnaire->dimensions as $dimension)
                        <option value="{{ $dimension->id }}">{{ $dimension->name }}</option>
                        @endforeach
                    </select>
                </div>
                @endif
                <div style="margin-bottom: 20px;">
                    <label style="display: block; font-weight: 600; margin-bottom: 8px;">Teks Pertanyaan *</label>
                    <textarea name="question_text" rows="3" required placeholder="Tulis pertanyaan angket..."
                        style="width: 100%; padding: 10px; border: 2px solid #e2e8f0; border-radius: 8px;"></textarea>
                </div>
                <div style="margin-bottom: 20px;">
                    <label style="display: flex; align-items: center; gap: 8px;">
                        <input type="checkbox" name="is_reverse_scored" value="1">
                        <span>Reverse Scored (skor dibalik)</span>
                    </label>
                    <small style="color: var(--gray); display: block; margin-top: 5px;">Centang jika jawaban 5 menjadi
                        1, 4 menjadi 2, dst.</small>
                </div>
                <div
                    style="background: #f0f4ff; padding: 15px; border-radius: 8px; border-left: 4px solid var(--primary);">
                    <strong style="color: var(--primary);">Skala Likert Default:</strong>
                    <div style="margin-top: 10px; color: var(--dark);">
                        1 = Sangat Tidak Setuju<br>
                        2 = Tidak Setuju<br>
                        3 = Netral<br>
                        4 = Setuju<br>
                        5 = Sangat Setuju
                    </div>
                </div>
            </div>
            <div
                style="padding: 20px 30px; border-top: 2px solid var(--light); display: flex; justify-content: flex-end; gap: 10px;">
                <button type="button" onclick="document.getElementById('addQuestionModal').style.display='none'"
                    class="btn-secondary">
                    Batal
                </button>
                <button type="submit" class="btn-primary">
                    <i class="fas fa-save"></i> Simpan
                </button>
            </div>
        </form>
    </div>
</div>

<style>
.alert {
    padding: 15px 20px;
    border-radius: 8px;
    margin-bottom: 20px;
    display: flex;
    align-items: center;
    gap: 10px;
}

.alert-success {
    background: #c6f6d5;
    color: #22543d;
    border-left: 4px solid #22543d;
}

.alert-danger {
    background: #fed7d7;
    color: #742a2a;
    border-left: 4px solid #742a2a;
}
</style>
@endsection