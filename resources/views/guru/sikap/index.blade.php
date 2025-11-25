@extends('layouts.app')
@section('title', 'Penilaian Sikap')
@section('page-title', 'Penilaian Sikap & Karakter')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-xl font-bold text-gray-900">Kelas {{ $kelas->nama_kelas }}</h2>
            <p class="text-gray-500">Input penilaian sikap spiritual dan sosial</p>
        </div>
        <a href="{{ route('guru.sikap.template') }}" target="_blank" class="text-blue-600 hover:text-blue-800 text-sm font-medium flex items-center">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
            </svg>
            Lihat Template Deskripsi
        </a>
    </div>

    <x-card>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Siswa</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sikap Spiritual</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sikap Sosial</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($siswa as $s)
                    @php $sikap = $s->sikap_data; @endphp
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $s->nama_lengkap }}</div>
                            <div class="text-xs text-gray-500">{{ $s->nis }}</div>
                        </td>
                        <td class="px-6 py-4">
                            @if($sikap && $sikap->sikap_spiritual)
                                <p class="text-xs text-gray-600 line-clamp-2">{{ $sikap->sikap_spiritual }}</p>
                            @else
                                <span class="text-xs text-red-500 italic">Belum diisi</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            @if($sikap && $sikap->sikap_sosial)
                                <p class="text-xs text-gray-600 line-clamp-2">{{ $sikap->sikap_sosial }}</p>
                            @else
                                <span class="text-xs text-red-500 italic">Belum diisi</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <a href="{{ route('guru.sikap.show', $s) }}" class="text-blue-600 hover:text-blue-900 text-sm font-medium">
                                Input / Edit
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
