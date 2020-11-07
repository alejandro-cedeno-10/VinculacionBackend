<?php

namespace App\Http\Controllers;

use Image;

use App\User;
use App\Notifications\SignupActivate;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;


class AuthController extends Controller
{
    
    
    public function signup(Request $request)
    {
        $request->validate([
            'idPersona'     => 'required|string|min:10|max:10|unique:users,idPersona',
            'apellidoPaterno'     => 'required|string|max:30',
            'apellidoMaterno'     => 'required|string|max:30',
            'nombres'     => 'required|string|max:30',
            'direccion'     => 'required|string',
            'telefono'     => 'nullable|string|min:10|max:10',
            'sexo'         =>   'in:M,F',
            'fechaNacimiento'         =>  'required|date',
            'estadoCivil'         =>   'in:S,C,V,D,U',
            'avatar'     => 'nullable|image|mimes:jpeg,png,jpg,svg|max:5048',
        ]);
        

        $user=Cache::remember('users',15/60, function() use ($request)
            {
                // Caché válida durante 15 segundos.
                return User::create($request->all());
            });
        
       
        //Encripto clave 
        $user->password=bcrypt($request->idPersona);

        /* $idPersona = substr($request->idPersona, -4);  */

        $user->email=$request->apellidoPaterno.$request->idPersona.'@'.'nacional'.'.'.'edu'.'.'.'ec';

        if($request->hasFile('avatar')){
            $avatar = $request->file('avatar');    
            $filename= $user->idPersona.'.'.$avatar->getClientOriginalExtension();
            Image::make($avatar)->resize(300, 300)->save( public_path('/uploads/avatars/' . $filename ) );  
            $user->avatar=$filename;
        }

        $user->save();        

        return response()->json(['data'=>$user,
            'message' => 'Usuario Creado'], 201)
            ->header('Location', env('APP_URL').'users/'.$user->idPersona)
            ->header('Content-Type', 'application/json');
    }


    public function login(Request $request)
    {
        $request->validate([
            'email'       => 'required|string|email',
            'password'    => 'required|string',
            'remember_me' => 'boolean',
        ]);
        $credentials = request(['email', 'password']);
        if (!Auth::attempt($credentials)) {
            return response()->json([
                'message' => 'Unauthorized'], 401);
        }
        $user = $request->user();
        $tokenResult = $user->createToken('Personal Access Token');
        $token = $tokenResult->token;
        if ($request->remember_me) {
            $token->expires_at = Carbon::now()->addWeeks(1);
        }
        $token->save();
        return response()->json([
            'access_token' => $tokenResult->accessToken,
            'token_type'   => 'Bearer',
            'expires_at'   => Carbon::parse(
                $tokenResult->token->expires_at)
                    ->toDateTimeString(),
        ]);
    }


    public function logout(Request $request)
    {
       
        $request->user()->token()->revoke();
        return response()->json(['message' => 
            'Successfully logged out']);
    }

    public function user(Request $request)
    {
        return response()->json($request->user());
    }
}
