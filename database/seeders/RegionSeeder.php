<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Region;

class RegionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Region::create([
            'name' => 'Sagamu',
        ]);

        Region::create([
            'name' => 'Iperu',
        ]);

        Region::create([
            'name' => 'Ikenne',
        ]);

        Region::create([
            'name' => 'Ilishan',
        ]);

        Region::create([
            'name' => 'Ode',
        ]);

        Region::create([
            'name' => 'Ogere',
        ]);
    }
}
