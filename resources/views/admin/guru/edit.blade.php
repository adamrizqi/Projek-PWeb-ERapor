@extends('layouts.app')
@section('title', 'Edit Guru')
@section('page-title', 'Edit Data Guru')

@section('content')
<div class="max-w-4xl mx-auto">
    <x-card>
        <form action="{{ route('admin.guru.update', $guru) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <div>
                <h3 class="text-lg font-semibold text-gray-900 mb-4 pb-2 border-b border-gray-200">Data Diri</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                            Nama Lengkap (dengan gelar) <span class="text-red-500">*</span>
                        </label>
                        <input type="text"
                               name="name"
                               id="name"
                               value="{{ old('name', $guru->name) }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('name') border-red-500 @enderror"
                               required>
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="nip" class="block text-sm font-medium text-gray-700 mb-2">
                            NIP <span class="text-red-500">*</span>
                        </label>
                        <input type="text"
                               name="nip"
                               id="nip"
                               value="{{ old('nip', $guru->nip) }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('nip') border-red-500 @enderror"
                               required>
                        @error('nip')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">
                            No. HP/WhatsApp
                        </label>
                        <input type="text"
                               name="phone"
                               id="phone"
                               value="{{ old('phone', $guru->phone) }}"
                               placeholder="08xxxxxxxxxx"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('phone') border-red-500 @enderror">
                        @error('phone')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="md:col-span-2">
                        <label for="address" class="block text-sm font-medium text-gray-700 mb-2">
                            Alamat
                        </label>
                        <textarea name="address"
                                  id="address"
                                  rows="3"
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('address') border-red-500 @enderror">{{ old('address', $guru->address) }}</textarea>
                        @error('address')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <div>
                <h3 class="text-lg font-semibold text-gray-900 mb-4 pb-2 border-b border-gray-200">Akun & Penugasan</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                            Email <span class="text-red-500">*</span>
                        </label>
                        <input type="email"
                               name="email"
                               id="email"
                               value="{{ old('email', $guru->email) }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('email') border-red-500 @enderror"
                               required>
                        @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="kelas_id" class="block text-sm font-medium text-gray-700 mb-2">
                            Tugaskan sebagai Wali Kelas
                        </label>
                        <select name="kelas_id"
                                id="kelas_id"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('kelas_id') border-red-500 @enderror">
                            <option value="">-- Lepas dari Kelas --</option>
                            @foreach($kelasTersedia as $kelas)
                            <option value="{{ $kelas->id }}" {{ old('kelas_id', $guru->kelas_id) == $kelas->id ? 'selected' : '' }}>
                                {{ $kelas->nama_kelas }} - Tingkat {{ $kelas->tingkat }}
                            </option>
                            @endforeach
                        </select>
                        <p class="mt-1 text-xs text-gray-500">Hanya menampilkan kelas yang kosong atau kelas yang diajar saat ini.</p>
                        @error('kelas_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-between pt-6 border-t border-gray-200">
                <button type="button"
                        onclick="if(confirm('Yakin ingin menghapus guru ini?')) document.getElementById('delete-form').submit()"
                        class="px-6 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition">
                    Hapus Guru
                </button>
                <div class="flex items-center space-x-3">
                    <a href="{{ route('admin.guru.show', $guru) }}" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
                        Batal
                    </a>
                    <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                        Update Data
                    </button>
                </div>
            </div>
        </form>

        <form id="delete-form" action="{{ route('admin.guru.destroy', $guru) }}" method="POST" class="hidden">
            @csrf
            @method('DELETE')
        </form>
    </x-card>
</div>
@endsection
