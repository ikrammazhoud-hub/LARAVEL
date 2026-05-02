<?php

namespace App\Services;

use App\Models\Machine;
use App\Repositories\MachineRepository;
use Illuminate\Pagination\LengthAwarePaginator;

class MachineService
{
    protected MachineRepository $repository;

    public function __construct(MachineRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getAllMachines(int $perPage = 15): LengthAwarePaginator
    {
        return $this->repository->all($perPage);
    }

    public function getMachineById(int $id): Machine
    {
        return $this->repository->find($id);
    }

    public function createMachine(array $data): Machine
    {
        return $this->repository->create($data);
    }

    public function updateMachine(int $id, array $data): Machine
    {
        return $this->repository->update($id, $data);
    }

    public function deleteMachine(int $id): bool
    {
        return $this->repository->delete($id);
    }

    public function getMachinesByStatut(string $statut): \Illuminate\Database\Eloquent\Collection
    {
        return $this->repository->findByStatut($statut);
    }

    public function updateStatut(int $id, string $statut): Machine
    {
        return $this->repository->updateStatut($id, $statut);
    }
}
