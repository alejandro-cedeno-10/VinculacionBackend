<?php

namespace App\Http\Controllers;

use App\materia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class MateriaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $materia=Cache::remember('materias',30/60, function()
            {
                // Caché válida durante 30 segundos.
                return materia::all();
            });
       
        return response()->json([
            'status'=>true,
            'data'=>$materia], 200);
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
            'idTipoAsignatura'     => 'required|numeric|exists:tipo_asignaturas,idTipoAsignatura',           
            'nombreMateria'     => 'required|string|max:80' 
        ]);

        $materia=Cache::remember('materias',15/60, function() use ($request)
            {
                // Caché válida durante 15 segundos.
                return materia::create($request->all());
            });
	
		$materia->save();
	
        return response()->json(['data'=>$materia,
            'message' => 'Materia Creada'], 201)
            ->header('Location', env('APP_URL').'materias/'.$materia->idMateria)
            ->header('Content-Type', 'application/json');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\materia  $materia
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $materia=Cache::remember('materias',30/60, function() use ($id)
		{
			// Caché válida durante 30 segundos.
			return materia::find($id);
		});

		if(!$materia)
		{
			return response()->json(
				['errors'=>array(['code'=>404,
				'message'=>'No se encuentra una materia con ese identificador.',
				'identificador'=>$id
			])],404);
		}

		return response()->json([
			'status'=>true,
			'data'=>$materia],200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\materia  $materia
     * @return \Illuminate\Http\Response
     */
    public function edit(materia $materia)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\materia  $materia
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $materia=Cache::remember('materias',15/60, function() use ($id)
		{
			// Caché válida durante 15 segundos.
			return materia::find($id);
		});

		if(!$materia)
		{
			return response()->json(
				['errors'=>array(['code'=>404,
				'message'=>'No se encuentra una materia con ese identificador.',
				'identificador'=>$id
			])],404);
		}
		
		if($request->method() === 'PUT')
		{
            $request->validate([
                'nombreMateria'     => 'required|string|max:80'
            ]);
			
			$materia->nombreMateria = $request->nombreMateria;
		
			$materia->save();

			return response()->json([
				'status'=>true,
				'data'=>$materia],200)
				->header('Location', env('APP_URL').'materias/'.$materia->idMateria)
				->header('Content-Type', 'application/json');
		}else{
			// Creamos una bandera para controlar si se ha modificado algún dato en el método PATCH.
			$bandera = false;

			if ($request->nombreMateria!= null)
			{
				$request->validate([
                    'nombreMateria'     => 'required|string|max:80'
                ]);

				$materia->nombreMateria = $request->nombreMateria;
				$bandera=true;
			}
			
			if ($bandera)
			{
				// Almacenamos en la base de datos el registro.
				$materia->save();
				return response()->json([
					'status'=>true,
					'data'=>$materia],200)
					->header('Location', env('APP_URL').'materias/'.$materia->idMateria)
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
     * @param  \App\materia  $materia
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $materia=Cache::remember('materias',15/60, function() use ($id)
		{
			// Caché válida durante 15 segundos.
			return materia::find($id);  
		});
		
		if(!$materia)
		{
			return response()->json(
				['errors'=>array(['code'=>404,
				'message'=>'No se encuentra una materia con ese identificador.',
				'identificador'=>$id
			])],404);
		}

		$Profesores=$materia->Profesores->first();

		$Cursos=$materia->Cursos->first();

        $Paralelos=$materia->Paralelos->first();

        $Especialidades=$materia->Especialidades->first();

        $Periodo_Lectivos=$materia->Periodo_Lectivos->first();

        $Tipo_Asignatura=$materia->Tipo_Asignatura->first();

		if ($Profesores || $Cursos || $Paralelos || $Especialidades || $Periodo_Lectivos || $Tipo_Asignatura)
		{
			$materia->delete();
			
			return response()->json([
				'status'=>true,
				'message'=>'La materia contaba con relaciones. Se ha eliminado la materia correctamente.'
			],200);
			
		}   

		$materia->delete();

        return response()->json([
			'status'=>true,
			'message'=>'Se ha eliminado la materia correctamente.'
		],200);
    }
}
