<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = [
            [
                'name' => 'Администратор',
                'key' => 'admin'
            ],
            [
                'name' => 'Сотрудник',
                'key' => 'employee'
            ],
            [
                'name' => 'Мененджер',
                'key' => 'manager'
            ]
        ];

        foreach ($roles as $role) {
            Role::create($role);
        }
    }
}
