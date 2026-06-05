<!DOCTYPE html>
<html lang="uz" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kirish — Avtomaktab</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        html, body { height: 100%; }
        .brand-panel {
            background: linear-gradient(145deg, #040810 0%, #060d1a 40%, #071020 70%, #04090f 100%);
        }
        .road-line {
            animation: roadAnim 3s linear infinite;
        }
        @keyframes roadAnim {
            0%   { transform: translateY(0); }
            100% { transform: translateY(40px); }
        }
        .float-card {
            animation: floatUp 6s ease-in-out infinite;
        }
        @keyframes floatUp {
            0%, 100% { transform: translateY(0px); }
            50%       { transform: translateY(-10px); }
        }
    </style>
</head>
<body class="h-full font-sans antialiased">
<div class="flex h-full min-h-screen">

    {{-- ══════════════  LEFT BRAND PANEL  ══════════════ --}}
    <div class="hidden lg:flex lg:w-[48%] xl:w-[52%] brand-panel flex-col justify-between p-10 relative overflow-hidden">

        {{-- Background decoration ─────────────────── --}}
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            {{-- Grid dots --}}
            <svg class="absolute inset-0 w-full h-full opacity-[0.04]" xmlns="http://www.w3.org/2000/svg">
                <defs>
                    <pattern id="dots" x="0" y="0" width="28" height="28" patternUnits="userSpaceOnUse">
                        <circle cx="2" cy="2" r="1.5" fill="white"/>
                    </pattern>
                </defs>
                <rect width="100%" height="100%" fill="url(#dots)"/>
            </svg>

            {{-- Glow circles --}}
            <div class="absolute -top-40 -left-40 w-96 h-96 rounded-full bg-emerald-500/10 blur-3xl"></div>
            <div class="absolute bottom-0 right-0 w-80 h-80 rounded-full bg-indigo-500/08 blur-3xl"></div>
            <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-64 h-64 rounded-full bg-emerald-400/06 blur-3xl"></div>
        </div>

        {{-- Logo ──────────────────────────────────── --}}
        <div class="relative z-10 flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-emerald-400 to-emerald-600 flex items-center justify-center shadow-lg shadow-emerald-500/30">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10l-1 1h14l-1-1z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M13 6l2 3h5l-1 7H13"/>
                </svg>
            </div>
            <div>
                <p class="text-white font-bold text-base tracking-tight leading-none">Avtomaktab</p>
                <p class="text-white/35 text-xs mt-0.5">Boshqaruv tizimi</p>
            </div>
        </div>

        {{-- Center content ─────────────────────────── --}}
        <div class="relative z-10 flex-1 flex flex-col items-start justify-center py-16">

            {{-- Main heading --}}
            <div class="mb-10">
                <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full
                    bg-emerald-500/10 border border-emerald-500/20 mb-6">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse-dot"></span>
                    <span class="text-emerald-400 text-xs font-semibold tracking-wide">O'zbekiston № 1 tizim</span>
                </div>

                <h1 class="text-5xl font-extrabold text-white leading-[1.1] tracking-tight mb-4">
                    Zamonaviy<br>
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-emerald-400 to-teal-300">
                        avtomaktab
                    </span><br>
                    boshqaruvi
                </h1>
                <p class="text-white/40 text-base leading-relaxed max-w-sm">
                    O'quvchilar, instruktorlar va to'lovlarni yagona platformada boshqaring.
                </p>
            </div>

            {{-- Feature cards --}}
            <div class="space-y-3 w-full max-w-sm">
                @foreach([
                    ['icon' => 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z', 'title' => "O'quvchilar ro'yxati", 'desc' => 'Barcha o\'quvchilarni bir joyda kuzating'],
                    ['icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4', 'title' => 'Imtihon tizimi', 'desc' => 'Onlayn test va natijalarni boshqaring'],
                    ['icon' => 'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z', 'title' => "To'lov nazorati", 'desc' => 'Moliyaviy hisobotlar va qarzdorlik'],
                ] as $i => $f)
                <div class="flex items-center gap-3.5 p-3.5 rounded-xl
                    bg-white/[0.04] border border-white/[0.07]
                    hover:bg-white/[0.07] transition-colors duration-200"
                    style="animation-delay: {{ $i * 0.1 }}s">
                    <div class="w-8 h-8 rounded-lg bg-emerald-500/15 flex items-center justify-center flex-shrink-0">
                        <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="{{ $f['icon'] }}"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-white/85 text-sm font-medium leading-none">{{ $f['title'] }}</p>
                        <p class="text-white/35 text-xs mt-0.5">{{ $f['desc'] }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        {{-- Bottom stats ────────────────────────────── --}}
        <div class="relative z-10 flex items-center gap-6 pt-6 border-t border-white/[0.06]">
            @foreach([['500+', "O'quvchi"], ['50+', 'Instruktor'], ['98%', 'Muvaffaqiyat']] as $s)
            <div>
                <p class="text-xl font-bold text-white">{{ $s[0] }}</p>
                <p class="text-xs text-white/35">{{ $s[1] }}</p>
            </div>
            @endforeach
        </div>
    </div>

    {{-- ══════════════  RIGHT FORM PANEL  ══════════════ --}}
    <div class="flex-1 flex flex-col justify-center items-center
        bg-white dark:bg-[#0a0e1a]
        px-6 py-10 sm:px-10">

        <div class="w-full max-w-[380px]">

            {{-- Mobile logo (only on small screens) --}}
            <div class="lg:hidden flex items-center gap-3 justify-center mb-8">
                <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-emerald-400 to-emerald-600 flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10l-1 1h14l-1-1z"/>
                    </svg>
                </div>
                <span class="text-xl font-bold text-slate-900 dark:text-white">Avtomaktab</span>
            </div>

            {{-- Heading ──────────────────────────────── --}}
            <div class="mb-8">
                <h2 class="text-2xl font-bold text-slate-900 dark:text-white tracking-tight">Xush kelibsiz</h2>
                <p class="text-slate-500 dark:text-slate-400 text-sm mt-1">
                    Hisobingizga kiring, davom eting
                </p>
            </div>

            {{-- Status message --}}
            @if (session('status'))
            <div class="mb-5 flex items-center gap-2.5 p-3 bg-emerald-50 dark:bg-emerald-500/10
                border border-emerald-200 dark:border-emerald-500/20 rounded-xl">
                <svg class="w-4 h-4 text-emerald-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                <p class="text-sm text-emerald-700 dark:text-emerald-400">{{ session('status') }}</p>
            </div>
            @endif

            {{-- Form ────────────────────────────────── --}}
            <form method="POST" action="{{ route('login') }}" class="space-y-4">
                @csrf

                {{-- Email --}}
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">
                        Elektron pochta
                    </label>
                    <input type="email" name="email" value="{{ old('email') }}"
                        required autofocus autocomplete="email"
                        class="w-full h-11 px-4 rounded-xl text-sm
                            bg-slate-50 dark:bg-slate-800/80
                            border border-slate-200 dark:border-white/[0.08]
                            text-slate-900 dark:text-white
                            placeholder:text-slate-400 dark:placeholder:text-slate-600
                            transition-all duration-150
                            focus:bg-white dark:focus:bg-slate-800
                            focus:border-emerald-400 dark:focus:border-emerald-500"
                        placeholder="email@example.com">
                    @error('email')
                    <p class="mt-1.5 text-xs text-red-500 flex items-center gap-1">
                        <svg class="w-3.5 h-3.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                        {{ $message }}
                    </p>
                    @enderror
                </div>

                {{-- Password --}}
                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <label class="text-sm font-medium text-slate-700 dark:text-slate-300">Parol</label>
                        @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}"
                            class="text-xs text-emerald-600 dark:text-emerald-400 hover:text-emerald-700 dark:hover:text-emerald-300 transition font-medium">
                            Parolni unutdingizmi?
                        </a>
                        @endif
                    </div>
                    <input type="password" name="password" required
                        class="w-full h-11 px-4 rounded-xl text-sm
                            bg-slate-50 dark:bg-slate-800/80
                            border border-slate-200 dark:border-white/[0.08]
                            text-slate-900 dark:text-white
                            placeholder:text-slate-400 dark:placeholder:text-slate-600
                            transition-all duration-150
                            focus:bg-white dark:focus:bg-slate-800
                            focus:border-emerald-400 dark:focus:border-emerald-500"
                        placeholder="••••••••">
                    @error('password')
                    <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Remember --}}
                <div class="flex items-center gap-2">
                    <input type="checkbox" name="remember" id="remember"
                        class="w-4 h-4 rounded border-slate-300 dark:border-white/20
                            bg-white dark:bg-slate-800
                            text-emerald-500
                            focus:ring-emerald-500 focus:ring-offset-0">
                    <label for="remember" class="text-sm text-slate-600 dark:text-slate-400 cursor-pointer select-none">
                        Eslab qolish
                    </label>
                </div>

                {{-- Submit --}}
                <button type="submit"
                    class="w-full h-11 flex items-center justify-center gap-2
                        bg-emerald-500 hover:bg-emerald-600
                        text-white text-sm font-semibold
                        rounded-xl
                        shadow-md shadow-emerald-500/25
                        hover:shadow-lg hover:shadow-emerald-500/30
                        transition-all duration-200
                        active:scale-[0.98]
                        focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 dark:focus:ring-offset-slate-900">
                    Tizimga kirish
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                    </svg>
                </button>
            </form>

            @if (Route::has('register'))
            <p class="mt-6 text-center text-sm text-slate-500 dark:text-slate-400">
                Hisob yo'qmi?
                <a href="{{ route('register') }}"
                    class="font-semibold text-emerald-600 dark:text-emerald-400 hover:text-emerald-700 dark:hover:text-emerald-300 transition">
                    Ro'yxatdan o'tish
                </a>
            </p>
            @endif

            {{-- Demo credentials ────────────────────── --}}
            <div class="mt-8 p-4 rounded-xl bg-slate-50 dark:bg-white/[0.04] border border-slate-200 dark:border-white/[0.06]">
                <p class="text-[11px] font-semibold text-slate-400 dark:text-white/25 uppercase tracking-widest mb-2.5">
                    Demo kirish
                </p>
                <div class="space-y-1.5">
                    @foreach([
                        ['Admin', 'admin@avtomaktab.uz', 'emerald'],
                        ['Instruktor', 'jasur@avtomaktab.uz', 'blue'],
                        ["O'quvchi", 'alisher.nazarov@student.uz', 'violet'],
                    ] as $demo)
                    <div class="flex items-center justify-between">
                        <span class="text-[11px] font-medium
                            {{ $demo[2] === 'emerald' ? 'text-emerald-600 dark:text-emerald-400' : ($demo[2] === 'blue' ? 'text-blue-600 dark:text-blue-400' : 'text-violet-600 dark:text-violet-400') }}">
                            {{ $demo[0] }}
                        </span>
                        <span class="text-[11px] text-slate-500 dark:text-white/35 font-mono">{{ $demo[1] }}</span>
                    </div>
                    @endforeach
                    <p class="text-[11px] text-slate-400 dark:text-white/20 mt-1">Parol: <span class="font-mono">password</span></p>
                </div>
            </div>

        </div>
    </div>

</div>
</body>
</html>
