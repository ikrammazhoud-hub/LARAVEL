@extends('layouts.technicien')

@section('header', 'Tableau de bord')
@section('subheader', 'Vos priorités, votre avancement et les prochaines interventions à traiter.')

@section('content')
@php
    $mainTask = $nextTaches->first();
@endphp

<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6 mb-8">
    <div class="metric-card">
        <p class="metric-label">Tâches totales</p>
        <div class="mt-4 flex items-end justify-between">
            <span class="metric-value">{{ $stats['total'] }}</span>
            <span class="status-dot bg-slate-400"></span>
        </div>
    </div>
    <div class="metric-card">
        <p class="metric-label">En cours</p>
        <div class="mt-4 flex items-end justify-between">
            <span class="metric-value text-blue-600">{{ $stats['en_cours'] }}</span>
            <span class="status-dot bg-blue-500"></span>
        </div>
    </div>
    <div class="metric-card">
        <p class="metric-label">Terminées</p>
        <div class="mt-4 flex items-end justify-between">
            <span class="metric-value text-emerald-600">{{ $stats['terminees'] }}</span>
            <span class="status-dot bg-emerald-500"></span>
        </div>
    </div>
    <div class="metric-card">
        <p class="metric-label">En retard</p>
        <div class="mt-4 flex items-end justify-between">
            <span class="metric-value text-red-600">{{ $stats['en_retard'] }}</span>
            <span class="status-dot bg-red-500"></span>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 xl:grid-cols-3 gap-8">
    <section class="xl:col-span-2 space-y-8">
        <div class="glass-panel rounded-lg border border-white/70 overflow-hidden">
            <div class="p-7 border-b border-slate-200/70 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                <div>
                    <p class="modal-kicker">Mission prioritaire</p>
                    <h3 class="text-2xl font-extrabold text-slate-900 mt-1">
                        {{ $mainTask?->titre ?? 'Aucune intervention active' }}
                    </h3>
                    <p class="text-sm text-slate-500 mt-2 max-w-2xl">
                        @if($mainTask)
                            {{ $mainTask->description ?: 'Aucune consigne détaillée pour cette tâche.' }}
                        @else
                            Votre planning est propre. Les prochaines tâches apparaîtront ici dès leur assignation.
                        @endif
                    </p>
                </div>
                <a href="{{ $mainTask ? route('technicien.taches.index', ['focus' => $mainTask->id]) . '#task-' . $mainTask->id : route('technicien.taches.index') }}" class="btn-primary shrink-0">
                    Ouvrir mes tâches
                </a>
            </div>

            @if($mainTask)
                <div class="grid grid-cols-1 md:grid-cols-3 divide-y md:divide-y-0 md:divide-x divide-slate-200/70">
                    <div class="p-6">
                        <p class="metric-label">Machine</p>
                        <p class="mt-2 font-extrabold text-slate-900">{{ $mainTask->machine?->nom ?? 'Non liée' }}</p>
                        <p class="text-sm text-slate-500 mt-1">{{ $mainTask->machine?->localisation ?? 'Aucune localisation' }}</p>
                    </div>
                    <div class="p-6">
                        <p class="metric-label">Échéance</p>
                        <p class="mt-2 font-extrabold {{ $mainTask->estEnRetard() ? 'text-red-600' : 'text-slate-900' }}">
                            {{ $mainTask->date_deadline?->format('d/m/Y') }}
                        </p>
                        <p class="text-sm text-slate-500 mt-1">{{ $mainTask->estEnRetard() ? 'Action urgente' : $mainTask->date_deadline?->diffForHumans() }}</p>
                    </div>
                    <div class="p-6">
                        <p class="metric-label">Statut</p>
                        <div class="mt-2 flex flex-wrap gap-2">
                            <span class="task-pill {{ $mainTask->statut === 'en cours' ? 'task-pill-blue' : 'task-pill-slate' }}">{{ ucfirst($mainTask->statut) }}</span>
                            <span class="task-pill {{ $mainTask->priorite === 'haute' ? 'task-pill-red' : ($mainTask->priorite === 'moyenne' ? 'task-pill-amber' : 'task-pill-emerald') }}">{{ ucfirst($mainTask->priorite) }}</span>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <div class="glass-panel rounded-lg border border-white/70 overflow-hidden">
            <div class="p-6 border-b border-slate-200/70 flex items-center justify-between">
                <div>
                    <h3 class="text-xl font-extrabold text-slate-900">File de travail</h3>
                    <p class="text-sm text-slate-500 mt-1">Les interventions ouvertes, triées par urgence.</p>
                </div>
                <a href="{{ route('technicien.taches.index') }}" class="text-sm font-extrabold text-brand-600 hover:text-brand-700">Tout voir</a>
            </div>

            <div class="divide-y divide-slate-200/70">
                @forelse($nextTaches as $tache)
                    <a href="{{ route('technicien.taches.index', ['focus' => $tache->id]) }}#task-{{ $tache->id }}" class="block p-5 hover:bg-white/70 transition">
                        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                            <div>
                                <div class="flex items-center gap-2 mb-2">
                                    <span class="task-pill {{ $tache->priorite === 'haute' ? 'task-pill-red' : ($tache->priorite === 'moyenne' ? 'task-pill-amber' : 'task-pill-emerald') }}">{{ ucfirst($tache->priorite) }}</span>
                                    <span class="task-pill {{ $tache->statut === 'en cours' ? 'task-pill-blue' : 'task-pill-slate' }}">{{ ucfirst($tache->statut) }}</span>
                                </div>
                                <p class="font-extrabold text-slate-900">{{ $tache->titre }}</p>
                                <p class="text-sm text-slate-500 mt-1">{{ $tache->machine?->nom ?? 'Aucune machine liée' }}</p>
                            </div>
                            <div class="text-sm font-bold {{ $tache->estEnRetard() ? 'text-red-600' : 'text-slate-500' }}">
                                {{ $tache->date_deadline?->format('d/m/Y') }}
                            </div>
                        </div>
                    </a>
                @empty
                    <div class="p-10 text-center">
                        <p class="text-lg font-extrabold text-slate-700">Aucune tâche ouverte</p>
                        <p class="text-sm text-slate-500 mt-2">Les tâches en attente et en cours s'afficheront ici.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    <aside class="space-y-8">
        <div class="glass-panel rounded-lg border border-white/70 p-7">
            <div class="flex items-center justify-between mb-5">
                <div>
                    <p class="modal-kicker">Progression</p>
                    <h3 class="text-xl font-extrabold text-slate-900 mt-1">{{ $stats['completion'] }}% clôturé</h3>
                </div>
                <span class="w-12 h-12 rounded-xl bg-brand-50 text-brand-700 border border-brand-100 flex items-center justify-center font-black">
                    {{ $stats['completion'] }}
                </span>
            </div>
            <div class="h-3 rounded-full bg-slate-100 overflow-hidden">
                <div class="h-full rounded-full bg-gradient-to-r from-brand-500 to-indigo-500" style="width: {{ $stats['completion'] }}%"></div>
            </div>
            <div class="grid grid-cols-2 gap-3 mt-6">
                <a href="{{ route('technicien.taches.index', ['statut' => 'pending']) }}" class="rounded-lg bg-white/70 border border-slate-200 p-4 hover:border-brand-200 transition">
                    <p class="text-xs font-extrabold text-slate-400 uppercase tracking-wider">En attente</p>
                    <p class="text-2xl font-black text-slate-900 mt-1">{{ $stats['en_attente'] }}</p>
                </a>
                <a href="{{ route('technicien.taches.index', ['statut' => 'active']) }}" class="rounded-lg bg-white/70 border border-slate-200 p-4 hover:border-brand-200 transition">
                    <p class="text-xs font-extrabold text-slate-400 uppercase tracking-wider">En cours</p>
                    <p class="text-2xl font-black text-blue-600 mt-1">{{ $stats['en_cours'] }}</p>
                </a>
            </div>
        </div>

        <div class="glass-panel rounded-lg border border-white/70 overflow-hidden">
            <div class="p-6 border-b border-slate-200/70">
                <h3 class="text-xl font-extrabold text-slate-900">Dernières tâches clôturées</h3>
                <p class="text-sm text-slate-500 mt-1">Votre historique récent.</p>
            </div>
            <div class="divide-y divide-slate-200/70">
                @forelse($recentCompleted as $tache)
                    <div class="p-5">
                        <p class="font-extrabold text-slate-900">{{ $tache->titre }}</p>
                        <p class="text-sm text-slate-500 mt-1">{{ $tache->machine?->nom ?? 'Aucune machine liée' }}</p>
                        <p class="text-xs font-bold text-emerald-600 mt-2">Clôturée {{ $tache->updated_at->diffForHumans() }}</p>
                    </div>
                @empty
                    <div class="p-8 text-center">
                        <p class="font-bold text-slate-500">Aucune tâche clôturée pour le moment.</p>
                    </div>
                @endforelse
            </div>
            <div class="p-5 bg-slate-50/70 border-t border-slate-200/70">
                <a href="{{ route('technicien.taches.index', ['statut' => 'done']) }}" class="btn-secondary w-full">Voir l'historique</a>
            </div>
        </div>
    </aside>
</div>
@endsection
