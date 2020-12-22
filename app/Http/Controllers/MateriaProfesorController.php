<?php

namespace App\Http\Controllers;

use App\materia;
use App\materia_profesor;
use App\matricula;

use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class MateriaProfesorController extends Controller
{
    

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $materia = QueryBuilder::for(materia::class)
            ->allowedIncludes('profesors')
            ->get();

        return response()->json([
			'status'=>true,
            'data'=>$materia
        ], 200);
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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

        $profesores=$materia->Profesores;

        return response()->json([
            'status'=>true,
            'data'=>$materia
        ], 200);
    }


    public function showAllLectivos()
    {
        $cursos = QueryBuilder::for(materia_profesor::class)
        ->join('periodo_lectivos', 'periodo_lectivos.idPeriodoLectivo', 'materia_profesors.idPeriodoLectivo')
        ->allowedFilters([
            AllowedFilter::exact('materia_profesors.idProfesor', null),
            ])
        ->get();

		return response()->json([
			'status'=>true,
			'data'=>$cursos],200);
    }

    public function showAllCursos()
    {
        $cursos = QueryBuilder::for(materia_profesor::class)
        ->join('materias', 'materias.idMateria', 'materia_profesors.idMateria')    
        ->join('tipo_asignaturas', 'tipo_asignaturas.idTipoAsignatura', 'materias.idTipoAsignatura') 
        ->join('cursos', 'cursos.idCurso', 'materia_profesors.idCurso')
        ->join('paralelos', 'paralelos.idParalelo', 'materia_profesors.idParalelo')
        ->join('periodo_lectivos', 'periodo_lectivos.idPeriodoLectivo', 'materia_profesors.idPeriodoLectivo')
        ->allowedFilters([
            AllowedFilter::exact('materia_profesors.idProfesor', null),
            AllowedFilter::exact('periodo_lectivos.periodoLectivo', null)
            ])
        ->get();

		return response()->json([
			'status'=>true,
			'data'=>$cursos],200);
    }

    public function showAllCursosSmall()
    {
        $cursos = QueryBuilder::for(materia_profesor::class)
        ->join('materias', 'materias.idMateria', 'materia_profesors.idMateria')    
        ->join('tipo_asignaturas', 'tipo_asignaturas.idTipoAsignatura', 'materias.idTipoAsignatura') 
        ->join('cursos', 'cursos.idCurso', 'materia_profesors.idCurso')
        ->join('paralelos', 'paralelos.idParalelo', 'materia_profesors.idParalelo')
        ->join('periodo_lectivos', 'periodo_lectivos.idPeriodoLectivo', 'materia_profesors.idPeriodoLectivo')
        ->select('cursos.idCurso','paralelos.idParalelo','cursos.curso', 'paralelos.paralelo','materias.nombreMateria')
        ->allowedFilters([
            AllowedFilter::exact('materia_profesors.idProfesor', null)
            ])
        ->get();

		return response()->json([
			'status'=>true,
			'data'=>$cursos],200);
    }

    public function showAllCursoParalelo()
    {
        $curso = QueryBuilder::for(materia_profesor::class)
        ->join('cursos', 'cursos.idCurso', 'materia_profesors.idCurso')
        ->join('paralelos', 'paralelos.idParalelo', 'materia_profesors.idParalelo')
        ->join('periodo_lectivos', 'periodo_lectivos.idPeriodoLectivo', 'materia_profesors.idPeriodoLectivo')
        ->select('cursos.idCurso','paralelos.idParalelo')
        ->allowedFilters([
            AllowedFilter::exact('cursos.curso', null),
            AllowedFilter::exact('paralelos.paralelo', null)
            ])
        ->select('cursos.idCurso','paralelos.idParalelo')
        ->GroupBy('cursos.idCurso','paralelos.idParalelo')
        ->get();

		return response()->json([
			'status'=>true,
			'data'=>$curso],200);
    }

    public function showAllCursoParaleloAll()
    {
        $cursos = QueryBuilder::for(matricula::class)
        ->join('cursos', 'cursos.idCurso', 'matriculas.idCurso')
        ->join('paralelos', 'paralelos.idParalelo', 'matriculas.idParalelo')
        ->select('cursos.idCurso','paralelos.idParalelo','cursos.curso', 'paralelos.paralelo')
        ->get();

		return response()->json([
			'status'=>true,
			'data'=>$cursos],200);
    }


    

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
