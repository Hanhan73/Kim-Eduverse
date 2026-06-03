{{-- resources/views/training/partials/quiz-form.blade.php --}}
{{-- Variables: $attempt, $quizType (pre/post), $token, $training --}}

@php
    $questionOrder   = $attempt->question_order ?? [];
    $allQuestions    = $training->questions->keyBy('id');
    $questions       = collect($questionOrder)->map(fn($id) => $allQuestions->get($id))->filter()->values();
    if ($questions->isEmpty()) $questions = $training->questions->values();

    $savedAnswers = $attempt->answers ?? [];
    if (is_string($savedAnswers)) $savedAnswers = json_decode($savedAnswers, true);
    if (!is_array($savedAnswers)) $savedAnswers = [];

    $shuffledOptions = $attempt->shuffled_options ?? [];
    if (is_string($shuffledOptions)) $shuffledOptions = json_decode($shuffledOptions, true);
    if (!is_array($shuffledOptions)) $shuffledOptions = [];

    $totalSeconds    = ($quizType === 'pre' ? 30 : 60) * 60;
    $elapsed         = (int) now()->diffInSeconds($attempt->started_at);
    $remaining       = (int) max(0, $totalSeconds - $elapsed);
    $displayLabels   = ['A','B','C','D','E'];
    $alreadyAnswered = count(array_filter($savedAnswers, fn($v) => $v !== null && $v !== ''));
@endphp

<div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:14px; flex-wrap:wrap; gap:10px;">
    <div id="quiz-timer" style="display:inline-flex; align-items:center; gap:8px; background:#eff6ff; color:#667eea; padding:8px 16px; border-radius:10px; font-weight:700; font-size:1rem; transition:all .3s;">
        <i class="fas fa-clock"></i> <span id="timer-display">{{ gmdate('i:s', $remaining) }}</span>
    </div>
    <div style="font-size:0.82rem; color:#6b7280;">
        {{ $questions->count() }} soal @if($quizType === 'post')&nbsp;·&nbsp; Min. lulus: <strong>60%</strong>@endif
    </div>
</div>

<div style="background:#e5e7eb; border-radius:10px; height:7px; margin-bottom:18px; overflow:hidden;">
    <div id="progress-bar" style="height:100%; background:linear-gradient(90deg,#667eea,#764ba2); border-radius:10px; transition:width .4s; width:{{ $questions->count() > 0 ? ($alreadyAnswered / $questions->count() * 100) : 0 }}%;"></div>
</div>

<form method="POST" action="{{ route('training.participant.quiz.submit', [$token, $quizType]) }}" id="quiz-form">
    @csrf

    <div style="padding:14px; background:#f8f9fa; border-radius:12px; margin-bottom:20px;">
        <div style="font-size:0.75rem; color:#6b7280; font-weight:600; margin-bottom:10px;">NAVIGASI SOAL</div>
        <div style="display:flex; flex-wrap:wrap; gap:8px;">
            @foreach($questions as $i => $q)
            @php $isAnswered = isset($savedAnswers[$q->id]) && $savedAnswers[$q->id] !== ''; @endphp
            <button type="button" id="nav-{{ $i }}" onclick="scrollToQuestion({{ $i }})"
                style="width:34px; height:34px; border-radius:50%; border:2px solid {{ $isAnswered ? '#10b981' : '#e5e7eb' }}; background:{{ $isAnswered ? '#10b981' : 'white' }}; color:{{ $isAnswered ? 'white' : '#374151' }}; font-size:0.8rem; font-weight:600; cursor:pointer; transition:all .2s;">
                {{ $i + 1 }}
            </button>
            @endforeach
        </div>
    </div>

    @foreach($questions as $i => $q)
    @php
        $savedAnswer = isset($savedAnswers[$q->id]) ? strtoupper($savedAnswers[$q->id]) : null;
        if (isset($shuffledOptions[$q->id]) && !empty($shuffledOptions[$q->id])) {
            $optsOrder = $shuffledOptions[$q->id];
        } else {
            $optsOrder = array_values(array_filter(['A','B','C','D','E'], fn($o) => !empty($q->{'option_'.strtolower($o)})));
        }
        $optsOrder = array_values(array_filter($optsOrder, fn($o) => !empty($q->{'option_'.strtolower($o)})));
    @endphp

    <div id="q-{{ $i }}" style="background:white; border:2px solid #f0f0f0; border-radius:14px; padding:22px; margin-bottom:14px;">
        <div style="display:flex; gap:12px; margin-bottom:18px; align-items:flex-start;">
            <div style="min-width:32px; height:32px; border-radius:50%; background:linear-gradient(135deg,#667eea,#764ba2); color:white; display:flex; align-items:center; justify-content:center; font-size:0.82rem; font-weight:700; flex-shrink:0; margin-top:2px;">{{ $i + 1 }}</div>
            <div style="font-size:0.95rem; color:#1e293b; line-height:1.7; font-weight:500; padding-top:4px;">{!! nl2br(e($q->question)) !!}</div>
        </div>

        <div style="display:flex; flex-direction:column; gap:8px; padding-left:44px;">
            @foreach($optsOrder as $idx => $originalOpt)
            @php
                $displayLabel = $displayLabels[$idx] ?? chr(65 + $idx);
                $optText      = $q->{'option_'.strtolower($originalOpt)} ?? '';
                $isSelected   = ($savedAnswer === strtoupper($originalOpt));
            @endphp
            <label id="label-{{ $q->id }}-{{ $originalOpt }}"
                onclick="selectAnswer({{ $q->id }}, '{{ strtoupper($originalOpt) }}', {{ $i }}, '{{ $originalOpt }}')"
                style="display:flex; align-items:center; gap:12px; padding:11px 16px; border:2px solid {{ $isSelected ? '#667eea' : '#e5e7eb' }}; border-radius:10px; background:{{ $isSelected ? '#eff6ff' : '#fafafa' }}; cursor:pointer; font-size:0.9rem; transition:all .2s; user-select:none;">
                <input type="radio" name="answers[{{ $q->id }}]" value="{{ strtoupper($originalOpt) }}" {{ $isSelected ? 'checked' : '' }} style="display:none;">
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

    <div style="background:#fef3c7; border:1px solid #fcd34d; border-radius:10px; padding:13px 16px; margin-bottom:16px; font-size:0.875rem; color:#92400e;">
        <i class="fas fa-info-circle"></i> Pastikan semua <strong>{{ $questions->count() }} soal</strong> sudah dijawab. Jawaban tidak bisa diubah setelah submit.
    </div>

    <div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:12px;">
        <div style="font-size:0.875rem; color:#6b7280;">Terjawab: <strong id="count-num">{{ $alreadyAnswered }}</strong> / {{ $questions->count() }}</div>
        <button type="button" onclick="confirmSubmit()"
            style="display:inline-flex; align-items:center; gap:8px; background:linear-gradient(135deg,#667eea,#764ba2); color:white; padding:12px 28px; border-radius:10px; border:none; font-weight:600; cursor:pointer; font-size:0.95rem;">
            <i class="fas fa-paper-plane"></i> Submit {{ $quizType === 'pre' ? 'Pre-Test' : 'Post-Test' }}
        </button>
    </div>
