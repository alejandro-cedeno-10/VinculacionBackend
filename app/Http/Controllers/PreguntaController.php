<?php

namespace App\Http\Controllers;

use App\pregunta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class PreguntaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $pregunta=Cache::remember('preguntas',30/60, function()
            {
                // Caché válida durante 30 segundos.
                return pregunta::all();
            });
       
        return response()->json([
            'status'=>true,
            'data'=>$pregunta], 200);
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
            'pregunta'     => 'required|string|max:300'            
        ]);

        $pregunta=Cache::remember('preguntas',15/60, function() use ($request)
            {
                // Caché válida durante 15 segundos.
                return pregunta::create($request->all());
            });
	
		$pregunta->save();
	
        return response()->json(['data'=>$pregunta,
            'message' => 'Pregunta Creada'], 201)
            ->header('Location', env('APP_URL').'preguntas/'.$pregunta->idPregunta)
            ->header('Content-Type', 'application/json');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\pregunta  $pregunta
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $pregunta=Cache::remember('preguntas',30/60, function() use ($id)
		{
			// Caché válida durante 30 segundos.
			return pregunta::find($id);
		});

		if(!$pregunta)
		{
			return response()->json(
				['errors'=>array(['code'=>404,
				'message'=>'No se encuentra una pregunta con ese identificador.',
				'identificador'=>$id
			])],404);
		}

		return response()->json([
			'status'=>true,
			'data'=>$pregunta],200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\pregunta  $pregunta
     * @return \Illuminate\Http\Response
     */
    public function edit(pregunta $pregunta)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\pregunta  $pregunta
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $pregunta=Cache::remember('preguntas',15/60, function() use ($id)
		{
			// Caché válida durante 15 segundos.
			return pregunta::find($id);
		});

		if(!$pregunta)
		{
			return response()->json(
				['errors'=>array(['code'=>404,
				'message'=>'No se encuentra una pregunta con ese identificador.',
				'identificador'=>$id
			])],404);
		}
		
		if($request->method() === 'PUT')
		{
            $request->validate([
                'pregunta'     => 'required|string|max:300'
            ]);

            $pregunta->pregunta = $request->pregunta ;
		
			$pregunta->save();

			return response()->json([
				'status'=>true,
				'data'=>$pregunta],200)
				->header('Location', env('APP_URL').'preguntas/'.$pregunta->idPregunta)
				->header('Content-Type', 'application/json');
		}else{
			// Creamos una bandera para controlar si se ha modificado algún dato en el método PATCH.
			$bandera = false;

			if ($request->pregunta!= null)
			{
				$request->validate([
                    'pregunta'     => 'required|string|max:300'
                ]);

				$pregunta->pregunta = $request->pregunta ;
				$bandera=true;
			}
			
			if ($bandera)
			{
				// Almacenamos en la base de datos el registro.
				$pregunta->save();
				return response()->json([
					'status'=>true,
					'data'=>$pregunta],200)
					->header('Location', env('APP_URL').'preguntas/'.$pregunta->idPregunta)
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
     * @param  \App\pregunta  $pregunta
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $pregunta=Cache::remember('preguntas',15/60, function() use ($id)
		{
			// Caché válida durante 15 segundos.
			return pregunta::find($id);  
		});
		
		if(!$pregunta)
		{
			return response()->json(
				['errors'=>array(['code'=>404,
				'message'=>'No se encuentra una pregunta con ese identificador.',
				'identificador'=>$id
			])],404);
		}

        $Cuestionarios=$pregunta->Cuestionarios->first();

		$Opciones=$pregunta->Opciones->first();

		$Respuestas=$pregunta->Respuestas->first();

        if ($Cuestionarios || $Opciones || $Respuestas)
		{
			$pregunta->delete();
			return response()->json([
				'status'=>true,
				'message'=>'La pregunta contaba con relaciones. Se ha eliminado la pregunta correctamente.'
			],200);
			
		}   

		$pregunta->delete();

        return response()->json([
			'status'=>true,
			'message'=>'Se ha eliminado la pregunta correctamente.'
		],200);
    }
}
