<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Curso_paralelo;
use Illuminate\Support\Facades\Cache;

class CursosParalelosController extends Controller
{
    //
    // Configuramos en el constructor del 
	// Controlador la autenticación usando el Middleware auth.basic,
    public function __construct()
	{
		/* $this->middleware('auth',['only'=>['index']]); */ 
    }


     /**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(Request $request)
	{
        //
        
        $curso_paralelo=Cache::remember('curso_paralelos',15/60, function() use ($request)
		{
			// Caché válida durante 15 segundos.
			return Curso_paralelo::where(['id_curso' => $request->id_curso, 'paralelo' => $request->paralelo])->first();
        });
        
		if($curso_paralelo)
		{
			return response()->json(
				['errors'=>array(['status'=>false,
				'message'=>'Ya existe esta relacion de curso_paralelo',
                'identificador_1'=>$request->id_curso,
                'identificador_2'=>$request->paralelo
			])],200);
        }
        
		$request->validate([
            'id_curso'     => 'required|numeric|exists:cursos,id_curso',
            'paralelo'     => 'required|string|max:1|exists:paralelos,paralelo'
        ]);

        $curso_paralelo=Cache::remember('curso_paralelos',15/60, function() use ($request)
            {
                // Caché válida durante 15 segundos.
                return Curso_paralelo::create($request->all());
            });
	
		$curso_paralelo->save();
	
        return response()->json(['data'=>$curso_paralelo,
            'message' => 'Relacion Curso-Paralelo Creada'], 201);
	}
	
	
 	/**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id,$id2)
	{
		//
		$curso_paralelo=Cache::remember('curso_paralelos',15/60, function() use ($id,$id2)
		{
			// Caché válida durante 15 segundos.
			return Curso_paralelo::where(['id_curso' => $id, 'paralelo' => $id2])->first();
        });
        
		if(!$curso_paralelo)
		{
			return response()->json(
				['errors'=>array(['code'=>404,
				'message'=>'No se encuentra un curso_paralelo con ese identificador.',
                'identificador_1'=>$id,
                'identificador_2'=>$id2
			])],404);
		}

        $curso_paralelo=Cache::remember('curso_paralelos',15/60, function() use ($id,$id2)
		{
			// Caché válida durante 15 segundos.
			Curso_paralelo::where(['id_curso' => $id, 'paralelo' => $id2])->delete();
        });
		
		return response()->json([
			'status'=>true,
			'message'=>'Se ha eliminado el curso_paralelo correctamente.'
		],200); 

	}

}
