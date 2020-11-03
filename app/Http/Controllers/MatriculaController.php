<?php

namespace App\Http\Controllers;

use App\matricula;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class MatriculaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $matricula=Cache::remember('matriculas',30/60, function()
            {
                // Caché válida durante 30 segundos.
                return matricula::all();
            });
           
        return response()->json([
			'status'=>true,
			'data'=>$matricula], 200);
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
            'idRepresentante'     => 'required|string|max:10|exists:representantes,idRepresentante',
            'idCurso'     => 'required|numeric|exists:cursos,idCurso',
            'idParalelo'     => 'required|numeric|exists:paralelos,idParalelo',
            'idEspecialidad'     => 'required|numeric|exists:especialidads,idEspecialidad',
            'idEstudiante'     => 'required|string|max:10|exists:estudiantes,idEstudiante',
            'idPeriodoLectivo'     => 'required|numeric|exists:periodo_lectivos,idPeriodoLectivo',
            'folder'     => 'required|string|max:80'                     
        ]);

        $matricula=Cache::remember('matriculas',15/60, function() use ($request)
            {
                // Caché válida durante 15 segundos.
                return matricula::create($request->all());
            });
	
		$matricula->save();
	
        return response()->json(['data'=>$matricula,
            'message' => 'Matricula Creada'], 201)
            ->header('Location', env('APP_URL').'matriculas/'.$matricula->idMatricula)
            ->header('Content-Type', 'application/json');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\matricula  $matricula
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $matricula=Cache::remember('matriculas',30/60, function() use ($id)
		{
			// Caché válida durante 30 segundos.
			return matricula::find($id);
		});

		if(!$matricula)
		{
			return response()->json(
				['errors'=>array(['code'=>404,
				'message'=>'No se encuentra una matricula con ese identificador.',
				'identificador'=>$id
			])],404);
		}

		return response()->json([
			'status'=>true,
			'data'=>$matricula],200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\matricula  $matricula
     * @return \Illuminate\Http\Response
     */
    public function edit(matricula $matricula)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\matricula  $matricula
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $matricula=Cache::remember('matriculas',15/60, function() use ($id)
		{
			// Caché válida durante 15 segundos.
			return matricula::find($id);
		});

		if(!$matricula)
		{
			return response()->json(
				['errors'=>array(['code'=>404,
				'message'=>'No se encuentra una matricula con ese identificador.',
				'identificador'=>$id
			])],404);
		}
		
		if($request->method() === 'PUT')
		{
            $request->validate([
                'folder'     => 'required|string|max:80' 
            ]);
			
            $matricula->folder = $request->folder;
            		
			$matricula->save();

			return response()->json([
				'status'=>true,
				'data'=>$matricula],200)
				->header('Location', env('APP_URL').'matriculas/'.$matricula->idMatricula)
				->header('Content-Type', 'application/json');
		}else{
			// Creamos una bandera para controlar si se ha modificado algún dato en el método PATCH.
			$bandera = false;
            
            if ($request->folder!= null)
			{
				$request->validate([
                    'folder'     => 'required|string|max:80' 
                ]);

				$matricula->folder = $request->folder;
				$bandera=true;
			}
			
			if ($bandera)
			{
				// Almacenamos en la base de datos el registro.
				$matricula->save();
				return response()->json([
					'status'=>true,
					'data'=>$matricula],200)
					->header('Location', env('APP_URL').'matriculas/'.$matricula->idMatricula)
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
     * @param  \App\matricula  $matricula
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $matricula=Cache::remember('matriculas',15/60, function() use ($id)
		{
			// Caché válida durante 15 segundos.
			return matricula::find($id);  
		});
		
		if(!$matricula)
		{
			return response()->json(
				['errors'=>array(['code'=>404,
				'message'=>'No se encuentra una matricula con ese identificador.',
				'identificador'=>$id
			])],404);
		}

        $Estados=$matricula->Estados->first();
        
        $EstudiantesPivote=$matricula->EstudiantesPivote->first();
        
        $Estudiante=$matricula->Estudiante->first();
                
        $Representante=$matricula->Representante->first();

		$Curso=$matricula->Curso->first();

        $Paralelo=$matricula->Paralelo->first();

        $Especialidad=$matricula->Especialidad->first();

        $PeriodoLectivo=$matricula->PeriodoLectivo->first();
             
		if ($Estados || $EstudiantesPivote || $Estudiante || $Representante || $Curso || $Paralelo || $Especialidad || $PeriodoLectivo)
		{
			$matricula->delete();
			
			return response()->json([
				'status'=>true,
				'message'=>'La matricula contaba con relaciones. Se ha eliminado la matricula correctamente.'
			],200);
			
		}   

		$matricula->delete();

        return response()->json([
			'status'=>true,
			'message'=>'Se ha eliminado la matricula correctamente.'
		],200);
    }
}
