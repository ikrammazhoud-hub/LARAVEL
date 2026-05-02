@extends('layouts.admin')

@section('title', 'Nouveau Technicien')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">

    <div>
        <a href="{{ route('admin.techniciens.index') }}" class="inline-flex items-center gap-1 text-sm text-slate-500 hover:text-brand-600 transition-colors mb-3">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            Retour aux techniciens
        </a>
        <h1 class="text-2xl font-bold text-slate-800">Ajouter un technicien</h1>
    </div>

    <form method="POST" action="{{ route('admin.techniciens.store') }}"
          class="bg-white rounded-2xl border border-slate-200 shadow-sm p-8 space-y-5">
        @csrf

        {{-- Section compte --}}
        <div>
            <h2 class="text-sm font-semibold text-slate-500 uppercase tracking-wider mb-4">Compte utilisateur</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div class="space-y-1.5 sm:col-span-2">
                    <label for="name" class="block text-sm font-semibold text-slate-700">Nom complet <span class="text-red-500">*</span></label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}" required
                        placeholder="Ex : Ali Ben Salah"
                        class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm focus:ring-2 focus:ring-brand-500 focus:border-brand-500 @error('name') border-red-400 @enderror">
                    @error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div class="space-y-1.5">
                    <label for="email" class="block text-sm font-semibold text-slate-700">Email <span class="text-red-500">*</span></label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" required
                        class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm focus:ring-2 focus:ring-brand-500 focus:border-brand-500 @error('email') border-red-400 @enderror">
                    @error('email')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div class="space-y-1.5">
                    <label for="password" class="block text-sm font-semibold text-slate-700">Mot de passe <span class="text-red-500">*</span></label>
                    <input type="password" id="password" name="password" required
                        class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm focus:ring-2 focus:ring-brand-500 focus:border-brand-500 @error('password') border-red-400 @enderror">
                    @error('password')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
            </div>
        </div>

        <hr class="border-slate-100">

        {{-- Section profil technicien --}}
        <div>
            <h2 class="text-sm font-semibold text-slate-500 uppercase tracking-wider mb-4">Profil technicien</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div class="space-y-1.5">
                    <label for="specialite" class="block text-sm font-semibold text-slate-700">Spécialité <span class="text-red-500">*</span></label>
                    <input type="text" id="specialite" name="specialite" value="{{ old('specialite') }}" required
                        placeholder="Ex : Électromécanique"
                        class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm focus:ring-2 focus:ring-brand-500 focus:border-brand-500 @error('specialite') border-red-400 @enderror">
                    @error('specialite')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div class="space-y-1.5">
                    <label for="telephone" class="block text-sm font-semibold text-slate-700">Téléphone <span class="text-red-500">*</span></label>
                    <input type="tel" id="telephone" name="telephone" value="{{ old('telephone') }}" required
                        placeholder="0600000000"
                        class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm focus:ring-2 focus:ring-brand-500 focus:border-brand-500 @error('telephone') border-red-400 @enderror">
                    @error('telephone')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div class="space-y-1.5">
                    <label for="matricule" class="block text-sm font-semibold text-slate-700">Matricule <span class="text-slate-400 font-normal">(optionnel)</span></label>
                    <input type="text" id="matricule" name="matricule" value="{{ old('matricule') }}"
                        placeholder="Ex : TEC-005"
                        class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm focus:ring-2 focus:ring-brand-500 focus:border-brand-500 @error('matricule') border-red-400 @enderror">
                    @error('matricule')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
            </div>
        </div>

        <div class="flex items-center gap-3 pt-2">
            <button type="submit" class="bg-brand-600 hover:bg-brand-700 text-white font-semibold px-6 py-2.5 rounded-xl transition-all shadow-sm">
                Créer le technicien
            </button>
            <a href="{{ route('admin.techniciens.index') }}" class="text-slate-500 hover:text-slate-700 font-medium text-sm">Annuler</a>
        </div>
    </form>

</div>
@endsection
