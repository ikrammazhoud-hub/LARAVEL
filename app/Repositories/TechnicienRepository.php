<?php

namespace App\Repositories;

use App\Models\Technicien;
use Illuminate\Pagination\LengthAwarePaginator;

class TechnicienRepository
{
    public function all(int $perPage = 15): LengthAwarePaginator
    {
        return Technicien::with('user')->orderBy('created_at', 'desc')->paginate($perPage);
    }

    public function find(int $id): Technicien
    {
        return Technicien::with('user')->findOrFail($id);
    }

    public function create(array $data): Technicien
    {
        return Technicien::create($data);
    }

    public function update(int $id, array $data): Technicien
    {
        $technicien = $this->find($id);
        $technicien->update($data);
        return $technicien;
    }

    public function delete(int $id): bool
    {
        $technicien = $this->find($id);
        return $technicien->delete();
    }

    public function findBySpecialite(string $specialite): \Illuminate\Database\Eloquent\Collection
    {
        return Technicien::where('specialite', $specialite)->with('user')->get();
    }

    public function findAvailable(): \Illuminate\Database\Eloquent\Collection
    {
        return Technicien::whereDoesntHave('taches', function ($query) {
            $query->where('statut', 'en cours');
        })->with('user')->get();
    }
}
