@extends('layouts.app')
@section('title', 'Detail Nilai')
@section('page-title', 'Detail Nilai Siswa')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">{{ $siswa->nama_lengkap }}</h2>
            <p class="text-sm text-gray-600">{{ $siswa->nis }}</p>
        </div>
        <a href="{{ route('guru.nilai.input', $siswa) }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
            + Tambah Nilai
        </a>
    </div>

    <x-card>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Mata Pelajaran</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">P</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">K</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Akhir</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Predikat</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($nilai as $n)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $n->mataPelajaran->nama_mapel }}</div>
                            <div class="text-xs text-gray-500">KKM: {{ $n->mataPelajaran->kkm }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm">{{ $n->nilai_pengetahuan }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm">{{ $n->nilai_keterampilan }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-center font-bold text-sm">{{ $n->nilai_akhir }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <x-badge type="primary">{{ $n->predikat }}</x-badge>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm">
                            <div class="flex justify-center space-x-2">
                                <a href="{{ route('guru.nilai.edit', $n) }}" class="text-yellow-600 hover:text-yellow-900">Edit</a>
                                <button onclick="if(confirm('Hapus nilai ini?')) document.getElementById('delete-nilai-{{ $n->id }}').submit()"
                                        class="text-red-600 hover:text-red-900">Hapus</button>
                            </div>
                            <form id="delete-nilai-{{ $n->id }}" action="{{ route('guru.nilai.destroy', $n) }}" method="POST" class="hidden">
                                @csrf @method('DELETE')
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-8 text-center text-gray-500">Belum ada nilai yang diinput.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-card>
</div>
@endsection
