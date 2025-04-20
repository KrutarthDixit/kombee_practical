<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreRolesRequest;
use App\Http\Requests\UpdateRolesRequest;
use App\Traits\ApiResponseTrait;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Symfony\Component\HttpFoundation\Response;

class RoleController extends Controller
{
    use ApiResponseTrait;

    /**
     * Function to handle role listing
     */
    public function index()
    {
        $roles = Role::paginate();

        return $this->successResponse($roles, 'Roles retrieved successfully.', Response::HTTP_OK);
    }

    /**
     * Function to handle role retrieval
     */
    public function show(Role $role)
    {
        return $this->successResponse($role, 'Role retrieved successfully.', Response::HTTP_OK);
    }

    /**
     * Function to handle role creation
     */
    public function store(StoreRolesRequest $request)
    {
        $data = $request->validated();

        $role = Role::create([
            'name' => $data['name'],
            'guard_name' => 'web',
        ]);

        return $this->successResponse($role, 'Role created successfully.', Response::HTTP_CREATED);
    }

    /**
     * Function to handle role update
     */
    public function update(UpdateRolesRequest $request, Role $role)
    {
        $data = $request->validated();

        $role->update([
            'name' => $data['name'],
            'guard_name' => 'web',
        ]);

        return $this->successResponse($role, 'Role updated successfully.', Response::HTTP_OK);
    }

    /**
     * Function to handle role deletion
     */
    public function destroy(Role $role)
    {
        $role->delete();

        return $this->successResponse(null, 'Role deleted successfully.', Response::HTTP_NO_CONTENT);
    }

    /**
     * Function to handle permission to role
     */
    public function assignPermissionToRole(Role $role, StoreRolesRequest $request)
    {
        $data = $request->validated();

        $permissions = Permission::whereIn('name', $data['permissions'])->pluck('id')->toArray();
        $role->syncPermissions($permissions);

        return $this->successResponse($role, 'Permissions assigned to role successfully.', Response::HTTP_OK);
    }
}
