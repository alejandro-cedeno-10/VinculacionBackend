<?php

namespace App\Http\Controllers;

use App\mensajes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class MensajesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $mensajes=Cache::remember('mensajes',30/60, function()
            {
                // Caché válida durante 30 segundos.
                return mensajes::all();
            });
           
        return response()->json([
			'status'=>true,
			'data'=>$mensajes], 200);
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
            'idPersona'     => 'required|string|max:10|exists:users,idPersona',
            'receptor'     => 'required|string|max:10|exists:users,idPersona',
            'mensaje'     => 'required|string|max:256'            
        ]);

        $mensajes=Cache::remember('mensajes',15/60, function() use ($request)
            {
                // Caché válida durante 15 segundos.
                return mensajes::create($request->all());
            });
	
		$mensajes->save();
	
        return response()->json(['data'=>$mensajes,
            'message' => 'Mensaje Creado'], 201)
            ->header('Location', env('APP_URL').'mensajes/'.$mensajes->idMensaje)
            ->header('Content-Type', 'application/json');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\mensajes  $mensajes
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $mensajes=Cache::remember('mensajes',30/60, function() use ($id)
		{
			// Caché válida durante 30 segundos.
			return mensajes::find($id);
		});

		if(!$mensajes)
		{
			return response()->json(
				['errors'=>array(['code'=>404,
				'message'=>'No se encuentra un mensaje con ese identificador.',
				'identificador'=>$id
			])],404);
		}

		return response()->json([
			'status'=>true,
			'data'=>$mensajes],200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\mensajes  $mensajes
     * @return \Illuminate\Http\Response
     */
    public function edit(mensajes $mensajes)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\mensajes  $mensajes
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $mensajes=Cache::remember('mensajes',15/60, function() use ($id)
		{
			// Caché válida durante 15 segundos.
			return mensajes::find($id);
		});

		if(!$mensajes)
		{
			return response()->json(
				['errors'=>array(['code'=>404,
				'message'=>'No se encuentra un mensaje con ese identificador.',
				'identificador'=>$id
			])],404);
		}
		
		if($request->method() === 'PUT')
		{
            $request->validate([
                'mensaje'     => 'required|string|max:256'
            ]);
			
			$mensajes->mensaje = $request->mensaje;
		
			$mensajes->save();

			return response()->json([
				'status'=>true,
				'data'=>$mensajes],200)
				->header('Location', env('APP_URL').'mensajes/'.$mensajes->idMensaje)
				->header('Content-Type', 'application/json');
		}else{
			// Creamos una bandera para controlar si se ha modificado algún dato en el método PATCH.
			$bandera = false;

			if ($request->mensaje!= null)
			{
				$request->validate([
                    'mensaje'     => 'required|string|max:256'
                ]);

				$mensajes->mensaje = $request->mensaje;
				$bandera=true;
			}
			
			if ($bandera)
			{
				// Almacenamos en la base de datos el registro.
				$mensajes->save();
				return response()->json([
					'status'=>true,
					'data'=>$mensajes],200)
					->header('Location', env('APP_URL').'mensajes/'.$mensajes->idMensaje)
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
     * @param  \App\mensajes  $mensajes
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $mensajes=Cache::remember('mensajes',15/60, function() use ($id)
		{
			// Caché válida durante 15 segundos.
			return mensajes::find($id);  
		});
		
		if(!$mensajes)
		{
			return response()->json(
				['errors'=>array(['code'=>404,
				'message'=>'No se encuentra un mensaje con ese identificador.',
				'identificador'=>$id
			])],404);
		}

		$Persona=$mensajes->Persona->first();

		$Receptor=$mensajes->Receptor->first();
      
		if ($Persona || $Receptor)
		{
			$mensajes->delete();
			
			return response()->json([
				'status'=>true,
				'message'=>'El mensaje contaba con relaciones. Se ha eliminado el mensaje correctamente.'
			],200);
			
		}   

		$curso->delete();

        return response()->json([
			'status'=>true,
			'message'=>'Se ha eliminado el mensaje correctamente.'
		],200);
    }
}
