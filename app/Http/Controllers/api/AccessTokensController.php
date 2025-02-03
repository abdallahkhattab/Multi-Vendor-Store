<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\PersonalAccessToken;
use PDO;

class AccessTokensController extends Controller
{
    //

    public function register(Request $request){

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required'],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);
        
        return response()->json(['message' => 'User registered', 'user' => $user],200);
    }

    public function login(Request $request) {
        // Validate request data
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            'device_name' => 'string|max:255',
            'abillities' => 'nullable|array',
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

        //Revoke all Tokens logout from all devices
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

    public function LogOutFromAllDevices(){

        $user = Auth::guard('sanctum')->user();
        
        if(!$user){
            return response()->json(['error' => 'unauthorized'], 401);
        }

        $user->tokens()->delete();
        return response()->json(['message' => 'Logged out from all devices'], 200);

    }
    
    
}
