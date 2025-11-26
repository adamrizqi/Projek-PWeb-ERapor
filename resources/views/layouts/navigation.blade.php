<div class="flex flex-col h-full">
    <div class="flex items-center justify-center h-16 px-4 border-b border-blue-700">
        <div class="flex items-center space-x-3">
            <div class="w-10 h-10 bg-white rounded-lg flex items-center justify-center">
                <span class="text-2xl">🏫</span>
            </div>
            <div class="text-white">
                <h2 class="text-lg font-bold">e-Rapor</h2>
                <p class="text-xs text-blue-200">SDN Slumbung 1</p>
            </div>
        </div>
    </div>

    <nav class="sidebar-nav flex-1 px-4 py-6 space-y-2 overflow-y-auto">
        @if(Auth::user()->role === 'admin')
            <a href="{{ route('admin.dashboard') }}"
               class="flex items-center px-4 py-3 text-white rounded-lg {{ request()->routeIs('admin.dashboard') ? 'bg-blue-700' : 'hover:bg-blue-700' }} transition">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
                Dashboard
            </a>

            <div class="pt-4 pb-2">
                <p class="px-4 text-xs font-semibold text-blue-300 uppercase">Manajemen Data</p>
            </div>

            <a href="{{ route('admin.kelas.index') }}"
               class="flex items-center px-4 py-3 text-white rounded-lg {{ request()->routeIs('admin.kelas.*') ? 'bg-blue-700' : 'hover:bg-blue-700' }} transition">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                </svg>
                Kelas
            </a>

            <a href="{{ route('admin.siswa.index') }}"
               class="flex items-center px-4 py-3 text-white rounded-lg {{ request()->routeIs('admin.siswa.*') ? 'bg-blue-700' : 'hover:bg-blue-700' }} transition">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
                Siswa
            </a>

            <a href="{{ route('admin.guru.index') }}"
               class="flex items-center px-4 py-3 text-white rounded-lg {{ request()->routeIs('admin.guru.*') ? 'bg-blue-700' : 'hover:bg-blue-700' }} transition">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
                Guru
            </a>

            <a href="{{ route('admin.mata-pelajaran.index') }}"
               class="flex items-center px-4 py-3 text-white rounded-lg {{ request()->routeIs('admin.mata-pelajaran.*') ? 'bg-blue-700' : 'hover:bg-blue-700' }} transition">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                </svg>
                Mata Pelajaran
            </a>

            <div class="pt-4 pb-2">
                <p class="px-4 text-xs font-semibold text-blue-300 uppercase">Laporan</p>
            </div>

            <a href="{{ route('admin.laporan.nilai') }}"
               class="flex items-center px-4 py-3 text-white rounded-lg {{ request()->routeIs('admin.laporan.nilai') ? 'bg-blue-700' : 'hover:bg-blue-700' }} transition">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                </svg>
                Laporan Nilai
            </a>

            <a href="{{ route('admin.laporan.kehadiran') }}"
               class="flex items-center px-4 py-3 text-white rounded-lg {{ request()->routeIs('admin.laporan.kehadiran') ? 'bg-blue-700' : 'hover:bg-blue-700' }} transition">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                </svg>
                Laporan Kehadiran
            </a>

            <a href="{{ route('admin.laporan.rekap-kelas') }}"
               class="flex items-center px-4 py-3 text-white rounded-lg {{ request()->routeIs('admin.laporan.rekap-kelas') ? 'bg-blue-700' : 'hover:bg-blue-700' }} transition">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                Rekap Kelas
            </a>

            <div class="pt-4 pb-2">
                <p class="px-4 text-xs font-semibold text-blue-300 uppercase">Sistem</p>
            </div>

            <a href="{{ route('admin.backup') }}"
               class="flex items-center px-4 py-3 text-white rounded-lg {{ request()->routeIs('admin.backup') ? 'bg-blue-700' : 'hover:bg-blue-700' }} transition">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                </svg>
                Backup Data
            </a>

        @else

            <a href="{{ route('guru.dashboard') }}"
               class="flex items-center px-4 py-3 text-white rounded-lg {{ request()->routeIs('guru.dashboard') ? 'bg-blue-700' : 'hover:bg-blue-700' }} transition">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
                Dashboard
            </a>

            <div class="pt-4 pb-2">
                <p class="px-4 text-xs font-semibold text-blue-300 uppercase">Penilaian</p>
            </div>

            <a href="{{ route('guru.nilai.index') }}"
               class="flex items-center px-4 py-3 text-white rounded-lg {{ request()->routeIs('guru.nilai.*') ? 'bg-blue-700' : 'hover:bg-blue-700' }} transition">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                </svg>
                Input Nilai
            </a>

            <a href="{{ route('guru.kehadiran.index') }}"
               class="flex items-center px-4 py-3 text-white rounded-lg {{ request()->routeIs('guru.kehadiran.*') ? 'bg-blue-700' : 'hover:bg-blue-700' }} transition">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                </svg>
                Kehadiran
            </a>

            <a href="{{ route('guru.sikap.index') }}"
               class="flex items-center px-4 py-3 text-white rounded-lg {{ request()->routeIs('guru.sikap.*') ? 'bg-blue-700' : 'hover:bg-blue-700' }} transition">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                </svg>
                Sikap & Karakter
            </a>

            <div class="pt-4 pb-2">
                <p class="px-4 text-xs font-semibold text-blue-300 uppercase">Rapor</p>
            </div>

            <a href="{{ route('guru.rapor.index') }}"
               class="flex items-center px-4 py-3 text-white rounded-lg {{ request()->routeIs('guru.rapor.*') ? 'bg-blue-700' : 'hover:bg-blue-700' }} transition">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                Cetak Rapor
            </a>

            <a href="{{ route('guru.rapor.validasi') }}"
               class="flex items-center px-4 py-3 text-white rounded-lg {{ request()->routeIs('guru.rapor.validasi') ? 'bg-blue-700' : 'hover:bg-blue-700' }} transition">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                Validasi Kelengkapan
            </a>

            <div class="pt-4 pb-2">
                <p class="px-4 text-xs font-semibold text-blue-300 uppercase">Data</p>
            </div>

            <a href="{{ route('guru.siswa.index') }}"
               class="flex items-center px-4 py-3 text-white rounded-lg {{ request()->routeIs('guru.siswa.*') ? 'bg-blue-700' : 'hover:bg-blue-700' }} transition">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
                Data Siswa
            </a>
        @endif
    </nav>

    <div class="p-4 border-t border-blue-700">
        <div class="flex items-center space-x-3">
            <div class="w-10 h-10 rounded-full bg-white flex items-center justify-center text-blue-600 font-semibold">
                {{ substr(Auth::user()->name, 0, 1) }}
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-medium text-white truncate">{{ Auth::user()->nama_pendek }}</p>
                <p class="text-xs text-blue-200">
                    @if(Auth::user()->role === 'admin')
                        Administrator
                    @else
                        Wali Kelas {{ Auth::user()->kelas->nama_kelas ?? '-' }}
                    @endif
                </p>
            </div>
        </div>
    </div>
</div>
