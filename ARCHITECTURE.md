# Atelier Pro - Architecture du Projet

## 📋 Vue d'ensemble

Atelier Pro est une plateforme de gestion d'atelier de maintenance industrielle construite avec Laravel 11, suivant les principes de l'architecture propre et la séparation des responsabilités.

## 🏗️ Architecture

### Pattern Repository-Service

Le projet utilise le pattern Repository-Service pour séparer la logique métier de l'accès aux données :

- **Controllers** : Gèrent les requêtes HTTP et les réponses
- **Services** : Contiennent la logique métier
- **Repositories** : Gèrent l'accès aux données (Eloquent)
- **Requests** : Validation des données d'entrée
- **Resources** : Transformation des données pour les réponses API
- **Policies** : Gestion des autorisations

## 📁 Structure des Dossiers

```
app/
├── Controllers/          # Contrôleurs HTTP
│   ├── AdminController.php
│   ├── MachineController.php
│   ├── TechnicienController.php
│   ├── TacheController.php
│   └── RapportController.php
├── Models/               # Modèles Eloquent
│   ├── Machine.php
│   ├── Technicien.php
│   ├── Tache.php
│   ├── Intervention.php
│   ├── Rapport.php
│   └── User.php
├── Services/             # Logique métier
│   ├── MachineService.php
│   ├── TechnicienService.php
│   ├── TacheService.php
│   └── RapportService.php
├── Repositories/         # Accès aux données
│   ├── MachineRepository.php
│   ├── TechnicienRepository.php
│   ├── TacheRepository.php
│   └── RapportRepository.php
├── Http/
│   ├── Requests/         # Validation des formulaires
│   │   ├── MachineRequest.php
│   │   ├── TechnicienRequest.php
│   │   ├── TacheRequest.php
│   │   └── RapportRequest.php
│   └── Resources/        # API Resources
│       ├── MachineResource.php
│       ├── TechnicienResource.php
│       ├── TacheResource.php
│       └── RapportResource.php
└── Policies/             # Autorisations
    ├── MachinePolicy.php
    ├── TechnicienPolicy.php
    ├── TachePolicy.php
    └── RapportPolicy.php
```

## 🗄️ Base de Données

### Tables Principales

| Table | Description |
|-------|-------------|
| `users` | Utilisateurs du système (admin, technicien) |
| `machines` | Machines de l'atelier (ACTIF, PANNE, MAINTENANCE) |
| `techniciens` | Profils des techniciens |
| `taches` | Tâches de maintenance assignées |
| `interventions` | Interventions réalisées sur les machines |
| `rapports` | Rapports d'intervention |

### Relations

- **User** → Technicien (1:1)
- **Technicien** → Taches (1:N)
- **Technicien** → Interventions (1:N)
- **Machine** → Interventions (1:N)
- **Intervention** → Rapport (1:1)
- **Tache** → Technicien (N:1)

## 🔐 Rôles et Autorisations

### Rôles

- **admin** : Accès complet à toutes les fonctionnalités
- **technicien** : Accès limité à ses tâches et interventions

### Policies

Chaque modèle possède sa Policy définissant les règles d'accès :

- `MachinePolicy` : Admin uniquement
- `TechnicienPolicy` : Admin + propre profil
- `TachePolicy` : Admin + tâches assignées
- `RapportPolicy` : Admin + rapports créés

## 🚀 Utilisation

### Injection de Dépendances

Les Services sont injectés dans les Controllers via le constructeur :

```php
public function __construct(MachineService $machineService)
{
    $this->machineService = $machineService;
}
```

### Exemple d'utilisation dans un Controller

```php
public function index()
{
    $machines = $this->machineService->getAllMachines(15);
    return view('admin.machines.index', compact('machines'));
}

public function store(MachineRequest $request)
{
    $machine = $this->machineService->createMachine($request->validated());
    return redirect()->back()->with('success', 'Machine créée avec succès.');
}
```

## 📦 Seeders

Les seeders permettent de peupler la base de données avec des données de test :

```bash
php artisan db:seed --class=MachineSeeder
php artisan db:seed --class=TechnicienSeeder
php artisan db:seed --class=TacheSeeder
```

Ou tous les seeders :

```bash
php artisan db:seed
```

## 🎨 Frontend

- **Templates** : Blade
- **Styling** : Tailwind CSS
- **Interactivité** : Alpine.js
- **Layouts** : 
  - `layouts/admin.blade.php` pour l'interface admin
  - `layouts/technicien.blade.php` pour l'interface technicien

## 📝 Standards de Code

- **PSR-12** : Style de code conforme aux standards PHP
- **Type Hinting** : Types stricts pour les paramètres et retours
- **DocBlocks** : Documentation des classes et méthodes
- **Français** : Textes de l'interface en français

## 🔧 Routes

### Routes Admin

```php
Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard']);
    Route::resource('machines', MachineController::class);
    Route::resource('techniciens', TechnicienController::class);
    Route::resource('taches', TacheController::class);
});
```

### Routes Technicien

```php
Route::middleware(['auth', 'role:technicien'])->prefix('technicien')->group(function () {
    Route::get('/dashboard', [TechnicienController::class, 'dashboard']);
    Route::patch('/taches/{tache}/statut', [TechnicienController::class, 'updateStatut']);
    Route::post('/taches/{tache}/rapport', [TechnicienController::class, 'storeRapport']);
});
```

## 🔄 Flux de Données

1. **Requête HTTP** → Controller
2. **Validation** → FormRequest
3. **Logique métier** → Service
4. **Accès données** → Repository
5. **Réponse** → View ou Resource

## 📊 État des Machines

- **ACTIF** : Machine opérationnelle
- **PANNE** : Machine en panne, nécessite une intervention
- **MAINTENANCE** : Machine en maintenance préventive

## 📋 Statut des Tâches

- **en attente** : Tâche assignée mais non démarrée
- **en cours** : Tâche en cours de réalisation
- **terminé** : Tâche complétée avec rapport

## 🔔 Notifications

Le système supporte les notifications pour :
- Assignation de nouvelles tâches
- Changements de statut
- Alertes de maintenance

## 🧪 Tests

Les tests doivent être placés dans `tests/` :

- `Unit/` : Tests unitaires des Services et Repositories
- `Feature/` : Tests fonctionnels des Controllers et Routes

## 📚 Ressources

- [Laravel Documentation](https://laravel.com/docs/11.x)
- [Tailwind CSS](https://tailwindcss.com)
- [Alpine.js](https://alpinejs.dev)
