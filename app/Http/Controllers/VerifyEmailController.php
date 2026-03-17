<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User\User;
use Illuminate\Auth\Events\Verified;

class VerifyEmailController extends Controller
{
    public function verify(Request $request, $id, $hash){
        $user = User::findOrFail($id);
        if(!Hash_equals((string)$hash, sha1($user->email_verified_at))){
            return response()->json(['message' => 'Invalid verification link.'], 403);
        }
        if (!$user->hasVerifiedEmail()) {
            return response()->json(['message' => 'Email is already verified.'], 200);
        }

        $user->markEmailAsVerified();
        event(new Verified($user));

        $user->is_active = 1;
        $user->save();
        return response()->json(['message' => "Email verified successfully!"]);
    }
}
