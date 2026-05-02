@extends('layouts.admin')

@section('header', 'Gestion des Machines')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div>
        <h3 class="text-xl font-bold text-slate-900">Liste des machines</h3>
        <p class="text-sm text-slate-500 mt-1">Inventaire, localisation et état opérationnel.</p>
    </div>
    <button onclick="document.getElementById('modal-add').classList.remove('hidden')" class="toolbar-button">
        <span>+</span>
        <span>Ajouter une machine</span>
    </button>
</div>

@if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
        {{ session('success') }}
    </div>
@endif

<div class="bg-white rounded-xl shadow-sm overflow-hidden border border-gray-100">
    <table class="w-full text-left">
        <thead class="bg-gray-50 border-b border-gray-100">
            <tr>
                <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-wider">Nom</th>
                <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-wider">Localisation</th>
                <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-wider">État</th>
                <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-wider">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @foreach($machines as $machine)
            <tr class="hover:bg-slate-50 transition-colors">
                <td class="px-6 py-4">
                    <div class="font-bold text-gray-800">{{ $machine->nom }}</div>
                    <div class="text-xs text-slate-400 mt-0.5">{{ $machine->numero_serie ?? 'Sans numéro de série' }}</div>
                </td>
                <td class="px-6 py-4 text-gray-500">{{ $machine->localisation }}</td>
                <td class="px-6 py-4">
                    @php
                        $etatClass = match($machine->etat) {
                            'ACTIF' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                            'PANNE' => 'bg-red-50 text-red-700 border-red-200',
                            'MAINTENANCE' => 'bg-amber-50 text-amber-700 border-amber-200',
                            default => 'bg-slate-50 text-slate-700 border-slate-200',
                        };
                    @endphp
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold border {{ $etatClass }}">
                        ● {{ ucfirst($machine->etat) }}
                    </span>
                </td>
                <td class="px-6 py-4">
                    <div class="flex items-center gap-3">
                        <a href="{{ route('admin.machines.show', $machine) }}" class="text-sm font-bold text-slate-500 hover:text-slate-900">Voir</a>
                        <a href="{{ route('admin.machines.edit', $machine) }}" class="text-sm font-bold text-brand-600 hover:text-brand-700">Modifier</a>
                    <form action="{{ route('admin.machines.destroy', $machine) }}" method="POST" onsubmit="return confirm('Supprimer ?')">
                        @csrf @method('DELETE')
                        <button class="text-sm text-red-500 hover:text-red-700 font-bold">Supprimer</button>
                    </form>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

@if($machines->hasPages())
    <div class="pagination-shell mt-6">
        {{ $machines->links() }}
    </div>
@endif

<!-- Modal Ajout -->
<div id="modal-add" class="modal-backdrop fixed inset-0 hidden z-50 flex items-center justify-center p-4" onclick="this.classList.add('hidden')">
    <div class="modal-card w-full max-w-2xl" onclick="event.stopPropagation()">
        <div class="flex items-start justify-between gap-6 border-b border-slate-200 px-8 py-6">
            <div>
                <p class="modal-kicker">Inventaire machine</p>
                <h3 class="modal-title mt-1">Ajouter une machine</h3>
                <p class="modal-subtitle mt-2">Renseignez les informations qui permettront de suivre l'équipement dans l'atelier.</p>
            </div>
            <button type="button" onclick="document.getElementById('modal-add').classList.add('hidden')" class="modal-close" aria-label="Fermer">×</button>
        </div>

        <form action="{{ route('admin.machines.store') }}" method="POST">
            @csrf
            <div class="modal-scroll px-8 py-6 space-y-5">
                <div>
                    <label class="form-label">Nom du modèle</label>
                    <input type="text" name="nom" value="{{ old('nom') }}" required class="form-control" placeholder="Ex : Presse hydraulique 50T">
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="form-label">Localisation</label>
                        <input type="text" name="localisation" value="{{ old('localisation') }}" required class="form-control" placeholder="Zone A - Secteur 1">
                    </div>
                    <div>
                        <label class="form-label">Numéro de série</label>
                        <input type="text" name="numero_serie" value="{{ old('numero_serie') }}" class="form-control" placeholder="PH-2026-001">
                    </div>
                </div>
                <div>
                    <label class="form-label">État initial</label>
                    <select name="etat" required class="form-control">
                        <option value="ACTIF" @selected(old('etat') === 'ACTIF')>Actif</option>
                        <option value="PANNE" @selected(old('etat') === 'PANNE')>Panne</option>
                        <option value="MAINTENANCE" @selected(old('etat') === 'MAINTENANCE')>Maintenance</option>
                    </select>
                </div>
                <div>
                    <label class="form-label">Description</label>
                    <textarea name="description" rows="3" class="form-control" placeholder="Informations utiles, contraintes, historique...">{{ old('description') }}</textarea>
                </div>
            </div>
            <div class="flex justify-end gap-3 border-t border-slate-200 bg-slate-50/80 px-8 py-5">
                <button type="button" onclick="document.getElementById('modal-add').classList.add('hidden')" class="btn-secondary">Annuler</button>
                <button type="submit" class="btn-primary">Enregistrer la machine</button>
            </div>
        </form>
    </div>
</div>
@endsection
