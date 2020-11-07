<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;

use App\dece_lectivo;
use Illuminate\Support\Facades\Cache;

class DecesLectivosController extends Controller
{

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $dece_lectivo=Cache::remember('dece_lectivos',15/60, function() use ($request)
		{
			// Caché válida durante 15 segundos.
            return dece_lectivo::where([
                'idPersona' => $request->idPersona,
                'idPeriodoLectivo' => $request->idPeriodoLectivo])->first();
        });
        
		if($dece_lectivo)
		{
			return response()->json(
				['errors'=>array(['status'=>false,
				'message'=>'Ya existe esta relacion de dece_lectivo',
                'identificador_1'=>$request->idPersona,
                'identificador_2'=>$request->idPeriodoLectivo              
			])],200);
        }
        
		$request->validate([
            'idPersona'     => 'required|string|max:10|exists:cuerpo_deces,idPersona',
            'idPeriodoLectivo'     => 'required|numeric|exists:periodo_lectivos,idPeriodoLectivo'           
        ]);

        $dece_lectivo=Cache::remember('dece_lectivos',15/60, function() use ($request)
            {
                // Caché válida durante 15 segundos.
                return dece_lectivo::create($request->all());
            });
	
		
	
        return response()->json(['data'=>$dece_lectivo,
            'message' => 'Relacion DECE_Lectivo Creada'], 201);
    }
  
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\dece_lectivo  $dece_lectivo
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, $id2)
    {
        $dece_lectivo=Cache::remember('dece_lectivos',15/60, function() use ($id,$id2)
		{
			// Caché válida durante 15 segundos.
			return dece_lectivo::where([
                'idPersona' => $id, 
                'idPeriodoLectivo' => $id2])->first();
        });
        
		if(!$dece_lectivo)
		{
			return response()->json(
				['errors'=>array(['code'=>404,
				'message'=>'No se encuentra un dece_lectivo con ese identificador.',
                'identificador_1'=>$id,
                'identificador_2'=>$id2
			])],404);
		}

        $dece_lectivo=Cache::remember('dece_lectivos',15/60, function() use ($id,$id2)
		{
			// Caché válida durante 15 segundos.
			dece_lectivo::where([
                'idPersona' => $id, 
                'idPeriodoLectivo' => $id2])->delete();
        });
		
		return response()->json([
			'status'=>true,
			'message'=>'Se ha eliminado el dece_lectivo correctamente.'
		],200);
    }
}
