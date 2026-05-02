<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\InterventionController;
use App\Http\Controllers\NotificationController;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\CheckRole;

/*
|--------------------------------------------------------------------------
| Web Routes — Atelier Pro
|--------------------------------------------------------------------------
*/

// Racine → login
Route::get('/', fn () => redirect()->route('login'));

Route::get('/test-session-set', function () {
    session(['test_key' => 'Hello World!']);
    return 'Session set. <a href="/test-session-get">Go to get</a>';
});

Route::get('/test-session-get', function () {
    return 'Session value: ' . session('test_key', 'NOT FOUND');
});

Route::middleware('auth')->group(function () {

    // Point d'entrée commun utilisé par Breeze après login/verification.
    Route::get('/dashboard', function () {
        return auth()->user()->role === 'technicien'
            ? redirect()->route('technicien.dashboard')
            : redirect()->route('admin.dashboard');
    })->name('dashboard');

    // ── Profil utilisateur ────────────────────────────────────────────────
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // ══════════════════════════════════════════════════════════════════════
    //  ESPACE ADMIN
    // ══════════════════════════════════════════════════════════════════════
    Route::middleware([CheckRole::class . ':admin'])
        ->prefix('admin')
        ->name('admin.')
        ->group(function () {

            // Dashboard
            Route::get('/dashboard', [\App\Http\Controllers\AdminController::class, 'dashboard'])
                ->name('dashboard');

            // Export PDF d'un rapport
            Route::get('/rapport/{rapport}/pdf', [\App\Http\Controllers\AdminController::class, 'exportPdf'])
                ->name('rapport.pdf');

            // ── Machines ──────────────────────────────────────────────────
            Route::resource('machines', \App\Http\Controllers\MachineController::class);

            // ── Techniciens ───────────────────────────────────────────────
            Route::resource('techniciens', \App\Http\Controllers\AdminTechnicienController::class);

            // ── Tâches ────────────────────────────────────────────────────
            Route::resource('taches', \App\Http\Controllers\TacheController::class);

            // ── Interventions ─────────────────────────────────────────────
            Route::resource('interventions', InterventionController::class);
            Route::patch('/interventions/{intervention}/cloturer', [InterventionController::class, 'cloturer'])
                ->name('interventions.cloturer');

            // ── Notifications ─────────────────────────────────────────────
            Route::get('/notifications', [NotificationController::class, 'index'])
                ->name('notifications.index');
            Route::patch('/notifications/{id}/lue', [NotificationController::class, 'marquerLue'])
                ->name('notifications.marquer-lue');
            Route::patch('/notifications/toutes-lues', [NotificationController::class, 'marquerToutesLues'])
                ->name('notifications.marquer-toutes-lues');
            Route::delete('/notifications/{id}', [NotificationController::class, 'destroy'])
                ->name('notifications.destroy');
        });

    // ══════════════════════════════════════════════════════════════════════
    //  ESPACE TECHNICIEN
    // ══════════════════════════════════════════════════════════════════════
    Route::middleware([CheckRole::class . ':technicien'])
        ->prefix('technicien')
        ->name('technicien.')
        ->group(function () {

            // Dashboard
            Route::get('/dashboard', [\App\Http\Controllers\TechnicienController::class, 'dashboard'])
                ->name('dashboard');

            // Liste paginée et historique des tâches du technicien
            Route::get('/taches', [\App\Http\Controllers\TechnicienController::class, 'taches'])
                ->name('taches.index');

            // Mise à jour du statut d'une tâche
            Route::patch('/taches/{tache}/statut', [\App\Http\Controllers\TechnicienController::class, 'updateStatut'])
                ->name('taches.statut');

            // Soumission d'un rapport d'intervention
            Route::post('/taches/{tache}/rapport', [\App\Http\Controllers\TechnicienController::class, 'storeRapport'])
                ->name('taches.rapport');

            // Notifications du technicien
            Route::get('/notifications', [NotificationController::class, 'index'])
                ->name('notifications.index');
            Route::patch('/notifications/{id}/lue', [NotificationController::class, 'marquerLue'])
                ->name('notifications.marquer-lue');
            Route::patch('/notifications/toutes-lues', [NotificationController::class, 'marquerToutesLues'])
                ->name('notifications.marquer-toutes-lues');
            Route::delete('/notifications/{id}', [NotificationController::class, 'destroy'])
                ->name('notifications.destroy');
        });
});

require __DIR__ . '/auth.php';
