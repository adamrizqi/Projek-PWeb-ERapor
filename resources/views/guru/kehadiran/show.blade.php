@extends('layouts.app')
@section('title', 'Edit Kehadiran')
@section('page-title', 'Edit Rekap Kehadiran')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <div class="flex justify-between items-center">
        <h2 class="text-xl font-bold text-gray-900">{{ $siswa->nama_lengkap }}</h2>
        <a href="{{ route('guru.kehadiran.index') }}" class="text-gray-600 hover:text-gray-900">Kembali</a>
    </div>

    <x-card title="Form Rekap Kehadiran (Semester Ini)">
        <form action="{{ route('guru.kehadiran.store', $siswa) }}" method="POST" class="space-y-6">
            @csrf

            <div class="grid grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Jumlah Sakit</label>
                    <input type="number" name="sakit" value="{{ $kehadiran ? $kehadiran->sakit : 0 }}" min="0"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-yellow-500 focus:border-yellow-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Jumlah Izin</label>
                    <input type="number" name="izin" value="{{ $kehadiran ? $kehadiran->izin : 0 }}" min="0"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Jumlah Alpa</label>
                    <input type="number" name="alpa" value="{{ $kehadiran ? $kehadiran->alpa : 0 }}" min="0"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-red-500 focus:border-red-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Jumlah Hadir</label>
                    <input type="number" name="hadir" value="{{ $kehadiran ? $kehadiran->hadir : 0 }}" min="0"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-green-500 focus:border-green-500">
                </div>
            </div>

            <div class="flex justify-end pt-4 border-t">
                <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </x-card>
</div>
@endsection
