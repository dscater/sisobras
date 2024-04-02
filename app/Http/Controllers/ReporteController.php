<?php

namespace App\Http\Controllers;

use App\Models\Obra;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use PDF;

class ReporteController extends Controller
{
    public function usuarios()
    {
        return Inertia::render("Reportes/Usuarios");
    }

    public function r_usuarios(Request $request)
    {
        $tipo =  $request->tipo;
        $usuarios = User::where('id', '!=', 1)->orderBy("paterno", "ASC")->get();

        if ($tipo != 'TODOS') {
            $request->validate([
                'tipo' => 'required',
            ]);
            $usuarios = User::where('id', '!=', 1)->where('tipo', $request->tipo)->orderBy("paterno", "ASC")->get();
        }

        $pdf = PDF::loadView('reportes.usuarios', compact('usuarios'))->setPaper('legal', 'landscape');

        // ENUMERAR LAS PÁGINAS USANDO CANVAS
        $pdf->output();
        $dom_pdf = $pdf->getDomPDF();
        $canvas = $dom_pdf->get_canvas();
        $alto = $canvas->get_height();
        $ancho = $canvas->get_width();
        $canvas->page_text($ancho - 90, $alto - 25, "Página {PAGE_NUM} de {PAGE_COUNT}", null, 9, array(0, 0, 0));

        return $pdf->stream('usuarios.pdf');
    }

    public function presupuestos()
    {
        return Inertia::render("Reportes/Presupuestos");
    }

    public function r_presupuestos(Request $request)
    {
        $obra_id =  $request->obra_id;
        if (Auth::user()->tipo == 'GERENTE REGIONAL' || Auth::user()->tipo == 'ENCARGADO DE OBRA') {
            if (Auth::user()->tipo == 'GERENTE REGIONAL') {
                $obras = Obra::where("gerente_regional_id", Auth::user()->id)->get();
            } else {
                $obras = Obra::where("encargado_obra_id", Auth::user()->id)->get();
            }
        } else {
            $obras = Obra::all();
        }

        if ($obra_id != 'TODOS') {
            $request->validate([
                'obra_id' => 'required',
            ]);
            $obras = Obra::where('id', 1)->get();
        }

        $pdf = PDF::loadView('reportes.presupuestos', compact('obras'))->setPaper('letter', 'portrait');

        // ENUMERAR LAS PÁGINAS USANDO CANVAS
        $pdf->output();
        $dom_pdf = $pdf->getDomPDF();
        $canvas = $dom_pdf->get_canvas();
        $alto = $canvas->get_height();
        $ancho = $canvas->get_width();
        $canvas->page_text($ancho - 90, $alto - 25, "Página {PAGE_NUM} de {PAGE_COUNT}", null, 9, array(0, 0, 0));

        return $pdf->stream('presupuestos.pdf');
    }

    public function operarios()
    {
        return Inertia::render("Reportes/Operarios");
    }

    public function r_operarios(Request $request)
    {
        $obra_id =  $request->obra_id;
        if (Auth::user()->tipo == 'GERENTE REGIONAL' || Auth::user()->tipo == 'ENCARGADO DE OBRA') {
            if (Auth::user()->tipo == 'GERENTE REGIONAL') {
                $obras = Obra::where("gerente_regional_id", Auth::user()->id)->get();
            } else {
                $obras = Obra::where("encargado_obra_id", Auth::user()->id)->get();
            }
        } else {
            $obras = Obra::all();
        }

        if ($obra_id != 'TODOS') {
            $request->validate([
                'obra_id' => 'required',
            ]);
            $obras = Obra::where('id', 1)->get();
        }

        $pdf = PDF::loadView('reportes.operarios', compact('obras'))->setPaper('letter', 'portrait');

        // ENUMERAR LAS PÁGINAS USANDO CANVAS
        $pdf->output();
        $dom_pdf = $pdf->getDomPDF();
        $canvas = $dom_pdf->get_canvas();
        $alto = $canvas->get_height();
        $ancho = $canvas->get_width();
        $canvas->page_text($ancho - 90, $alto - 25, "Página {PAGE_NUM} de {PAGE_COUNT}", null, 9, array(0, 0, 0));

        return $pdf->stream('operarios.pdf');
    }

