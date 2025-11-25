@extends('layouts.app')
@section('title', 'Laporan Kehadiran')
@section('page-title', 'Laporan Kehadiran Siswa')

@section('content')
<div class="space-y-6">
    <div>
        <h2 class="text-2xl font-bold text-gray-900">Laporan Kehadiran Siswa</h2>
        <p class="text-sm text-gray-600 mt-1">Rekapitulasi kehadiran (absensi) keseluruhan siswa</p>
    </div>

    <x-card>
        <form method="GET" action="{{ route('admin.laporan.kehadiran') }}" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label for="kelas_id" class="block text-sm font-medium text-gray-700 mb-2">Kelas</label>
                    <select name="kelas_id"
                            id="kelas_id"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">Semua Kelas</option>
                        @foreach($kelasList as $kelas)
                        <option value="{{ $kelas->id }}" {{ request('kelas_id') == $kelas->id ? 'selected' : '' }}>
                            {{ $kelas->nama_kelas }} - Tingkat {{ $kelas->tingkat }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="semester" class="block text-sm font-medium text-gray-700 mb-2">Semester</label>
                    <select name="semester"
                            id="semester"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="1" {{ request('semester', 1) == 1 ? 'selected' : '' }}>Semester 1</option>
                        <option value="2" {{ request('semester', 1) == 2 ? 'selected' : '' }}>Semester 2</option>
                    </select>
                </div>
                <div>
                    <label for="tahun_ajaran" class="block text-sm font-medium text-gray-700 mb-2">Tahun Ajaran</label>
                    <input type="text"
                           name="tahun_ajaran"
                           id="tahun_ajaran"
                           value="{{ request('tahun_ajaran', '2024/2025') }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
            </div>

            <div class="flex items-center justify-end space-x-3">
                <a href="{{ route('admin.laporan.kehadiran') }}" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition text-sm font-medium">
                    Reset
                </a>
                <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition text-sm font-medium">
                    <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    Tampilkan
                </button>
            </div>
        </form>
    </x-card>

    <div class="grid grid-cols-1 md:grid-cols-5 gap-6">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <p class="text-sm text-gray-600 mb-1">Rata-rata Kehadiran</p>
            <p class="text-3xl font-bold text-blue-600">{{ $statistik['rata_persentase'] }}%</p>
        </div>
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <p class="text-sm text-gray-600 mb-1">Total Hadir</p>
            <p class="text-3xl font-bold text-green-600">{{ $statistik['total_hadir'] }}</p>
        </div>
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <p class="text-sm text-gray-600 mb-1">Total Sakit</p>
            <p class="text-3xl font-bold text-yellow-600">{{ $statistik['total_sakit'] }}</p>
        </div>
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <p class="text-sm text-gray-600 mb-1">Total Izin</p>
            <p class="text-3xl font-bold text-gray-600">{{ $statistik['total_izin'] }}</p>
        </div>
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <p class="text-sm text-gray-600 mb-1">Total Alpa</p>
            <p class="text-3xl font-bold text-red-600">{{ $statistik['total_alpa'] }}</p>
        </div>
    </div>

    <x-card>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead>
                    <tr>
                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Siswa</th>
                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kelas</th>
                        <th class="px-6 py-3 bg-gray-50 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Persentase</th>
                        <th class="px-6 py-3 bg-gray-50 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Hadir</th>
                        <th class="px-6 py-3 bg-gray-50 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Sakit</th>
                        <th class="px-6 py-3 bg-gray-50 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Izin</th>
                        <th class="px-6 py-3 bg-gray-50 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Alpa</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($kehadiran as $k)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $k->siswa->nama_lengkap }}</div>
                            <div class="text-xs text-gray-500">{{ $k->siswa->nis }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <x-badge type="primary">{{ $k->siswa->kelas->nama_kelas }}</x-badge>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <span class="text-sm font-bold text-gray-900">{{ $k->persentase_kehadiran }}%</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium text-green-600">
                            {{ $k->hadir }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium text-yellow-600">
                            {{ $k->sakit }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium text-gray-600">
                            {{ $k->izin }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium text-red-600">
                            {{ $k->alpa }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                            Tidak ada data kehadiran untuk filter yang dipilih.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-card>
</div>
@endsection
