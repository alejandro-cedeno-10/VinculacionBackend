<?php

namespace App\Http\Controllers;

use App\paralelo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class ParaleloController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $paralelo=Cache::remember('paralelos',30/60, function()
            {
                // Caché válida durante 30 segundos.
                return paralelo::all();
            });
           
        return response()->json([
			'status'=>true,
			'data'=>$paralelo], 200);
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
            'paralelo'     => 'required|string|max:30|unique:paralelos,paralelo'            
        ]);

        $paralelo=Cache::remember('paralelos',15/60, function() use ($request)
            {
                // Caché válida durante 15 segundos.
                return paralelo::create($request->all());
            });
	
		$paralelo->save();
	
        return response()->json(['data'=>$paralelo,
            'message' => 'Paralelo Creado'], 201)
            ->header('Location', env('APP_URL').'paralelos/'.$paralelo->idParalelo)
            ->header('Content-Type', 'application/json');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\paralelo  $paralelo
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $paralelo=Cache::remember('paralelos',30/60, function() use ($id)
		{
			// Caché válida durante 30 segundos.
			return paralelo::find($id);
		});

		if(!$paralelo)
		{
			return response()->json(
				['errors'=>array(['code'=>404,
				'message'=>'No se encuentra un paralelo con ese identificador.',
				'identificador'=>$id
			])],404);
		}

		return response()->json([
			'status'=>true,
			'data'=>$paralelo],200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\paralelo  $paralelo
     * @return \Illuminate\Http\Response
     */
    public function edit(paralelo $paralelo)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\paralelo  $paralelo
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $paralelo=Cache::remember('paralelos',15/60, function() use ($id)
		{
			// Caché válida durante 15 segundos.
			return paralelo::find($id);
		});

		if(!$paralelo)
		{
			return response()->json(
				['errors'=>array(['code'=>404,
				'message'=>'No se encuentra un paralelo con ese identificador.',
				'identificador'=>$id
			])],404);
		}
		
		if($request->method() === 'PUT')
		{
            $request->validate([
                'paralelo'     => 'required|string|max:30'
            ]);
			
			$paralelo->paralelo = $request->paralelo;
		
			$paralelo->save();

			return response()->json([
				'status'=>true,
				'data'=>$paralelo],200)
				->header('Location', env('APP_URL').'paralelos/'.$paralelo->idParalelo)
				->header('Content-Type', 'application/json');
		}else{
			// Creamos una bandera para controlar si se ha modificado algún dato en el método PATCH.
			$bandera = false;

			if ($request->paralelo!= null)
			{
				$request->validate([
                    'paralelo'     => 'required|string|max:30'
                ]);

				$paralelo->paralelo = $request->paralelo ;
				$bandera=true;
			}
			
			if ($bandera)
			{
				// Almacenamos en la base de datos el registro.
				$paralelo->save();
				return response()->json([
					'status'=>true,
					'data'=>$paralelo],200)
					->header('Location', env('APP_URL').'paralelos/'.$paralelo->idParalelo)
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
     * @param  \App\paralelo  $paralelo
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $paralelo=Cache::remember('paralelos',15/60, function() use ($id)
		{
			// Caché válida durante 15 segundos.
			return paralelo::find($id);  
		});
		
		if(!$paralelo)
		{
			return response()->json(
				['errors'=>array(['code'=>404,
				'message'=>'No se encuentra un paralelo con ese identificador.',
				'identificador'=>$id
			])],404);
		}

		$Profesores=$paralelo->Profesores->first();

		$Materias=$paralelo->Materias->first();

        $Cursos=$paralelo->Cursos->first();

        $Especialidades=$paralelo->Especialidades->first();

        $PeriodoLectivo=$paralelo->PeriodoLectivo->first();

        $Matriculas=$paralelo->Matriculas->first();

        $Cuestionarios=$paralelo->Cuestionarios->first();

		if ($Profesores || $Materias || $Cursos || $Especialidades || $PeriodoLectivo || $Matriculas || $Cuestionarios)
		{
			$paralelo->delete();
			
			return response()->json([
				'status'=>true,
				'message'=>'El paralelo contaba con relaciones. Se ha eliminado el paralelo correctamente.'
			],200);
			
		}   

		$paralelo->delete();

        return response()->json([
			'status'=>true,
			'message'=>'Se ha eliminado el paralelo correctamente.'
		],200);
    }
}
