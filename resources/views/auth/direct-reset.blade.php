<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ganti Password - e-Rapor</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Outfit', sans-serif; }
        .animated-bg {
            background: linear-gradient(-45deg, #ee7752, #e73c7e, #23a6d5, #23d5ab);
            background-size: 400% 400%;
            animation: gradient 15s ease infinite;
        }
        @keyframes gradient {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
        .glass-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.5);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body class="animated-bg min-h-screen flex items-center justify-center p-6">

    <div class="glass-card w-full max-w-lg rounded-2xl p-10 relative shadow-2xl">
        <a href="{{ route('login') }}" class="absolute top-6 left-6 text-gray-400 hover:text-gray-600 transition flex items-center gap-1 font-medium text-sm">
            <span>←</span> Kembali
        </a>

        <div class="text-center mb-8 mt-4">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-red-50 text-red-500 mb-4 shadow-sm border-4 border-white">
                <span class="text-3xl">🔐</span>
            </div>
            <h1 class="text-2xl font-bold text-gray-800">Reset Password Kilat</h1>
            <p class="text-gray-500 text-base mt-2">Masukkan email dan password baru Anda.</p>
        </div>

        @if (session('error'))
            <div class="mb-6 p-4 bg-red-50 text-red-700 rounded-xl text-sm text-center font-medium border border-red-100">
                {{ session('error') }}
            </div>
        @endif

        <form method="POST" action="{{ route('password.direct.store') }}" class="space-y-6">
            @csrf

            <div class="space-y-2">
                <label class="block text-sm font-bold text-gray-700 uppercase tracking-wider pl-1">Email Akun</label>
                <input type="email" name="email" required
                    class="w-full px-5 py-3.5 rounded-xl bg-gray-50 border border-gray-200 focus:ring-4 focus:ring-red-500/20 focus:border-red-500 transition"
                    placeholder="contoh: admin@sdnslumbung1.sch.id">
                @error('email') <p class="text-red-500 text-sm pl-1">{{ $message }}</p> @enderror
            </div>

            <div x-data="{ show: false }" class="space-y-2">
                <label class="block text-sm font-bold text-gray-700 uppercase tracking-wider pl-1">Password Baru</label>
                <div class="relative">
                    <input :type="show ? 'text' : 'password'" name="password" required
                        class="w-full px-5 py-3.5 rounded-xl bg-gray-50 border border-gray-200 focus:ring-4 focus:ring-red-500/20 focus:border-red-500 transition"
                        placeholder="Minimal 8 karakter">
                    <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-gray-600 transition">
                        <span x-show="!show" class="text-lg">👁️</span>
                        <span x-show="show" class="text-lg" style="display: none;">🙈</span>
                    </button>
                </div>
                @error('password') <p class="text-red-500 text-sm pl-1">{{ $message }}</p> @enderror
            </div>

            <div class="pt-4">
                <button type="submit" class="w-full py-4 rounded-xl bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-bold text-lg tracking-wide shadow-lg hover:from-blue-700 hover:to-indigo-700 hover:shadow-blue-500/30 transform hover:-translate-y-0.5 transition duration-200">
                    GANTI PASSWORD
                </button>
            </div>
        </form>
    </div>

</body>
</html>
