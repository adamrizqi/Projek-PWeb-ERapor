@extends('layouts.app')
@section('title', 'Preview Rapor')
@section('page-title', 'Preview Rapor: ' . $siswa->nama_lengkap)

@section('content')
<div class="max-w-5xl mx-auto space-y-6">
    <div class="flex justify-between no-print">
        <a href="{{ route('guru.rapor.index') }}" class="bg-gray-200 text-gray-700 px-4 py-2 rounded hover:bg-gray-300 transition">
            ← Kembali
        </a>
        <a href="{{ route('guru.rapor.download', $siswa) }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition flex items-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
            </svg>
            Download PDF
        </a>
    </div>

    <div class="bg-white shadow-lg p-10 mx-auto border border-gray-200" style="min-height: 29.7cm; width: 21cm;">

        <div class="text-center border-b-2 border-gray-800 pb-4 mb-6">
            <h1 class="text-xl font-bold uppercase">Pemerintah Kabupaten Jember</h1>
            <h2 class="text-2xl font-bold uppercase">Dinas Pendidikan</h2>
            <h3 class="text-3xl font-bold uppercase">SD Negeri Slumbung 1</h3>
            <p class="text-sm text-gray-600">Jl. Pendidikan No. 1, Slumbung, Kec. Gandusari, Kab. Jember</p>
        </div>

        <div class="text-center mb-6">
            <h4 class="text-lg font-bold uppercase underline">Laporan Hasil Belajar Siswa</h4>
        </div>

        <table class="w-full mb-6 text-sm">
            <tr>
                <td class="w-1/6 py-1">Nama Peserta Didik</td>
                <td class="w-1/2 font-bold">: {{ strtoupper($siswa->nama_lengkap) }}</td>
                <td class="w-1/6">Kelas</td>
                <td class="w-1/6">: {{ $kelas->nama_kelas }}</td>
            </tr>
            <tr>
                <td class="py-1">NIS / NISN</td>
                <td>: {{ $siswa->nis }} / {{ $siswa->nisn ?? '-' }}</td>
                <td>Semester</td>
                <td>: {{ $semester }} (Satu)</td>
            </tr>
            <tr>
                <td class="py-1">Sekolah</td>
                <td>: SDN Slumbung 1</td>
                <td>Tahun Ajaran</td>
                <td>: {{ $tahun_ajaran }}</td>
            </tr>
        </table>

        <div class="mb-6">
            <h5 class="font-bold mb-2">A. KOMPETENSI SIKAP</h5>
            <table class="w-full border-collapse border border-gray-800 text-sm">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="border border-gray-800 p-2 w-1/4">Aspek</th>
                        <th class="border border-gray-800 p-2">Deskripsi</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="border border-gray-800 p-2 font-semibold text-center">Sikap Spiritual</td>
                        <td class="border border-gray-800 p-2 text-justify">{{ $sikap->sikap_spiritual }}</td>
                    </tr>
                    <tr>
                        <td class="border border-gray-800 p-2 font-semibold text-center">Sikap Sosial</td>
                        <td class="border border-gray-800 p-2 text-justify">{{ $sikap->sikap_sosial }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="mb-6">
            <h5 class="font-bold mb-2">B. KOMPETENSI PENGETAHUAN DAN KETERAMPILAN</h5>
            <table class="w-full border-collapse border border-gray-800 text-sm">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="border border-gray-800 p-2 w-10">No</th>
                        <th class="border border-gray-800 p-2">Mata Pelajaran</th>
                        <th class="border border-gray-800 p-2 w-16">KKM</th>
                        <th class="border border-gray-800 p-2 w-16">Nilai</th>
                        <th class="border border-gray-800 p-2 w-16">Predikat</th>
                        <th class="border border-gray-800 p-2">Deskripsi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($nilai as $index => $n)
                    <tr>
                        <td class="border border-gray-800 p-2 text-center">{{ $index + 1 }}</td>
                        <td class="border border-gray-800 p-2">{{ $n->mataPelajaran->nama_mapel }}</td>
                        <td class="border border-gray-800 p-2 text-center">{{ $n->mataPelajaran->kkm }}</td>
                        <td class="border border-gray-800 p-2 text-center font-bold">{{ $n->nilai_akhir }}</td>
                        <td class="border border-gray-800 p-2 text-center">{{ $n->predikat }}</td>
                        <td class="border border-gray-800 p-2 text-xs text-justify">{{ $n->deskripsi }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mb-8 w-1/2">
            <h5 class="font-bold mb-2">C. KETIDAKHADIRAN</h5>
            <table class="w-full border-collapse border border-gray-800 text-sm">
                <tr>
                    <td class="border border-gray-800 p-2">Sakit</td>
                    <td class="border border-gray-800 p-2 text-center w-20">{{ $kehadiran->sakit }} hari</td>
                </tr>
                <tr>
                    <td class="border border-gray-800 p-2">Izin</td>
                    <td class="border border-gray-800 p-2 text-center">{{ $kehadiran->izin }} hari</td>
                </tr>
                <tr>
                    <td class="border border-gray-800 p-2">Tanpa Keterangan</td>
                    <td class="border border-gray-800 p-2 text-center">{{ $kehadiran->alpa }} hari</td>
                </tr>
            </table>
        </div>

        <div class="flex justify-between mt-12 text-sm">
            <div class="text-center w-1/3">
                <p>Mengetahui,<br>Orang Tua/Wali,</p>
                <div class="h-20"></div>
                <p class="font-bold border-b border-gray-800 inline-block min-w-[150px]"></p>
            </div>
            <div class="text-center w-1/3">
                <p>Jember, {{ $tanggal_cetak }}<br>Wali Kelas,</p>
                <div class="h-20"></div>
                <p class="font-bold underline">{{ $wali_kelas->name }}</p>
                <p>NIP. {{ $wali_kelas->nip }}</p>
            </div>
        </div>

        <div class="text-center mt-8 text-sm">
            <p>Mengetahui,<br>Kepala Sekolah</p>
            <div class="h-20"></div>
            <p class="font-bold underline">{{ $kepala_sekolah['nama'] }}</p>
            <p>NIP. {{ $kepala_sekolah['nip'] }}</p>
        </div>
    </div>
</div>
@endsection
