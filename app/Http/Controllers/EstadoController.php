<?php

namespace App\Http\Controllers;

use App\estado;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class EstadoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $estado=Cache::remember('estados',30/60, function()
            {
                // Caché válida durante 30 segundos.
                return estado::all();
            });
        
        return response()->json([
            'status'=>true,
            'data'=>$estado], 200);
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
            'nombreEstado'     => 'required|string|max:50'            
        ]);

        $estado=Cache::remember('estados',15/60, function() use ($request)
            {
                // Caché válida durante 15 segundos.
                return estado::create($request->all());
            });
	
		$estado->save();
	
        return response()->json(['data'=>$estado,
            'message' => 'Estado Creado'], 201)
            ->header('Location', env('APP_URL').'estados/'.$estado->idEstado)
            ->header('Content-Type', 'application/json');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\estado  $estado
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $estado=Cache::remember('estados',30/60, function() use ($id)
		{
			// Caché válida durante 30 segundos.
			return estado::find($id);
		});

		if(!$estado)
		{
			return response()->json(
				['errors'=>array(['code'=>404,
				'message'=>'No se encuentra un estado con ese identificador.',
				'identificador'=>$id
			])],404);
		}

		return response()->json([
			'status'=>true,
			'data'=>$estado],200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\estado  $estado
     * @return \Illuminate\Http\Response
     */
    public function edit(estado $estado)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\estado  $estado
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $estado=Cache::remember('estados',15/60, function() use ($id)
		{
			// Caché válida durante 15 segundos.
			return estado::find($id);
		});

		if(!$estado)
		{
			return response()->json(
				['errors'=>array(['code'=>404,
				'message'=>'No se encuentra un estado con ese identificador.',
				'identificador'=>$id
			])],404);
		}
		
		if($request->method() === 'PUT')
		{
            $request->validate([
                'nombreEstado'     => 'required|string|max:50'
            ]);
			
			$estado->nombreEstado = $request->nombreEstado;
		
			$estado->save();

			return response()->json([
				'status'=>true,
				'data'=>$estado],200)
				->header('Location', env('APP_URL').'estados/'.$estado->idEstado)
				->header('Content-Type', 'application/json');
		}else{
			// Creamos una bandera para controlar si se ha modificado algún dato en el método PATCH.
			$bandera = false;

			if ($request->nombreEstado!= null)
			{
				$request->validate([
                    'nombreEstado'     => 'required|string|max:50'
                ]);

				$estado->nombreEstado = $request->nombreEstado;
				$bandera=true;
			}
			
			if ($bandera)
			{
				// Almacenamos en la base de datos el registro.
				$estado->save();
				return response()->json([
					'status'=>true,
					'data'=>$estado],200)
					->header('Location', env('APP_URL').'estados/'.$estado->idEstado)
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
     * @param  \App\estado  $estado
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $estado=Cache::remember('estados',15/60, function() use ($id)
		{
			// Caché válida durante 15 segundos.
			return estado::find($id);  
		});
		
		if(!$estado)
		{
			return response()->json(
				['errors'=>array(['code'=>404,
				'message'=>'No se encuentra un estado con ese identificador.',
				'identificador'=>$id
			])],404);
		}

		$Estudiantes=$estado->Estudiantes->first();

        $Matriculas=$estado->Matriculas->first();

		if ($Estudiantes || $Matriculas)
		{
			$estado->delete();
			
			return response()->json([
				'status'=>true,
				'message'=>'El estado contaba con relaciones. Se ha eliminado el estado correctamente.'
			],200);
			
		}   

		$estado->delete();

        return response()->json([
			'status'=>true,
			'message'=>'Se ha eliminado el estado correctamente.'
		],200);
    }
}
