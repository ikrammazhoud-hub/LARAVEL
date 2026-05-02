@extends('layouts.admin')

@section('header', 'Gestion des Techniciens')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <h3 class="text-xl font-bold text-gray-800">Équipe Technique</h3>
    <button onclick="document.getElementById('modal-add-tech').classList.remove('hidden')" class="bg-indigo-600 text-white px-5 py-2.5 rounded-xl hover:bg-indigo-700 transition-all font-bold shadow-md">+ Nouveau Recrutement</button>
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
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 flex items-center justify-between hover:shadow-md transition group">
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
        <form action="{{ route('admin.techniciens.destroy', $tech) }}" method="POST" onsubmit="return confirm('Supprimer ce profil ?')">
            @csrf @method('DELETE')
            <button class="bg-gray-50 text-red-500 w-10 h-10 rounded-lg flex items-center justify-center hover:bg-red-500 hover:text-white transition-colors cursor-pointer">🗑️</button>
        </form>
    </div>
    @endforeach
</div>

<!-- Modal Ajout Tech -->
<div id="modal-add-tech" class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm flex items-center justify-center hidden z-50">
    <div class="bg-white rounded-3xl w-full max-w-lg p-10 shadow-2xl relative border border-slate-100">
        <h3 class="text-3xl font-black text-slate-800 mb-8 tracking-tight">Profil Technicien</h3>
        <form action="{{ route('admin.techniciens.store') }}" method="POST">
            @csrf
            <div class="grid grid-cols-2 gap-6 mb-6">
                <div class="col-span-2">
                    <label class="block text-xs font-black text-slate-400 mb-2 uppercase tracking-widest">Nom Complet</label>
                    <input type="text" name="name" value="{{ old('name') }}" required class="w-full border-slate-200 rounded-xl bg-slate-50 focus:ring-4 focus:ring-indigo-100 transition-all px-4 py-3">
                </div>
                <div>
                    <label class="block text-xs font-black text-slate-400 mb-2 uppercase tracking-widest">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" required class="w-full border-slate-200 rounded-xl bg-slate-50 focus:ring-4 focus:ring-indigo-100 px-4 py-3">
                </div>
                <div>
                    <label class="block text-xs font-black text-slate-400 mb-2 uppercase tracking-widest">Mot de Passe</label>
                    <input type="password" name="password" required class="w-full border-slate-200 rounded-xl bg-slate-50 focus:ring-4 focus:ring-indigo-100 px-4 py-3">
                </div>
                <div>
                    <label class="block text-xs font-black text-slate-400 mb-2 uppercase tracking-widest">Spécialité</label>
                    <input type="text" name="specialite" value="{{ old('specialite') }}" required class="w-full border-slate-200 rounded-xl bg-slate-50 focus:ring-4 focus:ring-indigo-100 px-4 py-3" placeholder="ex: Hydraulique">
                </div>
                <div>
                    <label class="block text-xs font-black text-slate-400 mb-2 uppercase tracking-widest">Téléphone</label>
                    <input type="text" name="telephone" value="{{ old('telephone') }}" required class="w-full border-slate-200 rounded-xl bg-slate-50 focus:ring-4 focus:ring-indigo-100 px-4 py-3">
                </div>
            </div>
            <div class="mt-8 flex justify-end items-center gap-6">
                <button type="button" @click="document.getElementById('modal-add-tech').classList.add('hidden')" onclick="document.getElementById('modal-add-tech').classList.add('hidden')" class="text-slate-500 font-bold hover:text-slate-800 transition">Fermer</button>
                <button type="submit" class="bg-indigo-600 text-white px-8 py-3 rounded-2xl font-black shadow-lg shadow-indigo-200 hover:scale-105 active:scale-95 transition-all">CRÉER LE COMPTE</button>
            </div>
        </form>
    </div>
</div>
@endsection
