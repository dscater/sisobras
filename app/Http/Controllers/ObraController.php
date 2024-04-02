<?php

namespace App\Http\Controllers;

use App\Models\AvanceObra;
use App\Models\HistorialAccion;
use App\Models\Obra;
use App\Models\Presupuesto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;

class ObraController extends Controller
{
    public $validacion = [
        "nombre" => "required|min:1",
        "gerente_regional_id" => "required",
        "encargado_obra_id" => "required",
        "fecha_pent" => "required",
        "fecha_peje" => "required",
        "categoria_id" => "required",
    ];

    public $mensajes = [
        "nombre.required" => "Este campo es obligatorio",
        "nombre.min" => "Debes ingresar al menos :min caracteres",
        "gerente_regional_id.required" => "Este campo es obligatorio",
        "encargado_obra_id.required" => "Este campo es obligatorio",
        "fecha_pent.required" => "Este campo es obligatorio",
        "fecha_peje.required" => "Este campo es obligatorio",
        "categoria_id.required" => "Este campo es obligatorio",
    ];

    public function index()
    {
        return Inertia::render("Obras/Index");
    }

    public function geolocalizacion()
    {
        return Inertia::render("Obras/Geolocalizacion");
    }

    public function listado(Request $request)
    {
        $obras = Obra::with(["gerente_regional", "encargado_obra", "categoria"]);

        if ($request->sin_presupuesto) {
            if ($request->id && $request->id != '') {
                $obras = $obras->whereNotExists(function ($query) {
                    $query->select(DB::raw(1))
                        ->from('presupuestos')
                        ->whereRaw('presupuestos.obra_id = obras.id');
                })->orWhere(function ($subquery) use ($request) {
                    $subquery->whereIn('obras.id', [$request->id]);
                });
            } else {
                $obras = $obras->whereNotExists(function ($query) {
                    $query->select(DB::raw(1))
                        ->from('presupuestos')
                        ->whereRaw('presupuestos.obra_id = obras.id');
                });
            }
        }

        if (Auth::user()->tipo == 'GERENTE REGIONAL') {
            $obras = $obras->where("gerente_regional_id", Auth::user()->id);
        }

        if (Auth::user()->tipo == 'ENCARGADO DE OBRA') {
            $obras = $obras->where("encargado_obra_id", Auth::user()->id);
        }

        if (isset($request->order)) {
            $obras = $obras->orderBy("id", $request->order);
        }

        $obras = $obras->get();
        return response()->JSON([
            "obras" => $obras
        ]);
    }

    public function getAvances(Obra $obra, Request $request)
    {
        $avance_obras = [];
        $avance_obra = null;
        $avance_marcados = [];
        if ($request->id && $request->id != '') {
            $avance_obras = AvanceObra::where("id", "!=", $request->id)->where("obra_id", $obra->id)->orderBy("id", "asc")->get();
            $avance_obra = AvanceObra::find($request->id);
            $avance_marcados = explode(",", $avance_obra->marcados);
        } else {
            $avance_obras = AvanceObra::where("obra_id", $obra->id)->orderBy("id", "asc")->get();
        }
        $array_avances = [];
        $ultimo_avance = 0;
        if (count($avance_obras) > 0) {
            $array_aux = [];
            foreach ($avance_obras as $item) {
                $array_marcados = explode(",", $item->marcados);
                $array_aux = array_merge($array_aux, $array_marcados);
            }
            $array_avances = $array_aux;
            $ultimo_avance = $avance_obras[count($avance_obras) - 1]->nro_progreso;
        }


        return response()->JSON([
            "obra" => $obra->load(["categoria"]),
            "array_avances" => $array_avances,
            "ultimo_avance" => $ultimo_avance,
            "avance_obra" => $avance_obra,
            "avance_marcados" => $avance_marcados,
        ]);
    }

    public function getObra(Obra $obra)
    {
        return response()->JSON([
            "obra" => $obra
        ]);
    }

    public function paginado(Request $request)
    {

        $search = $request->search;

        $obras = Obra::with(["gerente_regional", "encargado_obra", "categoria"])->select("obras.*");

        if (trim($search) != "") {
            $obras->where("obras.nombre", "LIKE", "%$search%");
        }

        if (Auth::user()->tipo == 'GERENTE REGIONAL') {
            $obras = $obras->where("gerente_regional_id", Auth::user()->id);
        }

        if (Auth::user()->tipo == 'ENCARGADO DE OBRA') {
            $obras = $obras->where("encargado_obra_id", Auth::user()->id);
        }

        $obras = $obras->paginate($request->itemsPerPage);
        return response()->JSON([
            "obras" => $obras
        ]);
    }

    public function create()
    {
        return Inertia::render("Obras/Create");
    }

    public function store(Request $request)
    {
        $request->validate($this->validacion, $this->mensajes);
        $request['fecha_registro'] = date('Y-m-d');
        DB::beginTransaction();
        try {
            // crear el Obra
            $nuevo_obra = Obra::create(array_map('mb_strtoupper', $request->all()));
            $datos_original = HistorialAccion::getDetalleRegistro($nuevo_obra, "obras");
            HistorialAccion::create([
                'user_id' => Auth::user()->id,
                'accion' => 'CREACIÓN',
                'descripcion' => 'EL USUARIO ' . Auth::user()->obra . ' REGISTRO UNA CATEGORIA',
                'datos_original' => $datos_original,
                'modulo' => 'CATEGORIAS',
                'fecha' => date('Y-m-d'),
                'hora' => date('H:i:s')
            ]);

            DB::commit();
            return redirect()->route("obras.index")->with("bien", "Registro realizado");
        } catch (\Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'error' =>  $e->getMessage(),
            ]);
        }
    }

    public function show(Obra $obra)
    {
    }

    public function edit(Obra $obra)
    {
        return Inertia::render("Obras/Edit", compact("obra"));
    }

    public function update(Obra $obra, Request $request)
    {
        $request->validate($this->validacion, $this->mensajes);
        DB::beginTransaction();
        try {
            $datos_original = HistorialAccion::getDetalleRegistro($obra, "obras");
            $obra->update(array_map('mb_strtoupper', $request->all()));

            $datos_nuevo = HistorialAccion::getDetalleRegistro($obra, "obras");
            HistorialAccion::create([
                'user_id' => Auth::user()->id,
                'accion' => 'MODIFICACIÓN',
                'descripcion' => 'EL USUARIO ' . Auth::user()->obra . ' MODIFICÓ UNA CATEGORIA',
                'datos_original' => $datos_original,
                'datos_nuevo' => $datos_nuevo,
                'modulo' => 'CATEGORIAS',
                'fecha' => date('Y-m-d'),
                'hora' => date('H:i:s')
            ]);


            DB::commit();
            return redirect()->route("obras.index")->with("bien", "Registro actualizado");
        } catch (\Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'error' =>  $e->getMessage(),
            ]);
        }
    }
    public function destroy(Obra $obra)
    {
        DB::beginTransaction();
        try {
            $usos = Presupuesto::where("obra_id", $obra->id)->get();
            if (count($usos) > 0) {
                throw ValidationException::withMessages([
                    'error' =>  "No es posible eliminar este registro porque esta siendo utilizado por otros registros",
                ]);
            }

            $datos_original = HistorialAccion::getDetalleRegistro($obra, "obras");
            $obra->delete();
            HistorialAccion::create([
                'user_id' => Auth::user()->id,
                'accion' => 'ELIMINACIÓN',
                'descripcion' => 'EL USUARIO ' . Auth::user()->obra . ' ELIMINÓ UNA CATEGORIA',
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
