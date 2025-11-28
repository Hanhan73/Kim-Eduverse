@extends('layouts.admin-digital')

@section('title', 'Hasil Respons - Admin Digital')
@section('page-title', 'Hasil Respons Angket')

@section('content')
<!-- Stats -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon primary">
            <i class="fas fa-chart-bar"></i>
        </div>
        <div class="stat-content">
            <h4>{{ $stats['total'] }}</h4>
            <p>Total Respons</p>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon success">
            <i class="fas fa-check-circle"></i>
        </div>
        <div class="stat-content">
            <h4>{{ $stats['completed'] }}</h4>
            <p>Selesai</p>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon warning">
            <i class="fas fa-clock"></i>
        </div>
        <div class="stat-content">
            <h4>{{ $stats['pending'] }}</h4>
            <p>Pending</p>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon info">
            <i class="fas fa-envelope"></i>
        </div>
        <div class="stat-content">
            <h4>{{ $stats['sent'] }}</h4>
            <p>Email Terkirim</p>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h3><i class="fas fa-chart-bar"></i> Daftar Respons</h3>
        <a href="{{ route('admin.digital.responses.index', array_merge(request()->only(['questionnaire_id', 'status', 'date_from', 'date_to']), ['export' => 1])) }}" class="btn btn-success">
            <i class="fas fa-download"></i> Export CSV
        </a>
    </div>

    <!-- Filters -->
    <div class="filter-section" style="margin: 0 20px;">
        <form method="GET" action="{{ route('admin.digital.responses.index') }}">
            <div class="filter-row">
                <div class="filter-group">
                    <label>Cari Responden</label>
                    <input type="text" name="search" class="form-control" placeholder="Nama atau email..." value="{{ request('search') }}">
                </div>
                <div class="filter-group">
                    <label>Angket</label>
                    <select name="questionnaire_id" class="form-control">
                        <option value="">Semua Angket</option>
                        @foreach($questionnaires as $questionnaire)
                            <option value="{{ $questionnaire->id }}" {{ request('questionnaire_id') == $questionnaire->id ? 'selected' : '' }}>
                                {{ $questionnaire->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="filter-group" style="flex: 0.5;">
                    <label>Status</label>
                    <select name="status" class="form-control">
                        <option value="">Semua</option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Selesai</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    </select>
                </div>
                <div class="filter-group" style="flex: 0.5;">
                    <label>Dari Tanggal</label>
                    <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
                </div>
                <div class="filter-group" style="flex: 0.5;">
                    <label>Sampai Tanggal</label>
                    <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
                </div>
                <div class="filter-group" style="flex: 0; white-space: nowrap;">
                    <label>&nbsp;</label>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search"></i>
                    </button>
                    <a href="{{ route('admin.digital.responses.index') }}" class="btn btn-secondary">
                        <i class="fas fa-refresh"></i>
                    </a>
                </div>
            </div>
        </form>
    </div>

    <div class="card-body" style="padding: 0;">
        @if($responses->count() > 0)
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>Responden</th>
                        <th>Angket</th>
                        <th>Order</th>
                        <th>Skor</th>
                        <th>Status</th>
                        <th>Email</th>
                        <th>Tanggal</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($responses as $response)
                    <tr>
                        <td>
                            <div>
                                <strong>{{ $response->respondent_name }}</strong>
                                <br>
                                <small style="color: var(--gray);">{{ $response->respondent_email }}</small>
                            </div>
                        </td>
                        <td>
                            <a href="{{ route('admin.digital.questionnaires.show', $response->questionnaire_id) }}" style="color: var(--primary); text-decoration: none;">
                                {{ Str::limit($response->questionnaire->name ?? '-', 20) }}
                            </a>
                        </td>
                        <td>
                            @if($response->order)
                                <a href="{{ route('admin.digital.orders.show', $response->order->id) }}" style="color: var(--primary); text-decoration: none;">
                                    {{ $response->order->order_number }}
                                </a>
                            @else
                                -
                            @endif
                        </td>
                        <td>
                            @if($response->scores && $response->is_completed)
                                @php $scores = is_array($response->scores) ? $response->scores : json_decode($response->scores, true); @endphp
                                <div style="display: flex; gap: 3px; flex-wrap: wrap;">
                                    @foreach($scores as $key => $score)
                                        <span class="badge badge-info" title="{{ $key }}">{{ $score }}</span>
                                    @endforeach
                                </div>
                            @else
                                -
                            @endif
                        </td>
                        <td>
                            @if($response->is_completed)
                                <span class="badge badge-success">Selesai</span>
                            @else
                                <span class="badge badge-warning">Pending</span>
                            @endif
                        </td>
                        <td>
                            @if($response->result_sent)
                                <span class="badge badge-success" title="{{ $response->result_sent_at ? $response->result_sent_at->format('d M Y H:i') : '' }}">
                                    <i class="fas fa-check"></i> Terkirim
                                </span>
                            @else
                                <span class="badge badge-secondary">Belum</span>
                            @endif
                        </td>
                        <td>
                            @if($response->completed_at)
                                {{ $response->completed_at->format('d M Y') }}
                                <br>
                                <small style="color: var(--gray);">{{ $response->completed_at->format('H:i') }}</small>
                            @else
                                {{ $response->created_at->format('d M Y') }}
                            @endif
                        </td>
                        <td>
                            <div style="display: flex; gap: 5px;">
                                <a href="{{ route('admin.digital.responses.show', $response->id) }}" class="btn btn-sm btn-icon btn-secondary" title="Lihat Detail">
                                    <i class="fas fa-eye"></i>
                                </a>
                                @if($response->is_completed)
                                    @if($response->result_pdf_path)
                                    <a href="{{ route('admin.digital.responses.show', $response->id) }}?download=1" class="btn btn-sm btn-icon btn-primary" title="Download PDF">
                                        <i class="fas fa-download"></i>
                                    </a>
                                    @endif
                                    <form action="{{ route('admin.digital.responses.resend', $response->id) }}" method="POST" style="display: inline;">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-icon btn-success" title="Kirim Ulang Email">
                                            <i class="fas fa-envelope"></i>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div style="padding: 20px;">
            {{ $responses->withQueryString()->links() }}
        </div>
        @else
        <div class="empty-state">
            <i class="fas fa-chart-bar"></i>
            <h3>Belum Ada Respons</h3>
            <p>Respons akan muncul setelah pengguna mengisi angket</p>
        </div>
        @endif
    </div>
</div>
@endsection
