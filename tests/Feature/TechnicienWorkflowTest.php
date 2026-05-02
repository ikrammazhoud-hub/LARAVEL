<?php

use App\Models\Intervention;
use App\Models\Machine;
use App\Models\Notification;
use App\Models\Rapport;
use App\Models\Tache;
use App\Models\Technicien;
use App\Models\User;

test('technician can start and complete an assigned task', function () {
    $user = User::factory()->create(['role' => 'technicien']);
    $technicien = Technicien::create([
        'user_id' => $user->id,
        'specialite' => 'Electricite',
        'telephone' => '0600000001',
        'matricule' => 'TECH-START',
        'disponible' => true,
    ]);
    $machine = Machine::create([
        'nom' => 'Machine Technicien',
        'etat' => 'ACTIF',
        'localisation' => 'Atelier T',
    ]);
    $tache = Tache::create([
        'technicien_id' => $technicien->id,
        'machine_id' => $machine->id,
        'titre' => 'Verifier moteur',
        'description' => 'Controle du moteur.',
        'priorite' => 'moyenne',
        'statut' => 'en attente',
        'date_deadline' => now()->addDay()->format('Y-m-d'),
    ]);

    $this->actingAs($user);
    $this->get(route('technicien.dashboard'))->assertOk()->assertSee('Verifier moteur');

    $this->patch(route('technicien.taches.statut', $tache), [
        'statut' => 'en cours',
    ])->assertRedirect();
    expect($tache->fresh()->statut)->toBe('en cours');

    $this->patch(route('technicien.taches.statut', $tache), [
        'statut' => 'terminé',
    ])->assertRedirect();
    expect($tache->fresh()->statut)->toBe('terminé');
});

test('technician report submission closes the task and creates intervention records', function () {
    $user = User::factory()->create(['role' => 'technicien']);
    $technicien = Technicien::create([
        'user_id' => $user->id,
        'specialite' => 'Mecanique',
        'telephone' => '0600000002',
        'matricule' => 'TECH-RAPPORT',
        'disponible' => true,
    ]);
    $machine = Machine::create([
        'nom' => 'Machine Rapport',
        'etat' => 'PANNE',
        'localisation' => 'Atelier R',
    ]);
    $tache = Tache::create([
        'technicien_id' => $technicien->id,
        'machine_id' => $machine->id,
        'titre' => 'Rapport final',
        'description' => 'Rediger le rapport.',
        'priorite' => 'haute',
        'statut' => 'en cours',
        'date_deadline' => now()->addDay()->format('Y-m-d'),
    ]);

    $this->actingAs($user)
        ->post(route('technicien.taches.rapport', $tache), [
            'machine_id' => $machine->id,
            'contenu' => 'Remplacement effectue et controles termines.',
        ])
        ->assertRedirect(route('technicien.dashboard'));

    expect($tache->fresh()->statut)->toBe('terminé');
    expect(Intervention::where('tache_id', $tache->id)->where('statut', 'terminee')->exists())->toBeTrue();
    expect(Rapport::whereHas('intervention', fn ($query) => $query->where('tache_id', $tache->id))->exists())->toBeTrue();
});

test('technician cannot update another technician task', function () {
    $owner = User::factory()->create(['role' => 'technicien']);
    $other = User::factory()->create(['role' => 'technicien']);
    $ownerProfile = Technicien::create([
        'user_id' => $owner->id,
        'specialite' => 'Hydraulique',
        'telephone' => '0600000003',
        'matricule' => 'OWNER',
        'disponible' => true,
    ]);
    Technicien::create([
        'user_id' => $other->id,
        'specialite' => 'CNC',
        'telephone' => '0600000004',
        'matricule' => 'OTHER',
        'disponible' => true,
    ]);
    $tache = Tache::create([
        'technicien_id' => $ownerProfile->id,
        'titre' => 'Tache privee',
        'description' => 'Ne doit pas etre modifiee.',
        'priorite' => 'basse',
        'statut' => 'en attente',
        'date_deadline' => now()->addDay()->format('Y-m-d'),
    ]);

    $this->actingAs($other)
        ->patch(route('technicien.taches.statut', $tache), ['statut' => 'terminé'])
        ->assertForbidden();

    expect($tache->fresh()->statut)->toBe('en attente');
});

test('technician can browse paginated task workspace and history filters', function () {
    $user = User::factory()->create(['role' => 'technicien']);
    $technicien = Technicien::create([
        'user_id' => $user->id,
        'specialite' => 'Maintenance generale',
        'telephone' => '0600000005',
        'matricule' => 'TECH-PAGINATION',
        'disponible' => true,
    ]);

    foreach (range(1, 10) as $index) {
        Tache::create([
            'technicien_id' => $technicien->id,
            'titre' => sprintf('Paginated Task %02d', $index),
            'description' => 'Visible dans le workspace technicien.',
            'priorite' => 'moyenne',
            'statut' => $index > 8 ? 'terminé' : 'en attente',
            'date_deadline' => now()->addDays($index)->format('Y-m-d'),
        ]);
    }

    $this->actingAs($user)
        ->get(route('technicien.taches.index'))
        ->assertOk()
        ->assertSee('Mes tâches')
        ->assertSee('Paginated Task 01')
        ->assertSee('pagination');

    $this->actingAs($user)
        ->get(route('technicien.taches.index', ['statut' => 'done']))
        ->assertOk()
        ->assertSee('Paginated Task 09')
        ->assertDontSee('Paginated Task 01');
});

test('technician notifications use technician routes and can be managed', function () {
    $user = User::factory()->create(['role' => 'technicien']);
    Technicien::create([
        'user_id' => $user->id,
        'specialite' => 'Notifications',
        'telephone' => '0600000006',
        'matricule' => 'TECH-NOTIFS',
        'disponible' => true,
    ]);

    $notification = Notification::create([
        'user_id' => $user->id,
        'type' => 'tache_assignee',
        'titre' => 'Notification technicien',
        'message' => 'Une tâche vous attend.',
        'lu' => false,
    ]);

    $this->actingAs($user)
        ->get(route('technicien.notifications.index'))
        ->assertOk()
        ->assertSee('ATELIER PRO')
        ->assertSee('Notification technicien')
        ->assertSee(route('technicien.notifications.marquer-lue', $notification), false);

    $this->actingAs($user)
        ->patch(route('technicien.notifications.marquer-lue', $notification))
        ->assertRedirect();

    expect($notification->fresh()->lu)->toBeTrue();
});
