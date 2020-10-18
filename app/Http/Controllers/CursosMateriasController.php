<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Curso_materia;
use Illuminate\Support\Facades\Cache;

class CursosMateriasController extends Controller
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
        
        $curso_materia=Cache::remember('curso_materias',15/60, function() use ($request)
		{
			// Caché válida durante 15 segundos.
			return Curso_materia::where(['id_curso' => $request->id_curso, 'id_materia' => $request->id_materia])->first();
        });
        
		if($curso_materia)
		{
			return response()->json(
				['errors'=>array(['status'=>false,
				'message'=>'Ya existe esta relacion de curso_materia',
                'identificador_1'=>$request->id_curso,
                'identificador_2'=>$request->id_materia
			])],200);
        }
        
		$request->validate([
            'id_curso'     => 'required|numeric|exists:cursos,id_curso',
            'id_materia'     => 'required|numeric|exists:materias,id_materia'
        ]);

        $curso_materia=Cache::remember('curso_materias',15/60, function() use ($request)
            {
                // Caché válida durante 15 segundos.
                return Curso_materia::create($request->all());
            });
	
		$curso_materia->save();
	
        return response()->json(['data'=>$curso_materia,
            'message' => 'Relacion Curso-Materia Creada'], 201);
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
        $curso_materia=Cache::remember('curso_materias',15/60, function() use ($id,$id2)
		{
			// Caché válida durante 15 segundos.
			return Curso_materia::where(['id_curso' => $id, 'id_materia' => $id2])->first();
        });
        
		if(!$curso_materia)
		{
			return response()->json(
				['errors'=>array(['code'=>404,
				'message'=>'No se encuentra un curso_materia con ese identificador.',
                'identificador_1'=>$id,
                'identificador_2'=>$id2
			])],404);
		}

        $curso_materia=Cache::remember('curso_materias',15/60, function() use ($id,$id2)
		{
			// Caché válida durante 15 segundos.
			Curso_materia::where(['id_curso' => $id, 'id_materia' => $id2])->delete();
        });
		
		return response()->json([
			'status'=>true,
			'message'=>'Se ha eliminado el curso_materia correctamente.'
		],200); 

	}
}