    public function obras()
    {
        return Inertia::render("Reportes/Obras");
    }

    public function r_obras(Request $request)
    {
        $obra_id =  $request->obra_id;
        if (Auth::user()->tipo == 'GERENTE REGIONAL' || Auth::user()->tipo == 'ENCARGADO DE OBRA') {
            if (Auth::user()->tipo == 'GERENTE REGIONAL') {
                $obras = Obra::where("gerente_regional_id", Auth::user()->id)->get();
            } else {
                $obras = Obra::where("encargado_obra_id", Auth::user()->id)->get();
            }
        } else {
            $obras = Obra::all();
        }

        if ($obra_id != 'TODOS') {
            $request->validate([
                'obra_id' => 'required',
            ]);
            $obras = Obra::where('id', 1)->get();
        }

        $pdf = PDF::loadView('reportes.obras', compact('obras'))->setPaper('letter', 'landscape');

        // ENUMERAR LAS PÁGINAS USANDO CANVAS
        $pdf->output();
        $dom_pdf = $pdf->getDomPDF();
        $canvas = $dom_pdf->get_canvas();
        $alto = $canvas->get_height();
        $ancho = $canvas->get_width();
        $canvas->page_text($ancho - 90, $alto - 25, "Página {PAGE_NUM} de {PAGE_COUNT}", null, 9, array(0, 0, 0));

        return $pdf->stream('obras.pdf');
    }

    public function avance_obras()
    {
        return Inertia::render("Reportes/AvanceObras");
    }

    public function r_avance_obras(Request $request)
    {
        $obra_id =  $request->obra_id;
        if (Auth::user()->tipo == 'GERENTE REGIONAL' || Auth::user()->tipo == 'ENCARGADO DE OBRA') {
            if (Auth::user()->tipo == 'GERENTE REGIONAL') {
                $obras = Obra::where("gerente_regional_id", Auth::user()->id)->get();
            } else {
                $obras = Obra::where("encargado_obra_id", Auth::user()->id)->get();
            }
        } else {
            $obras = Obra::all();
        }

        if ($obra_id != 'TODOS') {
            $request->validate([
                'obra_id' => 'required',
            ]);
            $obras = Obra::where('id', 1)->get();
        }

        $pdf = PDF::loadView('reportes.avance_obras', compact('obras'))->setPaper('letter', 'portrait');

        // ENUMERAR LAS PÁGINAS USANDO CANVAS
        $pdf->output();
        $dom_pdf = $pdf->getDomPDF();
        $canvas = $dom_pdf->get_canvas();
        $alto = $canvas->get_height();
        $ancho = $canvas->get_width();
        $canvas->page_text($ancho - 90, $alto - 25, "Página {PAGE_NUM} de {PAGE_COUNT}", null, 9, array(0, 0, 0));

        return $pdf->stream('avance_obras.pdf');
    }

    public function g_avance_obras(Request $request)
    {
        $obra_id =  $request->obra_id;
        if (Auth::user()->tipo == 'GERENTE REGIONAL' || Auth::user()->tipo == 'ENCARGADO DE OBRA') {
            if (Auth::user()->tipo == 'GERENTE REGIONAL') {
                $obras = Obra::where("gerente_regional_id", Auth::user()->id)->get();
            } else {
                $obras = Obra::where("encargado_obra_id", Auth::user()->id)->get();
            }
        } else {
            $obras = Obra::all();
        }

        if ($obra_id != 'TODOS') {
            $request->validate([
                'obra_id' => 'required',
            ]);
            $obras = Obra::where('id', 1)->get();
        }
        $data = [];
        foreach ($obras as $value) {
            $data[] = [
                "name" => $value->nombre,
                "y" => (int)$value->porcentaje,
            ];
        }


        return response()->JSON([
            "data" => $data
        ]);
    }
}
