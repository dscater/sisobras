<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PresupuestoOperario extends Model
{
    use HasFactory;

    protected $fillable = [
        "presupuesto_id",
        "operario_id",
        "precio",
        "cantidad",
        "subtotal",
    ];

    public function presupuesto()
    {
        return $this->belongsTo(Presupuesto::class, 'presupuesto_id');
    }

    public function operario()
    {
        return $this->belongsTo(Operario::class, 'operario_id');
    }
}
