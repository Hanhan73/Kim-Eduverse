{{-- resources/views/training/partials/quiz-form.blade.php --}}
{{-- Variables: $quiz, $attempt, $quizType (pre/post), $token --}}

@php
    // Ambil urutan soal yang sudah diacak saat start
    $questionOrder = json_decode($attempt->question_order ?? '[]', true);
    $questions = collect($questionOrder)
        ->map(fn($id) => $quiz->questions->firstWhere('id', $id))
        ->filter()
        ->values();
    if ($questions->isEmpty()) $questions = $quiz->questions->values();

    // Jawaban yang sudah disimpan (huruf asli DB: a/b/c/d/e)
    $savedAnswers = $attempt->answers;
    if (is_string($savedAnswers)) $savedAnswers = json_decode($savedAnswers, true);
    if (!is_array($savedAnswers)) $savedAnswers = [];

    // Urutan opsi yang sudah diacak per soal
    $shuffledOptions = json_decode($attempt->shuffled_options ?? '{}', true);
    if (!is_array($shuffledOptions)) $shuffledOptions = [];

    // Hitung sisa waktu (keduanya UTC)
    $totalSeconds = $quiz->duration_minutes * 60;
    $elapsed      = (int) now()->diffInSeconds($attempt->started_at);
    $remaining    = (int) max(0, $totalSeconds - $elapsed);

    $displayLabels = ['A', 'B', 'C', 'D', 'E'];
@endphp

{{-- Timer --}}
<div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:16px; flex-wrap:wrap; gap:10px;">
    <div id="quiz-timer" style="display:inline-flex; align-items:center; gap:8px; background:#eff6ff; color:#667eea; padding:8px 16px; border-radius:10px; font-weight:700; font-size:1rem;">
        <i class="fas fa-clock"></i>
        <span id="timer-display">{{ gmdate('i:s', $remaining) }}</span>
    </div>
    <div style="font-size:0.85rem; color:#6b7280;">
        {{ $questions->count() }} soal &nbsp;·&nbsp; Min. lulus {{ $quiz->passing_score }}%
    </div>
</div>

{{-- Progress bar --}}
<div style="background:#e5e7eb; border-radius:10px; height:6px; margin-bottom:20px; overflow:hidden;">
    <div id="progress-bar" style="height:100%; background:linear-gradient(90deg,#667eea,#764ba2); border-radius:10px; transition:width .3s; width:0%;"></div>
</div>

