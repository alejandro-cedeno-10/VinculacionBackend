<?php

namespace App\Http\Controllers;

use App\especialidad;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class EspecialidadController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $especialidad=Cache::remember('especialidads',30/60, function()
            {
                // Caché válida durante 30 segundos.
                return especialidad::all();
            });
       
        return response()->json([
            'status'=>true,
            'data'=>$especialidad], 200);
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
            'especialidad'     => 'required|string|max:30'               
        ]);

        $especialidad=Cache::remember('especialidads',15/60, function() use ($request)
            {
                // Caché válida durante 15 segundos.
                return especialidad::create($request->all());
            });
	
		$especialidad->save();
	
        return response()->json(['data'=>$especialidad,
            'message' => 'Especialidad Creada'], 201)
            ->header('Location', env('APP_URL').'especialidads/'.$especialidad->idEspecialidad)
            ->header('Content-Type', 'application/json');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\especialidad  $especialidad
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $especialidad=Cache::remember('especialidads',30/60, function() use ($id)
		{
			// Caché válida durante 30 segundos.
			return especialidad::find($id);
		});

		if(!$especialidad)
		{
			return response()->json(
				['errors'=>array(['code'=>404,
				'message'=>'No se encuentra una especialidad con ese identificador.',
				'identificador'=>$id
			])],404);
		}

		return response()->json([
			'status'=>true,
			'data'=>$especialidad],200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\especialidad  $especialidad
     * @return \Illuminate\Http\Response
     */
    public function edit(especialidad $especialidad)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\especialidad  $especialidad
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $especialidad=Cache::remember('especialidads',15/60, function() use ($id)
		{
			// Caché válida durante 15 segundos.
			return especialidad::find($id);
		});

		if(!$especialidad)
		{
			return response()->json(
				['errors'=>array(['code'=>404,
				'message'=>'No se encuentra una especialidad con ese identificador.',
				'identificador'=>$id
			])],404);
		}
		
		if($request->method() === 'PUT')
		{
            $request->validate([
                'especialidad'     => 'required|string|max:30'
            ]);
			
			$especialidad->especialidad = $request->especialidad;
		
			$especialidad->save();

			return response()->json([
				'status'=>true,
				'data'=>$especialidad],200)
				->header('Location', env('APP_URL').'especialidads/'.$especialidad->idEspecialidad)
				->header('Content-Type', 'application/json');
		}else{
			// Creamos una bandera para controlar si se ha modificado algún dato en el método PATCH.
			$bandera = false;

			if ($request->especialidad!= null)
			{
				$request->validate([
                    'especialidad'     => 'required|string|max:30'
                ]);

				$especialidad->especialidad = $request->especialidad;
				$bandera=true;
			}
			
			if ($bandera)
			{
				// Almacenamos en la base de datos el registro.
				$especialidad->save();
				return response()->json([
					'status'=>true,
					'data'=>$especialidad],200)
					->header('Location', env('APP_URL').'especialidads/'.$especialidad->idEspecialidad)
					->header('Content-Type', 'application/json');
			}
			else
			{
				return response()->json([
					'errors'=>array(['
					status'=>false,
					'message'=>'No se ha modificado ningún dato.'])
				],200);
			}		
		}
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\especialidad  $especialidad
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $especialidad=Cache::remember('especialidads',15/60, function() use ($id)
		{
			// Caché válida durante 15 segundos.
			return especialidad::find($id);  
		});
		
		if(!$especialidad)
		{
			return response()->json(
				['errors'=>array(['code'=>404,
				'message'=>'No se encuentra una especialidad con ese identificador.',
				'identificador'=>$id
			])],404);
		}

		$Profesores=$especialidad->Profesores->first();

		$Materias=$especialidad->Materias->first();

        $Cursos=$especialidad->Cursos->first();
        
        $Paralelos=$especialidad->Paralelos->first();
        
        $Periodo_Lectivos=$especialidad->Periodo_Lectivos->first();

        $Matriculas=$especialidad->Matriculas->first();

        $Cuestionarios=$especialidad->Cuestionarios->first();
        
		if ($Profesores || $Materias || $Cursos || $Paralelos || $Periodo_Lectivos || $Matriculas || $Cuestionarios)
		{
			$especialidad->delete();
			
			return response()->json([
				'status'=>true,
				'message'=>'La especialidad contaba con relaciones. Se ha eliminado la especialidad correctamente.'
			],200);
			
		}   

		$especialidad->delete();

        return response()->json([
			'status'=>true,
			'message'=>'Se ha eliminado la especialidad correctamente.'
		],200);
    }
}
