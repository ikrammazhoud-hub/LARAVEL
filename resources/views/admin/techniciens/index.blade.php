@extends('layouts.admin')

@section('header', 'Gestion des Techniciens')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div>
        <h3 class="text-xl font-bold text-slate-900">Équipe technique</h3>
        <p class="text-sm text-slate-500 mt-1">Comptes techniciens, spécialités et disponibilités.</p>
    </div>
    <button onclick="document.getElementById('modal-add-tech').classList.remove('hidden')" class="toolbar-button">
        <span>+</span>
        <span>Nouveau technicien</span>
    </button>
</div>

@if(session('success'))
    <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl mb-6 font-medium shadow-sm">
        ✅ {{ session('success') }}
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

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    @foreach($techniciens as $tech)
    <div class="bg-white rounded-lg p-6 shadow-sm border border-gray-100 flex items-center justify-between hover:shadow-md hover:border-slate-200 transition group">
        <div class="flex items-center gap-4">
            <div class="w-14 h-14 bg-indigo-50 rounded-full flex items-center justify-center text-indigo-600 text-2xl font-bold uppercase">
                {{ substr($tech->user->name, 0, 1) }}
            </div>
            <div>
                <h4 class="font-extrabold text-gray-900 group-hover:text-indigo-600 transition">{{ $tech->user->name }}</h4>
                <p class="text-sm text-gray-500 font-semibold uppercase tracking-tighter">{{ $tech->specialite }}</p>
                <p class="text-xs text-indigo-400 mt-1">📞 {{ $tech->telephone }}</p>
            </div>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.techniciens.show', $tech) }}" class="text-sm font-bold text-slate-500 hover:text-slate-900">Voir</a>
            <a href="{{ route('admin.techniciens.edit', $tech) }}" class="text-sm font-bold text-brand-600 hover:text-brand-700">Modifier</a>
        <form action="{{ route('admin.techniciens.destroy', $tech) }}" method="POST" onsubmit="return confirm('Supprimer ce profil ?')">
            @csrf @method('DELETE')
            <button class="bg-gray-50 text-red-500 w-10 h-10 rounded-lg flex items-center justify-center hover:bg-red-500 hover:text-white transition-colors cursor-pointer">🗑️</button>
        </form>
        </div>
    </div>
    @endforeach
</div>

@if($techniciens->hasPages())
    <div class="pagination-shell mt-6">
        {{ $techniciens->links() }}
    </div>
@endif

<!-- Modal Ajout Tech -->
<div id="modal-add-tech" class="modal-backdrop fixed inset-0 hidden z-50 flex items-center justify-center p-4" onclick="this.classList.add('hidden')">
    <div class="modal-card w-full max-w-3xl" onclick="event.stopPropagation()">
        <div class="flex items-start justify-between gap-6 border-b border-slate-200 px-8 py-6">
            <div>
                <p class="modal-kicker">Compte technicien</p>
                <h3 class="modal-title mt-1">Ajouter un technicien</h3>
                <p class="modal-subtitle mt-2">Créez le profil métier et le compte de connexion associé.</p>
            </div>
            <button type="button" onclick="document.getElementById('modal-add-tech').classList.add('hidden')" class="modal-close" aria-label="Fermer">×</button>
        </div>

        <form action="{{ route('admin.techniciens.store') }}" method="POST">
            @csrf
            <div class="modal-scroll px-8 py-6 grid grid-cols-1 md:grid-cols-2 gap-5">
                <div class="col-span-2">
                    <label class="form-label">Nom complet</label>
                    <input type="text" name="name" value="{{ old('name') }}" required class="form-control" placeholder="Ex : Sara Mansouri">
                </div>
                <div>
                    <label class="form-label">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" required class="form-control" placeholder="technicien@atelier.com">
                </div>
                <div>
                    <label class="form-label">Mot de passe</label>
                    <input type="password" name="password" required class="form-control" placeholder="8 caractères minimum">
                </div>
                <div>
                    <label class="form-label">Spécialité</label>
                    <input type="text" name="specialite" value="{{ old('specialite') }}" required class="form-control" placeholder="Ex : Hydraulique">
                </div>
                <div>
                    <label class="form-label">Téléphone</label>
                    <input type="text" name="telephone" value="{{ old('telephone') }}" required class="form-control" placeholder="0600010002">
                </div>
                <div class="md:col-span-2">
                    <label class="form-label">Matricule</label>
                    <input type="text" name="matricule" value="{{ old('matricule') }}" class="form-control" placeholder="TEC-005">
                </div>
            </div>
            <div class="flex justify-end gap-3 border-t border-slate-200 bg-slate-50/80 px-8 py-5">
                <button type="button" onclick="document.getElementById('modal-add-tech').classList.add('hidden')" class="btn-secondary">Annuler</button>
                <button type="submit" class="btn-primary">Créer le compte</button>
            </div>
        </form>
    </div>
</div>
@endsection
