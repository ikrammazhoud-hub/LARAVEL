<!-- Header -->
<header class="h-24 glass-panel border-b border-slate-200/50 flex items-center justify-between px-10 z-10 sticky top-0">
    <div>
        <h2 class="text-3xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-slate-900 to-slate-600">@yield('header', 'Dashboard')</h2>
        <p class="text-sm text-slate-500 mt-1 font-medium">Gérez votre atelier avec efficacité et style.</p>
    </div>
    <div class="flex items-center gap-4">
        <button class="p-2.5 rounded-full bg-white text-slate-600 shadow-sm border border-slate-200 hover:text-brand-600 hover:shadow-md transition-all">
            🔔
        </button>
        <div class="h-8 w-px bg-slate-200 mx-2"></div>
        <div class="text-sm font-medium text-slate-600 bg-white shadow-sm border border-slate-200 px-4 py-2 rounded-full">
            {{ now()->translatedFormat('l j F Y') }}
        </div>
    </div>
</header>
