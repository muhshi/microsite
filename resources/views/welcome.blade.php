<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

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
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">

    <!-- Styles / Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

</head>

<body class="text-slate-900 font-sans antialiased overflow-x-hidden selection:bg-bps-blue/15" style="background-color: #FAFAF6;">

    <!-- Warm Ivory Premium Background -->
    <div class="fixed inset-0 z-[-1] pointer-events-none overflow-hidden">
        <!-- Warm base gradient -->
        <div class="absolute inset-0" style="background: linear-gradient(135deg, #FAFAF6 0%, #F5F0EB 40%, #EEF4FF 100%);"></div>
        
        <!-- Soft warm color blobs -->
        <div class="absolute top-[-15%] left-[-10%] w-[60%] h-[60%] rounded-full animate-pulse-slow" style="background: radial-gradient(circle, rgba(0,91,171,0.08) 0%, transparent 70%); filter: blur(80px);"></div>
        <div class="absolute bottom-[-10%] right-[-10%] w-[50%] h-[50%] rounded-full animate-pulse-slow" style="animation-delay: 2s; background: radial-gradient(circle, rgba(78,180,65,0.07) 0%, transparent 70%); filter: blur(80px);"></div>
        <div class="absolute top-[25%] right-[5%] w-[35%] h-[35%] rounded-full animate-pulse-slow" style="animation-delay: 4s; background: radial-gradient(circle, rgba(255,153,29,0.06) 0%, transparent 70%); filter: blur(70px);"></div>
        
        <!-- Elegant linen texture overlay -->
        <div class="absolute inset-0 opacity-[0.025]" style="background-image: url(&quot;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='4' height='4'%3E%3Crect width='4' height='4' fill='%23d4b896'/%3E%3Crect width='1' height='1' fill='%23c4a882'/%3E%3C/svg%3E&quot;);"></div>
    </div>

    <!-- Navigation -->
    <nav class="fixed top-6 inset-x-6 z-50">
        <div class="max-w-7xl mx-auto">
            <div
                class="flex items-center justify-between px-8 py-5 rounded-[2.5rem] shadow-xl" style="background: rgba(255,255,254,0.85); backdrop-filter: blur(24px); -webkit-backdrop-filter: blur(24px); border: 1px solid rgba(0,91,171,0.1); box-shadow: 0 8px 32px rgba(0,91,171,0.06);">
                <div class="flex items-center gap-4 group cursor-pointer">
                    <div class="relative">
                        <div
                            class="absolute -inset-2 bg-bps-blue/20 rounded-full blur-lg opacity-0 group-hover:opacity-100 transition-opacity">
                        </div>
                        <img src="{{ asset('images/logo.png') }}" alt="Logo BPS" class="h-10 w-auto relative">
                    </div>
                    <div class="border-l-2 border-slate-200 pl-4">
                        <span
                            class="block text-base font-black text-bps-blue uppercase tracking-tighter leading-none mb-0.5">PORTAL
                            TERPADU</span>
                        <span
                            class="block text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] opacity-70">BPS
                            Kabupaten Demak</span>
                    </div>
                </div>

                <div class="hidden md:flex items-center gap-8">
                    <a href="https://demakkab.bps.go.id" target="_blank"
                        class="text-sm font-bold text-slate-500 hover:text-bps-blue transition-colors relative group">
                        Website Resmi
                        <span
                            class="absolute -bottom-1 left-0 w-0 h-0.5 bg-bps-blue transition-all group-hover:w-full"></span>
                    </a>
                    @auth
                        <a href="{{ url('/admin') }}" class="btn-primary !px-6 !py-3 !text-sm">Dashboard Admin</a>
                    @else
                        <a href="{{ url('/admin/login') }}"
                            class="px-7 py-3 rounded-2xl bg-slate-900 text-white font-bold text-sm hover:bg-slate-800 transition-all hover:shadow-xl active:scale-95">Masuk
                            Akun</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <main class="relative pt-32 pb-20 px-6 overflow-hidden">
        <div class="max-w-4xl mx-auto flex flex-col items-center text-center gap-12 py-10">
            
            <div class="w-full relative z-10 flex flex-col items-center">
                <!-- Decorative Elements -->
                <div class="absolute -top-20 -left-20 w-40 h-40 bg-bps-orange/10 rounded-full blur-[80px]"></div>

                <div
                    class="inline-flex items-center gap-3 px-5 py-2 rounded-full border border-bps-blue/20 mb-8 shadow-sm" style="background: rgba(0,91,171,0.06);">
                    <span class="flex h-1.5 w-1.5 rounded-full bg-bps-blue animate-ping"></span>
                    <span class="text-[10px] font-black text-bps-blue uppercase tracking-[0.2em]">Portal Tautan Terpadu
                        • BPS Kabupaten Demak</span>
                </div>

                <h1 class="text-5xl md:text-6xl font-black tracking-tight mb-6 leading-[1.05] text-slate-900">
                    Pusat Tautan <br> <span class="text-bps-blue">Kegiatan</span> <br class="md:hidden"><span class="md:inline"> BPS</span> <br class="hidden md:block">Kabupaten Demak
                </h1>

                <p class="text-base sm:text-lg text-slate-500 max-w-2xl mx-auto mb-10 leading-relaxed font-medium">
                    Selamat datang di Portal Tautan Kegiatan <span class="font-bold text-slate-800">BPS Kabupaten
                        Demak</span>. <br class="hidden md:block">
                    Akses berbagai kumpulan tautan instrumen survei, dashboard pelaporan, dan sistem internal dari setiap kegiatan statistik dengan mudah dalam satu tempat terpusat.
                </p>




                <!-- Ultra-Compact Integrated Stats Bar -->
                <div class="mt-6 transition-all duration-1000 animate-fade-in-up">
                    <div class="inline-flex items-center gap-1.5 p-1 rounded-full shadow-lg" style="background: rgba(255,255,254,0.9); backdrop-filter: blur(12px); border: 1px solid rgba(0,91,171,0.1); box-shadow: 0 4px 16px rgba(0,91,171,0.08);">
                        <!-- Stat 1 -->
                        <div class="flex items-center gap-3 pl-2.5 pr-5 py-1.5 rounded-full transition-colors cursor-default group/stat">
                            <div class="w-8 h-8 rounded-lg bg-bps-blue/10 flex items-center justify-center text-bps-blue group-hover/stat:bg-bps-blue group-hover/stat:text-white transition-all duration-300">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                            </div>
                            <div>
                                <span class="block text-lg font-black text-slate-900 leading-none mb-0.5"><span class="counter" data-target="0">0</span>+</span>
                                <span class="text-[8px] font-bold text-slate-400 uppercase tracking-widest leading-none">Instrumen</span>
                            </div>
                        </div>
                        
                        <div class="w-px h-5 bg-slate-200/60"></div>

                        <!-- Stat 2 -->
                        <div class="flex items-center gap-3 pl-2.5 pr-5 py-1.5 rounded-full transition-colors cursor-default group/stat">
                            <div class="w-8 h-8 rounded-lg bg-bps-orange/10 flex items-center justify-center text-bps-orange group-hover/stat:bg-bps-orange group-hover/stat:text-white transition-all duration-300">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </div>
                            <div>
                                <span class="block text-lg font-black text-slate-900 leading-none mb-0.5"><span class="counter" data-target="0">0</span>/7</span>
                                <span class="text-[8px] font-bold text-slate-400 uppercase tracking-widest leading-none">Update</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


        </div>

        <!-- Scrolling Marquee Strip -->
        <div class="relative mt-20 mb-0 overflow-hidden py-6">
            <!-- Fade Masks -->
            <div class="absolute left-0 top-0 bottom-0 w-32 z-10 pointer-events-none" style="background: linear-gradient(to right, #FAFAF6, transparent);"></div>
            <div class="absolute right-0 top-0 bottom-0 w-32 z-10 pointer-events-none" style="background: linear-gradient(to left, #FAFAF6, transparent);"></div>

            <div class="flex gap-8 animate-marquee whitespace-nowrap">
                @foreach([
                    ['icon' => '🔗', 'text' => 'Kumpulan Link Kegiatan'],
                    ['icon' => '📝', 'text' => 'Kumpulan Link Kuesioner'],
                    ['icon' => '📊', 'text' => 'Kumpulan Link Dashboard'],
                    ['icon' => '📂', 'text' => 'Kumpulan Link Survei'],
                    ['icon' => '📋', 'text' => 'Kumpulan Link Pendaftaran'],
                    ['icon' => '💻', 'text' => 'Kumpulan Link Aplikasi'],
                    ['icon' => '📚', 'text' => 'Kumpulan Link Materi'],
                    ['icon' => '🌐', 'text' => 'Kumpulan Link Internal'],
                    ['icon' => '🔗', 'text' => 'Kumpulan Link Kegiatan'],
                    ['icon' => '📝', 'text' => 'Kumpulan Link Kuesioner'],
                    ['icon' => '📊', 'text' => 'Kumpulan Link Dashboard'],
                    ['icon' => '📂', 'text' => 'Kumpulan Link Survei'],
                    ['icon' => '📋', 'text' => 'Kumpulan Link Pendaftaran'],
                    ['icon' => '💻', 'text' => 'Kumpulan Link Aplikasi'],
                    ['icon' => '📚', 'text' => 'Kumpulan Link Materi'],
                    ['icon' => '🌐', 'text' => 'Kumpulan Link Internal'],
                ] as $item)
                    <div class="inline-flex items-center gap-3 px-6 py-3 rounded-full flex-shrink-0" style="background: rgba(255,255,254,0.85); border: 1px solid rgba(0,91,171,0.1); box-shadow: 0 2px 8px rgba(0,91,171,0.05);">
                        <span class="text-lg">{{ $item['icon'] }}</span>
                        <span class="text-sm font-bold text-slate-600 tracking-wide">{{ $item['text'] }}</span>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Features Section -->
        <section class="relative py-32 mt-4 overflow-hidden">
            <!-- Decorative Glows -->
            <div class="absolute top-0 right-[-10%] w-[40%] h-[40%] bg-bps-orange/8 rounded-full filter blur-[120px] pointer-events-none"></div>
            <div class="absolute bottom-[-10%] left-[-10%] w-[40%] h-[40%] bg-bps-blue/8 rounded-full filter blur-[120px] pointer-events-none"></div>
            
            <div class="max-w-7xl mx-auto px-6">
                <!-- Section Header -->
                <div class="text-center mb-24 reveal">
                    <h2 class="text-4xl md:text-5xl font-black text-slate-900 tracking-tighter mb-6">
                        Portal <span class="text-gradient">Terintegrasi</span>
                    </h2>
                    <p class="text-slate-500 font-medium max-w-2xl mx-auto text-lg leading-relaxed">
                        Kami menghadirkan platform digital yang dirancang khusus untuk menyimpan dan mengorganisir seluruh himpunan tautan dari berbagai kegiatan statistik.
                    </p>
                </div>

                <!-- Vibrant Feature Cards -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-10 reveal">
            <!-- Card 1 -->
            <div class="group relative h-full">
                <div
                    class="absolute -inset-4 bg-bps-blue/5 rounded-[4rem] blur-2xl opacity-0 group-hover:opacity-100 transition-opacity duration-500">
                </div>
                <div
                    class="h-full p-12 rounded-[3.5rem] transition-all duration-500 hover:shadow-2xl hover:shadow-bps-blue/15 hover:-translate-y-4 relative overflow-hidden" style="background: rgba(255,255,254,0.9); backdrop-filter: blur(16px); border: 1px solid rgba(0,91,171,0.08); box-shadow: 0 4px 24px rgba(0,91,171,0.06);">
                    <div
                        class="absolute -top-10 -right-10 w-40 h-40 bg-bps-blue/5 rounded-full blur-3xl opacity-0 group-hover:opacity-100 transition-opacity duration-700">
                    </div>

                    <div
                        class="w-20 h-20 rounded-3xl bg-gradient-to-br from-bps-blue to-bps-dark flex items-center justify-center mb-10 shadow-xl shadow-bps-blue/20 group-hover:rotate-[10deg] transition-transform duration-500">
                        <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-black text-slate-900 mb-5 tracking-tighter">Pusat Instrumen</h3>
                    <p class="text-slate-500 font-medium leading-relaxed mb-6">Akses satu gerbang terpadu
                        untuk kuesioner digital, aplikasi survei, dan monitoring tugas lapangan.</p>
                    <div
                        class="flex items-center gap-2 text-bps-blue text-xs font-black uppercase tracking-widest opacity-0 group-hover:opacity-100 transition-all duration-500 translate-y-2 group-hover:translate-y-0">
                        Pelajari Selengkapnya
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Card 2 -->
            <div class="group relative h-full">
                <div
                    class="absolute -inset-4 bg-bps-green/5 rounded-[4rem] blur-2xl opacity-0 group-hover:opacity-100 transition-opacity duration-500">
                </div>
                <div
                    class="h-full p-12 rounded-[3.5rem] transition-all duration-500 hover:shadow-2xl hover:shadow-bps-green/15 hover:-translate-y-4 relative overflow-hidden" style="background: rgba(255,255,254,0.9); backdrop-filter: blur(16px); border: 1px solid rgba(78,180,65,0.08); box-shadow: 0 4px 24px rgba(78,180,65,0.06);">
                    <div
                        class="absolute -top-10 -right-10 w-40 h-40 bg-bps-green/5 rounded-full blur-3xl opacity-0 group-hover:opacity-100 transition-opacity duration-700">
                    </div>

                    <div
                        class="w-20 h-20 rounded-3xl bg-gradient-to-br from-bps-green to-emerald-700 flex items-center justify-center mb-10 shadow-xl shadow-bps-green/20 group-hover:rotate-[10deg] transition-transform duration-500">
                        <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 00-2 2zm10-10V7a4 4 0 00-8 0v4h8z">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-black text-slate-900 mb-5 tracking-tighter">Kerahasiaan Terjamin</h3>
                    <p class="text-slate-500 font-medium leading-relaxed mb-6">Sistem autentikasi berlapis
                        memastikan integritas data terjamin sesuai protokol standar BPS.</p>
                    <div
                        class="flex items-center gap-2 text-bps-green text-xs font-black uppercase tracking-widest opacity-0 group-hover:opacity-100 transition-all duration-500 translate-y-2 group-hover:translate-y-0">
                        Privasi & Keamanan
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Card 3 -->
            <div class="group relative h-full">
                <div
                    class="absolute -inset-4 bg-bps-orange/5 rounded-[4rem] blur-2xl opacity-0 group-hover:opacity-100 transition-opacity duration-500">
                </div>
                <div
                    class="h-full p-12 rounded-[3.5rem] transition-all duration-500 hover:shadow-2xl hover:shadow-bps-orange/15 hover:-translate-y-4 relative overflow-hidden" style="background: rgba(255,255,254,0.9); backdrop-filter: blur(16px); border: 1px solid rgba(255,153,29,0.08); box-shadow: 0 4px 24px rgba(255,153,29,0.06);">
                    <div
                        class="absolute -top-10 -right-10 w-40 h-40 bg-bps-orange/5 rounded-full blur-3xl opacity-0 group-hover:opacity-100 transition-opacity duration-700">
                    </div>

                    <div
                        class="w-20 h-20 rounded-3xl bg-gradient-to-br from-bps-orange to-orange-700 flex items-center justify-center mb-10 shadow-xl shadow-bps-orange/20 group-hover:rotate-[10deg] transition-transform duration-500">
                        <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-black text-slate-900 mb-5 tracking-tighter">Responsif & Sigap</h3>
                    <p class="text-slate-500 font-medium leading-relaxed mb-6">Platform ringan yang
                        dioptimasi untuk kecepatan akses di lapangan maupun di dalam kantor.</p>
                    <div
                        class="flex items-center gap-2 text-bps-orange text-xs font-black uppercase tracking-widest opacity-0 group-hover:opacity-100 transition-all duration-500 translate-y-2 group-hover:translate-y-0">
                        Performa Optimal
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                        </svg>
                    </div>
                </div>
            </div>
            </div>
        </div>
    </section>
