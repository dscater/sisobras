<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Presupuesto extends Model
{
    use HasFactory;

    protected $fillable = [
        "obra_id",
        "presupuesto",
        "total",
        "fecha_registro",
    ];
    protected $appends = ["fecha_registro_t"];

    public function getFechaRegistroTAttribute()
    {
        return date("d/m/Y", strtotime($this->fecha_registro));
    }

    public function maquinarias()
    {
        return $this->hasMany(Maquinaria::class, 'presupuesto_id');
    }
}
