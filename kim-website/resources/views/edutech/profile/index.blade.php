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

        nav a {
            position: relative;
            transition: color 0.2s ease;
        }

        nav a::after {
            content: "";
            position: absolute;
            width: 0%;
            height: 2px;
            bottom: -2px;
            left: 0;
            background-color: #3b82f6;
            transition: width 0.3s ease;
        }

        nav a:hover::after {
            width: 100%;
        }

        /* Tab animation */
        .tab-button {
            position: relative;
            transition: all 0.25s ease;
        }

        .tab-button.active {
            color: #2563eb !important;
            background: linear-gradient(to right, #eff6ff, #e0e7ff);
        }

        /* Avatar hover */
        .avatar-hover:hover {
            transform: scale(1.05);
            transition: transform 0.2s ease;
        }

        /* Card hover */
        .card-smooth {
            transition: all 0.3s ease;
        }

        .card-smooth:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.07);
        }

        /* Buttons */
        .btn-gradient {
            background: linear-gradient(135deg, #6366f1, #3b82f6);
            color: white;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-gradient:hover {
            background: linear-gradient(135deg, #4f46e5, #2563eb);
            transform: translateY(-1px);
            box-shadow: 0 4px 10px rgba(99, 102, 241, 0.3);
        }

        .btn-danger-gradient {
            background: linear-gradient(135deg, #f87171, #ef4444);
            color: white;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-danger-gradient:hover {
            background: linear-gradient(135deg, #dc2626, #b91c1c);
            transform: translateY(-1px);
            box-shadow: 0 4px 10px rgba(239, 68, 68, 0.3);
        }

        /* Sidebar Base */
        .sidebar {
            width: 260px;
            padding: 0;
            position: fixed;
            height: 100vh;
            overflow-y: auto;
            color: white;
            box-shadow: 4px 0 20px rgba(0, 0, 0, 0.1);
            z-index: 1000;
        }

        .sidebar-header {
            padding: 30px 25px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .sidebar-header h2 {
            font-size: 1.5rem;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .sidebar-menu {
            padding: 20px 0;
        }

        .menu-item {
            display: flex;
            align-items: center;
            padding: 14px 25px;
            color: rgba(255, 255, 255, 0.85);
            text-decoration: none;
            transition: all 0.3s ease;
            border-left: 4px solid transparent;
            font-weight: 500;
        }

        .menu-item:hover {
            background: rgba(255, 255, 255, 0.1);
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

        /* Warna sidebar berdasarkan role */
        @if(session('edutech_user_role')==='admin') .sidebar {
            background: linear-gradient(180deg, #7f1d1d 0%, #991b1b 100%);
        }

        @elseif(session('edutech_user_role')==='instructor') .sidebar {
            background: linear-gradient(180deg, #5b21b6 0%, #6d28d9 100%);
        }

        @else .sidebar {
            background: linear-gradient(180deg, #1e3a8a 0%, #2563eb 100%);
        }

        @endif
    </style>

</head>

<body class="min-h-screen flex flex-col">

    {{-- Sidebar dinamis berdasarkan role pengguna --}}
    @if(session('edutech_user_role') === 'admin')
    {{-- Admin Sidebar --}}
    <aside class="sidebar fixed left-0 top-0 h-full w-64 bg-white shadow-lg border-r border-gray-200 p-5">
        <div class="sidebar-header mb-6">
            <h2 class="text-xl font-bold text-white-800 flex items-center gap-2">
                <i class="fas fa-shield-alt text-red-600"></i>
                Admin Panel
            </h2>
        </div>

        <nav class="sidebar-menu flex flex-col space-y-2">
            <a href="{{ route('edutech.admin.dashboard') }}"
                class="menu-item {{ request()->routeIs('edutech.admin.dashboard') ? 'text-blue-600 font-semibold' : 'text-white-700 hover:text-blue-600' }}">
                <i class="fas fa-home mr-2"></i> Dashboard
            </a>
            <a href="{{ route('edutech.profile.index') }}"
                class="menu-item {{ request()->routeIs('edutech.profile.index') ? 'text-blue-600 font-semibold' : 'text-white-700 hover:text-blue-600' }}">
                <i class="fas fa-user mr-2"></i> Profile Saya
            </a>
            <a href="{{ route('edutech.admin.users') }}"
                class="menu-item {{ request()->routeIs('edutech.admin.users*') ? 'text-blue-600 font-semibold' : 'text-white-700 hover:text-blue-600' }}">
                <i class="fas fa-users mr-2"></i> Users Management
            </a>
            <a href="{{ route('edutech.admin.courses') }}"
                class="menu-item {{ request()->routeIs('edutech.admin.courses*') ? 'text-blue-600 font-semibold' : 'text-white-700 hover:text-blue-600' }}">
                <i class="fas fa-book mr-2"></i> Courses Management
            </a>
            <a href="{{ route('edutech.admin.certificates') }}"
                class="menu-item {{ request()->routeIs('edutech.admin.certificates*') ? 'text-blue-600 font-semibold' : 'text-white-700 hover:text-blue-600' }}">
                <i class="fas fa-certificate mr-2"></i> Certificates
            </a>
            <div class="border-t border-gray-200 my-3"></div>
            <a href="{{ route('edutech.admin.settings') }}" class="menu-item text-white-700 hover:text-blue-600">
                <i class="fas fa-cog mr-2"></i> Settings
            </a>
            <a href="{{ route('edutech.landing') }}" class="menu-item text-white-700 hover:text-blue-600">
                <i class="fas fa-globe mr-2"></i> View Website
            </a>
        </nav>
    </aside>

    @elseif(session('edutech_user_role') === 'instructor')
    {{-- Instructor Sidebar --}}
    <aside class="sidebar fixed left-0 top-0 h-full w-64 bg-white shadow-lg border-r border-gray-200 p-5">
        <div class="sidebar-header mb-6">
            <h2 class="text-xl font-bold text-white-800 flex items-center gap-2">
                <i class="fas fa-chalkboard-teacher text-purple-600"></i>
                Instructor Panel
            </h2>
        </div>

        <nav class="sidebar-menu flex flex-col space-y-2">
            <a href="{{ route('edutech.instructor.dashboard') }}"
                class="menu-item {{ request()->routeIs('edutech.instructor.dashboard') ? 'text-blue-600 font-semibold' : 'text-white-700 hover:text-blue-600' }}">
                <i class="fas fa-home mr-2"></i> Dashboard
            </a>
            <a href="{{ route('edutech.profile.index') }}"
                class="menu-item {{ request()->routeIs('edutech.profile.index') ? 'text-blue-600 font-semibold' : 'text-white-700 hover:text-blue-600' }}">
                <i class="fas fa-user mr-2"></i> Profile Saya
            </a>
            <a href="{{ route('edutech.instructor.courses') }}"
                class="menu-item {{ request()->routeIs('edutech.instructor.courses') ? 'text-blue-600 font-semibold' : 'text-white-700 hover:text-blue-600' }}">
                <i class="fas fa-book mr-2"></i> My Courses
            </a>
            <a href="{{ route('edutech.instructor.quiz.index') }}"
                class="menu-item {{ request()->routeIs('edutech.instructor.quiz.index') ? 'text-blue-600 font-semibold' : 'text-white-700 hover:text-blue-600' }}">
                <i class="fas fa-clipboard-list mr-2"></i> Quiz Management
            </a>
            <a href="{{ route('edutech.instructor.live-meetings.index') }}"
                class="menu-item {{ request()->routeIs('edutech.instructor.live-meetings.index') ? 'text-blue-600 font-semibold' : 'text-white-700 hover:text-blue-600' }}">
                <i class="fas fa-video mr-2"></i> Live Meeting
            </a>
            <a href="{{ route('edutech.instructor.students') }}"
                class="menu-item {{ request()->routeIs('edutech.instructor.students') ? 'text-blue-600 font-semibold' : 'text-white-700 hover:text-blue-600' }}">
                <i class="fas fa-users mr-2"></i> Students
            </a>
        </nav>
    </aside>

    @else
    {{-- Student Sidebar --}}
    <aside class="sidebar fixed left-0 top-0 h-full w-64 bg-white shadow-lg border-r border-gray-200 p-5">
        <div class="sidebar-header mb-6">
            <h2 class="text-xl font-bold text-white-800 flex items-center gap-2">
                <i class="fas fa-user-graduate text-blue-600"></i>
                Student Panel
            </h2>
        </div>

        <nav class="sidebar-menu flex flex-col space-y-2">
            <a href="{{ route('edutech.student.dashboard') }}"
                class="menu-item {{ request()->routeIs('edutech.student.dashboard') ? 'text-blue-600 font-semibold' : 'text-white-700 hover:text-blue-600' }}">
                <i class="fas fa-home mr-2"></i> Dashboard
            </a>
            <a href="{{ route('edutech.profile.index') }}"
                class="menu-item {{ request()->routeIs('edutech.profile.index') ? 'text-blue-600 font-semibold' : 'text-white-700 hover:text-blue-600' }}">
                <i class="fas fa-user mr-2"></i> Profile Saya
            </a>
            <a href="{{ route('edutech.student.my-courses') }}"
                class="menu-item {{ request()->routeIs('edutech.student.my-courses') ? 'text-blue-600 font-semibold' : 'text-white-700 hover:text-blue-600' }}">
                <i class="fas fa-book mr-2"></i> My Courses
            </a>
            <a href="{{ route('edutech.student.certificates') }}"
                class="menu-item {{ request()->routeIs('edutech.student.certificates') ? 'text-blue-600 font-semibold' : 'text-white-700 hover:text-blue-600' }}">
                <i class="fas fa-certificate mr-2"></i> Certificates
            </a>
            <div class="border-t border-gray-200 my-3"></div>
            <a href="{{ route('edutech.courses.index') }}" class="menu-item text-white-700 hover:text-blue-600">
                <i class="fas fa-search mr-2"></i> Browse Courses
            </a>
            <a href="{{ route('edutech.landing') }}" class="menu-item text-white-700 hover:text-blue-600">
                <i class="fas fa-globe mr-2"></i> Home
            </a>
        </nav>
    </aside>
    @endif


    <!-- Main Section -->
    <main class="flex-1 py-10">
        <div class="max-w-4xl mx-auto px-5">

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

            {{-- Profile Card --}}
            <div class="bg-white rounded-2xl p-8 shadow-md card-smooth mb-8 border border-gray-100">
                <div class="flex items-center gap-6">
                    @if($user->avatar)
                    <img src="{{ asset('storage/' . $user->avatar) }}" alt="Avatar"
                        class="w-24 h-24 rounded-full border-4 border-indigo-100 shadow-md avatar-hover">
                    @else
                    <div
                        class="w-24 h-24 rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white text-3xl font-bold shadow-md avatar-hover">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                    @endif

                    <div>
                        <h1 class="text-2xl font-bold text-white-900">{{ $user->name }}</h1>
                        <p class="text-white-500 text-sm">{{ $user->email }}</p>
                        <div class="mt-2 flex items-center gap-3">
                            <span
                                class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold 
                                {{ $user->role === 'admin' ? 'bg-red-100 text-red-700' : ($user->role === 'instructor' ? 'bg-purple-100 text-purple-700' : 'bg-blue-100 text-blue-700') }}">
                                <i
                                    class="fas fa-{{ $user->role === 'admin' ? 'crown' : ($user->role === 'instructor' ? 'chalkboard-teacher' : 'user-graduate') }} mr-1"></i>
                                {{ ucfirst($user->role) }}
                            </span>

                            @if($user->is_active)
                            <span
                                class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700">
                                <i class="fas fa-check-circle mr-1"></i> Aktif
                            </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- Tabs --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden card-smooth">
                <nav class="flex border-b border-gray-200 bg-gray-50">
                    <button onclick="switchTab('profile')" id="tab-profile"
                        class="tab-button active w-1/2 py-4 font-semibold text-blue-600 border-b-2 border-blue-600">
                        <i class="fas fa-user mr-2"></i> Informasi Profil
                    </button>
                    <button onclick="switchTab('password')" id="tab-password"
                        class="tab-button w-1/2 py-4 font-semibold text-white-500 border-b-2 border-transparent hover:text-white-700 hover:bg-gray-100">
                        <i class="fas fa-lock mr-2"></i> Ganti Password
                    </button>
                </nav>

                {{-- Profile Tab --}}
                <div id="content-profile" class="tab-content p-6">
                    <form action="{{ route('edutech.profile.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="space-y-5">
                            {{-- Avatar --}}
                            <div>
                                <label class="block text-sm font-medium text-white-700 mb-2">
                                    <i class="fas fa-camera mr-1"></i> Foto Profil
                                </label>
                                <div class="flex items-center gap-4">
                                    <div id="avatar-preview">
                                        @if($user->avatar)
                                        <img src="{{ asset('storage/' . $user->avatar) }}"
                                            class="w-20 h-20 rounded-full object-cover border border-gray-300">
                                        @else
                                        <div
                                            class="w-20 h-20 rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white text-xl font-semibold">
                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                        </div>
                                        @endif
                                    </div>

                                    <div>
                                        <input type="file" name="avatar" id="avatar-input" accept="image/*"
                                            class="hidden">
                                        <button type="button" onclick="document.getElementById('avatar-input').click()"
                                            class="btn-gradient px-4 py-2 rounded-lg">
                                            <i class="fas fa-upload mr-2"></i> Upload Foto
                                        </button>

                                        @if($user->avatar)
                                        <button type="button" onclick="deleteAvatar()"
                                            class="btn-danger-gradient px-4 py-2 rounded-lg ml-2">
                                            <i class="fas fa-trash mr-1"></i> Hapus
                                        </button>
                                        @endif
                                        <p class="text-xs text-white-500 mt-2">JPG, PNG atau GIF (Max. 2MB)</p>
                                    </div>
                                </div>
                            </div>

                            {{-- Nama --}}
                            <div>
                                <label class="block text-sm font-medium text-white-700 mb-2">
                                    <i class="fas fa-user mr-1"></i> Nama Lengkap *
                                </label>
                                <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                                    class="w-full rounded-lg border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-transparent px-4 py-2">
                            </div>

                            {{-- Email --}}
                            <div>
                                <label class="block text-sm font-medium text-white-700 mb-2">
                                    <i class="fas fa-envelope mr-1"></i> Email *
                                </label>
                                <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                                    class="w-full rounded-lg border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-transparent px-4 py-2">
                            </div>

                            {{-- Telepon --}}
                            <div>
                                <label class="block text-sm font-medium text-white-700 mb-2">
                                    <i class="fas fa-phone mr-1"></i> No. Telepon
                                </label>
                                <input type="text" name="phone" value="{{ old('phone', $user->phone) }}"
                                    class="w-full rounded-lg border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-transparent px-4 py-2"
                                    placeholder="08xxxxxxxxxx">
                            </div>

                            {{-- Bio --}}
                            <div>
                                <label class="block text-sm font-medium text-white-700 mb-2">
                                    <i class="fas fa-align-left mr-1"></i> Bio / Tentang Saya
                                </label>
                                <textarea name="bio" rows="4"
                                    class="w-full rounded-lg border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-transparent px-4 py-2"
                                    placeholder="Ceritakan sedikit tentang diri Anda...">{{ old('bio', $user->bio) }}</textarea>
                            </div>
                        </div>

                        <div class="flex justify-end mt-6">
                            <button type="submit" class="btn-gradient px-6 py-3 rounded-lg">
                                <i class="fas fa-save mr-2"></i> Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>

                {{-- Password Tab --}}
                <div id="content-password" class="tab-content hidden p-6">
                    <form action="{{ route('edutech.profile.password') }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="bg-blue-50 border border-blue-100 p-4 rounded-lg mb-5 text-sm text-blue-800">
                            <i class="fas fa-info-circle mr-2"></i>
                            Gunakan password kuat yang terdiri dari huruf besar, kecil, angka, dan simbol.
                        </div>

                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-white-700 mb-2">
                                    <i class="fas fa-lock mr-1"></i> Password Lama *
                                </label>
                                <input type="password" name="current_password" required
                                    class="w-full rounded-lg border-gray-300 focus:ring-2 focus:ring-indigo-500 px-4 py-2">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-white-700 mb-2">
                                    <i class="fas fa-key mr-1"></i> Password Baru *
                                </label>
                                <input type="password" name="new_password" required
                                    class="w-full rounded-lg border-gray-300 focus:ring-2 focus:ring-indigo-500 px-4 py-2">
                                <p class="text-xs text-white-500 mt-1">Minimal 8 karakter</p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-white-700 mb-2">
                                    <i class="fas fa-key mr-1"></i> Konfirmasi Password Baru *
                                </label>
                                <input type="password" name="new_password_confirmation" required
                                    class="w-full rounded-lg border-gray-300 focus:ring-2 focus:ring-indigo-500 px-4 py-2">
                            </div>
                        </div>

                        <div class="flex justify-end mt-6">
                            <button type="submit" class="btn-gradient px-6 py-3 rounded-lg">
                                <i class="fas fa-shield-alt mr-2"></i> Update Password
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>

    <script>
        // Tab switching
        function switchTab(tab) {
            document.querySelectorAll('.tab-content').forEach(el => el.classList.add('hidden'));
            document.querySelectorAll('.tab-button').forEach(btn => btn.classList.remove('active'));
            document.getElementById('content-' + tab).classList.remove('hidden');
            document.getElementById('tab-' + tab).classList.add('active');
        }

        // Avatar preview
        document.getElementById('avatar-input')?.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = ev => {
                    document.getElementById('avatar-preview').innerHTML =
                        `<img src="${ev.target.result}" class="w-20 h-20 rounded-full object-cover border border-gray-300">`;
                };
                reader.readAsDataURL(file);
            }
        });

        // Delete avatar
        function deleteAvatar() {
            if (confirm('Apakah Anda yakin ingin menghapus foto profil?')) {
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
                    .catch(() => alert('Terjadi kesalahan saat menghapus foto.'));
            }
        }
    </script>
</body>

</html>