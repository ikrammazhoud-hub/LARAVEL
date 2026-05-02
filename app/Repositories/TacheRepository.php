<?php

namespace App\Repositories;

use App\Models\Tache;
use Illuminate\Pagination\LengthAwarePaginator;

class TacheRepository
{
    public function all(int $perPage = 15): LengthAwarePaginator
    {
        return Tache::with(['technicien.user', 'machine', 'rapports'])->orderBy('date_deadline')->paginate($perPage);
    }

    public function find(int $id): Tache
    {
        return Tache::with(['technicien.user', 'machine', 'rapports'])->findOrFail($id);
    }

    public function create(array $data): Tache
    {
        return Tache::create($data);
    }

    public function update(int $id, array $data): Tache
    {
        $tache = $this->find($id);
        $tache->update($data);
        return $tache;
    }

    public function delete(int $id): bool
    {
        $tache = $this->find($id);
        return $tache->delete();
    }

    public function findByStatut(string $statut): \Illuminate\Database\Eloquent\Collection
    {
        return Tache::where('statut', $statut)->with('technicien.user')->get();
    }

    public function findByTechnicien(int $technicienId): \Illuminate\Database\Eloquent\Collection
    {
        return Tache::where('technicien_id', $technicienId)->orderBy('date_deadline')->get();
    }

    public function findByPriorite(string $priorite): \Illuminate\Database\Eloquent\Collection
    {
        return Tache::where('priorite', $priorite)->with('technicien.user')->get();
    }

    public function updateStatut(int $id, string $statut): Tache
    {
        $tache = $this->find($id);
        $tache->update(['statut' => $statut]);
        return $tache;
    }
}
