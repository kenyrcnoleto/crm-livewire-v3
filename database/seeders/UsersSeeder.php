<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\User::factory()
        ->withPermission('be an admin')
        ->create([
            'name'  => 'Admin do CRM',
            'email' => 'admin@crm.com',
        ]);

    }
}
