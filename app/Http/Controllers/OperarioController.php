<?php

namespace App\Http\Controllers;

use App\Models\HistorialAccion;
use App\Models\Operario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;

class OperarioController extends Controller
{
    public $validacion = [
        "nombre" => "required|min:1",
        "tipo" => "required",
    ];

    public $mensajes = [
        "nombre.required" => "Este campo es obligatorio",
        "nombre.min" => "Debes ingresar al menos :min caracteres",
        "tipo.required" => "Este campo es obligatorio",
    ];

    public function index()
    {
        return Inertia::render("Operarios/Index");
    }

    public function listado()
    {
        $operarios = Operario::all();
        return response()->JSON([
            "operarios" => $operarios
        ]);
    }

    public function paginado(Request $request)
    {

        $search = $request->search;

        $operarios = Operario::select("operarios.*");

        if (trim($search) != "") {
            $operarios->where("nombre", "LIKE", "%$search%");
        }

        $operarios = $operarios->paginate($request->itemsPerPage);
        return response()->JSON([
            "operarios" => $operarios
        ]);
    }

    public function store(Request $request)
    {
        $request->validate($this->validacion, $this->mensajes);
        $request['fecha_registro'] = date('Y-m-d');
        DB::beginTransaction();
        try {
            // crear el Operario
            $nuevo_operario = Operario::create(array_map('mb_strtoupper', $request->all()));
            $datos_original = HistorialAccion::getDetalleRegistro($nuevo_operario, "operarios");
            HistorialAccion::create([
                'user_id' => Auth::user()->id,
                'accion' => 'CREACIÓN',
                'descripcion' => 'EL USUARIO ' . Auth::user()->operario . ' REGISTRO UN MATERIAL',
                'datos_original' => $datos_original,
                'modulo' => 'MATERIALES',
                'fecha' => date('Y-m-d'),
                'hora' => date('H:i:s')
            ]);

            DB::commit();
            return redirect()->route("operarios.index")->with("bien", "Registro realizado");
        } catch (\Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'error' =>  $e->getMessage(),
            ]);
        }
    }

    public function show(Operario $operario)
    {
    }

    public function update(Operario $operario, Request $request)
    {
        $request->validate($this->validacion, $this->mensajes);
        DB::beginTransaction();
        try {
            $datos_original = HistorialAccion::getDetalleRegistro($operario, "operarios");
            $operario->update(array_map('mb_strtoupper', $request->all()));

            $datos_nuevo = HistorialAccion::getDetalleRegistro($operario, "operarios");
            HistorialAccion::create([
                'user_id' => Auth::user()->id,
                'accion' => 'MODIFICACIÓN',
                'descripcion' => 'EL USUARIO ' . Auth::user()->operario . ' MODIFICÓ UN MATERIAL',
                'datos_original' => $datos_original,
                'datos_nuevo' => $datos_nuevo,
                'modulo' => 'MATERIALES',
                'fecha' => date('Y-m-d'),
                'hora' => date('H:i:s')
            ]);


            DB::commit();
            return redirect()->route("operarios.index")->with("bien", "Registro actualizado");
        } catch (\Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'error' =>  $e->getMessage(),
            ]);
        }
    }
    public function destroy(Operario $operario)
    {
        DB::beginTransaction();
        try {
            $datos_original = HistorialAccion::getDetalleRegistro($operario, "operarios");
            $operario->delete();
            HistorialAccion::create([
                'user_id' => Auth::user()->id,
                'accion' => 'ELIMINACIÓN',
                'descripcion' => 'EL USUARIO ' . Auth::user()->operario . ' ELIMINÓ UN MATERIAL',
                'datos_original' => $datos_original,
                'modulo' => 'MATERIALES',
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
            return response()->JSON([
                'sw' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
