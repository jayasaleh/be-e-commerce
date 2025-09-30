<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ApiKeySeeder extends Seeder
{

    public function run(): void
    {

        DB::table('api_keys')->truncate();

        DB::table('api_keys')->insert([
            [
                'name' => 'API Key E-commerce',
                'key' => Str::random(40),
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
