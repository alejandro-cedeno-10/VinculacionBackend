<?php

namespace App\Http\Controllers;
use Image;

use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Cache;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;

class UserController extends Controller
{
	
	
 
	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */

	public function __construct()
	{	
		/* $this->middleware('auth:api');  
	    $this->middleware('role:SuperAdmin',['only'=>['index']]); 
		$this->middleware('role:cliente|SuperAdmin',['only'=>['show']]);  */ 
	}
	
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

	public function showRole()
	{
		$id=$_REQUEST['filter']['idPersona'];
		$user=Cache::remember('user',15/60, function() use ($id)
		{
			// Caché válida durante 15 segundos.
			return User::find($id);  
		});

		$Rol=null;

		if(!is_null($user->Estudiante)){
			$Rol="Estudiante";
		}

		if(!is_null($user->Profesor)){
			$Rol="Profesor";
		}

		if(!is_null($user->Representante)){
			$Rol="Representante";
		}

		if(!is_null($user->Dece)){
			$Rol="Dece";
		}

		return response()->json([
			'Rol'=>$Rol],200);

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
				'apellidoPaterno'     => 'required|string|max:30',
				'apellidoMaterno'     => 'required|string|max:30',
				'nombres'     => 'required|string|max:30',
				'direccion'     => 'required|string',
				'telefono'     => 'nullable|string|min:10|max:10',
				'sexo'         =>   'in:M,F',
				'fechaNacimiento'         =>  'required|date',
				'estadoCivil'         =>   'in:S,C,V,D,U'
			]);


			$user->apellidoPaterno = $request->apellidoPaterno;
			$user->apellidoMaterno = $request->apellidoMaterno;
			$user->nombres = $request->nombres;
			$user->direccion = $request->direccion;
			$user->telefono = $request->telefono;
			$user->sexo = $request->sexo;
			$user->fechaNacimiento = $request->fechaNacimiento;			
			$user->estadoCivil = $request->estadoCivil;

			// Almacenamos en la base de datos el registro.
			$user->save();

			return response()->json([
				'status'=>true,
				'data'=>$user],200)
				->header('Location', env('APP_URL').'users/'.$user->idPersona)
				->header('Content-Type', 'application/json');


		}else{
			// Creamos una bandera para controlar si se ha modificado algún dato en el método PATCH.
			$bandera = false;

			if ($request->apellidoPaterno!= null)
			{
				$request->validate([
					'apellidoPaterno'     => 'required|string|max:30',
				]);

				$user->apellidoPaterno = $request->apellidoPaterno ;
				$bandera=true;
			}

			if ($request->apellidoMaterno!= null)
			{
				$request->validate([
					'apellidoMaterno'     => 'required|string|max:30',
				]);
		
				$user->apellidoMaterno = $request->apellidoMaterno;
				$bandera=true;
			}

			if ($request->nombres!= null)
			{
				$request->validate([
					'nombres'    => 'required|string',
				]);
		
				$user->nombres = $request->nombres;
				$bandera=true;
			}

			if ($request->direccion!= null)
			{
				$request->validate([
					'direccion'     => 'required|string',
				]);
		
				$user->direccion = $request->direccion;
				$bandera=true;
			}

			if ($request->telefono!= null)
			{
				$request->validate([
					'telefono'     => 'nullable|string|min:10|max:10',
				]);
		
				$user->telefono = $request->telefono;
				$bandera=true;
			}

			if ($request->sexo!= null)
			{
				$request->validate([
					'sexo'         =>   'in:M,F',
				]);
		
				$user->sexo = $request->sexo;
				$bandera=true;
			}

			
			if ($request->fechaNacimiento!= null)
			{
				$request->validate([
					'fechaNacimiento'         =>  'required|date',
				]);
		
				$user->fechaNacimiento = $request->fechaNacimiento;
				$bandera=true;
			}
			
			if ($request->estadoCivil!= null)
			{
				$request->validate([
					'estadoCivil'         =>   'in:S,C,V,D,U'
				]);
		
				$user->estadoCivil = $request->estadoCivil;
				$bandera=true;
			}

			if ($request->password!= null)
			{
				$request->validate([
					'password' => 'required|string|confirmed',
				]);

				if($user->password==(bcrypt($request->password))){
					$user->password=bcrypt($request->password);
					$bandera=true;
				}else{
					return response()->json([
						'errors'=>array(['
						status'=>false,
						'message'=>'Contraseña incorrecta'])
					],200);
				}
			}

			if ($bandera)
			{
				// Almacenamos en la base de datos el registro.
				$user->save();
				return response()->json([
					'status'=>true,
					'data'=>$user],200)
					->header('Location', env('APP_URL').'users/'.$user->idPersona)
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
            'idPersona'     => 'required|string|min:10|max:10|exists:users,idPersona',
            'avatar'     => 'required|image|mimes:jpeg,png,jpg,svg|max:5048',
        ]);

		$user=Cache::remember('users',15/60, function() use ($request)
		{
			// Caché válida durante 15 segundos.
			return User::find($request->idPersona);
		});

		$avatar = $request->file('avatar');    
		$filename= $user->idPersona.'.'.$avatar->getClientOriginalExtension();
		Image::make($avatar)->resize(300, 300)->save( public_path('/uploads/avatars/'.$filename ) );  
		$user->avatar=$filename;
			
		$user->save();

		return response()->json([
			'status'=>true,
			'data'=>$user],200)
			->header('Location', env('APP_URL').'users/'.$user->idPersona)
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

		$user->delete();

        return response()->json([
			'status'=>true,
			'message'=>'Se ha eliminado el usuario correctamente.'
		],200);    

	}



}
