<?php

namespace App\Http\Controllers;
use Image;

use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Cache;

class UserController extends Controller
{
	
	// Configuramos en el constructor del 
	// controlador la autenticación usando el Middleware auth.basic,
    public function __construct()
	{	
		$this->middleware('auth:api');  
	    $this->middleware('role:SuperAdmin',['only'=>['index']]); 
		$this->middleware('role:cliente|SuperAdmin',['only'=>['show']]);  
    }
 
	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
    public function index()
	{
      
			
		$user=Cache::remember('users',30/60, function()
            {
                // Caché válida durante 30 segundos.
                return User::all();
            });

            // Con caché.
        return response()->json([
			'status'=>true,
			'data'=>$user], 200);

    }


    /**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		//
	}

    /**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		
		$user=Cache::remember('users',30/60, function() use ($id)
		{
			// Caché válida durante 30 segundos.
			return User::find($id);
		});

		if(!$user)
		{
			return response()->json(
				['errors'=>array(['code'=>404,
				'message'=>'No se encuentra un usuario con ese identificador.',
				'identificador'=>$id
			])],404);
		}

		return response()->json([
			'status'=>true,
			'data'=>$user],200);

    }

    /**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update(Request $request, $id)
	{
		
		$user=Cache::remember('users',15/60, function() use ($id)
		{
			// Caché válida durante 15 segundos.
			return User::find($id);
		});

		if(!$user)
		{
			return response()->json(
				['errors'=>array(['code'=>404,
				'message'=>'No se encuentra un usuario con ese identificador.',
				'identificador'=>$id
			])],404);
		}

		if($request->method() === 'PUT')
		{
			$request->validate([
				'name'     => 'required|string|max:30',
				'lastname'     => 'required|string|max:30',
				'email'    => 'required|string|email|unique:users,email',
				'descripcion'     => 'nullable|string',
				'password' => 'required|string|confirmed'
			]);

			
			$user->name = $request->name;
			$user->lastname = $request->lastname;
			$user->descripcion = $request->descripcion;

			$user->password=bcrypt($request->password);
			/* $user->notify(new PasswordResetSuccess($user->password)); */


			// Almacenamos en la base de datos el registro.
			$user->save();

			return response()->json([
				'status'=>true,
				'data'=>$user],200)
				->header('Location', env('APP_URL').'users/'.$user->cedula)
				->header('Content-Type', 'application/json');


		}else{
			// Creamos una bandera para controlar si se ha modificado algún dato en el método PATCH.
			$bandera = false;

			if ($request->name!= null)
			{
				$request->validate([
					'name'     => 'required|string|max:30',
				]);

				$user->name = $request->name ;
				$bandera=true;
			}

			if ($request->lastname!= null)
			{
				$request->validate([
					'lastname'     => 'required|string|max:30',
				]);
		
				$user->lastname = $request->lastname;
				$bandera=true;
			}

			if ($request->email!= null)
			{
				$request->validate([
					'email'    => 'required|string|email|unique:users,email',
				]);
		
				$user->email = $request->email;
				$bandera=true;
			}

			if ($request->descripcion!= null)
			{
				$request->validate([
					'descripcion'     => 'required|string',
				]);
		
				$user->descripcion = $request->descripcion;
				$bandera=true;
			}

			if ($request->password!= null)
			{
				$request->validate([
					'password' => 'required|string|confirmed',
				]);

				$user->password=bcrypt($request->password);
				$bandera=true;
				/* $user->notify(new PasswordResetSuccess($user->password)); */
			}

			if ($bandera)
			{
				// Almacenamos en la base de datos el registro.
				$user->save();
				return response()->json([
					'status'=>true,
					'data'=>$user],200)
					->header('Location', env('APP_URL').'users/'.$user->cedula)
					->header('Content-Type', 'application/json');
			}
			else
			{
				return response()->json([
					'errors'=>array(['
					status'=>false,
					'message'=>'No se ha modificado ningún dato.'])
				],200);
			}
		
		}

	}

  	/**
	 * Update avatar specified resource in storage.
	 *
	 * 
	 * @return Response
	 */
	public function update_avatar(Request $request)
	{
		$request->validate([
            'cedula'     => 'required|string|min:10|max:10|exists:users,cedula',
            'avatar'     => 'required|image|mimes:jpeg,png,jpg,svg|max:5048',
        ]);

		$user=Cache::remember('users',15/60, function() use ($request)
		{
			// Caché válida durante 15 segundos.
			return User::find($request->cedula);
		});

		$avatar = $request->file('avatar');    
		$filename= $user->cedula.'.'.$avatar->getClientOriginalExtension();
		Image::make($avatar)->resize(300, 300)->save( public_path('/uploads/avatars/'.$filename ) );  
		$user->avatar=$filename;
			
		$user->save();

		return response()->json([
			'status'=>true,
			'data'=>$user],200)
			->header('Location', env('APP_URL').'users/'.$user->cedula)
			->header('Content-Type', 'application/json');
	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
		$user=Cache::remember('users',15/60, function() use ($id)
		{
			// Caché válida durante 15 segundos.
			return User::find($id);
		});

		if(!$user)
		{
			return response()->json(
				['errors'=>array(['code'=>404,
				'message'=>'No se encuentra un usuario con ese identificador.',
				'identificador'=>$id
			])],404);
		}

		if(($user->avatar)!='default.jpg')
		{
			$dirimgs = public_path().'/uploads/avatars/'.$user->avatar;
			@unlink($dirimgs);
		}

		$Cursos=$user->Cursos->first();

		$user->delete();

		if($Cursos)
		{
			$user->delete();
			return response()->json([
				'status'=>true,
				'message'=>'El usuario contaba con relaciones. Se ha eliminado el usuario correctamente.'
			],200);
			
		}   

		$user->delete();

        return response()->json([
			'status'=>true,
			'message'=>'Se ha eliminado el usuario correctamente.'
		],200);    

	}



}
