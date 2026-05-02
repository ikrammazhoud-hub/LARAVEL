@extends('layouts.technicien')

@section('content')
<div class="mb-8 flex justify-between items-center">
    <h1 class="text-3xl font-extrabold text-slate-800 tracking-tight">Mes Tâches Assignées</h1>
</div>

<div class="bg-white shadow-lg rounded-2xl border border-slate-100 overflow-hidden">
    <ul class="divide-y divide-slate-100">
        @forelse($taches as $tache)
        <li class="p-8 transition-colors hover:bg-slate-50 relative group" x-data="{ showForm: false }">
            <div class="flex items-start justify-between">
                <div class="flex-1 pr-6">
                    <h3 class="text-xl font-bold text-slate-900 mb-1">{{ $tache->titre }}</h3>
                    <p class="text-slate-500 text-sm mb-4 leading-relaxed">{{ $tache->description ?? 'Aucune instruction supplémentaire détaillée.' }}</p>
                    
                    <div class="flex items-center space-x-3 text-xs uppercase tracking-wide font-bold">
                        <span class="text-slate-400 bg-slate-100 px-3 py-1 rounded-full">⏳ {{ $tache->date_deadline->format('d M Y') }}</span>
                        
                        <span class="px-3 py-1 rounded-full shadow-sm
                            {{ $tache->priorite === 'haute' ? 'bg-red-500 text-white' : ($tache->priorite === 'moyenne' ? 'bg-amber-400 text-white' : 'bg-green-500 text-white') }}">
                            {{ ucfirst($tache->priorite) }}
                        </span>
                        
                        <span class="px-3 py-1 rounded-full border
                            {{ $tache->statut === 'en attente' ? 'text-slate-600 border-slate-300' : ($tache->statut === 'en cours' ? 'text-blue-600 border-blue-300 bg-blue-50' : 'text-emerald-600 border-emerald-300 bg-emerald-50') }}">
                            {{ ucfirst($tache->statut) }}
                        </span>
                    </div>
                </div>

                <div class="flex flex-col items-end space-y-3 min-w-[200px]">
                    @if($tache->statut === 'en attente')
                    <form action="{{ route('technicien.taches.statut', $tache) }}" method="POST" class="w-full">
                        @csrf @method('PATCH')
                        <input type="hidden" name="statut" value="en cours">
                        <button class="w-full bg-blue-600 text-white px-5 py-2.5 rounded-xl shadow-md hover:bg-blue-700 hover:shadow-lg transition-all font-semibold text-sm cursor-pointer">Démarrer Intervention</button>
                    </form>
                    @elseif($tache->statut === 'en cours')
                    <button @click="showForm = !showForm" class="w-full bg-emerald-500 text-white px-5 py-2.5 rounded-xl shadow-md hover:bg-emerald-600 hover:shadow-lg transition-all font-semibold text-sm cursor-pointer border border-emerald-400">
                        Rédiger Rapport
                    </button>
                    @else
                    <span class="text-emerald-500 font-extrabold flex items-center text-sm uppercase tracking-wider bg-emerald-50 px-4 py-2 rounded-lg"><svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg> Clôturée</span>
                    @endif
                </div>
            </div>

            <!-- Formulaire de rapport -->
            <div x-show="showForm" x-cloak class="mt-8 bg-white border-2 border-slate-200 rounded-xl p-6 shadow-sm relative overflow-hidden" x-transition.opacity.duration.300ms>
                <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-emerald-400 to-teal-400"></div>
                <h4 class="text-lg font-bold text-slate-800 mb-4 flex items-center gap-2">✍️ Rapport d'Intervention</h4>
                <form action="{{ route('technicien.taches.rapport', $tache) }}" method="POST" target="_blank" x-on:submit="setTimeout(() => window.location.reload(), 1500)">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-5">
                        <div>
                            <label class="block text-xs font-bold text-slate-500 mb-2 uppercase tracking-wide">ID Machine Concernee</label>
                            <input type="number" name="machine_id" required class="block w-full border-slate-300 rounded-lg shadow-sm focus:ring-emerald-500 focus:border-emerald-500 bg-slate-50 px-4 py-2" placeholder="ID Machine">
                        </div>
                    </div>
                    <div class="mb-5">
                        <label class="block text-xs font-bold text-slate-500 mb-2 uppercase tracking-wide">Compte Rendu Détaillé</label>
                        <textarea name="contenu" rows="4" required class="block w-full border-slate-300 rounded-lg shadow-sm focus:ring-emerald-500 focus:border-emerald-500 bg-slate-50 px-4 py-3" placeholder="Quelles actions ont été menées ?"></textarea>
                    </div>
                    <div class="flex justify-end space-x-3">
                        <button type="button" @click="showForm = false" class="text-slate-600 px-5 py-2 font-semibold hover:bg-slate-100 rounded-lg transition-colors">Annuler</button>
                        <button type="submit" class="bg-slate-900 text-white px-6 py-2 rounded-lg font-semibold shadow hover:bg-slate-800 transition-colors">Finaliser et Soumettre</button>
                    </div>
                </form>
            </div>
        </li>
        @empty
        <div class="p-16 text-center">
            <span class="text-6xl block mb-4">🍃</span>
            <h3 class="text-xl font-bold text-slate-400">Aucune tâche assignée.</h3>
            <p class="text-slate-500 mt-2">Votre emploi du temps est libre.</p>
        </div>
        @endforelse
    </ul>
</div>
@endsection
