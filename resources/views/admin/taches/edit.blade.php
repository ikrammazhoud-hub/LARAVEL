@extends('layouts.admin')

@section('title', 'Modifier la tâche')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">

    <div>
        <a href="{{ route('admin.taches.show', $tache) }}" class="inline-flex items-center gap-1 text-sm text-slate-500 hover:text-brand-600 transition-colors mb-3">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            Retour
        </a>
        <h1 class="text-2xl font-bold text-slate-800">Modifier la tâche</h1>
        <p class="text-slate-400 text-sm mt-1">{{ $tache->titre }}</p>
    </div>

    <form method="POST" action="{{ route('admin.taches.update', $tache) }}"
          class="bg-white rounded-2xl border border-slate-200 shadow-sm p-8 space-y-5">
        @csrf @method('PUT')

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
            <div class="space-y-1.5 sm:col-span-2">
                <label for="titre" class="block text-sm font-semibold text-slate-700">Titre <span class="text-red-500">*</span></label>
                <input type="text" id="titre" name="titre" value="{{ old('titre', $tache->titre) }}" required
                    class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm focus:ring-2 focus:ring-brand-500 focus:border-brand-500">
            </div>

            <div class="space-y-1.5">
                <label for="technicien_id" class="block text-sm font-semibold text-slate-700">Technicien <span class="text-red-500">*</span></label>
                <select id="technicien_id" name="technicien_id" required
                    class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm focus:ring-2 focus:ring-brand-500 focus:border-brand-500">
                    @foreach($techniciens as $tech)
                        <option value="{{ $tech->id }}" {{ $tache->technicien_id == $tech->id ? 'selected' : '' }}>
                            {{ $tech->user?->name }} — {{ $tech->specialite }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="space-y-1.5">
                <label for="machine_id" class="block text-sm font-semibold text-slate-700">Machine</label>
                <select id="machine_id" name="machine_id"
                    class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm focus:ring-2 focus:ring-brand-500 focus:border-brand-500">
                    <option value="">-- Aucune --</option>
                    @foreach($machines as $machine)
                        <option value="{{ $machine->id }}" {{ $tache->machine_id == $machine->id ? 'selected' : '' }}>
                            {{ $machine->nom }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="space-y-1.5">
                <label for="priorite" class="block text-sm font-semibold text-slate-700">Priorité <span class="text-red-500">*</span></label>
                <select id="priorite" name="priorite" required
                    class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm focus:ring-2 focus:ring-brand-500 focus:border-brand-500">
                    @foreach(\App\Models\Tache::PRIORITES as $p)
                        <option value="{{ $p }}" {{ $tache->priorite === $p ? 'selected' : '' }}>{{ ucfirst($p) }}</option>
                    @endforeach
                </select>
            </div>

            <div class="space-y-1.5">
                <label for="statut" class="block text-sm font-semibold text-slate-700">Statut</label>
                <select id="statut" name="statut"
                    class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm focus:ring-2 focus:ring-brand-500 focus:border-brand-500">
                    @foreach(\App\Models\Tache::STATUTS as $s)
                        <option value="{{ $s }}" {{ $tache->statut === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                    @endforeach
                </select>
            </div>

            <div class="space-y-1.5">
                <label for="date_deadline" class="block text-sm font-semibold text-slate-700">Date limite <span class="text-red-500">*</span></label>
                <input type="date" id="date_deadline" name="date_deadline"
                    value="{{ old('date_deadline', $tache->date_deadline?->format('Y-m-d')) }}"
                    required
                    class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm focus:ring-2 focus:ring-brand-500 focus:border-brand-500">
            </div>

            <div class="space-y-1.5 sm:col-span-2">
                <label for="description" class="block text-sm font-semibold text-slate-700">Description</label>
                <textarea id="description" name="description" rows="4"
                    class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm focus:ring-2 focus:ring-brand-500 focus:border-brand-500 resize-none">{{ old('description', $tache->description) }}</textarea>
            </div>
        </div>

        <div class="flex items-center gap-3 pt-2">
            <button type="submit" class="bg-brand-600 hover:bg-brand-700 text-white font-semibold px-6 py-2.5 rounded-xl transition-all shadow-sm">
                Enregistrer
            </button>
            <a href="{{ route('admin.taches.show', $tache) }}" class="text-slate-500 hover:text-slate-700 font-medium text-sm">Annuler</a>
        </div>
    </form>

</div>
@endsection