</form>

<script>
const totalQuestions = {{ $questions->count() }};

function selectAnswer(qid, originalOpt, questionIndex, rawOpt) {
    const card = document.getElementById('q-' + questionIndex);
    card.querySelectorAll('label[id^="label-' + qid + '-"]').forEach(l => { l.style.borderColor='#e5e7eb'; l.style.background='#fafafa'; });
    card.querySelectorAll('div[id^="dot-' + qid + '-"]').forEach(d => { d.style.borderColor='#d1d5db'; d.style.background='white'; d.style.color='#6b7280'; });
    const lbl = document.getElementById('label-' + qid + '-' + rawOpt);
    const dot = document.getElementById('dot-'   + qid + '-' + rawOpt);
    if (lbl) { lbl.style.borderColor='#667eea'; lbl.style.background='#eff6ff'; }
    if (dot) { dot.style.borderColor='#667eea'; dot.style.background='#667eea'; dot.style.color='white'; }
    const radio = card.querySelector('input[name="answers[' + qid + ']"][value="' + originalOpt + '"]');
    if (radio) radio.checked = true;
    const nav = document.getElementById('nav-' + questionIndex);
    if (nav) { nav.style.background='#10b981'; nav.style.borderColor='#10b981'; nav.style.color='white'; }
    updateProgress();
    const fd = new FormData();
    fd.append('_token', document.querySelector('meta[name=csrf-token]').content);
    fd.append('question_id', qid); fd.append('answer', originalOpt); fd.append('attempt_id', {{ $attempt->id }});
    fetch('{{ route("training.participant.quiz.save-answer", $token) }}', { method:'POST', body:fd }).catch(()=>{});
}

function updateProgress() {
    const checked = document.querySelectorAll('#quiz-form input[type=radio]:checked').length;
    const el = document.getElementById('count-num'); const bar = document.getElementById('progress-bar');
    if (el) el.textContent = checked;
    if (bar) bar.style.width = (checked / totalQuestions * 100) + '%';
}

function scrollToQuestion(i) { const el = document.getElementById('q-' + i); if (el) el.scrollIntoView({behavior:'smooth', block:'center'}); }

function confirmSubmit() {
    const u = totalQuestions - document.querySelectorAll('#quiz-form input[type=radio]:checked').length;
    if (confirm(u > 0 ? u + ' soal belum dijawab. Tetap submit?' : 'Yakin ingin submit?'))
        document.getElementById('quiz-form').submit();
}

let seconds = {{ $remaining }};
const timerEl = document.getElementById('timer-display');
const timerBox = document.getElementById('quiz-timer');
function renderTimer() {
    const m = String(Math.floor(seconds/60)).padStart(2,'0'), s = String(seconds%60).padStart(2,'0');
    if (timerEl) timerEl.textContent = m+':'+s;
    if (!timerBox) return;
    if (seconds<=60) { timerBox.style.background='#fee2e2'; timerBox.style.color='#dc2626'; }
    else if (seconds<=300) { timerBox.style.background='#fef3c7'; timerBox.style.color='#d97706'; }
    else { timerBox.style.background='#eff6ff'; timerBox.style.color='#667eea'; }
}
renderTimer();
const iv = setInterval(() => {
    if (seconds<=0) { clearInterval(iv); if(timerEl) timerEl.textContent='00:00'; alert('Waktu habis!'); document.getElementById('quiz-form')?.submit(); return; }
    seconds--; renderTimer();
}, 1000);
updateProgress();
</script>