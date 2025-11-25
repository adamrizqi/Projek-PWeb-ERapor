@extends('layouts.app')

@section('title', 'Manajemen Kelas')
@section('page-title', 'Manajemen Kelas')

@section('content')
<div class="space-y-6">
    <!-- Header Actions -->
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Daftar Kelas</h2>
            <p class="text-sm text-gray-600 mt-1">Kelola kelas dan wali kelas</p>
        </div>
        <a href="{{ route('admin.kelas.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Tambah Kelas
        </a>
    </div>

    <!-- Kelas Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        @forelse($kelas as $k)
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 hover:shadow-lg transition overflow-hidden">
            <!-- Header -->
            <div class="bg-gradient-to-r from-blue-500 to-purple-600 p-6 text-white">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-3xl font-bold">{{ $k->nama_kelas }}</h3>
                    <x-badge type="default" class="bg-white bg-opacity-20 text-white">
                        Tingkat {{ $k->tingkat }}
                    </x-badge>
                </div>
                <p class="text-blue-100 text-sm">{{ $k->tahun_ajaran }}</p>
            </div>

            <!-- Body -->
            <div class="p-6">
                <!-- Wali Kelas -->
                <div class="mb-4">
                    <p class="text-xs text-gray-500 uppercase mb-1">Wali Kelas</p>
                    @if($k->waliKelas)
                    <div class="flex items-center">
                        <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 font-semibold text-sm mr-2">
                            {{ substr($k->waliKelas->name, 0, 1) }}
                        </div>
                        <span class="text-sm font-medium text-gray-900">{{ $k->waliKelas->nama_pendek }}</span>
                    </div>
                    @else
                    <span class="text-sm text-red-600">Belum ada wali kelas</span>
                    @endif
                </div>

                <!-- Jumlah Siswa -->
                <div class="mb-6">
                    <p class="text-xs text-gray-500 uppercase mb-1">Jumlah Siswa</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $k->jumlah_siswa }}</p>
                </div>

                <!-- Actions -->
                <div class="flex items-center justify-between space-x-2">
                    <a href="{{ route('admin.kelas.show', $k) }}" class="flex-1 text-center px-3 py-2 bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-100 transition text-sm font-medium">
                        Detail
                    </a>
                    <a href="{{ route('admin.kelas.edit', $k) }}" class="flex-1 text-center px-3 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition text-sm font-medium">
                        Edit
                    </a>
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-full">
            <div class="bg-gray-50 rounded-lg p-12 text-center">
                <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                </svg>
                <p class="text-gray-500 mb-4">Belum ada data kelas</p>
                <a href="{{ route('admin.kelas.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Tambah Kelas Pertama
                </a>
            </div>
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($kelas->hasPages())
    <div class="mt-6">
        {{$kelas->links() }}
    </div>
    @endif
</div>
@endsection
