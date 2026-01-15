@extends('layouts.admin')

@section('title', 'Edit Course')

@section('page-title', '✏️ Edit Course')

@section('content')
<div class="content-card">
    <div class="card-header">
        <h3>Edit Course Information</h3>
    </div>
    <div class="card-body">
        <form action="{{ route('edutech.admin.courses.update', $course->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div style="display: grid; gap: 20px;">
                <div>
                    <label style="display: block; color: var(--dark); font-weight: 600; margin-bottom: 8px;">
                        Course Title <span style="color: var(--danger);">*</span>
                    </label>
                    <input type="text" name="title" value="{{ old('title', $course->title) }}" required
                        style="width: 100%; padding: 12px 15px; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 1rem;">
                    @error('title')
                        <span style="color: var(--danger); font-size: 0.85rem;">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label style="display: block; color: var(--dark); font-weight: 600; margin-bottom: 8px;">
                        Description <span style="color: var(--danger);">*</span>
                    </label>
                    <textarea name="description" rows="5" required
                        style="width: 100%; padding: 12px 15px; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 1rem; resize: vertical;">{{ old('description', $course->description) }}</textarea>
                    @error('description')
                        <span style="color: var(--danger); font-size: 0.85rem;">{{ $message }}</span>
                    @enderror
                </div>

                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px;">
                    <div>
                        <label style="display: block; color: var(--dark); font-weight: 600; margin-bottom: 8px;">
                            Category <span style="color: var(--danger);">*</span>
                        </label>
                        <select name="category" required
                            style="width: 100%; padding: 12px 15px; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 1rem;">
                            <option value="">Select Category</option>
                            <option value="Education" {{ $course->category === 'Education' ? 'selected' : '' }}>Education</option>
                            <option value="Language" {{ $course->category === 'Language' ? 'selected' : '' }}>Language</option>
                            <option value="Teknologi Informasi" {{ $course->category === 'Teknologi Informasi' ? 'selected' : '' }}>Teknologi Informasi</option>
                            <option value="Desain" {{ $course->category === 'Desain' ? 'selected' : '' }}>Desain</option>
                            <option value="Manajemen dan Teknik Industri" {{ $course->category === 'Manajemen dan Teknik Industri' ? 'selected' : '' }}>Manajemen dan Teknik Industri</option>
                        </select>
                        @error('category')
                            <span style="color: var(--danger); font-size: 0.85rem;">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label style="display: block; color: var(--dark); font-weight: 600; margin-bottom: 8px;">
                            Instructor <span style="color: var(--danger);">*</span>
                        </label>
                        <select name="instructor_id" required
                            style="width: 100%; padding: 12px 15px; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 1rem;">
                            <option value="">Select Instructor</option>
                            @foreach($instructors as $instructor)
                            <option value="{{ $instructor->id }}" {{ $course->instructor_id == $instructor->id ? 'selected' : '' }}>
                                {{ $instructor->name }}
                            </option>
                            @endforeach
                        </select>
                        @error('instructor_id')
                            <span style="color: var(--danger); font-size: 0.85rem;">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label style="display: block; color: var(--dark); font-weight: 600; margin-bottom: 8px;">
                            Price (Rp)
                        </label>
                        <input type="number" name="price" value="{{ old('price', $course->price) }}" min="0"
                            style="width: 100%; padding: 12px 15px; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 1rem;">
                        @error('price')
                            <span style="color: var(--danger); font-size: 0.85rem;">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div>
                    <label style="display: block; color: var(--dark); font-weight: 600; margin-bottom: 8px;">
                        Status
                    </label>
                    <div style="display: flex; align-items: center; gap: 10px; padding: 12px 0;">
                        <input type="checkbox" name="is_published" value="1" {{ $course->is_published ? 'checked' : '' }}
                            style="width: 20px; height: 20px; cursor: pointer;">
                        <span style="color: var(--dark); font-weight: 500;">Published</span>
                    </div>
                </div>
            </div>

            <div style="display: flex; gap: 10px; padding-top: 30px; border-top: 1px solid #e2e8f0; margin-top: 30px;">
                <button type="submit" style="background: var(--primary); color: white; padding: 12px 30px; border-radius: 8px; border: none; font-weight: 600; cursor: pointer; font-size: 1rem;">
                    <i class="fas fa-save"></i> Save Changes
                </button>
                <a href="{{ route('edutech.admin.courses.show', $course->id) }}" style="background: var(--gray); color: white; padding: 12px 30px; border-radius: 8px; text-decoration: none; font-weight: 600; display: inline-flex; align-items: center; gap: 8px;">
                    <i class="fas fa-times"></i> Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection