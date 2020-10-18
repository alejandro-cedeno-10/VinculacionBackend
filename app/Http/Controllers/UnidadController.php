<?php

namespace App\Http\Controllers;

use App\Unidad;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class UnidadController extends Controller
{
    //
    // Configuramos en el constructor del 
	// Controlador la autenticación usando el Middleware auth.basic,
    public function __construct()
	{
		/* $this->middleware('auth',['only'=>['index']]); */ 
    }

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
    public function index()
	{
      		
	$unidad=Cache::remember('unidads',30/60, function()
        {
            // Caché válida durante 30 segundos.
            return Unidad::all();
        }); 

    return response()->json([
		'status'=>true,
		'data'=>$unidad], 200);

    }


    /**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(Request $request)
	{
		//
		$request->validate([
            'nombre'     => 'required|string|max:50',
            'descripcion'     => 'required|string|max:255',
            'resultado_aprendizaje'     => 'required|string|max:255',
            'id_materia'     => 'required|numeric|exists:materias,id_materia'
        ]);

        $unidad=Cache::remember('unidads',15/60, function() use ($request)
            {
                // Caché válida durante 15 segundos.
                return Unidad::create($request->all());
            });
	
		$unidad->save();
	
        return response()->json(['data'=>$unidad,
            'message' => 'Unidad Creada'], 201)
            ->header('Location', env('APP_URL').'unidads/'.$unidad->id_unidad)
            ->header('Content-Type', 'application/json');
    }
    

     /**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		
		$unidad=Cache::remember('unidads',30/60, function() use ($id)
		{
			// Caché válida durante 30 segundos.
			return Unidad::find($id);
		});

		if(!$unidad)
		{
			return response()->json(
				['errors'=>array(['code'=>404,
				'message'=>'No se encuentra una unidad con ese identificador.',
				'identificador'=>$id
			])],404);
		}

		return response()->json([
			'status'=>true,
			'data'=>$unidad],200);

    }


     /**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update(Request $request, $id)
	{
		$unidad=Cache::remember('unidads',15/60, function() use ($id)
		{
			// Caché válida durante 15 segundos.
			return Unidad::find($id);
		});

		if(!$unidad)
		{
			return response()->json(
				['errors'=>array(['code'=>404,
				'message'=>'No se encuentra una unidad con ese identificador.',
				'identificador'=>$id
			])],404);
		}
		
		if($request->method() === 'PUT')
		{
            $request->validate([
                'nombre'     => 'required|string|max:50',
                'descripcion'     => 'required|string|max:255',
                'resultado_aprendizaje'     => 'required|string|max:255'
            ]);

			
			$unidad->nombre = $request->nombre;
			$unidad->descripcion = $request->descripcion;
			$unidad->resultado_aprendizaje = $request->resultado_aprendizaje;
			
			$unidad->save();

			return response()->json([
				'status'=>true,
				'data'=>$unidad],200)
				->header('Location', env('APP_URL').'unidads/'.$unidad->id_unidad)
				->header('Content-Type', 'application/json');


		}else{
			// Creamos una bandera para controlar si se ha modificado algún dato en el método PATCH.
			$bandera = false;

			if ($request->nombre!= null)
			{
				$request->validate([
                    'nombre'     => 'required|string|max:50'
				]);

				$unidad->nombre = $request->nombre ;
				$bandera=true;
			}

			if ($request->descripcion!= null)
			{
				$request->validate([
					'descripcion'     => 'required|string|max:255'
				]);
		
				$unidad->descripcion = $request->descripcion;
				$bandera=true;
			}

			if ($request->resultado_aprendizaje!= null)
			{
				$request->validate([
                    'resultado_aprendizaje'     => 'required|string|max:255'
				]);
	
				$unidad->resultado_aprendizaje = $request->resultado_aprendizaje;
				$bandera=true;
			}

			if ($bandera)
			{
				// Almacenamos en la base de datos el registro.
				$unidad->save();
				return response()->json([
					'status'=>true,
					'data'=>$unidad],200)
					->header('Location', env('APP_URL').'unidads/'.$unidad->id_unidad)
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
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
		$unidad=Cache::remember('unidads',15/60, function() use ($id)
		{
			// Caché válida durante 15 segundos.
			return Unidad::find($id);  
		});
		
		if(!$unidad)
		{
			return response()->json(
				['errors'=>array(['code'=>404,
				'message'=>'No se encuentra una unidad con ese identificador.',
				'identificador'=>$id
			])],404);
		}

		$Materia=$unidad->Materia->first();

		$Actividads=$unidad->Actividads->first();

        $Temas=$unidad->Temas->first();
        
        $Recursos=$unidad->Recursos->first();

		if ($Materia || $Actividads || $Temas || $Recursos)
		{
			$unidad->delete();
			
			return response()->json([
				'status'=>true,
				'message'=>'La unidad contaba con relaciones. Se ha eliminado la unidad correctamente.'
			],200);
			
		}   

		$unidad->delete();

        return response()->json([
			'status'=>true,
			'message'=>'Se ha eliminado la unidad correctamente.'
		],200);    

	}
}
