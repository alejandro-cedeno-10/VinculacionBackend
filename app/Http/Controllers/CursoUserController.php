<?php

namespace App\Http\Controllers;

use App\Curso;

use Spatie\QueryBuilder\QueryBuilder;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class CursoUserController extends Controller
{
    // Configuramos en el constructor del 
	// Controlador la autenticación usando el Middleware auth.basic,
    public function __construct()
	{
		/* $this->middleware('auth',['only'=>['index']]); */ 
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $curso= QueryBuilder::for(Curso::class)
            ->allowedIncludes('Estudiantes')
            ->get();

        return response()->json([
			'status'=>true,
            'data'=>$curso
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
        $curso=Cache::remember('cursos',30/60, function() use ($id)
		{
			// Caché válida durante 30 segundos.
			return Curso::find($id);
		});

		if(!$curso)
		{
			return response()->json(
				['errors'=>array(['code'=>404,
				'message'=>'No se encuentra un curso con ese identificador.',
				'identificador'=>$id
			])],404);
		}

        $Estudiantes=$curso->Estudiantes;

        return response()->json([
            'status'=>true,
            'data'=>$curso
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
