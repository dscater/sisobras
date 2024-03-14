<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use App\Models\HistorialAccion;
use App\Models\Obra;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;

class CategoriaController extends Controller
{
    public $validacion = [
        "nombre" => "required|min:1",
        "nro_avances" => "required|numeric|min:1",
    ];

    public $mensajes = [
        "nombre.required" => "Este campo es obligatorio",
        "nombre.min" => "Debes ingresar al menos :min caracteres",
        "nro_avances.required" => "Este campo es obligatorio",
        "nro_avances.numeric" => "Debes ingresar un valor númerico",
        "nro_avances.min" => "Debes ingresar al menos :min",
    ];

    public function index()
    {
        return Inertia::render("Categorias/Index");
    }

    public function listado(Request $request)
    {
        $categorias = Categoria::select("categorias.*");

        if ($request->order && $request->order == "desc") {
            $categorias->orderBy("categorias.id", $request->order);
        }

        $categorias = $categorias->get();

        return response()->JSON([
            "categorias" => $categorias
        ]);
    }

    public function paginado(Request $request)
    {

        $search = $request->search;

        $categorias = Categoria::select("categorias.*");

        if (trim($search) != "") {
            $categorias->where("nombre", "LIKE", "%$search%");
        }

        $categorias = $categorias->paginate($request->itemsPerPage);
        return response()->JSON([
            "categorias" => $categorias
        ]);
    }

    public function store(Request $request)
    {
        $request->validate($this->validacion, $this->mensajes);
        $request['fecha_registro'] = date('Y-m-d');
        DB::beginTransaction();
        try {
            // crear el Categoria
            $nuevo_categoria = Categoria::create(array_map('mb_strtoupper', $request->all()));
            $datos_original = HistorialAccion::getDetalleRegistro($nuevo_categoria, "categorias");
            HistorialAccion::create([
                'user_id' => Auth::user()->id,
                'accion' => 'CREACIÓN',
                'descripcion' => 'EL USUARIO ' . Auth::user()->categoria . ' REGISTRO UNA CATEGORIA',
                'datos_original' => $datos_original,
                'modulo' => 'CATEGORIAS',
                'fecha' => date('Y-m-d'),
                'hora' => date('H:i:s')
            ]);

            DB::commit();
            return redirect()->route("categorias.index")->with("bien", "Registro realizado");
        } catch (\Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'error' =>  $e->getMessage(),
            ]);
        }
    }

    public function show(Categoria $categoria)
    {
    }

    public function update(Categoria $categoria, Request $request)
    {
        $request->validate($this->validacion, $this->mensajes);
        DB::beginTransaction();
        try {
            $datos_original = HistorialAccion::getDetalleRegistro($categoria, "categorias");
            $categoria->update(array_map('mb_strtoupper', $request->all()));

            $datos_nuevo = HistorialAccion::getDetalleRegistro($categoria, "categorias");
            HistorialAccion::create([
                'user_id' => Auth::user()->id,
                'accion' => 'MODIFICACIÓN',
                'descripcion' => 'EL USUARIO ' . Auth::user()->categoria . ' MODIFICÓ UNA CATEGORIA',
                'datos_original' => $datos_original,
                'datos_nuevo' => $datos_nuevo,
                'modulo' => 'CATEGORIAS',
                'fecha' => date('Y-m-d'),
                'hora' => date('H:i:s')
            ]);


            DB::commit();
            return redirect()->route("categorias.index")->with("bien", "Registro actualizado");
        } catch (\Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'error' =>  $e->getMessage(),
            ]);
        }
    }
    public function destroy(Categoria $categoria)
    {
        DB::beginTransaction();
        try {
            $usos = Obra::where("categoria_id", $categoria->id)->get();
            if (count($usos) > 0) {
                throw ValidationException::withMessages([
                    'error' =>  "No es posible eliminar esta categoría porque esta siendo utilizada por otros registros",
                ]);
            }

            $datos_original = HistorialAccion::getDetalleRegistro($categoria, "categorias");
            $categoria->delete();
            HistorialAccion::create([
                'user_id' => Auth::user()->id,
                'accion' => 'ELIMINACIÓN',
                'descripcion' => 'EL USUARIO ' . Auth::user()->categoria . ' ELIMINÓ UNA CATEGORIA',
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
