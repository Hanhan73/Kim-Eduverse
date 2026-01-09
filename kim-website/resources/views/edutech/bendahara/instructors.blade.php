@extends('layouts.bendahara')

@section('content')
<div class="main-content">
    <div class="top-bar">
        <h1>Instructor List</h1>
        <div class="user-info">
            <div class="user-avatar">{{ substr(session('edutech_user_name'), 0, 1) }}</div>
            <span>{{ session('edutech_user_name') }}</span>
        </div>
    </div>

    <!-- Instructor Stats -->
    <div class="content-section">
        <div class="section-header">
            <h2>Daftar Instructor & Revenue</h2>
        </div>

        @if($instructors->count() > 0)
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>Instructor</th>
                        <th>Total Courses</th>
                        <th>Total Sales</th>
                        <th>Total Revenue</th>
                        <th>Available</th>
                        <th>Withdrawn</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($instructors as $data)
                    <tr>
                        <td>
                            <strong>{{ $data['instructor']->name }}</strong><br>
                            <small style="color: var(--gray);">{{ $data['instructor']->email }}</small>
                        </td>
                        <td>{{ $data['total_courses'] }} course</td>
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
                            <a href="{{ route('edutech.bendahara.instructors.show', $data['instructor']->id) }}"
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
            <h3>Belum ada instructor</h3>
            <p>Data instructor akan muncul di sini</p>
        </div>
        @endif
    </div>
</div>
@endsection