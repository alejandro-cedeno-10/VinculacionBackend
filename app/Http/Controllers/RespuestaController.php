<?php

namespace App\Http\Controllers;

use App\respuesta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class RespuestaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $respuesta=Cache::remember('respuestas',30/60, function()
            {
                // Caché válida durante 30 segundos.
                return respuesta::all();
            });
           
        return response()->json([
			'status'=>true,
			'data'=>$respuesta], 200);
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
            'respuesta'     => 'required|string|max:50',
            'idPregunta'     => 'required|numeric|exists:preguntas,idPregunta'                         
        ]);

        $respuesta=Cache::remember('respuestas',15/60, function() use ($request)
            {
                // Caché válida durante 15 segundos.
                return respuesta::create($request->all());
            });
	
		$respuesta->save();
	
        return response()->json(['data'=>$respuesta,
            'message' => 'Respuesta Creada'], 201)
            ->header('Location', env('APP_URL').'respuestas/'.$respuesta->idRespuesta)
            ->header('Content-Type', 'application/json');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\respuesta  $respuesta
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $respuesta=Cache::remember('respuestas',30/60, function() use ($id)
		{
			// Caché válida durante 30 segundos.
			return respuesta::find($id);
		});

		if(!$respuesta)
		{
			return response()->json(
				['errors'=>array(['code'=>404,
				'message'=>'No se encuentra una respuesta con ese identificador.',
				'identificador'=>$id
			])],404);
		}

		return response()->json([
			'status'=>true,
			'data'=>$respuesta],200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\respuesta  $respuesta
     * @return \Illuminate\Http\Response
     */
    public function edit(respuesta $respuesta)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\respuesta  $respuesta
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $respuesta=Cache::remember('respuestas',15/60, function() use ($id)
		{
			// Caché válida durante 15 segundos.
			return respuesta::find($id);
		});

		if(!$respuesta)
		{
			return response()->json(
				['errors'=>array(['code'=>404,
				'message'=>'No se encuentra una respuesta con ese identificador.',
				'identificador'=>$id
			])],404);
		}
		
		if($request->method() === 'PUT')
		{
            $request->validate([
                'respuesta'     => 'required|string|max:50'
            ]);
			
			$respuesta->respuesta = $request->respuesta;
		
			$respuesta->save();

			return response()->json([
				'status'=>true,
				'data'=>$respuesta],200)
				->header('Location', env('APP_URL').'respuestas/'.$respuesta->idRespuesta)
				->header('Content-Type', 'application/json');
		}else{
			// Creamos una bandera para controlar si se ha modificado algún dato en el método PATCH.
			$bandera = false;

			if ($request->respuesta!= null)
			{
				$request->validate([
                    'respuesta'     => 'required|string|max:50'
                ]);

				$respuesta->respuesta= $request->respuesta;
				$bandera=true;
			}
			
			if ($bandera)
			{
				// Almacenamos en la base de datos el registro.
				$respuesta->save();
				return response()->json([
					'status'=>true,
					'data'=>$respuesta],200)
					->header('Location', env('APP_URL').'respuestas/'.$respuesta->idRespuesta)
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
     * @param  \App\respuesta  $respuesta
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $respuesta=Cache::remember('respuestas',15/60, function() use ($id)
		{
			// Caché válida durante 15 segundos.
			return respuesta::find($id);  
		});
		
		if(!$respuesta)
		{
			return response()->json(
				['errors'=>array(['code'=>404,
				'message'=>'No se encuentra una respuesta con ese identificador.',
				'identificador'=>$id
			])],404);
		}

		$Pregunta=$respuesta->Pregunta->first();
		
		if ($Pregunta)
		{
			$respuesta->delete();
			
			return response()->json([
				'status'=>true,
				'message'=>'La respuesta contaba con relaciones. Se ha eliminado la respuesta correctamente.'
			],200);
			
		}   

		$respuesta->delete();

        return response()->json([
			'status'=>true,
			'message'=>'Se ha eliminado la respuesta correctamente.'
		],200);
    }
}
