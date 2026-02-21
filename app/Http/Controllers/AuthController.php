<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:40',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:4',
            'user_image' => 'nullable|image|mimes:jpeg,png,jpg'

        ]);
        $role = Role::where('name', 'user')->first();
        if (!$role) {
            return response()->json([
                'error' => 'Role not found'
            ], 404);
        }
        $user = new User();
        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->role_id = $role->id;
        $user->password = Hash::make($validated['password']);

        if ($request->hasFile('user_image')) {
            $filename = $request->file('user_image')->store('users', 'public');
            $user->user_image = $filename;
        }else {
            $filename = null;
        }

            try {
                $user->save();
                return response()->json([
                    'message' => 'Registration successful',
                    'user' => $user
                ], 201);
            } catch (\Exception $exception) {
                return response()->json([
                    'error' => "Registration Failed",
                    'message' => $exception->getMessage()
                ], 400);
            }
    }

    public function login(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:4'
        ]);

        $user = User::where('email', $validated['email'])->first();

        if (!$user || !Hash::check($validated['password'], $user->password)) {
            return response()->json([
                'error' => 'Invalid Credentials'
            ], 401);
        }

        $token = $user->createToken("auth-token")->plainTextToken;

        return response()->json([
            'token' => $token,
            'message' => 'Login Successful',
            'user' => $user,
            'abilities' => $user->abilities(),
        ], 201);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json([
            'message' => 'Logout Successful'
        ]);
    }
}

