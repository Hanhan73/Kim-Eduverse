{{-- resources/views/training/partials/quiz-form.blade.php --}}
{{-- Variables: $quiz, $attempt, $quizType (pre/post), $token --}}

@php
    // Urutan soal yang sudah diacak saat start
    $questionOrder = json_decode($attempt->question_order ?? '[]', true);
    $questions = collect($questionOrder)
        ->map(fn($id) => $quiz->questions->firstWhere('id', $id))
        ->filter()
        ->values();
    if ($questions->isEmpty()) $questions = $quiz->questions->values();

    // Jawaban yang sudah disimpan (huruf besar: A/B/C/D/E)
    $savedAnswers = $attempt->answers;
    if (is_string($savedAnswers)) $savedAnswers = json_decode($savedAnswers, true);
    if (!is_array($savedAnswers)) $savedAnswers = [];

    // Urutan opsi per soal yang sudah diacak
    $shuffledOptions = $attempt->shuffled_options ?? '{}';
    if (is_string($shuffledOptions)) $shuffledOptions = json_decode($shuffledOptions, true);
    if (!is_array($shuffledOptions)) $shuffledOptions = [];

    // Hitung sisa waktu (keduanya UTC)
    $totalSeconds = $quiz->duration_minutes * 60;
    $elapsed      = (int) now()->diffInSeconds($attempt->started_at);
    $remaining    = (int) max(0, $totalSeconds - $elapsed);

    $displayLabels = ['A', 'B', 'C', 'D', 'E'];

    // Hitung sudah berapa yang dijawab
    $alreadyAnswered = count(array_filter($savedAnswers, fn($v) => $v !== null && $v !== ''));
@endphp

{{-- Timer + Info --}}
<div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:14px; flex-wrap:wrap; gap:10px;">
    <div id="quiz-timer" style="display:inline-flex; align-items:center; gap:8px; background:#eff6ff; color:#667eea; padding:8px 16px; border-radius:10px; font-weight:700; font-size:1rem; transition:all .3s;">
        <i class="fas fa-clock"></i>
        <span id="timer-display">{{ gmdate('i:s', $remaining) }}</span>
    </div>
    <div style="font-size:0.82rem; color:#6b7280;">
        {{ $questions->count() }} soal &nbsp;·&nbsp; Min. lulus <strong>{{ $quiz->passing_score }}%</strong>
    </div>
</div>

{{-- Progress bar --}}
<div style="background:#e5e7eb; border-radius:10px; height:7px; margin-bottom:18px; overflow:hidden;">
    <div id="progress-bar" style="height:100%; background:linear-gradient(90deg,#667eea,#764ba2); border-radius:10px; transition:width .4s; width:{{ $questions->count() > 0 ? ($alreadyAnswered / $questions->count() * 100) : 0 }}%;"></div>
</div>

