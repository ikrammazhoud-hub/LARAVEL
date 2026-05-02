<?php

namespace App\Services;

use App\Models\Technicien;
use App\Repositories\TechnicienRepository;
use Illuminate\Pagination\LengthAwarePaginator;

class TechnicienService
{
    protected TechnicienRepository $repository;

    public function __construct(TechnicienRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getAllTechniciens(int $perPage = 15): LengthAwarePaginator
    {
        return $this->repository->all($perPage);
    }

    public function getTechnicienById(int $id): Technicien
    {
        return $this->repository->find($id);
    }

    public function createTechnicien(array $data): Technicien
    {
        return $this->repository->create($data);
    }

    public function updateTechnicien(int $id, array $data): Technicien
    {
        return $this->repository->update($id, $data);
    }

    public function deleteTechnicien(int $id): bool
    {
        return $this->repository->delete($id);
    }

    public function getTechniciensBySpecialite(string $specialite): \Illuminate\Database\Eloquent\Collection
    {
        return $this->repository->findBySpecialite($specialite);
    }

    public function getTechniciensDisponibles(): \Illuminate\Database\Eloquent\Collection
    {
        return $this->repository->findAvailable();
    }
}
