<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function index()
    {
        try {
            return User::query()
                ->with(['roles', 'permissions'])
                ->paginate();
        } catch (\Exception $e) {
            Log::info($e->getMessage());
            return response()->json(['message' => 'error'], 500);
        }
    }

    public function store(Request $request): JsonResponse
    {
        // Validate the incoming request data
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required|string|min:8'],
        ]);

        // Create the new user with hashed password
        $user = User::query()
            ->create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return response()->json(['user' => $user], 201);
    }

    public function update(Request $request, $id): JsonResponse
    {
        // Find the user by ID
        $user = User::query()
            ->findOrFail($id);

        // Validate the incoming request data
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'password' => ['required|string|min:8'],
        ]);

        // Update user data
        $user->name = $request->name;
        $user->email = $request->email;

        // Update password if provided
        if ($request->has('password')) {
            $user->password = Hash::make($request->password);
        }

        // Save the changes
        $user->save();

        return response()->json(['user' => $user]);
    }

    public function destroy($id): JsonResponse
    {
        // Find the user by ID
        $user = User::query()
            ->findOrFail($id);

        // Delete the user
        $user->delete();

        return response()->json(null, 204);
    }


    // privileges
    public function assignRoleToUser(int $roleId, int $userId): JsonResponse
    {
        $user = User::query()
            ->findOrFail($userId);
        $role = Role::query()
            ->findOrFail($roleId);

        $user->assignRole($role);

        return response()->json(['message' => 'Role assigned to user successfully']);
    }

    public function assignPermissionToUser(int $permissionId, int $userId): JsonResponse
    {
        $user = User::query()->findOrFail($userId);
        $permission = Permission::query()->findOrFail($permissionId);

        $user->givePermissionTo($permission);

        return response()->json(['message' => 'Permission assigned to user successfully']);

    }

    public function assignPermissionToRole(int $permissionId, int $roleId): JsonResponse
    {
        $role = Role::query()
            ->findOrFail($roleId);
        $permission = Permission::query()
            ->findOrFail($permissionId);

        $role->givePermissionTo($permission);

        return response()->json(['message' => 'Permission assigned to role successfully']);

    }

    //roles
    public function getRoles(): LengthAwarePaginator
    {
        return Role::query()
            ->with('permissions')
            ->paginate();

    }

    public function storeRole(Request $request): JsonResponse
    {
        $request->validate([
            'name' => 'required|unique:roles,name',
        ]);

        $role = Role::create(['name' => $request->name, 'guard_name' => $request->guard_name]);

        return response()->json(['role' => $role], 201);
    }

    public function updateRole(Request $request, int $id): JsonResponse
    {
        $role = Role::query()
            ->findOrFail($id);

        $request->validate([
            'name' => 'required|unique:roles,name,' . $role->id,
        ]);

        $role->update(['name' => $request->name]);

        return response()->json(['role' => $role]);
    }

    public function destroyRole(int $id): JsonResponse
    {
        $role = Role::query()
            ->findOrFail($id);

        $role->delete();

        return response()->json(null, 204);
    }

    //permissions
    public function getPermission(): LengthAwarePaginator
    {
        return Permission::query()->paginate();
    }

    public function storePermission(Request $request): JsonResponse
    {
        $request->validate([
            'name' => 'required|unique:roles,name',
        ]);

        $role = Permission::create([
            'name' => $request->name,
            'guard_name' => $request->guard_name
        ]);

        return response()->json(['role' => $role], 201);
    }

    public function updatePermission(Request $request, int $id): JsonResponse
    {
        $permission = Permission::query()
            ->findOrFail($id);

        $request->validate([
            'name' => 'required|unique:roles,name,' . $permission->id,
        ]);

        $permission->update(['name' => $request->name]);

        return response()->json(['permission' => $permission]);
    }

    public function destroyPermission(int $id): JsonResponse
    {
        $permission = Permission::query()
            ->findOrFail($id);

        $permission->delete();

        return response()->json(null, 204);
    }
}
