@extends('layouts.admin-digital')

@section('title', 'Kelola Quiz Seminar')
@section('page-title', 'Kelola Quiz Seminar')

@section('styles')
<style>
:root {
    --primary: #667eea;
    --primary-dark: #5a67d8;
    --secondary: #764ba2;
    --success: #48bb78;
    --warning: #ed8936;
    --danger: #f56565;
    --info: #4299e1;
    --dark: #2d3748;
    --gray: #718096;
    --light: #f7fafc;
}

.page-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 25px;
}

.page-header h1 {
    font-size: 1.8rem;
    font-weight: 700;
    color: var(--dark);
}

.page-header p {
    color: var(--gray);
    margin-top: 5px;
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 20px;
    margin-bottom: 30px;
}

.stat-card {
    background: white;
    border-radius: 15px;
    padding: 20px;
    display: flex;
    align-items: center;
    gap: 15px;
    box-shadow: 0 2px 15px rgba(0, 0, 0, 0.05);
}

.stat-icon {
    width: 55px;
    height: 55px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    color: white;
}

.stat-icon.primary {
    background: linear-gradient(135deg, var(--primary), var(--secondary));
}

.stat-icon.success {
    background: linear-gradient(135deg, #38a169, #48bb78);
}

.stat-icon.warning {
    background: linear-gradient(135deg, #dd6b20, #ed8936);
}

.stat-icon.info {
    background: linear-gradient(135deg, #2b6cb0, #4299e1);
}

.stat-content h4 {
    font-size: 1.8rem;
    font-weight: 700;
    color: var(--dark);
    margin: 0;
}

.stat-content p {
    color: var(--gray);
    font-size: 0.9rem;
    margin: 0;
}

.card {
    background: white;
    border-radius: 15px;
    box-shadow: 0 2px 15px rgba(0, 0, 0, 0.05);
    margin-bottom: 25px;
    overflow: hidden;
}

.card-header {
    padding: 20px 25px;
    border-bottom: 1px solid #edf2f7;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.card-header h3 {
    font-size: 1.1rem;
    font-weight: 600;
    color: var(--dark);
    margin: 0;
    display: flex;
    align-items: center;
    gap: 10px;
}

.card-body {
    padding: 25px;
}

.search-filter-bar {
    display: flex;
    gap: 15px;
    align-items: center;
}

.search-box {
    position: relative;
    flex: 1;
    max-width: 400px;
}

.search-box i {
    position: absolute;
    left: 15px;
    top: 50%;
    transform: translateY(-50%);
    color: var(--gray);
}

.search-box input {
    width: 100%;
    padding: 10px 15px 10px 45px;
    border: 2px solid #e2e8f0;
    border-radius: 8px;
    font-size: 0.95rem;
}

.filter-select {
    padding: 10px 15px;
    border: 2px solid #e2e8f0;
    border-radius: 8px;
    font-size: 0.95rem;
}

.table-responsive {
    overflow-x: auto;
}

table {
    width: 100%;
    border-collapse: collapse;
}

table thead {
    background: #f8fafc;
}

table th {
    padding: 15px;
    text-align: left;
    font-weight: 600;
    color: var(--dark);
    border-bottom: 2px solid #e2e8f0;
}

table td {
    padding: 15px;
    border-bottom: 1px solid #edf2f7;
}

table tbody tr:hover {
    background: #f8fafc;
}

.quiz-info {
    display: flex;
    align-items: center;
    gap: 15px;
}

.quiz-icon {
    width: 50px;
    height: 50px;
    border-radius: 10px;
    background: linear-gradient(135deg, var(--primary), var(--secondary));
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.3rem;
    flex-shrink: 0;
}

.quiz-details h4 {
    margin: 0 0 5px 0;
    font-size: 1rem;
    font-weight: 600;
    color: var(--dark);
}

.quiz-details p {
    margin: 0;
    font-size: 0.85rem;
    color: var(--gray);
}

.seminar-tags {
    display: flex;
    flex-direction: column;
    gap: 5px;
}

.seminar-tag {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 5px 10px;
    background: #f0f9ff;
    border: 1px solid #0284c7;
    border-radius: 6px;
    font-size: 0.8rem;
    color: #0c4a6e;
}

.seminar-tag.pre-test {
    background: #ecfdf5;
    border-color: #10b981;
    color: #064e3b;
}

.seminar-tag.post-test {
    background: #fef3c7;
    border-color: #f59e0b;
    color: #78350f;
}

.badge {
    display: inline-flex;
    align-items: center;
    padding: 5px 12px;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 600;
}

.badge-success {
    background: #c6f6d5;
    color: #22543d;
}

.badge-danger {
    background: #fed7d7;
    color: #742a2a;
}

.badge-info {
    background: #bee3f8;
    color: #2a4365;
}

.badge-warning {
    background: #feebc8;
    color: #744210;
}

.btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 10px 20px;
    border-radius: 10px;
    font-weight: 600;
    text-decoration: none;
    border: none;
    cursor: pointer;
    transition: all 0.3s ease;
    font-size: 0.9rem;
}

.btn-primary {
    background: linear-gradient(135deg, var(--primary), var(--secondary));
    color: white;
}

.btn-secondary {
    background: #edf2f7;
    color: var(--dark);
}

.btn-info {
    background: linear-gradient(135deg, #2b6cb0, #4299e1);
    color: white;
}

.btn-warning {
    background: linear-gradient(135deg, #dd6b20, #ed8936);
    color: white;
}

.btn-danger {
    background: linear-gradient(135deg, #c53030, #f56565);
    color: white;
}

.btn-sm {
    padding: 6px 12px;
    font-size: 0.85rem;
}

.btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
}

.empty-state {
    text-align: center;
    padding: 60px 20px;
}

.empty-state i {
    font-size: 4rem;
    color: #cbd5e0;
    margin-bottom: 20px;
}

.empty-state h3 {
    color: var(--dark);
    margin-bottom: 10px;
}

.empty-state p {
    color: var(--gray);
    margin-bottom: 20px;
}

.alert {
    padding: 15px 20px;
    border-radius: 10px;
    margin-bottom: 20px;
    display: flex;
    align-items: center;
    gap: 10px;
}

.alert-success {
    background: #c6f6d5;
    color: #22543d;
    border-left: 4px solid #38a169;
}

.alert-danger {
    background: #fed7d7;
    color: #742a2a;
    border-left: 4px solid #e53e3e;
}

@media (max-width: 1024px) {
    .stats-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 768px) {
    .stats-grid {
        grid-template-columns: 1fr;
    }

    .search-filter-bar {
        flex-direction: column;
    }

    .search-box {
        max-width: 100%;
    }
}
</style>
@endsection

@section('content')
<!-- Page Header -->
<div class="page-header">
    <div>
        <h1>Kelola Quiz Seminar</h1>
        <p>Kelola Pre-Test dan Post-Test untuk Seminar On Demand</p>
    </div>
    <a href="{{ route('admin.digital.seminars.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Ke Seminar
    </a>
</div>

<!-- Alerts -->
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

<!-- Stats -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon primary">
            <i class="fas fa-clipboard-list"></i>
        </div>
        <div class="stat-content">
            <h4>{{ $stats['total_quizzes'] }}</h4>
            <p>Total Quiz</p>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon success">
            <i class="fas fa-clipboard-check"></i>
        </div>
        <div class="stat-content">
            <h4>{{ $stats['total_pre_tests'] }}</h4>
            <p>Pre-Test</p>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon warning">
            <i class="fas fa-clipboard-check"></i>
        </div>
        <div class="stat-content">
            <h4>{{ $stats['total_post_tests'] }}</h4>
            <p>Post-Test</p>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon info">
            <i class="fas fa-question-circle"></i>
        </div>
        <div class="stat-content">
            <h4>{{ $stats['total_questions'] }}</h4>
            <p>Total Pertanyaan</p>
        </div>
    </div>
</div>

<!-- Main Card -->
<div class="card">
    <div class="card-header">
        <div class="search-filter-bar">
            <div class="search-box">
                <i class="fas fa-search"></i>
                <input type="text" id="searchInput" placeholder="Cari quiz...">
            </div>
            <select id="filterType" class="filter-select">
                <option value="">Semua Tipe</option>
                <option value="pre-test">Pre-Test</option>
                <option value="post-test">Post-Test</option>
            </select>
            <select id="filterStatus" class="filter-select">
                <option value="">Semua Status</option>
                <option value="active">Aktif</option>
                <option value="inactive">Nonaktif</option>
            </select>
        </div>
    </div>

    <div class="card-body" style="padding: 0;">
        @if($quizzes->count() > 0)
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>Quiz</th>
                        <th>Digunakan di Seminar</th>
                        <th>Pertanyaan</th>
                        <th>Durasi</th>
                        <th>Passing Score</th>
                        <th>Status</th>
                        <th style="width: 150px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($quizzes as $quiz)
                    <tr>
                        <td>
                            <div class="quiz-info">
                                <div class="quiz-icon">
                                    <i class="fas fa-clipboard-list"></i>
                                </div>
                                <div class="quiz-details">
                                    <h4>{{ $quiz->title }}</h4>
                                    @if($quiz->description)
                                    <p>{{ Str::limit($quiz->description, 50) }}</p>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="seminar-tags">
                                @foreach($quiz->preTestSeminars as $seminar)
                                <a href="{{ route('admin.digital.seminars.show', $seminar) }}"
                                    class="seminar-tag pre-test" style="text-decoration: none;">
                                    <i class="fas fa-play-circle"></i> Pre-Test: {{ Str::limit($seminar->title, 30) }}
                                </a>
                                @endforeach

                                @foreach($quiz->postTestSeminars as $seminar)
                                <a href="{{ route('admin.digital.seminars.show', $seminar) }}"
                                    class="seminar-tag post-test" style="text-decoration: none;">
                                    <i class="fas fa-check-circle"></i> Post-Test: {{ Str::limit($seminar->title, 30) }}
                                </a>
                                @endforeach
                            </div>
                        </td>
                        <td>
                            <div style="display: flex; align-items: center; gap: 8px;">
                                <i class="fas fa-question-circle" style="color: var(--info);"></i>
                                <strong>{{ $quiz->questions_count }}</strong>
                                <span style="color: var(--gray); font-size: 0.85rem;">soal</span>
                            </div>
                            <div style="margin-top: 5px;">
                                <span class="badge badge-info">{{ $quiz->total_points }} poin</span>
                            </div>
                        </td>
                        <td>
                            <i class="fas fa-clock" style="color: var(--warning);"></i>
                            {{ $quiz->duration_minutes }} menit
                        </td>
                        <td>
                            <span class="badge badge-warning">{{ $quiz->passing_score }}%</span>
                        </td>
                        <td>
                            <form action="{{ route('admin.digital.quizzes.toggle-active', $quiz) }}" method="POST"
                                style="display: inline;">
                                @csrf
                                @method('PATCH')
                                <button type="submit"
                                    class="badge {{ $quiz->is_active ? 'badge-success' : 'badge-danger' }}"
                                    style="border: none; cursor: pointer;">
                                    {{ $quiz->is_active ? 'Aktif' : 'Nonaktif' }}
                                </button>
                            </form>
                        </td>
                        <td>
                            <div style="display: flex; gap: 5px;">
                                <a href="{{ route('admin.digital.quizzes.edit', $quiz) }}" class="btn btn-sm btn-info"
                                    title="Edit Quiz">
                                    <i class="fas fa-edit"></i>
                                </a>

                                <form action="{{ route('admin.digital.quizzes.destroy', $quiz) }}" method="POST"
                                    style="display: inline;"
                                    onsubmit="return confirm('Yakin ingin menghapus quiz ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" title="Hapus"
                                        {{ $quiz->isUsedInSeminar() ? 'disabled' : '' }}>
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div style="padding: 20px;">
            {{ $quizzes->links('vendor.pagination.admin') }}
        </div>
        @else
        <div class="empty-state">
            <i class="fas fa-clipboard-list"></i>
            <h3>Belum Ada Quiz</h3>
            <p>Quiz akan muncul di sini setelah Anda membuat seminar dengan pre-test atau post-test</p>
            <a href="{{ route('admin.digital.seminars.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Buat Seminar Baru
            </a>
        </div>
        @endif
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const filterType = document.getElementById('filterType');
    const filterStatus = document.getElementById('filterStatus');
    const tableRows = document.querySelectorAll('tbody tr');

    function filterTable() {
        const searchTerm = searchInput.value.toLowerCase();
        const typeFilter = filterType.value;
        const statusFilter = filterStatus.value;

        tableRows.forEach(row => {
            const text = row.textContent.toLowerCase();
            const matchesSearch = text.includes(searchTerm);

            let matchesType = true;
            if (typeFilter) {
                matchesType = text.includes(typeFilter);
            }

            let matchesStatus = true;
            if (statusFilter === 'active') {
                matchesStatus = row.querySelector('.badge-success') !== null;
            } else if (statusFilter === 'inactive') {
                matchesStatus = row.querySelector('.badge-danger') !== null;
            }

            row.style.display = matchesSearch && matchesType && matchesStatus ? '' : 'none';
        });
    }

    searchInput.addEventListener('input', filterTable);
    filterType.addEventListener('change', filterTable);
    filterStatus.addEventListener('change', filterTable);
});
</script>
@endsection