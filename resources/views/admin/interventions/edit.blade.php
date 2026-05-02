@extends('layouts.admin')

@section('title', 'Modifier Intervention #' . $intervention->id)

@section('content')
<div class="max-w-2xl mx-auto space-y-6">

    <div>
        <a href="{{ route('admin.interventions.show', $intervention) }}" class="inline-flex items-center gap-1 text-sm text-slate-500 hover:text-brand-600 transition-colors mb-3">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            Retour au détail
        </a>
        <h1 class="text-2xl font-bold text-slate-800">Modifier l'intervention <span class="text-brand-600">#{{ $intervention->id }}</span></h1>
    </div>

    <form method="POST" action="{{ route('admin.interventions.update', $intervention) }}"
          class="bg-white rounded-2xl border border-slate-200 shadow-sm p-8 space-y-5">
        @csrf @method('PUT')

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
            <div class="space-y-1.5">
                <label for="machine_id" class="block text-sm font-semibold text-slate-700">Machine <span class="text-red-500">*</span></label>
                <select id="machine_id" name="machine_id" required
                    class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm focus:ring-2 focus:ring-brand-500 focus:border-brand-500">
                    @foreach($machines as $machine)
                        <option value="{{ $machine->id }}" {{ $intervention->machine_id == $machine->id ? 'selected' : '' }}>
                            {{ $machine->nom }} ({{ $machine->etat }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="space-y-1.5">
                <label for="technicien_id" class="block text-sm font-semibold text-slate-700">Technicien <span class="text-red-500">*</span></label>
                <select id="technicien_id" name="technicien_id" required
                    class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm focus:ring-2 focus:ring-brand-500 focus:border-brand-500">
                    @foreach($techniciens as $tech)
                        <option value="{{ $tech->id }}" {{ $intervention->technicien_id == $tech->id ? 'selected' : '' }}>
                            {{ $tech->user?->name }} — {{ $tech->specialite }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="space-y-1.5">
                <label for="statut" class="block text-sm font-semibold text-slate-700">Statut <span class="text-red-500">*</span></label>
                <select id="statut" name="statut" required
                    class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm focus:ring-2 focus:ring-brand-500 focus:border-brand-500">
                    <option value="en_cours" {{ $intervention->statut === 'en_cours' ? 'selected' : '' }}>En cours</option>
                    <option value="terminee" {{ $intervention->statut === 'terminee' ? 'selected' : '' }}>Terminée</option>
                </select>
            </div>

            <div class="space-y-1.5">
                <label for="date_debut" class="block text-sm font-semibold text-slate-700">Date de début</label>
                <input type="datetime-local" id="date_debut" name="date_debut"
                    value="{{ $intervention->date_debut?->format('Y-m-d\TH:i') }}"
                    class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm focus:ring-2 focus:ring-brand-500 focus:border-brand-500">
            </div>

            <div class="space-y-1.5 sm:col-span-2">
                <label for="date_fin" class="block text-sm font-semibold text-slate-700">Date de fin</label>
                <input type="datetime-local" id="date_fin" name="date_fin"
                    value="{{ $intervention->date_fin?->format('Y-m-d\TH:i') }}"
                    class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm focus:ring-2 focus:ring-brand-500 focus:border-brand-500">
            </div>
        </div>

        <div class="space-y-1.5">
            <label for="description" class="block text-sm font-semibold text-slate-700">Description <span class="text-red-500">*</span></label>
            <textarea id="description" name="description" rows="4" required
                class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm focus:ring-2 focus:ring-brand-500 focus:border-brand-500 resize-none">{{ old('description', $intervention->description) }}</textarea>
            @error('description')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>

        <div class="flex items-center gap-3 pt-2">
            <button type="submit" class="bg-brand-600 hover:bg-brand-700 text-white font-semibold px-6 py-2.5 rounded-xl transition-all shadow-sm">
                Enregistrer les modifications
            </button>
            <a href="{{ route('admin.interventions.show', $intervention) }}" class="text-slate-500 hover:text-slate-700 font-medium text-sm">Annuler</a>
        </div>
    </form>

</div>
@endsection
