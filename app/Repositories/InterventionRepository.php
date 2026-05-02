<?php

namespace App\Repositories;

use App\Models\Intervention;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

/**
 * Repository pour les opérations de données sur les Interventions.
 */
class InterventionRepository
{
    /**
     * Retourne toutes les interventions paginées avec relations.
     */
    public function all(int $perPage = 15): LengthAwarePaginator
    {
        return Intervention::with(['machine', 'technicien.user', 'tache'])
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }

    /**
     * Trouve une intervention par ID.
     */
    public function find(int $id): Intervention
    {
        return Intervention::with(['machine', 'technicien.user', 'tache', 'rapport'])
            ->findOrFail($id);
    }

    /**
     * Crée une intervention.
     */
    public function create(array $data): Intervention
    {
        return Intervention::create($data);
    }

    /**
     * Met à jour une intervention.
     */
    public function update(int $id, array $data): Intervention
    {
        $intervention = $this->find($id);
        $intervention->update($data);
        return $intervention;
    }

    /**
     * Supprime une intervention.
     */
    public function delete(int $id): bool
    {
        return $this->find($id)->delete();
    }

    /**
     * Retourne les interventions d'un technicien.
     */
    public function forTechnicien(int $technicienId): Collection
    {
        return Intervention::with(['machine', 'rapport'])
            ->where('technicien_id', $technicienId)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Retourne les interventions pour une machine donnée.
     */
    public function forMachine(int $machineId): Collection
    {
        return Intervention::with(['technicien.user', 'rapport'])
            ->where('machine_id', $machineId)
            ->orderBy('created_at', 'desc')
            ->get();
    }
}
