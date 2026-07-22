<div
    class="w-full bg-white rounded-3xl shadow-sm border border-slate-200 p-8 md:p-12 mb-8">
    @if(isset($section->config['title']))
        <h2 class="text-3xl font-extrabold tracking-tight text-slate-900 mb-6 text-center md:text-left">
            {{ $section->config['title'] }}</h2>
    @endif

    @if(isset($section->config['description']))
        <div class="prose prose-slate max-w-none text-slate-600 leading-relaxed text-lg">
            {!! nl2br(e($section->config['description'])) !!}
        </div>
    @endif
</div>