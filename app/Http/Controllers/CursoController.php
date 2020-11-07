<?php

namespace App\Http\Controllers;

use App\curso;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class CursoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $curso=Cache::remember('cursos',30/60, function()
            {
                // Caché válida durante 30 segundos.
                return curso::all();
            });
           
        return response()->json([
			'status'=>true,
			'data'=>$curso], 200);
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
            'curso'     => 'required|string|max:30|unique:cursos,curso'            
        ]);

        $curso=Cache::remember('cursos',15/60, function() use ($request)
            {
                // Caché válida durante 15 segundos.
                return curso::create($request->all());
            });
	
		$curso->save();
	
        return response()->json(['data'=>$curso,
            'message' => 'Curso Creado'], 201)
            ->header('Location', env('APP_URL').'cursos/'.$curso->idCurso)
            ->header('Content-Type', 'application/json');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\curso  $curso
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $curso=Cache::remember('cursos',30/60, function() use ($id)
		{
			// Caché válida durante 30 segundos.
			return curso::find($id);
		});

		if(!$curso)
		{
			return response()->json(
				['errors'=>array(['code'=>404,
				'message'=>'No se encuentra un curso con ese identificador.',
				'identificador'=>$id
			])],404);
		}

		return response()->json([
			'status'=>true,
			'data'=>$curso],200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\curso  $curso
     * @return \Illuminate\Http\Response
     */
    public function edit(curso $curso)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\curso  $curso
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $curso=Cache::remember('cursos',15/60, function() use ($id)
		{
			// Caché válida durante 15 segundos.
			return curso::find($id);
		});

		if(!$curso)
		{
			return response()->json(
				['errors'=>array(['code'=>404,
				'message'=>'No se encuentra un curso con ese identificador.',
				'identificador'=>$id
			])],404);
		}
		
		if($request->method() === 'PUT')
		{
            $request->validate([
                'curso'     => 'required|string|max:30'
            ]);
			
			$curso->curso = $request->curso;
		
			$curso->save();

			return response()->json([
				'status'=>true,
				'data'=>$curso],200)
				->header('Location', env('APP_URL').'cursos/'.$curso->idCurso)
				->header('Content-Type', 'application/json');
		}else{
			// Creamos una bandera para controlar si se ha modificado algún dato en el método PATCH.
			$bandera = false;

			if ($request->curso!= null)
			{
				$request->validate([
                    'curso'     => 'required|string|max:30'
                ]);

				$curso->curso = $request->curso ;
				$bandera=true;
			}
			
			if ($bandera)
			{
				// Almacenamos en la base de datos el registro.
				$curso->save();
				return response()->json([
					'status'=>true,
					'data'=>$curso],200)
					->header('Location', env('APP_URL').'cursos/'.$curso->idCurso)
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
     * @param  \App\curso  $curso
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $curso=Cache::remember('cursos',15/60, function() use ($id)
		{
			// Caché válida durante 15 segundos.
			return curso::find($id);  
		});
		
		if(!$curso)
		{
			return response()->json(
				['errors'=>array(['code'=>404,
				'message'=>'No se encuentra un curso con ese identificador.',
				'identificador'=>$id
			])],404);
		}

		$Profesores=$curso->Profesores->first();

		$Materias=$curso->Materias->first();

        $Paralelos=$curso->Paralelos->first();

        $Especialidades=$curso->Especialidades->first();

        $PeriodoLectivo=$curso->PeriodoLectivo->first();

        $Matriculas=$curso->Matriculas->first();

        $Cuestionarios=$curso->Cuestionarios->first();

		if ($Profesores || $Materias || $Paralelos || $Especialidades || $PeriodoLectivo || $Matriculas || $Cuestionarios)
		{
			$curso->delete();
			
			return response()->json([
				'status'=>true,
				'message'=>'El curso contaba con relaciones. Se ha eliminado el curso correctamente.'
			],200);
			
		}   

		$curso->delete();

        return response()->json([
			'status'=>true,
			'message'=>'Se ha eliminado el curso correctamente.'
		],200);
    }
}
