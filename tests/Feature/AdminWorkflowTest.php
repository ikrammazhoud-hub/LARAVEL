<?php

use App\Models\Intervention;
use App\Models\Machine;
use App\Models\Notification;
use App\Models\Rapport;
use App\Models\Tache;
use App\Models\Technicien;
use App\Models\User;
use App\Services\RapportFinalService;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

test('admin can use the main management workflows', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $this->actingAs($admin);

    $this->get(route('admin.dashboard'))->assertOk()->assertSee('Machines');

    $this->post(route('admin.machines.store'), [
        'nom' => 'Machine Test',
        'etat' => 'ACTIF',
        'localisation' => 'Atelier Test',
        'numero_serie' => 'MT-001',
        'description' => 'Machine de test.',
    ])->assertRedirect(route('admin.machines.index'));

    $machine = Machine::where('numero_serie', 'MT-001')->firstOrFail();
    $this->get(route('admin.machines.show', $machine))->assertOk()->assertSee('Machine Test');
    $this->put(route('admin.machines.update', $machine), [
        'nom' => 'Machine Test Updated',
        'etat' => 'MAINTENANCE',
        'localisation' => 'Atelier B',
        'numero_serie' => 'MT-001',
        'description' => 'Machine mise a jour.',
    ])->assertRedirect(route('admin.machines.index'));
    expect($machine->fresh()->etat)->toBe('MAINTENANCE');

    $this->post(route('admin.techniciens.store'), [
        'name' => 'Technicien Test',
        'email' => 'tech.workflow@example.com',
        'password' => 'password',
        'specialite' => 'Hydraulique',
        'telephone' => '0600000000',
        'matricule' => 'TST-001',
    ])->assertRedirect(route('admin.techniciens.index'));

    $technicien = Technicien::where('matricule', 'TST-001')->firstOrFail();
    $this->get(route('admin.techniciens.show', $technicien))->assertOk()->assertSee('Technicien Test');

    $this->post(route('admin.taches.store'), [
        'technicien_id' => $technicien->id,
        'machine_id' => $machine->id,
        'titre' => 'Tache workflow',
        'description' => 'Verifier le comportement complet.',
        'priorite' => 'haute',
        'date_deadline' => now()->addDay()->format('Y-m-d'),
    ])->assertRedirect(route('admin.taches.index'));

    $tache = Tache::where('titre', 'Tache workflow')->firstOrFail();
    $this->get(route('admin.taches.show', $tache))->assertOk()->assertSee('Tache workflow');

    $this->post(route('admin.interventions.store'), [
        'machine_id' => $machine->id,
        'technicien_id' => $technicien->id,
        'tache_id' => $tache->id,
        'description' => 'Intervention de verification complete.',
        'statut' => 'en_cours',
        'date_debut' => now()->format('Y-m-d H:i:s'),
    ])->assertRedirect(route('admin.interventions.index'));

    $intervention = Intervention::where('tache_id', $tache->id)->firstOrFail();
    $this->patch(route('admin.interventions.cloturer', $intervention))
        ->assertRedirect(route('admin.interventions.show', $intervention->id));
    expect($intervention->fresh()->statut)->toBe('terminee');

    $rapport = Rapport::create([
        'intervention_id' => $intervention->id,
        'contenu' => 'Rapport de verification.',
    ]);
    $this->get(route('admin.rapport.pdf', $rapport))->assertOk();

    $notification = Notification::create([
        'user_id' => $admin->id,
        'type' => 'alerte_machine',
        'titre' => 'Notification test',
        'message' => 'Message test',
        'lu' => false,
        'data' => [],
    ]);

    $this->get(route('admin.notifications.index'))->assertOk()->assertSee('Notification test');
    $this->patch(route('admin.notifications.marquer-lue', $notification->id))->assertRedirect();
    expect($notification->fresh()->lu)->toBeTrue();
    $this->delete(route('admin.notifications.destroy', $notification->id))->assertRedirect();
    expect(Notification::find($notification->id))->toBeNull();
});

