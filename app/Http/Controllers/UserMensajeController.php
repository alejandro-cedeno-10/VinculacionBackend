<?php

namespace App\Http\Controllers;

use App\User;
use App\Mensajes;
use Illuminate\Support\Facades\DB;

use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class UserMensajeController extends Controller
{
   


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = QueryBuilder::for(User::class)
            ->allowedIncludes('Mensaje')
            ->get();

        return response()->json([
			'status'=>true,
            'data'=>$user
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
        $user=Cache::remember('user',15/60, function() use ($id)
		{
			// Caché válida durante 15 segundos.
			return User::find($id);  
		});

        if(!$user)
        {
            return response()->json(
                ['errors'=>array(['code'=>404,
                'message'=>'No se encuentra un user con ese identificador.',
                'identificador'=>$id
            ])],404);
        }

        $Estudiante=$user->Mensaje;

        return response()->json([
            'status'=>true,
            'data'=>$user
        ], 200);
    }

    public function showReceptores($id)
    {
        //
        $user=Cache::remember('user',15/60, function() use ($id)
		{
			// Caché válida durante 15 segundos.
			return User::find($id);  
		});

        if(!$user)
        {
            return response()->json(
                ['errors'=>array(['code'=>404,
                'message'=>'No se encuentra un user con ese identificador.',
                'identificador'=>$id
            ])],404);
        }

        $user=QueryBuilder::for(User::where('idPersona', $id)) 
            ->allowedIncludes(['Mensaje'])
            ->get(); 

        return response()->json([
            'status'=>true,
            'data'=>$user
        ], 200);
    }


    public function showEmisorReceptor()
    {
        $mensajes = QueryBuilder::for(Mensajes::class)
        ->allowedIncludes(['Persona'])
        ->allowedFilters([
            AllowedFilter::exact('mensajes.idPersona', null),
            AllowedFilter::exact('mensajes.receptor', null)
            ])
        ->get();

		return response()->json([
			'status'=>true,
			'data'=>$mensajes],200);
    }


    public function showEmisorReceptorAll($id)
    {
        $mensajes = DB::table('mensajes')
        ->select('mensajes.idMensaje','mensajes.idPersona as emisor',"mensajes.receptor","mensajes.mensaje")
        ->where('mensajes.receptor',$id)
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
