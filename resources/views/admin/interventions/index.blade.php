@extends('layouts.admin')

@section('title', 'Interventions')

@section('content')
<div class="space-y-6">

    {{-- En-tête --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Interventions</h1>
            <p class="text-slate-500 text-sm mt-1">Historique de toutes les interventions de maintenance.</p>
        </div>
        <a href="{{ route('admin.interventions.create') }}"
           class="inline-flex items-center gap-2 bg-brand-600 hover:bg-brand-700 text-white font-semibold px-5 py-2.5 rounded-xl transition-all shadow-sm">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
            </svg>
            Nouvelle intervention
        </a>
    </div>

    {{-- Alertes --}}
    @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl text-sm flex items-center gap-2">
            <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            {{ session('success') }}
        </div>
    @endif

    {{-- Tableau --}}
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 border-b border-slate-200">
                <tr>
                    <th class="text-left px-6 py-3 font-semibold text-slate-600">#</th>
                    <th class="text-left px-6 py-3 font-semibold text-slate-600">Machine</th>
                    <th class="text-left px-6 py-3 font-semibold text-slate-600">Technicien</th>
                    <th class="text-left px-6 py-3 font-semibold text-slate-600">Statut</th>
                    <th class="text-left px-6 py-3 font-semibold text-slate-600">Date début</th>
                    <th class="text-left px-6 py-3 font-semibold text-slate-600">Date fin</th>
                    <th class="text-right px-6 py-3 font-semibold text-slate-600">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($interventions as $intervention)
                <tr class="hover:bg-slate-50 transition-colors">
                    <td class="px-6 py-4 text-slate-400 font-mono text-xs">#{{ $intervention->id }}</td>
                    <td class="px-6 py-4 font-medium text-slate-800">{{ $intervention->machine?->nom ?? '—' }}</td>
                    <td class="px-6 py-4 text-slate-600">{{ $intervention->technicien?->user?->name ?? '—' }}</td>
                    <td class="px-6 py-4">
                        @if($intervention->statut === 'terminee')
                            <span class="inline-flex items-center gap-1 bg-emerald-100 text-emerald-700 text-xs font-semibold px-2.5 py-1 rounded-full">
                                <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full"></span> Terminée
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1 bg-blue-100 text-blue-700 text-xs font-semibold px-2.5 py-1 rounded-full">
                                <span class="w-1.5 h-1.5 bg-blue-500 rounded-full animate-pulse"></span> En cours
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-slate-500">{{ $intervention->date_debut?->format('d/m/Y H:i') ?? '—' }}</td>
                    <td class="px-6 py-4 text-slate-500">{{ $intervention->date_fin?->format('d/m/Y H:i') ?? '—' }}</td>
                    <td class="px-6 py-4">
                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('admin.interventions.show', $intervention) }}"
                               class="text-slate-500 hover:text-brand-600 transition-colors" title="Voir">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            </a>
                            <a href="{{ route('admin.interventions.edit', $intervention) }}"
                               class="text-slate-500 hover:text-amber-600 transition-colors" title="Modifier">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            </a>
                            <form method="POST" action="{{ route('admin.interventions.destroy', $intervention) }}"
                                  onsubmit="return confirm('Supprimer cette intervention ?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-slate-500 hover:text-red-600 transition-colors" title="Supprimer">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-12 text-center text-slate-400">
                        <svg class="w-10 h-10 mx-auto mb-2 opacity-40" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                        Aucune intervention enregistrée.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>

        @if($interventions->hasPages())
        <div class="px-6 py-4 border-t border-slate-100">
            {{ $interventions->links() }}
        </div>
        @endif
    </div>

</div>
@endsection
