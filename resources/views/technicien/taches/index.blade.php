@extends('layouts.technicien')

@section('header', 'Mes tâches')
@section('subheader', 'Consultez vos missions actives, votre historique et vos rapports avec pagination.')

@section('content')
@php
    $filterItems = [
        ['key' => 'all', 'label' => 'Toutes', 'count' => $counts['all']],
        ['key' => 'pending', 'label' => 'En attente', 'count' => $counts['pending']],
        ['key' => 'active', 'label' => 'En cours', 'count' => $counts['active']],
        ['key' => 'done', 'label' => 'Terminées', 'count' => $counts['done']],
        ['key' => 'late', 'label' => 'En retard', 'count' => $counts['late']],
    ];
@endphp

<div class="mb-8 glass-panel rounded-lg border border-white/70 p-6">
    <form method="GET" action="{{ route('technicien.taches.index') }}" class="grid grid-cols-1 xl:grid-cols-[1fr_auto] gap-5">
        <div>
            <label for="q" class="form-label">Recherche</label>
            <input id="q" type="search" name="q" value="{{ $search }}" class="form-control" placeholder="Rechercher par titre, consigne ou machine">
        </div>
        <div class="flex items-end gap-3">
            <input type="hidden" name="statut" value="{{ $selectedStatus }}">
            <button type="submit" class="btn-primary">Rechercher</button>
            <a href="{{ route('technicien.taches.index') }}" class="btn-secondary">Réinitialiser</a>
        </div>
    </form>

    <div class="mt-6 flex flex-wrap gap-2">
        @foreach($filterItems as $item)
            @php
                $active = $selectedStatus === $item['key'] || ($selectedStatus === 'termine' && $item['key'] === 'done');
            @endphp
            <a href="{{ route('technicien.taches.index', array_filter(['statut' => $item['key'] === 'all' ? null : $item['key'], 'q' => $search ?: null])) }}" class="filter-chip {{ $active || ($selectedStatus === 'all' && $item['key'] === 'all') ? 'filter-chip-active' : '' }}">
                <span>{{ $item['label'] }}</span>
                <span class="filter-chip-count">{{ $item['count'] }}</span>
            </a>
        @endforeach
    </div>
</div>

