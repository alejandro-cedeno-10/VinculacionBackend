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
    //
    public function __construct()
	{
      /*$this->middleware('auth',['only'=>['user','logout']]); */ 
    }
    
    public function signup(Request $request)
    {
        $request->validate([
            'cedula'     => 'required|string|min:10|max:10|unique:users,cedula',
            'name'     => 'required|string|max:30',
            'lastname'     => 'required|string|max:30',
            'email'    => 'required|string|email|unique:users,email',
            'descripcion'     => 'nullable|string',
            'password' => 'required|string|confirmed',
            'avatar'     => 'nullable|image|mimes:jpeg,png,jpg,svg|max:5048',
        ]);

        $user=Cache::remember('users',15/60, function() use ($request)
            {
                // Caché válida durante 15 segundos.
                return User::create($request->all());
            });
        
       
        //Encripto clave 
        $user->password=bcrypt($request->password);
        $user->activation_token=Str::random(60);
        
        if($request->hasFile('avatar')){
            $avatar = $request->file('avatar');    
            $filename= $user->cedula.'.'.$avatar->getClientOriginalExtension();
            Image::make($avatar)->resize(300, 300)->save( public_path('/uploads/avatars/' . $filename ) );  
            $user->avatar=$filename;
        }

        $user->save();        

        $user->notify(new SignupActivate($user));

        return response()->json(['data'=>$user,
            'message' => 'Usuario Creado'], 201)
            ->header('Location', env('APP_URL').'users/'.$user->cedula)
            ->header('Content-Type', 'application/json');
    }


    public function login(Request $request)
    {
        $request->validate([
            'email'       => 'required|string|email',
            'password'    => 'required|string',
            'remember_me' => 'boolean',
        ]);

        
        $user=Cache::remember('users',30/60, function() use ($request)
            {
                // Caché válida durante 30 segundos.
                return User::where( 'email', $request->email )->first();
            });

        if( $user->active == false)
            
            return response( )->json([
                'success' => false,
                'message' => 'Su correo electrónico aún no ha sido validado, por favor verifique la bandeja de entrada de su dirección de correo.',
                'code'    => 401,
            ], 401 );

        $credentials = request(['email', 'password']);
        $credentials['active'] = 1;
        $credentials['deleted_at'] = null;
       
       
        if (!Auth::attempt($credentials)) {
            return response()->json([
                'message' => 'Unauthorized'
            ], 401);
        }
        $user = $request->user();
       
        $tokenResult = $user->createToken('Personal Access Token');
        $token = $tokenResult->token;
        if ($request->remember_me) {
            $token->expires_at = Carbon::now()->addWeeks(7);
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


    public function signupActivate($token)
    {
        $user=Cache::remember('users',15/60, function() use ($token)
        {
            // Caché válida durante 15 segundos.
            return  User::where('activation_token', $token)->first();
        });

        
        if (!$user) {
            return response()->json(['message' => 'El token de activación es inválido'], 404);
        }
        
        $user->active = true;
        $user->activation_token = '';
        $user->email_verified_at =Carbon::now()->toDateTimeString();
        
        $user->save();

        // Le asignamos el rol de Cliente
        $user->assignRole('cliente'); 

        /* $user->assignRole('SuperAdmin'); */
       

        // Retorna una respuesta 200 (Ok)
        return response( )->json([
            'success' => true,
            'message' => 'Su dirección de correo ha sido validada con éxito.',
            'code'    => 200,
        ], 200 );
    }

    public function logout(Request $request)
    {
       
        if ( ! DB::table( 'oauth_access_tokens' )->where( 'id', $request->user( )->token( )->id )->exists( ) )
        return response( )->json([
            'success' => false,
            'message' => 'No se encontró ninguna sesión de usuario activa.',
            'code'     => 404,
        ], 404 ); // Si no hay una sesión activa del usuario retorna un error 404 (Not Found)

        // Elimina el Token de acceso al sistema del usuario
        if($request->user()->token()->delete( )){
            return response()->json(['message' => 
            'Successfully logged out']);
        }
    }

    public function user(Request $request)
    {
        return response()->json($request->user());
    }
}
