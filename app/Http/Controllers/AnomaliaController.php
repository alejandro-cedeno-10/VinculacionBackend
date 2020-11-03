<?php

namespace App\Http\Controllers;

use App\anomalia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class AnomaliaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $anomalia=Cache::remember('anomalias',30/60, function()
            {
                // Caché válida durante 30 segundos.
                return anomalia::all();
            });
       
        return response()->json([
            'status'=>true,
            'data'=>$anomalia], 200);
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
            'idMateriaProfesor'     => 'required|numeric|exists:materia_profesors,idMateriaProfesor',
            'idSubcategoria'     => 'required|numeric|exists:subcategorias,idSubcategoria',   
            'afectado'     => 'required|string|max:30',
            'descripcion'     => 'required|string|max:150',
            'valoracion'         =>   'in:Regular,Urgente,Muy urgente',               
        ]);

        $anomalia=Cache::remember('anomalias',15/60, function() use ($request)
            {
                // Caché válida durante 15 segundos.
                return anomalia::create($request->all());
            });
	
		$anomalia->save();
	
        return response()->json(['data'=>$anomalia,
            'message' => 'Anomalia Creada'], 201)
            ->header('Location', env('APP_URL').'anomalias/'.$anomalia->idAnomalia)
            ->header('Content-Type', 'application/json');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\anomalia  $anomalia
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $anomalia=Cache::remember('anomalias',30/60, function() use ($id)
		{
			// Caché válida durante 30 segundos.
			return anomalia::find($id);
		});

		if(!$anomalia)
		{
			return response()->json(
				['errors'=>array(['code'=>404,
				'message'=>'No se encuentra una anomalia con ese identificador.',
				'identificador'=>$id
			])],404);
		}

		return response()->json([
			'status'=>true,
			'data'=>$anomalia],200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\anomalia  $anomalia
     * @return \Illuminate\Http\Response
     */
    public function edit(anomalia $anomalia)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\anomalia  $anomalia
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $anomalia=Cache::remember('anomalias',15/60, function() use ($id)
		{
			// Caché válida durante 15 segundos.
			return anomalia::find($id);
		});

		if(!$anomalia)
		{
			return response()->json(
				['errors'=>array(['code'=>404,
				'message'=>'No se encuentra una anomalia con ese identificador.',
				'identificador'=>$id
			])],404);
		}
		
		if($request->method() === 'PUT')
		{
            $request->validate([
                'afectado'     => 'required|string|max:30',
                'descripcion'     => 'required|string|max:150',
                'valoracion'     => 'required|string|max:11' 
            ]);
			
			$anomalia->afectado = $request->afectado;
            $anomalia->descripcion = $request->descripcion;
            $anomalia->valoracion = $request->valoracion;
            			
            $anomalia->save();

			return response()->json([
				'status'=>true,
				'data'=>$anomalia],200)
				->header('Location', env('APP_URL').'anomalias/'.$anomalia->idAnomalia)
				->header('Content-Type', 'application/json');


		}else{
			// Creamos una bandera para controlar si se ha modificado algún dato en el método PATCH.
			$bandera = false;
			
            if ($request->afectado!= null)
			{
				$request->validate([
					'afectado'     => 'required|string|max:30'
				]);
		
				$anomalia->afectado = $request->afectado;
				$bandera=true;
            }
            
            if ($request->descripcion!= null)
			{
				$request->validate([
					'descripcion'     => 'required|string|max:150'
				]);
		
				$anomalia->descripcion = $request->descripcion;
				$bandera=true;
			}

			if ($request->validacion!= null)
			{
				$request->validate([
                    'validación'     => 'required|string|max:11'
				]);
	
				$anomalia->validacion = $request->validacion;
				$bandera=true;
			}

			if ($bandera)
			{
				// Almacenamos en la base de datos el registro.
				$anomalia->save();
				return response()->json([
					'status'=>true,
					'data'=>$anomalia],200)
					->header('Location', env('APP_URL').'anomalias/'.$anomalia->idAnomalia)
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
     * @param  \App\anomalia  $anomalia
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $anomalia=Cache::remember('anomalias',15/60, function() use ($id)
		{
			// Caché válida durante 15 segundos.
			return anomalia::find($id);  
		});
		
		if(!$anomalia)
		{
			return response()->json(
				['errors'=>array(['code'=>404,
				'message'=>'No se encuentra una anomalia con ese identificador.',
				'identificador'=>$id
			])],404);
		}

		$MateriaProfesor=$anomalia->MateriaProfesor->first();

		$Profesores=$anomalia->Profesores->first();

        $subcategorias=$anomalia->subcategorias->first();
        
        $Estudiantes=$anomalia->Estudiantes->first();
        
		if ($MateriaProfesor || $Profesores || $subcategorias || $Estudiantes)
		{
			$anomalia->delete();
			
			return response()->json([
				'status'=>true,
				'message'=>'La anomalia contaba con relaciones. Se ha eliminado la anomalia correctamente.'
			],200);
			
		}   

		$anomalia->delete();

        return response()->json([
			'status'=>true,
			'message'=>'Se ha eliminado la anomalia correctamente.'
		],200);
    }
}
