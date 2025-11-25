@extends('layouts.app')
@section('title', 'Input Nilai Massal')
@section('page-title', 'Input Nilai Massal')

@section('content')
<div class="space-y-6">
    <x-card>
        <form method="GET" action="{{ route('guru.nilai.bulk-input') }}" class="flex flex-col md:flex-row items-end gap-4">
            <div class="w-full md:w-1/2">
                <label for="mata_pelajaran_id" class="block text-sm font-medium text-gray-700 mb-2">Pilih Mata Pelajaran</label>
                <select name="mata_pelajaran_id" id="mata_pelajaran_id" onchange="this.form.submit()"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">-- Pilih Mata Pelajaran --</option>
                    @foreach($mataPelajaranList as $mapel)
                    <option value="{{ $mapel->id }}" {{ request('mata_pelajaran_id') == $mapel->id ? 'selected' : '' }}>
                        {{ $mapel->nama_mapel }} (KKM: {{ $mapel->kkm }})
                    </option>
                    @endforeach
                </select>
            </div>
            <div class="hidden md:block text-sm text-gray-500 pb-2">
                Pilih mata pelajaran untuk mulai menginput nilai satu kelas.
            </div>
        </form>
    </x-card>

    @if($mataPelajaran)
    <form action="{{ route('guru.nilai.bulk-store') }}" method="POST">
        @csrf
        <input type="hidden" name="mata_pelajaran_id" value="{{ $mataPelajaran->id }}">

        <x-card title="Input Nilai: {{ $mataPelajaran->nama_mapel }}">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/3">Nama Siswa</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Nilai Pengetahuan (0-100)</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Nilai Keterampilan (0-100)</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($siswa as $index => $s)
                        @php
                            $nilai = $nilaiTersimpan[$s->id] ?? null;
                        @endphp
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <input type="hidden" name="nilai[{{ $index }}][siswa_id]" value="{{ $s->id }}">
                                <div class="text-sm font-medium text-gray-900">{{ $s->nama_lengkap }}</div>
                                <div class="text-xs text-gray-500">{{ $s->nis }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <input type="number" name="nilai[{{ $index }}][nilai_pengetahuan]"
                                       value="{{ $nilai ? $nilai->nilai_pengetahuan : '' }}"
                                       min="0" max="100" required
                                       class="w-24 text-center px-2 py-1 border border-gray-300 rounded focus:ring-blue-500 focus:border-blue-500">
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <input type="number" name="nilai[{{ $index }}][nilai_keterampilan]"
                                       value="{{ $nilai ? $nilai->nilai_keterampilan : '' }}"
                                       min="0" max="100" required
                                       class="w-24 text-center px-2 py-1 border border-gray-300 rounded focus:ring-blue-500 focus:border-blue-500">
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-6 flex justify-end border-t pt-4">
                <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition shadow-lg">
                    Simpan Semua Nilai
                </button>
            </div>
        </x-card>
    </form>
    @endif
</div>
@endsection
