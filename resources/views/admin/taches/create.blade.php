@extends('layouts.admin')

@section('title', 'Nouvelle Tâche')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">

    <div>
        <a href="{{ route('admin.taches.index') }}" class="inline-flex items-center gap-1 text-sm text-slate-500 hover:text-brand-600 transition-colors mb-3">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            Retour aux tâches
        </a>
        <h1 class="text-2xl font-bold text-slate-800">Créer une tâche</h1>
    </div>

    <form method="POST" action="{{ route('admin.taches.store') }}"
          class="bg-white rounded-2xl border border-slate-200 shadow-sm p-8 space-y-5">
        @csrf

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">

            {{-- Titre --}}
            <div class="space-y-1.5 sm:col-span-2">
                <label for="titre" class="block text-sm font-semibold text-slate-700">Titre de la tâche <span class="text-red-500">*</span></label>
                <input type="text" id="titre" name="titre" value="{{ old('titre') }}" required
                    placeholder="Ex : Révision mensuelle compresseur"
                    class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm focus:ring-2 focus:ring-brand-500 focus:border-brand-500 @error('titre') border-red-400 @enderror">
                @error('titre')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            {{-- Technicien --}}
            <div class="space-y-1.5">
                <label for="technicien_id" class="block text-sm font-semibold text-slate-700">Technicien assigné <span class="text-red-500">*</span></label>
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

            {{-- Machine --}}
            <div class="space-y-1.5">
                <label for="machine_id" class="block text-sm font-semibold text-slate-700">Machine concernée <span class="text-slate-400 font-normal">(optionnel)</span></label>
                <select id="machine_id" name="machine_id"
                    class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm focus:ring-2 focus:ring-brand-500 focus:border-brand-500">
                    <option value="">-- Aucune --</option>
                    @foreach($machines as $machine)
                        <option value="{{ $machine->id }}" {{ old('machine_id') == $machine->id ? 'selected' : '' }}>
                            {{ $machine->nom }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Priorité --}}
            <div class="space-y-1.5">
                <label for="priorite" class="block text-sm font-semibold text-slate-700">Priorité <span class="text-red-500">*</span></label>
                <select id="priorite" name="priorite" required
                    class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm focus:ring-2 focus:ring-brand-500 focus:border-brand-500 @error('priorite') border-red-400 @enderror">
                    @foreach(\App\Models\Tache::PRIORITES as $p)
                        <option value="{{ $p }}" {{ old('priorite') === $p ? 'selected' : '' }}>{{ ucfirst($p) }}</option>
                    @endforeach
                </select>
                @error('priorite')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            {{-- Date limite --}}
            <div class="space-y-1.5">
                <label for="date_deadline" class="block text-sm font-semibold text-slate-700">Date limite</label>
                <input type="date" id="date_deadline" name="date_deadline"
                    value="{{ old('date_deadline') }}"
                    min="{{ date('Y-m-d') }}"
                    class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm focus:ring-2 focus:ring-brand-500 focus:border-brand-500">
                @error('date_deadline')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            {{-- Description --}}
            <div class="space-y-1.5 sm:col-span-2">
                <label for="description" class="block text-sm font-semibold text-slate-700">Description <span class="text-slate-400 font-normal">(optionnel)</span></label>
                <textarea id="description" name="description" rows="4"
                    placeholder="Détaillez les opérations à effectuer..."
                    class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm focus:ring-2 focus:ring-brand-500 focus:border-brand-500 resize-none">{{ old('description') }}</textarea>
            </div>
        </div>

        <div class="flex items-center gap-3 pt-2">
            <button type="submit" class="bg-brand-600 hover:bg-brand-700 text-white font-semibold px-6 py-2.5 rounded-xl transition-all shadow-sm">
                Créer la tâche
            </button>
            <a href="{{ route('admin.taches.index') }}" class="text-slate-500 hover:text-slate-700 font-medium text-sm">Annuler</a>
        </div>
    </form>

</div>
@endsection
