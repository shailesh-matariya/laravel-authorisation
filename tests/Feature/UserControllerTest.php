<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    use WithFaker;

    /**** List user ****/

    public function index_with_role($role): void
    {
        $user = User::factory()
            ->create();

        $user->roles()->attach(Role::where('value', $role)->first());

        $this->actingAs($user);

        $response = $this->json('get', route('users.index'));

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'status',
            'users' => [
                'data' => [
                    '*' => [
                        'name',
                        'email',
                    ],
                ]
            ],
        ]);
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

    /**** Create user ****/

    public function store_with_role($role): void
    {
        $user = User::factory()
            ->create();

        $user->roles()->attach(Role::where('value', $role)->first());

        $this->actingAs($user);

        $password = $this->faker->password(8);

        $response = $this->postJson(route('users.store'), [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'password' => $password,
            'password_confirmation' => $password,
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'status',
            'user' => [
                'name',
                'email',
            ]
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

    /**** View user ****/

    public function show_with_role($role): void
    {
        $user = User::factory()
            ->create();

        $role = Role::where('value', $role)->first();

        $user->roles()->attach($role);

        $this->actingAs($user);

        $response = $this->getJson(route('users.show', compact('user')));

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'status',
            'user' => [
                'name',
                'email',
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

    /**** Update user ****/

    public function update_with_role($role): void
    {
        $user = User::factory()
            ->create();

        $user->roles()->attach(Role::where('value', $role)->first());

        $this->actingAs($user);

        $response = $this->putJson(route('users.update', compact('user')), [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'status',
            'user' => [
                'name',
                'email',
            ],
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

    /**** Delete user ****/

    public function destroy_with_role($role): void
    {
        $user = User::factory()
            ->create();

        $user->roles()->attach(Role::where('value', $role)->first());

        $this->actingAs($user);

        $response = $this->delete(route('users.destroy', compact('user')));

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

    /**** Asssign role ****/

    public function assign_role_with_role($role): void
    {
        $user = User::factory()
            ->create();

        $roles = Role::factory(2)->create()->pluck('id')->toArray();

        $user->roles()->attach(Role::where('value', $role)->first());

        $this->actingAs($user);

        $response = $this->postJson(route('users.assign-roles'), [
            'user_id' => $user->id,
            'roles' => $roles,
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'status'
        ]);
    }

    public function test_assign_role_with_admin()
    {
        $this->assign_role_with_role('admin');
    }

    public function test_assign_role_with_editor()
    {
        $this->assign_role_with_role('editor');
    }

    public function test_assign_role_with_viewer()
    {
        $this->assign_role_with_role('viewer');
    }
}
