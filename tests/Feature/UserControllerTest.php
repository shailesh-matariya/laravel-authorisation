<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Gate;
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

        $usersCount = User::count();

        $response = $this->json('get', route('users.index'));

        if (Gate::forUser($user)->allows('viewAny', User::class)) {
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
            $response->assertJsonPath('count', $usersCount);
        } else {
            $response->assertStatus(403);
        }
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

        if (Gate::forUser($user)->allows('create', User::class)) {
            $response->assertStatus(200);
            $response->assertJsonStructure([
                'status',
                'user' => [
                    'name',
                    'email',
                ]
            ]);
        } else {
            $response->assertStatus(403);
        }
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

        if (Gate::forUser($user)->allows('view', $user)) {
            $response->assertStatus(200);
            $response->assertJsonStructure([
                'status',
                'user' => [
                    'name',
                    'email',
                ],
            ]);
        } else {
            $response->assertStatus(403);
        }
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

        if (Gate::forUser($user)->allows('update', $user)) {
            $response->assertStatus(200);
            $response->assertJsonStructure([
                'status',
                'user' => [
                    'name',
                    'email',
                ],
            ]);
        } else {
            $response->assertStatus(403);
        }
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

        if (Gate::forUser($user)->allows('delete', $user)) {
            $response->assertStatus(204);
        } else {
            $response->assertStatus(403);
        }
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

        if (Gate::forUser($user)->allows('assignRole', User::class)) {
            $response->assertStatus(200);
            $response->assertJsonStructure([
                'status'
            ]);
        } else {
            $response->assertStatus(403);
        }
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
