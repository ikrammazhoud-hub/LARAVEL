@extends('layouts.admin')

@section('header', 'Aperçu Global')

@section('content')
<!-- Stats Grid -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-10">
    <!-- Card Machines -->
    <div class="group relative bg-white/70 backdrop-blur-xl rounded-3xl p-8 border border-white shadow-xl shadow-brand-500/5 hover:-translate-y-1 transition-all duration-300">
        <div class="absolute top-0 right-0 p-8 opacity-20 group-hover:opacity-100 group-hover:scale-110 transition-all duration-500">
            <span class="text-6xl filter drop-shadow-lg">⚙️</span>
        </div>
        <div class="relative z-10 flex flex-col h-full justify-between">
            <div>
                <div class="inline-flex items-center justify-center p-3 bg-brand-100 text-brand-600 rounded-2xl mb-4">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                </div>
                <h3 class="text-slate-500 text-sm font-bold uppercase tracking-widest mb-1">Machines</h3>
            </div>
            <div class="mt-4 flex items-baseline gap-2">
                <span class="text-5xl font-extrabold bg-clip-text text-transparent bg-gradient-to-r from-brand-600 to-teal-400">{{ $machinesCount }}</span>
                <span class="text-sm font-medium text-slate-400">enregistrées</span>
            </div>
        </div>
    </div>

    <!-- Card Tâches -->
    <div class="group relative bg-white/70 backdrop-blur-xl rounded-3xl p-8 border border-white shadow-xl shadow-orange-500/5 hover:-translate-y-1 transition-all duration-300">
        <div class="absolute top-0 right-0 p-8 opacity-20 group-hover:opacity-100 group-hover:scale-110 transition-all duration-500">
            <span class="text-6xl filter drop-shadow-lg">🚧</span>
        </div>
        <div class="relative z-10 flex flex-col h-full justify-between">
            <div>
                <div class="inline-flex items-center justify-center p-3 bg-orange-100 text-orange-600 rounded-2xl mb-4">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                </div>
                <h3 class="text-slate-500 text-sm font-bold uppercase tracking-widest mb-1">Tâches</h3>
            </div>
            <div class="mt-4 flex items-baseline gap-2">
                <span class="text-5xl font-extrabold bg-clip-text text-transparent bg-gradient-to-r from-orange-600 to-amber-400">{{ $tachesEnCours }}</span>
                <span class="text-sm font-medium text-slate-400">en cours</span>
            </div>
        </div>
    </div>

    <!-- Card Techniciens -->
    <div class="group relative bg-white/70 backdrop-blur-xl rounded-3xl p-8 border border-white shadow-xl shadow-blue-500/5 hover:-translate-y-1 transition-all duration-300">
        <div class="absolute top-0 right-0 p-8 opacity-20 group-hover:opacity-100 group-hover:scale-110 transition-all duration-500">
            <span class="text-6xl filter drop-shadow-lg">👨‍🔧</span>
        </div>
        <div class="relative z-10 flex flex-col h-full justify-between">
            <div>
                <div class="inline-flex items-center justify-center p-3 bg-blue-100 text-blue-600 rounded-2xl mb-4">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                </div>
                <h3 class="text-slate-500 text-sm font-bold uppercase tracking-widest mb-1">Techniciens</h3>
            </div>
            <div class="mt-4 flex items-baseline gap-2">
                <span class="text-5xl font-extrabold bg-clip-text text-transparent bg-gradient-to-r from-blue-600 to-indigo-400">{{ $techniciensActifs }}</span>
                <span class="text-sm font-medium text-slate-400">actifs</span>
            </div>
        </div>
    </div>
</div>

<!-- Info Section -->
<div class="bg-white/60 backdrop-blur-2xl rounded-3xl shadow-lg border border-white overflow-hidden relative">
    <div class="absolute top-0 right-0 w-64 h-64 bg-gradient-to-br from-brand-100 to-transparent opacity-50 rounded-full blur-3xl -z-10 transform translate-x-1/2 -translate-y-1/2"></div>
    
    <div class="p-8 border-b border-white/50 flex justify-between items-center bg-white/40">
        <div class="flex items-center gap-3">
            <div class="p-2 bg-gradient-to-br from-slate-800 to-slate-900 rounded-lg text-white">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <h3 class="text-xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-slate-800 to-slate-600">Guide des Statuts Machines</h3>
        </div>
    </div>
    
    <div class="p-8">
        <p class="text-slate-500 text-base font-medium mb-6">Légende des états pour la surveillance de l'atelier :</p>
        <div class="flex flex-wrap gap-4">
            <div class="flex items-center gap-2 px-5 py-2.5 bg-brand-50 border border-brand-100 rounded-xl shadow-sm hover:shadow-md transition cursor-default">
                <span class="w-3 h-3 rounded-full bg-brand-500 animate-pulse"></span>
                <span class="text-sm font-bold text-brand-700 uppercase tracking-wide">Actif</span>
            </div>
            <div class="flex items-center gap-2 px-5 py-2.5 bg-red-50 border border-red-100 rounded-xl shadow-sm hover:shadow-md transition cursor-default">
                <span class="w-3 h-3 rounded-full bg-red-500 shadow-[0_0_8px_rgba(239,68,68,0.6)]"></span>
                <span class="text-sm font-bold text-red-700 uppercase tracking-wide">Panne</span>
            </div>
            <div class="flex items-center gap-2 px-5 py-2.5 bg-amber-50 border border-amber-100 rounded-xl shadow-sm hover:shadow-md transition cursor-default">
                <span class="w-3 h-3 rounded-full bg-amber-500"></span>
                <span class="text-sm font-bold text-amber-700 uppercase tracking-wide">Maintenance</span>
            </div>
        </div>
    </div>
</div>
@endsection
