<?php

namespace Database\Seeders;

use App\Models\City;
use App\Models\State;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;

class States extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void{
        $jsonPath = public_path('tsconfig.json');
        $json = file_get_contents($jsonPath);
        $data = json_decode($json, true);
        foreach ($data as $datum) {
            $state=new State();
            $state->name='استان'.' '.$datum['province'];
            $state->save();
            foreach ($datum['cities'] as $city) {
                $cityRecord=new City();
                $cityRecord->state_id=intval($state->id);
                $cityRecord->name=$city;
                $cityRecord->save();
            }
        }
    }
}
