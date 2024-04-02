<?php

namespace App\Http\Controllers;

use App\Models\NotificacionUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificacionController extends Controller
{
    public function byUser(Request $request)
    {
        $user = Auth::user();
        $list_notificacions = [];
        if ($user->tipo == "GERENTE GENERAL" || $user->tipo == "GERENTE REGIONAL") {
            $sin_ver = count(NotificacionUser::where("user_id", $user->id)->get());
            if ((int)$sin_ver > 0) {
                $list_notificacions = NotificacionUser::with(["notificacion.avance_obra.obra"])->where("user_id", $user->id)
                    ->orderBy("updated_at", "desc")
                    ->get();
                if (count($list_notificacions) > 0) {
                    $ultimo_sin_ver = $list_notificacions[count($list_notificacions) - 1]->id;
                }
            }
        }

        return response()->JSON([
            "sin_ver" => (int)$sin_ver,
            "list_notificacions" => $list_notificacions,
            "ultimo_sin_ver" => $ultimo_sin_ver,
        ]);
    }
}