<form method="POST" action="{{ route('training.participant.quiz.submit', [$token, $quizType]) }}" id="quiz-form">
    @csrf

    {{-- Navigator soal --}}
    <div style="display:flex; flex-wrap:wrap; gap:8px; margin-bottom:20px; padding:14px; background:#f8f9fa; border-radius:12px;">
        <div style="width:100%; font-size:0.78rem; color:#6b7280; margin-bottom:8px; font-weight:600;">NAVIGASI SOAL</div>
        @foreach($questions as $i => $q)
        @php $isAnswered = isset($savedAnswers[$q->id]) && $savedAnswers[$q->id] !== null; @endphp
        <button type="button"
            id="nav-{{ $i }}"
            onclick="scrollToQuestion({{ $i }})"
            style="width:34px; height:34px; border-radius:50%; border:2px solid {{ $isAnswered ? '#10b981' : '#e5e7eb' }}; background:{{ $isAnswered ? '#10b981' : 'white' }}; color:{{ $isAnswered ? 'white' : '#374151' }}; font-size:0.8rem; font-weight:600; cursor:pointer; transition:all .2s;">
            {{ $i + 1 }}
        </button>
        @endforeach
    </div>

    {{-- Daftar soal --}}
    @foreach($questions as $i => $q)
    @php
        $savedAnswer = $savedAnswers[$q->id] ?? null;

        // Ambil urutan opsi untuk soal ini (huruf asli DB)
        // Jika tidak ada shuffled_options, buat urutan default
        if (isset($shuffledOptions[$q->id]) && !empty($shuffledOptions[$q->id])) {
            $optsOrder = $shuffledOptions[$q->id];
        } else {
            $optsOrder = collect(['a','b','c','d','e'])
                ->filter(fn($o) => !empty($q->{'option_'.$o}))
                ->values()
                ->toArray();
        }
        // Filter ulang pastikan opsi ada isinya
        $optsOrder = array_values(array_filter($optsOrder, fn($o) => !empty($q->{'option_'.$o})));
    @endphp

    <div class="question-card" id="q-{{ $i }}" style="background:#f8f9fa; border-radius:14px; padding:22px; margin-bottom:16px; border:2px solid transparent; transition:border-color .2s;"
        data-index="{{ $i }}">

        {{-- Nomor & teks soal --}}
        <div style="display:flex; gap:12px; margin-bottom:18px;">
            <div style="width:32px; height:32px; border-radius:50%; background:#667eea; color:white; display:flex; align-items:center; justify-content:center; font-size:0.85rem; font-weight:700; flex-shrink:0;">
                {{ $i + 1 }}
            </div>
            <div style="font-size:0.95rem; color:#1e293b; line-height:1.65; font-weight:500; padding-top:4px;">
                {!! nl2br(e($q->question_text)) !!}
            </div>
        </div>

        {{-- Opsi jawaban --}}
        <div style="display:flex; flex-direction:column; gap:8px;">
            @foreach($optsOrder as $idx => $originalOpt)
            @php
                $displayLabel = $displayLabels[$idx] ?? chr(65 + $idx); // A, B, C, D, E
                $optText      = $q->{'option_'.$originalOpt};
                $isSelected   = ($savedAnswer === $originalOpt);
            @endphp
            <label
                id="label-{{ $q->id }}-{{ $originalOpt }}"
                style="display:flex; align-items:center; gap:12px; padding:12px 16px; border:2px solid {{ $isSelected ? '#667eea' : '#e5e7eb' }}; border-radius:10px; background:{{ $isSelected ? '#eff6ff' : 'white' }}; cursor:pointer; font-size:0.9rem; transition:all .2s; user-select:none;">

                {{-- Input hidden, value = huruf ASLI dari DB --}}
                <input
                    type="radio"
                    name="answers[{{ $q->id }}]"
                    value="{{ $originalOpt }}"
                    {{ $isSelected ? 'checked' : '' }}
                    onchange="selectAnswer({{ $q->id }}, '{{ $originalOpt }}', {{ $i }})"
                    style="display:none;">

                {{-- Lingkaran label tampilan (A/B/C/D/E) --}}
                <div id="dot-{{ $q->id }}-{{ $originalOpt }}"
                    style="width:28px; height:28px; border-radius:50%; border:2px solid {{ $isSelected ? '#667eea' : '#d1d5db' }}; background:{{ $isSelected ? '#667eea' : 'white' }}; display:flex; align-items:center; justify-content:center; flex-shrink:0; font-size:0.78rem; font-weight:700; color:{{ $isSelected ? 'white' : '#6b7280' }}; transition:all .2s;">
                    {{ $displayLabel }}
                </div>

                {{-- Teks opsi --}}
                <span>{{ $optText }}</span>
            </label>
            @endforeach
        </div>
    </div>
    @endforeach

    {{-- Info & Submit --}}
    <div style="background:#fef3c7; border:1px solid #fcd34d; border-radius:10px; padding:14px; margin-bottom:16px; font-size:0.875rem; color:#92400e;">
        <i class="fas fa-info-circle"></i>
        Pastikan semua <strong>{{ $questions->count() }} soal</strong> sudah dijawab sebelum submit. Jawaban tidak bisa diubah setelah submit.
    </div>

    <div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:10px;">
        <div id="answered-count" style="font-size:0.875rem; color:#6b7280;">
            Terjawab: <strong id="count-num">{{ count(array_filter($savedAnswers)) }}</strong> / {{ $questions->count() }}
        </div>
        <button type="button" class="btn btn-primary" onclick="confirmSubmit()"
            style="background:linear-gradient(135deg,#667eea,#764ba2); color:white; padding:12px 28px; border-radius:10px; border:none; font-weight:600; cursor:pointer; font-size:0.95rem;">
            <i class="fas fa-paper-plane"></i>
            Submit {{ $quizType === 'pre' ? 'Pre-Test' : 'Post-Test' }}
        </button>
    </div>
</form>

