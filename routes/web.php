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
Route::POST('/administrador/usuarios/edit/save', [App\Http\Controllers\PlataformaController::class, 'administrador_usuarios_edit_save'])->name('administrador_usuarios_edit_save');
Route::GET('/administrador/usuarios/bandeja', [App\Http\Controllers\PlataformaController::class, 'administrador_usuarios_bandeja'])->name('administrador_usuarios_bandeja');
Route::GET('/administrador/consulta/policial', [App\Http\Controllers\PlataformaController::class, 'administrador_consulta_policial'])->name('administrador_consulta_policial');


Route::GET('/administrador/reporte/usuarios', [App\Http\Controllers\PlataformaController::class, 'administrador_reporte_usuarios'])->name('administrador_reporte_usuarios');
Route::GET('/administrador/reporte/usuario', [App\Http\Controllers\PlataformaController::class, 'administrador_reporte_usuario'])->name('administrador_reporte_usuario');


Route::GET('entidades/policial', [App\Http\Controllers\PlataformaController::class, 'entidades_policial'])->name('entidades_policial');


Route::GET('basededatos/secundarias/delitos', [App\Http\Controllers\PlataformaController::class, 'basededatos_secundarias_delitos'])->name('basededatos_secundarias_delitos');
Route::GET('basededatos/secundarias/grados', [App\Http\Controllers\PlataformaController::class, 'basededatos_secundarias_grados'])->name('basededatos_secundarias_grados');
Route::GET('basededatos/secundarias/unidades', [App\Http\Controllers\PlataformaController::class, 'basededatos_secundarias_unidades'])->name('basededatos_secundarias_unidades');
Route::GET('basededatos/secundarias/perfiles', [App\Http\Controllers\PlataformaController::class, 'basededatos_secundarias_perfiles'])->name('basededatos_secundarias_perfiles');
Route::GET('basededatos/secundarias/plazos', [App\Http\Controllers\PlataformaController::class, 'basededatos_secundarias_plazos'])->name('basededatos_secundarias_plazos');
Route::POST('basededatos/secundarias/save', [App\Http\Controllers\PlataformaController::class, 'basededatos_secundarias_save'])->name('basededatos_secundarias_save');