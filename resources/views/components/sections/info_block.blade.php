<div
    class="w-full bg-white rounded-3xl shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] border border-slate-100 p-8 md:p-12 mb-8">
    @if(isset($section->config['title']))
        <h2 class="text-3xl font-bold tracking-tight text-gray-900 mb-6 text-center md:text-left">
            {{ $section->config['title'] }}</h2>
    @endif

    @if(isset($section->config['description']))
        <div class="prose prose-slate max-w-none text-gray-600 leading-relaxed text-lg">
            {!! nl2br(e($section->config['description'])) !!}
        </div>
    @endif
</div>