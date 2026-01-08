<div class="lesson-header">
    <h1>{{ $currentLesson->title }}</h1>
    <div class="lesson-meta">
        <span><i class="fas fa-clock"></i> {{ $currentLesson->duration_minutes }} minutes</span>
        <span><i class="fas fa-{{ $currentLesson->type == 'video' ? 'play' : 'file' }}-circle"></i> {{ ucfirst($currentLesson->type) }}</span>
    </div>
</div>

@if($currentLesson->type === 'video' && $currentLesson->video_id)
    <div class="video-container">
        <iframe src="https://www.youtube.com/embed/{{ $currentLesson->video_id }}" frameborder="0"
            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
            allowfullscreen>
        </iframe>
    </div>
@elseif($currentLesson->type === 'pdf' && $currentLesson->file_path)
    <div class="pdf-viewer">
        @php
        preg_match('/\/d\/(.*?)\//', $currentLesson->file_path, $matches);
        $fileId = $matches[1] ?? null;
        @endphp
        @if($fileId)
        <iframe src="https://drive.google.com/file/d/{{ $fileId }}/preview"></iframe>
        @else
        <div class="empty-state">
            <i class="fas fa-file-pdf"></i>
            <h3>PDF cannot be displayed</h3>
            <p>Make sure the Google Drive link is correct</p>
            <a href="{{ $currentLesson->file_path }}" target="_blank" class="btn btn-next">
                <i class="fas fa-external-link-alt"></i> Open in New Tab
            </a>
        </div>
        @endif
    </div>
@elseif($currentLesson->type === 'text')
    <div class="lesson-description">
        {!! nl2br(e($currentLesson->content)) !!}
    </div>
@endif

@if($currentLesson->description)
<div class="lesson-description">
    <strong>Description:</strong><br>
    {!! nl2br(e($currentLesson->description)) !!}
</div>
@endif

<div class="lesson-actions">
    <button
        class="btn btn-complete {{ in_array($currentLesson->id, $completedLessons) ? 'completed' : '' }}"
        onclick="markAsComplete({{ $currentLesson->id }})"
        {{ in_array($currentLesson->id, $completedLessons) ? 'disabled' : '' }}>
        <i class="fas fa-check-circle"></i>
        <span>{{ in_array($currentLesson->id, $completedLessons) ? 'Completed' : 'Mark as Complete' }}</span>
    </button>

    <a href="{{ route('edutech.learning.next', $currentLesson->id) }}" class="btn btn-next">
        <span>Next Lesson</span>
        <i class="fas fa-arrow-right"></i>
    </a>
</div>