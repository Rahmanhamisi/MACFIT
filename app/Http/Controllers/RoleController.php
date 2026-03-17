<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function __construct()
    {
        // Apply authorization to all methods in this controller
    }

    // Create Role
    public function createRole(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|unique:roles,name',
            'description' => 'nullable|string|max:1000',
        ]);

        try {
            $role = new Role();
            $role->name = $validated['name'];
            $role->description = $validated['description'] ?? null;
            $role->save();

            return response()->json($role, 201);
        } catch (\Exception $exception) {
            return response()->json([
                'error' => 'Failed to save Role',
                'message' => $exception->getMessage()
            ], 500);
        }
    }

    // Read All Roles
    public function readAllRoles()
    {
        try {
            $roles = Role::all();
            return response()->json($roles);
        } catch (\Exception $exception) {
            return response()->json([
                'error' => 'Failed to fetch Roles',
                'message' => $exception->getMessage()
            ], 500);
        }
    }

    // Read Single Role
    public function readRole($id)
    {
        try {
            $role = Role::findOrFail($id);
            return response()->json($role);
        } catch (\Exception $exception) {
            return response()->json([
                'error' => 'Failed to fetch the role',
                'message' => $exception->getMessage()
            ], 404);
        }
    }

    // Update Role
    public function updateRole(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|unique:roles,name,' . $id,
            'description' => 'nullable|string|max:1000',
        ]);

        try {
            $existingRole = Role::findOrFail($id);
            $existingRole->name = $validated['name'];
            $existingRole->description = $validated['description'] ?? null;
            $existingRole->save();

            return response()->json($existingRole);
        } catch (\Exception $exception) {
            return response()->json([
                'error' => 'Failed to update Role',
                'message' => $exception->getMessage()
            ], 500);
        }
    }

    // Delete Role
    public function deleteRole($id)
    {
        try {
            $role = Role::findOrFail($id);
            $role->delete();

            return response()->json([
                'message' => 'Role deleted successfully'
            ]);
        } catch (\Exception $exception) {
            return response()->json([
                'error' => 'Failed to delete Role',
                'message' => $exception->getMessage()
            ], 500);
        }
    }
}