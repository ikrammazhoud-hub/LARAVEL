@extends('layouts.app')

@section('content')

<style>
    body {
        background: radial-gradient(circle at top, #0f172a, #020617);
    }

    .card-pro {
        background: rgba(255,255,255,0.05);
        backdrop-filter: blur(12px);
        border: 1px solid rgba(255,255,255,0.1);
        border-radius: 15px;
        padding: 20px;
        transition: all 0.3s ease;
        box-shadow: 0 0 20px rgba(0,0,0,0.3);
    }

    .card-pro:hover {
        transform: translateY(-8px) scale(1.05);
        box-shadow: 0 0 30px rgba(0,255,150,0.4);
    }

    .title-glow {
        text-shadow: 0 0 10px rgba(0,255,150,0.7);
    }
</style>

<h1 class="text-3xl font-bold mb-6 text-white title-glow">🚀 Dashboard</h1>

<!-- Cards -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6">

    <div class="card-pro">
        <h3 class="text-gray-400">Machines</h3>
        <p class="text-green-400 text-3xl font-bold">{{ count($donnees) }}</p>
    </div>

    <div class="card-pro">
        <h3 class="text-gray-400">Alertes</h3>
        <p class="text-red-400 text-3xl font-bold">{{ count($alertes) }}</p>
    </div>

    <div class="card-pro">
        <h3 class="text-gray-400">Temp Moy</h3>
        <p class="text-yellow-400 text-3xl font-bold">
            {{ round($donnees->avg('temperature'),1) ?? 0 }}°C
        </p>
    </div>

    <div class="card-pro">
        <h3 class="text-gray-400">Status</h3>
        <p class="text-green-400 text-3xl font-bold animate-pulse">OK</p>
    </div>

</div>

<!-- Alertes -->
<div class="card-pro mt-6">
    <h2 class="text-xl font-bold mb-3 text-white">⚠️ Alertes</h2>

    @foreach($alertes as $a)
        <p class="text-red-400 animate-pulse">- ⚠️ {{ $a->message }}</p>
    @endforeach
</div>

<!-- Graph -->
<div class="card-pro mt-6">
    <h2 class="text-xl font-bold mb-3 text-white">📊 Température (Live)</h2>
    <canvas id="tempChart"></canvas>
</div>

<!-- Données -->
<div class="card-pro mt-6">
    <h2 class="text-xl font-bold mb-3 text-white">📈 Données</h2>

    @foreach($donnees as $d)
        <p class="text-gray-300">Temp: {{ $d->temperature }}</p>
    @endforeach
</div>

<!-- Chart -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    const ctx = document.getElementById('tempChart');

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: {!! json_encode($donnees->pluck('created_at')) !!},
            datasets: [{
                label: 'Température',
                data: {!! json_encode($donnees->pluck('temperature')) !!},
                borderColor: '#22c55e',
                backgroundColor: 'rgba(34,197,94,0.2)',
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            plugins: {
                legend: {
                    labels: {
                        color: 'white'
                    }
                }
            },
            scales: {
                x: {
                    ticks: { color: 'white' }
                },
                y: {
                    ticks: { color: 'white' }
                }
            }
        }
    });
</script>

@endsection