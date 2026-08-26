<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Campus;

class CampusSeeder extends Seeder
{
    public function run(): void
    {
        $campuses = [
            ['name' => 'Beirut', 'code' => 'BEY'],
            ['name' => 'Saida', 'code' => 'SAI'],
            ['name' => 'Nabatieh', 'code' => 'NAB'],
            ['name' => 'Tripoli', 'code' => 'TRI'],
            ['name' => 'Mount Lebanon', 'code' => 'MTL'],
            ['name' => 'Tyre', 'code' => 'TYR'],
            ['name' => 'Rayak', 'code' => 'RAY'],
            ['name' => 'Akkar', 'code' => 'AKK'],
        ];

        foreach ($campuses as $campus) {
            Campus::firstOrCreate(
                ['code' => $campus['code']],
                ['name' => $campus['name'], 'status' => 'active']
            );
        }
    }
}