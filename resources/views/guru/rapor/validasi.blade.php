@extends('layouts.app')
@section('title', 'Validasi Rapor')
@section('page-title', 'Validasi Kelengkapan Data')

@section('content')
<div class="space-y-6">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white rounded-lg p-6 shadow-sm border border-gray-200">
            <p class="text-sm text-gray-500">Total Siswa</p>
            <p class="text-3xl font-bold text-gray-800">{{ $statistik['total_siswa'] }}</p>
        </div>
        <div class="bg-green-50 rounded-lg p-6 shadow-sm border border-green-200">
            <p class="text-sm text-green-700">Siap Cetak</p>
            <p class="text-3xl font-bold text-green-700">{{ $statistik['rapor_lengkap'] }}</p>
        </div>
        <div class="bg-red-50 rounded-lg p-6 shadow-sm border border-red-200">
            <p class="text-sm text-red-700">Belum Lengkap</p>
            <p class="text-3xl font-bold text-red-700">{{ $statistik['rapor_belum_lengkap'] }}</p>
        </div>
    </div>

    <x-card title="Status Kelengkapan Data Siswa">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama Siswa</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Nilai Mapel</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Kehadiran</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Sikap</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Status Akhir</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($analisa as $item)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $item['siswa']->nama_lengkap }}</div>
                            <div class="text-xs text-gray-500">{{ $item['siswa']->nis }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            @if($item['nilai_lengkap'])
                                <span class="text-green-500">✅ Lengkap</span>
                            @else
                                <span class="text-red-500">❌ Belum</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            @if($item['kehadiran_lengkap'])
                                <span class="text-green-500">✅ Lengkap</span>
                            @else
                                <span class="text-red-500">❌ Belum</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            @if($item['sikap_lengkap'])
                                <span class="text-green-500">✅ Lengkap</span>
                            @else
                                <span class="text-red-500">❌ Belum</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            @if($item['rapor_lengkap'])
                                <x-badge type="success">SIAP CETAK</x-badge>
                            @else
                                <x-badge type="danger">BELUM SIAP</x-badge>
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
