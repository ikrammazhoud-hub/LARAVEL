<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Alerte extends Model
{
    protected $fillable = [
        'machine_id',
        'message',
    ];

    public function machine()
    {
        return $this->belongsTo(Machine::class);
    }
}
