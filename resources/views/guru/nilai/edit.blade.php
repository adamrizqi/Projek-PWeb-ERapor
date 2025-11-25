@extends('layouts.app')
@section('title', 'Edit Nilai')
@section('page-title', 'Edit Nilai')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <h2 class="text-xl font-bold text-gray-900">Edit Nilai: {{ $nilai->mataPelajaran->nama_mapel }}</h2>
        <a href="{{ route('guru.nilai.show', $nilai->siswa) }}" class="text-gray-600 hover:text-gray-900">Batal</a>
    </div>

    <x-card>
        <form action="{{ route('guru.nilai.update', $nilai) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="bg-blue-50 p-4 rounded-lg mb-4">
                <p class="text-sm text-blue-800"><strong>Siswa:</strong> {{ $nilai->siswa->nama_lengkap }}</p>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Pengetahuan</label>
                    <input type="number" name="nilai_pengetahuan" value="{{ old('nilai_pengetahuan', $nilai->nilai_pengetahuan) }}"
                           min="0" max="100" required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Keterampilan</label>
                    <input type="number" name="nilai_keterampilan" value="{{ old('nilai_keterampilan', $nilai->nilai_keterampilan) }}"
                           min="0" max="100" required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Deskripsi (Opsional)</label>
                <textarea name="deskripsi" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg">{{ old('deskripsi', $nilai->deskripsi) }}</textarea>
            </div>

            <div class="flex justify-end">
                <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                    Update Nilai
                </button>
            </div>
        </form>
    </x-card>
</div>
@endsection
