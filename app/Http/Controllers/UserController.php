<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{


    public static $permisos = [
        "GERENTE GENERAL" => [
            "usuarios.index",
            "usuarios.create",
            "usuarios.edit",
            "usuarios.destroy",

            "institucions.index",
            "institucions.create",
            "institucions.edit",
            "institucions.destroy",

            "materials.index",
            "materials.create",
            "materials.edit",
            "materials.destroy",

            "operarios.index",
            "operarios.create",
            "operarios.edit",
            "operarios.destroy",

            "maquinarias.index",
            "maquinarias.create",
            "maquinarias.edit",
            "maquinarias.destroy",

            "categorias.index",
            "categorias.create",
            "categorias.edit",
            "categorias.destroy",

            "obras.index",
            "obras.create",
            "obras.edit",
            "obras.destroy",

            "presupuestos.index",
            "presupuestos.create",
            "presupuestos.edit",
            "presupuestos.destroy",
        ],
        "GERENTE REGIONAL" => [
            "vuetify",
        ],
        "ADMINISTRADOR DE PERSONAL" => [
            "vuetify",
        ],
        "ENCARGADO DE OBRA" => [
            "vuetify",
        ],
    ];

    public static function getPermisosUser()
    {
        $array_permisos = self::$permisos;
        if ($array_permisos[Auth::user()->tipo]) {
            return $array_permisos[Auth::user()->tipo];
        }
        return [];
    }


    public static function verificaPermiso($permiso)
    {
        if (in_array($permiso, self::$permisos[Auth::user()->tipo])) {
            return true;
        }
        return false;
    }

    public function permisos(Request $request)
    {
        return response()->JSON([
            "permisos" => $this->permisos[Auth::user()->tipo]
        ]);
    }

    public function getUser()
    {
        return response()->JSON([
            "user" => Auth::user()
        ]);
    }
}
