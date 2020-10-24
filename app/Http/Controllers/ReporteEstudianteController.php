<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;

use App\reporte_estudiante;
use Illuminate\Support\Facades\Cache;
class ReporteEstudianteController extends Controller
{    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $reporte_estudiante=Cache::remember('reporte_estudiantes',15/60, function() use ($request)
		{
			// Caché válida durante 15 segundos.
            return reporte_estudiante::where([
                'idEstudiante' => $request->idEstudiante,
                'idAnomalia' => $request->idAnomalia])->first();
        });
        
		if($reporte_estudiante)
		{
			return response()->json(
				['errors'=>array(['status'=>false,
				'message'=>'Ya existe esta relacion de reporte_estudiante',
                'identificador_1'=>$request->idEstudiante,
                'identificador_2'=>$request->idAnomalia               
			])],200);
        }
        
		$request->validate([
            'idEstudiante'     => 'required|string|max:10|exists:estudiantes,idEstudiante',
            'idAnomalia'     => 'required|numeric|exists:anomalias,idAnomalia'           
        ]);

        $reporte_estudiante=Cache::remember('reporte_estudiantes',15/60, function() use ($request)
            {
                // Caché válida durante 15 segundos.
                return reporte_estudiante::create($request->all());
            });
	
		$reporte_estudiante->save();
	
        return response()->json(['data'=>$reporte_estudiante,
            'message' => 'Relacion Reporte_Estudiante Creada'], 201);
    }
    
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\reporte_estudiante  $reporte_estudiante
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, $id2)
    {
        $reporte_estudiante=Cache::remember('reporte_estudiante',15/60, function() use ($id,$id2)
		{
			// Caché válida durante 15 segundos.
			return reporte_estudiante::where([
                'idEstudiante' => $id, 
                'idAnomalia' => $id2])->first();
        });
        
		if(!$reporte_estudiante)
		{
			return response()->json(
				['errors'=>array(['code'=>404,
				'message'=>'No se encuentra un reporte_estudiante con ese identificador.',
                'identificador_1'=>$id,
                'identificador_2'=>$id2
			])],404);
		}

        $reporte_estudiante=Cache::remember('reporte_estudiantes',15/60, function() use ($id,$id2)
		{
			// Caché válida durante 15 segundos.
			reporte_estudiante::where([
                'idEstudiante' => $id, 
                'idAnomalia' => $id2])->delete();
        });
		
		return response()->json([
			'status'=>true,
			'message'=>'Se ha eliminado el reporte_estudiante correctamente.'
		],200);
    }
}
