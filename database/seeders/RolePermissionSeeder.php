<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RolePermissionSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            [
                'label' => 'View',
                'value' => 'view',
            ],
            [
                'label' => 'Create',
                'value' => 'create',
            ],
            [
                'label' => 'Edit',
                'value' => 'edit',
            ],
            [
                'label' => 'Delete',
                'value' => 'delete',
            ],
        ];

        $roles = [
            [
                'label' => 'Admin',
                'value' => 'admin',
                'permissions' => ['view', 'create', 'edit', 'delete'],
            ],
            [
                'label' => 'Editor',
                'value' => 'editor',
                'permissions' => ['view', 'create', 'edit'],
            ],
            [
                'label' => 'Viewer',
                'value' => 'viewer',
                'permissions' => ['view'],
            ],
        ];

        foreach ($permissions as $permission) {
            Permission::updateOrCreate([
                'value' => $permission['value'],
            ], [
                'label' => $permission['label'],
            ]);
        }

        foreach ($roles as $role) {
            $roleModel = Role::updateOrCreate([
                'value' => $role['value'],
            ], [
                'label' => $role['label'],
            ]);

            $permissions = Permission::whereIn('value', $role['permissions'])->get();
            $roleModel->permissions()->sync($permissions);
        }
    }
}
