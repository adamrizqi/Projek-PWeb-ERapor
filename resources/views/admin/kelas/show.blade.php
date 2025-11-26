@extends('layouts.app')

@section('title', 'Detail Kelas')
@section('page-title', 'Detail Kelas ' . $kelas->nama_kelas)

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div class="flex items-center space-x-4">
            <a href="{{ route('admin.kelas.index') }}" class="text-gray-600 hover:text-gray-900">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </a>
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Kelas {{ $kelas->nama_kelas }}</h2>
                <p class="text-sm text-gray-600">{{ $kelas->tahun_ajaran }} • Tingkat {{ $kelas->tingkat }}</p>
            </div>
        </div>
        <div class="flex items-center space-x-3">
            <a href="{{ route('admin.kelas.edit', $kelas) }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                Edit Kelas
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Total Siswa</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $statistik['jumlah_siswa'] }}</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Laki-laki</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $statistik['jumlah_laki'] }}</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <span class="text-2xl">👦</span>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Perempuan</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $statistik['jumlah_perempuan'] }}</p>
                </div>
                <div class="w-12 h-12 bg-pink-100 rounded-lg flex items-center justify-center">
                    <span class="text-2xl">👧</span>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Rata-rata Nilai</p>
                    <p class="text-3xl font-bold text-gray-900">{{ number_format($statistik['rata_rata_nilai'], 1) }}</p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <x-card title="Wali Kelas">
        @if($kelas->waliKelas)
        <div class="flex items-center space-x-4">
            <div class="w-16 h-16 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center text-white font-bold text-2xl">
                {{ substr($kelas->waliKelas->name, 0, 1) }}
            </div>
            <div class="flex-1">
                <h3 class="text-lg font-semibold text-gray-900">{{ $kelas->waliKelas->name }}</h3>
                <p class="text-sm text-gray-600">NIP: {{ $kelas->waliKelas->nip }}</p>
                <p class="text-sm text-gray-600">{{ $kelas->waliKelas->email }}</p>
            </div>
            <a href="{{ route('admin.guru.show', $kelas->waliKelas) }}" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition">
                Lihat Profil
            </a>
        </div>
        @else
        <div class="text-center py-8">
            <p class="text-gray-500 mb-4">Kelas ini belum memiliki wali kelas</p>
            <a href="{{ route('admin.kelas.edit', $kelas) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                Tambah Wali Kelas
            </a>
        </div>
        @endif
    </x-card>

    <x-card title="Mata Pelajaran">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($mataPelajaran as $mapel)
            <div class="border border-gray-200 rounded-lg p-4">
                <h4 class="font-medium text-gray-900 mb-1">{{ $mapel->nama_mapel }}</h4>
                <p class="text-sm text-gray-600">{{ $mapel->kode_mapel }}</p>
                <div class="mt-2">
                    <x-badge type="info">KKM {{ $mapel->kkm }}</x-badge>
                </div>
            </div>
            @endforeach
        </div>
    </x-card>

    <x-card title="Daftar Siswa">
        <div class="mb-4 flex items-center justify-between">
            <div class="text-sm text-gray-600">
                Total: <span class="font-semibold">{{ $kelas->siswaAktif->count() }}</span> siswa
            </div>
            <a href="{{ route('admin.siswa.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition text-sm">
                Tambah Siswa
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead>
                    <tr>
                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase">No</th>
                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase">NIS</th>
                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase">Nama Siswa</th>
                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase">JK</th>
                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($kelas->siswaAktif as $index => $siswa)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $index + 1 }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $siswa->nis }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <img src="{{ $siswa->foto_url }}" alt="" class="w-10 h-10 rounded-full object-cover mr-3">
                                <span class="text-sm font-medium text-gray-900">{{ $siswa->nama_lengkap }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <x-badge :type="$siswa->jenis_kelamin === 'L' ? 'primary' : 'danger'">
                                {{ $siswa->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan' }}
                            </x-badge>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            <a href="{{ route('admin.siswa.show', $siswa) }}" class="text-blue-600 hover:text-blue-900">
                                Detail
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-8 text-center text-sm text-gray-500">
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
