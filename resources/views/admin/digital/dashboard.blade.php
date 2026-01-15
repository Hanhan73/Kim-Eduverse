@extends('layouts.admin-digital')

@section('title', 'Dashboard - Admin Digital')
@section('page-title', 'Dashboard')

@section('content')
<!-- Stats Grid -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon primary">
            <i class="fas fa-box"></i>
        </div>
        <div class="stat-content">
            <h4>{{ $stats['total_products'] }}</h4>
            <p>Total Produk</p>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon info">
            <i class="fas fa-clipboard-list"></i>
        </div>
        <div class="stat-content">
            <h4>{{ $stats['total_questionnaires'] }}</h4>
            <p>Total CEKMA</p>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon success">
            <i class="fas fa-shopping-cart"></i>
        </div>
        <div class="stat-content">
            <h4>{{ $stats['paid_orders'] }}</h4>
            <p>Orders Terbayar</p>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon warning">
            <i class="fas fa-chart-bar"></i>
        </div>
        <div class="stat-content">
            <h4>{{ $stats['completed_responses'] }}</h4>
            <p>Respons Selesai</p>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon danger">
            <i class="fas fa-clock"></i>
        </div>
        <div class="stat-content">
            <h4>{{ $stats['pending_responses'] }}</h4>
            <p>Respons Pending</p>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon success">
            <i class="fas fa-money-bill-wave"></i>
        </div>
        <div class="stat-content">
            <h4>Rp {{ number_format($stats['total_revenue'], 0, ',', '.') }}</h4>
            <p>Total Revenue</p>
        </div>
    </div>
</div>

<div style="display: grid; grid-template-columns: 2fr 1fr; gap: 25px;">
    <!-- Recent Orders -->
    <div class="card">
        <div class="card-header">
            <h3><i class="fas fa-shopping-cart"></i> Order Terbaru</h3>
            <a href="{{ route('admin.digital.orders.index') }}" class="btn btn-sm btn-secondary">
                Lihat Semua
            </a>
        </div>
        <div class="card-body" style="padding: 0;">
            @if($recentOrders->count() > 0)
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>Order #</th>
                            <th>Customer</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Tanggal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentOrders as $order)
                        <tr>
                            <td>
                                <a href="{{ route('admin.digital.orders.show', $order->id) }}" style="color: var(--primary); text-decoration: none; font-weight: 600;">
                                    {{ $order->order_number }}
                                </a>
                            </td>
                            <td>{{ $order->customer_name }}</td>
                            <td>Rp {{ number_format($order->total, 0, ',', '.') }}</td>
                            <td>
                                @if($order->payment_status === 'paid')
                                    <span class="badge badge-success">Paid</span>
                                @elseif($order->payment_status === 'pending')
                                    <span class="badge badge-warning">Pending</span>
                                @else
                                    <span class="badge badge-danger">{{ ucfirst($order->payment_status) }}</span>
                                @endif
                            </td>
                            <td>{{ $order->created_at->format('d M Y') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="empty-state">
                <i class="fas fa-shopping-cart"></i>
                <h3>Belum Ada Order</h3>
            </div>
            @endif
        </div>
    </div>

    <!-- Questionnaire Stats -->
    <div class="card">
        <div class="card-header">
            <h3><i class="fas fa-chart-pie"></i> Respons per CEKMA</h3>
        </div>
        <div class="card-body">
            @if($responsesByQuestionnaire->count() > 0)
                @foreach($responsesByQuestionnaire as $questionnaire)
                <div style="margin-bottom: 20px; padding-bottom: 15px; border-bottom: 1px solid #edf2f7;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                        <span style="font-weight: 600; color: var(--dark);">{{ Str::limit($questionnaire->name, 25) }}</span>
                        <span style="color: var(--gray); font-size: 0.85rem;">
                            {{ $questionnaire->completed_count }} selesai
                        </span>
                    </div>
                    <div style="background: #edf2f7; border-radius: 10px; height: 8px; overflow: hidden;">
                        @php
                            $total = $questionnaire->completed_count + $questionnaire->pending_count;
                            $percentage = $total > 0 ? ($questionnaire->completed_count / $total) * 100 : 0;
                        @endphp
                        <div style="background: linear-gradient(135deg, var(--primary), var(--secondary)); height: 100%; width: {{ $percentage }}%; border-radius: 10px;"></div>
                    </div>
                    @if($questionnaire->pending_count > 0)
                    <small style="color: var(--warning);">{{ $questionnaire->pending_count }} pending</small>
                    @endif
                </div>
                @endforeach
            @else
            <div class="empty-state" style="padding: 30px;">
                <i class="fas fa-clipboard-list" style="font-size: 2rem;"></i>
                <p>Belum ada cekma</p>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Recent Responses -->
<div class="card" style="margin-top: 25px;">
    <div class="card-header">
        <h3><i class="fas fa-chart-bar"></i> Respons Terbaru</h3>
    </div>
    <div class="card-body" style="padding: 0;">
        @if($recentResponses->count() > 0)
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>Responden</th>
                        <th>CEKMA</th>
                        <th>Order</th>
                        <th>Skor</th>
                        <th>Selesai</th>
                        <th>Email</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recentResponses as $response)
                    <tr>
                        <td>
                            <div>
                                <strong>{{ $response->respondent_name }}</strong>
                                <br>
                                <small style="color: var(--gray);">{{ $response->respondent_email }}</small>
                            </div>
                        </td>
                        <td>{{ Str::limit($response->questionnaire->name ?? '-', 20) }}</td>
                        <td>{{ $response->order->order_number ?? '-' }}</td>
                        <td>
                            @if($response->scores)
                                @php $scores = is_array($response->scores) ? $response->scores : json_decode($response->scores, true); @endphp
                                @foreach($scores as $key => $score)
                                    <span class="badge badge-info" style="margin-right: 3px;">{{ $score }}</span>
                                @endforeach
                            @else
                                -
                            @endif
                        </td>
                        <td>{{ $response->completed_at ? $response->completed_at->format('d M Y H:i') : '-' }}</td>
                        <td>
                            @if($response->result_sent)
                                <span class="badge badge-success">Terkirim</span>
                            @else
                                <span class="badge badge-warning">Belum</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="empty-state">
            <i class="fas fa-chart-bar"></i>
            <h3>Belum Ada Respons</h3>
        </div>
        @endif
    </div>
</div>
@endsection
