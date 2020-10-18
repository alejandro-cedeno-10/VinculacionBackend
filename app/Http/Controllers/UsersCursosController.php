<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Est_curso;
use Illuminate\Support\Facades\Cache;

class UsersCursosController extends Controller
{
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
        $est_curso=Cache::remember('est_cursos',15/60, function() use ($request)
		{
			// Caché válida durante 15 segundos.
			return Est_curso::where(['cedula' => $request->cedula, 'id_curso' => $request->id_curso])->first();
        });
        
		if($est_curso)
		{
			return response()->json(
				['errors'=>array(['status'=>false,
				'message'=>'Ya existe esta relacion de est_curso',
                'identificador_1'=>$request->cedula,
                'identificador_2'=>$request->id_curso
			])],200);
        }
        
		$request->validate([
            'cedula'     => 'required|string|min:10|max:10|exists:users,cedula',
            'id_curso'     => 'required|numeric|min:1|exists:cursos,id_curso'
        ]);

        $est_curso=Cache::remember('est_cursos',15/60, function() use ($request)
            {
                // Caché válida durante 15 segundos.
                return Est_curso::create($request->all());
            });
	
		$est_curso->save();
	
        return response()->json(['data'=>$est_curso,
            'message' => 'Relacion Est_curso Creada'], 201);
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
        $est_curso=Cache::remember('est_cursos',15/60, function() use ($id,$id2)
		{
			// Caché válida durante 15 segundos.
			return Est_curso::where(['cedula' => $id, 'id_curso' => $id2])->first();
        });
        
		if(!$est_curso)
		{
			return response()->json(
				['errors'=>array(['code'=>404,
				'message'=>'No se encuentra un est_curso con ese identificador.',
                'identificador_1'=>$id,
                'identificador_2'=>$id2
			])],404);
		}
		

        $est_curso=Cache::remember('est_cursos',15/60, function() use ($id,$id2)
		{
			// Caché válida durante 15 segundos.
			Est_curso::where(['cedula' => $id, 'id_curso' => $id2])->delete();
        });
		
		return response()->json([
			'status'=>true,
			'message'=>'Se ha eliminado el est_curso correctamente.'
		],200); 

	}
}
