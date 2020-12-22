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


Route::group([
    'prefix' => 'auth'
    ], function () {
        Route::post('login', 'AuthController@login');
        Route::post('signup', 'AuthController@signup');

    Route::group(['middleware' => 'auth:api'], function() {
        Route::group([
            ], function() {
                Route::get('/logout', 'AuthController@logout');
                Route::get('/user', 'AuthController@user');
        });
     });
});

Route::group([
    ], function () { 
        Route::resource('users','UserController',[ 
            'except'=>['create','store','edit']]
        );
        Route::post('users_avatar', 'UserController@update_avatar');
        Route::get('user_showRole', 'UserController@showRole');
}); 


Route::resource('estudiantes','EstudianteController',[ 
    'except'=>['create','edit']]
);

Route::get('estudiantes_showOrden', 'EstudianteController@showOrden');

Route::get('estudiantes_showEstados', 'EstudianteController@showEstados');

Route::get('estudiantes_showEstudiantes', 'EstudianteController@showEstudiantes');

Route::get('estudiantes_showAnomaliasEstudiantes/{id}', 'EstudianteController@showAnomaliasEstudiante');

Route::get('estudiantes_showAnomaliasSubcategoriasEstudiantes/{id}', 'EstudianteController@showAnomaliasSubcategoriasEstudiante');

Route::resource('representantes','RepresentanteController',[ 
    'except'=>['create','edit']]
);

Route::resource('profesors','ProfesorController',[ 
    'except'=>['create','edit']]
);

Route::resource('cuerpo_deces','CuerpoDeceController',[ 
    'except'=>['create','edit']]
);


Route::resource('mensajes','MensajesController',[ 
    'except'=>['create','edit']]
);

Route::resource('estados','EstadoController',[ 
    'except'=>['create','edit']]
);

Route::resource('categorias','CategoriaController',[ 
    'except'=>['create','edit']]
); 


Route::resource('subcategorias','SubcategoriaController',[ 
    'except'=>['create','edit']]
);

Route::resource('tipo_asignaturas','TipoAsignaturaController',[ 
    'except'=>['create','edit']]
);

Route::resource('materias','MateriaController',[ 
    'except'=>['create','edit']]
);

Route::resource('cursos','CursoController',[ 
    'except'=>['create','edit']]
);

Route::get('cursosAllParaleloAll', 'MateriaProfesorController@showAllCursoParaleloAll');
Route::get('showEmisorReceptorAll/{id}/{id2}', 'MensajeUserController@showEmisorReceptorAll');

Route::resource('paralelos','ParaleloController',[ 
    'except'=>['create','edit']]
);

Route::resource('especialidades','EspecialidadController',[ 
    'except'=>['create','edit']]
);

Route::resource('periodo_lectivos','PeriodoLectivoController',[ 
    'except'=>['create','edit']]
);


Route::resource('matriculas','MatriculaController',[ 
    'except'=>['create','edit']]
);

Route::resource('cuerpo_deces.periodo_lectivos','DecesLectivosController',[ 
    'only'=>['store','destroy']]
);

Route::resource('materiasProfesor','MateriasProfesorsController',[ 
    'only'=>['store','destroy']]
);

Route::get('materiasProfesorAllCursos', 'MateriaProfesorController@showAllCursos');

Route::get('materiasProfesorAllCursosSmall', 'MateriaProfesorController@showAllCursosSmall');

Route::get('materiasProfesorAllCursoParalelo', 'MateriaProfesorController@showAllCursoParalelo');

Route::get('materiasProfesorAllLectivos', 'MateriaProfesorController@showAllLectivos');

Route::get('materiasProfesor_showAllAnomalia', 'MateriaprofesorAnomaliaController@showAllAnomalias');

Route::resource('anomalias','AnomaliaController',[ 
    'except'=>['create','edit']]
);

Route::resource('estudiantes.anomalias','ReporteEstudianteController',[ 
    'only'=>['store','destroy']]
);

Route::resource('anomalias.profesors','DiagnosticoTutorController',[ 
    'only'=>['store','destroy']]
);

Route::resource('estados.estudiantes','EstadosEstudiantesController',[ 
    'only'=>['store','destroy']]
);

Route::resource('userEstudiante','UserEstudianteController',[ 
    'only'=>['index','show']]
);

Route::resource('estudianteUser','EstudianteUserController',[ 
    'only'=>['index','show']]
);

Route::resource('representanteUser','RepresentanteUserController',[ 
    'only'=>['index','show']]
);

