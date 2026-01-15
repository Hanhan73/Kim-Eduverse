<div class="quiz-taking">
    <div class="timer-section">
        <div style="display: flex; align-items: center; gap: 15px;">
            <i class="fas fa-clock" style="font-size: 1.5rem;"></i>
            <div>
                <div style="font-size: 0.85rem; opacity: 0.8;">Sisa Waktu</div>
                <div class="timer" id="timer">{{ $quiz->duration_minutes }}:00</div>
            </div>
        </div>
        <input type="hidden" id="quizDuration" value="{{ $quiz->duration_minutes }}">
    </div>

    <!-- Question Navigator -->
    <div class="question-navigator">
        @foreach($quiz->questions as $index => $question)
        <div class="nav-question" id="nav-{{ $question->id }}" onclick="goToQuestion({{ $index }})">
            {{ $index + 1 }}
        </div>
        @endforeach
    </div>

    <form action="{{ route('digital.seminar.quiz.submit', [$order->order_number, $quizType]) }}" method="POST"
        id="quizForm">
        @csrf

        @foreach($quiz->questions as $index => $question)
        <div class="question-card" data-question-index="{{ $index }}"
            style="{{ $index === 0 ? '' : 'display: none;' }}">
            <div style="display: flex; align-items: start; margin-bottom: 20px;">
                <div class="question-number">{{ $index + 1 }}</div>
                <div class="question-text">{{ $question->question_text }}</div>
            </div>

            <div style="display: flex; flex-direction: column; gap: 10px;">
                @foreach(['A', 'B', 'C', 'D', 'E'] as $optionLetter)
                @php
                $optionField = 'option_' . strtolower($optionLetter);
                $optionText = $question->$optionField;
                @endphp

                @if($optionText)
                <label class="answer-option" onclick="selectOption(this)">
                    <input type="radio" name="answers[{{ $question->id }}]" value="{{ $optionLetter }}"
                        onchange="markAnswered({{ $question->id }})" required>
                    <span class="option-label">
                        <strong>{{ $optionLetter }}.</strong> {{ $optionText }}
                    </span>
                </label>
                @endif
                @endforeach
            </div>
        </div>
        @endforeach

        <div class="quiz-navigation">
            <div class="nav-buttons">
                <button type="button" class="btn" id="prevBtn" onclick="changeQuestion(-1)"
                    style="display: none; background: #e0e0e0; color: #333;">
                    <i class="fas fa-arrow-left"></i> Sebelumnya
                </button>
                <button type="button" class="btn btn-primary" id="nextBtn" onclick="changeQuestion(1)">
                    Selanjutnya <i class="fas fa-arrow-right"></i>
                </button>
            </div>
            <button type="submit" class="btn btn-success" id="submitBtn" style="display: none;">
                <i class="fas fa-paper-plane"></i> Kirim Jawaban
            </button>
        </div>
    </form>
</div>

<script>
    let currentQuestion = 0;
    let answeredQuestions = new Set();

    function selectOption(element) {
        const radio = element.querySelector('input[type="radio"]');
        radio.checked = true;

        // Remove selected class from siblings
        element.parentElement.querySelectorAll('.answer-option').forEach(opt => {
            opt.style.background = 'white';
            opt.style.borderColor = '#e0e0e0';
        });

        // Add selected style
        element.style.background = 'linear-gradient(135deg, #667eea, #764ba2)';
        element.style.borderColor = '#667eea';
        element.style.color = 'white';
    }

    function startTimer(duration) {
        let timeRemaining = duration * 60;
        const timerInterval = setInterval(() => {
            timeRemaining--;
            const minutes = Math.floor(timeRemaining / 60);
            const seconds = timeRemaining % 60;
            const timerElement = document.getElementById('timer');
            if (timerElement) {
                timerElement.textContent = `${minutes}:${seconds.toString().padStart(2,'0')}`;
                if (timeRemaining <= 300) timerElement.classList.add('warning');
                if (timeRemaining <= 0) {
                    clearInterval(timerInterval);
                    document.getElementById('quizForm').submit();
                }
            }
        }, 1000);
    }

    function changeQuestion(direction) {
        const questions = document.querySelectorAll('.question-card');
        questions[currentQuestion].style.display = 'none';

        const navQuestions = document.querySelectorAll('.nav-question');
        navQuestions[currentQuestion].classList.remove('current');

        currentQuestion += direction;
        questions[currentQuestion].style.display = 'block';
        navQuestions[currentQuestion].classList.add('current');

        updateNavigationButtons();
    }

    function goToQuestion(index) {
        const questions = document.querySelectorAll('.question-card');
        const navQuestions = document.querySelectorAll('.nav-question');

        questions[currentQuestion].style.display = 'none';
        navQuestions[currentQuestion].classList.remove('current');

        currentQuestion = index;
        questions[currentQuestion].style.display = 'block';
        navQuestions[currentQuestion].classList.add('current');

        updateNavigationButtons();
    }

    function updateNavigationButtons() {
        const totalQuestions = document.querySelectorAll('.question-card').length;
        const prevBtn = document.getElementById('prevBtn');
        const nextBtn = document.getElementById('nextBtn');
        const submitBtn = document.getElementById('submitBtn');

        if (prevBtn) prevBtn.style.display = currentQuestion === 0 ? 'none' : 'block';
        if (nextBtn) nextBtn.style.display = currentQuestion === totalQuestions - 1 ? 'none' : 'block';
        if (submitBtn) submitBtn.style.display = currentQuestion === totalQuestions - 1 ? 'block' : 'none';
    }

    function markAnswered(questionId) {
        answeredQuestions.add(questionId);
        const navQuestion = document.getElementById(`nav-${questionId}`);
        if (navQuestion) navQuestion.classList.add('answered');
    }

    document.addEventListener('DOMContentLoaded', () => {
        const quizForm = document.getElementById('quizForm');
        if (quizForm) {
            const durationElement = document.getElementById('quizDuration');
            if (durationElement) {
                startTimer(parseInt(durationElement.value));
            }

            updateNavigationButtons();

            const firstNavQuestion = document.querySelector('.nav-question');
            if (firstNavQuestion) firstNavQuestion.classList.add('current');

            quizForm.addEventListener('submit', e => {
                if (!confirm('Kirim jawaban Anda? Pastikan semua soal sudah dijawab.')) {
                    e.preventDefault();
                }
            });
        }
    });
</script>