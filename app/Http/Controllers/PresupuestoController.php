<?php

namespace App\Http\Controllers;

use App\Models\HistorialAccion;
use App\Models\Presupuesto;
use App\Models\PresupuestoMaquinaria;
use App\Models\PresupuestoMaterial;
use App\Models\PresupuestoOperario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;

class PresupuestoController extends Controller
{
    public $validacion = [
        "obra_id" => "required",
        "presupuesto" => "required|numeric|min:1",
    ];

    public $mensajes = [
        "obra_id.required" => "Este campo es obligatorio",
        "presupuesto.required" => "Este campo es obligatorio",
        "presupuesto.numeric" => "El campo debe ser un valor númerico",
        "presupuesto.min" => "El valor debe ser al menos :min",
    ];

    public function index()
    {
        return Inertia::render("Presupuestos/Index");
    }

    public function listado()
    {
        $presupuestos = Presupuesto::with(["obra", "materials.material", "operarios.operario", "maquinarias.maquinaria"])->get();
        return response()->JSON([
            "presupuestos" => $presupuestos
        ]);
    }

    public function paginado(Request $request)
    {

        $search = $request->search;

        $presupuestos = Presupuesto::with(["obra", "materials.material", "operarios.operario", "maquinarias.maquinaria"])->select("presupuestos.*");

        if (trim($search) != "") {
            $presupuestos->join("obras", "obras.id", "=", "presupuestos.obra_id");
            $presupuestos->where("obras.nombre", "LIKE", "%$search%");
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
        if ((float)$request["total"] <= 0) {
            throw ValidationException::withMessages([
                "error" => "El Presupuesto usado debe ser mayor a 0"
            ]);
        }

        if ((float)$request["total"] > (float)$request["presupuesto"]) {
            throw ValidationException::withMessages([
                "error" => "El Presupuesto usado " . $request['total'] . " no puede ser mayor al presupuesto " . $request['presupuesto']
            ]);
        }

        $request['fecha_registro'] = date('Y-m-d');
        DB::beginTransaction();
        try {
            // crear el Presupuesto
            $nuevo_presupuesto = Presupuesto::create(array_map('mb_strtoupper', $request->except("materials", "operarios", "maquinarias", "eliminados_materials", "eliminados_operarios", "eliminados_maquinarias")));

            if (isset($request["materials"])) {
                $materials = $request->materials;
                foreach ($materials as $m) {
                    $nuevo_presupuesto->materials()->create([
                        "material_id" => $m["material_id"],
                        "precio" => $m["precio"],
                        "cantidad" => $m["cantidad"],
                        "subtotal" => $m["subtotal"],
                    ]);
                }
            }

            if (isset($request["operarios"])) {
                $operarios = $request->operarios;
                foreach ($operarios as $m) {
                    $nuevo_presupuesto->operarios()->create([
                        "operario_id" => $m["operario_id"],
                        "precio" => $m["precio"],
                        "cantidad" => $m["cantidad"],
                        "subtotal" => $m["subtotal"],
                    ]);
                }
            }

            if (isset($request["maquinarias"])) {
                $maquinarias = $request->maquinarias;
                foreach ($maquinarias as $m) {
                    $nuevo_presupuesto->maquinarias()->create([
                        "maquinaria_id" => $m["maquinaria_id"],
                        "precio" => $m["precio"],
                        "cantidad" => $m["cantidad"],
                        "subtotal" => $m["subtotal"],
                    ]);
                }
            }

            $datos_original = HistorialAccion::getDetalleRegistro($nuevo_presupuesto, "presupuestos");
            HistorialAccion::create([
                'user_id' => Auth::user()->id,
                'accion' => 'CREACIÓN',
                'descripcion' => 'EL USUARIO ' . Auth::user()->presupuesto . ' REGISTRO UN PRESUPUESTO',
                'datos_original' => $datos_original,
                'modulo' => 'PRESUPUESTOS',
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
        $presupuesto = $presupuesto->load(["materials.material", "operarios.operario", "maquinarias.maquinaria"]);

        return Inertia::render("Presupuestos/Edit", compact("presupuesto"));
    }

    public function update(Presupuesto $presupuesto, Request $request)
    {
        $request->validate($this->validacion, $this->mensajes);
        DB::beginTransaction();
        try {
            $datos_original = HistorialAccion::getDetalleRegistro($presupuesto, "presupuestos");
            $presupuesto->update(array_map('mb_strtoupper', $request->except("materials", "operarios", "maquinarias", "eliminados_materials", "eliminados_operarios", "eliminados_maquinarias")));

            if (isset($request["eliminados_materials"])) {
                $eliminados_materials = $request->eliminados_materials;
                foreach ($eliminados_materials as $e) {
                    $pm = PresupuestoMaterial::find($e);
                    $pm->delete();
                }
            }
            if (isset($request["eliminados_operarios"])) {
                $eliminados_operarios = $request->eliminados_operarios;
                foreach ($eliminados_operarios as $e) {
                    $po = PresupuestoOperario::find($e);
                    $po->delete();
                }
            }
            if (isset($request["eliminados_maquinarias"])) {
                $eliminados_maquinarias = $request->eliminados_maquinarias;
                foreach ($eliminados_maquinarias as $e) {
                    $pm = PresupuestoMaquinaria::find($e);
                    $pm->delete();
                }
            }

            if (isset($request["materials"])) {
                $materials = $request->materials;
                foreach ($materials as $m) {
                    if ($m["id"] == 0)
                        $presupuesto->materials()->create([
                            "material_id" => $m["material_id"],
                            "precio" => $m["precio"],
                            "cantidad" => $m["cantidad"],
                            "subtotal" => $m["subtotal"],
                        ]);
                }
            }

            if (isset($request["operarios"])) {
                $operarios = $request->operarios;
                foreach ($operarios as $o) {
                    if ($o["id"] == 0)
                        $presupuesto->operarios()->create([
                            "operario_id" => $o["operario_id"],
                            "precio" => $o["precio"],
                            "cantidad" => $o["cantidad"],
                            "subtotal" => $o["subtotal"],
                        ]);
                }
            }

            if (isset($request["maquinarias"])) {
                $maquinarias = $request->maquinarias;
                foreach ($maquinarias as $m) {
                    if ($m["id"] == 0)
                        $presupuesto->maquinarias()->create([
                            "maquinaria_id" => $m["maquinaria_id"],
                            "precio" => $m["precio"],
                            "cantidad" => $m["cantidad"],
                            "subtotal" => $m["subtotal"],
                        ]);
                }
            }

            $datos_nuevo = HistorialAccion::getDetalleRegistro($presupuesto, "presupuestos");
            HistorialAccion::create([
                'user_id' => Auth::user()->id,
                'accion' => 'MODIFICACIÓN',
                'descripcion' => 'EL USUARIO ' . Auth::user()->presupuesto . ' MODIFICÓ UN PRESUPUESTO',
                'datos_original' => $datos_original,
                'datos_nuevo' => $datos_nuevo,
                'modulo' => 'PRESUPUESTOS',
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
                'descripcion' => 'EL USUARIO ' . Auth::user()->presupuesto . ' ELIMINÓ UN PRESUPUESTO',
                'datos_original' => $datos_original,
                'modulo' => 'PRESUPUESTOS',
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
