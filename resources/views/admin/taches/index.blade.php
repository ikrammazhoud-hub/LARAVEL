@extends('layouts.admin')

@section('header', 'Gestion des Tâches')

@section('content')
<div class="mb-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
    <div>
        <h3 class="text-2xl font-black text-slate-900">Affectations & Planning</h3>
        <p class="text-slate-500 font-medium">Assignez les tâches de maintenance aux techniciens disponibles.</p>
    </div>
    <button onclick="document.getElementById('modal-assign').classList.remove('hidden')" class="toolbar-button">
        <span>+</span>
        <span>Nouvelle tâche</span>
    </button>
</div>

@if(session('success'))
    <div class="bg-emerald-50 text-emerald-700 p-5 rounded-2xl border border-emerald-100 mb-8 font-bold animate-bounce">
        🚀 {{ session('success') }}
    </div>
@endif

@if($errors->any())
    <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl mb-6 font-medium shadow-sm">
        ⚠️ Erreur : 
        <ul class="list-disc ml-5 mt-1">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="bg-white rounded-lg shadow-xl border border-gray-100 overflow-hidden">
    <table class="w-full text-left">
        <thead class="bg-gray-900 text-white">
            <tr>
                <th class="px-8 py-5 text-sm font-bold uppercase tracking-widest">Sujet / Description</th>
                <th class="px-8 py-5 text-sm font-bold uppercase tracking-widest">Assigné à</th>
                <th class="px-8 py-5 text-sm font-bold uppercase tracking-widest">Priorité</th>
                <th class="px-8 py-5 text-sm font-bold uppercase tracking-widest">Statut</th>
                <th class="px-8 py-5 text-sm font-bold uppercase tracking-widest">Deadline</th>
                <th class="px-8 py-5 text-sm font-bold uppercase tracking-widest text-right">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100 text-gray-700 font-semibold">
            @foreach($taches as $tache)
            <tr class="hover:bg-slate-50 transition">
                <td class="px-8 py-6 max-w-xs">
                    <div class="font-black text-gray-900">{{ $tache->titre }}</div>
                    <div class="text-xs text-gray-400 mt-1 line-clamp-1 italic">{{ $tache->description }}</div>
                </td>
                <td class="px-8 py-6">
                    <div class="flex items-center gap-2">
                        <span class="w-8 h-8 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center text-xs font-black">{{ substr($tache->technicien->user->name, 0, 1) }}</span>
                        {{ $tache->technicien->user->name }}
                    </div>
                </td>
                <td class="px-8 py-6">
                    <span class="px-4 py-1.5 rounded-full text-xs font-black uppercase tracking-widest shadow-sm
                        {{ $tache->priorite === 'haute' ? 'bg-red-500 text-white' : ($tache->priorite === 'moyenne' ? 'bg-orange-400 text-white' : 'bg-gray-100 text-gray-700') }}">
                        {{ $tache->priorite }}
                    </span>
                </td>
                <td class="px-8 py-6">
                    <span class="task-pill {{ $tache->statut === 'terminé' ? 'task-pill-emerald' : ($tache->statut === 'en cours' ? 'task-pill-blue' : 'task-pill-slate') }}">
                        {{ ucfirst($tache->statut) }}
                    </span>
                </td>
                <td class="px-8 py-6 text-indigo-600">
                    📅 {{ $tache->date_deadline->format('d/m/Y') }}
                </td>
                <td class="px-8 py-6 text-right">
                    @php $rapport = $tache->rapports->sortByDesc('created_at')->first(); @endphp
                    <div class="flex items-center justify-end gap-3">
                        <a href="{{ route('admin.taches.show', $tache) }}" class="text-xs font-black text-slate-400 hover:text-slate-900 uppercase tracking-widest transition">Voir</a>
                        @if($rapport)
                            <a href="{{ route('admin.rapport.pdf', $rapport) }}" class="text-xs font-black text-emerald-600 hover:text-emerald-700 uppercase tracking-widest transition">Rapport</a>
                        @endif
                        <a href="{{ route('admin.taches.edit', $tache) }}" class="text-xs font-black text-brand-600 hover:text-brand-700 uppercase tracking-widest transition">Modifier</a>
                    <form action="{{ route('admin.taches.destroy', $tache) }}" method="POST" class="inline">
                        @csrf @method('DELETE')
                        <button class="text-xs font-black text-red-500 hover:text-red-700 uppercase tracking-widest transition">Retirer</button>
                    </form>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

@if($taches->hasPages())
    <div class="pagination-shell mt-6">
        {{ $taches->links() }}
    </div>
@endif

<!-- Modal Assign -->
<div id="modal-assign" class="modal-backdrop fixed inset-0 hidden z-50 flex items-center justify-center p-4" onclick="this.classList.add('hidden')">
    <div class="modal-card w-full max-w-3xl" onclick="event.stopPropagation()">
        <div class="flex items-start justify-between gap-6 border-b border-slate-200 px-8 py-6">
            <div>
                <p class="modal-kicker">Planification</p>
                <h3 class="modal-title mt-1">Assigner une tâche</h3>
                <p class="modal-subtitle mt-2">Définissez l'intervention attendue, son responsable et son niveau de priorité.</p>
            </div>
            <button type="button" onclick="document.getElementById('modal-assign').classList.add('hidden')" class="modal-close" aria-label="Fermer">×</button>
        </div>

        <form action="{{ route('admin.taches.store') }}" method="POST">
            @csrf
            <div class="modal-scroll px-8 py-6 space-y-5">
                <div>
                    <label class="form-label">Titre de l'opération</label>
                    <input type="text" name="titre" value="{{ old('titre') }}" required class="form-control" placeholder="Ex : Vidange presse hydraulique">
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                    <label class="form-label">Technicien responsable</label>
                    <select name="technicien_id" required class="form-control">
                        @foreach($techniciens as $tech)
                            <option value="{{ $tech->id }}" @selected(old('technicien_id') == $tech->id)>{{ $tech->user->name }} ({{ $tech->specialite }})</option>
                        @endforeach
                    </select>
                    </div>
                    <div>
                        <label class="form-label">Machine concernée</label>
                        <select name="machine_id" class="form-control">
                            <option value="">Aucune machine liée</option>
                            @foreach($machines as $machine)
                                <option value="{{ $machine->id }}" @selected(old('machine_id') == $machine->id)>{{ $machine->nom }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="form-label">Priorité</label>
                        <select name="priorite" required class="form-control">
                            <option value="basse" @selected(old('priorite') === 'basse')>Basse</option>
                            <option value="moyenne" @selected(old('priorite', 'moyenne') === 'moyenne')>Moyenne</option>
                            <option value="haute" @selected(old('priorite') === 'haute')>Haute</option>
                        </select>
                    </div>
                    <div>
                        <label class="form-label">Échéance</label>
                        <input type="date" name="date_deadline" value="{{ old('date_deadline') }}" min="{{ date('Y-m-d') }}" required class="form-control">
                    </div>
                </div>
                <div>
                    <label class="form-label">Consignes spécifiques</label>
                    <textarea name="description" rows="3" class="form-control" placeholder="Ajoutez les contrôles à effectuer, pièces à prévoir, contraintes...">{{ old('description') }}</textarea>
                </div>
            </div>
            <div class="flex justify-end gap-3 border-t border-slate-200 bg-slate-50/80 px-8 py-5">
                <button type="button" onclick="document.getElementById('modal-assign').classList.add('hidden')" class="btn-secondary">Annuler</button>
                <button type="submit" class="btn-primary">Assigner la tâche</button>
            </div>
        </form>
    </div>
</div>
@endsection