<form method="POST" action="{{ route('training.participant.quiz.submit', [$token, $quizType]) }}" id="quiz-form">
    @csrf

    {{-- Navigator soal --}}
    <div style="padding:14px; background:#f8f9fa; border-radius:12px; margin-bottom:20px;">
        <div style="font-size:0.75rem; color:#6b7280; font-weight:600; margin-bottom:10px; letter-spacing:.5px;">NAVIGASI SOAL</div>
        <div style="display:flex; flex-wrap:wrap; gap:8px;">
            @foreach($questions as $i => $q)
            @php $isAnswered = isset($savedAnswers[$q->id]) && $savedAnswers[$q->id] !== null && $savedAnswers[$q->id] !== ''; @endphp
            <button type="button"
                id="nav-{{ $i }}"
                onclick="scrollToQuestion({{ $i }})"
                style="width:34px; height:34px; border-radius:50%; border:2px solid {{ $isAnswered ? '#10b981' : '#e5e7eb' }}; background:{{ $isAnswered ? '#10b981' : 'white' }}; color:{{ $isAnswered ? 'white' : '#374151' }}; font-size:0.8rem; font-weight:600; cursor:pointer; transition:all .2s;">
                {{ $i + 1 }}
            </button>
            @endforeach
        </div>
    </div>

    {{-- Daftar soal --}}
    @foreach($questions as $i => $q)
    @php
        // Decode options JSON: {"A":"teks","B":"teks",...}
        $optsRaw = $q->options;
        if (is_string($optsRaw)) $optsRaw = json_decode($optsRaw, true);
        if (!is_array($optsRaw)) $optsRaw = [];

        // Jawaban yang sudah dipilih (huruf besar)
        $savedAnswer = isset($savedAnswers[$q->id]) ? strtoupper($savedAnswers[$q->id]) : null;

        // Urutan opsi (huruf besar: A/B/C/D/E) yang sudah diacak
        if (isset($shuffledOptions[$q->id]) && !empty($shuffledOptions[$q->id])) {
            $optsOrder = $shuffledOptions[$q->id];
        } else {
            $optsOrder = array_keys($optsRaw);
        }
        // Filter yang ada isinya
        $optsOrder = array_values(array_filter($optsOrder, fn($o) => isset($optsRaw[$o]) && $optsRaw[$o] !== ''));
    @endphp

    <div class="question-card" id="q-{{ $i }}"
        style="background:white; border:2px solid #f0f0f0; border-radius:14px; padding:22px; margin-bottom:14px; transition:border-color .2s;">

        {{-- Nomor & teks soal --}}
        <div style="display:flex; gap:12px; margin-bottom:18px; align-items:flex-start;">
            <div style="min-width:32px; height:32px; border-radius:50%; background:linear-gradient(135deg,#667eea,#764ba2); color:white; display:flex; align-items:center; justify-content:center; font-size:0.82rem; font-weight:700; flex-shrink:0; margin-top:2px;">
                {{ $i + 1 }}
            </div>
            <div style="font-size:0.95rem; color:#1e293b; line-height:1.7; font-weight:500;">
                {!! nl2br(e($q->question)) !!}
            </div>
        </div>

        {{-- Opsi jawaban --}}
        <div style="display:flex; flex-direction:column; gap:8px; padding-left:44px;">
            @foreach($optsOrder as $idx => $originalOpt)
            @php
                $displayLabel = $displayLabels[$idx] ?? chr(65 + $idx);
                $optText      = $optsRaw[$originalOpt] ?? '';
                $isSelected   = ($savedAnswer === strtoupper($originalOpt));
            @endphp
            <label
                id="label-{{ $q->id }}-{{ $originalOpt }}"
                onclick="selectAnswer({{ $q->id }}, '{{ strtoupper($originalOpt) }}', {{ $i }}, '{{ $originalOpt }}')"
                style="display:flex; align-items:center; gap:12px; padding:11px 16px; border:2px solid {{ $isSelected ? '#667eea' : '#e5e7eb' }}; border-radius:10px; background:{{ $isSelected ? '#eff6ff' : '#fafafa' }}; cursor:pointer; font-size:0.88rem; transition:all .2s; user-select:none;">

                {{-- Input radio - value = huruf ASLI dari DB (huruf besar) --}}
                <input
                    type="radio"
                    name="answers[{{ $q->id }}]"
                    value="{{ strtoupper($originalOpt) }}"
                    {{ $isSelected ? 'checked' : '' }}
                    style="display:none;">

                {{-- Lingkaran label tampilan A/B/C/D/E --}}
                <div id="dot-{{ $q->id }}-{{ $originalOpt }}"
                    style="min-width:28px; height:28px; border-radius:50%; border:2px solid {{ $isSelected ? '#667eea' : '#d1d5db' }}; background:{{ $isSelected ? '#667eea' : 'white' }}; display:flex; align-items:center; justify-content:center; font-size:0.75rem; font-weight:700; color:{{ $isSelected ? 'white' : '#6b7280' }}; transition:all .2s; flex-shrink:0;">
                    {{ $displayLabel }}
                </div>

                <span style="line-height:1.5;">{{ $optText }}</span>
            </label>
            @endforeach
        </div>
    </div>
    @endforeach

    {{-- Info & Submit --}}
    <div style="background:#fef3c7; border:1px solid #fcd34d; border-radius:10px; padding:13px 16px; margin-bottom:16px; font-size:0.875rem; color:#92400e;">
        <i class="fas fa-info-circle"></i>
        Pastikan semua <strong>{{ $questions->count() }} soal</strong> sudah dijawab. Jawaban tidak bisa diubah setelah submit.
    </div>

    <div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:12px;">
        <div style="font-size:0.875rem; color:#6b7280;">
            Terjawab: <strong id="count-num">{{ $alreadyAnswered }}</strong> / {{ $questions->count() }}
        </div>
        <button type="button" onclick="confirmSubmit()"
            style="display:inline-flex; align-items:center; gap:8px; background:linear-gradient(135deg,#667eea,#764ba2); color:white; padding:12px 28px; border-radius:10px; border:none; font-weight:600; cursor:pointer; font-size:0.95rem;">
            <i class="fas fa-paper-plane"></i>
            Submit {{ $quizType === 'pre' ? 'Pre-Test' : 'Post-Test' }}
        </button>
    </div>