test('non admin cannot enter the admin area', function () {
    $user = User::factory()->create(['role' => 'technicien']);
    $this->actingAs($user)
        ->get(route('admin.dashboard'))
        ->assertForbidden();
});

test('admin header notification button links to notifications with unread count', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    Notification::create([
        'user_id' => $admin->id,
        'type' => 'alerte_machine',
        'titre' => 'Header notification',
        'message' => 'Visible depuis la cloche.',
        'lu' => false,
        'data' => [],
    ]);

    $this->actingAs($admin)
        ->get(route('admin.dashboard'))
        ->assertOk()
        ->assertSee('Ouvrir les notifications')
        ->assertSee(route('admin.notifications.index'), false)
        ->assertSee('bg-red-500', false);
});

test('admin list pages show pagination when records grow', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $this->actingAs($admin);

    Machine::factory()->count(16)->create();

    foreach (range(1, 16) as $index) {
        $user = User::factory()->create(['role' => 'technicien']);
        Technicien::create([
            'user_id' => $user->id,
            'specialite' => 'Pagination',
            'telephone' => '06000000' . str_pad((string) $index, 2, '0', STR_PAD_LEFT),
            'matricule' => 'PAGE-' . str_pad((string) $index, 2, '0', STR_PAD_LEFT),
            'disponible' => true,
        ]);
    }

    $technicien = Technicien::firstOrFail();
    $machine = Machine::firstOrFail();
    foreach (range(1, 16) as $index) {
        Tache::create([
            'technicien_id' => $technicien->id,
            'machine_id' => $machine->id,
            'titre' => 'Admin pagination task ' . $index,
            'description' => 'Pagination coverage.',
            'priorite' => 'moyenne',
            'statut' => 'en attente',
            'date_deadline' => now()->addDays($index)->format('Y-m-d'),
        ]);
    }

    $this->get(route('admin.machines.index'))->assertOk()->assertSee('pagination');
    $this->get(route('admin.techniciens.index'))->assertOk()->assertSee('pagination');
    $this->get(route('admin.taches.index'))->assertOk()->assertSee('pagination');
});

test('admin dashboard shows task tracking charts and final report links', function () {
    Storage::fake('public');

    $admin = User::factory()->create(['role' => 'admin']);
    $user = User::factory()->create(['role' => 'technicien']);
    $technicien = Technicien::create([
        'user_id' => $user->id,
        'specialite' => 'Rapports',
        'telephone' => '0600000099',
        'matricule' => 'REPORT-DASH',
        'disponible' => true,
    ]);
    $machine = Machine::create([
        'nom' => 'Machine Rapport Dashboard',
        'etat' => 'ACTIF',
        'localisation' => 'Zone Rapport',
    ]);
    $tache = Tache::create([
        'technicien_id' => $technicien->id,
        'machine_id' => $machine->id,
        'titre' => 'Dashboard final report task',
        'description' => 'Cloture avec rapport final.',
        'priorite' => 'haute',
        'statut' => 'en cours',
        'date_deadline' => now()->addDay()->format('Y-m-d'),
    ]);

    $rapport = app(RapportFinalService::class)->finaliserTache($tache, 'Rapport final pour le dashboard.');

    $this->actingAs($admin)
        ->get(route('admin.dashboard'))
        ->assertOk()
        ->assertSee('État des tâches en temps réel')
        ->assertSee('tasksStatusChart')
        ->assertSee('tasksPriorityChart')
        ->assertSee('Dashboard final report task')
        ->assertSee(route('admin.rapport.pdf', $rapport), false);

    $this->actingAs($admin)
        ->get(route('admin.taches.show', $tache))
        ->assertOk()
        ->assertSee('Rapport final associé')
        ->assertSee('Télécharger le PDF');

    $this->actingAs($admin)
        ->get(route('admin.rapport.pdf', $rapport))
        ->assertOk();
});
