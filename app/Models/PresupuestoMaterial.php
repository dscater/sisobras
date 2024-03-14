<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PresupuestoMaterial extends Model
{
    use HasFactory;

    protected $fillable = [
        "presupuesto_id",
        "material_id",
        "precio",
        "cantidad",
        "subtotal",
    ];



    public function presupuesto()
    {
        return $this->belongsTo(Presupuesto::class, 'presupuesto_id');
    }

    public function materials()
    {
        return $this->belongsTo(Material::class, 'material_id');
    }
}
