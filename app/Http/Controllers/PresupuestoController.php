<?php

namespace App\Http\Controllers;

use App\Models\HistorialAccion;
use App\Models\Presupuesto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;

class PresupuestoController extends Controller
{
    public $validacion = [
        "nombre" => "required|min:1",
        "gerente_regional_id" => "required",
        "encargado_presupuesto_id" => "required",
        "fecha_pent" => "required",
        "fecha_peje" => "required",
        "categoria_id" => "required",
    ];

    public $mensajes = [
        "nombre.required" => "Este campo es obligatorio",
        "nombre.min" => "Debes ingresar al menos :min caracteres",
        "gerente_regional_id.required" => "Este campo es obligatorio",
        "encargado_presupuesto_id.required" => "Este campo es obligatorio",
        "fecha_pent.required" => "Este campo es obligatorio",
        "fecha_peje.required" => "Este campo es obligatorio",
        "categoria_id.required" => "Este campo es obligatorio",
    ];

    public function index()
    {
        return Inertia::render("Presupuestos/Index");
    }

    public function listado()
    {
        $presupuestos = Presupuesto::with(["gerente_regional", "encargado_presupuesto", "categoria"])->get();
        return response()->JSON([
            "presupuestos" => $presupuestos
        ]);
    }

    public function paginado(Request $request)
    {

        $search = $request->search;

        $presupuestos = Presupuesto::with(["gerente_regional", "encargado_presupuesto", "categoria"])->select("presupuestos.*");

        if (trim($search) != "") {
            $presupuestos->where("presupuestos.nombre", "LIKE", "%$search%");
        }

        $presupuestos = $presupuestos->paginate($request->itemsPerPage);
        return response()->JSON([
            "presupuestos" => $presupuestos
        ]);
    }

    public function create()
    {
        return Inertia::render("Presupuestos/Create");
    }

    public function store(Request $request)
    {
        $request->validate($this->validacion, $this->mensajes);
        $request['fecha_registro'] = date('Y-m-d');
        DB::beginTransaction();
        try {
            // crear el Presupuesto
            $nuevo_presupuesto = Presupuesto::create(array_map('mb_strtoupper', $request->all()));
            $datos_original = HistorialAccion::getDetalleRegistro($nuevo_presupuesto, "presupuestos");
            HistorialAccion::create([
                'user_id' => Auth::user()->id,
                'accion' => 'CREACIÓN',
                'descripcion' => 'EL USUARIO ' . Auth::user()->presupuesto . ' REGISTRO UNA CATEGORIA',
                'datos_original' => $datos_original,
                'modulo' => 'CATEGORIAS',
                'fecha' => date('Y-m-d'),
                'hora' => date('H:i:s')
            ]);

            DB::commit();
            return redirect()->route("presupuestos.index")->with("bien", "Registro realizado");
        } catch (\Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'error' =>  $e->getMessage(),
            ]);
        }
    }

    public function show(Presupuesto $presupuesto)
    {
    }

    public function edit(Presupuesto $presupuesto)
    {
        return Inertia::render("Presupuestos/Edit", compact("presupuesto"));
    }

    public function update(Presupuesto $presupuesto, Request $request)
    {
        $request->validate($this->validacion, $this->mensajes);
        DB::beginTransaction();
        try {
            $datos_original = HistorialAccion::getDetalleRegistro($presupuesto, "presupuestos");
            $presupuesto->update(array_map('mb_strtoupper', $request->all()));

            $datos_nuevo = HistorialAccion::getDetalleRegistro($presupuesto, "presupuestos");
            HistorialAccion::create([
                'user_id' => Auth::user()->id,
                'accion' => 'MODIFICACIÓN',
                'descripcion' => 'EL USUARIO ' . Auth::user()->presupuesto . ' MODIFICÓ UNA CATEGORIA',
                'datos_original' => $datos_original,
                'datos_nuevo' => $datos_nuevo,
                'modulo' => 'CATEGORIAS',
                'fecha' => date('Y-m-d'),
                'hora' => date('H:i:s')
            ]);


            DB::commit();
            return redirect()->route("presupuestos.index")->with("bien", "Registro actualizado");
        } catch (\Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'error' =>  $e->getMessage(),
            ]);
        }
    }
    public function destroy(Presupuesto $presupuesto)
    {
        DB::beginTransaction();
        try {
            $datos_original = HistorialAccion::getDetalleRegistro($presupuesto, "presupuestos");
            $presupuesto->delete();
            HistorialAccion::create([
                'user_id' => Auth::user()->id,
                'accion' => 'ELIMINACIÓN',
                'descripcion' => 'EL USUARIO ' . Auth::user()->presupuesto . ' ELIMINÓ UNA CATEGORIA',
                'datos_original' => $datos_original,
                'modulo' => 'CATEGORIAS',
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
