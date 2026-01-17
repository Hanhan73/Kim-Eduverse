@extends('layouts.admin-digital')

@section('title', 'Peserta Seminar')
@section('page-title', 'Peserta - ' . $seminar->title)

@section('content')
<div class="page-header">
    <div>
        <a href="{{ route('admin.digital.seminars.show', $seminar) }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Kembali ke Detail
        </a>
    </div>
    <div>
        <button onclick="exportToCSV()" class="btn btn-success">
            <i class="fas fa-file-excel"></i> Export CSV
        </button>
    </div>
</div>

<!-- Quick Stats -->
<div class="stats-grid" style="margin-bottom: 25px;">
    <div class="stat-card">
        <div class="stat-icon primary">
            <i class="fas fa-users"></i>
        </div>
        <div class="stat-content">
            <h4>{{ $enrollments->total() }}</h4>
            <p>Total Peserta</p>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon success">
            <i class="fas fa-check-circle"></i>
        </div>
        <div class="stat-content">
            <h4>{{ $enrollments->where('is_completed', true)->count() }}</h4>
            <p>Selesai</p>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon warning">
            <i class="fas fa-hourglass-half"></i>
        </div>
        <div class="stat-content">
            <h4>{{ $enrollments->where('is_completed', false)->count() }}</h4>
            <p>Dalam Progress</p>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon info">
            <i class="fas fa-certificate"></i>
        </div>
        <div class="stat-content">
            <h4>{{ $enrollments->where('certificate_generated', true)->count() }}</h4>
            <p>Sertifikat Terbit</p>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <div style="display: flex; gap: 15px; align-items: center; flex-wrap: wrap;">
            <div style="position: relative; flex: 1; min-width: 300px;">
                <i class="fas fa-search"
                    style="position: absolute; left: 15px; top: 50%; transform: translateY(-50%); color: var(--gray);"></i>
                <input type="text" id="searchInput" placeholder="Cari email peserta..."
                    style="width: 100%; padding: 10px 15px 10px 45px; border: 2px solid #e2e8f0; border-radius: 8px;">
            </div>

            <select id="filterStatus" style="padding: 10px 15px; border: 2px solid #e2e8f0; border-radius: 8px;">
                <option value="">Semua Status</option>
                <option value="completed">Selesai</option>
                <option value="in-progress">Dalam Progress</option>
                <option value="pre-test">Pre-Test Lulus</option>
                <option value="post-test">Post-Test Lulus</option>
            </select>

            <select id="filterCertificate" style="padding: 10px 15px; border: 2px solid #e2e8f0; border-radius: 8px;">
                <option value="">Semua Sertifikat</option>
                <option value="generated">Sudah Terbit</option>
                <option value="not-generated">Belum Terbit</option>
                <option value="sent">Sudah Dikirim</option>
            </select>
        </div>
    </div>

    <div class="card-body" style="padding: 0;">
        @if($enrollments->count() > 0)
        <div class="table-responsive">
            <table id="enrollmentsTable">
                <thead>
                    <tr>
                        <th style="width: 50px;">
                            <input type="checkbox" id="selectAll">
                        </th>
                        <th>Email Peserta</th>
                        <th>Order</th>
                        <th>Progress</th>
                        <th>Pre-Test</th>
                        <th>Materi</th>
                        <th>Post-Test</th>
                        <th>Sertifikat</th>
                        <th>Status</th>
                        <th>Tanggal</th>
                        <th style="width: 100px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($enrollments as $enrollment)
                    <tr data-status="{{ $enrollment->is_completed ? 'completed' : 'in-progress' }}"
                        data-pretest="{{ $enrollment->pre_test_passed ? 'yes' : 'no' }}"
                        data-posttest="{{ $enrollment->post_test_passed ? 'yes' : 'no' }}"
                        data-certificate="{{ $enrollment->certificate_generated ? 'generated' : 'not-generated' }}"
                        data-cert-sent="{{ $enrollment->certificate_sent_via_email ? 'sent' : 'not-sent' }}">
                        <td>
                            <input type="checkbox" class="row-checkbox" value="{{ $enrollment->id }}">
                        </td>
                        <td>
                            <div>
                                <strong>{{ $enrollment->customer_email }}</strong>
                                @if($enrollment->order)
                                <br>
                                <small style="color: var(--gray);">{{ $enrollment->order->customer_name }}</small>
                                @endif
                            </div>
                        </td>
                        <td>
                            @if($enrollment->order)
                            <a href="{{ route('admin.digital.orders.show', $enrollment->order->id) }}"
                                style="color: var(--primary); text-decoration: none; font-weight: 600;">
                                {{ $enrollment->order->order_number }}
                            </a>
                            @else
                            <span class="badge badge-secondary">-</span>
                            @endif
                        </td>
                        <td>
                            <div style="display: flex; align-items: center; gap: 10px;">
                                <div
                                    style="flex: 1; background: #e2e8f0; border-radius: 10px; height: 8px; overflow: hidden; min-width: 100px;">
                                    <div
                                        style="background: linear-gradient(90deg, var(--success), #38a169); height: 100%; width: {{ $enrollment->progress_percentage }}%;">
                                    </div>
                                </div>
                                <span
                                    style="font-weight: 600; color: var(--primary); white-space: nowrap;">{{ $enrollment->progress_percentage }}%</span>
                            </div>
                        </td>
                        <td>
                            @if($enrollment->pre_test_passed)
                            <span class="badge badge-success">
                                <i class="fas fa-check"></i> {{ round($enrollment->pre_test_score) }}%
                            </span>
                            @elseif($enrollment->pre_test_completed_at)
                            <span class="badge badge-danger">
                                <i class="fas fa-times"></i> {{ round($enrollment->pre_test_score) }}%
                            </span>
                            @else
                            <span class="badge badge-secondary">Belum</span>
                            @endif
                        </td>
                        <td>
                            @if($enrollment->material_viewed)
                            <span class="badge badge-success">
                                <i class="fas fa-check"></i> Sudah
                            </span>
                            <br>
                            <small
                                style="color: var(--gray);">{{ $enrollment->material_viewed_at->format('d M Y') }}</small>
                            @else
                            <span class="badge badge-secondary">Belum</span>
                            @endif
                        </td>
                        <td>
                            @if($enrollment->post_test_passed)
                            <span class="badge badge-success">
                                <i class="fas fa-check"></i> {{ round($enrollment->post_test_score) }}%
                            </span>
                            @elseif($enrollment->post_test_completed_at)
                            <span class="badge badge-danger">
                                <i class="fas fa-times"></i> {{ round($enrollment->post_test_score) }}%
                            </span>
                            @else
                            <span class="badge badge-secondary">Belum</span>
                            @endif
                        </td>
                        <td>
                            @if($enrollment->certificate_generated)
                            <div>
                                <span class="badge badge-success">
                                    <i class="fas fa-certificate"></i> Terbit
                                </span>
                                @if($enrollment->certificate_sent_via_email)
                                <br><span class="badge badge-info"><i class="fas fa-envelope"></i> Terkirim</span>
                                @endif
                            </div>
                            <div style="margin-top: 5px;">
                                <small style="color: var(--gray);">{{ $enrollment->certificate_number }}</small>
                            </div>
                            @else
                            <span class="badge badge-secondary">Belum</span>
                            @endif
                        </td>
                        <td>
                            @if($enrollment->is_completed)
                            <span class="badge badge-success">
                                <i class="fas fa-check-circle"></i> Selesai
                            </span>
                            <br>
                            <small style="color: var(--gray);">{{ $enrollment->completed_at->format('d M Y') }}</small>
                            @else
                            <span class="badge badge-warning">
                                <i class="fas fa-hourglass-half"></i> Progress
                            </span>
                            @endif
                        </td>
                        <td>
                            <div>{{ $enrollment->created_at->format('d M Y') }}</div>
                            <small style="color: var(--gray);">{{ $enrollment->created_at->format('H:i') }}</small>
                        </td>
                        <td>
                            <div style="display: flex; gap: 5px;">
                                @if($enrollment->certificate_path)
                                <a href="{{ route('digital.seminar.certificate.download', $enrollment->id) }}"
                                    class="btn btn-sm btn-success" title="Download Sertifikat" target="_blank">
                                    <i class="fas fa-download"></i>
                                </a>
                                @endif

                                @if($enrollment->order)
                                <a href="{{ route('digital.seminar.learn', $enrollment->order->order_number) }}"
                                    class="btn btn-sm btn-primary" title="Lihat Progress" target="_blank">
                                    <i class="fas fa-eye"></i>
                                </a>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div style="padding: 20px;">
            {{ $enrollments->links() }}
        </div>
        @else
        <div class="empty-state">
            <i class="fas fa-users"></i>
            <h3>Belum Ada Peserta</h3>
            <p>Peserta akan muncul di sini setelah mereka membeli seminar</p>
        </div>
        @endif
    </div>
