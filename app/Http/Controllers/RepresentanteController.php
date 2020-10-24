<?php

namespace App\Http\Controllers;

use App\representante;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class RepresentanteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $representante=Cache::remember('representantes',30/60, function()
            {
                // Caché válida durante 30 segundos.
                return representante::all();
            });
           
        return response()->json([
            'status'=>true,
            'data'=>$representante], 200);
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
            'idRepresentante'     => 'required|string|max:10',
            'ocupacion'     => 'required|string|max:50',
            'direccionTrabajo'     => 'required|string|max:150'            
        ]);

        $representante=Cache::remember('representantes',15/60, function() use ($request)
            {
                // Caché válida durante 15 segundos.
                return representante::create($request->all());
            });
	
		$representante->save();
	
        return response()->json(['data'=>$curso,
            'message' => 'Representante Creado'], 201)
            ->header('Location', env('APP_URL').'representantes/'.$representante->idRepresentante)
            ->header('Content-Type', 'application/json');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\representante  $representante
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $representante=Cache::remember('representantes',30/60, function() use ($id)
		{
			// Caché válida durante 30 segundos.
			return representante::find($id);
		});

		if(!$representante)
		{
			return response()->json(
				['errors'=>array(['code'=>404,
				'message'=>'No se encuentra un representante con ese identificador.',
				'identificador'=>$id
			])],404);
		}

		return response()->json([
			'status'=>true,
			'data'=>$representante],200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\representante  $representante
     * @return \Illuminate\Http\Response
     */
    public function edit(representante $representante)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\representante  $representante
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $representante=Cache::remember('representantes',15/60, function() use ($id)
		{
			// Caché válida durante 15 segundos.
			return representante::find($id);
		});

		if(!$representante)
		{
			return response()->json(
				['errors'=>array(['code'=>404,
				'message'=>'No se encuentra un representante con ese identificador.',
				'identificador'=>$id
			])],404);
		}
		
		if($request->method() === 'PUT')
		{
            $request->validate([
                'ocupacion'     => 'required|string|max:50',
                'direccionTrabajo'     => 'required|string|max:150'
            ]);
			
			$representante->ocupacion = $request->ocupacion;
			$representante->direccionTrabajo = $request->direccionTrabajo;			
		
			$representante->save();

			return response()->json([
				'status'=>true,
				'data'=>$representante],200)
				->header('Location', env('APP_URL').'representantes/'.$representante->idRepresentante)
				->header('Content-Type', 'application/json');
		}else{
			// Creamos una bandera para controlar si se ha modificado algún dato en el método PATCH.
			$bandera = false;

            if ($request->ocupacion!= null)
			{
				$request->validate([
                    'ocupacion'     => 'required|string|max:50'
                ]);

				$representante->ocupacion = $request->ocupacion;
				$bandera=true;
			}
            
            if ($request->direccionTrabajo!= null)
			{
				$request->validate([
                    'direccionTrabajo'     => 'required|string|max:150'
                ]);

				$representante->direccionTrabajo = $request->direccionTrabajo;
				$bandera=true;
			}
			
			if ($bandera)
			{
				// Almacenamos en la base de datos el registro.
				$representante->save();
				return response()->json([
					'status'=>true,
					'data'=>$representante],200)
					->header('Location', env('APP_URL').'representantes/'.$representante->idRepresentante)
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
     * @param  \App\representante  $representante
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $representante=Cache::remember('representantes',15/60, function() use ($id)
		{
			// Caché válida durante 15 segundos.
			return representante::find($id);  
		});
		
		if(!$representante)
		{
			return response()->json(
				['errors'=>array(['code'=>404,
				'message'=>'No se encuentra un representante con ese identificador.',
				'identificador'=>$id
			])],404);
		}

		$User=$representante->User->first();

        $Matriculas=$representante->Matriculas->first();

		if ($User || $Matriculas)
		{
			$representante->delete();
			
			return response()->json([
				'status'=>true,
				'message'=>'El representante contaba con relaciones. Se ha eliminado el representante correctamente.'
			],200);
			
		}   

		$representante->delete();

        return response()->json([
			'status'=>true,
			'message'=>'Se ha eliminado el representante correctamente.'
		],200);
    }
}
