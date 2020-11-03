<?php

namespace App\Http\Controllers;

use App\estudiante;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;

class EstudianteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $estudiante=Cache::remember('estudiantes',30/60, function()
            {
                // Caché válida durante 30 segundos.
                return estudiante::all();
            });
       
        return response()->json([
            'status'=>true,
            'data'=>$estudiante], 200);
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
            'idEstudiante'     => 'required|string|max:10|unique:estudiantes,idEstudiante|exists:users,idPersona',  
            'idRepresentante'     => 'required|string|max:10|exists:users,idPersona',           
            'procedencia'     => 'required|string|max:80' 
        ]);

        $estudiante=Cache::remember('estudiantes',15/60, function() use ($request)
            {
                // Caché válida durante 15 segundos.
                return estudiante::create($request->all());
            });
	
		$estudiante->save();
    
       /*  // Le asignamos el rol
        $estudiante->assignRole('Estudiante');  */

        return response()->json(['data'=>$estudiante,
            'message' => 'Estudiante Creado'], 201)
            ->header('Location', env('APP_URL').'estudiantes/'.$estudiante->idEstudiante)
            ->header('Content-Type', 'application/json');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\estudiante  $estudiante
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $estudiante=Cache::remember('estudiantes',30/60, function() use ($id)
		{
			// Caché válida durante 30 segundos.
			return estudiante::find($id);
		});

		if(!$estudiante)
		{
			return response()->json(
				['errors'=>array(['code'=>404,
				'message'=>'No se encuentra un estudiante con ese identificador.',
				'identificador'=>$id
			])],404);
		}

		return response()->json([
			'status'=>true,
			'data'=>$estudiante],200);
    }


    public function showOrden(Request $request)
    {
        
        $estudiantes = QueryBuilder::for(estudiante::class)
        ->allowedIncludes(['user'])
        ->join('matriculas', 'matriculas.idEstudiante', 'estudiantes.idEstudiante')   
        ->join('cursos', 'cursos.idCurso', 'matriculas.idCurso')
        ->join('paralelos', 'paralelos.idParalelo', 'matriculas.idParalelo')
        ->join('periodo_lectivos', 'periodo_lectivos.idPeriodoLectivo', 'matriculas.idPeriodoLectivo')
        ->allowedFilters([
            AllowedFilter::exact('cursos.curso', null),
            AllowedFilter::exact('paralelos.paralelo', null),
            AllowedFilter::exact('periodo_lectivos.periodoLectivo', null)
            ])
        ->get();

		return response()->json([
			'status'=>true,
			'data'=>$estudiantes],200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\estudiante  $estudiante
     * @return \Illuminate\Http\Response
     */
    public function edit(estudiante $estudiante)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\estudiante  $estudiante
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $estudiante=Cache::remember('estudiantes',15/60, function() use ($id)
		{
			// Caché válida durante 15 segundos.
			return estudiante::find($id);
		});

		if(!$estudiante)
		{
			return response()->json(
				['errors'=>array(['code'=>404,
				'message'=>'No se encuentra un estudiante con ese identificador.',
				'identificador'=>$id
			])],404);
		}
		
		if($request->method() === 'PUT')
		{
            $request->validate([
                'procedencia'     => 'required|string|max:80'
            ]);
			
			$estudiante->procedencia = $request->procedencia;
		
			$estudiante->save();

			return response()->json([
				'status'=>true,
				'data'=>$estudiante],200)
				->header('Location', env('APP_URL').'estudiantes/'.$estudiante->idEstudiante)
				->header('Content-Type', 'application/json');
		}else{
			// Creamos una bandera para controlar si se ha modificado algún dato en el método PATCH.
			$bandera = false;

			if ($request->procedencia!= null)
			{
				$request->validate([
                    'procedencia'     => 'required|string|max:80'
                ]);

				$estudiante->procedencia = $request->procedencia;
				$bandera=true;
			}
			
			if ($bandera)
			{
				// Almacenamos en la base de datos el registro.
				$estudiante->save();
				return response()->json([
					'status'=>true,
					'data'=>$estudiante],200)
					->header('Location', env('APP_URL').'estudiantes/'.$estudiante->idEstudiante)
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
     * @param  \App\estudiante  $estudiante
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $estudiante=Cache::remember('estudiantes',15/60, function() use ($id)
		{
			// Caché válida durante 15 segundos.
			return estudiante::find($id);  
		});
		
		if(!$estudiante)
		{
			return response()->json(
				['errors'=>array(['code'=>404,
				'message'=>'No se encuentra un estudiante con ese identificador.',
				'identificador'=>$id
			])],404);
		}

		$User_Estudiante=$estudiante->User_Estudiante->first();
		
        $User_Representante=$estudiante->User_Representante->first();
        
        $Estados=$estudiante->Estados->first();

        $Matriculas_Pivote=$estudiante->Matriculas_Pivote->first();

        $Matriculas=$estudiante->Matriculas->first();

        $Anomalias=$estudiante->Anomalias->first();

		if ($User_Estudiante || $User_Representante || $Estados || $Matriculas_Pivote || $Matriculas || $Anomalias)
		{
			$estudiante->delete();
			
			return response()->json([
				'status'=>true,
				'message'=>'El estudiante contaba con relaciones. Se ha eliminado el estudiante correctamente.'
			],200);
			
		}   

		$estudiante->delete();

        return response()->json([
			'status'=>true,
			'message'=>'Se ha eliminado el estudiante correctamente.'
		],200);
    }
}
