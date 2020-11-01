<?php

namespace App\Http\Controllers;

use App\tipo_asignatura;
use Illuminate\Http\Request;

use Spatie\QueryBuilder\QueryBuilder;
use Illuminate\Support\Facades\Cache;

class TipoAsignaturaMateriaController extends Controller
{
     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tipo_asignatura = QueryBuilder::for(tipo_asignatura::class)
            ->allowedIncludes('Materias')
            ->get();

        return response()->json([
			'status'=>true,
            'data'=>$tipo_asignatura
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
        $tipo_asignatura=Cache::remember('tipo_asignaturas',15/60, function() use ($id)
		{
			// Caché válida durante 15 segundos.
			return tipo_asignatura::find($id);  
		});

        if(!$tipo_asignatura)
        {
            return response()->json(
                ['errors'=>array(['code'=>404,
                'message'=>'No se encuentra una tipo_asignatura con ese identificador.',
                'identificador'=>$id
            ])],404);
        }

        $Materias=$tipo_asignatura->Materias;

        return response()->json([
            'status'=>true,
            'data'=>$tipo_asignatura
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