<script>
// ─── State ────────────────────────────────────────────────
const totalQuestions = {{ $questions->count() }};
let answeredCount = {{ count(array_filter($savedAnswers)) }};

// ─── Pilih jawaban ────────────────────────────────────────
function selectAnswer(qid, originalOpt, questionIndex) {
    // Reset semua opsi di soal ini
    const card = document.getElementById('q-' + questionIndex);
    card.querySelectorAll('label').forEach(lbl => {
        lbl.style.borderColor = '#e5e7eb';
        lbl.style.background  = 'white';
    });
    card.querySelectorAll('[id^="dot-' + qid + '-"]').forEach(dot => {
        dot.style.borderColor = '#d1d5db';
        dot.style.background  = 'white';
        dot.style.color       = '#6b7280';
    });

    // Highlight yang dipilih
    const lbl = document.getElementById('label-' + qid + '-' + originalOpt);
    const dot = document.getElementById('dot-'   + qid + '-' + originalOpt);
    if (lbl) { lbl.style.borderColor = '#667eea'; lbl.style.background = '#eff6ff'; }
    if (dot) { dot.style.borderColor = '#667eea'; dot.style.background = '#667eea'; dot.style.color = 'white'; }

    // Update navigator
    const navBtn = document.getElementById('nav-' + questionIndex);
    if (navBtn) {
        navBtn.style.background   = '#10b981';
        navBtn.style.borderColor  = '#10b981';
        navBtn.style.color        = 'white';
    }

    // Update counter
    updateAnsweredCount();

    // AJAX save (simpan huruf ASLI ke DB)
    const fd = new FormData();
    fd.append('_token',      document.querySelector('meta[name=csrf-token]').content);
    fd.append('question_id', qid);
    fd.append('answer',      originalOpt);
    fd.append('attempt_id',  {{ $attempt->id }});
    fetch('{{ route("training.participant.quiz.save-answer", $token) }}', {
        method: 'POST', body: fd
    }).catch(() => {});
}

function updateAnsweredCount() {
    const checked = document.querySelectorAll('#quiz-form input[type=radio]:checked').length;
    const el = document.getElementById('count-num');
    if (el) el.textContent = checked;

    // Progress bar
    const bar = document.getElementById('progress-bar');
    if (bar) bar.style.width = (checked / totalQuestions * 100) + '%';
}

// ─── Scroll ke soal ───────────────────────────────────────
function scrollToQuestion(i) {
    const el = document.getElementById('q-' + i);
    if (el) el.scrollIntoView({ behavior: 'smooth', block: 'center' });
}

// ─── Konfirmasi submit ────────────────────────────────────
function confirmSubmit() {
    const checked = document.querySelectorAll('#quiz-form input[type=radio]:checked').length;
    const unanswered = totalQuestions - checked;

    let msg = 'Yakin ingin submit?';
    if (unanswered > 0) {
        msg = unanswered + ' soal belum dijawab. Tetap submit?';
    }
    if (confirm(msg)) {
        document.getElementById('quiz-form').submit();
    }
}

// ─── Timer ────────────────────────────────────────────────
let seconds = {{ $remaining }};
const timerEl  = document.getElementById('timer-display');
const timerBox = document.getElementById('quiz-timer');

function updateTimerDisplay() {
    const m = String(Math.floor(seconds / 60)).padStart(2, '0');
    const s = String(seconds % 60).padStart(2, '0');
    if (timerEl) timerEl.textContent = m + ':' + s;
    if (timerBox) {
        if (seconds <= 60) {
            timerBox.style.background = '#fee2e2';
            timerBox.style.color      = '#dc2626';
        } else if (seconds <= 300) {
            timerBox.style.background = '#fef3c7';
            timerBox.style.color      = '#d97706';
        }
    }
}

updateTimerDisplay();

const timerInterval = setInterval(() => {
    if (seconds <= 0) {
        clearInterval(timerInterval);
        alert('Waktu habis! Jawaban akan otomatis dikumpulkan.');
        document.getElementById('quiz-form')?.submit();
        return;
    }
    seconds--;
    updateTimerDisplay();
}, 1000);

// ─── Init progress bar ────────────────────────────────────
updateAnsweredCount();
</script>