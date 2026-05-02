@extends('layouts.admin')

@section('title', $technicien->user?->name)

@section('content')
<div class="max-w-3xl mx-auto space-y-6">

    <div class="flex items-start justify-between">
        <div>
            <a href="{{ route('admin.techniciens.index') }}" class="inline-flex items-center gap-1 text-sm text-slate-500 hover:text-brand-600 transition-colors mb-2">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                Techniciens
            </a>
            <h1 class="text-2xl font-bold text-slate-800">{{ $technicien->user?->name }}</h1>
            <p class="text-slate-400 text-sm mt-0.5">{{ $technicien->specialite }} · {{ $technicien->matricule ?? 'Sans matricule' }}</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('admin.techniciens.edit', $technicien) }}"
               class="inline-flex items-center gap-1 bg-amber-100 hover:bg-amber-200 text-amber-800 font-semibold px-4 py-2 rounded-xl text-sm transition-all">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                Modifier
            </a>
            <form method="POST" action="{{ route('admin.techniciens.destroy', $technicien) }}" onsubmit="return confirm('Supprimer ce technicien ?')">
                @csrf @method('DELETE')
                <button type="submit" class="inline-flex items-center gap-1 bg-red-100 hover:bg-red-200 text-red-700 font-semibold px-4 py-2 rounded-xl text-sm transition-all">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    Supprimer
                </button>
            </form>
        </div>
    </div>

    {{-- Fiche identité --}}
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-8">
        <div class="grid grid-cols-2 sm:grid-cols-3 gap-6">
            <div>
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">Email</p>
                <p class="text-slate-700 text-sm">{{ $technicien->user?->email }}</p>
            </div>
            <div>
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">Téléphone</p>
                <p class="text-slate-700 text-sm">{{ $technicien->telephone ?? '—' }}</p>
            </div>
            <div>
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">Disponibilité</p>
                @if($technicien->disponible)
                    <span class="inline-flex items-center gap-1 bg-emerald-100 text-emerald-700 text-xs font-semibold px-2.5 py-1 rounded-full">
                        <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full"></span> Disponible
                    </span>
                @else
                    <span class="inline-flex items-center gap-1 bg-slate-100 text-slate-600 text-xs font-semibold px-2.5 py-1 rounded-full">
                        Non disponible
                    </span>
                @endif
            </div>
        </div>
    </div>

    {{-- Statistiques rapides --}}
    <div class="grid grid-cols-3 gap-4">
        @php
            $enAttente = $technicien->taches->where('statut', 'en attente')->count();
            $enCours   = $technicien->taches->where('statut', 'en cours')->count();
            $terminees = $technicien->taches->where('statut', 'terminé')->count();
        @endphp
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5 text-center">
            <p class="text-3xl font-bold text-slate-800">{{ $enAttente }}</p>
            <p class="text-xs text-slate-400 mt-1 font-medium">En attente</p>
        </div>
        <div class="bg-white rounded-2xl border border-blue-200 shadow-sm p-5 text-center">
            <p class="text-3xl font-bold text-blue-600">{{ $enCours }}</p>
            <p class="text-xs text-slate-400 mt-1 font-medium">En cours</p>
        </div>
        <div class="bg-white rounded-2xl border border-emerald-200 shadow-sm p-5 text-center">
            <p class="text-3xl font-bold text-emerald-600">{{ $terminees }}</p>
            <p class="text-xs text-slate-400 mt-1 font-medium">Terminées</p>
        </div>
    </div>

    {{-- Tâches récentes --}}
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm">
        <div class="px-6 py-4 border-b border-slate-100">
            <h2 class="font-bold text-slate-800">Tâches assignées</h2>
        </div>
        <div class="divide-y divide-slate-100">
            @forelse($technicien->taches->take(8) as $t)
            <div class="px-6 py-3 flex items-center justify-between">
                <div>
                    <a href="{{ route('admin.taches.show', $t) }}" class="text-sm font-medium text-slate-800 hover:text-brand-600 transition-colors">
                        {{ $t->titre }}
                    </a>
                    @if($t->date_deadline)
                    <p class="text-xs text-slate-400 mt-0.5">Échéance : {{ $t->date_deadline->format('d/m/Y') }}</p>
                    @endif
                </div>
                @php
                    $sc = match($t->statut) {
                        'terminé'  => 'bg-emerald-100 text-emerald-700',
                        'en cours' => 'bg-blue-100 text-blue-700',
                        default    => 'bg-slate-100 text-slate-600',
                    };
                @endphp
                <span class="text-xs {{ $sc }} font-semibold px-2.5 py-1 rounded-full shrink-0">{{ ucfirst($t->statut) }}</span>
            </div>
            @empty
            <div class="px-6 py-8 text-center text-slate-400 text-sm">Aucune tâche assignée.</div>
            @endforelse
        </div>
    </div>

</div>
@endsection
