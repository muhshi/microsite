<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description"
        content="Portal Situs Terpadu Badan Pusat Statistik Kabupaten Demak. Sistem informasi terpusat untuk mengakses instrumen survei, layanan internal, dan dokumentasi.">
    <meta name="keywords"
        content="BPS Demak, Portal BPS Demak, Microsite BPS Demak, Badan Pusat Statistik Kabupaten Demak">
    <meta name="author" content="Tim IT BPS Kabupaten Demak">

    <!-- Open Graph for Social Media -->
    <meta property="og:title" content="Portal BPS Kabupaten Demak">
    <meta property="og:description"
        content="Platform sentral layanan internal dan instrumen tautan BPS Kabupaten Demak.">
    <meta property="og:type" content="website">
    <meta property="og:image" content="{{ asset('images/logo.png') }}">

    <title>Portal BPS Kabupaten Demak</title>

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Styles / Scripts -->
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <script src="https://cdn.tailwindcss.com"></script>
        <script>
            tailwind.config = {
                darkMode: 'class',
                theme: {
                    extend: {
                        fontFamily: {
                            sans: ['Inter', 'sans-serif'],
                        },
                        animation: {
                            'blob': 'blob 7s infinite',
                        },
                        keyframes: {
                            blob: {
                                '0%': { transform: 'translate(0px, 0px) scale(1)' },
                                '33%': { transform: 'translate(30px, -50px) scale(1.1)' },
                                '66%': { transform: 'translate(-20px, 20px) scale(0.9)' },
                                '100%': { transform: 'translate(0px, 0px) scale(1)' },
                            }
                        }
                    }
                }
            }
        </script>
        <style type="text/tailwindcss">
            @layer utilities {
                                .glass {
                                    @apply bg-white/5 backdrop-blur-xl border border-white/10;
                                }
                            }
                        </style>


        <style>
            .glass {
                background-color: rgb(255 255 255 / 0.05);
                backdrop-filter: blur(24px);
                border: 1px solid rgb(255 255 255 / 0.1);
            }

            .animate-gradient-x {
                background-size: 200% 200%;
                animation: gradient-x 6s ease infinite;
            }

            @keyframes gradient-x {

                0%,
                100% {
                    background-position: 0% 50%;
                }

                50% {
                    background-position: 100% 50%;
                }
            }

            .animation-delay-2000 {
                animation-delay: 2s;
            }

            .animation-delay-4000 {
                animation-delay: 4s;
            }

            .noise-bg {
                background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 200 200' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noiseFilter'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.65' numOctaves='3' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noiseFilter)'/%3E%3C/svg%3E");
            }
        </style>
    @endif
</head>

