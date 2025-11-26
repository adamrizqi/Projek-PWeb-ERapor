@extends('layouts.app')

@section('title', 'Dashboard Guru')
@section('page-title', 'Dashboard Wali Kelas')

@section('content')
<div class="space-y-6">
    <div class="bg-gradient-to-r from-blue-600 to-purple-600 rounded-xl shadow-lg p-8 text-white">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-3xl font-bold mb-2">Kelas {{ $kelas->nama_kelas }}</h2>
                <p class="text-blue-100 mb-4">Tahun Ajaran {{ $kelas->tahun_ajaran }} • Semester 1</p>
                <div class="flex items-center space-x-6">
                    <div>
                        <p class="text-4xl font-bold">{{ $total_siswa }}</p>
                        <p class="text-blue-100 text-sm">Total Siswa</p>
                    </div>
                    <div>
                        <p class="text-4xl font-bold">{{ number_format($rata_rata_kelas, 1) }}</p>
                        <p class="text-blue-100 text-sm">Rata-rata Kelas</p>
                    </div>
                </div>
            </div>
            <div class="hidden md:block">
                <div class="w-32 h-32 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                    <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-800">Input Nilai</h3>
                <span class="text-2xl font-bold text-blue-600">{{ $progress_nilai }}%</span>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-3 mb-4">
                <div class="bg-blue-600 h-3 rounded-full transition-all duration-500" style="width: {{ $progress_nilai }}%"></div>
            </div>
            <a href="{{ route('guru.nilai.index') }}" class="text-sm text-blue-600 hover:text-blue-800 font-medium">
                Input Nilai →
            </a>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-800">Kehadiran</h3>
                <span class="text-2xl font-bold text-green-600">{{ $progress_kehadiran }}%</span>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-3 mb-4">
                <div class="bg-green-600 h-3 rounded-full transition-all duration-500" style="width: {{ $progress_kehadiran }}%"></div>
            </div>
            <a href="{{ route('guru.kehadiran.index') }}" class="text-sm text-green-600 hover:text-green-800 font-medium">
                Input Kehadiran →
            </a>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-800">Sikap & Karakter</h3>
                <span class="text-2xl font-bold text-purple-600">{{ $progress_sikap }}%</span>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-3 mb-4">
                <div class="bg-purple-600 h-3 rounded-full transition-all duration-500" style="width: {{ $progress_sikap }}%"></div>
            </div>
            <a href="{{ route('guru.sikap.index') }}" class="text-sm text-purple-600 hover:text-purple-800 font-medium">
                Input Sikap →
            </a>
        </div>
    </div>

    <x-card title="Aksi Cepat">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <a href="{{ route('guru.nilai.bulk-input') }}" class="flex flex-col items-center justify-center p-6 bg-blue-50 rounded-lg hover:bg-blue-100 transition group">
                <svg class="w-10 h-10 text-blue-600 mb-3 group-hover:scale-110 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                </svg>
                <span class="text-sm font-medium text-gray-700 text-center">Input Nilai Massal</span>
            </a>

            <a href="{{ route('guru.kehadiran.harian') }}" class="flex flex-col items-center justify-center p-6 bg-green-50 rounded-lg hover:bg-green-100 transition group">
                <svg class="w-10 h-10 text-green-600 mb-3 group-hover:scale-110 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                </svg>
                <span class="text-sm font-medium text-gray-700 text-center">Absen Harian</span>
            </a>

            <a href="{{ route('guru.rapor.validasi') }}" class="flex flex-col items-center justify-center p-6 bg-purple-50 rounded-lg hover:bg-purple-100 transition group">
                <svg class="w-10 h-10 text-purple-600 mb-3 group-hover:scale-110 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span class="text-sm font-medium text-gray-700 text-center">Validasi Rapor</span>
            </a>

            <a href="{{ route('guru.rapor.index') }}" class="flex flex-col items-center justify-center p-6 bg-orange-50 rounded-lg hover:bg-orange-100 transition group">
                <svg class="w-10 h-10 text-orange-600 mb-3 group-hover:scale-110 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <span class="text-sm font-medium text-gray-700 text-center">Cetak Rapor</span>
            </a>
        </div>
    </x-card>

    <x-card title="Mata Pelajaran Kelas {{ $kelas->nama_kelas }}">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($mata_pelajaran as $mapel)
            <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition">
                <div class="flex items-center justify-between mb-2">
                    <h4 class="font-medium text-gray-900">{{ $mapel->nama_mapel }}</h4>
                    <x-badge type="info">KKM {{ $mapel->kkm }}</x-badge>
                </div>
                <p class="text-xs text-gray-500 mb-3">{{ $mapel->kode_mapel }}</p>
                <a href="{{ route('guru.nilai.bulk-input', ['mata_pelajaran_id' => $mapel->id]) }}" class="text-sm text-blue-600 hover:text-blue-800">
                    Input Nilai →
                </a>
            </div>
            @endforeach
        </div>
    </x-card>

    <x-card title="Daftar Siswa Kelas {{ $kelas->nama_kelas }}">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead>
                    <tr>
                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">NIS</th>
                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Siswa</th>
                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">JK</th>
                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($siswa_list as $index => $siswa)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $index + 1 }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $siswa->nis }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-400 to-purple-500 flex items-center justify-center text-white font-semibold mr-3">
                                    {{ substr($siswa->nama_lengkap, 0, 1) }}
                                </div>
                                <span class="text-sm font-medium text-gray-900">{{ $siswa->nama_lengkap }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <x-badge :type="$siswa->jenis_kelamin === 'L' ? 'primary' : 'danger'">
                                {{ $siswa->jenis_kelamin === 'L' ? 'L' : 'P' }}
                            </x-badge>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            <a href="{{ route('guru.nilai.show', $siswa) }}" class="text-blue-600 hover:text-blue-900 mr-3">Nilai</a>
                            <a href="{{ route('guru.siswa.show', $siswa) }}" class="text-gray-600 hover:text-gray-900">Detail</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">
                            Belum ada siswa di kelas ini
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-card>
</div>
@endsection
