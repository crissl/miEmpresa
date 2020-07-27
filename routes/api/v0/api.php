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

//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});
Route::get('passwords', function () {
    $users = \App\User::orderBy('id')->get();
    $passwords = array();
    $users2 = array();
    foreach ($users as $user) {
        $passwords[] = \Illuminate\Support\Facades\Hash::make($user->identification);
        $users2[] = $user->first_lastname;
    }
    return response()->json(['users' => $users2, 'passwords' => $passwords]);
});

// Users
Route::group(['prefix' => 'auth'], function () {
    Route::post('login', 'v0\AuthController@login');
    Route::put('users', 'v0\AuthController@updateUser');
    Route::post('users/avatar', 'v0\AuthController@uploadAvatarUri');
    Route::group(['middleware' => 'auth:api'], function () {
        Route::get('logout', 'v0\AuthController@logout');
        Route::get('user', 'v0\AuthController@user');
    });
});

Route::group(['prefix' => 'docentes'], function () {
    Route::get('asistencia_laboral', 'v0\DocenteController@obtenerJornadaLaboralDiaria');
    Route::get('asistencia_laboral/todos', 'v0\DocenteController@obtenerJornadaLaboralTodos');
    Route::get('asistencia_laboral/reportes/jornadas', 'v0\DocenteController@exportarJornadasDocente');
    Route::post('asistencia_laboral', 'v0\DocenteController@inicarActividad');
    Route::put('asistencia_laboral', 'v0\DocenteController@actualizarActividad');
    Route::delete('asistencia_laboral', 'v0\DocenteController@eliminarActividad');
    Route::post('asistencia_laboral/finalizar', 'v0\DocenteController@finalizarActividad');
    Route::post('actividades', 'v0\DocenteController@crearActividad');
    Route::get('actividades', 'v0\DocenteController@obtenerActividades');
    Route::group(['middleware' => 'auth:api'], function () {

    });
});

Route::group(['prefix' => 'catalogues'], function () {
    Route::get('', 'v0\CatalogueController@filter');
    Route::group(['middleware' => 'auth:api'], function () {

    });
});

Route::group(['prefix' => 'workdays'], function () {
    Route::get('all', 'v0\WorkdayController@all');
    Route::get('current_day', 'v0\WorkdayController@getCurrenDate');

    Route::post('', 'v0\WorkdayController@store');
    Route::put('', 'v0\WorkdayController@update');
    Route::delete('{id}', 'v0\WorkdayController@destroy');
    Route::group(['middleware' => 'auth:api'], function () {

    });
});

Route::group(['prefix' => 'tasks'], function () {
    Route::get('all', 'v0\TaskController@all');
    Route::get('catalogues', 'v0\TaskController@allCatalogues');
    Route::get('current_day', 'v0\TaskController@getCurrenDate');
    Route::post('', 'v0\TaskController@store');
    Route::put('', 'v0\TaskController@update');
    Route::delete('{id}', 'v0\TaskController@destroy');
    Route::group(['middleware' => 'auth:api'], function () {

    });
});

Route::group(['prefix' => 'attendances'], function () {
    Route::get('all', 'v0\AttendanceController@all');
    Route::get('{id}', 'v0\AttendanceController@show');
    Route::post('', 'v0\AttendanceController@store');
    Route::put('', 'v0\AttendanceController@update');
    Route::delete('{id}', 'v0\AttendanceController@destroy');
    Route::group(['middleware' => 'auth:api'], function () {

    });
});
