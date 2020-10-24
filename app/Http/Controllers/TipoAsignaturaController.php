<?php

namespace App\Http\Controllers;

use App\tipo_asignatura;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class TipoAsignaturaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tipo_asignatura=Cache::remember('tipo_asignaturas',30/60, function()
            {
                // Caché válida durante 30 segundos.
                return tipo_asignatura::all();
            });
           
        return response()->json([
			'status'=>true,
			'data'=>$tipo_asignatura], 200);
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
            'nombreTipoAsignatura'     => 'required|string|max:75'                       
        ]);

        $tipo_asignatura=Cache::remember('tipo_asignaturas',15/60, function() use ($request)
            {
                // Caché válida durante 15 segundos.
                return tipo_asignatura::create($request->all());
            });
	
		$tipo_asignatura->save();
	
        return response()->json(['data'=>$tipo_asignatura,
            'message' => 'Tipo de Asignatura Creada'], 201)
            ->header('Location', env('APP_URL').'tipo_asignaturas/'.$tipo_asignatura->idTipoAsignatura)
            ->header('Content-Type', 'application/json');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\tipo_asignatura  $tipo_asignatura
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $tipo_asignatura=Cache::remember('tipo_asignaturas',30/60, function() use ($id)
		{
			// Caché válida durante 30 segundos.
			return tipo_asignatura::find($id);
		});

		if(!$tipo_asignatura)
		{
			return response()->json(
				['errors'=>array(['code'=>404,
				'message'=>'No se encuentra un tipo de asignatura con ese identificador.',
				'identificador'=>$id
			])],404);
		}

		return response()->json([
			'status'=>true,
			'data'=>$tipo_asignatura],200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\tipo_asignatura  $tipo_asignatura
     * @return \Illuminate\Http\Response
     */
    public function edit(tipo_asignatura $tipo_asignatura)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\tipo_asignatura  $tipo_asignatura
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $tipo_asignatura=Cache::remember('tipo_asignaturas',15/60, function() use ($id)
		{
			// Caché válida durante 15 segundos.
			return tipo_asignatura::find($id);
		});

		if(!$tipo_asignatura)
		{
			return response()->json(
				['errors'=>array(['code'=>404,
				'message'=>'No se encuentra un tipo de asignatura con ese identificador.',
				'identificador'=>$id
			])],404);
		}
		
		if($request->method() === 'PUT')
		{
            $request->validate([
                'nombreTipoAsignatura'     => 'required|string|max:75'
            ]);
			
			$tipo_asignatura->nombreTipoAsignatura= $request->nombreTipoAsignatura;
		
			$tipo_asignatura->save();

			return response()->json([
				'status'=>true,
				'data'=>$tipo_asignatura],200)
				->header('Location', env('APP_URL').'tipo_asignaturas/'.$tipo_asignatura->idTipoAsignatura)
				->header('Content-Type', 'application/json');
		}else{
			// Creamos una bandera para controlar si se ha modificado algún dato en el método PATCH.
			$bandera = false;

			if ($request->nombreTipoAsignatura!= null)
			{
				$request->validate([
                    'nombreTipoAsignatura'     => 'required|string|max:75'
                ]);

				$tipo_asignatura->nombreTipoAsignatura= $request->nombreTipoAsignatura;
				$bandera=true;
			}
			
			if ($bandera)
			{
				// Almacenamos en la base de datos el registro.
				$tipo_asignatura->save();
				return response()->json([
					'status'=>true,
					'data'=>$tipo_asignatura],200)
					->header('Location', env('APP_URL').'tipo_asignaturas/'.$tipo_asignatura->idTipoAsignatura)
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
     * @param  \App\tipo_asignatura  $tipo_asignatura
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $tipo_asignatura=Cache::remember('tipo_asignaturas',15/60, function() use ($id)
		{
			// Caché válida durante 15 segundos.
			return tipo_asignatura::find($id);  
		});
		
		if(!$tipo_asignatura)
		{
			return response()->json(
				['errors'=>array(['code'=>404,
				'message'=>'No se encuentra un tip de asignatura con ese identificador.',
				'identificador'=>$id
			])],404);
		}

		$Materias=$tipo_asignatura->Materias->first();

		if ($Materias)
		{
			$tipo_asignatura->delete();
			
			return response()->json([
				'status'=>true,
				'message'=>'El tipo de asignatura contaba con relaciones. Se ha eliminado el tipo de asignatura correctamente.'
			],200);
			
		}   

		$tipo_asignatura->delete();

        return response()->json([
			'status'=>true,
			'message'=>'Se ha eliminado el tipo de asignatura correctamente.'
		],200);
    }
}
