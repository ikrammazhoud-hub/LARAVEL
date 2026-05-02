@extends('layouts.admin')

@section('header', 'Gestion des Tâches')

@section('content')
<div class="mb-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
    <div>
        <h3 class="text-2xl font-black text-gray-800">Affectations & Planning</h3>
        <p class="text-gray-500 font-medium">Assignez les tâches de maintenance aux techniciens disponibles.</p>
    </div>
    <button onclick="document.getElementById('modal-assign').classList.remove('hidden')" class="bg-indigo-600 text-white px-6 py-3 rounded-2xl hover:bg-indigo-700 transition-all font-black shadow-lg">⚡ Nouvelle Assignation</button>
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

<div class="bg-white rounded-3xl shadow-xl border border-gray-100 overflow-hidden">
    <table class="w-full text-left">
        <thead class="bg-gray-900 text-white">
            <tr>
                <th class="px-8 py-5 text-sm font-bold uppercase tracking-widest">Sujet / Description</th>
                <th class="px-8 py-5 text-sm font-bold uppercase tracking-widest">Assigné à</th>
                <th class="px-8 py-5 text-sm font-bold uppercase tracking-widest">Priorité</th>
                <th class="px-8 py-5 text-sm font-bold uppercase tracking-widest">Deadline</th>
                <th class="px-8 py-5 text-sm font-bold uppercase tracking-widest text-right">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100 text-gray-700 font-semibold">
            @foreach($taches as $tache)
            <tr class="hover:bg-indigo-50/30 transition">
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
                <td class="px-8 py-6 text-indigo-600">
                    📅 {{ $tache->date_deadline->format('d/m/Y') }}
                </td>
                <td class="px-8 py-6 text-right">
                    <form action="{{ route('admin.taches.destroy', $tache) }}" method="POST" class="inline">
                        @csrf @method('DELETE')
                        <button class="text-xs font-black text-gray-400 hover:text-red-500 uppercase tracking-widest hover:underline transition">Retirer</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<!-- Modal Assign -->
<div id="modal-assign" class="fixed inset-0 bg-slate-900/60 backdrop-blur-md flex items-center justify-center hidden z-50 p-4">
    <div class="bg-white rounded-[2rem] w-full max-w-xl p-10 shadow-2xl relative border border-white">
        <h3 class="text-4xl font-black text-slate-900 mb-8 tracking-tighter italic">Lancer une Intervention</h3>
        <form action="{{ route('admin.taches.store') }}" method="POST">
            @csrf
            <div class="space-y-6">
                <div>
                    <label class="block text-xs font-black text-slate-500 mb-2 uppercase tracking-widest">Titre de l'Opération</label>
                    <input type="text" name="titre" value="{{ old('titre') }}" required class="w-full border-2 border-slate-100 rounded-2xl bg-slate-50 px-5 py-4 focus:border-indigo-500 transition-all font-bold" placeholder="ex: Vidange Presse #4">
                </div>
                <div>
                    <label class="block text-xs font-black text-slate-500 mb-2 uppercase tracking-widest">Technicien Responsable</label>
                    <select name="technicien_id" class="w-full border-2 border-slate-100 rounded-2xl bg-slate-50 px-5 py-4 focus:border-indigo-500 font-bold appearance-none">
                        @foreach($techniciens as $tech)
                            <option value="{{ $tech->id }}">{{ $tech->user->name }} ({{ $tech->specialite }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <label class="block text-xs font-black text-slate-500 mb-2 uppercase tracking-widest">Priorité</label>
                        <select name="priorite" class="w-full border-2 border-slate-100 rounded-2xl bg-slate-50 px-5 py-4 focus:border-indigo-500 font-bold italic">
                            <option value="basse">Basse</option>
                            <option value="moyenne" selected>Moyenne</option>
                            <option value="haute">Haute 🔥</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-black text-slate-500 mb-2 uppercase tracking-widest">Échéance</label>
                        <input type="date" name="date_deadline" value="{{ old('date_deadline') }}" required class="w-full border-2 border-slate-100 rounded-2xl bg-slate-50 px-5 py-4 font-bold">
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-black text-slate-500 mb-2 uppercase tracking-widest">Consignes Spécifiques</label>
                    <textarea name="description" rows="3" class="w-full border-2 border-slate-100 rounded-2xl bg-slate-50 px-5 py-4 font-bold" placeholder="Ajoutez des détails...">{{ old('description') }}</textarea>
                </div>
            </div>
            <div class="mt-10 flex justify-end items-center gap-8">
                <button type="button" onclick="document.getElementById('modal-assign').classList.add('hidden')" class="text-slate-400 font-black hover:text-slate-900 transition tracking-tighter uppercase text-sm">Annuler</button>
                <button type="submit" class="bg-slate-900 text-white px-10 py-5 rounded-2xl font-black shadow-2xl hover:bg-slate-800 transition-all uppercase tracking-widest text-sm">Déployer la Tâche</button>
            </div>
        </form>
    </div>
</div>
@endsection
