<?php

namespace App\Services;

use App\Models\Rapport;
use App\Repositories\RapportRepository;
use Illuminate\Pagination\LengthAwarePaginator;

class RapportService
{
    protected RapportRepository $repository;

    public function __construct(RapportRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getAllRapports(int $perPage = 15): LengthAwarePaginator
    {
        return $this->repository->all($perPage);
    }

    public function getRapportById(int $id): Rapport
    {
        return $this->repository->find($id);
    }

    public function createRapport(array $data): Rapport
    {
        return $this->repository->create($data);
    }

    public function updateRapport(int $id, array $data): Rapport
    {
        return $this->repository->update($id, $data);
    }

    public function deleteRapport(int $id): bool
    {
        return $this->repository->delete($id);
    }

    public function getRapportsByIntervention(int $interventionId): \Illuminate\Database\Eloquent\Collection
    {
        return $this->repository->findByIntervention($interventionId);
    }
}
