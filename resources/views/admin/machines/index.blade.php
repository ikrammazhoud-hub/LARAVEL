@extends('layouts.admin')

@section('header', 'Gestion des Machines')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <h3 class="text-xl font-bold">Liste des Machines</h3>
    <button onclick="document.getElementById('modal-add').classList.remove('hidden')" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition font-bold">+ Ajouter une Machine</button>
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
            <tr>
                <td class="px-6 py-4 font-bold text-gray-800">{{ $machine->nom }}</td>
                <td class="px-6 py-4 text-gray-500">{{ $machine->localisation }}</td>
                <td class="px-6 py-4">
                    <span class="px-3 py-1 rounded-full text-xs font-bold 
                        {{ $machine->etat === 'active' ? 'bg-green-100 text-green-700' : ($machine->etat === 'panne' ? 'bg-red-100 text-red-700' : 'bg-orange-100 text-orange-700') }}">
                        ● {{ ucfirst($machine->etat) }}
                    </span>
                </td>
                <td class="px-6 py-4">
                    <form action="{{ route('admin.machines.destroy', $machine) }}" method="POST" onsubmit="return confirm('Supprimer ?')">
                        @csrf @method('DELETE')
                        <button class="text-red-500 hover:underline font-bold">Supprimer</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<!-- Modal Ajout -->
<div id="modal-add" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
    <div class="bg-white rounded-2xl w-full max-w-md p-8 shadow-2xl relative">
        <h3 class="text-2xl font-extrabold text-gray-800 mb-6">Nouvelle Machine</h3>
        <form action="{{ route('admin.machines.store') }}" method="POST">
            @csrf
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-bold text-gray-600 mb-1">Nom du modèle</label>
                    <input type="text" name="nom" required class="w-full border-gray-300 rounded-lg bg-gray-50">
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-600 mb-1">Localisation (Atelier/Secteur)</label>
                    <input type="text" name="localisation" required class="w-full border-gray-300 rounded-lg bg-gray-50">
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-600 mb-1">État Initial</label>
                    <select name="etat" class="w-full border-gray-300 rounded-lg bg-gray-50">
                        <option value="active">Active</option>
                        <option value="panne">Panne</option>
                        <option value="maintenance">Maintenance</option>
                    </select>
                </div>
            </div>
            <div class="mt-8 flex justify-end gap-3">
                <button type="button" onclick="document.getElementById('modal-add').classList.add('hidden')" class="text-gray-500 font-bold hover:underline">Annuler</button>
                <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded-lg font-bold shadow-lg">Enregistrer</button>
            </div>
        </form>
    </div>
</div>
@endsection
