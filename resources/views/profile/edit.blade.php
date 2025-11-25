@extends('layouts.app')
@section('title', 'Edit Profil')
@section('page-title', 'Edit Profil')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <x-card>
        <header>
            <h2 class="text-lg font-medium text-gray-900">
                Informasi Profil
            </h2>
            <p class="mt-1 text-sm text-gray-600">
                Perbarui informasi profil dan alamat email akun Anda.
            </p>
        </header>

        <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6">
            @csrf
            @method('patch')

            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap</label>
                <input id="name" name="name" type="text"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                       value="{{ old('name', $user->name) }}" required autofocus autocomplete="name">
                @if ($errors->get('name'))
                    <p class="mt-1 text-sm text-red-600">{{ $errors->first('name') }}</p>
                @endif
            </div>

            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                <input id="email" name="email" type="email"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                       value="{{ old('email', $user->email) }}" required autocomplete="username">
                @if ($errors->get('email'))
                    <p class="mt-1 text-sm text-red-600">{{ $errors->first('email') }}</p>
                @endif

                @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                    <div class="mt-2">
                        <p class="text-sm text-gray-800">
                            Alamat email Anda belum terverifikasi.
                            <button form="send-verification"
                                    class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Klik di sini untuk mengirim ulang email verifikasi.
                            </button>
                        </p>
                    </div>
                @endif
            </div>

            <div class="flex items-center gap-4">
                <button type="submit"
                        class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                    Simpan
                </button>

                @if (session('status') === 'profile-updated')
                    <p x-data="{ show: true }"
                       x-show="show"
                       x-transition
                       x-init="setTimeout(() => show = false, 2000)"
                       class="text-sm text-gray-600">
                       Tersimpan.
                    </p>
                @endif
            </div>
        </form>
    </x-card>

    <x-card>
        <header>
            <h2 class="text-lg font-medium text-gray-900">
                Ubah Password
            </h2>
            <p class="mt-1 text-sm text-gray-600">
                Pastikan akun Anda menggunakan password yang panjang dan acak agar tetap aman.
            </p>
        </header>

        {{-- Asumsi rute 'password.update' ada di auth.php --}}
        <form method="post" action="{{ route('password.update') }}" class="mt-6 space-y-6">
            @csrf
            @method('put')

            <div>
                <label for="current_password" class="block text-sm font-medium text-gray-700 mb-2">Password Saat Ini</label>
                <input id="current_password" name="current_password" type="password"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                       autocomplete="current-password" required>
                @if ($errors->updatePassword->get('current_password'))
                    <p class="mt-1 text-sm text-red-600">{{ $errors->updatePassword->first('current_password') }}</p>
                @endif
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password Baru</label>
                <input id="password" name="password" type="password"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                       autocomplete="new-password" required>
                @if ($errors->updatePassword->get('password'))
                    <p class="mt-1 text-sm text-red-600">{{ $errors->updatePassword->first('password') }}</p>
                @endif
            </div>

            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">Konfirmasi Password</label>
                <input id="password_confirmation" name="password_confirmation" type="password"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                       autocomplete="new-password" required>
                @if ($errors->updatePassword->get('password_confirmation'))
                    <p class="mt-1 text-sm text-red-600">{{ $errors->updatePassword->first('password_confirmation') }}</p>
                @endif
            </div>

            <div class="flex items-center gap-4">
                <button type="submit"
                        class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                    Simpan
                </button>

                @if (session('status') === 'password-updated')
                    <p x-data="{ show: true }"
                       x-show="show"
                       x-transition
                       x-init="setTimeout(() => show = false, 2000)"
                       class="text-sm text-gray-600">
                       Tersimpan.
                    </p>
                @endif
            </div>
        </form>
    </x-card>

    <x-card>
        <header>
            <h2 class="text-lg font-medium text-gray-900">
                Hapus Akun
            </h2>
            <p class="mt-1 text-sm text-gray-600">
                Setelah akun Anda dihapus, semua data akan dihapus secara permanen.
            </p>
        </header>

        <button type="button"
                @click="$dispatch('open-modal', 'confirm-user-deletion')"
                class="inline-flex items-center px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition mt-6">
            Hapus Akun
        </button>

        <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
            <form method="post" action="{{ route('profile.destroy') }}" class="p-6">
                @csrf
                @method('delete')

                <h2 class="text-lg font-medium text-gray-900">
                    Apakah Anda yakin ingin menghapus akun Anda?
                </h2>

                <p class="mt-1 text-sm text-gray-600">
                    Setelah akun Anda dihapus, semua data akan dihapus permanen. Silakan masukkan password Anda untuk mengonfirmasi bahwa Anda ingin menghapus akun Anda secara permanen.
                </p>

                <div class="mt-6">
                    <label for="password_delete" class="block text-sm font-medium text-gray-700 mb-2 sr-only">Password</label>
                    <input id="password_delete" name="password" type="password"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent"
                           placeholder="Password" required>
                    @if ($errors->userDeletion->get('password'))
                        <p class="mt-1 text-sm text-red-600">{{ $errors->userDeletion->first('password') }}</p>
                    @endif
                </div>

                <div class="mt-6 flex justify-end">
                    <button type="button"
                            @click="$dispatch('close-modal', 'confirm-user-deletion')"
                            class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
                        Batal
                    </button>

                    <button type="submit"
                            class="ml-3 inline-flex items-center px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition">
                        Hapus Akun
                    </button>
                </div>
            </form>
        </x-modal>
    </x-card>

</div>
@endsection
