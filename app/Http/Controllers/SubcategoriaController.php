<?php

namespace App\Http\Controllers;

use App\subcategoria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class SubcategoriaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $subcategoria=Cache::remember('subcategorias',30/60, function()
            {
                // Caché válida durante 30 segundos.
                return subcategoria::all();
            });

        return response()->json([
			'status'=>true,
			'data'=>$subcategoria], 200);
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
            'idCategoria'     => 'required|numeric|exists:categorias,idCategoria',
            'nombreSubcategoria'     => 'required|string|max:30',
            'sugerencia'     => 'required|string|max:80',
        ]);

        $subcategoria=Cache::remember('subcategorias',15/60, function() use ($request)
            {
                // Caché válida durante 15 segundos.
                return subcategoria::create($request->all());
            });

		$subcategoria->save();

        return response()->json(['data'=>$subcategoria,
            'message' => 'Subcategoria Creada'], 201)
            ->header('Location', env('APP_URL').'subcategorias/'.$subcategoria->idSubcategoria)
            ->header('Content-Type', 'application/json');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\subcategoria  $subcategoria
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $subcategoria=Cache::remember('subcategorias',30/60, function() use ($id)
		{
			// Caché válida durante 30 segundos.
			return subcategoria::find($id);
		});

		if(!$subcategoria)
		{
			return response()->json(
				['errors'=>array(['code'=>404,
				'message'=>'No se encuentra una subcategoria con ese identificador.',
				'identificador'=>$id
			])],404);
		}

		return response()->json([
			'status'=>true,
			'data'=>$subcategoria],200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\subcategoria  $subcategoria
     * @return \Illuminate\Http\Response
     */
    public function edit(subcategoria $subcategoria)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\subcategoria  $subcategoria
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $subcategoria=Cache::remember('subcategorias',15/60, function() use ($id)
		{
			// Caché válida durante 15 segundos.
			return subcategoria::find($id);
		});

		if(!$subcategoria)
		{
			return response()->json(
				['errors'=>array(['code'=>404,
				'message'=>'No se encuentra una subcategoria con ese identificador.',
				'identificador'=>$id
			])],404);
		}

		if($request->method() === 'PUT')
		{
            $request->validate([
                'nombreSubcategoria'     => 'required|string|max:30',
                'sugerencia'     => 'required|string|max:80'
            ]);

            $subcategoria->nombreSubcategoria= $request->nombreSubcategoria;
            $subcategoria->sugerencia= $request->sugerencia;

			$subcategoria->save();

			return response()->json([
				'status'=>true,
				'data'=>$subcategoria],200)
				->header('Location', env('APP_URL').'subcategorias/'.$subcategoria->idSubcategoria)
				->header('Content-Type', 'application/json');
		}else{
			// Creamos una bandera para controlar si se ha modificado algún dato en el método PATCH.
			$bandera = false;

            if ($request->nombreSubcategoria!= null)
			{
				$request->validate([
                    'nombreSubcategoria'     => 'required|string|max:30'
                ]);

				$subcategoria->nombreSubcategoria= $request->nombreSubcategoria;
				$bandera=true;
            }

            if ($request->sugerencia!= null)
			{
				$request->validate([
                    'sugerencia'     => 'required|string|max:80'
                ]);

				$subcategoria->sugerencia= $request->sugerencia;
				$bandera=true;
			}

			if ($bandera)
			{
				// Almacenamos en la base de datos el registro.
				$subcategoria->save();
				return response()->json([
					'status'=>true,
					'data'=>$subcategoria],200)
					->header('Location', env('APP_URL').'subcategorias/'.$subcategoria->idSubcategoria)
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
     * @param  \App\subcategoria  $subcategoria
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $subcategoria=Cache::remember('subcategorias',15/60, function() use ($id)
		{
			// Caché válida durante 15 segundos.
			return subcategoria::find($id);
		});

		if(!$subcategoria)
		{
			return response()->json(
				['errors'=>array(['code'=>404,
				'message'=>'No se encuentra una subcategoria con ese identificador.',
				'identificador'=>$id
			])],404);
		}

        $Anomalias=$subcategoria->Anomalias->first();

        $Categoria=$subcategoria->Categoria->first();

		if ($Anomalias || $Categoria)
		{
			$subcategoria->delete();

			return response()->json([
				'status'=>true,
				'message'=>'La subcategoria contaba con relaciones. Se ha eliminado la subcategoria correctamente.'
			],200);

		}

		$subcategoria->delete();

        return response()->json([
			'status'=>true,
			'message'=>'Se ha eliminado la subcategoria correctamente.'
		],200);
    }
}
