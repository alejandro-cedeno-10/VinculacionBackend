<?php

namespace App\Http\Controllers;

use App\mensajes;
use App\User;

use Spatie\QueryBuilder\QueryBuilder;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class MensajeUserController extends Controller
{
    

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $mensaje = QueryBuilder::for(mensajes::class)
            ->allowedIncludes('Persona')
            ->get();

        return response()->json([
			'status'=>true,
            'data'=>$mensaje
        ], 200);
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        $mensaje=Cache::remember('mensajes',15/60, function() use ($id)
		{
			// Caché válida durante 15 segundos.
			return mensajes::find($id);  
		});

        if(!$mensaje)
        {
            return response()->json(
                ['errors'=>array(['code'=>404,
                'message'=>'No se encuentra un mensaje con ese identificador.',
                'identificador'=>$id
            ])],404);
        }

        $Persona=$mensaje->Persona;

        return response()->json([
            'status'=>true,
            'data'=>$mensaje
        ], 200);
    }

    public function showEmisorReceptorAll($id,$id2)
    {
        $mensajes = QueryBuilder::for(mensajes::class)
        ->join('users', 'users.idPersona', 'mensajes.idPersona')
        ->whereIn('mensajes.idPersona',[$id,$id2])->whereIn('mensajes.receptor',[$id,$id2])
        ->select('mensajes.idPersona as Emisor','mensajes.receptor as Receptor')
        ->orderBy('cursos.created_at', 'desc')
        ->get();

		return response()->json([
			'status'=>true,
			'data'=>$mensajes],200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
