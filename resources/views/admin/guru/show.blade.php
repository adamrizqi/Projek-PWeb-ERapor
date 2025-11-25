@extends('layouts.app')
@section('title', 'Detail Guru')
@section('page-title', 'Detail Guru')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div class="flex items-center space-x-4">
            <a href="{{ route('admin.guru.index') }}" class="text-gray-600 hover:text-gray-900">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </a>
            <div>
                <h2 class="text-2xl font-bold text-gray-900">{{ $guru->name }}</h2>
                <p class="text-sm text-gray-600">NIP: {{ $guru->nip }}</p>
            </div>
        </div>
        <div class="flex items-center space-x-3">
            <a href="{{ route('admin.guru.edit', $guru) }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                Edit Data
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-1">
            <x-card>
                <div class="text-center">
                    <div class="w-32 h-32 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center text-white font-bold text-5xl mx-auto mb-6">
                        {{ substr($guru->name, 0, 1) }}
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-1">{{ $guru->name }}</h3>
                    <p class="text-gray-600 mb-4">{{ $guru->nip }}</p>
                    <x-badge type="primary" class="text-sm">Guru</x-badge>
                </div>

                <div class="mt-6 pt-6 border-t border-gray-200 space-y-3">
                    <div class="flex items-center text-sm">
                        <svg class="w-5 h-5 text-gray-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                        <p class="text-gray-900 font-medium">{{ $guru->email }}</p>
                    </div>
                    <div class="flex items-center text-sm">
                        <svg class="w-5 h-5 text-gray-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498A1 1 0 0119 15.28V19a2 2 0 01-2 2h-1a9 9 0 01-9-9v-1a2 2 0 012-2z" />
                        </svg>
                        <p class="text-gray-900 font-medium">{{ $guru->phone ?? 'Belum diisi' }}</p>
                    </div>
                    <div class="flex items-center text-sm">
                        <svg class="w-5 h-5 text-gray-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        <p class="text-gray-900 font-medium">{{ $guru->address ?? 'Belum diisi' }}</p>
                    </div>
                </div>
            </x-card>
        </div>

        <div class="lg:col-span-2 space-y-6">
            <x-card title="Penugasan Wali Kelas">
                @if($guru->kelas)
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-blue-700">Wali Kelas</p>
                            <h3 class="text-2xl font-bold text-blue-900">Kelas {{ $guru->kelas->nama_kelas }}</h3>
                            <p class="text-sm text-blue-700">Tingkat {{ $guru->kelas->tingkat }} • {{ $guru->kelas->tahun_ajaran }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-3xl font-bold text-blue-900">{{ $statistik['total_siswa'] }}</p>
                            <p class="text-sm text-blue-700">Siswa</p>
                        </div>
                    </div>
                    <div class="mt-6 pt-6 border-t border-blue-200">
                        <a href="{{ route('admin.kelas.show', $guru->kelas) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                            Lihat Detail Kelas
                        </a>
                    </div>
                </div>
                @else
                <div class="text-center py-8">
                    <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                    <p class="text-gray-500 mb-4">Guru ini belum ditugaskan sebagai wali kelas</p>
                </div>
                @endif
            </x-card>

            <x-card title="Aksi Cepat">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @if(!$guru->kelas)

                    <button type="button"
                            @click="$dispatch('open-modal', 'assign-kelas')"
                            class="flex flex-col items-center justify-center p-4 bg-green-50 rounded-lg hover:bg-green-100 transition group">
                        <svg class="w-8 h-8 text-green-600 mb-2 group-hover:scale-110 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                        </svg>
                        <span class="text-sm font-medium text-gray-700">Tugaskan Kelas</span>
                    </button>
                    @endif

                    <button type="button"
                            @click="$dispatch('open-modal', 'reset-password')"
                            class="flex flex-col items-center justify-center p-4 bg-yellow-50 rounded-lg hover:bg-yellow-100 transition group">
                        <svg class="w-8 h-8 text-yellow-600 mb-2 group-hover:scale-110 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                        <span class="text-sm font-medium text-gray-700">Reset Password</span>
                    </button>

                    <a href="{{ route('admin.guru.edit', $guru) }}"
                        class="flex flex-col items-center justify-center p-4 bg-purple-50 rounded-lg hover:bg-purple-100 transition group">
                        <svg class="w-8 h-8 text-purple-600 mb-2 group-hover:scale-110 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        <span class="text-sm font-medium text-gray-700">Edit Data</span>
                    </a>
                </div>
            </x-card>
        </div>
    </div>
</div>

<x-modal name="assign-kelas" title="Tugaskan Wali Kelas" max-width="md">
    <form action="{{ route('admin.guru.assign-kelas', $guru) }}" method="POST">
        @csrf
        <div class="space-y-4">
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                <p class="text-sm text-blue-800">
                    <strong>Guru:</strong> {{ $guru->name }}
                </p>
            </div>
            <div>
                <label for="kelas_id_assign" class="block text-sm font-medium text-gray-700 mb-2">
                    Kelas <span class="text-red-500">*</span>
                </label>
                <select name="kelas_id"
                        id="kelas_id_assign"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        required>
                    <option value="">-- Pilih Kelas --</option>
                    @foreach(\App\Models\Kelas::whereNull('wali_kelas_id')->orderBy('tingkat')->orderBy('nama_kelas')->get() as $kelas)
                    <option value="{{ $kelas->id }}">{{ $kelas->nama_kelas }} - Tingkat {{ $kelas->tingkat }}</option>
                    @endforeach
                </select>
                <p class="mt-1 text-xs text-gray-500">Hanya menampilkan kelas yang belum punya wali.</p>
            </div>
        </div>
        <div class="flex items-center justify-end space-x-3 mt-6">
            <button type="button"
                    @click="$dispatch('close-modal', 'assign-kelas')"
                    class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
                Batal
            </button>
            <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                Tugaskan
            </button>
        </div>
    </form>
</x-modal>

<x-modal name="reset-password" title="Reset Password" max-width="md">
    <form action="{{ route('admin.guru.reset-password', $guru) }}" method="POST">
        @csrf
        <div class="space-y-4">
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                <p class="text-sm text-yellow-800">
                    <strong>Guru:</strong> {{ $guru->name }}
                </p>
            </div>
            <div>
                <label for="new_password" class="block text-sm font-medium text-gray-700 mb-2">
                    Password Baru <span class="text-red-500">*</span>
                </label>
                <input type="password"
                       name="new_password"
                       id="new_password"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('new_password') border-red-500 @enderror"
                       required>
                @error('new_password')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label for="new_password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                    Konfirmasi Password Baru <span class="text-red-500">*</span>
                </label>
                <input type="password"
                       name="new_password_confirmation"
                       id="new_password_confirmation"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                       required>
            </div>
        </div>
        <div class="flex items-center justify-end space-x-3 mt-6">
            <button type="button"
                    @click="$dispatch('close-modal', 'reset-password')"
                    class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
                Batal
            </button>
            <button type="submit" class="px-4 py-2 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700 transition">
                Reset Password
            </button>
        </div>
    </form>
</x-modal>
@endsection
