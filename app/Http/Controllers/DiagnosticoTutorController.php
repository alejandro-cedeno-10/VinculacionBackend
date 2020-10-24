<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;

use App\diagnostico_tutor;
use Illuminate\Support\Facades\Cache;

class DiagnosticoTutorController extends Controller
{  
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $diagnostico_tutor=Cache::remember('diagnostico_tutors',15/60, function() use ($request)
		{
			// Caché válida durante 15 segundos.
            return diagnostico_tutor::where([
                'idAnomalia' => $request->idAnomalia,
                'idProfesor' => $request->idProfesor])->first();
        });
        
		if($diagnostico_tutor)
		{
			return response()->json(
				['errors'=>array(['status'=>false,
				'message'=>'Ya existe esta relacion de diagnostico_tutor',
                'identificador_1'=>$request->idAnomalia,
                'identificador_2'=>$request->idProfesor               
			])],200);
        }
        
		$request->validate([
            'idAnomalia'     => 'required|numeric|exists:anomalias,idAnomalia',
            'idProfesor'     => 'required|string|max:10|exists:profesors,idProfesor'           
        ]);

        $diagnostico_tutor=Cache::remember('diagnostico_tutors',15/60, function() use ($request)
            {
                // Caché válida durante 15 segundos.
                return diagnostico_tutor::create($request->all());
            });
	
		$diagnostico_tutor->save();
	
        return response()->json(['data'=>$diagnostico_tutor,
            'message' => 'Relacion Diagnostico_Tutor Creada'], 201);
    }
    
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\diagnostico_tutor  $diagnostico_tutor
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, $id2)
    {
        $diagnostico_tutor=Cache::remember('diagnostico_tutors',15/60, function() use ($id,$id2)
		{
			// Caché válida durante 15 segundos.
			return diagnostico_tutor::where([
                'idAnomalia' => $id, 
                'idProfesor' => $id2])->first();
        });
        
		if(!$diagnostico_tutor)
		{
			return response()->json(
				['errors'=>array(['code'=>404,
				'message'=>'No se encuentra un diagnostico_tutor con ese identificador.',
                'identificador_1'=>$id,
                'identificador_2'=>$id2
			])],404);
		}

        $diagnostico_tutor=Cache::remember('diagnostico_tutors',15/60, function() use ($id,$id2)
		{
			// Caché válida durante 15 segundos.
			diagnostico_tutor::where([
                'idAnomalia' => $id, 
                'idProfesor' => $id2])->delete();
        });
		
		return response()->json([
			'status'=>true,
			'message'=>'Se ha eliminado el diagnostico_tutor correctamente.'
		],200);
    }
}
