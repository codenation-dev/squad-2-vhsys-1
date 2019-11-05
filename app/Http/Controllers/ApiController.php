<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Http\Requests\RegisterAuthRequest;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;



class ApiController extends Controller
{
    public $isLogged = true;


    public function register(RegisterAuthRequest $request)
    {
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->save();

        if ($this->isLogged) {
            return $this->login($request);
        }

        return response()->json([
            'success' => true,
            'data' =>$user
        ], 200);
    }


    public function login(Request $request)
    {
        $data = $request->only('email', 'password');
        $jwt_token = null;
        $jwt_token = JWTAuth::attempt($data);

        if (jwt_token) {
            return response()->json([
                'success' => true,
                'token' => $jwt_token,
            ], 200);
        }
        else {
            return response()->json([
                'success' => false,
                'message' => 'Invalid Email or Password'
                ], 401);
        }
    }

    public function logout(Request $request)
    {
        $this->validate($request, [
            'token' => 'required'
        ]);

        try {
            JWTAuth::invalidate($request->token);

            return response()->json([
                'success' => true,
                'message' => 'User logged out successfully'
            ], 200);
        } catch (JWTException $exception) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, the user cannot be logged out'
            ], 500);
        }
    }

    public function getAuthUser(Request $request)
    {
        $this->validate($request, [
            'token' => 'required'
        ]);

        $user = JWTAuth::authenticate($request->token);

        return response()->json([
            'user' => $user],200);
    }
}
