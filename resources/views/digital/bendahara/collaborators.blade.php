@extends('layouts.bendahara_digital')

@section('content')
<div class="main-content">
    <div class="top-bar">
        <h1>Collaborator List</h1>
        <div class="user-info">
            <div class="user-avatar">{{ substr(session('digital_admin_name'), 0, 1) }}</div>
            <span>{{ session('digital_admin_name') }}</span>
        </div>
    </div>

    <!-- Collaborator Stats -->
    <div class="content-section">
        <div class="section-header">
            <h2>Daftar Collaborator & Revenue</h2>
        </div>

        @if($collaborators->count() > 0)
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>Collaborator</th>
                        <th>Total Products</th>
                        <th>Total Sales</th>
                        <th>Total Revenue</th>
                        <th>Available</th>
                        <th>Withdrawn</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($collaborators as $data)
                    <tr>
                        <td>
                            <strong>{{ $data['collaborator']->name }}</strong><br>
                            <small style="color: var(--gray);">{{ $data['collaborator']->email }}</small>
                        </td>
                        <td>{{ $data['total_products'] }} product</td>
                        <td>{{ $data['total_sales'] }} sales</td>
                        <td style="font-weight: 700;">
                            Rp {{ number_format($data['total_revenue'], 0, ',', '.') }}
                        </td>
                        <td style="color: var(--success); font-weight: 700;">
                            Rp {{ number_format($data['available'], 0, ',', '.') }}
                        </td>
                        <td style="color: var(--gray);">
                            Rp {{ number_format($data['withdrawn'], 0, ',', '.') }}
                        </td>
                        <td>
                            <a href="{{ route('digital.bendahara.collaborators.show', $data['collaborator']->id) }}"
                                style="color: var(--info); text-decoration: none; font-weight: 600;">
                                <i class="fas fa-eye"></i> Detail
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="empty-state">
            <i class="fas fa-users"></i>
            <h3>Belum ada collaborator</h3>
            <p>Data collaborator akan muncul di sini</p>
        </div>
        @endif
    </div>
</div>
@endsection