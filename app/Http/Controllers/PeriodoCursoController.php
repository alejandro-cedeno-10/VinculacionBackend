<?php

namespace App\Http\Controllers;

use App\periodo_lectivo;

use Spatie\QueryBuilder\QueryBuilder;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class PeriodoCursoController extends Controller
{
   

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $periodo_lectivo = QueryBuilder::for(periodo_lectivo::class)
            ->allowedIncludes('cursos')
            ->get();

        return response()->json([
			'status'=>true,
            'data'=>$periodo_lectivo
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
        $periodo_lectivo=Cache::remember('periodo_lectivos',15/60, function() use ($id)
		{
			// Caché válida durante 15 segundos.
			return periodo_lectivo::find($id);  
		});

        if(!$periodo_lectivo)
        {
            return response()->json(
                ['errors'=>array(['code'=>404,
                'message'=>'No se encuentra un periodo_lectivo con ese identificador.',
                'identificador'=>$id
            ])],404);
        }

        $cursos=$periodo_lectivo->Cursos;

        return response()->json([
            'status'=>true,
            'data'=>$periodo_lectivo
        ], 200);
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
