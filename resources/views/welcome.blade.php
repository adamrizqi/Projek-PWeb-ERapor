<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>e-Rapor SDN Slumbung 1</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased">
    <div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-purple-50">
        <!-- Header -->
        <header class="absolute top-0 left-0 right-0 z-10">
            <nav class="container mx-auto px-6 py-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="w-12 h-12 bg-gradient-to-br from-blue-600 to-purple-600 rounded-lg flex items-center justify-center">
                            <span class="text-2xl">🏫</span>
                        </div>
                        <div>
                            <h1 class="text-xl font-bold text-gray-800">e-Rapor</h1>
                            <p class="text-sm text-gray-600">SDN Slumbung 1</p>
                        </div>
                    </div>

                    @auth
                        <a href="{{ route('dashboard') }}" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                            Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                            Login
                        </a>
                    @endauth
                </div>
            </nav>
        </header>

        <!-- Hero Section -->
        <section class="container mx-auto px-6 pt-32 pb-20">
            <div class="grid md:grid-cols-2 gap-12 items-center">
                <div>
                    <h2 class="text-5xl font-bold text-gray-900 mb-6 leading-tight">
                        Sistem Rapor Digital untuk
                        <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-purple-600">
                            SDN Slumbung 1
                        </span>
                    </h2>
                    <p class="text-xl text-gray-600 mb-8">
                        Platform modern untuk mengelola nilai, kehadiran, dan rapor siswa secara digital.
                        Lebih cepat, akurat, dan terarsip dengan baik.
                    </p>

                    @guest
                    <div class="flex space-x-4">
                        <a href="{{ route('login') }}" class="px-8 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-semibold">
                            Login Sekarang
                        </a>
                        <a href="#fitur" class="px-8 py-3 bg-white text-blue-600 border-2 border-blue-600 rounded-lg hover:bg-blue-50 transition font-semibold">
                            Lihat Fitur
                        </a>
                    </div>
                    @endguest
                </div>

                <div class="relative">
                    <div class="relative z-10">
                        <img src="https://illustrations.popsy.co/amber/student-going-to-school.svg" alt="Ilustrasi" class="w-full">
                    </div>
                    <div class="absolute top-10 -left-10 w-72 h-72 bg-purple-300 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-blob"></div>
                    <div class="absolute top-10 -right-10 w-72 h-72 bg-yellow-300 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-blob animation-delay-2000"></div>
                    <div class="absolute -bottom-10 left-20 w-72 h-72 bg-pink-300 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-blob animation-delay-4000"></div>
                </div>
            </div>
        </section>

        <!-- Features Section -->
        <section id="fitur" class="container mx-auto px-6 py-20">
            <div class="text-center mb-16">
                <h3 class="text-3xl font-bold text-gray-900 mb-4">Fitur Unggulan</h3>
                <p class="text-lg text-gray-600">Solusi lengkap untuk administrasi rapor sekolah</p>
            </div>

            <div class="grid md:grid-cols-3 gap-8">
                <!-- Feature 1 -->
                <div class="bg-white p-8 rounded-2xl shadow-lg hover:shadow-xl transition">
                    <div class="w-16 h-16 bg-blue-100 rounded-xl flex items-center justify-center mb-6">
                        <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                    </div>
                    <h4 class="text-xl font-bold text-gray-900 mb-3">Manajemen Siswa</h4>
                    <p class="text-gray-600">Kelola data siswa dengan mudah, termasuk foto, biodata lengkap, dan riwayat akademik.</p>
                </div>

                <!-- Feature 2 -->
                <div class="bg-white p-8 rounded-2xl shadow-lg hover:shadow-xl transition">
                    <div class="w-16 h-16 bg-green-100 rounded-xl flex items-center justify-center mb-6">
                        <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                        </svg>
                    </div>
                    <h4 class="text-xl font-bold text-gray-900 mb-3">Input Nilai Digital</h4>
                    <p class="text-gray-600">Input nilai pengetahuan dan keterampilan dengan perhitungan otomatis dan konversi predikat.</p>
                </div>

                <!-- Feature 3 -->
                <div class="bg-white p-8 rounded-2xl shadow-lg hover:shadow-xl transition">
                    <div class="w-16 h-16 bg-purple-100 rounded-xl flex items-center justify-center mb-6">
                        <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <h4 class="text-xl font-bold text-gray-900 mb-3">Cetak Rapor PDF</h4>
                    <p class="text-gray-600">Generate rapor dalam format PDF dengan desain profesional dan siap cetak.</p>
                </div>

                <!-- Feature 4 -->
                <div class="bg-white p-8 rounded-2xl shadow-lg hover:shadow-xl transition">
                    <div class="w-16 h-16 bg-yellow-100 rounded-xl flex items-center justify-center mb-6">
                        <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                        </svg>
                    </div>
                    <h4 class="text-xl font-bold text-gray-900 mb-3">Rekap Kehadiran</h4>
                    <p class="text-gray-600">Catat kehadiran siswa dengan mudah dan lihat statistik kehadiran per kelas.</p>
                </div>

                <!-- Feature 5 -->
                <div class="bg-white p-8 rounded-2xl shadow-lg hover:shadow-xl transition">
                    <div class="w-16 h-16 bg-red-100 rounded-xl flex items-center justify-center mb-6">
                        <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                        </svg>
                    </div>
                    <h4 class="text-xl font-bold text-gray-900 mb-3">Penilaian Sikap</h4>
                    <p class="text-gray-600">Input deskripsi sikap spiritual dan sosial dengan template yang memudahkan.</p>
                </div>

                <!-- Feature 6 -->
                <div class="bg-white p-8 rounded-2xl shadow-lg hover:shadow-xl transition">
                    <div class="w-16 h-16 bg-indigo-100 rounded-xl flex items-center justify-center mb-6">
                        <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                    </div>
                    <h4 class="text-xl font-bold text-gray-900 mb-3">Dashboard & Statistik</h4>
                    <p class="text-gray-600">Pantau progress dan statistik nilai dengan visualisasi yang informatif.</p>
                </div>
            </div>
        </section>

        <!-- Stats Section -->
        <section class="bg-gradient-to-r from-blue-600 to-purple-600 py-20">
            <div class="container mx-auto px-6">
                <div class="grid md:grid-cols-4 gap-8 text-center text-white">
                    <div>
                        <div class="text-5xl font-bold mb-2">12</div>
                        <div class="text-blue-100">Kelas</div>
                    </div>
                    <div>
                        <div class="text-5xl font-bold mb-2">300+</div>
                        <div class="text-blue-100">Siswa</div>
                    </div>
                    <div>
                        <div class="text-5xl font-bold mb-2">12</div>
                        <div class="text-blue-100">Guru</div>
                    </div>
                    <div>
                        <div class="text-5xl font-bold mb-2">100%</div>
                        <div class="text-blue-100">Digital</div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Footer -->
        <footer class="bg-gray-900 text-white py-12">
            <div class="container mx-auto px-6">
                <div class="text-center">
                    <div class="flex items-center justify-center space-x-3 mb-4">
                        <div class="w-10 h-10 bg-gradient-to-br from-blue-600 to-purple-600 rounded-lg flex items-center justify-center">
                            <span class="text-xl">🏫</span>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold">e-Rapor SDN Slumbung 1</h3>
                        </div>
                    </div>
                    <p class="text-gray-400 mb-4">
                        Jl. Pendidikan No. 1, Slumbung, Jember, Jawa Timur
                    </p>
                    <p class="text-gray-500 text-sm">
                        &copy; {{ date('Y') }} e-Rapor SDN Slumbung 1. Dikembangkan dengan ❤️ untuk pendidikan.
                    </p>
                </div>
            </div>
        </footer>
    </div>

    <style>
        @keyframes blob {
            0% { transform: translate(0px, 0px) scale(1); }
            33% { transform: translate(30px, -50px) scale(1.1); }
            66% { transform: translate(-20px, 20px) scale(0.9); }
            100% { transform: translate(0px, 0px) scale(1); }
        }
        .animate-blob {
            animation: blob 7s infinite;
        }
        .animation-delay-2000 {
            animation-delay: 2s;
        }
        .animation-delay-4000 {
            animation-delay: 4s;
        }
    </style>
</body>
</html>
