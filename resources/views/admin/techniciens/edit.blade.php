@extends('layouts.admin')

@section('title', 'Modifier ' . $technicien->user?->name)

@section('content')
<div class="max-w-2xl mx-auto space-y-6">

    <div>
        <a href="{{ route('admin.techniciens.show', $technicien) }}" class="inline-flex items-center gap-1 text-sm text-slate-500 hover:text-brand-600 transition-colors mb-3">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            Retour
        </a>
        <h1 class="text-2xl font-bold text-slate-800">Modifier <span class="text-brand-600">{{ $technicien->user?->name }}</span></h1>
    </div>

    <form method="POST" action="{{ route('admin.techniciens.update', $technicien) }}"
          class="bg-white rounded-2xl border border-slate-200 shadow-sm p-8 space-y-5">
        @csrf @method('PUT')

        <div>
            <h2 class="text-sm font-semibold text-slate-500 uppercase tracking-wider mb-4">Compte utilisateur</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div class="space-y-1.5 sm:col-span-2">
                    <label for="name" class="block text-sm font-semibold text-slate-700">Nom complet</label>
                    <input type="text" id="name" name="name" value="{{ old('name', $technicien->user?->name) }}" required
                        class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm focus:ring-2 focus:ring-brand-500 focus:border-brand-500">
                </div>
                <div class="space-y-1.5">
                    <label for="email" class="block text-sm font-semibold text-slate-700">Email</label>
                    <input type="email" id="email" name="email" value="{{ old('email', $technicien->user?->email) }}" required
                        class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm focus:ring-2 focus:ring-brand-500 focus:border-brand-500 @error('email') border-red-400 @enderror">
                    @error('email')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div class="space-y-1.5">
                    <label for="password" class="block text-sm font-semibold text-slate-700">Nouveau mot de passe <span class="text-slate-400 font-normal">(laisser vide = inchangé)</span></label>
                    <input type="password" id="password" name="password"
                        class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm focus:ring-2 focus:ring-brand-500 focus:border-brand-500">
                    @error('password')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
            </div>
        </div>

        <hr class="border-slate-100">

        <div>
            <h2 class="text-sm font-semibold text-slate-500 uppercase tracking-wider mb-4">Profil technicien</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div class="space-y-1.5">
                    <label for="specialite" class="block text-sm font-semibold text-slate-700">Spécialité</label>
                    <input type="text" id="specialite" name="specialite" value="{{ old('specialite', $technicien->specialite) }}" required
                        class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm focus:ring-2 focus:ring-brand-500 focus:border-brand-500">
                </div>
                <div class="space-y-1.5">
                    <label for="telephone" class="block text-sm font-semibold text-slate-700">Téléphone</label>
                    <input type="tel" id="telephone" name="telephone" value="{{ old('telephone', $technicien->telephone) }}" required
                        class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm focus:ring-2 focus:ring-brand-500 focus:border-brand-500">
                </div>
                <div class="space-y-1.5">
                    <label for="matricule" class="block text-sm font-semibold text-slate-700">Matricule</label>
                    <input type="text" id="matricule" name="matricule" value="{{ old('matricule', $technicien->matricule) }}"
                        class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm focus:ring-2 focus:ring-brand-500 focus:border-brand-500">
                </div>
                <div class="space-y-1.5">
                    <label class="block text-sm font-semibold text-slate-700">Disponibilité</label>
                    <label class="inline-flex items-center gap-2 mt-1 cursor-pointer">
                        <input type="checkbox" name="disponible" value="1" {{ $technicien->disponible ? 'checked' : '' }}
                            class="w-4 h-4 rounded text-brand-600 border-slate-300 focus:ring-brand-500">
                        <span class="text-sm text-slate-700">Disponible pour de nouvelles tâches</span>
                    </label>
                </div>
            </div>
        </div>

        <div class="flex items-center gap-3 pt-2">
            <button type="submit" class="bg-brand-600 hover:bg-brand-700 text-white font-semibold px-6 py-2.5 rounded-xl transition-all shadow-sm">
                Enregistrer
            </button>
            <a href="{{ route('admin.techniciens.show', $technicien) }}" class="text-slate-500 hover:text-slate-700 font-medium text-sm">Annuler</a>
        </div>
    </form>

</div>
@endsection
