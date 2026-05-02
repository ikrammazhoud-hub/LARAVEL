<?php

namespace App\Services;

use App\Models\Intervention;
use App\Repositories\InterventionRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * Service de gestion des Interventions.
 */
class InterventionService
{
    protected InterventionRepository $repository;

    public function __construct(InterventionRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Retourne toutes les interventions paginées.
     */
    public function getAllInterventions(int $perPage = 15): LengthAwarePaginator
    {
        return $this->repository->all($perPage);
    }

    /**
     * Trouve une intervention par ID.
     */
    public function getInterventionById(int $id): Intervention
    {
        return $this->repository->find($id);
    }

    /**
     * Crée une nouvelle intervention.
     */
    public function createIntervention(array $data): Intervention
    {
        return $this->repository->create($data);
    }

    /**
     * Met à jour une intervention existante.
     */
    public function updateIntervention(int $id, array $data): Intervention
    {
        return $this->repository->update($id, $data);
    }

    /**
     * Supprime une intervention.
     */
    public function deleteIntervention(int $id): bool
    {
        return $this->repository->delete($id);
    }

    /**
     * Retourne les interventions d'un technicien.
     */
    public function getInterventionsTechnicien(int $technicienId): Collection
    {
        return $this->repository->forTechnicien($technicienId);
    }

    /**
     * Retourne les interventions pour une machine donnée.
     */
    public function getInterventionsMachine(int $machineId): Collection
    {
        return $this->repository->forMachine($machineId);
    }

    /**
     * Clôture une intervention (statut → terminee + date_fin).
     */
    public function cloturerIntervention(int $id): Intervention
    {
        return $this->repository->update($id, [
            'statut'   => 'terminee',
            'date_fin' => now(),
        ]);
    }
}
