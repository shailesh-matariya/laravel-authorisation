<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\AssignRolesRequest;
use App\Http\Requests\User\CreateUserRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Models\User;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

class UserController extends Controller
{
    public function index()
    {
        $users = User::latest('id', 'desc')
            ->paginate(10);

        return response()->json([
            'status' => true,
            'users' => $users,
            'count' => User::count(),
        ]);
    }

    public function store(CreateUserRequest $request)
    {
        $user = User::create($request->validated());

        return response()->json([
            'status' => true,
            'user' => $user
        ]);
    }

    public function show(User $user)
    {
        return response()->json([
            'status' => true,
            'user' => $user
        ]);
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        $user->fill($request->only('name', 'email'));
        $user->save();

        return response()->json([
            'status' => true,
            'user' => $user
        ]);
    }

    public function destroy(User $user)
    {
        $user->delete();

        return response(null, HttpResponse::HTTP_NO_CONTENT);
    }

    public function assignRoles(AssignRolesRequest $request)
    {
        $user = User::find($request->validated('user_id'));

        $user->assignRoles($request->roles);

        return response()->json([
            'status' => true
        ]);
    }

}
