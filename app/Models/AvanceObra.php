<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AvanceObra extends Model
{
    use HasFactory;

    protected $fillable = [
        "obra_id",
        "nro_progreso",
        "marcados",
        "descripcion",
        "observacion",
        "fecha_registro",
    ];

    protected $appends = ["fecha_registro_t", "modificable", "porcentaje"];

    public function getModificableAttribute()
    {
        $existe_mayor = AvanceObra::where("id", ">", $this->id)
            ->where("obra_id", $this->obra_id)->get();
        if (count($existe_mayor) > 0) {
            return false;
        }
        return true;
    }

    public function getPorcentajeAttribute()
    {
        $total = $this->obra->categoria->nro_avances;
        $porcentaje = ($this->nro_progreso * 100) / $total;
        $porcentaje = round($porcentaje, 0);

        return $porcentaje;
    }


    public function getFechaRegistroTAttribute()
    {
        return date("d/m/Y", strtotime($this->fecha_registro));
    }

    public function obra()
    {
        return $this->belongsTo(Obra::class, 'obra_id');
    }
}
