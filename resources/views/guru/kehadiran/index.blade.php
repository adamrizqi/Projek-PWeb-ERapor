@extends('layouts.app')
@section('title', 'Kehadiran Siswa')
@section('page-title', 'Manajemen Kehadiran')

@section('content')
<div class="space-y-6">
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 flex flex-col md:flex-row justify-between items-center">
        <div>
            <h2 class="text-xl font-bold text-gray-900">Kelas {{ $kelas->nama_kelas }}</h2>
            <p class="text-gray-500">Rekap Kehadiran Semester Ini</p>
        </div>
        <div class="mt-4 md:mt-0">
            <a href="{{ route('guru.kehadiran.harian') }}" class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                </svg>
                Absen Harian (Hari Ini)
            </a>
        </div>
    </div>

    <x-card>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Siswa</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Hadir</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Sakit</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Izin</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Alpa</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Persentase</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($siswa as $s)
                    @php
                        $k = $s->kehadiran_data;
                    @endphp
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $s->nama_lengkap }}</div>
                            <div class="text-xs text-gray-500">{{ $s->nis }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center text-gray-900">{{ $k ? $k->hadir : 0 }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-center text-yellow-600">{{ $k ? $k->sakit : 0 }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-center text-blue-600">{{ $k ? $k->izin : 0 }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-center text-red-600">{{ $k ? $k->alpa : 0 }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            @if($k)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $k->persentase_kehadiran >= 90 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $k->persentase_kehadiran }}%
                                </span>
                            @else
                                <span class="text-gray-400">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <a href="{{ route('guru.kehadiran.show', $s) }}" class="text-blue-600 hover:text-blue-900 text-sm font-medium">
                                Edit Rekap
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </x-card>
</div>
@endsection
