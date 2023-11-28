<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/ayuda', [App\Http\Controllers\UserController::class, 'ayuda'])->name('ayuda');
Route::get('/ingresar', [App\Http\Controllers\UserController::class, 'ingresar'])->name('ingresar');
Route::POST('/ingresar/credenciales', [App\Http\Controllers\UserController::class, 'credenciales'])->name('credenciales');
Route::get('/registrarse', [App\Http\Controllers\UserController::class, 'registrarse'])->name('registrarse');
Route::get('/recuperar/usuario', [App\Http\Controllers\UserController::class, 'recuperar'])->name('recuperar');
Route::POST('/recuperar/usuario/credenciales', [App\Http\Controllers\UserController::class, 'recuperar_save'])->name('recuperar_save');
Route::GET('/usuario/salir', [App\Http\Controllers\UserController::class, 'logout'])->name('salir');

Route::GET('/dashboard', [App\Http\Controllers\PlataformaController::class, 'dashboard'])->name('dashboard');

Route::GET('/usuario/perfil', [App\Http\Controllers\PlataformaController::class, 'perfil'])->name('perfil');
Route::GET('/usuario/actividad/conexion', [App\Http\Controllers\PlataformaController::class, 'conexion'])->name('conexion');
Route::GET('/usuario/actividad/actualizacion', [App\Http\Controllers\PlataformaController::class, 'actualizacion'])->name('actualizacion');


Route::GET('/administrador/usuarios/registro', [App\Http\Controllers\PlataformaController::class, 'administrador_usuarios_registro'])->name('administrador_usuarios_registro');
Route::POST('/administrador/usuarios/registro/save', [App\Http\Controllers\PlataformaController::class, 'administrador_usuarios_registro_save'])->name('administrador_usuarios_registro_save');
Route::GET('/administrador/usuarios/bandeja', [App\Http\Controllers\PlataformaController::class, 'administrador_usuarios_bandeja'])->name('administrador_usuarios_bandeja');
Route::GET('/administrador/consulta/policial', [App\Http\Controllers\PlataformaController::class, 'administrador_consulta_policial'])->name('administrador_consulta_policial');

