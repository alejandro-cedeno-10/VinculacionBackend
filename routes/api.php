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
        Route::get('signup/activate/{token}', 'AuthController@signupActivate');

    Route::group([
        ], function() {
            Route::get('/logout', 'AuthController@logout');
            Route::get('/user', 'AuthController@user');
    });
});

Route::group([    
    'prefix' => 'password'
    ], function () {    
        Route::post('create', 'PasswordResetController@create');
        Route::get('find/{token}', 'PasswordResetController@find');
        Route::post('reset', 'PasswordResetController@reset');
});   

Route::group([
    ], function () { 
        Route::resource('users','UserController',[ 
            'except'=>['create','store','edit']]
        );
        Route::post('users_avatar', 'UserController@update_avatar');
}); 
 
Route::resource('cursos','CursoController',[ 
    'except'=>['create','edit']]
);

Route::group([ 
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


Route::resource('paralelos','ParaleloController',[ 
    'except'=>['create','edit']]
);

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
}); 


Route::group([ 
], function () { 
    Route::resource('materias','MateriaController',[ 
        'except'=>['create','edit']]
    );
    Route::post('materias_imagen', 'MateriaController@update_imagen');
});


Route::group([ 
], function () { 
    Route::resource('cursos.materias','CursosMateriasController',[ 
        'only'=>['store','destroy']]
    );

    Route::resource('cursoMateria','CursoMateriaController',[ 
        'only'=>['index','show']]
    );

    Route::resource('materiaCurso','MateriaCursoController',[ 
        'only'=>['index','show']]
    );
}); 

Route::resource('unidads','UnidadController',[ 
    'except'=>['create','edit']]
);

Route::group([ 
], function () { 
    Route::resource('materiaUnidad','MateriaUnidadController',[ 
        'only'=>['index','show']]
    );
    Route::resource('unidadMateria','UnidadMateriaController',[ 
        'only'=>['index','show']]
    );
}); 

Route::resource('recursos','RecursoController',[ 
    'except'=>['create','edit']]
);

Route::group([ 
], function () { 
    Route::resource('unidadRecurso','UnidadRecursoController',[ 
        'only'=>['index','show']]
    );
    Route::resource('recursoUnidad','RecursoUnidadController',[ 
        'only'=>['index','show']]
    );
}); 

Route::resource('temas','TemaController',[ 
    'except'=>['create','edit']]
);

Route::group([ 
], function () { 
    Route::resource('unidadTema','UnidadTemaController',[ 
        'only'=>['index','show']]
    );
    Route::resource('temaUnidad','TemaUnidadController',[ 
        'only'=>['index','show']]
    );
}); 

Route::resource('actividads','ActividadController',[ 
    'except'=>['create','edit']]
);

Route::group([ 
], function () { 
    Route::resource('unidadActividad','UnidadActividadController',[ 
        'only'=>['index','show']]
    );
    Route::resource('actividadUnidad','ActividadUnidadController',[ 
        'only'=>['index','show']]
    );
}); 

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
