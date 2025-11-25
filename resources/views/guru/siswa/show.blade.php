@extends('layouts.app')
@section('title', 'Detail Siswa')
@section('page-title', 'Detail Siswa')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="mb-4">
        <a href="{{ route('guru.siswa.index') }}" class="text-gray-600 hover:text-gray-900">← Kembali ke Daftar</a>
    </div>

    <x-card>
        <div class="flex flex-col md:flex-row gap-8">
            <div class="flex-shrink-0 text-center">
                <img src="{{ $siswa->foto_url }}" alt="{{ $siswa->nama_lengkap }}" class="w-40 h-40 rounded-full object-cover border-4 border-gray-100 shadow mx-auto">
                <h2 class="mt-4 text-xl font-bold text-gray-900">{{ $siswa->nama_lengkap }}</h2>
                <p class="text-gray-500">{{ $siswa->nis }}</p>
                <div class="mt-2">
                    <x-badge :type="$siswa->status_badge">{{ ucfirst($siswa->status) }}</x-badge>
                </div>
            </div>

            <div class="flex-1 space-y-4">
                <div>
                    <h3 class="text-lg font-semibold text-gray-800 border-b pb-2 mb-3">Biodata Diri</h3>
                    <dl class="grid grid-cols-1 gap-x-4 gap-y-4 sm:grid-cols-2">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">NISN</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $siswa->nisn ?? '-' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Jenis Kelamin</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $siswa->jenis_kelamin_lengkap }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Tempat, Tanggal Lahir</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $siswa->tempat_tanggal_lahir }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Umur</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $siswa->umur }} Tahun</dd>
                        </div>
                        <div class="sm:col-span-2">
                            <dt class="text-sm font-medium text-gray-500">Alamat</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $siswa->alamat }}</dd>
                        </div>
                    </dl>
                </div>

                <div class="pt-4">
                    <h3 class="text-lg font-semibold text-gray-800 border-b pb-2 mb-3">Data Wali</h3>
                    <dl class="grid grid-cols-1 gap-x-4 gap-y-4 sm:grid-cols-2">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Nama Wali</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $siswa->nama_wali }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">No. Telepon</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $siswa->phone_wali ?? '-' }}</dd>
                        </div>
                    </dl>
                </div>
            </div>
        </div>
    </x-card>
</div>
@endsection
