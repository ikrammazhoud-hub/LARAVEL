<!-- Sidebar Premium -->
<aside class="w-72 glass-sidebar text-slate-700 flex flex-col shadow-2xl relative z-20 transition-all duration-300">
    <!-- Logo Area -->
    <div class="h-24 flex items-center px-8 bg-white/70 border-b border-slate-200/70 backdrop-blur-md">
        <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-brand-500 to-blue-500 shadow-lg shadow-brand-500/25 flex items-center justify-center mr-4">
            <span class="text-xl">⚙️</span>
        </div>
        <div>
            <h1 class="font-bold text-xl tracking-wide text-slate-800">ATELIER PRO</h1>
            <p class="text-xs text-brand-600 font-bold tracking-widest uppercase mt-0.5">Administration</p>
        </div>
    </div>

    <!-- Navigation -->
    <nav class="flex-1 px-4 py-8 space-y-2 overflow-y-auto">
        <div class="px-4 text-xs font-semibold text-slate-500 uppercase tracking-wider mb-4">Menu Principal</div>
        
        <a href="{{ route('admin.dashboard') }}" class="group flex items-center px-4 py-3.5 rounded-2xl transition-all duration-300 relative overflow-hidden {{ request()->routeIs('admin.dashboard') ? 'bg-brand-50 text-brand-900 shadow-md border border-brand-100' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
            @if(request()->routeIs('admin.dashboard'))
                <div class="absolute inset-0 bg-gradient-to-r from-brand-100/80 to-white/20"></div>
                <div class="absolute left-0 top-0 bottom-0 w-1 bg-brand-500 rounded-r-full"></div>
            @endif
            <span class="text-xl mr-4 group-hover:scale-110 transition-transform duration-300 relative z-10">📊</span>
            <span class="font-semibold relative z-10">Dashboard</span>
        </a>
        
        <a href="{{ route('admin.machines.index') }}" class="group flex items-center px-4 py-3.5 rounded-2xl transition-all duration-300 relative overflow-hidden {{ request()->routeIs('admin.machines.*') ? 'bg-brand-50 text-brand-900 shadow-md border border-brand-100' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
            @if(request()->routeIs('admin.machines.*'))
                <div class="absolute inset-0 bg-gradient-to-r from-brand-100/80 to-white/20"></div>
                <div class="absolute left-0 top-0 bottom-0 w-1 bg-brand-500 rounded-r-full"></div>
            @endif
            <span class="text-xl mr-4 group-hover:scale-110 transition-transform duration-300 relative z-10">🛠️</span>
            <span class="font-semibold relative z-10">Machines</span>
        </a>
        
        <a href="{{ route('admin.techniciens.index') }}" class="group flex items-center px-4 py-3.5 rounded-2xl transition-all duration-300 relative overflow-hidden {{ request()->routeIs('admin.techniciens.*') ? 'bg-brand-50 text-brand-900 shadow-md border border-brand-100' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
            @if(request()->routeIs('admin.techniciens.*'))
                <div class="absolute inset-0 bg-gradient-to-r from-brand-100/80 to-white/20"></div>
                <div class="absolute left-0 top-0 bottom-0 w-1 bg-brand-500 rounded-r-full"></div>
            @endif
            <span class="text-xl mr-4 group-hover:scale-110 transition-transform duration-300 relative z-10">👨‍🔧</span>
            <span class="font-semibold relative z-10">Techniciens</span>
        </a>
        
        <a href="{{ route('admin.taches.index') }}" class="group flex items-center px-4 py-3.5 rounded-2xl transition-all duration-300 relative overflow-hidden {{ request()->routeIs('admin.taches.*') ? 'bg-brand-50 text-brand-900 shadow-md border border-brand-100' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
            @if(request()->routeIs('admin.taches.*'))
                <div class="absolute inset-0 bg-gradient-to-r from-brand-100/80 to-white/20"></div>
                <div class="absolute left-0 top-0 bottom-0 w-1 bg-brand-500 rounded-r-full"></div>
            @endif
            <span class="text-xl mr-4 group-hover:scale-110 transition-transform duration-300 relative z-10">📋</span>
            <span class="font-semibold relative z-10">Tâches</span>
        </a>
    </nav>
    
    <!-- User profile mock -->
    <div class="p-4 bg-white/70 border-t border-slate-200/70">
        <div class="flex items-center gap-3 p-3 rounded-xl hover:bg-slate-100 cursor-pointer transition-colors">
            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-indigo-500 to-purple-500 flex items-center justify-center text-white font-bold shadow-md">
                AD
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-semibold text-slate-800 truncate">Administrateur</p>
                <p class="text-xs text-slate-500 truncate">{{ auth()->user()->email ?? 'admin@atelier.com' }}</p>
            </div>
        </div>
        
        <form method="POST" action="{{ route('logout') }}" class="mt-2">
            @csrf
            <button type="submit" class="w-full flex items-center justify-center gap-2 py-2 px-4 rounded-xl text-sm font-semibold text-red-600 hover:text-red-700 hover:bg-red-50 transition-colors border border-transparent hover:border-red-100">
                Déconnexion
            </button>
        </form>
    </div>
</aside>
