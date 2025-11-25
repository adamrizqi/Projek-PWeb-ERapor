@extends('layouts.app')
@section('title', 'Input Nilai Siswa')
@section('page-title', 'Input Nilai: ' . $siswa->nama_lengkap)

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center bg-white p-4 rounded-lg shadow-sm border">
        <a href="{{ route('guru.nilai.index') }}" class="text-gray-600 hover:text-gray-900">← Kembali ke Daftar</a>
        <div class="text-center">
            <h3 class="font-bold text-gray-800">{{ $siswa->nama_lengkap }}</h3>
            <p class="text-sm text-gray-500">{{ $siswa->nis }}</p>
        </div>
        <div></div> </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <x-card title="Form Nilai">
            <form action="{{ route('guru.nilai.store', $siswa) }}" method="POST" class="space-y-6">
                @csrf

                <div>
                    <label for="mata_pelajaran_id" class="block text-sm font-medium text-gray-700 mb-2">Mata Pelajaran</label>
                    <select name="mata_pelajaran_id" id="mata_pelajaran_id" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                        <option value="">-- Pilih Mapel --</option>
                        @foreach($mataPelajaran as $mapel)
                            {{-- Disable jika nilai sudah ada --}}
                            <option value="{{ $mapel->id }}"
                                    {{ in_array($mapel->id, $nilaiTersimpan) ? 'disabled' : '' }}
                                    class="{{ in_array($mapel->id, $nilaiTersimpan) ? 'bg-gray-100 text-gray-400' : '' }}">
                                {{ $mapel->nama_mapel }} {{ in_array($mapel->id, $nilaiTersimpan) ? '(Sudah Dinilai)' : '' }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="nilai_pengetahuan" class="block text-sm font-medium text-gray-700 mb-2">Nilai Pengetahuan</label>
                        <input type="number" name="nilai_pengetahuan" id="nilai_pengetahuan" min="0" max="100" required
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label for="nilai_keterampilan" class="block text-sm font-medium text-gray-700 mb-2">Nilai Keterampilan</label>
                        <input type="number" name="nilai_keterampilan" id="nilai_keterampilan" min="0" max="100" required
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>

                <div>
                    <label for="deskripsi" class="block text-sm font-medium text-gray-700 mb-2">
                        Deskripsi / Catatan (Opsional)
                    </label>
                    <textarea name="deskripsi" id="deskripsi" rows="3"
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                              placeholder="Jika dikosongkan, sistem akan membuat deskripsi otomatis."></textarea>
                </div>

                <div class="pt-4">
                    <button type="submit" class="w-full px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-medium">
                        Simpan Nilai
                    </button>
                </div>
            </form>
        </x-card>

        <x-card title="Nilai Tersimpan">
            @if(count($nilaiTersimpan) > 0)
                <div class="space-y-3">
                    <div class="p-3 bg-green-50 text-green-800 rounded-lg text-sm">
                        <strong>{{ count($nilaiTersimpan) }}</strong> mata pelajaran sudah dinilai.
                    </div>
                    <a href="{{ route('guru.nilai.show', $siswa) }}" class="block text-center text-blue-600 hover:underline text-sm mt-4">
                        Lihat Detail Semua Nilai →
                    </a>
                </div>
            @else
                <p class="text-gray-500 text-center py-4">Belum ada nilai yang diinput untuk siswa ini.</p>
            @endif
        </x-card>
    </div>
</div>
@endsection
