{{-- resources/views/edutech/profile/index.blade.php --}}
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Saya - KIM Edutech</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: #f8fafc;
        }

        /* Sidebar Styling */
        .sidebar {
            width: 260px;
            background: linear-gradient(180deg, #2d3748 0%, #1a202c 100%);
            position: fixed;
            left: 0;
            top: 0;
            height: 100vh;
            overflow-y: auto;
            z-index: 1000;
        }

        .sidebar-header {
            padding: 30px 25px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .sidebar-header h2 {
            color: white;
            font-size: 1.5rem;
            display: flex;
            align-items: center;
            gap: 12px;
            margin: 0;
        }

        .sidebar-menu {
            padding: 20px 0;
        }

        .menu-item {
            display: flex;
            align-items: center;
            padding: 15px 25px;
            color: rgba(255, 255, 255, 0.7);
            text-decoration: none;
            transition: all 0.3s ease;
            font-weight: 500;
            border-left: 4px solid transparent;
        }

        .menu-item:hover {
            background: rgba(255, 255, 255, 0.05);
            color: white;
        }

        .menu-item.active {
            background: rgba(255, 255, 255, 0.1);
            border-left-color: white;
            color: white;
        }

        .menu-item i {
            width: 25px;
            margin-right: 12px;
        }

        .menu-divider {
            margin: 20px 25px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }

        /* Main Content with proper offset */
        .main-content-wrapper {
            margin-left: 260px;
            min-height: 100vh;
            background: #f8fafc;
        }

        /* Card Animations */
        .card-smooth {
            transition: all 0.3s ease;
        }

        .card-smooth:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08);
        }

        /* Avatar hover */
        .avatar-hover:hover {
            transform: scale(1.05);
            transition: transform 0.2s ease;
        }

        /* Tab styling */
        .tab-button {
            position: relative;
            transition: all 0.3s ease;
        }

        .tab-button.active {
            color: #2563eb !important;
            border-bottom-color: #2563eb !important;
        }

        /* Gradient buttons */
        .btn-gradient {
            background: linear-gradient(135deg, #6366f1, #3b82f6);
            color: white;
            font-weight: 600;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
        }

        .btn-gradient:hover {
            background: linear-gradient(135deg, #4f46e5, #2563eb);
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(99, 102, 241, 0.4);
        }

        .btn-danger-gradient {
            background: linear-gradient(135deg, #f87171, #ef4444);
            color: white;
            font-weight: 600;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
        }

        .btn-danger-gradient:hover {
            background: linear-gradient(135deg, #dc2626, #b91c1c);
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(239, 68, 68, 0.4);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .main-content-wrapper {
                margin-left: 0;
            }
        }
    </style>
</head>

<body>

    {{-- Sidebar dinamis berdasarkan role pengguna --}}
    @if(session('edutech_user_role') === 'admin')
    {{-- Admin Sidebar --}}
    <aside class="sidebar">
        <div class="sidebar-header">
            <h2>
                <i class="fas fa-shield-alt"></i>
                Admin Panel
            </h2>
        </div>

        <nav class="sidebar-menu">
            <a href="{{ route('edutech.admin.dashboard') }}"
                class="menu-item {{ request()->routeIs('edutech.admin.dashboard') ? 'active' : '' }}">
                <i class="fas fa-home"></i> Dashboard
            </a>
            <a href="{{ route('edutech.profile.index') }}"
                class="menu-item {{ request()->routeIs('edutech.profile.index') ? 'active' : '' }}">
                <i class="fas fa-user"></i> Profile Saya
            </a>
            <a href="{{ route('edutech.admin.users') }}"
                class="menu-item {{ request()->routeIs('edutech.admin.users*') ? 'active' : '' }}">
                <i class="fas fa-users"></i> Users Management
            </a>
            <a href="{{ route('edutech.admin.courses') }}"
                class="menu-item {{ request()->routeIs('edutech.admin.courses*') ? 'active' : '' }}">
                <i class="fas fa-book"></i> Courses Management
            </a>
            <a href="{{ route('edutech.admin.enrollments') }}"
                class="menu-item {{ request()->routeIs('edutech.admin.enrollments*') ? 'active' : '' }}">
                <i class="fas fa-graduation-cap"></i> Enrollments
            </a>
            <a href="{{ route('edutech.admin.certificates') }}"
                class="menu-item {{ request()->routeIs('edutech.admin.certificates*') ? 'active' : '' }}">
                <i class="fas fa-certificate"></i> Certificates
            </a>
            <div class="menu-divider"></div>
            <a href="{{ route('edutech.admin.settings') }}"
                class="menu-item {{ request()->routeIs('edutech.admin.settings') ? 'active' : '' }}">
                <i class="fas fa-cog"></i> Settings
            </a>
            <a href="{{ route('edutech.landing') }}" class="menu-item">
                <i class="fas fa-globe"></i> View Website
            </a>
        </nav>
    </aside>

    @elseif(session('edutech_user_role') === 'instructor')
    {{-- Instructor Sidebar --}}
    <aside class="sidebar">
        <div class="sidebar-header">
            <h2>
                <i class="fas fa-chalkboard-teacher"></i>
                Instructor Panel
            </h2>
        </div>

        <nav class="sidebar-menu">
            <a href="{{ route('edutech.instructor.dashboard') }}"
                class="menu-item {{ request()->routeIs('edutech.instructor.dashboard') ? 'active' : '' }}">
                <i class="fas fa-home"></i> Dashboard
            </a>
            <a href="{{ route('edutech.profile.index') }}"
                class="menu-item {{ request()->routeIs('edutech.profile.index') ? 'active' : '' }}">
                <i class="fas fa-user"></i> Profile Saya
            </a>
            <a href="{{ route('edutech.instructor.courses') }}"
                class="menu-item {{ request()->routeIs('edutech.instructor.courses*') ? 'active' : '' }}">
                <i class="fas fa-book"></i> My Courses
            </a>
            <a href="{{ route('edutech.instructor.quiz.index') }}"
                class="menu-item {{ request()->routeIs('edutech.instructor.quiz*') ? 'active' : '' }}">
                <i class="fas fa-clipboard-list"></i> Quiz Management
            </a>
            <a href="{{ route('edutech.instructor.live-meetings.index') }}"
                class="menu-item {{ request()->routeIs('edutech.instructor.live-meetings*') ? 'active' : '' }}">
                <i class="fas fa-video"></i> Live Meeting
            </a>
            <a href="{{ route('edutech.instructor.students') }}"
                class="menu-item {{ request()->routeIs('edutech.instructor.students') ? 'active' : '' }}">
                <i class="fas fa-users"></i> Students
            </a>
            <div class="menu-divider"></div>
            <a href="{{ route('edutech.landing') }}" class="menu-item">
                <i class="fas fa-globe"></i> View Website
            </a>
        </nav>
    </aside>

    @else
    {{-- Student Sidebar --}}
    <aside class="sidebar">
        <div class="sidebar-header">
            <h2>
                <i class="fas fa-user-graduate"></i>
                Student Panel
            </h2>
        </div>

        <nav class="sidebar-menu">
            <a href="{{ route('edutech.student.dashboard') }}"
                class="menu-item {{ request()->routeIs('edutech.student.dashboard') ? 'active' : '' }}">
                <i class="fas fa-home"></i> Dashboard
            </a>
            <a href="{{ route('edutech.profile.index') }}"
                class="menu-item {{ request()->routeIs('edutech.profile.index') ? 'active' : '' }}">
                <i class="fas fa-user"></i> Profile Saya
            </a>
            <a href="{{ route('edutech.student.my-courses') }}"
                class="menu-item {{ request()->routeIs('edutech.student.my-courses') ? 'active' : '' }}">
                <i class="fas fa-book"></i> My Courses
            </a>
            <a href="{{ route('edutech.student.certificates') }}"
                class="menu-item {{ request()->routeIs('edutech.student.certificates') ? 'active' : '' }}">
                <i class="fas fa-certificate"></i> Certificates
            </a>
            <div class="menu-divider"></div>
            <a href="{{ route('edutech.courses.index') }}" class="menu-item">
                <i class="fas fa-search"></i> Browse Courses
            </a>
            <a href="{{ route('edutech.landing') }}" class="menu-item">
                <i class="fas fa-globe"></i> Home
            </a>
        </nav>
    </aside>
    @endif

    <!-- Main Content with proper offset -->
    <div class="main-content-wrapper">
        <div class="max-w-6xl mx-auto px-6 py-8">

            {{-- Page Title --}}
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-900">
                    <i class="fas fa-user-circle text-blue-600 mr-2"></i>
                    My Profile
                </h1>
                <p class="text-gray-600 mt-1">Manage your account settings and preferences</p>
            </div>

            {{-- Flash messages --}}
            @if(session('success'))
            <div class="mb-6 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg shadow-sm">
                <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
            </div>
            @endif

            @if(session('error'))
            <div class="mb-6 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg shadow-sm">
                <i class="fas fa-exclamation-circle mr-2"></i>{{ session('error') }}
            </div>
            @endif

            @if($errors->any())
            <div class="mb-6 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg">
                <p class="font-semibold mb-2"><i class="fas fa-exclamation-triangle mr-2"></i>Terdapat kesalahan:</p>
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            {{-- Profile Header Card --}}
            <div class="bg-gradient-to-r from-blue-600 to-indigo-600 rounded-2xl p-8 shadow-lg mb-8 text-white">
                <div class="flex items-center gap-6">
                    @if($user->avatar)
                    <img src="{{ asset('storage/' . $user->avatar) }}" alt="Avatar"
                        class="w-28 h-28 rounded-full border-4 border-white shadow-xl avatar-hover object-cover">
                    @else
                    <div
                        class="w-28 h-28 rounded-full bg-white/20 backdrop-blur-sm flex items-center justify-center text-white text-4xl font-bold shadow-xl avatar-hover border-4 border-white">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                    @endif

                    <div class="flex-1">
                        <h2 class="text-3xl font-bold mb-2">{{ $user->name }}</h2>
                        <p class="text-blue-100 mb-3">{{ $user->email }}</p>
                        <div class="flex items-center gap-3 flex-wrap">
                            <span
                                class="inline-flex items-center px-4 py-1.5 rounded-full text-sm font-semibold bg-white/20 backdrop-blur-sm border border-white/30">
                                <i
                                    class="fas fa-{{ $user->role === 'admin' ? 'crown' : ($user->role === 'instructor' ? 'chalkboard-teacher' : 'user-graduate') }} mr-2"></i>
                                {{ ucfirst($user->role) }}
                            </span>

                            @if($user->is_active)
                            <span
                                class="inline-flex items-center px-4 py-1.5 rounded-full text-sm font-semibold bg-green-500/90 backdrop-blur-sm">
                                <i class="fas fa-check-circle mr-2"></i> Active Account
                            </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- Tabs Card --}}
            <div class="bg-white rounded-2xl shadow-md overflow-hidden border border-gray-100">
                <nav class="flex border-b border-gray-200 bg-gray-50">
                    <button onclick="switchTab('profile')" id="tab-profile"
                        class="tab-button flex-1 py-4 px-6 font-semibold text-blue-600 border-b-2 border-blue-600 transition-all">
                        <i class="fas fa-user mr-2"></i> Profile Information
                    </button>
                    <button onclick="switchTab('password')" id="tab-password"
                        class="tab-button flex-1 py-4 px-6 font-semibold text-gray-500 border-b-2 border-transparent hover:text-gray-700 hover:bg-gray-100 transition-all">
                        <i class="fas fa-lock mr-2"></i> Change Password
                    </button>
                </nav>

                {{-- Profile Tab --}}
                <div id="content-profile" class="tab-content p-8">
                    <form action="{{ route('edutech.profile.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="space-y-6">
                            {{-- Avatar Section --}}
                            <div class="bg-gray-50 rounded-xl p-6">
                                <label class="block text-sm font-semibold text-gray-700 mb-4">
                                    <i class="fas fa-camera mr-2 text-blue-600"></i> Profile Picture
                                </label>
                                <div class="flex items-center gap-6">
                                    <div id="avatar-preview">
                                        @if($user->avatar)
                                        <img src="{{ asset('storage/' . $user->avatar) }}"
                                            class="w-24 h-24 rounded-full object-cover border-4 border-white shadow-lg">
                                        @else
                                        <div
                                            class="w-24 h-24 rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white text-2xl font-bold shadow-lg">
                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                        </div>
                                        @endif
                                    </div>

                                    <div class="flex-1">
                                        <input type="file" name="avatar" id="avatar-input" accept="image/*"
                                            class="hidden">
                                        <div class="flex gap-3">
                                            <button type="button"
                                                onclick="document.getElementById('avatar-input').click()"
                                                class="btn-gradient px-5 py-2.5 rounded-lg inline-flex items-center">
                                                <i class="fas fa-cloud-upload-alt mr-2"></i> Upload New Photo
                                            </button>

                                            @if($user->avatar)
                                            <button type="button" onclick="deleteAvatar()"
                                                class="btn-danger-gradient px-5 py-2.5 rounded-lg inline-flex items-center">
                                                <i class="fas fa-trash mr-2"></i> Remove
                                            </button>
                                            @endif
                                        </div>
                                        <p class="text-xs text-gray-500 mt-3">
                                            <i class="fas fa-info-circle mr-1"></i>
                                            Accepted: JPG, PNG, GIF (Max: 2MB)
                                        </p>
                                    </div>
                                </div>
                            </div>

                            {{-- Personal Information --}}
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                                    <i class="fas fa-id-card text-blue-600 mr-2"></i>
                                    Personal Information
                                </h3>

                                <div class="grid md:grid-cols-2 gap-6">
                                    {{-- Name --}}
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">
                                            <i class="fas fa-user mr-1 text-gray-400"></i> Full Name *
                                        </label>
                                        <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                                            class="w-full rounded-lg border border-gray-300 px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                                    </div>

                                    {{-- Email --}}
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">
                                            <i class="fas fa-envelope mr-1 text-gray-400"></i> Email Address *
                                        </label>
                                        <input type="email" name="email" value="{{ old('email', $user->email) }}"
                                            required
                                            class="w-full rounded-lg border border-gray-300 px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                                    </div>

                                    {{-- Phone --}}
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">
                                            <i class="fas fa-phone mr-1 text-gray-400"></i> Phone Number
                                        </label>
                                        <input type="text" name="phone" value="{{ old('phone', $user->phone) }}"
                                            class="w-full rounded-lg border border-gray-300 px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                                            placeholder="08xxxxxxxxxx">
                                    </div>

                                    {{-- Bio (full width) --}}
                                    <div class="md:col-span-2">
                                        <label class="block text-sm font-medium text-gray-700 mb-2">
                                            <i class="fas fa-align-left mr-1 text-gray-400"></i> Bio / About Me
                                        </label>
                                        <textarea name="bio" rows="4"
                                            class="w-full rounded-lg border border-gray-300 px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                                            placeholder="Tell us a little bit about yourself...">{{ old('bio', $user->bio) }}</textarea>
                                        <p class="text-xs text-gray-500 mt-1">Maximum 1000 characters</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-end mt-8 pt-6 border-t border-gray-200">
                            <button type="submit"
                                class="btn-gradient px-8 py-3 rounded-lg inline-flex items-center text-base">
                                <i class="fas fa-save mr-2"></i> Save Changes
                            </button>
                        </div>
                    </form>
                </div>

                {{-- Password Tab --}}
                <div id="content-password" class="tab-content hidden p-8">
                    <form action="{{ route('edutech.profile.password') }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div
                            class="bg-blue-50 border-l-4 border-blue-500 p-5 rounded-lg mb-6 flex items-start gap-3">
                            <i class="fas fa-shield-alt text-blue-600 text-xl mt-0.5"></i>
                            <div>
                                <h4 class="font-semibold text-blue-900 mb-1">Password Security</h4>
                                <p class="text-sm text-blue-800">
                                    Use a strong password with a combination of uppercase letters, lowercase letters,
                                    numbers, and symbols for better security.
                                </p>
                            </div>
                        </div>

                        <div class="space-y-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-key mr-1 text-gray-400"></i> Current Password *
                                </label>
                                <input type="password" name="current_password" required
                                    class="w-full rounded-lg border border-gray-300 px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-lock mr-1 text-gray-400"></i> New Password *
                                </label>
                                <input type="password" name="new_password" required
                                    class="w-full rounded-lg border border-gray-300 px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                                <p class="text-xs text-gray-500 mt-1">Minimum 8 characters required</p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-check-double mr-1 text-gray-400"></i> Confirm New Password *
                                </label>
                                <input type="password" name="new_password_confirmation" required
                                    class="w-full rounded-lg border border-gray-300 px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                            </div>
                        </div>

                        <div class="flex justify-end mt-8 pt-6 border-t border-gray-200">
                            <button type="submit"
                                class="btn-gradient px-8 py-3 rounded-lg inline-flex items-center text-base">
                                <i class="fas fa-shield-alt mr-2"></i> Update Password
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Tab switching
        function switchTab(tab) {
            // Hide all contents
            document.querySelectorAll('.tab-content').forEach(el => el.classList.add('hidden'));

            // Remove active from all buttons
            document.querySelectorAll('.tab-button').forEach(btn => {
                btn.classList.remove('text-blue-600', 'border-blue-600');
                btn.classList.add('text-gray-500', 'border-transparent');
            });

            // Show selected content
            document.getElementById('content-' + tab).classList.remove('hidden');

            // Activate selected button
            const selectedBtn = document.getElementById('tab-' + tab);
            selectedBtn.classList.remove('text-gray-500', 'border-transparent');
            selectedBtn.classList.add('text-blue-600', 'border-blue-600');
        }

        // Avatar preview
        document.getElementById('avatar-input')?.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = ev => {
                    document.getElementById('avatar-preview').innerHTML =
                        `<img src="${ev.target.result}" class="w-24 h-24 rounded-full object-cover border-4 border-white shadow-lg">`;
                };
                reader.readAsDataURL(file);
            }
        });

        // Delete avatar
        function deleteAvatar() {
            if (confirm('Are you sure you want to remove your profile picture?')) {
                fetch('{{ route("edutech.profile.avatar.delete") }}', {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        }
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) location.reload();
                    })
                    .catch(() => alert('An error occurred while deleting the photo.'));
            }
        }
    </script>
</body>

</html>