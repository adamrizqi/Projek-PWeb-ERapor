@extends('layouts.app')

@section('title', 'Detail Siswa')
@section('page-title', 'Detail Siswa')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div class="flex items-center space-x-4">
            <a href="{{ route('admin.siswa.index') }}" class="text-gray-600 hover:text-gray-900">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </a>
            <div>
                <h2 class="text-2xl font-bold text-gray-900">{{ $siswa->nama_lengkap }}</h2>
                <p class="text-sm text-gray-600">{{ $siswa->nis }} • Kelas {{ $siswa->nama_kelas }}</p>
            </div>
        </div>
        <div class="flex items-center space-x-3">
            <a href="{{ route('admin.siswa.edit', $siswa) }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                Edit Data
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-1">
            <x-card>
                <div class="text-center">
                    <img src="{{ $siswa->foto_url }}"
                         alt="{{ $siswa->nama_lengkap }}"
                         class="w-48 h-48 rounded-full object-cover mx-auto mb-6 border-4 border-gray-200">

                    <h3 class="text-xl font-bold text-gray-900 mb-1">{{ $siswa->nama_lengkap }}</h3>
                    <p class="text-gray-600 mb-4">{{ $siswa->nis }}</p>

                    <div class="space-y-2">
                        <x-badge :type="$siswa->status_badge" class="text-sm">
                            {{ ucfirst($siswa->status) }}
                        </x-badge>
                        <x-badge :type="$siswa->jenis_kelamin === 'L' ? 'primary' : 'danger'" class="text-sm">
                            {{ $siswa->jenis_kelamin_lengkap }}
                        </x-badge>
                    </div>
                </div>

                <div class="mt-6 pt-6 border-t border-gray-200 space-y-3">
                    <div class="flex items-center text-sm">
                        <svg class="w-5 h-5 text-gray-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <div>
                            <p class="text-gray-500 text-xs">Tempat, Tanggal Lahir</p>
                            <p class="text-gray-900 font-medium">{{ $siswa->tempat_tanggal_lahir }}</p>
                        </div>
                    </div>

                    <div class="flex items-center text-sm">
                        <svg class="w-5 h-5 text-gray-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <div>
                            <p class="text-gray-500 text-xs">Umur</p>
                            <p class="text-gray-900 font-medium">{{ $siswa->umur }} tahun</p>
                        </div>
                    </div>

                    <div class="flex items-center text-sm">
                        <svg class="w-5 h-5 text-gray-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                        <div>
                            <p class="text-gray-500 text-xs">Kelas</p>
                            <p class="text-gray-900 font-medium">{{ $siswa->kelas->nama_kelas }} (Tingkat {{ $siswa->kelas->tingkat }})</p>
                        </div>
                    </div>

                    <div class="flex items-center text-sm">
                        <svg class="w-5 h-5 text-gray-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        <div>
                            <p class="text-gray-500 text-xs">Alamat</p>
                            <p class="text-gray-900 font-medium">{{ $siswa->alamat }}</p>
                        </div>
                    </div>
                </div>
            </x-card>

            <x-card class="mt-6" title="Informasi Wali">
                <div class="space-y-3">
                    <div>
                        <p class="text-xs text-gray-500 mb-1">Nama Wali/Orang Tua</p>
                        <p class="text-sm font-medium text-gray-900">{{ $siswa->nama_wali }}</p>
                    </div>
                    @if($siswa->phone_wali)
                    <div>
                        <p class="text-xs text-gray-500 mb-1">No. HP/WhatsApp</p>
                        <a href="https://wa.me/{{ $siswa->phone_wali }}"
                           target="_blank"
                           class="text-sm font-medium text-green-600 hover:text-green-700 flex items-center">
                            {{ $siswa->phone_wali }}
                            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                            </svg>
                        </a>
                    </div>
                    @endif
                </div>
            </x-card>
        </div>

        <div class="lg:col-span-2 space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg shadow-lg p-6 text-white">
                    <p class="text-blue-100 text-sm mb-2">Rata-rata Nilai</p>
                    <p class="text-4xl font-bold">{{ number_format($statistik['rata_rata_nilai'], 1) }}</p>
                </div>

                <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-lg shadow-lg p-6 text-white">
                    <p class="text-green-100 text-sm mb-2">Ranking Kelas</p>
                    <p class="text-4xl font-bold">{{ $statistik['ranking'] ?? '-' }}</p>
                </div>

                <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-lg shadow-lg p-6 text-white">
                    <p class="text-purple-100 text-sm mb-2">Kelengkapan Rapor</p>
                    <p class="text-4xl font-bold">{{ $statistik['persentase_rapor'] }}%</p>
                </div>
            </div>

            <x-card title="Nilai Akademik">
                @if($nilai->isNotEmpty())
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead>
                            <tr>
                                <th class="px-4 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase">Mata Pelajaran</th>
                                <th class="px-4 py-3 bg-gray-50 text-center text-xs font-medium text-gray-500 uppercase">Pengetahuan</th>
                                <th class="px-4 py-3 bg-gray-50 text-center text-xs font-medium text-gray-500 uppercase">Keterampilan</th>
                                <th class="px-4 py-3 bg-gray-50 text-center text-xs font-medium text-gray-500 uppercase">Nilai Akhir</th>
                                <th class="px-4 py-3 bg-gray-50 text-center text-xs font-medium text-gray-500 uppercase">Predikat</th>
                                <th class="px-4 py-3 bg-gray-50 text-center text-xs font-medium text-gray-500 uppercase">Status</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($nilai as $n)
                            <tr>
                                <td class="px-4 py-3 text-sm text-gray-900">
                                    {{ $n->mataPelajaran->nama_mapel }}
                                    <span class="text-xs text-gray-500 block">KKM: {{ $n->mataPelajaran->kkm }}</span>
                                </td>
                                <td class="px-4 py-3 text-center text-sm text-gray-900">
                                    {{ $n->nilai_pengetahuan }}
                                </td>
                                <td class="px-4 py-3 text-center text-sm text-gray-900">
                                    {{ $n->nilai_keterampilan }}
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <span class="text-sm font-bold text-gray-900">{{ $n->nilai_akhir }}</span>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <x-badge type="primary">{{ $n->predikat }}</x-badge>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <x-badge :type="$n->status_badge">
                                        {{ $n->status_text }}
                                    </x-badge>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="text-center py-8">
                    <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                    <p class="text-gray-500">Belum ada data nilai</p>
                </div>
                @endif
            </x-card>

            <x-card title="Kehadiran">
                @if($kehadiran)
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div class="bg-green-50 rounded-lg p-4 text-center">
                        <p class="text-3xl font-bold text-green-600">{{ $kehadiran->hadir }}</p>
                        <p class="text-sm text-gray-600 mt-1">Hadir</p>
                    </div>
                    <div class="bg-yellow-50 rounded-lg p-4 text-center">
                        <p class="text-3xl font-bold text-yellow-600">{{ $kehadiran->sakit }}</p>
                        <p class="text-sm text-gray-600 mt-1">Sakit</p>
                    </div>
                    <div class="bg-blue-50 rounded-lg p-4 text-center">
                        <p class="text-3xl font-bold text-blue-600">{{ $kehadiran->izin }}</p>
                        <p class="text-sm text-gray-600 mt-1">Izin</p>
                    </div>
                    <div class="bg-red-50 rounded-lg p-4 text-center">
                        <p class="text-3xl font-bold text-red-600">{{ $kehadiran->alpa }}</p>
                        <p class="text-sm text-gray-600 mt-1">Alpa</p>
                    </div>
                </div>

                <div class="mt-6 pt-6 border-t border-gray-200">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm font-medium text-gray-700">Persentase Kehadiran</span>
                        <span class="text-2xl font-bold text-gray-900">{{ $kehadiran->persentase_kehadiran }}%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-3">
                        <div class="bg-green-600 h-3 rounded-full transition-all duration-500"
                             style="width: {{ $kehadiran->persentase_kehadiran }}%"></div>
                    </div>
                    <p class="text-sm text-gray-600 mt-2">
                        Status: <span class="font-semibold">{{ $kehadiran->status_kehadiran }}</span>
                    </p>
                </div>
                @else
                <div class="text-center py-8">
                    <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                    </svg>
                    <p class="text-gray-500">Belum ada data kehadiran</p>
                </div>
                @endif
            </x-card>

            <x-card title="Penilaian Sikap & Karakter">
                @if($sikap)
                <div class="space-y-4">
                    <div>
                        <h4 class="text-sm font-semibold text-gray-900 mb-2 flex items-center">
                            <svg class="w-5 h-5 text-purple-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                            </svg>
                            Sikap Spiritual
                        </h4>
                        <p class="text-sm text-gray-700 leading-relaxed bg-gray-50 p-4 rounded-lg">
                            {{ $sikap->sikap_spiritual }}
                        </p>
                    </div>

                    <div>
                        <h4 class="text-sm font-semibold text-gray-900 mb-2 flex items-center">
                            <svg class="w-5 h-5 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                            Sikap Sosial
                        </h4>
                        <p class="text-sm text-gray-700 leading-relaxed bg-gray-50 p-4 rounded-lg">
                            {{ $sikap->sikap_sosial }}
                        </p>
                    </div>
                </div>
                @else
                <div class="text-center py-8">
                    <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                    </svg>
                    <p class="text-gray-500">Belum ada penilaian sikap</p>
                </div>
                @endif
            </x-card>
        </div>
    </div>

    <x-card title="Aksi Cepat">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">

            <a href="{{ route('admin.siswa.rapor.preview', $siswa) }}" target="_blank"
               class="flex flex-col items-center justify-center p-4 bg-blue-50 rounded-lg hover:bg-blue-100 transition group">
                <svg class="w-8 h-8 text-blue-600 mb-2 group-hover:scale-110 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <span class="text-sm font-medium text-gray-700">Cetak Rapor</span>
            </a>

            <button type="button"
                    @click="$dispatch('open-modal', 'pindah-kelas')"
                    class="flex flex-col items-center justify-center p-4 bg-green-50 rounded-lg hover:bg-green-100 transition group">
                <svg class="w-8 h-8 text-green-600 mb-2 group-hover:scale-110 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                </svg>
                <span class="text-sm font-medium text-gray-700">Pindah Kelas</span>
            </button>

            <button type="button"
                    @click="$dispatch('open-modal', 'update-status')"
                    class="flex flex-col items-center justify-center p-4 bg-yellow-50 rounded-lg hover:bg-yellow-100 transition group">
                <svg class="w-8 h-8 text-yellow-600 mb-2 group-hover:scale-110 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span class="text-sm font-medium text-gray-700">Update Status</span>
            </button>

            <a href="{{ route('admin.siswa.edit', $siswa) }}"
               class="flex flex-col items-center justify-center p-4 bg-purple-50 rounded-lg hover:bg-purple-100 transition group">
                <svg class="w-8 h-8 text-purple-600 mb-2 group-hover:scale-110 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
                <span class="text-sm font-medium text-gray-700">Edit Data</span>
            </a>
        </div>
    </x-card>
