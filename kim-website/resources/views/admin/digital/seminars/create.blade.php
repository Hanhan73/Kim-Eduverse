{{-- File: resources/views/admin/digital/seminars/create.blade.php --}}
@extends('layouts.admin-digital')

@section('title', 'Tambah Seminar')
@section('page-title', 'Tambah Seminar')

@section('content')
<div class="page-header">
    <div>
        <a href="{{ route('admin.digital.seminars.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>
</div>

@include('admin.digital.seminars._form', [
'seminar' => null,
'action' => route('admin.digital.seminars.store'),
'method' => 'POST',
'submitText' => 'Simpan Seminar'
])
@endsection