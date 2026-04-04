<?php
// app/Http/Controllers/Admin/SeminarTypeController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SeminarType;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SeminarTypeController extends Controller
{
    public function index()
    {
        $types = SeminarType::orderBy('order')->orderBy('name')->get();
        return view('admin.digital.seminar-types.index', compact('types'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'  => 'required|string|max:100|unique:seminar_types,name',
            'order' => 'nullable|integer|min:0',
        ]);

        SeminarType::create([
            'name'      => $request->name,
            'slug'      => Str::slug($request->name),
            'is_active' => true,
            'order'     => $request->order ?? 0,
        ]);

        return back()->with('success', "Tipe '{$request->name}' berhasil ditambahkan.");
    }

    public function update(Request $request, SeminarType $seminarType)
    {
        $request->validate([
            'name'  => 'required|string|max:100|unique:seminar_types,name,' . $seminarType->id,
            'order' => 'nullable|integer|min:0',
        ]);

        $seminarType->update([
            'name'  => $request->name,
            'slug'  => Str::slug($request->name),
            'order' => $request->order ?? $seminarType->order,
        ]);

        return back()->with('success', "Tipe berhasil diperbarui.");
    }

    public function destroy(SeminarType $seminarType)
    {
        if ($seminarType->seminars()->exists()) {
            return back()->with('error', "Tidak bisa dihapus — masih ada seminar bertipe '{$seminarType->name}'.");
        }

        $seminarType->delete();
        return back()->with('success', "Tipe '{$seminarType->name}' berhasil dihapus.");
    }

    public function toggleActive(SeminarType $seminarType)
    {
        $seminarType->update(['is_active' => !$seminarType->is_active]);
        return back()->with('success', 'Status berhasil diubah.');
    }
}