</div>

<x-modal name="pindah-kelas" title="Pindah Kelas" max-width="md">
    <form action="{{ route('admin.siswa.pindah-kelas', $siswa) }}" method="POST">
        @csrf

        <div class="space-y-4">
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                <p class="text-sm text-blue-800">
                    <strong>Siswa:</strong> {{ $siswa->nama_lengkap }}<br>
                    <strong>Kelas Saat Ini:</strong> {{ $siswa->nama_kelas }}
                </p>
            </div>

            <div>
                <label for="kelas_baru_id" class="block text-sm font-medium text-gray-700 mb-2">
                    Kelas Baru <span class="text-red-500">*</span>
                </label>
                <select name="kelas_baru_id"
                        id="kelas_baru_id"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        required>
                    <option value="">-- Pilih Kelas Baru --</option>
                    @foreach(\App\Models\Kelas::where('id', '!=', $siswa->kelas_id)->orderBy('tingkat')->orderBy('nama_kelas')->get() as $kelas)
                    <option value="{{ $kelas->id }}">{{ $kelas->nama_kelas }} - Tingkat {{ $kelas->tingkat }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="flex items-center justify-end space-x-3 mt-6">
            <button type="button"
                    @click="$dispatch('close-modal', 'pindah-kelas')"
                    class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
                Batal
            </button>
            <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                Pindahkan
            </button>
        </div>
    </form>
</x-modal>

<x-modal name="update-status" title="Update Status Siswa" max-width="md">
    <form action="{{ route('admin.siswa.update-status', $siswa) }}" method="POST">
        @csrf

        <div class="space-y-4">
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                <p class="text-sm text-yellow-800">
                    <strong>Siswa:</strong> {{ $siswa->nama_lengkap }}<br>
                    <strong>Status Saat Ini:</strong> <span class="font-semibold">{{ ucfirst($siswa->status) }}</span>
                </p>
            </div>

            <div>
                <label for="status_baru" class="block text-sm font-medium text-gray-700 mb-2">
                    Status Baru <span class="text-red-500">*</span>
                </label>
                <select name="status"
                        id="status_baru"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        required>
                    <option value="aktif" {{ $siswa->status == 'aktif' ? 'selected' : '' }}>Aktif</option>
                    <option value="lulus" {{ $siswa->status == 'lulus' ? 'selected' : '' }}>Lulus</option>
                    <option value="pindah" {{ $siswa->status == 'pindah' ? 'selected' : '' }}>Pindah</option>
                    <option value="keluar" {{ $siswa->status == 'keluar' ? 'selected' : '' }}>Keluar</option>
                </select>
            </div>
        </div>

        <div class="flex items-center justify-end space-x-3 mt-6">
            <button type="button"
                    @click="$dispatch('close-modal', 'update-status')"
                    class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
                Batal
            </button>
            <button type="submit" class="px-4 py-2 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700 transition">
                Update Status
            </button>
        </div>
    </form>
</x-modal>

@endsection
