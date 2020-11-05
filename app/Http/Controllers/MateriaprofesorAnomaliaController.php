<?php

namespace App\Http\Controllers;

use App\materia_profesor;

use Spatie\QueryBuilder\QueryBuilder;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Spatie\QueryBuilder\AllowedFilter;

class MateriaprofesorAnomaliaController extends Controller
{
    

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $materia_profesor = QueryBuilder::for(materia_profesor::class)
            ->allowedIncludes('anomalias')
            ->get();

        return response()->json([
			'status'=>true,
            'data'=>$materia_profesor
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
        $materia_profesor=Cache::remember('materia_profesors',15/60, function() use ($id)
		{
			// Caché válida durante 15 segundos.
			return materia_profesor::find($id);  
		});

        if(!$materia_profesor)
        {
            return response()->json(
                ['errors'=>array(['code'=>404,
                'message'=>'No se encuentra una materia_profesor con ese identificador.',
                'identificador'=>$id
            ])],404);
        }

        $anomalias=$materia_profesor->Anomalias;

        return response()->json([
            'status'=>true,
            'data'=>$materia_profesor
        ], 200);
    }


    public function showAllAnomalias()
    {
        $anomalias = QueryBuilder::for(materia_profesor::class)
        ->allowedIncludes(['Anomalias'])
        ->join('anomalias', 'anomalias.idMateriaProfesor', 'materia_profesors.idMateriaProfesor')
        ->join('subcategorias', 'subcategorias.idSubcategoria', 'anomalias.idSubcategoria')    
        ->join('categorias', 'categorias.idCategoria', 'subcategorias.idCategoria') 
        ->join('cursos', 'cursos.idCurso', 'materia_profesors.idCurso')
        ->join('paralelos', 'paralelos.idParalelo', 'materia_profesors.idParalelo')
        ->join('periodo_lectivos', 'periodo_lectivos.idPeriodoLectivo', 'materia_profesors.idPeriodoLectivo')
        ->join('reporte_estudiantes','reporte_estudiantes.idAnomalia','anomalias.idAnomalia')
        ->join('users','users.idPersona','reporte_estudiantes.idEstudiante')
        ->allowedFilters([
            AllowedFilter::exact('cursos.curso', null),
            AllowedFilter::exact('paralelos.paralelo', null),
            AllowedFilter::exact('periodo_lectivos.periodoLectivo', null)
            ])
        ->get();

		return response()->json([
			'status'=>true,
			'data'=>$anomalias],200);
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
