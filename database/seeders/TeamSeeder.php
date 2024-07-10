<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Team;

class TeamSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //Tech Teams
        Team::factory()->create([
            'name' => 'iOS'
        ]);

        Team::factory()->create([
            'name' => 'Android'
        ]);

        Team::factory()->create([
            'name' => 'Systems'
        ]);

        //Other Teams
        Team::factory()->create([
            'name' => 'Marketing'
        ]);

        Team::factory()->create([
            'name' => 'Customer Success'
        ]);

        Team::factory()->create([
            'name' => 'Design'
        ]);

        //Functional Teams
        Team::factory()->create([
            'name' => 'Champions of Good'
        ]);

        Team::factory()->create([
            'name' => 'Payments'
        ]);
    }
}
