<?php

namespace App\Http\Controllers;

use App\cuerpo_dece;

use Spatie\QueryBuilder\QueryBuilder;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class DecePeriodoController extends Controller
{


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $cuerpo_dece = QueryBuilder::for(cuerpo_dece::class)
            ->allowedIncludes('periodo_lectivos')
            ->get();

        return response()->json([
			'status'=>true,
            'data'=>$cuerpo_dece
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
        $cuerpo_dece=Cache::remember('cuerpo_deces',15/60, function() use ($id)
		{
			// Caché válida durante 15 segundos.
			return cuerpo_dece::find($id);  
		});

        if(!$cuerpo_dece)
        {
            return response()->json(
                ['errors'=>array(['code'=>404,
                'message'=>'No se encuentra un cuerpo_dece con ese identificador.',
                'identificador'=>$id
            ])],404);
        }

        $periodo_lectivos=$cuerpo_dece->Periodo_Lectivos;

        return response()->json([
            'status'=>true,
            'data'=>$cuerpo_dece
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
