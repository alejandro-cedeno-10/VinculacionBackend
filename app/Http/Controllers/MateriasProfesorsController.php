<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;

use App\materia_profesor;
use Illuminate\Support\Facades\Cache;


class MateriasProfesorsController extends Controller
{    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $materia_profesor=Cache::remember('materia_profesors',15/60, function() use ($request)
		{
			// Caché válida durante 15 segundos.
            return materia_profesor::where([
                'idProfesor' => $request->idEstado,    
                'idCurso' => $request->idEstudiante,
                'idParalelo' => $request->id,
                'idEspecialidad' => $request->id,
                'idPeriodoLectivo' => $request->id,
                'idMateria' => $request->id])->first();
        });
        
		if($materia_profesor)
		{
			return response()->json(
				['errors'=>array(['status'=>false,
				'message'=>'Ya existe esta relacion de materia_profesor',
                'identificador_1'=>$request->idProfesor,
                'identificador_2'=>$request->idCurso,
                'identificador_3'=>$request->idParalelo,
                'identificador_4'=>$request->idEspecialidad,
                'identificador_5'=>$request->idPeriodoLectivo,
                'identificador_6'=>$request->idMateria                   
			])],200);
        }
        
		$request->validate([
            'idProfesor'     => 'required|string|max:10|exists:profesors,idProfesor',
            'idCurso'     => 'required|numeric|exists:cursos,idCurso',
            'idParalelo'     => 'required|numeric|exists:paralelos,idParalelo',
            'idEspecialidad'     => 'required|numeric|exists:especialidads,idEspecialidad',
            'idPeriodoLectivo'     => 'required|numeric|exists:periodo_lectivos,idPeriodoLectivo',
            'idMateria'     => 'required|numeric|exists:materias,idMateria'            
        ]);

        $materia_profesor=Cache::remember('materia_profesors',15/60, function() use ($request)
            {
                // Caché válida durante 15 segundos.
                return materia_profesor::create($request->all());
            });
	
		$materia_profesor->save();
	
        return response()->json(['data'=>$materia_profesor,
            'message' => 'Relacion Materia_Profesor Creada'], 201);
    }
   
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\materia_profesor  $materia_profesor
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, $id2, $id3, $id4, $id5, $id6)
    {
        $materia_profesor=Cache::remember('materia_profesors',15/60, function() use ($id,$id2,$id3,$id4,$id5,$id6)
		{
			// Caché válida durante 15 segundos.
			return materia_profesor::where([
                'idProfesor' => $id, 
                'idCurso' => $id2,  
                'idParalelo' => $id3,  
                'idEspeciaalidad' => $id4,  
                'idPeriodoLectivo' => $id5,  
                'idMateria' => $id6])->first();
        });
        
		if(!$materia_profesor)
		{
			return response()->json(
				['errors'=>array(['code'=>404,
				'message'=>'No se encuentra un materia_profesor con ese identificador.',
                'identificador_1'=>$id,
                'identificador_2'=>$id2,
                'identificador_3'=>$id3,
                'identificador_3'=>$id4,
                'identificador_3'=>$id5,
                'identificador_3'=>$id6
			])],404);
		}

        $materia_profesor=Cache::remember('materia_profesors',15/60, function() use ($id,$id2,$id3,$id4,$id5,$id6)
		{
			// Caché válida durante 15 segundos.
			materia_profesor::where([
                'idProfesor' => $id, 
                'idCurso' => $id2,  
                'idParalelo' => $id3,  
                'idEspeciaalidad' => $id4,  
                'idPeriodoLectivo' => $id5,  
                'idMateria' => $id6])->delete();
        });
		
		return response()->json([
			'status'=>true,
			'message'=>'Se ha eliminado el materia_profesor correctamente.'
		],200);
    }
}
