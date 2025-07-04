<?php

namespace Database\Seeders;

use App\Models\Can;
use Illuminate\Database\Seeder;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\User::factory()
        ->withPermission(Can::BE_AN_ADMIN)
        ->create([
            'name'  => 'Admin do CRM',
            'email' => 'admin@crm.com',
        ]);

    }
}
