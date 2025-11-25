@extends('layouts.app')
@section('title', 'Absensi Harian')
@section('page-title', 'Input Absensi Harian')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <x-card>
        <form action="{{ route('guru.kehadiran.harian') }}" method="GET" class="flex flex-col md:flex-row items-end gap-4">
            <div class="flex-1 w-full">
                <label for="tanggal" class="block text-sm font-medium text-gray-700 mb-2">Tanggal Absensi</label>
                <input type="date" name="tanggal" id="tanggal" value="{{ $tanggal }}"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                       onchange="this.form.submit()">
            </div>
            <div class="text-sm text-gray-500 pb-2">
                Pilih tanggal untuk melakukan absensi.
            </div>
        </form>
    </x-card>

    <form action="{{ route('guru.kehadiran.harian.store') }}" method="POST">
        @csrf
        <input type="hidden" name="tanggal" value="{{ $tanggal }}">

        <x-card title="Daftar Siswa - {{ \Carbon\Carbon::parse($tanggal)->isoFormat('D MMMM Y') }}">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/3">Nama Siswa</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Status Kehadiran</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($siswa as $index => $s)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <input type="hidden" name="kehadiran[{{ $index }}][siswa_id]" value="{{ $s->id }}">
                                <div class="text-sm font-medium text-gray-900">{{ $s->nama_lengkap }}</div>
                                <div class="text-xs text-gray-500">{{ $s->nis }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex justify-center space-x-4">
                                    <label class="inline-flex items-center">
                                        <input type="radio" name="kehadiran[{{ $index }}][status]" value="hadir" checked class="text-green-600 focus:ring-green-500">
                                        <span class="ml-2 text-sm text-gray-700">Hadir</span>
                                    </label>
                                    <label class="inline-flex items-center">
                                        <input type="radio" name="kehadiran[{{ $index }}][status]" value="sakit" class="text-yellow-600 focus:ring-yellow-500">
                                        <span class="ml-2 text-sm text-gray-700">Sakit</span>
                                    </label>
                                    <label class="inline-flex items-center">
                                        <input type="radio" name="kehadiran[{{ $index }}][status]" value="izin" class="text-blue-600 focus:ring-blue-500">
                                        <span class="ml-2 text-sm text-gray-700">Izin</span>
                                    </label>
                                    <label class="inline-flex items-center">
                                        <input type="radio" name="kehadiran[{{ $index }}][status]" value="alpa" class="text-red-600 focus:ring-red-500">
                                        <span class="ml-2 text-sm text-gray-700">Alpa</span>
                                    </label>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-6 flex justify-end border-t pt-4">
                <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                    Simpan Absensi
                </button>
            </div>
        </x-card>
    </form>
</div>
@endsection
