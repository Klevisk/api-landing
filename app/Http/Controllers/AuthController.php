<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Dotenv\Exception\ValidationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    //Register
public function register(RegisterRequest $request)
{
  $user = new User();

  $user->phone = $request->phone;
  $user->email = $request->email;
  $user->password = bcrypt($request->password);


  $user->save();



  return response()->json([

    'res' => true,
    'msg' => 'Usuario Registrado Correctamente'
            ],200);

}
//login
public function login(LoginRequest $request){
    $validator = $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    $user = User::where('email', $request->email)->first();

    if (! $user || ! Hash::check($request->password, $user->password)) {
        return response()->json([
            'errors' => [
                'msg' => ['*Los datos ingresados son incorrectos'],
            ],
        ],  422);
    }

    $token = $user->createToken($request->email)->plainTextToken;
    return response()->json([
        'res' => true,
        'token' => $token,
        'usuario' => $user
    ],  200);
}
//LogOut--------------------------------------------------------------*
public function logOut(Request $request){

    $user = Auth::user();

    if ($user) {

        $user->currentAccessToken()->delete();


        Auth::guard('web')->logout();

        return response()->json([
            'res' => true,
            'msg' => 'Has Cerrado Sesión Correctamente'
        ], 200);
    }


    return response()->json([
        'res' => false,
        'msg' => 'No hay usuario autenticado'
    ], 401);
}
}