<div class="space-y-5">
    @forelse($taches as $tache)
        @php
            $isFocused = (string) request('focus') === (string) $tache->id;
            $statusClass = match($tache->statut) {
                'en cours' => 'task-pill-blue',
                'terminé' => 'task-pill-emerald',
                default => 'task-pill-slate',
            };
            $priorityClass = match($tache->priorite) {
                'haute' => 'task-pill-red',
                'moyenne' => 'task-pill-amber',
                default => 'task-pill-emerald',
            };
        @endphp

        <article id="task-{{ $tache->id }}" class="glass-panel rounded-lg border {{ $isFocused ? 'border-brand-300 ring-4 ring-brand-100' : 'border-white/70' }} overflow-hidden" x-data="{ showReport: {{ $isFocused && $tache->statut === 'en cours' ? 'true' : 'false' }} }">
            <div class="p-6 lg:p-7">
                <div class="flex flex-col xl:flex-row xl:items-start xl:justify-between gap-6">
                    <div class="min-w-0 flex-1">
                        <div class="flex flex-wrap items-center gap-2 mb-3">
                            <span class="task-pill {{ $statusClass }}">{{ ucfirst($tache->statut) }}</span>
                            <span class="task-pill {{ $priorityClass }}">{{ ucfirst($tache->priorite) }}</span>
                            @if($tache->estEnRetard())
                                <span class="task-pill task-pill-red">En retard</span>
                            @endif
                        </div>

                        <h3 class="text-xl font-extrabold text-slate-900">{{ $tache->titre }}</h3>
                        <p class="text-sm text-slate-500 mt-2 leading-relaxed">{{ $tache->description ?: 'Aucune consigne supplémentaire.' }}</p>

                        <div class="mt-5 grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="task-meta">
                                <span class="task-meta-label">Machine</span>
                                <strong>{{ $tache->machine?->nom ?? 'Non liée' }}</strong>
                                <span>{{ $tache->machine?->localisation ?? 'Aucune localisation' }}</span>
                            </div>
                            <div class="task-meta">
                                <span class="task-meta-label">Échéance</span>
                                <strong class="{{ $tache->estEnRetard() ? 'text-red-600' : '' }}">{{ $tache->date_deadline?->format('d/m/Y') }}</strong>
                                <span>{{ $tache->date_deadline?->diffForHumans() }}</span>
                            </div>
                            <div class="task-meta">
                                <span class="task-meta-label">Dernière mise à jour</span>
                                <strong>{{ $tache->updated_at->format('d/m/Y') }}</strong>
                                <span>{{ $tache->updated_at->diffForHumans() }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="w-full xl:w-64 space-y-3">
                        @if($tache->statut === 'en attente')
                            <form action="{{ route('technicien.taches.statut', $tache) }}" method="POST">
                                @csrf @method('PATCH')
                                <input type="hidden" name="statut" value="en cours">
                                <button class="btn-primary w-full">Démarrer</button>
                            </form>
                        @elseif($tache->statut === 'en cours')
                            <button type="button" @click="showReport = !showReport" class="btn-primary w-full">
                                Rédiger le rapport
                            </button>
                            <form action="{{ route('technicien.taches.statut', $tache) }}" method="POST">
                                @csrf @method('PATCH')
                                <input type="hidden" name="statut" value="terminé">
                                <button class="btn-secondary w-full">Marquer terminée</button>
                            </form>
                        @else
                            <div class="rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-center text-sm font-extrabold text-emerald-700">
                                Tâche clôturée
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            @if($tache->statut === 'en cours')
                <div x-show="showReport" x-cloak x-transition.opacity.duration.200ms class="border-t border-slate-200/70 bg-slate-50/80 p-6 lg:p-7">
                    <form action="{{ route('technicien.taches.rapport', $tache) }}" method="POST" class="space-y-5">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div>
                                <label class="form-label">Machine concernée</label>
                                @if($tache->machine)
                                    <input type="hidden" name="machine_id" value="{{ $tache->machine_id }}">
                                    <div class="form-control bg-slate-100 flex items-center">{{ $tache->machine->nom }}</div>
                                @else
                                    <select name="machine_id" required class="form-control">
                                        <option value="">Sélectionner une machine</option>
                                        @foreach($machines as $machine)
                                            <option value="{{ $machine->id }}" @selected(old('machine_id') == $machine->id)>{{ $machine->nom }}</option>
                                        @endforeach
                                    </select>
                                @endif
                            </div>
                            <div>
                                <label class="form-label">Statut après rapport</label>
                                <div class="form-control bg-white flex items-center text-emerald-700">La tâche sera clôturée automatiquement</div>
                            </div>
                        </div>
                        <div>
                            <label class="form-label">Compte rendu détaillé</label>
                            <textarea name="contenu" rows="4" required class="form-control" placeholder="Décrivez les actions réalisées, pièces remplacées, contrôles effectués...">{{ old('contenu') }}</textarea>
                        </div>
                        <div class="flex flex-col sm:flex-row justify-end gap-3">
                            <button type="button" @click="showReport = false" class="btn-secondary">Annuler</button>
                            <button type="submit" class="btn-primary">Soumettre et clôturer</button>
                        </div>
                    </form>
                </div>
            @endif
        </article>
    @empty
        <div class="glass-panel rounded-lg border border-white/70 p-12 text-center">
            <p class="text-xl font-extrabold text-slate-800">Aucune tâche trouvée</p>
            <p class="text-sm text-slate-500 mt-2">Essayez un autre filtre ou revenez au tableau complet.</p>
        </div>
    @endforelse
</div>

@if($taches->hasPages())
    <div class="pagination-shell mt-8">
        {{ $taches->links() }}
    </div>
@endif
@endsection
