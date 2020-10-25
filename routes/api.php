<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
/* 
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
 */

Route::group([
    'prefix' => 'auth'
    ], function () {
        Route::post('login', 'AuthController@login');
        Route::post('signup', 'AuthController@signup');

    Route::group([
        ], function() {
            Route::get('/logout', 'AuthController@logout');
            Route::get('/user', 'AuthController@user');
    });
});

Route::group([
    ], function () { 
        Route::resource('users','UserController',[ 
            'except'=>['create','store','edit']]
        );
        Route::post('users_avatar', 'UserController@update_avatar');
}); 
 
Route::resource('cursos','CategoriaController',[ 
    'except'=>['create','edit']]
);

Route::resource('cursos','CuerpoDeceController',[ 
    'except'=>['create','edit']]
);

Route::resource('cursos','CuestionarioController',[ 
    'except'=>['create','edit']]
);

Route::resource('cursos','CursoController',[ 
    'except'=>['create','edit']]
);

Route::resource('cursos','EspecialidadController',[ 
    'except'=>['create','edit']]
);

Route::resource('cursos','EstadoController',[ 
    'except'=>['create','edit']]
);

Route::resource('cursos','EspecialidadController',[ 
    'except'=>['create','edit']]
);

Route::resource('cursos','EstadoController',[ 
    'except'=>['create','edit']]
);

Route::resource('cursos','EstudianteController',[ 
    'except'=>['create','edit']]
);

Route::resource('cursos','MateriaController',[ 
    'except'=>['create','edit']]
);


Route::resource('cursos','MatriculaController',[ 
    'except'=>['create','edit']]
);

Route::resource('cursos','MensajesController',[ 
    'except'=>['create','edit']]
);


Route::resource('cursos','OpcionController',[ 
    'except'=>['create','edit']]
);

Route::resource('cursos','ParaleloController',[ 
    'except'=>['create','edit']]
);


Route::resource('cursos','PeriodoLectivoController',[ 
    'except'=>['create','edit']]
);

Route::resource('cursos','PreguntaController',[ 
    'except'=>['create','edit']]
);


Route::resource('cursos','PeriodoLectivoController',[ 
    'except'=>['create','edit']]
);

Route::resource('cursos','PreguntaController',[ 
    'except'=>['create','edit']]
);

Route::resource('cursos','ProfesorController',[ 
    'except'=>['create','edit']]
);

Route::resource('cursos','RepresentanteController',[ 
    'except'=>['create','edit']]
);

Route::resource('cursos','RespuestaController',[ 
    'except'=>['create','edit']]
);

Route::resource('cursos','SubcategoriaController',[ 
    'except'=>['create','edit']]
);

Route::resource('cursos','TipoAsignaturaController',[ 
    'except'=>['create','edit']]
);



/* Route::group([ 
], function () { 
    Route::resource('users.cursos','UsersCursosController',[ 
        'only'=>['store','destroy']]
    );

    Route::resource('userCurso','UserCursoController',[ 
        'only'=>['index','show']]
    );

    Route::resource('cursoUser','CursoUserController',[ 
        'only'=>['index','show']]
    );
});  

Route::group([ 
    ], function () { 
        Route::resource('cursos.paralelos','CursosParalelosController',[ 
            'only'=>['store','destroy']]
        );

        Route::resource('cursoParalelo','CursoParaleloController',[ 
            'only'=>['index','show']]
        );

        Route::resource('paraleloCurso','ParaleloCursoController',[ 
            'only'=>['index','show']]
        );
});  */


Route::group([ 
], function () { 

    Route::post('roles/assign', 'RoleController@assign');
    Route::post('roles/revoke', 'RoleController@revoke');
    Route::resource('roles','RoleController',[ 
        'except'=>['create','edit','update']]
    );

    Route::post('permisos/assign', 'PermissionController@assign');
    Route::post('permisos/revoke', 'PermissionController@revoke');
    Route::resource('permisos','PermissionController',[ 
        'except'=>['create','edit']]
    );
    
}); 
