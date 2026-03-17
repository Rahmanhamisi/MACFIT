<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Faker\Provider\PhoneNumber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    // Register user
    public function register(Request $request)
    {
        // Validate input
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:40',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:8|max:15|confirmed',
            'user_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'role_id' => 'nullable|exists:roles,id',
            'phoneNumber'=>'nullable|string',
            'gender'=>'nullable|string',
            'dob'=>'nullable|string',
            'gymLocation'=>'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => 'Validation failed',
                'messages' => $validator->errors()
            ], 422);
        }

        $validated = $validator->validated();

        // Default role if not provided
        if (isset($validated['role_id'])) {
            $role_id = $validated['role_id'];
        } else {
            $role = Role::where('name', 'User')->first();
            $role_id = $role ? $role->id : 1; // fallback to id 1 if Role missing
        }

        try {
            $user = new User();
            $user->name = $validated['name'];
            $user->email = $validated['email'];
            $user->password = Hash::make($validated['password']);
            $user->role_id = $role_id;
            $user->phoneNumber = $validated['phoneNumber'];
            $user->gender = $validated['gender'];
            $user->dob = $validated['dob'];
            $user->gymLocation = $validated['gymLocation'];
            $user->is_active = true;

            $user->save();

            if ($request->hasFile('user_image')) {
                $user->user_image = $request->file('user_image')->store('user_images', 'public');
            }

            $user->save();

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
            return response()->json([
                'error' => 'Invalid email or password'
            ], 401);
        }

        if (!$user->is_active) {
            return response()->json([
                'message' => 'Your account is not active. Please verify your email.'
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

        return response()->json([
            'message' => 'Logged out successfully'
        ], 200);
    }
}
