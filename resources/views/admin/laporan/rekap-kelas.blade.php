@extends('layouts.app')
@section('title', 'Rekap Kelas')
@section('page-title', 'Rekap Kelengkapan Rapor')

@section('content')
<div class="space-y-6">
    <div>
        <h2 class="text-2xl font-bold text-gray-900">Rekap Kelengkapan Rapor</h2>
        <p class="text-sm text-gray-600 mt-1">Pantau progres kelengkapan data rapor per kelas</p>
    </div>

    <x-card>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead>
                    <tr>
                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kelas</th>
                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Wali Kelas</th>
                        <th class="px-6 py-3 bg-gray-50 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Total Siswa</th>
                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Progress Rapor Lengkap</th>
                        <th class="px-6 py-3 bg-gray-50 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($rekap as $r)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $r['kelas']->nama_kelas }}</div>
                            <div class="text-xs text-gray-500">Tingkat {{ $r['kelas']->tingkat }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $r['kelas']->nama_wali_kelas }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-bold text-gray-900">
                            {{ $r['total_siswa'] }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($r['total_siswa'] > 0)
                            <div>
                                <div class="flex items-center justify-between mb-1">
                                    <span class="text-sm font-medium text-blue-600">{{ $r['persentase_lengkap'] }}%</span>
                                    <span class="text-xs text-gray-500">{{ $r['nilai_lengkap'] }} / {{ $r['total_siswa'] }} siswa</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2.5">
                                    <div class="bg-blue-600 h-2.5 rounded-full" style="width: {{ $r['persentase_lengkap'] }}%"></div>
                                </div>
                            </div>
                            @else
                            <span class="text-xs text-gray-500">Tidak ada siswa</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <a href="{{ route('admin.kelas.show', $r['kelas']) }}" class="text-blue-600 hover:text-blue-900 text-sm">
                                Detail Kelas
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                            Tidak ada data kelas untuk ditampilkan.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-card>
</div>
@endsection
