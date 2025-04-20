<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Models\User;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    use ApiResponseTrait;

    public function __construct()
    {
        $this->authorizeResource(User::class, 'user');
    }

    /**
     * Function to handle user listing
     */
    public function index()
    {
        $this->authorize('viewAny', Auth::user());

        $users = User::paginate();

        return $this->successResponse($users, 'Users retrieved successfully.', Response::HTTP_OK);
    }

    /**
     * Function to handle user retrieval
     */
    public function show(User $user)
    {
        $this->authorize('view', [Auth::user(), $user]);

        return $this->successResponse($user, 'User retrieved successfully.', Response::HTTP_OK);
    }

    /**
     * Function to handle user creation
     */
    public function store(UserRequest $request)
    {
        $this->authorize('create', Auth::user());

        $data = $request->validated();

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);

        $user->profile()->create([
            'address_id' => $data['address'],
            'contact_number' => $data['contact_number'],
            'postcode' => $data['postcode'],
            'gender' => $data['gender'],
        ]);

        $userRoles = Role::whereIn('name', $data['role'])->pluck('id')->toArray();
        $user->syncRoles($userRoles);

        return $this->successResponse($user, 'User created successfully.', Response::HTTP_CREATED);
    }

    /**
     * Function to handle user update
     */
    public function update(UserRequest $request, User $user)
    {
        $this->authorize('update', [Auth::user(), $user]);

        $data = $request->validated();

        $user->update([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);

        $user->profile()->update([
            'address_id' => $data['address'],
            'contact_number' => $data['contact_number'],
            'postcode' => $data['postcode'],
            'gender' => $data['gender'],
        ]);

        $userRoles = Role::whereIn('name', $data['role'])->pluck('id')->toArray();
        $user->syncRoles($userRoles);

        return $this->successResponse($user, 'User updated successfully.', Response::HTTP_OK);
    }

    /**
     * Function to handle user deletion
     */
    public function destroy(User $user)
    {
        $this->authorize('delete', [Auth::user(), $user]);

        $user->roles()->detach();
        $user->delete();

        return $this->successResponse(null, 'User deleted successfully.', Response::HTTP_NO_CONTENT);
    }
}
