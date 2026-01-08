{{-- File: resources/views/admin/digital/seminars/edit.blade.php --}}
@extends('layouts.admin-digital')

@section('title', 'Edit Seminar')
@section('page-title', 'Edit Seminar')

@section('content')
<div class="page-header">
    <div>
        <a href="{{ route('admin.digital.seminars.show', $seminar) }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>
</div>

@include('admin.digital.seminars._form', [
'seminar' => $seminar,
'action' => route('admin.digital.seminars.update', $seminar),
'method' => 'PUT',
'submitText' => 'Update Seminar'
])
@endsection