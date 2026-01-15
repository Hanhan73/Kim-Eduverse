<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Kelola Artikel</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: 'Inter', -apple-system, sans-serif;
        background: #f8f9fa;
        color: #2d3748;
    }

    /* Sidebar */
    .admin-layout {
        display: grid;
        grid-template-columns: 250px 1fr;
        min-height: 100vh;
    }

    .sidebar {
        background: linear-gradient(135deg, #667eea, #764ba2);
        color: white;
        padding: 2rem 0;
    }

    .sidebar-brand {
        padding: 0 1.5rem 2rem;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        margin-bottom: 2rem;
    }

    .sidebar-brand h2 {
        font-size: 1.5rem;
        font-weight: 700;
    }

    .sidebar-brand p {
        font-size: 0.85rem;
        opacity: 0.8;
        margin-top: 0.5rem;
    }

    .sidebar-menu {
        list-style: none;
    }

    .sidebar-item {
        margin-bottom: 0.5rem;
    }

    .sidebar-link {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 12px 1.5rem;
        color: white;
        text-decoration: none;
        transition: all 0.3s;
    }

    .sidebar-link:hover,
    .sidebar-link.active {
        background: rgba(255, 255, 255, 0.1);
        border-left: 3px solid white;
    }

    /* Main Content */
    .main-content {
        padding: 2rem;
    }

    .admin-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
    }

    .admin-header h1 {
        font-size: 2rem;
        font-weight: 700;
    }

    .btn {
        padding: 12px 24px;
        border-radius: 8px;
        border: none;
        font-weight: 600;
        cursor: pointer;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s;
    }

    .btn-primary {
        background: linear-gradient(135deg, #667eea, #764ba2);
        color: white;
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(102, 126, 234, 0.3);
    }

    .btn-danger {
        background: #e53e3e;
        color: white;
    }

    .btn-success {
        background: #48bb78;
        color: white;
    }

    .btn-sm {
        padding: 8px 16px;
        font-size: 0.875rem;
    }

    /* Stats */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .stat-card {
        background: white;
        padding: 1.5rem;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    }

    .stat-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 1rem;
    }

    .stat-icon {
        width: 50px;
        height: 50px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        color: white;
    }

    .stat-number {
        font-size: 2rem;
        font-weight: 700;
        color: #2d3748;
    }

    .stat-label {
        color: #718096;
        font-size: 0.9rem;
        margin-top: 0.5rem;
    }

    /* Alert */
    .alert {
        padding: 1rem 1.5rem;
        border-radius: 8px;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .alert-success {
        background: #d4edda;
        color: #155724;
        border-left: 4px solid #48bb78;
    }

    /* Table */
    .table-container {
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        overflow: hidden;
    }

    .table-header {
        padding: 1.5rem;
        border-bottom: 1px solid #e2e8f0;
    }

    .table-header h2 {
        font-size: 1.25rem;
        font-weight: 600;
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    thead {
        background: #f7fafc;
    }

    th {
        padding: 1rem 1.5rem;
        text-align: left;
        font-weight: 600;
        color: #4a5568;
        font-size: 0.875rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    td {
        padding: 1rem 1.5rem;
        border-bottom: 1px solid #e2e8f0;
    }

    tbody tr:hover {
        background: #f7fafc;
    }

    .article-title {
        font-weight: 600;
        color: #2d3748;
        max-width: 300px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .badge {
        padding: 4px 12px;
        border-radius: 50px;
        font-size: 0.75rem;
        font-weight: 600;
    }

    .badge-published {
        background: #d4edda;
        color: #155724;
    }

    .badge-draft {
        background: #fff3cd;
        color: #856404;
    }

    .action-buttons {
        display: flex;
        gap: 8px;
    }

    .btn-icon {
        width: 32px;
        height: 32px;
        padding: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 6px;
        border: none;
        cursor: pointer;
        transition: all 0.3s;
    }

    .btn-icon.edit {
        background: #e6f2ff;
        color: #3182ce;
    }

    .btn-icon.delete {
        background: #fff5f5;
        color: #e53e3e;
    }

    .btn-icon:hover {
        transform: scale(1.1);
    }

    /* Pagination */
    .pagination {
        display: flex;
        justify-content: center;
        gap: 8px;
        padding: 1.5rem;
    }

    .pagination a,
    .pagination span {
        padding: 8px 12px;
        border-radius: 6px;
        text-decoration: none;
        color: #4a5568;
    }

    .pagination a:hover {
        background: #e2e8f0;
    }

    .pagination .active {
        background: linear-gradient(135deg, #667eea, #764ba2);
        color: white;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .admin-layout {
            grid-template-columns: 1fr;
        }

        .sidebar {
            display: none;
        }

        .main-content {
            padding: 1rem;
        }

        .table-container {
            overflow-x: auto;
        }
    }
    </style>
</head>

<body>
    <div class="admin-layout">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="sidebar-brand">
                <h2><i class="fas fa-newspaper"></i>KIM EDUVERSE</h2>
                <p>Admin Panel</p>
            </div>

            <!-- Admin Info -->
            <div style="padding: 0 1.5rem 1rem; border-bottom: 1px solid rgba(255,255,255,0.1); margin-bottom: 1rem;">
                <div
                    style="display: flex; align-items: center; gap: 12px; background: rgba(255,255,255,0.1); padding: 12px; border-radius: 10px;">
                    <div
                        style="width: 40px; height: 40px; background: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: #667eea; font-weight: 700; font-size: 1.2rem;">
                        {{ substr(session('admin_name'), 0, 1) }}
                    </div>
                    <div style="flex: 1; min-width: 0;">
                        <div
                            style="font-weight: 600; font-size: 0.95rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                            {{ session('admin_name') }}
                        </div>
                        <div
                            style="font-size: 0.8rem; opacity: 0.8; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                            {{ session('admin_email') }}
                        </div>
                    </div>
                </div>
            </div>

            <ul class="sidebar-menu">
                <li class="sidebar-item">
                    <a href="{{ route('admin.articles.index') }}" class="sidebar-link active">
                        <i class="fas fa-file-alt"></i>
                        <span>Artikel</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a href="{{ route('home') }}" class="sidebar-link">
                        <i class="fas fa-globe"></i>
                        <span>Lihat Website</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a href="{{ route('blog.index') }}" class="sidebar-link">
                        <i class="fas fa-blog"></i>
                        <span>Lihat Blog</span>
                    </a>
                </li>
                <li class="sidebar-item"
                    style="margin-top: auto; padding-top: 1rem; border-top: 1px solid rgba(255,255,255,0.1);">
                    <form action="{{ route('admin.logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="sidebar-link"
                            style="width: 100%; background: none; border: none; cursor: pointer; text-align: left; color: white;">
                            <i class="fas fa-sign-out-alt"></i>
                            <span>Logout</span>
                        </button>
                    </form>
                </li>
            </ul>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <!-- Header -->
            <div class="admin-header">
                <h1>Kelola Artikel</h1>
                <a href="{{ route('admin.articles.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Buat Artikel Baru
                </a>
            </div>

            <!-- Success Alert -->
            @if(session('success'))
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i>
                {{ session('success') }}
            </div>
            @endif

            <!-- Stats -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-header">
                        <div>
                            <div class="stat-number">{{ $stats['total'] }}</div>
                            <div class="stat-label">Total Artikel</div>
                        </div>
                        <div class="stat-icon" style="background: linear-gradient(135deg, #667eea, #764ba2);">
                            <i class="fas fa-file-alt"></i>
                        </div>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-header">
                        <div>
                            <div class="stat-number">{{ $stats['published'] }}</div>
                            <div class="stat-label">Dipublikasikan</div>
                        </div>
                        <div class="stat-icon" style="background: linear-gradient(135deg, #48bb78, #38a169);">
                            <i class="fas fa-check-circle"></i>
                        </div>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-header">
                        <div>
                            <div class="stat-number">{{ $stats['draft'] }}</div>
                            <div class="stat-label">Draft</div>
                        </div>
                        <div class="stat-icon" style="background: linear-gradient(135deg, #f59e0b, #d97706);">
                            <i class="fas fa-edit"></i>
                        </div>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-header">
                        <div>
                            <div class="stat-number">{{ number_format($stats['total_views']) }}</div>
                            <div class="stat-label">Total Views</div>
                        </div>
                        <div class="stat-icon" style="background: linear-gradient(135deg, #3182ce, #2c5aa0);">
                            <i class="fas fa-eye"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Articles Table -->
            <div class="table-container">
                <div class="table-header">
                    <h2>Daftar Artikel</h2>
                </div>

                <table>
                    <thead>
                        <tr>
                            <th>Judul</th>
                            <th>Kategori</th>
                            <th>Penulis</th>
                            <th>Status</th>
                            <th>Views</th>
                            <th>Tanggal</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($articles as $article)
                        <tr>
                            <td>
                                <div class="article-title" title="{{ $article->title }}">
                                    {{ $article->title }}
                                </div>
                            </td>
                            <td>{{ $article->category }}</td>
                            <td>{{ $article->author }}</td>
                            <td>
                                <form action="{{ route('admin.articles.toggle-publish', $article) }}" method="POST"
                                    style="display: inline;">
                                    @csrf
                                    <button type="submit"
                                        class="badge {{ $article->is_published ? 'badge-published' : 'badge-draft' }}"
                                        style="border: none; cursor: pointer;">
                                        {{ $article->is_published ? 'Published' : 'Draft' }}
                                    </button>
                                </form>
                            </td>
                            <td>{{ number_format($article->views) }}</td>
                            <td>{{ $article->created_at->format('d M Y') }}</td>
                            <td>
                                <div class="action-buttons">
                                    <a href="{{ route('admin.articles.edit', $article) }}" class="btn-icon edit"
                                        title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.articles.destroy', $article) }}" method="POST"
                                        style="display: inline;"
                                        onsubmit="return confirm('Yakin ingin menghapus artikel ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-icon delete" title="Hapus">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" style="text-align: center; padding: 3rem; color: #718096;">
                                <i class="fas fa-inbox"
                                    style="font-size: 3rem; margin-bottom: 1rem; display: block;"></i>
                                Belum ada artikel. <a href="{{ route('admin.articles.create') }}"
                                    style="color: #667eea;">Buat artikel pertama</a>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>

                <!-- Pagination -->
                <div class="pagination">
                    {{ $articles->links() }}
                </div>
            </div>
        </main>
    </div>

    <script>
    // Auto hide alert after 5 seconds
    setTimeout(() => {
        const alert = document.querySelector('.alert');
        if (alert) {
            alert.style.transition = 'all 0.5s ease';
            alert.style.opacity = '0';
            alert.style.transform = 'translateY(-10px)';
            setTimeout(() => alert.remove(), 500);
        }
    }, 5000);
    </script>
</body>

</html>