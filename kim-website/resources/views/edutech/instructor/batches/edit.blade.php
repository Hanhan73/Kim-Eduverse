<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Course - Instructor</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* === Gunakan style yang sama seperti di create === */
        * { margin: 0; padding: 0; box-sizing: border-box; }
        :root {
            --primary: #667eea; --secondary: #764ba2; --success: #48bb78;
            --warning: #ed8936; --danger: #f56565; --dark: #2d3748;
            --gray: #718096; --light: #f7fafc;
        }
        body { font-family: 'Inter', sans-serif; background: #f8f9fa; }
        .container { max-width: 900px; margin: 40px auto; padding: 0 20px; }
        .page-header, .form-card {
            background: white; padding: 30px 40px; border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }
        .page-header { margin-bottom: 30px; }
        .page-header h1 { font-size: 2rem; color: var(--dark); margin-bottom: 10px; }
        .page-header p { color: var(--gray); }
        .form-group { margin-bottom: 25px; }
        .form-group label { display: block; font-weight: 600; color: var(--dark); margin-bottom: 8px; }
        .form-group label span { color: var(--danger); }
        .form-control {
            width: 100%; padding: 12px 15px; border: 2px solid #e2e8f0;
            border-radius: 8px; font-size: 1rem; transition: all 0.3s ease;
        }
        .form-control:focus { outline: none; border-color: var(--primary); box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1); }
        textarea.form-control { min-height: 120px; resize: vertical; }
        select.form-control { cursor: pointer; }
        .form-hint { font-size: 0.85rem; color: var(--gray); margin-top: 5px; }
        .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
        .file-upload {
            border: 2px dashed #e2e8f0; border-radius: 8px; padding: 30px;
            text-align: center; transition: all 0.3s ease; cursor: pointer;
        }
        .file-upload:hover { border-color: var(--primary); background: #f7fafc; }
        .file-upload input[type="file"] { display: none; }
        .file-upload-icon { font-size: 3rem; color: var(--primary); margin-bottom: 15px; }
        .file-upload-text { color: var(--gray); margin-bottom: 10px; }
        .file-upload-button {
            display: inline-block; padding: 10px 20px; background: var(--primary);
            color: white; border-radius: 8px; font-weight: 600; cursor: pointer;
        }
        .image-preview { margin-top: 20px; display: block; }
        .image-preview img { max-width: 300px; border-radius: 8px; box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1); }
        .form-actions {
            display: flex; gap: 15px; margin-top: 40px; padding-top: 30px;
            border-top: 2px solid var(--light);
        }
        .btn {
            padding: 14px 30px; border-radius: 8px; font-weight: 600;
            font-size: 1rem; cursor: pointer; transition: all 0.3s ease;
            border: none; text-decoration: none; display: inline-flex;
            align-items: center; gap: 10px;
        }
        .btn-primary { background: linear-gradient(135deg, var(--primary), var(--secondary)); color: white; }
        .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 8px 20px rgba(102, 126, 234, 0.3); }
        .btn-secondary { background: var(--light); color: var(--dark); }
        .btn-secondary:hover { background: #e2e8f0; }
        .alert { padding: 15px 20px; border-radius: 8px; margin-bottom: 20px; }
        .alert-danger { background: #fed7d7; color: #742a2a; border: 1px solid #fc8181; }
        .alert-success { background: #c6f6d5; color: #22543d; border: 1px solid #9ae6b4; }
        @media (max-width: 768px) { .form-row { grid-template-columns: 1fr; } }
    </style>
</head>
<body>
    <div class="container">
        <div class="page-header">
            <h1>✏️ Edit Course</h1>
            <p>Update your course information below.</p>
        </div>

        @if($errors->any())
        <div class="alert alert-danger">
            <strong>Oops!</strong> There were some errors:
            <ul style="margin-top: 10px;">
                @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form action="{{ route('edutech.instructor.courses.update', $course->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="form-card">
                <h3 style="margin-bottom: 25px; color: var(--dark);">Course Information</h3>

                <!-- Title -->
                <div class="form-group">
                    <label>Course Title <span>*</span></label>
                    <input type="text" name="title" class="form-control"
                        value="{{ old('title', $course->title) }}" required>
                </div>

                <!-- Description -->
                <div class="form-group">
                    <label>Description <span>*</span></label>
                    <textarea name="description" class="form-control" required>{{ old('description', $course->description) }}</textarea>
                </div>

                <!-- Category & Level -->
                <div class="form-row">
                    <div class="form-group">
                        <label>Category <span>*</span></label>
                        <select name="category" class="form-control" required>
                            <option value="">Select Category</option>
                            @foreach($categories as $key => $category)
                                <option value="{{ $key }}" {{ old('category', $course->category) == $key ? 'selected' : '' }}>
                                    {{ $category }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Level <span>*</span></label>
                        <select name="level" class="form-control" required>
                            <option value="">Select Level</option>
                            <option value="beginner" {{ old('level', $course->level) == 'beginner' ? 'selected' : '' }}>Beginner</option>
                            <option value="intermediate" {{ old('level', $course->level) == 'intermediate' ? 'selected' : '' }}>Intermediate</option>
                            <option value="advanced" {{ old('level', $course->level) == 'advanced' ? 'selected' : '' }}>Advanced</option>
                        </select>
                    </div>
                </div>

                <!-- Price & Duration -->
                <div class="form-row">
                    <div class="form-group">
                        <label>Price (Rp) <span>*</span></label>
                        <input type="number" name="price" class="form-control"
                            value="{{ old('price', $course->price) }}" min="0" step="1000" required>
                    </div>

                    <div class="form-group">
                        <label>Duration (Hours) <span>*</span></label>
                        <input type="number" name="duration_hours" class="form-control"
                            value="{{ old('duration_hours', $course->duration_hours) }}" min="1" required>
                    </div>
                </div>

                <!-- Thumbnail -->
                <div class="form-group">
                    <label>Course Thumbnail</label>
                    <div class="file-upload" onclick="document.getElementById('thumbnail').click()">
                        <input type="file" id="thumbnail" name="thumbnail" accept="image/*" onchange="previewImage(event)">
                        <div class="file-upload-icon">
                            <i class="fas fa-cloud-upload-alt"></i>
                        </div>
                        <div class="file-upload-text">
                            Click to upload a new thumbnail (optional)
                        </div>
                        <div class="file-upload-button">
                            Choose File
                        </div>
                    </div>

                    <div class="image-preview" id="imagePreview" style="margin-top: 15px;">
                        @if($course->thumbnail)
                            <img id="preview" src="{{ asset('storage/' . $course->thumbnail) }}" alt="Current Thumbnail">
                        @else
                            <img id="preview" src="" alt="Preview" style="display:none;">
                        @endif
                    </div>
                </div>

                <!-- Actions -->
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Update Course
                    </button>
                    <a href="{{ route('edutech.instructor.courses') }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Cancel
                    </a>
                </div>
            </div>
        </form>
    </div>

    <script>
        function previewImage(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('preview').src = e.target.result;
                    document.getElementById('preview').style.display = 'block';
                };
                reader.readAsDataURL(file);
            }
        }
    </script>
</body>
</html>
