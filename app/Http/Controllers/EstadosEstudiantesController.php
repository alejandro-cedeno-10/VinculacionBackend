<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\estado_estudiante;
use Illuminate\Support\Facades\Cache;

class EstadosEstudiantesController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $estado_estudiante=Cache::remember('estado_estudiantes',15/60, function() use ($request)
		{
			// Caché válida durante 15 segundos.
            return estado_estudiante::where([
                'idEstudiante' => $request->idEstudiante,    
                'fecha' => $request->fecha])->first();
        });
        
		if($estado_estudiante)
		{
			return response()->json(
				['errors'=>array(['status'=>false,
				'message'=>'Ya existe esta relacion de estado_estudiante',
                'identificador_1'=>$request->idEstudiante,
                'identificador_2'=>$request->fecha                
			])],200);
        }
        
		$request->validate([
            'idEstado'     => 'required|numeric|exists:estados,idEstado',
            'idEstudiante'     => 'required|string|max:10|exists:estudiantes,idEstudiante',
            'idMatricula'     => 'required|numeric|exists:matriculas,idMatricula'            
        ]);

        $estado_estudiante=Cache::remember('estado_estudiantes',15/60, function() use ($request)
            {
                // Caché válida durante 15 segundos.
                return estado_estudiante::create($request->all());
            });
	
		$estado_estudiante->save();
	
        return response()->json(['data'=>$estado_estudiante,
            'message' => 'Relacion Estado_Estudiante Creada'], 201);
    }
    
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\estado_estudiante  $estado_estudiante
     * @return \Illuminate\Http\Response
     */
    public function destroy($id,$id2)
    {
        $estado_estudiante=Cache::remember('estado_estudiantes',15/60, function() use ($id,$id2)
		{
			// Caché válida durante 15 segundos.
			return estado_estudiante::where([ 
                'idEstudiante' => $id,    
                'fecha' => $id2
                ])->first();
        });
        
		if(!$estado_estudiante)
		{
			return response()->json(
				['errors'=>array(['code'=>404,
				'message'=>'No se encuentra un estado_estudiante con ese identificador.',
                'identificador_1'=>$id,
                'identificador_2'=>$id2
			])],404);
		}

        $estado_estudiante=Cache::remember('estado_estudiantes',15/60, function() use ($id,$id2)
		{
			// Caché válida durante 15 segundos.
			estado_estudiante::where([
                'idEstudiante' => $id,    
                'fecha' => $id2])->delete();
        });
		
		return response()->json([
			'status'=>true,
			'message'=>'Se ha eliminado el estado_estudiante correctamente.'
		],200);
    }
}
