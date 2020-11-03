<?php

namespace App\Http\Controllers;

use App\cuerpo_dece;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class CuerpoDeceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $cuerpo_dece=Cache::remember('cuerpo_deces',30/60, function()
            {
                // Caché válida durante 30 segundos.
                return cuerpo_dece::all();
            });
           
        return response()->json([
			'status'=>true,
			'data'=>$cuerpo_dece], 200);
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
            'idPersona'     => 'required|string|max:10|exists:users,idPersona|unique:cuerpo_deces,idPersona',            
            'cargo'     => 'required|string|max:30'            
        ]);

        $cuerpo_dece=Cache::remember('cuerpo_deces',15/60, function() use ($request)
            {
                // Caché válida durante 15 segundos.
                return cuerpo_dece::create($request->all());
            });
	
        $cuerpo_dece->save();
     /*    
        // Le asignamos el rol
        $cuerpo_dece->assignRole('Cuerpo_dece');  */

	
        return response()->json(['data'=>$cuerpo_dece,
            'message' => 'Cuerpo DECE Creado'], 201)
            ->header('Location', env('APP_URL').'cuerpo_deces/'.$cuerpo_dece->idPersona)
            ->header('Content-Type', 'application/json');
    }
    
    /**
     * Display the specified resource.
     *
     * @param  \App\cuerpo_dece  $cuerpo_dece
     * @return \Illuminate\Http\Response
     */
    public function show ($id)
    {
        $cuerpo_dece=Cache::remember('cuerpo_deces',30/60, function() use ($id)
		{
			// Caché válida durante 30 segundos.
			return cuerpo_dece::find($id);
		});

		if(!$cuerpo_dece)
		{
			return response()->json(
				['errors'=>array(['code'=>404,
				'message'=>'No se encuentra un Cuerpo DECE con ese identificador.',
				'identificador'=>$id
			])],404);
		}

		return response()->json([
			'status'=>true,
			'data'=>$cuerpo_dece],200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\cuerpo_dece  $cuerpo_dece
     * @return \Illuminate\Http\Response
     */
    public function edit(cuerpo_dece $cuerpo_dece)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\cuerpo_dece  $cuerpo_dece
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $cuerpo_dece=Cache::remember('cuerpo_deces',15/60, function() use ($id)
		{
			// Caché válida durante 15 segundos.
			return cuerpo_dece::find($id);
		});

		if(!$cuerpo_dece)
		{
			return response()->json(
				['errors'=>array(['code'=>404,
				'message'=>'No se encuentra un Cuerpo DECE con ese identificador.',
				'identificador'=>$id
			])],404);
		}
		
		if($request->method() === 'PUT')
		{
            $request->validate([
                'cargo'     => 'required|string|max:30'
            ]);
			
			$cuerpo_dece->cargo = $request->cargo;
		
			$cuerpo_dece->save();

			return response()->json([
				'status'=>true,
				'data'=>$cuerpo_dece],200)
				->header('Location', env('APP_URL').'cuerpo_deces/'.$cuerpo_dece->idPersona)
				->header('Content-Type', 'application/json');
		}else{
			// Creamos una bandera para controlar si se ha modificado algún dato en el método PATCH.
			$bandera = false;

			if ($request->cargo!= null)
			{
				$request->validate([
                    'cargo'     => 'required|string|max:30'
                ]);

				$cuerpo_dece->cargo = $request->cargo;
				$bandera=true;
			}
			
			if ($bandera)
			{
				// Almacenamos en la base de datos el registro.
				$cuerpo_dece->save();
				return response()->json([
					'status'=>true,
					'data'=>$cuerpo_dece],200)
					->header('Location', env('APP_URL').'cuerpo_deces/'.$cuerpo_dece->idPersona)
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
     * @param  \App\cuerpo_dece  $cuerpo_dece
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $cuerpo_dece=Cache::remember('cuerpo_deces',15/60, function() use ($id)
		{
			// Caché válida durante 15 segundos.
			return cuerpo_dece::find($id);  
		});
		
		if(!$cuerpo_dece)
		{
			return response()->json(
				['errors'=>array(['code'=>404,
				'message'=>'No se encuentra un Cuerpo DECE con ese identificador.',
				'identificador'=>$id
			])],404);
		}

        $Persona=$cuerpo_dece->Persona->first();
        
        $PeriodoLectivo=$cuerpo_dece->PeriodoLectivo->first();

        $Cuestionarios=$cuerpo_dece->Cuestionarios->first();

		if ($Persona || $PeriodoLectivo || $Cuestionarios)
		{
			$cuerpo_dece->delete();
			
			return response()->json([
				'status'=>true,
				'message'=>'El Cuerpo DECE contaba con relaciones. Se ha eliminado el Cuerpo DECE correctamente.'
			],200);
			
		}   

		$cuerpo_dece->delete();

        return response()->json([
			'status'=>true,
			'message'=>'Se ha eliminado el Cuerpo DECE correctamente.'
		],200);
    }
}
