@extends('layouts.app')
@section('title', 'Data Siswa')
@section('page-title', 'Data Siswa Kelas ' . $kelas->nama_kelas)

@section('content')
<div class="space-y-6">
    <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200 flex justify-between items-center">
        <div>
            <h2 class="text-lg font-bold text-gray-900">Daftar Siswa</h2>
            <p class="text-sm text-gray-500">Total: {{ $siswa->total() }} Siswa Aktif</p>
        </div>
        <div class="text-sm text-blue-600 bg-blue-50 px-3 py-1 rounded-full">
            Semester 1 • {{ $kelas->tahun_ajaran }}
        </div>
    </div>

    <x-card>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Foto</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">NIS / Nama</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">L/P</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">TTL</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Alamat</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($siswa as $s)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <img class="h-10 w-10 rounded-full object-cover border border-gray-200" src="{{ $s->foto_url }}" alt="">
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $s->nama_lengkap }}</div>
                            <div class="text-xs text-gray-500">{{ $s->nis }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <x-badge :type="$s->jenis_kelamin === 'L' ? 'primary' : 'danger'">
                                {{ $s->jenis_kelamin }}
                            </x-badge>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500">
                            {{ $s->tempat_lahir }}, {{ $s->tanggal_lahir ? $s->tanggal_lahir->format('d-m-Y') : '-' }}
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500 truncate max-w-xs">
                            {{ $s->alamat }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <a href="{{ route('guru.siswa.show', $s) }}" class="text-blue-600 hover:text-blue-900 bg-blue-50 px-3 py-1 rounded-md hover:bg-blue-100 transition">
                                Detail
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                            Belum ada siswa di kelas ini.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($siswa->hasPages())
        <div class="px-6 py-4 border-t">
            {{ $siswa->links() }}
        </div>
        @endif
    </x-card>
</div>
@endsection
