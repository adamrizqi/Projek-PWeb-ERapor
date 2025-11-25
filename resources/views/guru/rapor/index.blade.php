@extends('layouts.app')
@section('title', 'Cetak Rapor')
@section('page-title', 'Cetak Rapor Siswa')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <div class="bg-blue-50 p-4 rounded-lg border border-blue-200">
            <p class="text-blue-800 text-sm">
                <strong>Info:</strong> Pastikan data nilai, kehadiran, dan sikap sudah lengkap sebelum mencetak rapor.
                Gunakan menu <a href="{{ route('guru.rapor.validasi') }}" class="underline font-bold">Validasi</a> untuk mengecek.
            </p>
        </div>
        <a href="{{ route('guru.rapor.download-all') }}" class="hidden md:inline-flex items-center px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition shadow">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
            </svg>
            Download Semua (.zip)
        </a>
    </div>

    <x-card>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama Siswa</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">NIS</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Kelengkapan</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($siswa as $s)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            {{ $s->nama_lengkap }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500">
                            {{ $s->nis }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            @if($s->rapor_lengkap)
                                <x-badge type="success">Lengkap</x-badge>
                            @else
                                <x-badge type="warning">{{ $s->kelengkapan_rapor }}%</x-badge>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                            @if($s->rapor_lengkap)
                                <a href="{{ route('guru.rapor.show', $s) }}" class="text-blue-600 hover:text-blue-900 mr-3">Preview</a>
                                <a href="{{ route('guru.rapor.download', $s) }}" class="text-green-600 hover:text-green-900 font-bold">Download PDF</a>
                            @else
                                <span class="text-gray-400 cursor-not-allowed" title="Lengkapi data terlebih dahulu">Belum Lengkap</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </x-card>
</div>
@endsection
