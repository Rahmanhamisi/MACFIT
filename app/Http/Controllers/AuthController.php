<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    // ===================== USER AUTH =====================

    // Register user
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:40',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:8|max:15|confirmed',
            'user_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'role_id' => 'nullable|exists:roles,id',
            'phoneNumber' => 'nullable|string',
            'gender' => 'nullable|string',
            'dob' => 'nullable|string',
            'gymLocation' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => 'Validation failed',
                'messages' => $validator->errors()
            ], 422);
        }

        $validated = $validator->validated();

        $role_id = $validated['role_id'] ?? Role::where('name', 'User')->first()?->id ?? 1;

        try {
            $user = new User();
            $user->name = $validated['name'];
            $user->email = $validated['email'];
            $user->password = Hash::make($validated['password']);
            $user->role_id = $role_id;
            $user->phoneNumber = $validated['phoneNumber'] ?? null;
            $user->gender = $validated['gender'] ?? null;
            $user->dob = $validated['dob'] ?? null;
            $user->gymLocation = $validated['gymLocation'] ?? null;
            $user->is_active = true; // ✅ Active by default
            $user->save();

            if ($request->hasFile('user_image')) {
                $user->user_image = $request->file('user_image')->store('user_images', 'public');
                $user->save();
            }

            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'message' => 'Registration successful',
                'user' => $user,
                'token' => $token
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Registration failed',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    // Login user
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => 'Validation failed',
                'messages' => $validator->errors()
            ], 422);
        }

        $validated = $validator->validated();
        $user = User::where('email', $validated['email'])->first();

        if (!$user || !Hash::check($validated['password'], $user->password)) {
            return response()->json(['error' => 'Invalid email or password'], 401);
        }

        if (!$user->is_active) {
            return response()->json([
                'message' => 'Your account is not active. Please contact admin.'
            ], 403);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Login successful',
            'user' => $user,
            'token' => $token
        ], 200);
    }

    // Logout user
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Logged out successfully'], 200);
    }

    // ===================== USER MANAGEMENT =====================

    // Fetch Users
    public function fetchUsers()
    {
        try {
            $users = User::with('role')->get(); // include all users
            return response()->json($users, 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to fetch users',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    // Activate user
    public function activateUser($id)
    {
        $user = User::findOrFail($id);
        $user->is_active = true;
        $user->save();

        return response()->json(['message' => 'User activated successfully'], 200);
    }

    // Deactivate user
    public function deactivateUser($id)
    {
        $user = User::findOrFail($id);
        $user->is_active = false;
        $user->save();

        return response()->json(['message' => 'User deactivated successfully'], 200);
    }

    // ===================== ROLE MANAGEMENT =====================

    // Fetch Roles
    public function fetchRoles()
    {
        try {
            $roles = Role::withCount('users')->get();
            return response()->json($roles, 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to fetch roles',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    // Add Role
    public function addRole(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|unique:roles,name',
            'abilities' => 'nullable|array'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => 'Validation failed',
                'messages' => $validator->errors()
            ], 422);
        }

        try {
            $role = new Role();
            $role->name = $request->name;
            $role->abilities = $request->abilities ? json_encode($request->abilities) : json_encode([]);
            $role->is_active = true; // ✅ Active by default
            $role->save();

            return response()->json([
                'message' => 'Role created successfully',
                'role' => $role
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to create role',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    // Activate Role
    public function activateRole($id)
    {
        $role = Role::findOrFail($id);
        $role->is_active = true;
        $role->save();

        return response()->json(['message' => 'Role activated successfully'], 200);
    }

    // Deactivate Role
    public function deactivateRole($id)
    {
        $role = Role::findOrFail($id);
        $role->is_active = false;
        $role->save();

        return response()->json(['message' => 'Role deactivated successfully'], 200);
    }
}