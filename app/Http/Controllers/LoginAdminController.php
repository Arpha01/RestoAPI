<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class LoginAdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum')->only('logout');        
    }

    public function login(Request $request) 
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required'
        ],[
            'email.required' => 'Email belum diisi',
            'email.email' => 'Email tidak valid',
            'password.required' => 'password belum diisi'
        ]);

        if($validator->fails()) {
            $response = [
                'status' => 'error',
                'message' => 'Validasi error',
                'errors' => $validator->errors(),
                'content' => null
            ];
            
            return response()->json($response, 400);
        }

        $admin = Admin::where('email', $request->email)->first();

        if(Hash::check($request->password, $admin->password)) {
            $token = $admin->createToken('auth_token', ['role:admin'])->plainTextToken;

            $response = [
                'status' => 'success',
                'message' => 'Login successfully',
                'errors' => null,
                'content' => [
                    'status_code' => 200,
                    'access_token' => $token,
                    'token_type' => 'Bearer',
                ]
            ];

            return response()->json($response,200);

        }

        $response = [
            'status' => 'error',
            'message' => 'Unathorized',
            'errors' => null,
            'content' => null,
        ];

        return response()->json($response, 401);
    }

    public function logout(Request $request) 
    {
        $user = $request->user('admin');

        $user->currentAccessToken()->delete();

        $response = [
            'status' => 'success',
            'message' => 'Logout successfully',
            'errors' => null,
            'content' => null,
            
        ];

        return response()->json($response, 200);
    }
}
