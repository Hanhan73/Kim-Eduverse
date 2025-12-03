<div class="quiz-display">
    <div class="quiz-header-display">
        <div>
            <div class="quiz-title">{{ $quiz->title }}</div>
            <div
                style="display: inline-block; padding: 6px 15px; border-radius: 20px; font-size: 0.85rem; font-weight: 600; background: {{ $quizType === 'pre' ? '#feebc8' : '#c6f6d5' }}; color: {{ $quizType === 'pre' ? '#7c2d12' : '#22543d' }};">
                {{ $quizType === 'pre' ? 'Pre-Test' : 'Post-Test' }}
            </div>
        </div>
    </div>

    @if($quiz->description)
    <p style="color: rgba(255, 255, 255, 0.8); margin-bottom: 20px;">{{ $quiz->description }}</p>
    @endif

    <div class="quiz-info">
        <div class="quiz-info-item">
            <i class="fas fa-question-circle"></i>
            <span>{{ $quiz->questions->count() }} Pertanyaan</span>
        </div>
        <div class="quiz-info-item">
            <i class="fas fa-clock"></i>
            <span>{{ $quiz->duration_minutes }} Menit</span>
        </div>
        <div class="quiz-info-item">
            <i class="fas fa-check-circle"></i>
            <span>Passing: {{ $quiz->passing_score }}%</span>
        </div>
    </div>

    <div
        style="background: rgba(66, 153, 225, 0.1); border: 2px solid var(--info); border-radius: 10px; padding: 20px; margin: 20px 0;">
        <h4 style="margin-bottom: 10px; display: flex; align-items: center; gap: 10px;">
            <i class="fas fa-info-circle"></i> Petunjuk:
        </h4>
        <ul style="margin-left: 20px; line-height: 1.8; color: rgba(255, 255, 255, 0.8);">
            <li>Jawab semua pertanyaan dengan teliti</li>
            <li>Waktu akan dimulai setelah Anda klik "Mulai {{ $quizType === 'pre' ? 'Pre-Test' : 'Post-Test' }}"</li>
            <li>Nilai minimum untuk lulus: {{ $quiz->passing_score }}%</li>
            <li>Anda hanya dapat mengerjakan quiz ini sekali</li>
        </ul>
    </div>

    <form action="{{ route('digital.seminar.quiz.start', [$order->order_number, $quizType]) }}" method="POST">
        @csrf
        <button type="submit" class="btn btn-primary" style="font-size: 1.1rem; padding: 15px 40px;">
            <i class="fas fa-play"></i>
            Mulai {{ $quizType === 'pre' ? 'Pre-Test' : 'Post-Test' }}
        </button>
    </form>
</div>