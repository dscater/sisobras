<?php

use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\InstitucionController;
use App\Http\Controllers\MaquinariaController;
use App\Http\Controllers\MaterialController;
use App\Http\Controllers\ObraController;
use App\Http\Controllers\OperarioController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UsuarioController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     return Inertia::render('Welcome', [
//         'canLogin' => Route::has('login'),
//         'canRegister' => Route::has('register'),
//         'laravelVersion' => Application::VERSION,
//         'phpVersion' => PHP_VERSION,
//     ]);
// });

Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('inicio');
    }
    return Inertia::render('Auth/Login');
});

Route::get("institucions/getInstitucion", [InstitucionController::class, 'getInstitucion'])->name("institucions.getInstitucion");

Route::middleware('auth')->group(function () {
    // BORRAR
    Route::get('/vuetify', function () {
        return Inertia::render('Vuetify/Index');
    })->name("vuetify");

    // INICIO
    Route::get('/inicio', function () {
        return Inertia::render('Home');
    })->name('inicio');

    // INSTITUCION
    Route::resource("institucions", InstitucionController::class)->only(
        ["index", "show", "update"]
    );

    // USUARIO
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::patch('/profile/update_foto', [ProfileController::class, 'update_foto'])->name('profile.update_foto');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get("/getUser", [UserController::class, 'getUser'])->name('users.getUser');
    Route::get("/permisos", [UserController::class, 'permisos']);
    Route::get("/menu_user", [UserController::class, 'permisos']);

    // USUARIOS
    Route::put("/usuarios/password/{user}", [UsuarioController::class, 'actualizaPassword'])->name("usuarios.password");
    Route::get("/usuarios/paginado", [UsuarioController::class, 'paginado'])->name("usuarios.paginado");
    Route::get("/usuarios/listado", [UsuarioController::class, 'listado'])->name("usuarios.listado");
    Route::get("/usuarios/listado/byTipo", [UsuarioController::class, 'byTipo'])->name("usuarios.byTipo");
    Route::get("/usuarios/show/{user}", [UsuarioController::class, 'show'])->name("usuarios.show");
    Route::put("/usuarios/update/{user}", [UsuarioController::class, 'update'])->name("usuarios.update");
    Route::delete("/usuarios/{user}", [UsuarioController::class, 'destroy'])->name("usuarios.destroy");
    Route::resource("usuarios", UsuarioController::class)->only(
        ["index", "store"]
    );

    // MATERIALES
    Route::get("/materials/paginado", [MaterialController::class, 'paginado'])->name("materials.paginado");
    Route::get("/materials/listado", [MaterialController::class, 'listado'])->name("materials.listado");
    Route::resource("materials", MaterialController::class)->only(
        ["index", "store", "update", "show", "destroy"]
    );

    // OPERARIOS
    Route::get("/operarios/paginado", [OperarioController::class, 'paginado'])->name("operarios.paginado");
    Route::get("/operarios/listado", [OperarioController::class, 'listado'])->name("operarios.listado");
    Route::resource("operarios", OperarioController::class)->only(
        ["index", "store", "update", "show", "destroy"]
    );

    // MAQUINARIAS
    Route::get("/maquinarias/paginado", [MaquinariaController::class, 'paginado'])->name("maquinarias.paginado");
    Route::get("/maquinarias/listado", [MaquinariaController::class, 'listado'])->name("maquinarias.listado");
    Route::resource("maquinarias", MaquinariaController::class)->only(
        ["index", "store", "update", "show", "destroy"]
    );

    // CATEGORIAS
    Route::get("/categorias/paginado", [CategoriaController::class, 'paginado'])->name("categorias.paginado");
    Route::get("/categorias/listado", [CategoriaController::class, 'listado'])->name("categorias.listado");
    Route::resource("categorias", CategoriaController::class)->only(
        ["index", "store", "update", "show", "destroy"]
    );

    // OBRAS
    Route::get("/obras/paginado", [ObraController::class, 'paginado'])->name("obras.paginado");
    Route::get("/obras/listado", [ObraController::class, 'listado'])->name("obras.listado");
    Route::resource("obras", ObraController::class)->only(
        ["index", "create", "edit", "store", "update", "show", "destroy"]
    );
});

require __DIR__ . '/auth.php';