</form>

<script>
const totalQuestions = {{ $questions->count() }};

// ─── Pilih jawaban ────────────────────────────────────────────────────────────
function selectAnswer(qid, originalOpt, questionIndex, rawOpt) {
    const card = document.getElementById('q-' + questionIndex);

    // Reset semua label di soal ini
    card.querySelectorAll('label[id^="label-' + qid + '-"]').forEach(lbl => {
        lbl.style.borderColor = '#e5e7eb';
        lbl.style.background  = '#fafafa';
    });
    card.querySelectorAll('div[id^="dot-' + qid + '-"]').forEach(dot => {
        dot.style.borderColor = '#d1d5db';
        dot.style.background  = 'white';
        dot.style.color       = '#6b7280';
    });

    // Highlight yang dipilih
    const lbl = document.getElementById('label-' + qid + '-' + rawOpt);
    const dot = document.getElementById('dot-'   + qid + '-' + rawOpt);
    if (lbl) { lbl.style.borderColor = '#667eea'; lbl.style.background = '#eff6ff'; }
    if (dot) { dot.style.borderColor = '#667eea'; dot.style.background = '#667eea'; dot.style.color = 'white'; }

    // Set radio checked
    const radio = card.querySelector('input[name="answers[' + qid + ']"][value="' + originalOpt + '"]');
    if (radio) radio.checked = true;

    // Update navigator
    const navBtn = document.getElementById('nav-' + questionIndex);
    if (navBtn) {
        navBtn.style.background  = '#10b981';
        navBtn.style.borderColor = '#10b981';
        navBtn.style.color       = 'white';
    }

    // Update counter & progress bar
    updateProgress();

    // AJAX save (simpan huruf besar ke DB)
    const fd = new FormData();
    fd.append('_token',      document.querySelector('meta[name=csrf-token]').content);
    fd.append('question_id', qid);
    fd.append('answer',      originalOpt); // huruf besar
    fd.append('attempt_id',  {{ $attempt->id }});
    fetch('{{ route("training.participant.quiz.save-answer", $token) }}', {
        method: 'POST', body: fd
    }).catch(() => {});
}

function updateProgress() {
    const checked = document.querySelectorAll('#quiz-form input[type=radio]:checked').length;
    const el  = document.getElementById('count-num');
    const bar = document.getElementById('progress-bar');
    if (el)  el.textContent  = checked;
    if (bar) bar.style.width = (checked / totalQuestions * 100) + '%';
}

// ─── Scroll ke soal ───────────────────────────────────────────────────────────
function scrollToQuestion(i) {
    const el = document.getElementById('q-' + i);
    if (el) el.scrollIntoView({ behavior: 'smooth', block: 'center' });
}

// ─── Konfirmasi submit ────────────────────────────────────────────────────────
function confirmSubmit() {
    const checked    = document.querySelectorAll('#quiz-form input[type=radio]:checked').length;
    const unanswered = totalQuestions - checked;
    let msg = unanswered > 0
        ? unanswered + ' soal belum dijawab. Tetap ingin submit?'
        : 'Yakin ingin submit? Jawaban tidak bisa diubah.';
    if (confirm(msg)) document.getElementById('quiz-form').submit();
}

// ─── Timer ────────────────────────────────────────────────────────────────────
let seconds    = {{ $remaining }};
const timerEl  = document.getElementById('timer-display');
const timerBox = document.getElementById('quiz-timer');

function renderTimer() {
    const m = String(Math.floor(seconds / 60)).padStart(2, '0');
    const s = String(seconds % 60).padStart(2, '0');
    if (timerEl) timerEl.textContent = m + ':' + s;

    if (!timerBox) return;
    if (seconds <= 60) {
        timerBox.style.background = '#fee2e2';
        timerBox.style.color      = '#dc2626';
    } else if (seconds <= 300) {
        timerBox.style.background = '#fef3c7';
        timerBox.style.color      = '#d97706';
    } else {
        timerBox.style.background = '#eff6ff';
        timerBox.style.color      = '#667eea';
    }
}

renderTimer();

const timerInterval = setInterval(() => {
    if (seconds <= 0) {
        clearInterval(timerInterval);
        if (timerEl) timerEl.textContent = '00:00';
        alert('Waktu habis! Jawaban dikumpulkan otomatis.');
        document.getElementById('quiz-form')?.submit();
        return;
    }
    seconds--;
    renderTimer();
}, 1000);

// Init
updateProgress();
</script>