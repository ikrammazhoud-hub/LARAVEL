<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\Alerte;
class GenerateData extends Command
{
    protected $signature = 'generate:data';

    protected $description = 'Generate fake IoT data';

    public function handle(): void
    {
        $temperature = rand(20, 100);
    $vibration = rand(0, 1);
    $courant = rand(1, 10);

    DB::table('donnees')->insert([
        'machine_id' => 1,
        'temperature' => $temperature,
        'vibration' => $vibration,
        'courant' => $courant,
        'created_at' => now(),
        'updated_at' => now()
    ]);

    // 🧠 IA simple (Alert)
    if ($temperature > 70 || $vibration == 1) {
        Alerte::create([
            'machine_id' => 1,
            'message' => '⚠️ Risque de panne détecté'
        ]);

        $this->info("⚠️ Alert created!");
    } else {
        $this->info("Data normal");
    }
}
}