<?php

namespace App\Http\Controllers;

use App\Models\Institucion;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class InstitucionController extends Controller
{
    public $validacion = [
        "razon_social" => "required|min:2",
        "nombre_sistema" => "required|min:2",
        "nit" => "required|min:2",
        "actividad" => "required|min:2",
        "dir" => "required|min:2",
    ];

    public $messages = [
        "razon_social.required" => "Este campo es obligatorio",
        "razon_social.min" => "Debes ingresar al menos :min caracteres",
        "nombre_sistema.required" => "Este campo es obligatorio",
        "nombre_sistema.min" => "Debes ingresar al menos :min caracteres",
        "nit.required" => "Este campo es obligatorio",
        "nit.min" => "Debes ingresar al menos :min caracteres",
        "actividad.required" => "Este campo es obligatorio",
        "actividad.min" => "Debes ingresar al menos :min caracteres",
        "dir.required" => "Este campo es obligatorio",
        "dir.min" => "Debes ingresar al menos :min caracteres",
    ];

    public function index(Request $request)
    {
        if (!UserController::verificaPermiso("institucions.index")) {
            abort(401, "No autorizado");
        }

        $institucion = Institucion::first();

        return Inertia::render("Institucions/Index", compact("institucion"));
    }

    public function getInstitucion()
    {
        $institucion = Institucion::first();
        return response()->JSON([
            "institucion" => $institucion
        ], 200);
    }

    public function update(Institucion $institucion, Request $request)
    {
        $request->validate($this->validacion, $this->messages);
        DB::beginTransaction();
        try {
            $institucion->update(array_map("mb_strtoupper", $request->except("logo")));
            if ($request->hasFile('logo')) {
                $antiguo = $institucion->logo;
                if ($antiguo && $antiguo != 'default.png') {
                    \File::delete(public_path() . '/imgs/' . $antiguo);
                }
                $file = $request->logo;
                $nom_logo = time() . '_' . $institucion->id . '.' . $file->getClientOriginalExtension();
                $institucion->logo = $nom_logo;
                $file->move(public_path() . '/imgs/', $nom_logo);
            }
            $institucion->save();

            DB::commit();
            return redirect()->route("institucions.index")->with("success", "Registro correcto");
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with("error", $e->getMessage());
        }
    }

    public function show(Institucion $institucion)
    {
    }
}
