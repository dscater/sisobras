<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Obra extends Model
{
    use HasFactory;

    protected $fillable = [
        "nombre",
        "gerente_regional_id",
        "encargado_obra_id",
        "fecha_pent",
        "fecha_peje",
        "descripcion",
        "lat",
        "lng",
        "categoria_id",
        "fecha_registro",
    ];

    protected $appends = ["fecha_registro_t", "fecha_pent_t", "fecha_peje_t"];

    public function getFechaPentTAttribute()
    {
        return date("d/m/Y", strtotime($this->fecha_pent));
    }

    public function getFechaPejeTAttribute()
    {
        return date("d/m/Y", strtotime($this->fecha_peje));
    }

    public function getFechaRegistroTAttribute()
    {
        return date("d/m/Y", strtotime($this->fecha_registro));
    }

    public function gerente_regional()
    {
        return $this->belongsTo(User::class, 'gerente_regional_id');
    }

    public function encargado_obra()
    {
        return $this->belongsTo(User::class, 'encargado_obra_id');
    }

    public function categoria()
    {
        return $this->belongsTo(Categoria::class, 'categoria_id');
    }
}
