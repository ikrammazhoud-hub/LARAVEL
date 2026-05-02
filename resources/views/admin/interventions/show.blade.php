@extends('layouts.admin')

@section('title', 'Détail Intervention #' . $intervention->id)

@section('content')
<div class="max-w-3xl mx-auto space-y-6">

    <div class="flex items-center justify-between">
        <div>
            <a href="{{ route('admin.interventions.index') }}" class="inline-flex items-center gap-1 text-sm text-slate-500 hover:text-brand-600 transition-colors mb-2">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                Interventions
            </a>
            <h1 class="text-2xl font-bold text-slate-800">Intervention <span class="text-brand-600">#{{ $intervention->id }}</span></h1>
        </div>

        <div class="flex gap-2">
            @if($intervention->statut === 'en_cours')
            <form method="POST" action="{{ route('admin.interventions.cloturer', $intervention) }}">
                @csrf @method('PATCH')
                <button type="submit" class="inline-flex items-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold px-4 py-2 rounded-xl text-sm transition-all">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    Clôturer
                </button>
            </form>
            @endif
            <a href="{{ route('admin.interventions.edit', $intervention) }}"
               class="inline-flex items-center gap-2 bg-amber-100 hover:bg-amber-200 text-amber-800 font-semibold px-4 py-2 rounded-xl text-sm transition-all">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                Modifier
            </a>
        </div>
    </div>

    {{-- Carte principale --}}
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-8 space-y-6">

        <div class="grid grid-cols-2 sm:grid-cols-3 gap-6">
            <div>
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">Statut</p>
                @if($intervention->statut === 'terminee')
                    <span class="inline-flex items-center gap-1 bg-emerald-100 text-emerald-700 text-sm font-semibold px-3 py-1 rounded-full">
                        <span class="w-2 h-2 bg-emerald-500 rounded-full"></span> Terminée
                    </span>
                @else
                    <span class="inline-flex items-center gap-1 bg-blue-100 text-blue-700 text-sm font-semibold px-3 py-1 rounded-full">
                        <span class="w-2 h-2 bg-blue-500 rounded-full animate-pulse"></span> En cours
                    </span>
                @endif
            </div>
            <div>
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">Machine</p>
                <p class="font-semibold text-slate-800">{{ $intervention->machine?->nom ?? '—' }}</p>
            </div>
            <div>
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">Technicien</p>
                <p class="font-semibold text-slate-800">{{ $intervention->technicien?->user?->name ?? '—' }}</p>
                <p class="text-xs text-slate-400">{{ $intervention->technicien?->specialite }}</p>
            </div>
            <div>
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">Date début</p>
                <p class="text-slate-700">{{ $intervention->date_debut?->format('d/m/Y H:i') ?? '—' }}</p>
            </div>
            <div>
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">Date fin</p>
                <p class="text-slate-700">{{ $intervention->date_fin?->format('d/m/Y H:i') ?? '—' }}</p>
            </div>
            @if($intervention->tache)
            <div>
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">Tâche liée</p>
                <p class="text-slate-700">{{ $intervention->tache->titre }}</p>
            </div>
            @endif
        </div>

        <div class="border-t border-slate-100 pt-4">
            <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Description</p>
            <p class="text-slate-700 leading-relaxed">{{ $intervention->description }}</p>
        </div>
    </div>

    {{-- Rapport associé --}}
    @if($intervention->rapport)
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-8 space-y-4">
        <div class="flex items-center justify-between">
            <h2 class="text-lg font-bold text-slate-800">Rapport d'intervention</h2>
            <a href="{{ route('admin.rapport.pdf', $intervention->rapport) }}"
               class="inline-flex items-center gap-2 text-sm text-red-600 hover:text-red-700 font-semibold transition-colors">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                Télécharger PDF
            </a>
        </div>
        <div class="space-y-3 text-sm">
            <div>
                <p class="font-semibold text-slate-500 mb-1">Contenu</p>
                <p class="text-slate-700">{{ $intervention->rapport->contenu }}</p>
            </div>
            @if($intervention->rapport->observations)
            <div>
                <p class="font-semibold text-slate-500 mb-1">Observations</p>
                <p class="text-slate-700">{{ $intervention->rapport->observations }}</p>
            </div>
            @endif
            @if($intervention->rapport->pieces_changees)
            <div>
                <p class="font-semibold text-slate-500 mb-1">Pièces changées</p>
                <p class="text-slate-700">{{ $intervention->rapport->pieces_changees }}</p>
            </div>
            @endif
            @if($intervention->rapport->recommandations)
            <div>
                <p class="font-semibold text-slate-500 mb-1">Recommandations</p>
                <p class="text-slate-700">{{ $intervention->rapport->recommandations }}</p>
            </div>
            @endif
        </div>
    </div>
    @endif

</div>
@endsection
