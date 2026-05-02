<?php

namespace App\Services;

use App\Models\Tache;
use App\Repositories\TacheRepository;
use Illuminate\Pagination\LengthAwarePaginator;

class TacheService
{
    protected TacheRepository $repository;

    public function __construct(TacheRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getAllTaches(int $perPage = 15): LengthAwarePaginator
    {
        return $this->repository->all($perPage);
    }

    public function getTacheById(int $id): Tache
    {
        return $this->repository->find($id);
    }

    public function createTache(array $data): Tache
    {
        return $this->repository->create($data);
    }

    public function updateTache(int $id, array $data): Tache
    {
        return $this->repository->update($id, $data);
    }

    public function deleteTache(int $id): bool
    {
        return $this->repository->delete($id);
    }

    public function getTachesByStatut(string $statut): \Illuminate\Database\Eloquent\Collection
    {
        return $this->repository->findByStatut($statut);
    }

    public function getTachesByTechnicien(int $technicienId): \Illuminate\Database\Eloquent\Collection
    {
        return $this->repository->findByTechnicien($technicienId);
    }

    public function getTachesByPriorite(string $priorite): \Illuminate\Database\Eloquent\Collection
    {
        return $this->repository->findByPriorite($priorite);
    }

    public function updateStatut(int $id, string $statut): Tache
    {
        return $this->repository->updateStatut($id, $statut);
    }
}
