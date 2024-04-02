<?php

namespace App\Http\Controllers;

use App\Models\AvanceObra;
use App\Models\HistorialAccion;
use App\Models\Notificacion;
use App\Models\NotificacionUser;
use App\Models\User;
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
        "marcados" => "required|min:1",
    ];

    public $mensajes = [
        "obra_id.required" => "Este campo es obligatorio",
        "descripcion.required" => "Este campo es obligatorio",
        "descripcion.min" => "Debes ingresar al menos :min caracteres",
        "marcados.required" => "Este campo es obligatorio",
        "marcados.min" => "Debes seleccionar al menos :min avance",
    ];

    public function index()
    {
        return Inertia::render("AvanceObras/Index");
    }

    public function listado(Request $request)
    {
        $avance_obras = AvanceObra::with(["obra"])->select("avance_obras.*");

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

        $avance_obras = AvanceObra::with(["obra"])->select("avance_obras.*")
            ->join("obras", "obras.id", "=", "avance_obras.obra_id");

        if (trim($search) != "") {
            $avance_obras->where("obras.nombre", "LIKE", "%$search%");
        }


        if (Auth::user()->tipo == 'GERENTE REGIONAL') {
            $avance_obras = $avance_obras->where("obras.gerente_regional_id", Auth::user()->id);
        }

        if (Auth::user()->tipo == 'ENCARGADO DE OBRA') {
            $avance_obras = $avance_obras->where("obras.encargado_obra_id", Auth::user()->id);
        }

        $avance_obras = $avance_obras->orderBy("id", "desc")->paginate($request->itemsPerPage);
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
                'descripcion' => 'EL USUARIO ' . Auth::user()->avance_obra . ' REGISTRO UNA AVANCE DE OBRA',
                'datos_original' => $datos_original,
                'modulo' => 'AVANCE DE OBRAS',
                'fecha' => date('Y-m-d'),
                'hora' => date('H:i:s')
            ]);

            // REGISTRAR NOTIFICACION
            $nueva_notificacion = Notificacion::create([
                "registro_id" => $nuevo_avance_obra->id,
                "modelo" => "AvanceObra",
                "descripcion" => $nuevo_avance_obra->descripcion,
            ]);

            // USUARIOS
            $usuarios = User::whereIn("tipo", ["GERENTE GENERAL"])->get();
            foreach ($usuarios as $item) {
                $nueva_notificacion->notificacion_users()->create([
                    "user_id" => $item->id,
                    "visto" => 0,
                ]);
            }
            $usuarios = User::select("users.*")
                ->join("obras", "obras.gerente_regional_id", "=", "users.id")
                ->where("obras.gerente_regional_id", $nuevo_avance_obra->obra->gerente_regional_id)->get();

            foreach ($usuarios as $item) {
                $nueva_notificacion->notificacion_users()->create([
                    "user_id" => $item->id,
                    "visto" => 0,
                ]);
            }

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
                'descripcion' => 'EL USUARIO ' . Auth::user()->avance_obra . ' MODIFICÓ UNA AVANCE DE OBRA',
                'datos_original' => $datos_original,
                'datos_nuevo' => $datos_nuevo,
                'modulo' => 'AVANCE DE OBRAS',
                'fecha' => date('Y-m-d'),
                'hora' => date('H:i:s')
            ]);

            // ACTUALIZAR NOTIFICACION
            $notificacion = Notificacion::where("registro_id", $avance_obra->id)
                ->where("modelo", "AvanceObra")->get()->first();
            if ($notificacion) {
                $notificacion->descripcion = $avance_obra->descripcion;
                $notificacion->notificacion_users()->update([
                    "visto" => 0,
                ]);
                $notificacion->save();
            }

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
            // eliminar notifcaciones
            $notificacion = Notificacion::where("registro_id", $avance_obra->id)
                ->where("modelo", "AvanceObra")->get()->first();
            if ($notificacion) {
                $notificacion->notificacion_users()->delete();
                $notificacion->delete();
            }
            $datos_original = HistorialAccion::getDetalleRegistro($avance_obra, "avance_obras");
            $avance_obra->delete();
            HistorialAccion::create([
                'user_id' => Auth::user()->id,
                'accion' => 'ELIMINACIÓN',
                'descripcion' => 'EL USUARIO ' . Auth::user()->avance_obra . ' ELIMINÓ UNA AVANCE DE OBRA',
                'datos_original' => $datos_original,
                'modulo' => 'AVANCE DE OBRAS',
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