</main>


    <!-- Section Divider (Wave) -->
    <div class="relative h-24 w-full overflow-hidden -mb-px" style="background-color: #FAFAF6;">
        <svg class="absolute bottom-0 w-full h-full" preserveAspectRatio="none" viewBox="0 0 1440 120" fill="none"
            xmlns="http://www.w3.org/2000/svg">
            <path d="M0 120H1440V0C1440 0 1120 70 720 70C320 70 0 0 0 0V120Z" fill="#0f172a" />
        </svg>
    </div>

    <!-- Footer -->
    <footer class="bg-slate-900 text-white relative overflow-hidden reveal">
        <!-- Background Glow -->
        <div
            class="absolute top-0 left-1/4 w-[500px] h-[500px] bg-bps-blue/20 rounded-full blur-[120px] pointer-events-none">
        </div>
        <div
            class="absolute bottom-0 right-1/4 w-[400px] h-[400px] bg-bps-green/10 rounded-full blur-[100px] pointer-events-none">
        </div>

        <div class="max-w-7xl mx-auto px-6 pt-24 pb-12 relative z-10">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-16 mb-20">
                <!-- Branding Column -->
                <div class="lg:col-span-4 flex flex-col items-start gap-8">
                    <div class="flex items-center gap-4">
                        <div class="p-3 bg-white rounded-2xl shadow-lg shadow-white/10">
                            <img src="{{ asset('images/logo.png') }}" class="h-12 w-auto" alt="Logo Footer">
                        </div>
                        <div>
                            <h4 class="text-xl font-black tracking-tighter leading-none mb-1">BPS KABUPATEN</h4>
                            <h4 class="text-xl font-black tracking-tighter text-bps-blue leading-none uppercase">DEMAK
                            </h4>
                        </div>
                    </div>
                    <p class="text-slate-400 font-medium leading-relaxed max-w-sm">
                        Penyedia data statistik berkualitas untuk membantu perencanaan dan evaluasi pembangunan di
                        Kabupaten Demak.
                    </p>
                    <div class="space-y-4 font-inter">
                        <div class="flex items-start gap-4 group">
                            <div
                                class="w-10 h-10 rounded-xl bg-white/5 border border-white/10 flex items-center justify-center group-hover:bg-bps-blue/20 transition-colors">
                                <svg class="w-5 h-5 text-bps-blue" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                                    </path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                            </div>
                            <span class="text-sm font-medium text-slate-300">Jl. Sultan Hadiwijaya No. 23 <br>Demak,
                                Jawa Tengah 59515</span>
                        </div>
                        <div class="flex items-center gap-4 group">
                            <div
                                class="w-10 h-10 rounded-xl bg-white/5 border border-white/10 flex items-center justify-center group-hover:bg-bps-blue/20 transition-colors">
                                <svg class="w-5 h-5 text-bps-blue" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 5z">
                                    </path>
                                </svg>
                            </div>
                            <div class="flex flex-col">
                                <span class="text-sm font-medium text-slate-300">Telp: (0291) 685445</span>
                                <span class="text-xs font-medium text-slate-400">Fax: (0291) 681754</span>
                            </div>
                        </div>
                        <div class="flex items-center gap-4 group">
                            <div
                                class="w-10 h-10 rounded-xl bg-white/5 border border-white/10 flex items-center justify-center group-hover:bg-bps-blue/20 transition-colors">
                                <svg class="w-5 h-5 text-bps-blue" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                                    </path>
                                </svg>
                            </div>
                            <span class="text-sm font-medium text-slate-300">bps3321@bps.go.id</span>
                        </div>
                    </div>
                </div>

                <!-- Navigation Links -->
                <div class="lg:col-span-2 flex flex-col gap-8">
                    <h5 class="text-sm font-black uppercase tracking-[0.2em] text-white/50">Layanan</h5>
                    <ul class="space-y-4 font-bold text-slate-400">
                        <li><a href="#" class="hover:text-bps-blue transition-colors">Microsite</a></li>
                        <li><a href="#" class="hover:text-bps-blue transition-colors">Survey Digital</a></li>
                        <li><a href="#" class="hover:text-bps-blue transition-colors">Data Publik</a></li>
                        <li><a href="#" class="hover:text-bps-blue transition-colors">Instrumen Lapangan</a></li>
                    </ul>
                </div>

                <!-- External Links -->
                <div class="lg:col-span-3 flex flex-col gap-8">
                    <h5 class="text-sm font-black uppercase tracking-[0.2em] text-white/50">Tautan Cepat</h5>
                    <ul class="space-y-4 font-bold text-slate-400">
                        <li><a href="https://bps.go.id" target="_blank"
                                class="hover:text-bps-blue transition-colors flex items-center gap-2">BPS Republik
                                Indonesia <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                        d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14">
                                    </path>
                                </svg></a></li>
                        <li><a href="https://jateng.bps.go.id" target="_blank"
                                class="hover:text-bps-blue transition-colors flex items-center gap-2">BPS Provinsi Jawa
                                Tengah <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                        d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14">
                                    </path>
                                </svg></a></li>
                        <li><a href="https://demakkab.bps.go.id" target="_blank"
                                class="hover:text-bps-blue transition-colors flex items-center gap-2">Portal BPS Kab.
                                Demak <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                        d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14">
                                    </path>
                                </svg></a></li>
                        <li><a href="https://demakkab.go.id" target="_blank"
                                class="hover:text-bps-blue transition-colors flex items-center gap-2">Pemkab Demak <svg
                                    class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                        d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14">
                                    </path>
                                </svg></a></li>
                    </ul>
                </div>

                <!-- Connect Column -->
                <div class="lg:col-span-3 flex flex-col gap-8">
                    <h5 class="text-sm font-black uppercase tracking-[0.2em] text-white/50">Media Sosial</h5>
                    <div class="flex items-center gap-4">
                        <a href="https://instagram.com/bpskabdemak" target="_blank"
                            class="w-12 h-12 rounded-2xl bg-white/5 border border-white/10 flex items-center justify-center hover:bg-gradient-to-tr hover:from-orange-500 hover:to-pink-500 hover:border-transparent transition-all duration-300">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M12 2.163c3.204 0 3.584.012 4.85.07 1.17.054 1.805.249 2.227.412.56.216.96.474 1.38.894.42.42.678.82.894 1.38.163.422.358 1.057.412 2.227.058 1.266.07 1.646.07 4.85s-.012 3.584-.07 4.85c-.054 1.17-.249 1.805-.412 2.227-.216.56-.474.96-.894 1.38-.42.42-.82.678-1.38.894-.422.163-1.057.358-2.227.412-1.266.058-1.646.07-4.85.07s-3.584-.012-4.85-.07c-1.17-.054-1.805-.249-2.227-.412-.56-.216-.96-.474-1.38-.894-.42-.42-.678-.82-.894-1.38-.163-.422-.358-1.057-.412-2.227-.058-1.266-.07-1.646-.07-4.85s.012-3.584.07-4.85c.054-1.17.249-1.805.412-2.227.216-.56.474-.96.894-1.38.42-.42.82-.678 1.38-.894.422-.163 1.057-.358 2.227-.412 1.266-.058 1.646-.07 4.85-.07m0-2.163c-3.259 0-3.667.014-4.947.072-1.277.057-2.148.258-2.911.554-.788.305-1.456.713-2.122 1.378-.665.666-1.073 1.334-1.378 2.122-.296.763-.497 1.634-.554 2.911-.059 1.28-.073 1.688-.073 4.947s.014 3.668.072 4.948c.057 1.277.258 2.148.554 2.911.305.788.713 1.456 1.378 2.122.666.665 1.334-1.073 2.122-1.378.763-.296 1.634-.497 2.911-.554 1.28-.059 1.688-.073 4.947-.073s3.668.014 4.948.072c1.277.057 2.148.258 2.911.554.788.305 1.456.713 2.122 1.378.666.666 1.073 1.334 1.378 2.122.296.763.497 1.634.554 2.911.059 1.28.073 1.688.073 4.947s-.014 3.668-.072 4.948c-.057 1.277-.258 2.148-.554 2.911-.305.788-.713 1.456-1.378 2.122-.666.665-1.334 1.073-2.122 1.378-.763-.296-1.634-.497-2.911-.554-1.28-.059-1.688-.073-4.947-.073s-3.668.014-4.948.072c-1.277.057-2.148.258-2.911.554-.788.305-1.456.713-2.122 1.378-.665.666-1.073 1.334-1.378 2.122-.296.763-.497 1.634-.554 2.911-.059 1.28-.073 1.688-.073 4.947s.014 3.668.072 4.948c.057 1.277.258 2.148.554 2.911.305.788.713 1.456 1.378 2.122.666.665 1.334 1.073 2.122 1.378.763-.296 1.634-.497 2.911-.554 1.28-.059 1.688-.073 4.947-.073" />
                            </svg>
                        </a>
                        <a href="https://twitter.com/bpskabdemak" target="_blank"
                            class="w-12 h-12 rounded-2xl bg-white/5 border border-white/10 flex items-center justify-center hover:bg-blue-400 hover:border-transparent transition-all duration-300">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z" />
                            </svg>
                        </a>
                        <a href="https://facebook.com/bpskabdemak" target="_blank"
                            class="w-12 h-12 rounded-2xl bg-white/5 border border-white/10 flex items-center justify-center hover:bg-blue-700 hover:border-transparent transition-all duration-300">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M9 8h-3v4h3v12h5v-12h3.642l.358-4h-4v-1.667c0-.955.192-1.333 1.115-1.333h2.885v-5h-3.808c-3.596 0-5.192 1.583-5.192 4.615v3.385z" />
                            </svg>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Bottom Bar -->
            <div class="pt-12 border-t border-white/10 flex flex-col md:flex-row items-center justify-between gap-8">
                <p class="text-slate-500 font-medium text-sm text-center md:text-left">
                    &copy; {{ date('Y') }} Badan Pusat Statistik Kabupaten Demak. <br class="md:hidden"> Seluruh hak
                    cipta dilindungi.
                </p>
                <div class="flex items-center gap-6">
                    <div class="flex items-center gap-3 px-6 py-2.5 rounded-2xl bg-white/5 border border-white/10">
                        <span class="w-2 h-2 rounded-full bg-bps-blue animate-pulse"></span>
                        <span class="text-[10px] font-black uppercase tracking-[0.3em] text-slate-300">Data Mencerdaskan
                            Bangsa</span>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <!-- Back to Top -->
    <button id="backToTop"
        class="fixed bottom-8 right-8 w-14 h-14 bg-bps-blue text-white rounded-2xl shadow-2xl shadow-bps-blue/30 flex items-center justify-center translate-y-20 opacity-0 transition-all duration-500 hover:bg-bps-dark hover:-translate-y-1 z-[60]">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 15l7-7 7 7"></path>
        </svg>
    </button>

    <!-- Scripts -->
    <script>
        // Scroll Reveal
        const observerOptions = {
            threshold: 0.1
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('reveal-visible');

                    // Trigger count-up if it contains counters
                    const counters = entry.target.querySelectorAll('.counter');
                    if (counters.length > 0) {
                        counters.forEach(counter => {
                            const target = +counter.getAttribute('data-target');
                            const increment = target / 50; // Speed of animation

                            const updateCount = () => {
                                const count = +counter.innerText;
                                if (count < target) {
                                    counter.innerText = Math.ceil(count + increment);
                                    setTimeout(updateCount, 20);
                                } else {
                                    counter.innerText = target;
                                }
                            };
                            updateCount();
                        });
                    }

                    // Delay for sequential grid children
                    if (entry.target.classList.contains('grid')) {
                        Array.from(entry.target.children).forEach((child, index) => {
                            setTimeout(() => {
                                child.classList.add('reveal-visible');
                            }, index * 100);
                        });
                    }
                }
            });
        }, observerOptions);

        document.querySelectorAll('.reveal').forEach((el) => observer.observe(el));

        // Back to Top Logic
        const backToTop = document.getElementById('backToTop');
        window.addEventListener('scroll', () => {
            if (window.scrollY > 500) {
                backToTop.classList.remove('translate-y-20', 'opacity-0');
            } else {
                backToTop.classList.add('translate-y-20', 'opacity-0');
            }
        });
        backToTop.addEventListener('click', () => {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
    </script>

    <style>
        .reveal {
            opacity: 0;
            transform: translateY(30px);
            transition: all 0.8s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .reveal-visible {
            opacity: 1 !important;
            transform: translateY(0) !important;
        }
    </style>
</body>

</html>