<?php

namespace App\Http\Controllers;

use App\cuestionario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class CuestionarioController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $cuestionario=Cache::remember('cuestionarios',30/60, function()
            {
                // Caché válida durante 30 segundos.
                return cuestionario::all();
            });
           
        return response()->json([
			'status'=>true,
			'data'=>$cuestionario], 200);
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
        $request->validate([
            'idPeriodoLectivo'     => 'required|numeric|exists:periodo_lectivos,idPeriodoLectivo',
            'idCurso'     => 'required|numeric|exists:cursos,idCurso',      
            'idPersona'     => 'required|string|max:10|exists:cuerpo_deces,idPersona',
            'idEspecialidad'     => 'required|numeric|exists:especialidads,idEspecialidad',            
            'idParalelo'     => 'required|numeric|exists:paralelos,idParalelo'  
        ]);

        $cuestionario=Cache::remember('cuestionarios',15/60, function() use ($request)
            {
                // Caché válida durante 15 segundos.
                return cuestionario::create($request->all());
            });
	
		$cuestionario->save();
	
        return response()->json(['data'=>$cuestionario,
            'message' => 'Cuestionario Creado'], 201)
            ->header('Location', env('APP_URL').'cuestionarios/'.$cuestionario->idCuestionario)
            ->header('Content-Type', 'application/json');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\cuestionario  $cuestionario
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $cuestionario=Cache::remember('cuestionarios',30/60, function() use ($id)
		{
			// Caché válida durante 30 segundos.
			return cuestionario::find($id);
		});

        if(!$cuestionario)
		{
			return response()->json(
				['errors'=>array(['code'=>404,
				'message'=>'No se encuentra un cuestionario con ese identificador.',
				'identificador'=>$id
			])],404);
		}

		return response()->json([
			'status'=>true,
			'data'=>$cuestionario],200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\cuestionario  $cuestionario
     * @return \Illuminate\Http\Response
     */
    public function edit(cuestionario $cuestionario)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\cuestionario  $cuestionario
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\cuestionario  $cuestionario
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $cuestionario=Cache::remember('cuestionarios',15/60, function() use ($id)
		{
			// Caché válida durante 15 segundos.
			return cuestionario::find($id);  
		});
		
		if(!$cuestionario)
		{
			return response()->json(
				['errors'=>array(['code'=>404,
				'message'=>'No se encuentra un cuestionario con ese identificador.',
				'identificador'=>$id
			])],404);
		}

        $Cuerpo_dece=$cuestionario->Cuerpo_dece->first();
        
		$Curso=$cuestionario->Curso->first();

        $Paralelo=$cuestionario->Paralelo->first();

        $Especialidad=$cuestionario->Especialidad->first();

        $Periodo_Lectivo=$cuestionario->Periodo_Lectivo->first();
        
        $Preguntas=$cuestionario->Preguntas->first();

		if ($Cuerpo_dece || $Curso || $Paralelo || $Especialidad || $Periodo_Lectivo || $Preguntas)
		{
			$cuestionario->delete();
			
			return response()->json([
				'status'=>true,
				'message'=>'El cuestionario contaba con relaciones. Se ha eliminado el cuestionario correctamente.'
			],200);			
		}   

		$cuestionario->delete();

        return response()->json([
			'status'=>true,
			'message'=>'Se ha eliminado el cuestionario correctamente.'
		],200);
    }
}
