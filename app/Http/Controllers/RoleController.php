<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;

use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    // Configuramos en el constructor del 
	// controlador la autenticación usando el Middleware auth.basic,
    public function __construct()
	{	
		/* $this->middleware('auth:api');  
	    $this->middleware('role:SuperAdmin'); 
	 */
    }

    public function index()
    {
        //
        $roles = Role::with('permissions')->get( );

        return response( )->json([
            'status' => true,
            'roles' => $roles,],200);
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
        $request->validate(['name' => 'required|string|unique:roles,name']);

        // Crea el Role en la BD
        $role = Role::create( ['name' => $request->name, 'guard_name' => 'api'] );
        
        return response()->json(['data'=>$role,
            'message' => 'Se ha creado el Role con éxito'], 201)
            ->header('Location', env('APP_URL').'roles/'.$role->id)
            ->header('Content-Type', 'application/json');
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
        // Obtiene el rol por ID
        $role = Role::find($id);

        // Si no consigue al rol retorna un error 404 (Not Found)
        if(!$role)
        {
            return response( )->json([
                 'status' => false,
                 'message' => 'No existe un rol con el ID enviado.',
                 'code'    => 404,
            ],404);
        }

        $users=User::role($role->name)->get();

        return response()->json(
            ['Rol'=>array(['atributos'=>$role,
            'users'=>$users
        ])],200);


    }

     /**
     * Asignar un Role a un User.
     *
     * @return [string] message
     */
    public function assign( Request $request ) {

        // Hace las validaciones de los datos enviados por el request
        $request->validate([
            'role_id'   => 'required|numeric|exists:roles,id',
            'model_id'     => 'required|string|min:10|max:10|exists:users,cedula',
        ]);

        // Obtiene el rol por ID
        $role = Role::find($request->role_id);

        // Obtiene al usuario a asignarle un rol por UUID
        $user=User::find($request->model_id);

        // Asigna el rol al usuario
        $user->assignRole( $role );

        // Retorna una respuesta 200 (OK)
        return response( )->json([
            'status' => true,
            'message' => 'Rol asignado con éxito.',
            'code'    => 201,
        ],201);
        
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
     * Revoca un Role a un User.
     *
     * @return [string] message
     */
    public function revoke( Request $request ) {

        // Hace las validaciones de los datos enviados por el request
        $request->validate([
            'role_id'   => 'required|numeric|exists:roles,id',
            'model_id'     => 'required|string|min:10|max:10|exists:users,cedula',
        ]);

        // Obtiene el rol por ID
        $role = Role::find($request->role_id);
       
        // Obtiene al usuario a asignarle un rol por UUID
        $user=User::find($request->model_id);
       
        $user->removeRole($role);
            
        return response( )->json([
            'status' => true,
            'message' => 'Rol revocado con éxito.',
            'code'    => 200,
        ],200);

       
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // Obtiene el rol por ID
        $role = Role::find($id);

        // Si no consigue al rol retorna un error 404 (Not Found)
        if(!$role)
        {
            return response( )->json([
                 'status' => false,
                 'message' => 'No existe un rol con el ID enviado.',
                 'code'    => 404,
            ],404);
        }

        // Elimina el rol de la BD
        $role->delete();
        
        return response( )->json([
            'status' => true,
            'message' => 'Rol eliminado con éxito.',
            'code'    => 200,
        ],200 );
    }
}
