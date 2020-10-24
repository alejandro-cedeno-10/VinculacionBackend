<?php

namespace App\Http\Controllers;

use App\opcion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class OpcionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $opcion=Cache::remember('opcions',30/60, function()
            {
                // Caché válida durante 30 segundos.
                return opcion::all();
            });
           
        return response()->json([
			'status'=>true,
			'data'=>$opcion], 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'opcion'     => 'required|string|max:50',
            'idPregunta'     => 'required|numeric|exists:preguntas,idPregunta'                        
        ]);

        $opcion=Cache::remember('opcions',15/60, function() use ($request)
            {
                // Caché válida durante 15 segundos.
                return opcion::create($request->all());
            });
	
		$opcion->save();
	
        return response()->json(['data'=>$opcion,
            'message' => 'Opcion Creada'], 201)
            ->header('Location', env('APP_URL').'opcions/'.$opcion->idOpcion)
            ->header('Content-Type', 'application/json');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\opcion  $opcion
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $opcion=Cache::remember('opcions',30/60, function() use ($id)
		{
			// Caché válida durante 30 segundos.
			return opcion::find($id);
		});

		if(!$opcion)
		{
			return response()->json(
				['errors'=>array(['code'=>404,
				'message'=>'No se encuentra una opcion con ese identificador.',
				'identificador'=>$id
			])],404);
		}

		return response()->json([
			'status'=>true,
			'data'=>$opcion],200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\opcion  $opcion
     * @return \Illuminate\Http\Response
     */
    public function edit(opcion $opcion)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\opcion  $opcion
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $opcion=Cache::remember('opcions',15/60, function() use ($id)
		{
			// Caché válida durante 15 segundos.
			return opcion::find($id);
		});

		if(!$opcion)
		{
			return response()->json(
				['errors'=>array(['code'=>404,
				'message'=>'No se encuentra una opcion con ese identificador.',
				'identificador'=>$id
			])],404);
		}
		
		if($request->method() === 'PUT')
		{
            $request->validate([
                'opcion'     => 'required|string|max:50'
            ]);
			
			$opcion->opcion = $request->opcion;
		
			$opcion->save();

			return response()->json([
				'status'=>true,
				'data'=>$opcion],200)
				->header('Location', env('APP_URL').'opcions/'.$opcion->idOpcion)
				->header('Content-Type', 'application/json');
		}else{
			// Creamos una bandera para controlar si se ha modificado algún dato en el método PATCH.
			$bandera = false;

			if ($request->opcion!= null)
			{
				$request->validate([
                    'opcion'     => 'required|string|max:50'
                ]);

                $opcion->opcion = $request->opcion;
				$bandera=true;
			}
			
			if ($bandera)
			{
				// Almacenamos en la base de datos el registro.
				$opcion->save();
				return response()->json([
					'status'=>true,
					'data'=>$opcion],200)
					->header('Location', env('APP_URL').'opcions/'.$opcion->idOpcion)
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
     * @param  \App\opcion  $opcion
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $opcion=Cache::remember('opcions',15/60, function() use ($id)
		{
			// Caché válida durante 15 segundos.
			return opcion::find($id);  
		});
		
		if(!$opcion)
		{
			return response()->json(
				['errors'=>array(['code'=>404,
				'message'=>'No se encuentra una opcion con ese identificador.',
				'identificador'=>$id
			])],404);
		}

		$Pregunta=$opcion->Pregunta->first();

		if ($Pregunta)
		{
			$opcion->delete();
			
			return response()->json([
				'status'=>true,
				'message'=>'La opcion contaba con relaciones. Se ha eliminado la opcion correctamente.'
			],200);
			
		}   

		$opcion->delete();

        return response()->json([
			'status'=>true,
			'message'=>'Se ha eliminado la opcion correctamente.'
		],200);
    }
}
