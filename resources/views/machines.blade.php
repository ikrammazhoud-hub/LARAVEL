@extends('layouts.app')

@section('content')

<div class="p-6 text-white">

    <h1 class="text-2xl font-bold mb-4">Machines</h1>

    <!-- FORM -->
    <form method="POST" action="/machines/add" class="mb-6 bg-gray-800 p-4 rounded-xl">
        @csrf

        <input type="text" name="nom" placeholder="Nom machine"
            class="w-full mb-3 p-2 rounded bg-gray-700">

        <input type="text" name="description" placeholder="Description"
            class="w-full mb-3 p-2 rounded bg-gray-700">

        <button class="bg-green-500 px-4 py-2 rounded">
            ➕ Ajouter Machine
        </button>
    </form>

    <!-- LIST -->
    <div class="bg-gray-900 p-4 rounded-xl">
       <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

@foreach($machines as $m)

<div class="bg-gray-800 rounded-xl p-4 shadow-lg hover:scale-105 transition duration-300">

    <div class="flex justify-between items-center mb-2">
        <h2 class="text-lg font-bold text-white">
            🏭 {{ $m->nom }}
        </h2>

        <span class="px-2 py-1 text-sm rounded
            {{ $m->status == 'OK' ? 'bg-green-500' : 'bg-red-500' }}">
            {{ $m->status }}
        </span>
    </div>

    <p class="text-gray-300 mb-3">
        {{ $m->description }}
    </p>

    <div class="flex gap-2">
        <button class="bg-blue-500 px-3 py-1 rounded text-sm hover:bg-blue-600">
            ✏️ Edit
        </button>

        <button class="bg-red-500 px-3 py-1 rounded text-sm hover:bg-red-600">
            🗑 Delete
        </button>
    </div>

</div>

@endforeach

</div>
    </div>

</div>

@endsection