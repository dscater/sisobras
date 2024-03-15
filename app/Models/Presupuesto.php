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
        "total_precio",
        "total_cantidad",
        "total",
        "fecha_registro",
    ];
    protected $appends = ["fecha_registro_t", "total1", "total1_precio", "total1_cantidad", "total2", "total2_precio", "total2_cantidad", "total3", "total3_precio", "total3_cantidad"];

    public function getFechaRegistroTAttribute()
    {
        return date("d/m/Y", strtotime($this->fecha_registro));
    }

    public function getTotal1Attribute()
    {
        return PresupuestoMaterial::where("presupuesto_id", $this->id)->sum("subtotal");
    }

    public function getTotal1PrecioAttribute()
    {
        return PresupuestoMaterial::where("presupuesto_id", $this->id)->sum("precio");
    }

    public function getTotal1CantidadAttribute()
    {
        return PresupuestoMaterial::where("presupuesto_id", $this->id)->sum("cantidad");
    }

    public function getTotal2Attribute()
    {
        return PresupuestoOperario::where("presupuesto_id", $this->id)->sum("subtotal");
    }

    public function getTotal2PrecioAttribute()
    {
        return PresupuestoOperario::where("presupuesto_id", $this->id)->sum("precio");
    }

    public function getTotal2CantidadAttribute()
    {
        return PresupuestoOperario::where("presupuesto_id", $this->id)->sum("cantidad");
    }

    public function getTotal3Attribute()
    {
        return PresupuestoMaquinaria::where("presupuesto_id", $this->id)->sum("subtotal");
    }

    public function getTotal3PrecioAttribute()
    {
        return PresupuestoMaquinaria::where("presupuesto_id", $this->id)->sum("precio");
    }

    public function getTotal3CantidadAttribute()
    {
        return PresupuestoMaquinaria::where("presupuesto_id", $this->id)->sum("cantidad");
    }

    public function obra()
    {
        return $this->belongsTo(Obra::class, 'obra_id');
    }

    public function materials()
    {
        return $this->hasMany(PresupuestoMaterial::class, 'presupuesto_id');
    }

    public function operarios()
    {
        return $this->hasMany(PresupuestoOperario::class, 'presupuesto_id');
    }

    public function maquinarias()
    {
        return $this->hasMany(PresupuestoMaquinaria::class, 'presupuesto_id');
    }
}
