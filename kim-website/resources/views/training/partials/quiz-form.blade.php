
{{-- resources/views/training/partials/quiz-form.blade.php --}}
{{-- Variables: $quiz, $attempt, $quizType (pre/post), $token --}}

@php
    $questions = collect(json_decode($attempt->question_order ?? '[]', true))
        ->map(fn($id) => $quiz->questions->firstWhere('id', $id))
        ->filter();
    if ($questions->isEmpty()) $questions = $quiz->questions;
    
    $savedAnswers = $attempt->answers;
    if (is_string($savedAnswers)) $savedAnswers = json_decode($savedAnswers, true);
    if (!is_array($savedAnswers)) $savedAnswers = [];
    
    $totalSeconds = $quiz->duration_minutes * 60;
    // Pakai now() tanpa timezone - biarkan keduanya UTC
    $elapsed   = (int) now()->diffInSeconds($attempt->started_at);
    $remaining = (int) max(0, $totalSeconds - $elapsed);
@endphp

<div id="quiz-timer">⏱ <span id="timer-display">{{ gmdate('i:s', $remaining) }}</span></div>

<form method="POST" action="{{ route('training.participant.quiz.submit', [$token, $quizType]) }}" id="quiz-form">
    @csrf

    {{-- Navigator --}}
    <div style="display:flex; flex-wrap:wrap; gap:8px; margin-bottom:20px;">
        @foreach($questions as $i => $q)
        @php $isAnswered = isset($savedAnswers[$q->id]); @endphp
        <button type="button"
            onclick="scrollToQuestion({{ $i }})"
            style="width:34px; height:34px; border-radius:50%; border:2px solid {{ $isAnswered ? '#10b981' : '#e5e7eb' }}; background:{{ $isAnswered ? '#10b981' : 'white' }}; color:{{ $isAnswered ? 'white' : '#374151' }}; font-size:0.8rem; font-weight:600; cursor:pointer;">
            {{ $i+1 }}
        </button>
        @endforeach
    </div>

    {{-- Questions --}}
    @foreach($questions as $i => $q)
    @php $savedAnswer = $savedAnswers[$q->id] ?? null; @endphp
    <div class="question-card" id="q-{{ $i }}">
        <div class="question-text">
            <span style="display:inline-flex; align-items:center; justify-content:center; width:26px; height:26px; background:#667eea; color:white; border-radius:50%; font-size:0.75rem; font-weight:700; margin-right:8px;">{{ $i+1 }}</span>
            {!! nl2br(e($q->question_text)) !!}
        </div>

        @foreach(['a','b','c','d','e'] as $opt)
        @php $optVal = $q->{'option_'.$opt}; @endphp
        @if($optVal)
        <label style="display:flex; align-items:center; gap:10px; padding:12px 14px; margin-bottom:8px; border:2px solid {{ $savedAnswer === $opt ? '#667eea' : '#e5e7eb' }}; border-radius:10px; background:{{ $savedAnswer === $opt ? '#eff6ff' : 'white' }}; cursor:pointer; font-size:0.9rem;">
            <input type="radio" name="answers[{{ $q->id }}]" value="{{ $opt }}"
                {{ $savedAnswer === $opt ? 'checked' : '' }}
                onchange="saveAnswer({{ $q->id }}, '{{ $opt }}', this)"
                style="display:none;">
            <span style="width:24px; height:24px; border-radius:50%; border:2px solid {{ $savedAnswer === $opt ? '#667eea' : '#d1d5db' }}; background:{{ $savedAnswer === $opt ? '#667eea' : 'white' }}; display:flex; align-items:center; justify-content:center; flex-shrink:0; font-size:0.75rem; font-weight:700; color:{{ $savedAnswer === $opt ? 'white' : '#6b7280' }};">
                {{ strtoupper($opt) }}
            </span>
            {{ $optVal }}
        </label>
        @endif
        @endforeach
    </div>
    @endforeach

    <div style="background:#fef3c7; border:1px solid #fcd34d; border-radius:10px; padding:14px; margin-bottom:16px; font-size:0.875rem; color:#92400e;">
        <i class="fas fa-info-circle"></i> Pastikan semua soal sudah dijawab sebelum submit.
    </div>

    <button type="submit" class="btn btn-primary btn-block" onclick="return confirm('Yakin ingin submit? Jawaban tidak bisa diubah setelah submit.')">
        <i class="fas fa-paper-plane"></i> Submit {{ $quizType === 'pre' ? 'Pre-Test' : 'Post-Test' }}
    </button>
</form>

<script>
function scrollToQuestion(i) {
    document.getElementById('q-' + i).scrollIntoView({ behavior: 'smooth', block: 'center' });
}

function saveAnswer(qid, val, el) {
    // Update visual semua option di soal ini
    const labels = el.closest('.question-card').querySelectorAll('label');
    labels.forEach(l => {
        const isSelected = l.querySelector('input[type=radio]')?.value === val;
        l.style.borderColor = isSelected ? '#667eea' : '#e5e7eb';
        l.style.background = isSelected ? '#eff6ff' : 'white';
        const dot = l.querySelector('span');
        if (dot) {
            dot.style.borderColor = isSelected ? '#667eea' : '#d1d5db';
            dot.style.background = isSelected ? '#667eea' : 'white';
            dot.style.color = isSelected ? 'white' : '#6b7280';
        }
    });

    // AJAX save
    const formData = new FormData();
    formData.append('_token', document.querySelector('meta[name=csrf-token]').content);
    formData.append('question_id', qid);
    formData.append('answer', val);
    formData.append('attempt_id', {{ $attempt->id }});
    fetch('{{ route("training.participant.quiz.save-answer", $token) }}', {
        method: 'POST',
        body: formData
    }).catch(() => {});
}

// Timer
let seconds = {{ $remaining }};
const timerEl = document.getElementById('timer-display');
const timerBox = timerEl?.parentElement;
const interval = setInterval(() => {
    if (seconds <= 0) {
        clearInterval(interval);
        document.getElementById('quiz-form')?.submit();
        return;
    }
    seconds--;
    const m = String(Math.floor(seconds / 60)).padStart(2, '0');
    const s = String(seconds % 60).padStart(2, '0');
    if (timerEl) timerEl.textContent = m + ':' + s;
    if (timerBox && seconds < 60) timerBox.classList.add('danger');
}, 1000);
</script>