<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    // Configuramos en el constructor del 
	// controlador la autenticación usando el Middleware auth.basic,
    public function __construct()
	{	
		$this->middleware('auth:api');  
	    $this->middleware('role:SuperAdmin'); 
	
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $permissions = Permission::with('roles')->get( );

        return response( )->json([
            'status' => true,
            'permissions' => $permissions,],200);
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
        $request->validate(['name' => 'required|string|unique:permissions,name']);

        // Crea el permiso en la BD
        $permission = Permission::create( ['name' => $request->name, 'guard_name' => 'api'] );
         
        // Obtiene el rol SuperAdmin
        $role = Role::where('name','SuperAdmin')->first( );

        // Asigna el permiso creado al rol SuperAdmin
        $role->givePermissionTo( $permission );

        return response()->json(['data'=>$permission,
            'message' => 'Se ha creado el permiso con éxito'], 201)
            ->header('Location', env('APP_URL').'permissions/'.$permission->id)
            ->header('Content-Type', 'application/json');
    }

    /**
     * Asignar un permiso a un rol.
     *
     * @return [string] message
     */
    public function assign( Request $request ) {

        $request->validate([
            'permission_id'     => 'required|numeric|exists:permissions,id',
            'role_id'   => 'required|numeric|exists:roles,id'     
        ]);

        // Obtiene el permiso por ID
        $permission = Permission::find($request->permission_id);

        // Obtiene el rol por ID
        $role = Role::find($request->role_id);

        // Asigna el permiso al usuario
        $role->givePermissionTo($permission);

        return response( )->json([
                'status' => true,
                'message' => 'Permiso asignado con éxito.',
                'code'    => 201,
        ],201);
        
    }

     /**
     * Revoca un permiso a un rol.
     *
     * @return [string] message
     */
    public function revoke( Request $request ) {

        // Hace las validaciones de los datos enviados por el request
        $request->validate([
            'permission_id'     => 'required|numeric|exists:permissions,id',
            'role_id'   => 'required|numeric|exists:roles,id'     
        ]);

        // Obtiene el permiso por ID
        $permission = Permission::find($request->permission_id);

        // Obtiene el rol por ID
        $role = Role::find($request->role_id);

        $role->revokePermissionTo( $permission );

        return response()->json([
            'status' => true,
            'message' => 'Permiso revocado con éxito.',
            'code'    => 200,
        ],200);
        
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
        // Obtiene el permiso por ID
        $permission = Permission::find($id);
      
        if(!$permission)
        {
            return response( )->json([
                 'status' => false,
                 'message' => 'No existe un permiso con el ID enviado.',
                 'code'    => 404,
            ],404);
        }

        $permission->delete();
        
        return response( )->json([
            'status' => true,
            'message' => 'Permiso eliminado con éxito.',
            'code'    => 200,
        ],200 );

    }
}
