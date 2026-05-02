<?php

namespace App\Repositories;

use App\Models\Machine;
use Illuminate\Pagination\LengthAwarePaginator;

class MachineRepository
{
    public function all(int $perPage = 15): LengthAwarePaginator
    {
        return Machine::orderBy('created_at', 'desc')->paginate($perPage);
    }

    public function find(int $id): Machine
    {
        return Machine::findOrFail($id);
    }

    public function create(array $data): Machine
    {
        return Machine::create($data);
    }

    public function update(int $id, array $data): Machine
    {
        $machine = $this->find($id);
        $machine->update($data);
        return $machine;
    }

    public function delete(int $id): bool
    {
        $machine = $this->find($id);
        return $machine->delete();
    }

    public function findByStatut(string $statut): \Illuminate\Database\Eloquent\Collection
    {
        return Machine::where('etat', $statut)->get();
    }

    public function updateStatut(int $id, string $statut): Machine
    {
        $machine = $this->find($id);
        $machine->update(['etat' => $statut]);
        return $machine;
    }
}
