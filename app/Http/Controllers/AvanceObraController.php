<?php

namespace App\Http\Controllers;

use App\Models\AvanceObra;
use App\Models\HistorialAccion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;

class AvanceObraController extends Controller
{
    public $validacion = [
        "obra_id" => "required",
        "descripcion" => "required|min:1",
    ];

    public $mensajes = [
        "obra_id.required" => "Este campo es obligatorio",
        "descripcion.required" => "Este campo es obligatorio",
        "descripcion.min" => "Debes ingresar al menos :min caracteres",
    ];

    public function index()
    {
        return Inertia::render("AvanceObras/Index");
    }

    public function listado(Request $request)
    {
        $avance_obras = AvanceObra::select("avance_obras.*");

        if ($request->order && $request->order == "desc") {
            $avance_obras->orderBy("avance_obras.id", $request->order);
        }

        $avance_obras = $avance_obras->get();

        return response()->JSON([
            "avance_obras" => $avance_obras
        ]);
    }

    public function paginado(Request $request)
    {

        $search = $request->search;

        $avance_obras = AvanceObra::select("avance_obras.*");

        if (trim($search) != "") {
            $avance_obras->where("nombre", "LIKE", "%$search%");
        }

        $avance_obras = $avance_obras->paginate($request->itemsPerPage);
        return response()->JSON([
            "avance_obras" => $avance_obras
        ]);
    }

    public function store(Request $request)
    {
        $request->validate($this->validacion, $this->mensajes);
        $request['fecha_registro'] = date('Y-m-d');
        DB::beginTransaction();
        try {
            // crear el AvanceObra
            $nuevo_avance_obra = AvanceObra::create(array_map('mb_strtoupper', $request->all()));
            $datos_original = HistorialAccion::getDetalleRegistro($nuevo_avance_obra, "avance_obras");
            HistorialAccion::create([
                'user_id' => Auth::user()->id,
                'accion' => 'CREACIÓN',
                'descripcion' => 'EL USUARIO ' . Auth::user()->avance_obra . ' REGISTRO UNA CATEGORIA',
                'datos_original' => $datos_original,
                'modulo' => 'CATEGORIAS',
                'fecha' => date('Y-m-d'),
                'hora' => date('H:i:s')
            ]);

            DB::commit();
            return redirect()->route("avance_obras.index")->with("bien", "Registro realizado");
        } catch (\Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'error' =>  $e->getMessage(),
            ]);
        }
    }

    public function show(AvanceObra $avance_obra)
    {
    }

    public function update(AvanceObra $avance_obra, Request $request)
    {
        $request->validate($this->validacion, $this->mensajes);
        DB::beginTransaction();
        try {
            $datos_original = HistorialAccion::getDetalleRegistro($avance_obra, "avance_obras");
            $avance_obra->update(array_map('mb_strtoupper', $request->all()));

            $datos_nuevo = HistorialAccion::getDetalleRegistro($avance_obra, "avance_obras");
            HistorialAccion::create([
                'user_id' => Auth::user()->id,
                'accion' => 'MODIFICACIÓN',
                'descripcion' => 'EL USUARIO ' . Auth::user()->avance_obra . ' MODIFICÓ UNA CATEGORIA',
                'datos_original' => $datos_original,
                'datos_nuevo' => $datos_nuevo,
                'modulo' => 'CATEGORIAS',
                'fecha' => date('Y-m-d'),
                'hora' => date('H:i:s')
            ]);


            DB::commit();
            return redirect()->route("avance_obras.index")->with("bien", "Registro actualizado");
        } catch (\Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'error' =>  $e->getMessage(),
            ]);
        }
    }
    public function destroy(AvanceObra $avance_obra)
    {
        DB::beginTransaction();
        try {
            $datos_original = HistorialAccion::getDetalleRegistro($avance_obra, "avance_obras");
            $avance_obra->delete();
            HistorialAccion::create([
                'user_id' => Auth::user()->id,
                'accion' => 'ELIMINACIÓN',
                'descripcion' => 'EL USUARIO ' . Auth::user()->avance_obra . ' ELIMINÓ UNA CATEGORIA',
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
