<?php

namespace App\Http\Controllers;

use App\Models\HistorialAccion;
use App\Models\Material;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;

class MaterialController extends Controller
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
        return Inertia::render("Materials/Index");
    }

    public function listado()
    {
        $materials = Material::all();
        return response()->JSON([
            "materials" => $materials
        ]);
    }

    public function paginado(Request $request)
    {

        $search = $request->search;

        $materials = Material::select("materials.*");

        if (trim($search) != "") {
            $materials->where("nombre", "LIKE", "%$search%");
        }

        $materials = $materials->paginate($request->itemsPerPage);
        return response()->JSON([
            "materials" => $materials
        ]);
    }

    public function store(Request $request)
    {
        $request->validate($this->validacion, $this->mensajes);
        $request['fecha_registro'] = date('Y-m-d');
        DB::beginTransaction();
        try {
            // crear el Material
            $nuevo_material = Material::create(array_map('mb_strtoupper', $request->all()));
            $datos_original = HistorialAccion::getDetalleRegistro($nuevo_material, "materials");
            HistorialAccion::create([
                'user_id' => Auth::user()->id,
                'accion' => 'CREACIÓN',
                'descripcion' => 'EL USUARIO ' . Auth::user()->material . ' REGISTRO UN MATERIAL',
                'datos_original' => $datos_original,
                'modulo' => 'MATERIALES',
                'fecha' => date('Y-m-d'),
                'hora' => date('H:i:s')
            ]);

            DB::commit();
            return redirect()->route("materials.index")->with("bien", "Registro realizado");
        } catch (\Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'error' =>  $e->getMessage(),
            ]);
        }
    }

    public function show(Material $material)
    {
    }

    public function update(Material $material, Request $request)
    {
        $request->validate($this->validacion, $this->mensajes);
        DB::beginTransaction();
        try {
            $datos_original = HistorialAccion::getDetalleRegistro($material, "materials");
            $material->update(array_map('mb_strtoupper', $request->all()));

            $datos_nuevo = HistorialAccion::getDetalleRegistro($material, "materials");
            HistorialAccion::create([
                'user_id' => Auth::user()->id,
                'accion' => 'MODIFICACIÓN',
                'descripcion' => 'EL USUARIO ' . Auth::user()->material . ' MODIFICÓ UN MATERIAL',
                'datos_original' => $datos_original,
                'datos_nuevo' => $datos_nuevo,
                'modulo' => 'MATERIALES',
                'fecha' => date('Y-m-d'),
                'hora' => date('H:i:s')
            ]);


            DB::commit();
            return redirect()->route("materials.index")->with("bien", "Registro actualizado");
        } catch (\Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'error' =>  $e->getMessage(),
            ]);
        }
    }
    public function destroy(Material $material)
    {
        DB::beginTransaction();
        try {
            $datos_original = HistorialAccion::getDetalleRegistro($material, "materials");
            $material->delete();
            HistorialAccion::create([
                'user_id' => Auth::user()->id,
                'accion' => 'ELIMINACIÓN',
                'descripcion' => 'EL USUARIO ' . Auth::user()->material . ' ELIMINÓ UN MATERIAL',
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
