@extends('layouts.app')
@section('title', 'Detail Mata Pelajaran')
@section('page-title', 'Detail Mata Pelajaran')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center space-x-4">
            <a href="{{ route('admin.mata-pelajaran.index') }}" class="text-gray-600 hover:text-gray-900">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </a>
            <div>
                <h2 class="text-2xl font-bold text-gray-900">{{ $mataPelajaran->nama_mapel }}</h2>
                <p class="text-sm text-gray-600">{{ $mataPelajaran->kode_mapel }} • Tingkat {{ $mataPelajaran->tingkat }}</p>
            </div>
        </div>
        <a href="{{ route('admin.mata-pelajaran.edit', $mataPelajaran) }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
            Edit
        </a>
    </div>

    <x-card>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-gray-50 rounded-lg p-6">
                <p class="text-sm text-gray-600 mb-1">KKM</p>
                <p class="text-4xl font-bold text-gray-900">{{ $mataPelajaran->kkm }}</p>
            </div>
            <div class="bg-gray-50 rounded-lg p-6">
                <p class="text-sm text-gray-600 mb-1">Kategori</p>
                <p class="text-4xl font-bold text-gray-900">{{ $mataPelajaran->kategori }}</p>
            </div>
            <div class="bg-gray-50 rounded-lg p-6">
                <p class="text-sm text-gray-600 mb-1">Total Nilai Tercatat</p>
                <p class="text-4xl font-bold text-blue-600">{{ $statistik['total_nilai'] }}</p>
            </div>
            <div class="bg-gray-50 rounded-lg p-6">
                <p class="text-sm text-gray-600 mb-1">Rata-rata Nilai</p>
                <p class="text-4xl font-bold text-green-600">{{ $statistik['rata_rata'] }}</p>
            </div>
        </div>
    </x-card>
</div>
@endsection
