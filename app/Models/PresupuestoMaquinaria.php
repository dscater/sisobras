<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PresupuestoMaquinaria extends Model
{
    use HasFactory;

    protected $fillable = [
        "presupuesto_id",
        "maquinaria_id",
        "precio",
        "cantidad",
        "subtotal",
    ];

    public function presupuesto()
    {
        return $this->belongsTo(Presupuesto::class, 'presupuesto_id');
    }

    public function maquinaria()
    {
        return $this->belongsTo(Maquinaria::class, 'maquinaria_id');
    }
}