<body class="bg-[#020617] text-slate-50 font-sans antialiased overflow-x-hidden selection:bg-indigo-500/30">

    <!-- Background Elements -->
    <div class="fixed inset-0 z-[-1] flex items-center justify-center overflow-hidden pointer-events-none">
        <div
            class="absolute top-0 w-[500px] h-[500px] bg-indigo-600/20 rounded-full mix-blend-screen filter blur-[100px] animate-[blob_7s_infinite]">
        </div>
        <div
            class="absolute top-0 right-0 w-[400px] h-[400px] bg-purple-600/20 rounded-full mix-blend-screen filter blur-[100px] animate-[blob_7s_infinite] animation-delay-2000">
        </div>
        <div
            class="absolute bottom-0 left-0 w-[600px] h-[600px] bg-blue-600/20 rounded-full mix-blend-screen filter blur-[100px] animate-[blob_7s_infinite] animation-delay-4000">
        </div>
        <div class="absolute inset-0 noise-bg opacity-20 brightness-100 contrast-150 mix-blend-overlay"></div>
    </div>

    <!-- Navigation -->
    <nav class="relative z-10 sticky top-0 border-b border-white/5 bg-[#020617]/50 backdrop-blur-md">
        <div class="max-w-7xl mx-auto px-6 h-20 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <div
                    class="w-10 h-10 rounded-xl flex items-center justify-center relative overflow-hidden bg-white/5 border border-white/10 p-1 shadow-lg shadow-indigo-500/10">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo BPS Demak"
                        class="w-full h-full object-contain filter drop-shadow-md">
                </div>
                <span
                    class="text-xl font-bold tracking-tight bg-clip-text text-transparent bg-gradient-to-r from-white to-white/70">Portal
                    BPS Demak</span>
            </div>

            <div class="flex items-center gap-4">

                @auth
                    <a href="{{ url('/admin') }}"
                        class="px-5 py-2.5 text-sm font-medium rounded-full bg-white/10 hover:bg-white/20 border border-white/10 transition-all duration-300 hover:shadow-[0_0_20px_rgba(255,255,255,0.1)] hover:-translate-y-0.5">
                        Go to Dashboard
                    </a>
                @else
                    <a href="{{ url('/admin/login') }}"
                        class="px-5 py-2.5 text-sm font-medium text-slate-300 hover:text-white transition-colors duration-300">
                        Log in
                    </a>
                @endauth

            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <main
        class="relative z-10 flex flex-col items-center justify-center min-h-[calc(100vh-80px)] px-6 py-20 text-center">
        <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full glass mb-8 animate-pulse">
            <span class="flex block w-2 h-2 rounded-full bg-emerald-400 shadow-[0_0_8px_rgba(52,211,153,0.8)]"></span>
            <span class="text-sm font-medium text-emerald-400/90 tracking-wide">Portal BPS Demak Siap Digunakan</span>
        </div>

        <h1 class="text-5xl md:text-7xl font-extrabold tracking-tight mb-8 leading-[1.1] max-w-4xl">
            Selamat Datang di <br class="hidden md:block" />
            <span
                class="bg-clip-text text-transparent bg-gradient-to-r from-indigo-400 via-purple-400 to-blue-400 animate-gradient-x">
                Portal BPS Kabupaten Demak
            </span>
        </h1>

        <p class="text-lg md:text-xl text-slate-400 max-w-2xl mb-12 leading-relaxed font-light">
            Sistem informasi terpusat untuk mengakses berbagai tautan instrumen, survei data, layanan internal, dan
            portal dokumentasi BPS Kabupaten Demak secara mudah dan cepat.
        </p>

        <div class="flex flex-col sm:flex-row items-center gap-4 w-full sm:w-auto">
            @auth
                <a href="{{ url('/admin') }}"
                    class="px-8 py-4 text-base font-semibold rounded-full bg-white text-slate-950 hover:bg-slate-200 transition-all duration-300 hover:shadow-[0_0_30px_rgba(255,255,255,0.3)] hover:-translate-y-1 w-full sm:w-auto flex items-center justify-center gap-2">
                    Masuk Dashboard
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3">
                        </path>
                    </svg>
                </a>
            @else
                <a href="{{ url('/admin/login') }}"
                    class="px-8 py-4 text-base font-semibold rounded-full bg-white text-slate-950 hover:bg-slate-200 transition-all duration-300 hover:shadow-[0_0_30px_rgba(255,255,255,0.3)] hover:-translate-y-1 w-full sm:w-auto flex items-center justify-center gap-2">
                    Login Sistem
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3">
                        </path>
                    </svg>
                </a>
            @endauth
        </div>

        <!-- Features Grid -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 max-w-6xl mx-auto mt-32 w-full text-left">
            <!-- Feature 1 -->
            <div
                class="glass p-8 rounded-3xl hover:-translate-y-2 transition-transform duration-500 group relative overflow-hidden">
                <div
                    class="absolute inset-0 bg-gradient-to-br from-indigo-500/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500">
                </div>
                <div
                    class="w-14 h-14 rounded-2xl bg-indigo-500/20 border border-indigo-500/30 flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-500 relative z-10">
                    <svg class="w-7 h-7 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4">
                        </path>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold mb-3 text-slate-100 relative z-10">Tautan Terpusat</h3>
                <p class="text-slate-400 leading-relaxed font-light relative z-10">Seluruh jadwal kuesioner, form tugas,
                    dan layanan internal terpusat dalam satu jendela pencarian di aplikasi ini.</p>
            </div>

            <!-- Feature 2 -->
            <div
                class="glass p-8 rounded-3xl hover:-translate-y-2 transition-transform duration-500 group relative overflow-hidden">
                <div
                    class="absolute inset-0 bg-gradient-to-br from-purple-500/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500">
                </div>
                <div
                    class="w-14 h-14 rounded-2xl bg-purple-500/20 border border-purple-500/30 flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-500 relative z-10">
                    <svg class="w-7 h-7 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z">
                        </path>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold mb-3 text-slate-100 relative z-10">Akses Aman</h3>
                <p class="text-slate-400 leading-relaxed font-light relative z-10">Hanya dapat digunakan dan dikelola
                    oleh pegawai yang sudah terautentikasi dan terdaftar di dalam sistem internal BPS.</p>
            </div>

            <!-- Feature 3 -->
            <div
                class="glass p-8 rounded-3xl hover:-translate-y-2 transition-transform duration-500 group relative overflow-hidden">
                <div
                    class="absolute inset-0 bg-gradient-to-br from-blue-500/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500">
                </div>
                <div
                    class="w-14 h-14 rounded-2xl bg-blue-500/20 border border-blue-500/30 flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-500 relative z-10">
                    <svg class="w-7 h-7 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold mb-3 text-slate-100 relative z-10">Responsif & Cepat</h3>
                <p class="text-slate-400 leading-relaxed font-light relative z-10">Dibangun sebagai wadah microsite yang
                    teroptimasi tanpa perlu loading lambat. Dirancang memadai di Desktop & Mobile.</p>
            </div>
        </div>
    </main>

    <footer class="relative z-10 border-t border-white/5 mt-20 bg-[#020617]/80">
        <div class="max-w-7xl mx-auto px-6 py-8 flex flex-col md:flex-row items-center justify-between">
            <p class="text-slate-500 text-sm font-light">
                &copy; {{ date('Y') }} Badan Pusat Statistik Kabupaten Demak. All rights reserved.
            </p>
            <div class="flex items-center gap-2 mt-4 md:mt-0 text-slate-500 text-sm font-light">
                <span>Made with <span class="text-rose-500 mx-1">♥</span> for data lovers.</span>
            </div>
        </div>
    </footer>
</body>

</html>