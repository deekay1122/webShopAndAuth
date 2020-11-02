<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('products')->insert([
            'name' => 'Blue Bossa',
            'description' => 'Great Song',
            'price' => 500,
            'photo_url' => 'http',
            'created_at' => now(),
        ]);

        DB::table('products')->insert([
            'name' => 'Autumn Leaves',
            'description' => 'Greatest Song',
            'price' => 500,
            'photo_url' => 'http',
            'created_at' => now(),
        ]);
    }
}
