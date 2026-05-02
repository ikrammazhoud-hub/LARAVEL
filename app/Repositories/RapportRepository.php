<?php

namespace App\Repositories;

use App\Models\Rapport;
use Illuminate\Pagination\LengthAwarePaginator;

class RapportRepository
{
    public function all(int $perPage = 15): LengthAwarePaginator
    {
        return Rapport::with('intervention.machine', 'intervention.technicien.user')
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }

    public function find(int $id): Rapport
    {
        return Rapport::with('intervention.machine', 'intervention.technicien.user')->findOrFail($id);
    }

    public function create(array $data): Rapport
    {
        return Rapport::create($data);
    }

    public function update(int $id, array $data): Rapport
    {
        $rapport = $this->find($id);
        $rapport->update($data);
        return $rapport;
    }

    public function delete(int $id): bool
    {
        $rapport = $this->find($id);
        return $rapport->delete();
    }

    public function findByIntervention(int $interventionId): \Illuminate\Database\Eloquent\Collection
    {
        return Rapport::where('intervention_id', $interventionId)->get();
    }
}
