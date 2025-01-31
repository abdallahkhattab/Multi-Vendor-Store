<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\PersonalAccessToken;

class AccessTokensController extends Controller
{
    //

    public function store(Request $request) {
        // Validate request data
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            'device_name' => 'string|max:255'
        ]);
    
        // Find user by email
        $user = User::where('email', $request->email)->first();
    
        // Check if user exists and password is correct
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['error' => 'Invalid credentials'], 401);
        }
    
        // Generate token for the user
        $device_name = $request->post('device_name', $request->userAgent());
        $token = $user->createToken($device_name);
    
        // Return token and user info
        return response()->json([
            'token' => $token->plainTextToken,
            'user' => $user
        ], 201);
    }
    

    public function destroy($token=null) {

        //Revoke all Tokens
        // $user->tokens()->delete();
        // Get authenticated user
        $user = Auth::guard('sanctum')->user();
    
        // Find the token in the database
        $personalAccessToken = PersonalAccessToken::findToken($token);
    
        // If token is invalid or does not belong to the user, return error
        if (!$personalAccessToken || $personalAccessToken->tokenable_id !== $user->id) {
            return response()->json(['error' => 'Invalid token or unauthorized'], 401);
        }
    
        // Revoke the token (log out the user from that session)
        $personalAccessToken->delete();
    
        return response()->json(['message' => 'Token has been revoked'], 200);
    }
    
    
}
