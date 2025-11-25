@php
    // Cek apakah ada filter 'kelas_id' yang aktif di URL
    $filteredKelasId = request('kelas_id');

    // Tentukan rute: jika ada filter, tambahkan parameter 'kelas'. Jika tidak, panggil rute ekspor biasa.
    $exportRoute = $filteredKelasId
        ? route('admin.siswa.export', ['kelas' => $filteredKelasId])
        : route('admin.siswa.export');

    // Tentukan teks tombol
    $exportText = $filteredKelasId ? 'Export Kelas Ini' : 'Export Semua';
@endphp

@extends('layouts.app')

@section('title', 'Manajemen Siswa')
@section('page-title', 'Manajemen Siswa')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Daftar Siswa</h2>
            <p class="text-sm text-gray-600 mt-1">Kelola data siswa sekolah</p>
        </div>
    <div class="flex items-center space-x-3">
        <a href="{{ $exportRoute }}"
        class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition text-sm font-medium">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
            </svg>
            {{ $exportText }}
        </a>

        <button type="button"
                @click="$dispatch('open-modal', 'import-siswa')"
                class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
            </svg>
            Import
        </button>

        <a href="{{ route('admin.siswa.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Tambah
        </a>
    </div>
    </div>

    <x-card>
        <form method="GET" action="{{ route('admin.siswa.index') }}" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label for="search" class="block text-sm font-medium text-gray-700 mb-2">Cari</label>
                    <input type="text"
                           name="search"
                           id="search"
                           value="{{ request('search') }}"
                           placeholder="Nama / NIS / NISN"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>

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
                    <label for="jenis_kelamin" class="block text-sm font-medium text-gray-700 mb-2">Jenis Kelamin</label>
                    <select name="jenis_kelamin"
                            id="jenis_kelamin"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">Semua</option>
                        <option value="L" {{ request('jenis_kelamin') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                        <option value="P" {{ request('jenis_kelamin') == 'P' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                </div>

                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                    <select name="status"
                            id="status"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">Semua Status</option>
                        <option value="aktif" {{ request('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                        <option value="lulus" {{ request('status') == 'lulus' ? 'selected' : '' }}>Lulus</option>
                        <option value="pindah" {{ request('status') == 'pindah' ? 'selected' : '' }}>Pindah</option>
                        <option value="keluar" {{ request('status') == 'keluar' ? 'selected' : '' }}>Keluar</option>
                    </select>
                </div>
            </div>

            <div class="flex items-center justify-end space-x-3">
                <a href="{{ route('admin.siswa.index') }}" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
                    Reset
                </a>
                <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                    <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    Filter
                </button>
            </div>
        </form>
    </x-card>

    <x-card>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead>
                    <tr>
                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Foto
                        </th>
                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            NIS / NISN
                        </th>
                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Nama Lengkap
                        </th>
                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Kelas
                        </th>
                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            JK
                        </th>
                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Status
                        </th>
                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($siswa as $s)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <img src="{{ $s->foto_url }}"
                                 alt="{{ $s->nama_lengkap }}"
                                 class="w-12 h-12 rounded-full object-cover border-2 border-gray-200">
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $s->nis }}</div>
                            <div class="text-xs text-gray-500">{{ $s->nisn ?? '-' }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm font-medium text-gray-900">{{ $s->nama_lengkap }}</div>
                            <div class="text-xs text-gray-500">{{ $s->tempat_tanggal_lahir }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <x-badge type="primary">{{ $s->nama_kelas }}</x-badge>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <x-badge :type="$s->jenis_kelamin === 'L' ? 'info' : 'danger'">
                                {{ $s->jenis_kelamin === 'L' ? 'L' : 'P' }}
                            </x-badge>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <x-badge :type="$s->status_badge">
                                {{ ucfirst($s->status) }}
                            </x-badge>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            <div class="flex items-center space-x-3">
                                <a href="{{ route('admin.siswa.show', $s) }}"
                                   class="text-blue-600 hover:text-blue-900"
                                   title="Detail">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </a>
                                <a href="{{ route('admin.siswa.edit', $s) }}"
                                   class="text-green-600 hover:text-green-900"
                                   title="Edit">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </a>
                                <button onclick="confirmDelete({{ $s->id }})"
                                        class="text-red-600 hover:text-red-900"
                                        title="Hapus">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </div>

                            <form id="delete-form-{{ $s->id }}"
                                  action="{{ route('admin.siswa.destroy', $s) }}"
                                  method="POST"
                                  class="hidden">
                                @csrf
                                @method('DELETE')
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12">
                            <div class="text-center">
                                <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                </svg>
                                <p class="text-gray-500 mb-4">Tidak ada data siswa</p>
                                <a href="{{ route('admin.siswa.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                                    Tambah Siswa Pertama
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($siswa->hasPages())
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $siswa->links() }}
        </div>
        @endif
    </x-card>
</div>

<x-modal name="import-siswa" title="Import Siswa dari Excel" max-width="lg">
    <form action="{{ route('admin.siswa.import') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="space-y-4">
            <div>
                <label for="kelas_id_import" class="block text-sm font-medium text-gray-700 mb-2">
                    Kelas Tujuan <span class="text-red-500">*</span>
                </label>
                <select name="kelas_id"
                        id="kelas_id_import"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        required>
                    <option value="">-- Pilih Kelas --</option>
                    @foreach($kelasList as $kelas)
                    <option value="{{ $kelas->id }}">{{ $kelas->nama_kelas }} - Tingkat {{ $kelas->tingkat }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="file" class="block text-sm font-medium text-gray-700 mb-2">
                    File Excel/CSV <span class="text-red-500">*</span>
                </label>
                <input type="file"
                       name="file"
                       id="file"
                       accept=".xlsx,.xls,.csv"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                       required>
                <p class="mt-1 text-xs text-gray-500">Format: XLSX, XLS, atau CSV (Max: 5MB)</p>
            </div>

            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                <p class="text-sm text-blue-800 font-medium mb-2">📋 Format Excel/CSV:</p>
                <p class="text-xs text-blue-700">Kolom: NIS, NISN, Nama Lengkap, Jenis Kelamin (L/P), Tempat Lahir, Tanggal Lahir (YYYY-MM-DD), Alamat, Nama Wali, No. HP Wali</p>
                <a href="{{ asset('templates/template_import_siswa.csv') }}" download
                   class="text-xs text-blue-600 hover:text-blue-800 font-medium mt-2 inline-block">
                    Download Template (.csv) →
                </a>
            </div>
        </div>

        <div class="flex items-center justify-end space-x-3 mt-6">
            <button type="button"
                    @click="$dispatch('close-modal', 'import-siswa')"
                    class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
                Batal
            </button>
            <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                Import Data
            </button>
        </div>
    </form>
</x-modal>

@push('scripts')
<script>
function confirmDelete(id) {
    if (confirm('Yakin ingin menghapus siswa ini? Data nilai, kehadiran, dan sikap juga akan terhapus!')) {
        document.getElementById('delete-form-' + id).submit();
    }
}
</script>
@endpush
@endsection
