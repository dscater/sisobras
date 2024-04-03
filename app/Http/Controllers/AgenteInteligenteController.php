<?php

namespace App\Http\Controllers;

use App\Models\AvanceObra;
use App\Models\Notificacion;
use App\Models\NotificacionUser;
use App\NotificacionEstudiante;
use Illuminate\Support\Facades\Auth;

class AgenteInteligenteController extends Controller
{
    public function generarNotificaciones($inscripciones)
    {
        // Array para almacenar las notificaciones
        $notificaciones = array();

        // Generar notificaciones para cada estudiante inscrito
        $avance_obras = $this->detectarEventos();

        if (!empty($avance_obras)) {
            foreach ($avance_obras as $avance_obra) {
                // inicializar la notificacion
                $notificacion_generada = Notificacion::create([
                    "registro_id" => $avance_obra->id,
                    "modelo" => "AvanceObra",
                    "descripcion" => $avance_obra->descripcion,
                ]);

                $notificacion = $this->generarNotificacion($avance_obra, $notificacion_generada);
                if ($notificacion) {
                    $notificaciones[] = $notificacion;
                }
            }
        }
        return $notificaciones;
    }

    // Obtener eventos/avance_obras
    private function detectarEventos()
    {
        // segun la fecha actual
        $fecha_actual = date("Y-m-d");

        $avance_obras = AvanceObra::where("fecha_registro", $fecha_actual);

        if (Auth::user()->tipo == 'GERENTE REGIONAL') {
            // filtrar por usuario logeado
            $avance_obras = $avance_obras->where("gerente_regional_id", Auth::user()->id);
        }

        if (Auth::user()->tipo == 'ENCARGADO DE OBRA') {
            // filtrar por usuario logeado
            $avance_obras = $avance_obras->where("encargado_obra_id", Auth::user()->id);
        }

        $avance_obras = $avance_obras->get();

        return $avance_obras;
    }

    // Generar una notificación para un evento dado
    private function generarNotificacion(AvanceObra $avance_obra, Notificacion $notificacion)
    {
        // obtener los usuarios que recibiran la notificación
        $usuarios = User::whereIn("tipo", ["GERENTE GENERAL"])->get();
        foreach ($usuarios as $item) {
            $notificacion->notificacion_users()->create([
                "user_id" => $item->id,
                "visto" => 0,
            ]);
        }
        // usuario gerente regional de la obra
        $gerente_regional = $avance_obra->obra->gerente_regional;
        $notificacion->notificacion_users()->create([
            "user_id" => $gerente_regional->id,
            "visto" => 0,
        ]);

        return $notificacion;
    }
}
