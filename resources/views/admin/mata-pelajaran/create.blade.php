@extends('layouts.app')
@section('title', 'Tambah Mata Pelajaran')
@section('page-title', 'Tambah Mata Pelajaran Baru')

@section('content')
<div class="max-w-2xl mx-auto">
    <x-card>
        <form action="{{ route('admin.mata-pelajaran.store') }}" method="POST" class="space-y-6">
            @csrf

            <div>
                <label for="nama_mapel" class="block text-sm font-medium text-gray-700 mb-2">
                    Nama Mata Pelajaran <span class="text-red-500">*</span>
                </label>
                <input type="text"
                       name="nama_mapel"
                       id="nama_mapel"
                       value="{{ old('nama_mapel') }}"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('nama_mapel') border-red-500 @enderror"
                       placeholder="Contoh: Pendidikan Agama Islam"
                       required>
                @error('nama_mapel')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="kode_mapel" class="block text-sm font-medium text-gray-700 mb-2">
                    Kode Mata Pelajaran <span class="text-red-500">*</span>
                </label>
                <input type="text"
                       name="kode_mapel"
                       id="kode_mapel"
                       value="{{ old('kode_mapel') }}"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('kode_mapel') border-red-500 @enderror"
                       placeholder="Contoh: PAI (Pastikan Unik)"
                       required>
                @error('kode_mapel')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="tingkat" class="block text-sm font-medium text-gray-700 mb-2">
                    Tingkat <span class="text-red-500">*</span>
                </label>
                <select name="tingkat"
                        id="tingkat"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('tingkat') border-red-500 @enderror"
                        required>
                    <option value="">-- Pilih Tingkat --</option>
                    @for($i = 1; $i <= 6; $i++)
                    <option value="{{ $i }}" {{ old('tingkat') == $i ? 'selected' : '' }}>
                        Kelas {{ $i }}
                    </option>
                    @endfor
                </select>
                @error('tingkat')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="kkm" class="block text-sm font-medium text-gray-700 mb-2">
                    KKM (Kriteria Ketuntasan Minimal) <span class="text-red-500">*</span>
                </label>
                <input type="number"
                       name="kkm"
                       id="kkm"
                       value="{{ old('kkm', 70) }}"
                       min="0"
                       max="100"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('kkm') border-red-500 @enderror"
                       required>
                @error('kkm')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center justify-end space-x-3 pt-6 border-t border-gray-200">
                <a href="{{ route('admin.mata-pelajaran.index') }}" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
                    Batal
                </a>
                <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                    Simpan Mata Pelajaran
                </button>
            </div>
        </form>
    </x-card>
</div>
@endsection
