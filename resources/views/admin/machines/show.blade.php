@extends('layouts.admin')

@section('title', $machine->nom)

@section('content')
<div class="max-w-3xl mx-auto space-y-6">

    {{-- En-tête --}}
    <div class="flex items-start justify-between">
        <div>
            <a href="{{ route('admin.machines.index') }}" class="inline-flex items-center gap-1 text-sm text-slate-500 hover:text-brand-600 transition-colors mb-2">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                Machines
            </a>
            <h1 class="text-2xl font-bold text-slate-800">{{ $machine->nom }}</h1>
            <p class="text-slate-400 text-sm mt-0.5">{{ $machine->numero_serie ?? 'Sans numéro de série' }}</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('admin.machines.edit', $machine) }}"
               class="inline-flex items-center gap-2 bg-amber-100 hover:bg-amber-200 text-amber-800 font-semibold px-4 py-2 rounded-xl text-sm transition-all">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                Modifier
            </a>
            <form method="POST" action="{{ route('admin.machines.destroy', $machine) }}"
                  onsubmit="return confirm('Supprimer cette machine ?')">
                @csrf @method('DELETE')
                <button type="submit" class="inline-flex items-center gap-2 bg-red-100 hover:bg-red-200 text-red-700 font-semibold px-4 py-2 rounded-xl text-sm transition-all">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    Supprimer
                </button>
            </form>
        </div>
    </div>

    {{-- Fiche machine --}}
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-8">
        <div class="grid grid-cols-2 sm:grid-cols-3 gap-6">
            <div>
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">État</p>
                @php
                    $badge = match($machine->etat) {
                        'ACTIF'       => 'bg-emerald-100 text-emerald-700',
                        'PANNE'       => 'bg-red-100 text-red-700',
                        'MAINTENANCE' => 'bg-amber-100 text-amber-700',
                        default       => 'bg-slate-100 text-slate-600',
                    };
                    $dot = match($machine->etat) {
                        'ACTIF'       => 'bg-emerald-500',
                        'PANNE'       => 'bg-red-500',
                        'MAINTENANCE' => 'bg-amber-500',
                        default       => 'bg-slate-400',
                    };
                @endphp
                <span class="inline-flex items-center gap-1.5 {{ $badge }} text-sm font-semibold px-3 py-1 rounded-full">
                    <span class="w-2 h-2 {{ $dot }} rounded-full {{ $machine->etat === 'ACTIF' ? '' : '' }}"></span>
                    {{ $machine->etat }}
                </span>
            </div>
            <div>
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">Localisation</p>
                <p class="font-semibold text-slate-700">{{ $machine->localisation }}</p>
            </div>
            <div>
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">Enregistrée le</p>
                <p class="text-slate-700">{{ $machine->created_at->format('d/m/Y') }}</p>
            </div>
        </div>

        @if($machine->description)
        <div class="mt-6 pt-6 border-t border-slate-100">
            <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Description</p>
            <p class="text-slate-600 text-sm leading-relaxed">{{ $machine->description }}</p>
        </div>
        @endif
    </div>

    {{-- Dernières interventions --}}
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm">
        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
            <h2 class="font-bold text-slate-800">Dernières interventions</h2>
            <a href="{{ route('admin.interventions.create') }}?machine_id={{ $machine->id }}"
               class="text-brand-600 hover:text-brand-700 text-sm font-semibold transition-colors">
                + Nouvelle
            </a>
        </div>
        <div class="divide-y divide-slate-100">
            @forelse($interventions as $interv)
            <div class="px-6 py-4 flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-slate-800">{{ Str::limit($interv->description, 60) }}</p>
                    <p class="text-xs text-slate-400 mt-0.5">{{ $interv->technicien?->user?->name }} · {{ $interv->created_at->format('d/m/Y') }}</p>
                </div>
                @if($interv->statut === 'terminee')
                    <span class="text-xs bg-emerald-100 text-emerald-700 font-semibold px-2.5 py-1 rounded-full">Terminée</span>
                @else
                    <span class="text-xs bg-blue-100 text-blue-700 font-semibold px-2.5 py-1 rounded-full">En cours</span>
                @endif
            </div>
            @empty
            <div class="px-6 py-8 text-center text-slate-400 text-sm">Aucune intervention enregistrée.</div>
            @endforelse
        </div>
    </div>

</div>
@endsection