Route::resource('userRepresentante','UserRepresentanteController',[ 
    'only'=>['index','show']]
);

Route::resource('profesorUser','ProfesorUserController',[ 
    'only'=>['index','show']]
);

Route::resource('userProfesor','UserProfesorController',[ 
    'only'=>['index','show']]
);

Route::resource('deceUser','DeceUserController',[ 
    'only'=>['index','show']]
);

Route::resource('userDece','UserDeceController',[ 
    'only'=>['index','show']]
);


Route::resource('userMensaje','UserMensajeController',[ 
    'only'=>['index','show']]
);

Route::resource('mensajeUser','MensajeUserController',[ 
    'only'=>['index','show']]
);

Route::get('userMensaje/receptor/{id}', 'UserMensajeController@showReceptores');

Route::get('userMensajeAll/{id}', 'UserMensajeController@showEmisorReceptorAll');

Route::get('userMensaje_receptor_emisor', 'UserMensajeController@showEmisorReceptor');

Route::get('userMensaje_receptor_emisor', 'UserMensajeController@showEmisorReceptor');

Route::resource('categoriaSubcategoria','CategoriaSubcategoriasController',[ 
    'only'=>['index','show']]
);

Route::resource('subcategoriaCategoria','SubcategoriaCategoriaController',[ 
    'only'=>['index','show']]
);

Route::resource('subcategoriaAnomalia','SubcategoriaAnomaliaController',[ 
    'only'=>['index','show']]
);

Route::resource('anomaliaSubcategoria','AnomaliaSubcategoriaController',[ 
    'only'=>['index','show']]
);

Route::resource('asignaturaMateria','TipoAsignaturaMateriaController',[ 
    'only'=>['index','show']]
);

Route::resource('materiaAsignatura','MateriaTipoAsignaturaController',[ 
    'only'=>['index','show']]
);

Route::resource('profesorAnomalia','ProfesorAnomaliaController',[ 
    'only'=>['index','show']]
);

Route::resource('anomaliaProfesor','AnomaliaProfesorController',[ 
    'only'=>['index','show']]
);

Route::resource('decePeriodo','DecePeriodoController',[ 
    'only'=>['index','show']]
);

Route::resource('periodoDece','PeriodoDeceController',[ 
    'only'=>['index','show']]
);

Route::resource('profesorCurso','ProfesorCursoController',[ 
    'only'=>['index','show']]
);

Route::resource('cursoProfesor','CursoProfesorController',[ 
    'only'=>['index','show']]
);

Route::resource('cursoProfesor','CursoProfesorController',[ 
    'only'=>['index','show']]
);

Route::resource('profesorParalelo','ProfesorParaleloController',[ 
    'only'=>['index','show']]
);

Route::resource('paraleloProfesor','ParaleloProfesorController',[ 
    'only'=>['index','show']]
);

Route::resource('profesorEspecialidad','ProfesorEspecialidadController',[ 
    'only'=>['index','show']]
);

Route::resource('especialidadProfesor','EspecialidadProfesorController',[ 
    'only'=>['index','show']]
);

Route::resource('profesorPeriodo','ProfesorPeriodoController',[ 
    'only'=>['index','show']]
);

Route::resource('periodoProfesor','PeriodoProfesorController',[ 
    'only'=>['index','show']]
);

Route::resource('materiaprofesorAnomalia','MateriaprofesorAnomaliaController',[ 
    'only'=>['index','show']]
);

Route::resource('anomaliaMateriaprofesor','AnomaliaMateriaprofesorController',[ 
    'only'=>['index','show']]
);

Route::resource('estudianteAnomalia','EstudianteAnomaliaController',[ 
    'only'=>['index','show']]
);

Route::resource('anomaliaEstudiante','AnomaliaEstudianteController',[ 
    'only'=>['index','show']]
);

Route::resource('estadoEstudiante','EstadoEstudianteController',[ 
    'only'=>['index','show']]
);

Route::resource('estudianteEstado','EstudianteEstadoController',[ 
    'only'=>['index','show']]
);

Route::resource('estudianteMatricula','EstudianteMatriculaController',[ 
    'only'=>['index','show']]
);

Route::get('estudianteMatricula_showAll', 'EstudianteMatriculaController@showAll');

Route::resource('matriculaEstudiante','MatriculaEstudianteController',[ 
    'only'=>['index','show']]
);

Route::resource('periodoMatricula','PeriodoMatriculaController',[ 
    'only'=>['index','show']]
);

Route::resource('periodoCurso','PeriodoCursoController',[ 
    'only'=>['index','show']]
);

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
