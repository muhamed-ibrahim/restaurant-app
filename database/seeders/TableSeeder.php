<?php

namespace Database\Seeders;

use Database\Factories\TableFactory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        TableFactory::new()->count(10)->create();
    }
}
