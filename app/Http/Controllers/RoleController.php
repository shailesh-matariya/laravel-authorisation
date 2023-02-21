<?php

namespace App\Http\Controllers;

use App\Http\Requests\Authorisation\Role\CreateRoleRequest;
use App\Http\Requests\Authorisation\Role\UpdateRoleRequest;
use App\Models\Role;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::latest('id')
            ->paginate(10);

        return response()->json([
            'status' => true,
            'roles' => $roles
        ]);
    }

    public function store(CreateRoleRequest $request)
    {
        $role = Role::create($request->validated());

        return response()->json([
            'status' => true,
            'role' => $role
        ]);
    }

    public function show(Role $role)
    {
        return response()->json([
            'status' => true,
            'role' => $role
        ]);
    }

    public function update(UpdateRoleRequest $request, Role $role)
    {
        $role->fill($request->only('label', 'value'));
        $role->save();

        return response()->json([
            'status' => true,
            'role' => $role
        ]);
    }

    public function destroy(Role $role)
    {
        $role->delete();

        return response()->setStatusCode(HttpResponse::HTTP_NO_CONTENT);
    }
}
