# 🏭 Atelier Pro — Plateforme de Gestion de Maintenance Industrielle

> **Stack** : PHP 8.3 · Laravel 13 · SQLite/MySQL · Tailwind CSS · Alpine.js

---

## 📋 Table des matières

1. [Vue d'ensemble](#vue-densemble)
2. [Architecture](#architecture)
3. [Modules](#modules)
4. [Structure des dossiers](#structure-des-dossiers)
5. [Base de données](#base-de-données)
6. [Rôles & Autorisations](#rôles--autorisations)
7. [Installation](#installation)
8. [Seeders & Données de test](#seeders--données-de-test)
9. [Standards de code](#standards-de-code)
10. [Routes](#routes)

---

## Vue d'ensemble

**Atelier Pro** est un système de gestion d'atelier de maintenance industrielle permettant à un administrateur de piloter machines, techniciens, tâches d'intervention et rapports, tandis que les techniciens gèrent leurs propres tâches depuis leur espace dédié.

---

## Architecture

Le projet suit le **pattern Repository → Service → Controller** (Clean Architecture allégée) :

```
Requête HTTP
    ↓
FormRequest          ← Validation des entrées
    ↓
Controller           ← Orchestration HTTP uniquement
    ↓
Service              ← Logique métier
    ↓
Repository           ← Accès aux données (Eloquent)
    ↓
Model                ← Entité + relations
    ↓
Response (View / Resource JSON)
```

### Principe de séparation

| Couche | Responsabilité | Ne doit PAS |
|---|---|---|
| **Controller** | Recevoir la requête, appeler le Service, retourner la vue | Contenir de la logique métier |
| **Service** | Orchestrer les règles métier | Faire des requêtes SQL directes |
| **Repository** | Requêtes Eloquent | Contenir des règles métier |
| **Model** | Relations, scopes, casts, constantes | Logique complexe |

---

## Modules

| Module | Description | Statut |
|---|---|---|
| 🔐 **Auth** | Connexion, profil, rôles (admin / technicien) | ✅ |
| 📊 **Dashboard** | KPIs, statistiques temps réel | ✅ |
| ⚙️ **Machines** | CRUD + états (ACTIF / PANNE / MAINTENANCE) | ✅ |
| 👷 **Techniciens** | CRUD + compte utilisateur lié | ✅ |
| 📋 **Tâches** | CRUD + assignation + suivi de statut | ✅ |
| 🔧 **Interventions** | CRUD + clôture automatique | ✅ |
| 📄 **Rapports** | Génération PDF (DomPDF) | ✅ |
| 🔔 **Notifications** | Alertes internes (tâche assignée, panne, statut) | ✅ |

---

## Structure des dossiers

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── AdminController.php           ← Dashboard admin + export PDF
│   │   ├── MachineController.php         ← CRUD machines
│   │   ├── AdminTechnicienController.php ← CRUD techniciens
│   │   ├── TacheController.php           ← CRUD tâches
│   │   ├── InterventionController.php    ← CRUD interventions + clôture
│   │   ├── NotificationController.php    ← Gestion notifications
│   │   ├── TechnicienController.php      ← Espace technicien
│   │   └── ProfileController.php
│   ├── Middleware/
│   │   └── CheckRole.php                 ← Middleware rôle (admin/technicien)
│   ├── Requests/
│   │   ├── MachineRequest.php
│   │   ├── TechnicienRequest.php
│   │   ├── TacheRequest.php
│   │   ├── InterventionRequest.php
│   │   └── RapportRequest.php
│   └── Resources/
│       ├── MachineResource.php
│       ├── TechnicienResource.php
│       ├── TacheResource.php
│       ├── InterventionResource.php
│       ├── RapportResource.php
│       └── NotificationResource.php
│
├── Models/
│   ├── User.php           ← role: admin | technicien
│   ├── Machine.php        ← ETATS [], scopes, badgeClass()
│   ├── Technicien.php     ← belongs User, has Taches & Interventions
│   ├── Tache.php          ← STATUTS [], PRIORITES [], estEnRetard()
│   ├── Intervention.php   ← belongs Machine + Technicien + Tache
│   ├── Rapport.php        ← belongs Intervention
│   └── Notification.php   ← scopes nonLues(), pourUtilisateur()
│
├── Services/
│   ├── DashboardService.php       ← Statistiques admin + technicien
│   ├── MachineService.php         ← CRUD + filtrage par statut
│   ├── TechnicienService.php      ← CRUD
│   ├── TacheService.php           ← CRUD + assignation
│   ├── InterventionService.php    ← CRUD + clôture
│   ├── RapportService.php         ← CRUD + génération PDF
│   └── NotificationService.php   ← Envoi & lecture notifications
│
├── Repositories/
│   ├── MachineRepository.php
│   ├── TechnicienRepository.php
│   ├── TacheRepository.php
│   ├── InterventionRepository.php
│   ├── RapportRepository.php
│   └── NotificationRepository.php
│
└── Policies/
    ├── MachinePolicy.php
    ├── TechnicienPolicy.php
    ├── TachePolicy.php
    ├── InterventionPolicy.php
    ├── RapportPolicy.php
    └── NotificationPolicy.php

database/
├── migrations/
│   ├── …_create_users_table.php
│   ├── …_add_role_to_users_table.php
│   ├── …_create_machines_table.php
│   ├── …_create_techniciens_table.php
│   ├── …_create_taches_table.php
│   ├── …_create_interventions_table.php
│   ├── …_create_rapports_table.php
│   ├── …_create_notifications_table.php
│   ├── …_add_columns_to_machines_table.php
│   ├── …_add_columns_to_techniciens_table.php
│   └── …_add_columns_to_rapports_table.php
├── seeders/
│   ├── DatabaseSeeder.php         ← Orchestre tout
│   ├── MachineSeeder.php
│   ├── TechnicienSeeder.php
│   ├── TacheSeeder.php
│   ├── InterventionSeeder.php
│   └── NotificationSeeder.php
└── factories/
    ├── UserFactory.php
    ├── MachineFactory.php
    └── TacheFactory.php

resources/views/
├── layouts/
│   ├── admin.blade.php             ← Layout admin (sidebar + header)
│   └── technicien.blade.php        ← Layout technicien
├── admin/
│   ├── dashboard.blade.php
│   ├── rapport_pdf.blade.php
│   ├── machines/
│   │   ├── index.blade.php
│   │   ├── create.blade.php
│   │   ├── edit.blade.php
│   │   └── show.blade.php
│   ├── techniciens/
│   │   ├── index.blade.php
│   │   ├── create.blade.php
│   │   ├── edit.blade.php
│   │   └── show.blade.php
│   ├── taches/
│   │   ├── index.blade.php
│   │   ├── create.blade.php
│   │   ├── edit.blade.php
│   │   └── show.blade.php
│   ├── interventions/
│   │   ├── index.blade.php
│   │   ├── create.blade.php
│   │   ├── edit.blade.php
│   │   └── show.blade.php
│   └── notifications/
│       └── index.blade.php
└── technicien/
    └── dashboard.blade.php
```

---

## Base de données

### Schéma des tables

| Table | Champs clés | Relations |
|---|---|---|
| `users` | name, email, password, **role** | → Technicien (1:1) |
| `machines` | nom, etat, localisation, numero_serie | → Interventions (1:N) |
| `techniciens` | user_id, specialite, telephone, matricule, disponible | → Taches (1:N), → Interventions (1:N) |
| `taches` | technicien_id, machine_id, titre, priorite, statut, date_deadline | → Technicien (N:1), → Machine (N:1) |
| `interventions` | machine_id, technicien_id, tache_id, description, statut, date_debut, date_fin | → Rapport (1:1) |
| `rapports` | intervention_id, contenu, observations, pieces_changees, recommandations | ← Intervention |
| `notifications` | user_id, type, titre, message, lu, data (JSON) | → User (N:1) |

### États machine

| Valeur | Signification |
|---|---|
| `ACTIF` | Machine opérationnelle |
| `PANNE` | Panne, intervention requise |
| `MAINTENANCE` | En maintenance préventive |

### Statuts de tâche

| Valeur | Signification |
|---|---|
| `en attente` | Assignée, non démarrée |
| `en cours` | En cours de réalisation |
| `terminé` | Complétée |

---

## Rôles & Autorisations

### Middleware `CheckRole`

Protège les groupes de routes selon le rôle de l'utilisateur :

```php
Route::middleware([CheckRole::class . ':admin'])->prefix('admin')...
Route::middleware([CheckRole::class . ':technicien'])->prefix('technicien')...
```

### Policies

| Policy | Admin | Technicien |
|---|---|---|
| `MachinePolicy` | Toutes actions | Lecture seule |
| `TechnicienPolicy` | Toutes actions | Son propre profil |
| `TachePolicy` | Toutes actions | Ses tâches uniquement |
| `InterventionPolicy` | Toutes actions | Ses interventions |
| `RapportPolicy` | Toutes actions | Ses rapports |
| `NotificationPolicy` | — | Ses notifications uniquement |

---

## Installation

```bash
# 1. Cloner et installer les dépendances
git clone <repo> && cd maintenance-platform
composer install
npm install

# 2. Configuration
cp .env.example .env
php artisan key:generate

# 3. Base de données
php artisan migrate
php artisan db:seed

# 4. Lancer le serveur de développement
php artisan serve
npm run dev
```

### Comptes de démonstration

| Rôle | Email | Mot de passe |
|---|---|---|
| Admin | `admin@atelier.com` | `password` |
| Technicien | `ali@atelier.com` | `password` |
| Technicien | `sara@atelier.com` | `password` |

---

## Seeders & Données de test

```bash
# Réinitialiser et remplir la base complète
php artisan migrate:fresh --seed

# Seeder individuel
php artisan db:seed --class=MachineSeeder
php artisan db:seed --class=TechnicienSeeder
php artisan db:seed --class=TacheSeeder
php artisan db:seed --class=InterventionSeeder
php artisan db:seed --class=NotificationSeeder
```

---

## Standards de code

- **PSR-12** — Style de code PHP standard
- **Type hints** stricts sur tous les paramètres et retours de méthodes
- **DocBlocks** sur toutes les classes et méthodes publiques
- **Français** — Tous les textes d'interface, messages de validation et commentaires
- **Nommage** : `camelCase` méthodes · `PascalCase` classes · `snake_case` colonnes BDD

### Exemple d'injection de dépendances

```php
// Controller → injecte le Service
public function __construct(MachineService $machineService)
{
    $this->machineService = $machineService;
}

// Service → injecte le Repository
public function __construct(MachineRepository $repository)
{
    $this->repository = $repository;
}
```

---

## Routes

### Admin (`/admin/*`)

| Méthode | URI | Nom | Action |
|---|---|---|---|
| GET | `/admin/dashboard` | `admin.dashboard` | Tableau de bord |
| GET/POST | `/admin/machines` | `admin.machines.*` | CRUD machines |
| GET/POST | `/admin/techniciens` | `admin.techniciens.*` | CRUD techniciens |
| GET/POST | `/admin/taches` | `admin.taches.*` | CRUD tâches |
| GET/POST | `/admin/interventions` | `admin.interventions.*` | CRUD interventions |
| PATCH | `/admin/interventions/{id}/cloturer` | `admin.interventions.cloturer` | Clôturer |
| GET | `/admin/notifications` | `admin.notifications.index` | Notifications |
| GET | `/admin/rapport/{id}/pdf` | `admin.rapport.pdf` | Export PDF |

### Technicien (`/technicien/*`)

| Méthode | URI | Nom | Action |
|---|---|---|---|
| GET | `/technicien/dashboard` | `technicien.dashboard` | Mon espace |
| PATCH | `/technicien/taches/{id}/statut` | `technicien.taches.statut` | Mettre à jour statut |
| POST | `/technicien/taches/{id}/rapport` | `technicien.taches.rapport` | Soumettre rapport |
| GET | `/technicien/notifications` | `technicien.notifications.index` | Mes notifications |

---

## 📚 Ressources

- Laravel 13 Documentation
- [Tailwind CSS](https://tailwindcss.com)
- [Alpine.js](https://alpinejs.dev)
- [DomPDF](https://github.com/barryvdh/laravel-dompdf)
#   L A R A V E L  
 
