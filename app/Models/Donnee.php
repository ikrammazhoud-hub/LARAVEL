<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Donnee extends Model
{
    protected $fillable = [
        'machine_id',
        'temperature',
        'vibration',
        'courant',
    ];

    public function machine()
    {
        return $this->belongsTo(Machine::class);
    }
}
