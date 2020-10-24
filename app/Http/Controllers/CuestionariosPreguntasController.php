<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;

use App\cuestionario_pregunta;
use Illuminate\Support\Facades\Cache;
class CuestionariosPreguntasController extends Controller
{   
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $cuestionario_pregunta=Cache::remember('cuestionario_preguntas',15/60, function() use ($request)
		{
			// Caché válida durante 15 segundos.
            return cuestionario_pregunta::where([
                'idCuestionario' => $request->idCuestionario,
                'idPregunta' => $request->idPregunta])->first();
        });
        
		if($cuestionario_pregunta)
		{
			return response()->json(
				['errors'=>array(['status'=>false,
				'message'=>'Ya existe esta relacion de cuestionario_pregunta',
                'identificador_1'=>$request->idCuestionario,
                'identificador_2'=>$request->idPregunta               
			])],200);
        }
        
		$request->validate([
            'idCuestionario'     => 'required|numeric|exists:cuestionarios,idCuestionario',
            'idPregunta'     => 'required|numeric|exists:preguntas,idPregunta'           
        ]);

        $cuestionario_pregunta=Cache::remember('cuestionario_preguntas',15/60, function() use ($request)
            {
                // Caché válida durante 15 segundos.
                return cuestionario_pregunta::create($request->all());
            });
	
		$cuestionario_pregunta->save();
	
        return response()->json(['data'=>$cuestionario_pregunta,
            'message' => 'Relacion Cuestionario_Pregunta Creada'], 201);
    }
 
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\cuestionario_pregunta  $cuestionario_pregunta
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, $id2)
    {
        $cuestionario_pregunta=Cache::remember('cuestionario_preguntas',15/60, function() use ($id,$id2)
		{
			// Caché válida durante 15 segundos.
			return cuestionario_pregunta::where([
                'idCuestionario' => $id, 
                'idPregunta' => $id2])->first();
        });
        
		if(!$cuestionario_pregunta)
		{
			return response()->json(
				['errors'=>array(['code'=>404,
				'message'=>'No se encuentra un cuestionario_pregunta con ese identificador.',
                'identificador_1'=>$id,
                'identificador_2'=>$id2
			])],404);
		}

        $cuestionario_pregunta=Cache::remember('cuestionario_preguntas',15/60, function() use ($id,$id2)
		{
			// Caché válida durante 15 segundos.
			cuestionario_pregunta::where([
                'idCuestionario' => $id, 
                'idPregunta' => $id2])->delete();
        });
		
		return response()->json([
			'status'=>true,
			'message'=>'Se ha eliminado el cuestionario_pregunta correctamente.'
		],200);
    }
}
