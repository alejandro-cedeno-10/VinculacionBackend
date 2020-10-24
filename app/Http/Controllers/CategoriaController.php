<?php

namespace App\Http\Controllers;

use App\categoria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class CategoriaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categoria=Cache::remember('categorias',30/60, function()
			{
				// Caché válida durante 30 segundos.
				return categoria::all();
			}); 

		return response()->json([
			'status'=>true,
			'data'=>$categoria], 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
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
            'nombreCategoria'     => 'required|string|max:50'            
        ]);

        $categoria=Cache::remember('categorias',15/60, function() use ($request)
            {
                // Caché válida durante 15 segundos.
                return categoria::create($request->all());
            });
	
		$categoria->save();
            
        return response()->json(['data'=>$categoria,
            'message' => 'Categoria Creada'], 201)
            ->header('Location', env('APP_URL').'categorias/'.$categoria->idCategoria)
            ->header('Content-Type', 'application/json');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\categoria  $categoria
     * @return \Illuminate\Http\Response
     */
    public function show ($id)
    {
        $categoria=Cache::remember('categorias',30/60, function() use ($id)
		{
			// Caché válida durante 30 segundos.
			return categoria::find($id);
		});

		if(!$categoria)
		{
			return response()->json(
				['errors'=>array(['code'=>404,
				'message'=>'No se encuentra una categoria con ese identificador.',
				'identificador'=>$id
			])],404);
		}

		return response()->json([
			'status'=>true,
			'data'=>$categoria],200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\categoria  $categoria
     * @return \Illuminate\Http\Response
     */
    public function edit(categoria $categoria)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\categoria  $categoria
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$id)
    {
        $categoria=Cache::remember('categorias',15/60, function() use ($id)
		{
			// Caché válida durante 15 segundos.
			return categpria::find($id);
		});

		if(!$categoria)
		{
			return response()->json(
				['errors'=>array(['code'=>404,
				'message'=>'No se encuentra una categoria con ese identificador.',
				'identificador'=>$id
			])],404);
		}
		
		if($request->method() === 'PUT')
		{
            $request->validate([
                'nombreCategoria'     => 'required|string|max:50'               
            ]);
			
			$categoria->nombreCategoria = $request->nombreCategoria;
						
			$categoria->save();

			return response()->json([
				'status'=>true,
				'data'=>$categoria],200)
				->header('Location', env('APP_URL').'categorias/'.$categoria->idCategoria)
				->header('Content-Type', 'application/json');


		}else{
			// Creamos una bandera para controlar si se ha modificado algún dato en el método PATCH.
			$bandera = false;

			if ($request->nombreCategoria!= null)
			{
				$request->validate([
                    'nombreCategoria'     => 'required|string|max:50'
				]);

				$categoria->nombreCategoria = $request->nombreCategoria;
				$bandera=true;
			}
	
			if ($bandera)
			{
				// Almacenamos en la base de datos el registro.
				$categoria->save();
				return response()->json([
					'status'=>true,
					'data'=>$categoria],200)
					->header('Location', env('APP_URL').'categorias/'.$categoria->idCategoria)
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
     * @param  \App\categoria  $categoria
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $categoria=Cache::remember('categorias',15/60, function() use ($id)
		{
			// Caché válida durante 15 segundos.
			return categoria::find($id);  
		});
		
		if(!$categoria)
		{
			return response()->json(
				['errors'=>array(['code'=>404,
				'message'=>'No se encuentra una categoria con ese identificador.',
				'identificador'=>$id
			])],404);
		}

		$SubCategorias=$categoria->SubCategorias->first();

		if ($SubCategorias)
		{
			$categoria->delete();
			
			return response()->json([
				'status'=>true,
				'message'=>'La categoria contaba con relaciones. Se ha eliminado la categoria correctamente.'
			],200);
			
		}   

		$categoria->delete();

        return response()->json([
			'status'=>true,
			'message'=>'Se ha eliminado la categoria correctamente.'
		],200);
    }
}
