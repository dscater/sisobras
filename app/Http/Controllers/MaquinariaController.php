<?php

namespace App\Http\Controllers;

use App\Models\HistorialAccion;
use App\Models\Maquinaria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;

class MaquinariaController extends Controller
{
    public $validacion = [
        "nombre" => "required|min:1",
    ];

    public $mensajes = [
        "nombre.required" => "Este campo es obligatorio",
        "nombre.min" => "Debes ingresar al menos :min caracteres",
    ];

    public function index()
    {
        return Inertia::render("Maquinarias/Index");
    }

    public function listado()
    {
        $maquinarias = Maquinaria::all();
        return response()->JSON([
            "maquinarias" => $maquinarias
        ]);
    }

    public function paginado(Request $request)
    {

        $search = $request->search;

        $maquinarias = Maquinaria::select("maquinarias.*");

        if (trim($search) != "") {
            $maquinarias->where("nombre", "LIKE", "%$search%");
        }

        $maquinarias = $maquinarias->paginate($request->itemsPerPage);
        return response()->JSON([
            "maquinarias" => $maquinarias
        ]);
    }

    public function store(Request $request)
    {
        $request->validate($this->validacion, $this->mensajes);
        $request['fecha_registro'] = date('Y-m-d');
        DB::beginTransaction();
        try {
            // crear el Maquinaria
            $nuevo_maquinaria = Maquinaria::create(array_map('mb_strtoupper', $request->all()));
            $datos_original = HistorialAccion::getDetalleRegistro($nuevo_maquinaria, "maquinarias");
            HistorialAccion::create([
                'user_id' => Auth::user()->id,
                'accion' => 'CREACIÓN',
                'descripcion' => 'EL USUARIO ' . Auth::user()->maquinaria . ' REGISTRO UNA MAQUINARIA',
                'datos_original' => $datos_original,
                'modulo' => 'MAQUINARIAS',
                'fecha' => date('Y-m-d'),
                'hora' => date('H:i:s')
            ]);

            DB::commit();
            return redirect()->route("maquinarias.index")->with("bien", "Registro realizado");
        } catch (\Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'error' =>  $e->getMessage(),
            ]);
        }
    }

    public function show(Maquinaria $maquinaria)
    {
    }

    public function update(Maquinaria $maquinaria, Request $request)
    {
        $request->validate($this->validacion, $this->mensajes);
        DB::beginTransaction();
        try {
            $datos_original = HistorialAccion::getDetalleRegistro($maquinaria, "maquinarias");
            $maquinaria->update(array_map('mb_strtoupper', $request->all()));

            $datos_nuevo = HistorialAccion::getDetalleRegistro($maquinaria, "maquinarias");
            HistorialAccion::create([
                'user_id' => Auth::user()->id,
                'accion' => 'MODIFICACIÓN',
                'descripcion' => 'EL USUARIO ' . Auth::user()->maquinaria . ' MODIFICÓ UNA MAQUINARIA',
                'datos_original' => $datos_original,
                'datos_nuevo' => $datos_nuevo,
                'modulo' => 'MAQUINARIAS',
                'fecha' => date('Y-m-d'),
                'hora' => date('H:i:s')
            ]);


            DB::commit();
            return redirect()->route("maquinarias.index")->with("bien", "Registro actualizado");
        } catch (\Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'error' =>  $e->getMessage(),
            ]);
        }
    }
    public function destroy(Maquinaria $maquinaria)
    {
        DB::beginTransaction();
        try {
            $datos_original = HistorialAccion::getDetalleRegistro($maquinaria, "maquinarias");
            $maquinaria->delete();
            HistorialAccion::create([
                'user_id' => Auth::user()->id,
                'accion' => 'ELIMINACIÓN',
                'descripcion' => 'EL USUARIO ' . Auth::user()->maquinaria . ' ELIMINÓ UNA MAQUINARIA',
                'datos_original' => $datos_original,
                'modulo' => 'MAQUINARIAS',
                'fecha' => date('Y-m-d'),
                'hora' => date('H:i:s')
            ]);
            DB::commit();
            return response()->JSON([
                'sw' => true,
                'message' => 'El registro se eliminó correctamente'
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'error' =>  $e->getMessage(),
            ]);
        }
    }
}
