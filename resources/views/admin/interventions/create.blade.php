@extends('layouts.admin')

@section('title', 'Nouvelle Intervention')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">

    <div>
        <a href="{{ route('admin.interventions.index') }}" class="inline-flex items-center gap-1 text-sm text-slate-500 hover:text-brand-600 transition-colors mb-3">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            Retour aux interventions
        </a>
        <h1 class="text-2xl font-bold text-slate-800">Nouvelle intervention</h1>
    </div>

    <form method="POST" action="{{ route('admin.interventions.store') }}"
          class="bg-white rounded-2xl border border-slate-200 shadow-sm p-8 space-y-5">
        @csrf

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
            {{-- Machine --}}
            <div class="space-y-1.5">
                <label for="machine_id" class="block text-sm font-semibold text-slate-700">Machine <span class="text-red-500">*</span></label>
                <select id="machine_id" name="machine_id" required
                    class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm focus:ring-2 focus:ring-brand-500 focus:border-brand-500 @error('machine_id') border-red-400 @enderror">
                    <option value="">-- Sélectionner --</option>
                    @foreach($machines as $machine)
                        <option value="{{ $machine->id }}" {{ old('machine_id') == $machine->id ? 'selected' : '' }}>
                            {{ $machine->nom }} ({{ $machine->etat }})
                        </option>
                    @endforeach
                </select>
                @error('machine_id')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            {{-- Technicien --}}
            <div class="space-y-1.5">
                <label for="technicien_id" class="block text-sm font-semibold text-slate-700">Technicien <span class="text-red-500">*</span></label>
                <select id="technicien_id" name="technicien_id" required
                    class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm focus:ring-2 focus:ring-brand-500 focus:border-brand-500 @error('technicien_id') border-red-400 @enderror">
                    <option value="">-- Sélectionner --</option>
                    @foreach($techniciens as $tech)
                        <option value="{{ $tech->id }}" {{ old('technicien_id') == $tech->id ? 'selected' : '' }}>
                            {{ $tech->user?->name }} — {{ $tech->specialite }}
                        </option>
                    @endforeach
                </select>
                @error('technicien_id')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            {{-- Tâche liée (optionnel) --}}
            <div class="space-y-1.5">
                <label for="tache_id" class="block text-sm font-semibold text-slate-700">Tâche liée <span class="text-slate-400 font-normal">(optionnel)</span></label>
                <select id="tache_id" name="tache_id"
                    class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm focus:ring-2 focus:ring-brand-500 focus:border-brand-500">
                    <option value="">-- Aucune --</option>
                    @foreach($taches as $tache)
                        <option value="{{ $tache->id }}" {{ old('tache_id') == $tache->id ? 'selected' : '' }}>
                            {{ $tache->titre }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Statut --}}
            <div class="space-y-1.5">
                <label for="statut" class="block text-sm font-semibold text-slate-700">Statut <span class="text-red-500">*</span></label>
                <select id="statut" name="statut" required
                    class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm focus:ring-2 focus:ring-brand-500 focus:border-brand-500 @error('statut') border-red-400 @enderror">
                    <option value="en_cours"  {{ old('statut') === 'en_cours'  ? 'selected' : '' }}>En cours</option>
                    <option value="terminee"  {{ old('statut') === 'terminee'  ? 'selected' : '' }}>Terminée</option>
                </select>
                @error('statut')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            {{-- Date début --}}
            <div class="space-y-1.5">
                <label for="date_debut" class="block text-sm font-semibold text-slate-700">Date de début</label>
                <input type="datetime-local" id="date_debut" name="date_debut" value="{{ old('date_debut') }}"
                    class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm focus:ring-2 focus:ring-brand-500 focus:border-brand-500">
            </div>

            {{-- Date fin --}}
            <div class="space-y-1.5">
                <label for="date_fin" class="block text-sm font-semibold text-slate-700">Date de fin</label>
                <input type="datetime-local" id="date_fin" name="date_fin" value="{{ old('date_fin') }}"
                    class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm focus:ring-2 focus:ring-brand-500 focus:border-brand-500">
            </div>
        </div>

        {{-- Description --}}
        <div class="space-y-1.5">
            <label for="description" class="block text-sm font-semibold text-slate-700">Description <span class="text-red-500">*</span></label>
            <textarea id="description" name="description" rows="4" required
                class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm focus:ring-2 focus:ring-brand-500 focus:border-brand-500 resize-none @error('description') border-red-400 @enderror"
                placeholder="Décrivez l'intervention réalisée...">{{ old('description') }}</textarea>
            @error('description')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>

        <div class="flex items-center gap-3 pt-2">
            <button type="submit"
                class="bg-brand-600 hover:bg-brand-700 text-white font-semibold px-6 py-2.5 rounded-xl transition-all shadow-sm">
                Enregistrer
            </button>
            <a href="{{ route('admin.interventions.index') }}"
               class="text-slate-500 hover:text-slate-700 font-medium text-sm transition-colors">
                Annuler
            </a>
        </div>
    </form>

</div>
@endsection
