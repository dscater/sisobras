<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Operario extends Model
{
    use HasFactory;

    protected $fillable = [
        "nombre",
        "paterno",
        "materno",
        "ci",
        "ci_exp",
        "fono",
        "tipo",
        "fecha_registro",
    ];

    protected $appends = ["fecha_registro_t", "full_ci", "full_name"];

    public function getFechaRegistroTAttribute()
    {
        return date("d/m/Y", strtotime($this->fecha_registro));
    }

    public function getFullCiAttribute()
    {
        return $this->ci . ' ' . $this->ci_exp;
    }

    public function getFullNameAttribute()
    {
        return $this->nombre .  ($this->paterno != NULL && $this->paterno != '' ? ' ' . $this->paterno : '') . ($this->materno != NULL && $this->materno != '' ? ' ' . $this->materno : '');
    }
}
