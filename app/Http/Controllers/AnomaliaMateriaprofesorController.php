<?php

namespace App\Http\Controllers;

use App\anomalia;

use Spatie\QueryBuilder\QueryBuilder;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class AnomaliaMateriaprofesorController extends Controller
{
    


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $anomalia = QueryBuilder::for(anomalia::class)
            ->allowedIncludes('MateriaProfesor')
            ->get();

        return response()->json([
			'status'=>true,
            'data'=>$anomalia
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

        $MateriaProfesor=$anomalia->MateriaProfesor;

        return response()->json([
            'status'=>true,
            'data'=>$anomalia
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
