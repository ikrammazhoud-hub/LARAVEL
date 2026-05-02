@extends('layouts.admin')

@section('title', $tache->titre)

@section('content')
<div class="max-w-3xl mx-auto space-y-6">

    <div class="flex items-start justify-between">
        <div>
            <a href="{{ route('admin.taches.index') }}" class="inline-flex items-center gap-1 text-sm text-slate-500 hover:text-brand-600 transition-colors mb-2">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                Tâches
            </a>
            <h1 class="text-2xl font-bold text-slate-800">{{ $tache->titre }}</h1>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('admin.taches.edit', $tache) }}"
               class="inline-flex items-center gap-1 bg-amber-100 hover:bg-amber-200 text-amber-800 font-semibold px-4 py-2 rounded-xl text-sm transition-all">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                Modifier
            </a>
            <form method="POST" action="{{ route('admin.taches.destroy', $tache) }}" onsubmit="return confirm('Supprimer cette tâche ?')">
                @csrf @method('DELETE')
                <button type="submit" class="inline-flex items-center gap-1 bg-red-100 hover:bg-red-200 text-red-700 font-semibold px-4 py-2 rounded-xl text-sm transition-all">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    Supprimer
                </button>
            </form>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-8 space-y-6">
        <div class="grid grid-cols-2 sm:grid-cols-3 gap-6">
            <div>
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">Statut</p>
                @php
                    $sc = match($tache->statut) {
                        'terminé'    => 'bg-emerald-100 text-emerald-700',
                        'en cours'   => 'bg-blue-100 text-blue-700',
                        default      => 'bg-slate-100 text-slate-600',
                    };
                @endphp
                <span class="inline-flex items-center {{ $sc }} text-sm font-semibold px-3 py-1 rounded-full">
                    {{ ucfirst($tache->statut) }}
                </span>
            </div>
            <div>
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">Priorité</p>
                @php
                    $pc = match($tache->priorite) {
                        'haute'   => 'bg-red-100 text-red-700',
                        'moyenne' => 'bg-orange-100 text-orange-700',
                        'basse'   => 'bg-sky-100 text-sky-700',
                        default   => 'bg-slate-100 text-slate-600',
                    };
                @endphp
                <span class="inline-flex items-center {{ $pc }} text-sm font-semibold px-3 py-1 rounded-full">
                    {{ ucfirst($tache->priorite) }}
                </span>
            </div>
            <div>
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">Date limite</p>
                <p class="font-semibold {{ $tache->estEnRetard() ? 'text-red-600' : 'text-slate-700' }}">
                    {{ $tache->date_deadline?->format('d/m/Y') ?? '—' }}
                    @if($tache->estEnRetard()) <span class="text-xs font-normal">(en retard)</span> @endif
                </p>
            </div>
            <div>
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">Technicien</p>
                <p class="font-semibold text-slate-700">{{ $tache->technicien?->user?->name ?? '—' }}</p>
                <p class="text-xs text-slate-400">{{ $tache->technicien?->specialite }}</p>
            </div>
            @if($tache->machine)
            <div>
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">Machine</p>
                <a href="{{ route('admin.machines.show', $tache->machine) }}" class="font-semibold text-brand-600 hover:underline text-sm">
                    {{ $tache->machine->nom }}
                </a>
            </div>
            @endif
            <div>
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">Créée le</p>
                <p class="text-slate-700">{{ $tache->created_at->format('d/m/Y') }}</p>
            </div>
        </div>

        @if($tache->description)
        <div class="pt-4 border-t border-slate-100">
            <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Description</p>
            <p class="text-slate-600 text-sm leading-relaxed">{{ $tache->description }}</p>
        </div>
        @endif
    </div>

    @php
        $rapportFinal = $tache->rapports->sortByDesc('created_at')->first();
    @endphp

    @if($rapportFinal)
        <div class="bg-white rounded-2xl border border-emerald-200 shadow-sm p-8">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <p class="text-xs font-black text-emerald-600 uppercase tracking-widest">Rapport final associé</p>
                    <h2 class="text-xl font-extrabold text-slate-900 mt-1">PDF généré automatiquement</h2>
                    <p class="text-sm text-slate-500 mt-2">
                        Généré {{ $rapportFinal->pdf_generated_at?->format('d/m/Y H:i') ?? $rapportFinal->created_at->format('d/m/Y H:i') }}.
                    </p>
                </div>
                <a href="{{ route('admin.rapport.pdf', $rapportFinal) }}" class="btn-primary">Télécharger le PDF</a>
            </div>
            <div class="mt-6 border-t border-slate-100 pt-5">
                <p class="form-label">Résumé du rapport</p>
                <p class="text-sm text-slate-600 leading-relaxed whitespace-pre-line">{{ $rapportFinal->contenu }}</p>
            </div>
        </div>
    @elseif($tache->statut === 'terminé')
        <div class="bg-amber-50 rounded-2xl border border-amber-200 p-6 text-amber-800 font-bold">
            Cette tâche est terminée, mais aucun rapport final n'est encore associé.
        </div>
    @endif

</div>
@endsection
