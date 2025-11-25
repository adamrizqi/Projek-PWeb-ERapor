@extends('layouts.app')
@section('title', 'Manajemen Guru')
@section('page-title', 'Manajemen Guru')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Daftar Guru</h2>
            <p class="text-sm text-gray-600 mt-1">Kelola data guru dan wali kelas</p>
        </div>
        <div class="flex items-center space-x-3">

            <button type="button"
                    @click="$dispatch('open-modal', 'import-guru')"
                    class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                </svg>
                Import Excel
            </button>

            <a href="{{ route('admin.guru.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Tambah Guru
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <p class="text-sm text-gray-600 mb-1">Total Guru</p>
            <p class="text-3xl font-bold text-gray-900">{{ $statistik['total_guru'] }}</p>
        </div>
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <p class="text-sm text-gray-600 mb-1">Sudah Punya Kelas</p>
            <p class="text-3xl font-bold text-green-600">{{ $statistik['dengan_kelas'] }}</p>
        </div>
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <p class="text-sm text-gray-600 mb-1">Belum Punya Kelas</p>
            <p class="text-3xl font-bold text-red-600">{{ $statistik['tanpa_kelas'] }}</p>
        </div>
    </div>

    <x-card>
        <form method="GET" action="{{ route('admin.guru.index') }}" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label for="search" class="block text-sm font-medium text-gray-700 mb-2">Cari</label>
                    <input type="text"
                           name="search"
                           id="search"
                           value="{{ request('search') }}"
                           placeholder="Nama / NIP / Email"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
                <div>
                    <label for="kelas_id" class="block text-sm font-medium text-gray-700 mb-2">Status Kelas</label>
                    <select name="kelas_id"
                            id="kelas_id"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">Semua Guru</option>
                        <option value="tanpa_kelas" {{ request('kelas_id') == 'tanpa_kelas' ? 'selected' : '' }}>Tanpa Kelas</option>
                        @foreach($kelasList as $kelas)
                        <option value="{{ $kelas->id }}" {{ request('kelas_id') == $kelas->id ? 'selected' : '' }}>
                            {{ $kelas->nama_kelas }} - Tingkat {{ $kelas->tingkat }}
                        </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="flex items-center justify-end space-x-3">
                <a href="{{ route('admin.guru.index') }}" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition text-sm font-medium">
                    Reset
                </a>
                <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition text-sm font-medium">
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
                            Nama Guru
                        </th>
                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            NIP
                        </th>
                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Email
                        </th>
                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Wali Kelas
                        </th>
                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($guru as $g)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-400 to-purple-500 flex items-center justify-center text-white font-semibold mr-3">
                                    {{ substr($g->name, 0, 1) }}
                                </div>
                                <div>
                                    <div class="text-sm font-medium text-gray-900">{{ $g->name }}</div>
                                    <div class="text-xs text-gray-500">{{ $g->phone ?? '-' }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $g->nip }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $g->email }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($g->kelas)
                                <x-badge type="success">Kelas {{ $g->kelas->nama_kelas }}</x-badge>
                            @else
                                <x-badge type="danger">Belum Ada</x-badge>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            <div class="flex items-center space-x-3">
                                <a href="{{ route('admin.guru.show', $g) }}"
                                   class="text-blue-600 hover:text-blue-900"
                                   title="Detail">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                </a>
                                <a href="{{ route('admin.guru.edit', $g) }}"
                                   class="text-green-600 hover:text-green-900"
                                   title="Edit">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                                </a>
                                <button onclick="confirmDelete({{ $g->id }})"
                                        class="text-red-600 hover:text-red-900"
                                        title="Hapus">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                </button>
                            </div>
                            <form id="delete-form-{{ $g->id }}"
                                  action="{{ route('admin.guru.destroy', $g) }}"
                                  method="POST"
                                  class="hidden">
                                @csrf
                                @method('DELETE')
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12">
                            <div class="text-center">
                                <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                                <p class="text-gray-500 mb-4">Tidak ada data guru</p>
                                <a href="{{ route('admin.guru.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                                    Tambah Guru Pertama
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($guru->hasPages())
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $guru->links() }}
        </div>
        @endif
    </x-card>
</div>

<x-modal name="import-guru" title="Import Guru dari Excel" max-width="lg">
            <form action="{{ route('admin.guru.import') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="space-y-4">
            <div>
                <label for="file-guru" class="block text-sm font-medium text-gray-700 mb-2">
                    File Excel/CSV <span class="text-red-500">*</span>
                </label>
                <input type="file"
                       name="file"
                       id="file-guru"
                       accept=".xlsx,.xls,.csv"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                       required>
                <p class="mt-1 text-xs text-gray-500">Format: XLSX, XLS, atau CSV (Max: 5MB)</p>
            </div>
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                <p class="text-sm text-blue-800 font-medium mb-2">📋 Format Excel/CSV:</p>
                <p class="text-xs text-blue-700">Kolom: "Nama Lengkap","NIP","Email","No. HP","Alamat","Password" (Opsional)</p>

                <a href="{{ asset('templates/template_import_guru.csv') }}" download
                   class="text-xs text-blue-600 hover:text-blue-800 font-medium mt-2 inline-block">
                    Download Template (.csv) →
                </a>
            </div>
             <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                <p class="text-sm text-yellow-800 font-medium mb-2">⚠️ Peringatan Keamanan</p>
                <p class="text-xs text-yellow-700">Import guru akan membuat akun login baru. Pastikan file Excel berasal dari sumber terpercaya.</p>
            </div>
        </div>

        <div class="flex items-center justify-end space-x-3 mt-6">
            <button type="button"
                    @click="$dispatch('close-modal', 'import-guru')"
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
    if (confirm('Yakin ingin menghapus guru ini? Jika guru ini adalah wali kelas, kelas akan otomatis terlepas.')) {
        document.getElementById('delete-form-' + id).submit();
    }
}
</script>
@endpush
@endsection
