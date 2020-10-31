<?php

namespace App\Http\Controllers;

use App\materia;

use Spatie\QueryBuilder\QueryBuilder;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class MateriaParaleloController extends Controller
{
   


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $materia = QueryBuilder::for(materia::class)
            ->allowedIncludes('paralelos')
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

        $paralelos=$materia->Paralelos;

        return response()->json([
            'status'=>true,
            'data'=>$materia
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
