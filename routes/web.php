<?php

use App\Http\Controllers\AvanceObraController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\InstitucionController;
use App\Http\Controllers\MaquinariaController;
use App\Http\Controllers\MaterialController;
use App\Http\Controllers\NotificacionController;
use App\Http\Controllers\ObraController;
use App\Http\Controllers\OperarioController;
use App\Http\Controllers\PresupuestoController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReporteController;
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
    Route::get("/obras/getAvances/{obra}", [ObraController::class, 'getAvances'])->name("obras.getAvances");
    Route::get("/obras/getObra/{obra}", [ObraController::class, 'getObra'])->name("obras.getObra");
    Route::get("/obras/paginado", [ObraController::class, 'paginado'])->name("obras.paginado");
    Route::get("/obras/listado", [ObraController::class, 'listado'])->name("obras.listado");
    Route::get("/obras/geolocalizacion", [ObraController::class, 'geolocalizacion'])->name("obras.geolocalizacion");
    Route::resource("obras", ObraController::class)->only(
        ["index", "create", "edit", "store", "update", "show", "destroy"]
    );

    // PRESUPUESTOS
    Route::get("/presupuestos/paginado", [PresupuestoController::class, 'paginado'])->name("presupuestos.paginado");
    Route::get("/presupuestos/listado", [PresupuestoController::class, 'listado'])->name("presupuestos.listado");
    Route::resource("presupuestos", PresupuestoController::class)->only(
        ["index", "create", "edit", "store", "update", "show", "destroy"]
    );

    // AVANCE OBRAS
    Route::get("/avance_obras/paginado", [AvanceObraController::class, 'paginado'])->name("avance_obras.paginado");
    Route::get("/avance_obras/listado", [AvanceObraController::class, 'listado'])->name("avance_obras.listado");
    Route::resource("avance_obras", AvanceObraController::class)->only(
        ["index", "store", "update", "show", "destroy"]
    );

    // NOTIFICACIONES
    Route::get("/notificacions/byUser", [NotificacionController::class, 'byUser'])->name("notificacions.byUser");
    Route::get("/notificacions/paginado", [NotificacionController::class, 'paginado'])->name("notificacions.paginado");
    Route::get("/notificacions/listado", [NotificacionController::class, 'listado'])->name("notificacions.listado");
    Route::get("/notificacions/show/{notificacion_user}", [NotificacionController::class, 'show'])->name("notificacions.show");
    Route::resource("notificacions", NotificacionController::class)->only(
        ["index"]
    );

    // REPORTES
    Route::get('reportes/usuarios', [ReporteController::class, 'usuarios'])->name("reportes.usuarios");
    Route::get('reportes/r_usuarios', [ReporteController::class, 'r_usuarios'])->name("reportes.r_usuarios");

    Route::get('reportes/presupuestos', [ReporteController::class, 'presupuestos'])->name("reportes.presupuestos");
    Route::get('reportes/r_presupuestos', [ReporteController::class, 'r_presupuestos'])->name("reportes.r_presupuestos");

    Route::get('reportes/operarios', [ReporteController::class, 'operarios'])->name("reportes.operarios");
    Route::get('reportes/r_operarios', [ReporteController::class, 'r_operarios'])->name("reportes.r_operarios");

    Route::get('reportes/obras', [ReporteController::class, 'obras'])->name("reportes.obras");
    Route::get('reportes/r_obras', [ReporteController::class, 'r_obras'])->name("reportes.r_obras");

    Route::get('reportes/avance_obras', [ReporteController::class, 'avance_obras'])->name("reportes.avance_obras");
    Route::get('reportes/r_avance_obras', [ReporteController::class, 'r_avance_obras'])->name("reportes.r_avance_obras");
    Route::get('reportes/g_avance_obras', [ReporteController::class, 'g_avance_obras'])->name("reportes.g_avance_obras");
});

require __DIR__ . '/auth.php';
