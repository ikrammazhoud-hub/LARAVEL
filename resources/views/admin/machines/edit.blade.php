@extends('layouts.admin')

@section('title', 'Modifier ' . $machine->nom)

@section('content')
<div class="max-w-2xl mx-auto space-y-6">

    <div>
        <a href="{{ route('admin.machines.show', $machine) }}" class="inline-flex items-center gap-1 text-sm text-slate-500 hover:text-brand-600 transition-colors mb-3">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            Retour au détail
        </a>
        <h1 class="text-2xl font-bold text-slate-800">Modifier <span class="text-brand-600">{{ $machine->nom }}</span></h1>
    </div>

    <form method="POST" action="{{ route('admin.machines.update', $machine) }}"
          class="bg-white rounded-2xl border border-slate-200 shadow-sm p-8 space-y-5">
        @csrf @method('PUT')

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
            <div class="space-y-1.5 sm:col-span-2">
                <label for="nom" class="block text-sm font-semibold text-slate-700">Nom <span class="text-red-500">*</span></label>
                <input type="text" id="nom" name="nom" value="{{ old('nom', $machine->nom) }}" required
                    class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm focus:ring-2 focus:ring-brand-500 focus:border-brand-500 @error('nom') border-red-400 @enderror">
                @error('nom')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div class="space-y-1.5">
                <label for="etat" class="block text-sm font-semibold text-slate-700">État <span class="text-red-500">*</span></label>
                <select id="etat" name="etat" required
                    class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm focus:ring-2 focus:ring-brand-500 focus:border-brand-500">
                    @foreach(\App\Models\Machine::ETATS as $etat)
                        <option value="{{ $etat }}" {{ $machine->etat === $etat ? 'selected' : '' }}>{{ $etat }}</option>
                    @endforeach
                </select>
            </div>

            <div class="space-y-1.5">
                <label for="localisation" class="block text-sm font-semibold text-slate-700">Localisation <span class="text-red-500">*</span></label>
                <input type="text" id="localisation" name="localisation" value="{{ old('localisation', $machine->localisation) }}" required
                    class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm focus:ring-2 focus:ring-brand-500 focus:border-brand-500">
            </div>

            <div class="space-y-1.5">
                <label for="numero_serie" class="block text-sm font-semibold text-slate-700">Numéro de série</label>
                <input type="text" id="numero_serie" name="numero_serie" value="{{ old('numero_serie', $machine->numero_serie) }}"
                    class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm focus:ring-2 focus:ring-brand-500 focus:border-brand-500 @error('numero_serie') border-red-400 @enderror">
                @error('numero_serie')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div class="space-y-1.5 sm:col-span-2">
                <label for="description" class="block text-sm font-semibold text-slate-700">Description</label>
                <textarea id="description" name="description" rows="3"
                    class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm focus:ring-2 focus:ring-brand-500 focus:border-brand-500 resize-none">{{ old('description', $machine->description) }}</textarea>
            </div>
        </div>

        <div class="flex items-center gap-3 pt-2">
            <button type="submit" class="bg-brand-600 hover:bg-brand-700 text-white font-semibold px-6 py-2.5 rounded-xl transition-all shadow-sm">
                Enregistrer les modifications
            </button>
            <a href="{{ route('admin.machines.show', $machine) }}" class="text-slate-500 hover:text-slate-700 font-medium text-sm">Annuler</a>
        </div>
    </form>

</div>
@endsection
