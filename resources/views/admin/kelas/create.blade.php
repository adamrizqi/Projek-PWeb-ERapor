@extends('layouts.app')

@section('title', 'Tambah Kelas')
@section('page-title', 'Tambah Kelas Baru')

@section('content')
<div class="max-w-2xl mx-auto">
    <x-card>
        <form action="{{ route('admin.kelas.store') }}" method="POST" class="space-y-6">
            @csrf

            <div>
                <label for="nama_kelas" class="block text-sm font-medium text-gray-700 mb-2">
                    Nama Kelas <span class="text-red-500">*</span>
                </label>
                <input type="text"
                       name="nama_kelas"
                       id="nama_kelas"
                       value="{{ old('nama_kelas') }}"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('nama_kelas') border-red-500 @enderror"
                       placeholder="Contoh: 1A, 2B, dst"
                       required>
                @error('nama_kelas')
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
                <label for="tahun_ajaran" class="block text-sm font-medium text-gray-700 mb-2">
                    Tahun Ajaran <span class="text-red-500">*</span>
                </label>
                <select name="tahun_ajaran"
                        id="tahun_ajaran"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('tahun_ajaran') border-red-500 @enderror"
                        required>
                    <option value="">-- Pilih Tahun Ajaran --</option>
                    @foreach($tahunAjaranList as $ta)
                    <option value="{{ $ta }}" {{ old('tahun_ajaran') == $ta ? 'selected' : '' }}>
                        {{ $ta }}
                    </option>
                    @endforeach
                </select>
                @error('tahun_ajaran')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="wali_kelas_id" class="block text-sm font-medium text-gray-700 mb-2">
                    Wali Kelas (Opsional)
                </label>
                <select name="wali_kelas_id"
                        id="wali_kelas_id"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('wali_kelas_id') border-red-500 @enderror">
                    <option value="">-- Pilih Wali Kelas --</option>
                    @foreach($guruTersedia as $guru)
                    <option value="{{ $guru->id }}" {{ old('wali_kelas_id') == $guru->id ? 'selected' : '' }}>
                        {{ $guru->nama_pendek }} ({{ $guru->nip }})
                    </option>
                    @endforeach
                </select>
                @error('wali_kelas_id')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
                <p class="mt-1 text-xs text-gray-500">Wali kelas dapat ditambahkan nanti</p>
            </div>

            <div class="flex items-center justify-end space-x-3 pt-6 border-t border-gray-200">
                <a href="{{ route('admin.kelas.index') }}" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
                    Batal
                </a>
                <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                    Simpan Kelas
                </button>
            </div>
        </form>
    </x-card>
</div>
@endsection
