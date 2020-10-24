<?php

namespace App\Http\Controllers;

use App\periodo_lectivo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class PeriodoLectivoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $periodo_lectivo=Cache::remember('periodo_lectivos',30/60, function()
            {
                // Caché válida durante 30 segundos.
                return periodo_lectivo::all();
            });
           
        return response()->json([
			'status'=>true,
			'data'=>$periodo_lectivo], 200);
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
            'fechaInicio'     => 'required|date',
            'fechaFinal'     => 'required|date',
            'periodoLectivo'     => 'required|string|max:30'            
        ]);

        $periodo_lectivo=Cache::remember('periodo_lectivos',15/60, function() use ($request)
            {
                // Caché válida durante 15 segundos.
                return periodo_lectivo::create($request->all());
            });
	
		$periodo_lectivo->save();
	
        return response()->json(['data'=>$periodo_lectivo,
            'message' => 'Periodo Lectivo Creado'], 201)
            ->header('Location', env('APP_URL').'periodo_lectivos/'.$periodo_lectivo->idPeriodoLectivo)
            ->header('Content-Type', 'application/json');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\periodo_lectivo  $periodo_lectivo
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $periodo_lectivo=Cache::remember('periodo_lectivos',30/60, function() use ($id)
		{
			// Caché válida durante 30 segundos.
			return periodo_lectivo::find($id);
		});

		if(!$periodo_lectivo)
		{
			return response()->json(
				['errors'=>array(['code'=>404,
				'message'=>'No se encuentra un Periodo Lectivo con ese identificador.',
				'identificador'=>$id
			])],404);
		}

		return response()->json([
			'status'=>true,
			'data'=>$periodo_lectivo],200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\periodo_lectivo  $periodo_lectivo
     * @return \Illuminate\Http\Response
     */
    public function edit(periodo_lectivo $periodo_lectivo)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\periodo_lectivo  $periodo_lectivo
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $periodo_lectivo=Cache::remember('periodo_lectivos',15/60, function() use ($id)
		{
			// Caché válida durante 15 segundos.
			return periodo_lectivo::find($id);
		});

		if(!$periodo_lectivo)
		{
			return response()->json(
				['errors'=>array(['code'=>404,
				'message'=>'No se encuentra un Periodo Lectivo con ese identificador.',
				'identificador'=>$id
			])],404);
		}
		
		if($request->method() === 'PUT')
		{
            $request->validate([
                'fechaInicio'     => 'required|date',
                'fechaFinal'     => 'required|date',
                'periodoLectivo'     => 'required|string|max:30'
            ]);
			
            $periodo_lectivo->fechaInicio = $request->fechaInicio;
            $periodo_lectivo->fechaFinal = $request->fechaFinal;
            $periodo_lectivo->periodoLectivo = $request->periodoLectivo;
		
			$periodo_lectivo->save();

			return response()->json([
				'status'=>true,
				'data'=>$periodo_lectivo],200)
				->header('Location', env('APP_URL').'periodo_lectivos/'.$periodo_lectivo->idPeriodoLectivo)
				->header('Content-Type', 'application/json');
		}else{
			// Creamos una bandera para controlar si se ha modificado algún dato en el método PATCH.
			$bandera = false;

            if ($request->fechaInicio!= null)
			{
				$request->validate([
                    'fechaInicio'     => 'required|date'
                ]);

				$periodo_lectivo->fechaInicio = $request->fechaInicio ;
				$bandera=true;
            }
            
            if ($request->fechaFinal!= null)
			{
				$request->validate([
                    'fechaFinal'     => 'required|date'
                ]);

				$periodo_lectivo->fechaFinal = $request->fechaFinal;
				$bandera=true;
			}
            
            if ($request->periodoLectivo!= null)
			{
				$request->validate([
                    'periodoLectivo'     => 'required|string|max:30'
                ]);

				$periodo_lectivo->periodoLectivo = $request->periodoLectivo;
				$bandera=true;
			}
			
			if ($bandera)
			{
				// Almacenamos en la base de datos el registro.
				$periodo_lectivo->save();
				return response()->json([
					'status'=>true,
					'data'=>$periodo_lectivo],200)
					->header('Location', env('APP_URL').'periodo_lectivos/'.$periodo_lectivo->idPeriodoLectivo)
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
     * @param  \App\periodo_lectivo  $periodo_lectivo
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $periodo_lectivo=Cache::remember('periodo_lectivos',15/60, function() use ($id)
		{
			// Caché válida durante 15 segundos.
			return periodo_lectivo::find($id);  
		});
		
		if(!$periodo_lectivo)
		{
			return response()->json(
				['errors'=>array(['code'=>404,
				'message'=>'No se encuentra un Periodo Lectivo con ese identificador.',
				'identificador'=>$id
			])],404);
		}

		$Profesores=$periodo_lectivo->Profesores->first();

		$Materias=$periodo_lectivo->Materias->first();

        $Cursos=$periodo_lectivo->Cursos->first();
       
        $Paralelos=$periodo_lectivo->Paralelos->first();

        $Especialidades=$periodo_lectivo->Especialidades->first();

        $Matriculas=$periodo_lectivo->Matriculas->first();

        $Cuestionarios=$periodo_lectivo->Cuestionarios->first();

        $Cuerpos_dece=$periodo_lectivo->Cuerpos_dece->first();

		if ($Profesores || $Materias || $Cursos || $Paralelos || $Especialidades || $Matriculas || $Cuestionarios || $Cuerpos_dece )
		{
			$periodo_lectivo->delete();
			
			return response()->json([
				'status'=>true,
				'message'=>'El Periodo Lectivo contaba con relaciones. Se ha eliminado el Periodo Lectivo correctamente.'
			],200);
			
		}   

		$periodo_lectivo->delete();

        return response()->json([
			'status'=>true,
			'message'=>'Se ha eliminado el Periodo Lectivo correctamente.'
		],200);
    }
}
