@extends('layouts.app')
@section('title', 'Template Sikap')
@section('page-title', 'Bank Kalimat Deskripsi Sikap')

@section('content')
<div class="space-y-6">
    <div class="bg-blue-50 border-l-4 border-blue-500 p-4">
        <p class="text-blue-700">
            <strong>Tips:</strong> Anda dapat menyalin kalimat di bawah ini untuk mengisi penilaian sikap siswa. Sesuaikan dengan kondisi siswa yang sebenarnya.
        </p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <x-card title="Template Sikap Spiritual">
            <div class="space-y-6">
                @foreach($templateSpiritual as $kategori => $list)
                <div>
                    <h4 class="font-bold text-gray-800 capitalize mb-2 border-b pb-1">{{ str_replace('_', ' ', $kategori) }}</h4>
                    <ul class="list-disc pl-5 space-y-1">
                        @foreach($list as $item)
                        <li class="text-sm text-gray-600 select-all cursor-copy hover:text-blue-600" title="Klik untuk blok text">
                            {{ $item }}
                        </li>
                        @endforeach
                    </ul>
                </div>
                @endforeach
            </div>
        </x-card>

        <x-card title="Template Sikap Sosial">
            <div class="space-y-6">
                @foreach($templateSosial as $kategori => $list)
                <div>
                    <h4 class="font-bold text-gray-800 capitalize mb-2 border-b pb-1">{{ str_replace('_', ' ', $kategori) }}</h4>
                    <ul class="list-disc pl-5 space-y-1">
                        @foreach($list as $item)
                        <li class="text-sm text-gray-600 select-all cursor-copy hover:text-green-600" title="Klik untuk blok text">
                            {{ $item }}
                        </li>
                        @endforeach
                    </ul>
                </div>
                @endforeach
            </div>
        </x-card>
    </div>
</div>
@endsection