</div>

<style>
.table-responsive {
    overflow-x: auto;
}

table {
    width: 100%;
    border-collapse: collapse;
}

th,
td {
    padding: 15px;
    text-align: left;
    border-bottom: 1px solid #e2e8f0;
}

th {
    background: #f8f9fa;
    font-weight: 600;
    color: var(--dark);
}

tbody tr:hover {
    background: #f8f9fa;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const filterStatus = document.getElementById('filterStatus');
    const filterCertificate = document.getElementById('filterCertificate');
    const tableRows = document.querySelectorAll('#enrollmentsTable tbody tr');

    function filterTable() {
        const searchTerm = searchInput.value.toLowerCase();
        const statusFilter = filterStatus.value;
        const certFilter = filterCertificate.value;

        tableRows.forEach(row => {
            const text = row.textContent.toLowerCase();
            const matchesSearch = text.includes(searchTerm);

            let matchesStatus = true;
            if (statusFilter === 'completed') {
                matchesStatus = row.dataset.status === 'completed';
            } else if (statusFilter === 'in-progress') {
                matchesStatus = row.dataset.status === 'in-progress';
            } else if (statusFilter === 'pre-test') {
                matchesStatus = row.dataset.pretest === 'yes';
            } else if (statusFilter === 'post-test') {
                matchesStatus = row.dataset.posttest === 'yes';
            }

            let matchesCert = true;
            if (certFilter === 'generated') {
                matchesCert = row.dataset.certificate === 'generated';
            } else if (certFilter === 'not-generated') {
                matchesCert = row.dataset.certificate === 'not-generated';
            } else if (certFilter === 'sent') {
                matchesCert = row.dataset.certSent === 'sent';
            }

            row.style.display = matchesSearch && matchesStatus && matchesCert ? '' : 'none';
        });
    }

    searchInput.addEventListener('input', filterTable);
    filterStatus.addEventListener('change', filterTable);
    filterCertificate.addEventListener('change', filterTable);

    // Select all checkbox
    const selectAll = document.getElementById('selectAll');
    const rowCheckboxes = document.querySelectorAll('.row-checkbox');

    selectAll?.addEventListener('change', function() {
        rowCheckboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
    });
});

