<?php

namespace App\Http\Controllers;

use App\Tema;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class TemaController extends Controller
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
      		
	$tema=Cache::remember('temas',30/60, function()
        {
            // Caché válida durante 30 segundos.
            return Tema::all();
        }); 

    return response()->json([
		'status'=>true,
		'data'=>$tema], 200);

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
            'link'     => 'required|string|max:255',
            'id_unidad'     => 'required|numeric|exists:unidads,id_unidad'
        ]);

        $tema=Cache::remember('temas',15/60, function() use ($request)
            {
                // Caché válida durante 15 segundos.
                return Tema::create($request->all());
            });
	
		$tema->save();
	
        return response()->json(['data'=>$tema,
            'message' => 'tema Creada'], 201)
            ->header('Location', env('APP_URL').'temas/'.$tema->id_tema)
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
		
		$tema=Cache::remember('temas',30/60, function() use ($id)
		{
			// Caché válida durante 30 segundos.
			return Tema::find($id);
		});

		if(!$tema)
		{
			return response()->json(
				['errors'=>array(['code'=>404,
				'message'=>'No se encuentra un tema con ese identificador.',
				'identificador'=>$id
			])],404);
		}

		return response()->json([
			'status'=>true,
			'data'=>$tema],200);

    }


     /**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update(Request $request, $id)
	{
		$tema=Cache::remember('temas',15/60, function() use ($id)
		{
			// Caché válida durante 15 segundos.
			return Tema::find($id);
		});

		if(!$tema)
		{
			return response()->json(
				['errors'=>array(['code'=>404,
				'message'=>'No se encuentra un tema con ese identificador.',
				'identificador'=>$id
			])],404);
		}
		
		if($request->method() === 'PUT')
		{
            $request->validate([
                'nombre'     => 'required|string|max:50',
                'descripcion'     => 'required|string|max:255',
                'link'     => 'required|string|max:255'
            ]);

			
			$tema->nombre = $request->nombre;
			$tema->descripcion = $request->descripcion;
            $tema->link = $request->link;
            
			$tema->save();

			return response()->json([
				'status'=>true,
				'data'=>$tema],200)
				->header('Location', env('APP_URL').'temas/'.$tema->id_tema)
				->header('Content-Type', 'application/json');


		}else{
			// Creamos una bandera para controlar si se ha modificado algún dato en el método PATCH.
			$bandera = false;

			if ($request->nombre!= null)
			{
				$request->validate([
                    'nombre'     => 'required|string|max:50'
				]);

				$tema->nombre = $request->nombre ;
				$bandera=true;
			}

            if ($request->descripcion!= null)
			{
				$request->validate([
					'descripcion'     => 'required|string|max:255'
				]);
		
				$tema->descripcion = $request->descripcion;
				$bandera=true;
            }
            
            if ($request->link!= null)
			{
				$request->validate([
					'link'     => 'required|string|max:255'
				]);
		
				$tema->link = $request->link;
				$bandera=true;
			}

			if ($bandera)
			{
				// Almacenamos en la base de datos el registro.
				$tema->save();
				return response()->json([
					'status'=>true,
					'data'=>$tema],200)
					->header('Location', env('APP_URL').'temas/'.$tema->id_tema)
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
		$tema=Cache::remember('temas',15/60, function() use ($id)
		{
			// Caché válida durante 15 segundos.
			return Tema::find($id);  
		});
		
		if(!$tema)
		{
			return response()->json(
				['errors'=>array(['code'=>404,
				'message'=>'No se encuentra un tema con ese identificador.',
				'identificador'=>$id
			])],404);
		}

		$Unidad=$tema->Unidad->first();

		if($Unidad)
		{
			$tema->delete();
			
			return response()->json([
				'status'=>true,
				'message'=>'El tema contaba con relaciones. Se ha eliminado el tema correctamente.'
			],200);
			
		}   

		$tema->delete();

        return response()->json([
			'status'=>true,
			'message'=>'Se ha eliminado el tema correctamente.'
		],200);    

	}
}
