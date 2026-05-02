@extends('layouts.admin')

@section('header', 'Aperçu Global')

@section('content')
<div class="space-y-8">
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6">
        <a href="{{ route('admin.taches.index') }}" class="metric-card hover:-translate-y-1 transition">
            <p class="metric-label">Tâches suivies</p>
            <div class="mt-4 flex items-end justify-between">
                <span class="metric-value">{{ $tachesTotal }}</span>
                <span class="task-pill task-pill-blue">{{ $tauxCompletion }}% clôturé</span>
            </div>
        </a>
        <a href="{{ route('admin.taches.index') }}" class="metric-card hover:-translate-y-1 transition">
            <p class="metric-label">En cours</p>
            <div class="mt-4 flex items-end justify-between">
                <span class="metric-value text-blue-600">{{ $tachesEnCours }}</span>
                <span class="status-dot bg-blue-500"></span>
            </div>
        </a>
        <a href="#rapports" class="metric-card hover:-translate-y-1 transition">
            <p class="metric-label">Rapports PDF</p>
            <div class="mt-4 flex items-end justify-between">
                <span class="metric-value text-emerald-600">{{ $rapportsPdfGeneres }}</span>
                <span class="text-xs font-extrabold text-slate-400">{{ $rapportsCount }} total</span>
            </div>
        </a>
        <a href="{{ route('admin.taches.index') }}" class="metric-card hover:-translate-y-1 transition">
            <p class="metric-label">Points à surveiller</p>
            <div class="mt-4 flex items-end justify-between">
                <span class="metric-value text-red-600">{{ $tachesEnRetard }}</span>
                <span class="text-xs font-extrabold text-slate-400">{{ $tachesSansRetour }} sans retour</span>
            </div>
        </a>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">
        <section class="xl:col-span-2 glass-panel rounded-lg border border-white/70 overflow-hidden">
            <div class="p-6 border-b border-slate-200/70 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <p class="modal-kicker">Suivi continu</p>
                    <h3 class="text-xl font-extrabold text-slate-900 mt-1">État des tâches en temps réel</h3>
                    <p class="text-sm text-slate-500 mt-1">Vérifiez l'avancement et ouvrez le rapport final dès qu'une tâche est clôturée.</p>
                </div>
                <a href="{{ route('admin.taches.index') }}" class="btn-secondary">Toutes les tâches</a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="bg-slate-900 text-white">
                        <tr>
                            <th class="px-6 py-4 text-xs font-black uppercase tracking-widest">Tâche</th>
                            <th class="px-6 py-4 text-xs font-black uppercase tracking-widest">Technicien</th>
                            <th class="px-6 py-4 text-xs font-black uppercase tracking-widest">État</th>
                            <th class="px-6 py-4 text-xs font-black uppercase tracking-widest">Rapport</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 bg-white/70">
                        @forelse($suiviTaches as $tache)
                            @php
                                $rapport = $tache->rapports->sortByDesc('created_at')->first();
                                $statusClass = match($tache->statut) {
                                    'en cours' => 'task-pill-blue',
                                    'terminé' => 'task-pill-emerald',
                                    default => 'task-pill-slate',
                                };
                            @endphp
                            <tr class="hover:bg-slate-50 transition">
                                <td class="px-6 py-4">
                                    <a href="{{ route('admin.taches.show', $tache) }}" class="font-extrabold text-slate-900 hover:text-brand-700">{{ $tache->titre }}</a>
                                    <p class="text-xs text-slate-500 mt-1">{{ $tache->machine?->nom ?? 'Aucune machine liée' }}</p>
                                </td>
                                <td class="px-6 py-4 text-sm font-bold text-slate-600">{{ $tache->technicien?->user?->name ?? '—' }}</td>
                                <td class="px-6 py-4"><span class="task-pill {{ $statusClass }}">{{ ucfirst($tache->statut) }}</span></td>
                                <td class="px-6 py-4">
                                    @if($rapport)
                                        <a href="{{ route('admin.rapport.pdf', $rapport) }}" class="text-sm font-extrabold text-brand-600 hover:text-brand-700">PDF final</a>
                                    @elseif($tache->statut === 'terminé')
                                        <span class="task-pill task-pill-amber">À générer</span>
                                    @else
                                        <span class="text-sm font-bold text-slate-400">En attente</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="px-6 py-12 text-center font-bold text-slate-400">Aucune tâche à suivre.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>

        <aside class="glass-panel rounded-lg border border-white/70 p-6">
            <p class="modal-kicker">Vue globale</p>
            <h3 class="text-xl font-extrabold text-slate-900 mt-1">Répartition des statuts</h3>
            <div class="h-64 mt-6"><canvas id="tasksStatusChart"></canvas></div>
        </aside>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">
        <section class="glass-panel rounded-lg border border-white/70 p-6">
            <p class="modal-kicker">Priorités</p>
            <h3 class="text-xl font-extrabold text-slate-900 mt-1">Charge par urgence</h3>
            <div class="h-64 mt-6"><canvas id="tasksPriorityChart"></canvas></div>
        </section>

        <section id="rapports" class="xl:col-span-2 glass-panel rounded-lg border border-white/70 overflow-hidden">
            <div class="p-6 border-b border-slate-200/70">
                <p class="modal-kicker">Rapports finaux</p>
                <h3 class="text-xl font-extrabold text-slate-900 mt-1">Derniers rapports générés</h3>
            </div>
            <div class="divide-y divide-slate-200/70 bg-white/60">
                @forelse($rapportsRecents as $rapport)
                    <div class="p-5 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                        <div>
                            <p class="font-extrabold text-slate-900">{{ $rapport->intervention?->tache?->titre ?? 'Rapport intervention #' . $rapport->intervention_id }}</p>
                            <p class="text-sm text-slate-500 mt-1">
                                {{ $rapport->intervention?->technicien?->user?->name ?? 'Technicien inconnu' }}
                                · {{ $rapport->intervention?->machine?->nom ?? 'Machine inconnue' }}
                            </p>
                            <p class="text-xs font-bold text-slate-400 mt-1">
                                Généré {{ $rapport->pdf_generated_at?->diffForHumans() ?? $rapport->created_at->diffForHumans() }}
                            </p>
                        </div>
                        <a href="{{ route('admin.rapport.pdf', $rapport) }}" class="btn-primary">Télécharger PDF</a>
                    </div>
                @empty
                    <div class="p-10 text-center font-bold text-slate-400">Aucun rapport final généré pour le moment.</div>
                @endforelse
            </div>
        </section>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const status = @json($chartTachesStatuts);
        const priorities = @json($chartTachesPriorites);

        new Chart(document.getElementById('tasksStatusChart'), {
            type: 'doughnut',
            data: {
                labels: status.labels,
                datasets: [{ data: status.data, backgroundColor: ['#94a3b8', '#3b82f6', '#10b981'], borderWidth: 0 }]
            },
            options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { position: 'bottom' } } }
        });

        new Chart(document.getElementById('tasksPriorityChart'), {
            type: 'bar',
            data: {
                labels: priorities.labels,
                datasets: [{ label: 'Tâches', data: priorities.data, backgroundColor: ['#14b8a6', '#f59e0b', '#ef4444'], borderRadius: 8 }]
            },
            options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true, ticks: { precision: 0 } } } }
        });
    });
</script>
@endsection
