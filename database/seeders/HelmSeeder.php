<?php

namespace Database\Seeders;

use App\Models\Helm;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class HelmSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Helm::factory()->count(20)->create();
    }
}
