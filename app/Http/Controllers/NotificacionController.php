<?php

namespace App\Http\Controllers;

use App\Models\NotificacionUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class NotificacionController extends Controller
{
    public function byUser(Request $request)
    {
        $user = Auth::user();
        $list_notificacions = [];
        if ($user->tipo == "GERENTE GENERAL" || $user->tipo == "GERENTE REGIONAL") {
            $sin_ver = count(NotificacionUser::where("user_id", $user->id)->where("visto", 0)->get());
            $list_notificacions = NotificacionUser::with(["notificacion.avance_obra.obra"])->where("user_id", $user->id)
                ->orderBy("updated_at", "desc")
                ->get();
            if (count($list_notificacions) > 0) {
                $ultimo_sin_ver = $list_notificacions[count($list_notificacions) - 1]->id;
            }
        }

        return response()->JSON([
            "sin_ver" => (int)$sin_ver,
            "list_notificacions" => $list_notificacions,
            "ultimo_sin_ver" => $ultimo_sin_ver,
        ]);
    }

    public function index()
    {
        return Inertia::render("Notificacions/Index");
    }

    public function listado(Request $request)
    {
        $notificacions = NotificacionUser::with(["notificacion.avance_obra.obra"])->select("notificacion_users.*");

        if ($request->order && $request->order == "desc") {
            $notificacions->orderBy("notificacion_users.id", $request->order);
        }
        $notificacions->where("user_id", Auth::user()->id);
        $notificacions = $notificacions->get();

        return response()->JSON([
            "notificacions" => $notificacions
        ]);
    }

    public function paginado(Request $request)
    {

        $search = $request->search;

        $notificacions = NotificacionUser::with(["notificacion.avance_obra.obra"])->select("notificacion_users.*");

        if (trim($search) != "") {
            $notificacions->where("nombre", "LIKE", "%$search%");
        }

        $notificacions->where("user_id", Auth::user()->id);
        $notificacions->orderBy("notificacion_users.id", "desc");
        $notificacions = $notificacions->paginate($request->itemsPerPage);
        return response()->JSON([
            "notificacions" => $notificacions
        ]);
    }

    public function show(NotificacionUser $notificacion_user)
    {
        $notificacion_user->visto = 1;
        $notificacion_user->save();

        $notificacion_user = $notificacion_user->load(["notificacion.avance_obra.obra"]);
        return Inertia::render("Notificacions/Show", compact("notificacion_user"));
    }
}