function exportToCSV() {
    const table = document.getElementById('enrollmentsTable');
    let csv = [];

    // Headers
    const headers = [];
    table.querySelectorAll('thead th').forEach(th => {
        if (!th.querySelector('input[type="checkbox"]')) {
            headers.push(th.textContent.trim());
        }
    });
    csv.push(headers.join(','));

    // Rows
    const visibleRows = Array.from(table.querySelectorAll('tbody tr')).filter(row => row.style.display !== 'none');
    visibleRows.forEach(row => {
        const rowData = [];
        row.querySelectorAll('td').forEach((td, index) => {
            if (index > 0 && index < row.querySelectorAll('td').length -
                1) { // Skip checkbox and action column
                let text = td.textContent.trim().replace(/\s+/g, ' ').replace(/,/g, ';');
                rowData.push('"' + text + '"');
            }
        });
        csv.push(rowData.join(','));
    });

    // Download
    const csvContent = csv.join('\n');
    const blob = new Blob([csvContent], {
        type: 'text/csv;charset=utf-8;'
    });
    const link = document.createElement('a');
    link.href = URL.createObjectURL(blob);
    link.download = 'seminar_enrollments_{{ $seminar->slug }}_' + new Date().getTime() + '.csv';
    link.click();
}
</script>
@endsection