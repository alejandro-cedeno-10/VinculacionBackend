<?php

namespace App\Http\Controllers;

use App\profesor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class ProfesorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $profesor=Cache::remember('profesors',30/60, function()
            {
                // Caché válida durante 30 segundos.
                return profesor::all();
            });
       
        return response()->json([
            'status'=>true,
            'data'=>$profesor], 200);
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
            'idProfesor'     => 'required|string|max:10|exists:users,idPersona|unique:profesors,idProfesor',
            'cargo'     => 'required|string|max:50',
            'titulacion'     => 'required|string|max:50', 
            'fechaIngreso'     => 'required|date'           
        ]);

        $profesor=Cache::remember('profesors',15/60, function() use ($request)
            {
                // Caché válida durante 15 segundos.
                return profesor::create($request->all());
            });
	
        $profesor->save();
                
      /*   // Le asignamos el rol
        $profesor->assignRole('Profesor'); 
 */
	
        return response()->json(['data'=>$profesor,
            'message' => 'Profesor Creado'], 201)
            ->header('Location', env('APP_URL').'profesors/'.$profesor->idProfesor)
            ->header('Content-Type', 'application/json');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\profesor  $profesor
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $profesor=Cache::remember('profesors',30/60, function() use ($id)
		{
			// Caché válida durante 30 segundos.
			return profesor::find($id);
		});

		if(!$profesor)
		{
			return response()->json(
				['errors'=>array(['code'=>404,
				'message'=>'No se encuentra un profesor con ese identificador.',
				'identificador'=>$id
			])],404);
		}

		return response()->json([
			'status'=>true,
			'data'=>$profesor],200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\profesor  $profesor
     * @return \Illuminate\Http\Response
     */
    public function edit(profesor $profesor)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\profesor  $profesor
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $profesor=Cache::remember('profesors',15/60, function() use ($id)
		{
			// Caché válida durante 15 segundos.
			return profesor::find($id);
		});

		if(!$profesor)
		{
			return response()->json(
				['errors'=>array(['code'=>404,
				'message'=>'No se encuentra un profesor con ese identificador.',
				'identificador'=>$id
			])],404);
		}
		
		if($request->method() === 'PUT')
		{
            $request->validate([
                'cargo'     => 'required|string|max:50',
                'titulacion'     => 'required|string|max:50',
                'fechaIngreso'     => 'required|date'
            ]);
			
			$profesor->cargo = $request->cargo;
            $profesor->titulacion = $request->titulacion;
            $profesor->fechaIngreso = $request->fechaIngreso;
				
			$profesor->save();

			return response()->json([
				'status'=>true,
				'data'=>$profesor],200)
				->header('Location', env('APP_URL').'profesors/'.$profesor->idProfesor)
				->header('Content-Type', 'application/json');
		}else{
			// Creamos una bandera para controlar si se ha modificado algún dato en el método PATCH.
			$bandera = false;

            if ($request->cargo!= null)
			{
				$request->validate([
                    'cargo'     => 'required|string|max:50'
                ]);

				$profesor->cargo = $request->cargo;
				$bandera=true;
            }
            
            if ($request->titulacion!= null)
			{
				$request->validate([
                    'titulacion'     => 'required|string|max:50'
                ]);

				$profesor->titulacion = $request->titulacion;
				$bandera=true;
            }
            
            if ($request->fechaIngreso!= null)
			{
				$request->validate([
                    'fechaIngreso'     => 'required|date'
                ]);

				$profesor->fechaIngreso = $request->fechaIngreso;
				$bandera=true;
			}
			
			if ($bandera)
			{
				// Almacenamos en la base de datos el registro.
				$profesor->save();
				return response()->json([
					'status'=>true,
					'data'=>$profesor],200)
					->header('Location', env('APP_URL').'profesors/'.$profesor->idProfesor)
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
     * @param  \App\profesor  $profesor
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $profesor=Cache::remember('profesors',15/60, function() use ($id)
		{
			// Caché válida durante 15 segundos.
			return profesor::find($id);  
		});
		
		if(!$profesor)
		{
			return response()->json(
				['errors'=>array(['code'=>404,
				'message'=>'No se encuentra un profesor con ese identificador.',
				'identificador'=>$id
			])],404);
		}

		$User=$profesor->User->first();

		$Materias=$profesor->Materias->first();

        $Cursos=$profesor->Cursos->first();
        
        $Paralelos=$profesor->Paralelos->first();

        $Especialidades=$profesor->Especialidades->first();

        $PeriodoLectivo=$profesor->PeriodoLectivo->first();

        $Anomalias=$profesor->Anomalias->first();

		if ($User || $Materias || $Cursos || $Paralelos || $Especialidades || $PeriodoLectivo || $Anomalias)
		{
			$profesor->delete();
			
			return response()->json([
				'status'=>true,
				'message'=>'El profesor contaba con relaciones. Se ha eliminado el profesor correctamente.'
			],200);
			
		}   

		$profesor->delete();

        return response()->json([
			'status'=>true,
			'message'=>'Se ha eliminado el profesor correctamente.'
		],200);
    }
}
