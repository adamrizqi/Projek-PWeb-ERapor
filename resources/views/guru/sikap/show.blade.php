@extends('layouts.app')
@section('title', 'Input Sikap')
@section('page-title', 'Input Sikap: ' . $siswa->nama_lengkap)

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex justify-between items-center">
        <h2 class="text-xl font-bold text-gray-900">{{ $siswa->nama_lengkap }}</h2>
        <a href="{{ route('guru.sikap.index') }}" class="text-gray-600 hover:text-gray-900">Kembali</a>
    </div>

    <form action="{{ route('guru.sikap.store', $siswa) }}" method="POST">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <x-card title="Sikap Spiritual (KI-1)">
                <div class="mb-4 bg-blue-50 p-3 rounded text-xs text-blue-800">
                    Contoh: "Selalu berdoa sebelum dan sesudah belajar, taat beribadah, dan menghormati teman yang berbeda agama."
                </div>
                <textarea name="sikap_spiritual" rows="6"
                          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                          placeholder="Deskripsikan sikap spiritual siswa..." required>{{ $sikap ? $sikap->sikap_spiritual : '' }}</textarea>
            </x-card>

            <x-card title="Sikap Sosial (KI-2)">
                <div class="mb-4 bg-green-50 p-3 rounded text-xs text-green-800">
                    Contoh: "Memiliki sikap santun, jujur, disiplin, dan peduli terhadap lingkungan sekolah."
                </div>
                <textarea name="sikap_sosial" rows="6"
                          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                          placeholder="Deskripsikan sikap sosial siswa..." required>{{ $sikap ? $sikap->sikap_sosial : '' }}</textarea>
            </x-card>
        </div>

        <div class="mt-6 flex justify-end">
            <button type="submit" class="px-8 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-bold shadow-lg">
                Simpan Penilaian
            </button>
        </div>
    </form>
</div>
@endsection
