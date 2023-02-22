<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RoleControllerTest extends TestCase
{
    use WithFaker;

    /**** List role ****/

    public function index_with_role($role): void
    {
        $user = User::factory()
            ->create();

        $user->roles()->attach(Role::where('value', $role)->first());

        $this->actingAs($user);

//        $roles = Role::factory()->count(10)->create();
        $response = $this->json('get', route('roles.index'));

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'status',
            'roles' => [
                'data' => [
                    '*' => [
                        'label',
                        'value',
                    ],
                ]
            ],
        ]);

        $rolesCount = Role::count();
        // Assert for the total roles count
        // Response must have total count equals to the $rolesCount
    }

    public function test_index_with_admin()
    {
        $this->index_with_role('admin');
    }

    public function test_index_with_editor()
    {
        $this->index_with_role('editor');
    }

    public function test_index_with_viewer()
    {
        $this->index_with_role('viewer');
    }

    /**** Create role ****/

    public function store_with_role($role): void
    {
        $user = User::factory()
            ->create();

        $user->roles()->attach(Role::where('value', $role)->first());

        $this->actingAs($user);

        $response = $this->postJson(route('roles.store'), [
            'label' => $this->faker->unique()->word(),
            'value' => $this->faker->unique()->slug(2),
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'status',
            'role'
        ]);
    }

    public function test_store_with_admin()
    {
        $this->store_with_role('admin');
    }

    public function test_store_with_editor()
    {
        $this->store_with_role('editor');
    }

    public function test_store_with_viewer()
    {
        $this->store_with_role('viewer');
    }

    /**** View role ****/

    public function show_with_role($role): void
    {
        $user = User::factory()
            ->create();

        $role = Role::where('value', $role)->first();

        $user->roles()->attach($role);

        $this->actingAs($user);

        $response = $this->getJson(route('roles.show', compact('role')));

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'status',
            'role' => [
                'label',
                'value',
            ],
        ]);
    }

    public function test_show_with_admin()
    {
        $this->show_with_role('admin');
    }

    public function test_show_with_editor()
    {
        $this->show_with_role('editor');
    }

    public function test_show_with_viewer()
    {
        $this->show_with_role('viewer');
    }

    /**** Update role ****/

    public function update_with_role($role): void
    {
        $user = User::factory()
            ->create();

        $roleModel = Role::factory()
            ->create();

        $user->roles()->attach(Role::where('value', $role)->first());

        $this->actingAs($user);

        $response = $this->putJson(route('roles.update', ['role' => $roleModel]), [
            'label' => $this->faker->unique()->word(),
            'value' => $this->faker->unique()->slug(2),
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'status',
            'role'
        ]);
    }

    public function test_update_with_admin()
    {
        $this->update_with_role('admin');
    }

    public function test_update_with_editor()
    {
        $this->update_with_role('editor');
    }

    public function test_update_with_viewer()
    {
        $this->update_with_role('viewer');
    }

    /**** Delete role ****/

    public function destroy_with_role($role): void
    {
        $user = User::factory()
            ->create();

        $roleModel = Role::factory()
            ->create();

        $user->roles()->attach(Role::where('value', $role)->first());

        $this->actingAs($user);

        $response = $this->delete(route('roles.destroy', ['role' => $roleModel]));

        $response->assertStatus(204);
    }

    public function test_destroy_with_admin()
    {
        $this->destroy_with_role('admin');
    }

    public function test_destroy_with_editor()
    {
        $this->destroy_with_role('editor');
    }

    public function test_destroy_with_viewer()
    {
        $this->destroy_with_role('viewer');
    }
}
