@extends('layouts.app')

@section('content')

<h1 class="text-2xl font-bold mb-6 text-white">⚠️ Alertes</h1>

@foreach($alertes as $a)
    <p class="text-red-400">⚠️ {{ $a->message }}</p>
@endforeach

@endsection