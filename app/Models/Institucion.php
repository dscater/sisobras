<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Institucion extends Model
{
    use HasFactory;

    protected $fillable = [
        "nombre_sistema",
        "alias",
        "razon_social",
        "nit",
        "ciudad",
        "dir",
        "fono",
        "correo",
        "web",
        "actividad",
        "logo",
    ];

    protected $appends = [
        "iniciales_nombre",
        "url_logo",
    ];

    public function getInicialesNombreAttribute()
    {
        $array_razon_social = explode(" ", $this->razon_social);
        $iniciales = "";
        foreach ($array_razon_social as $value) {
            $iniciales .= substr($value, 0, 1);
        }
        return $iniciales;
    }

    public function getUrlLogoAttribute()
    {
        if ($this->logo && trim($this->logo) != "") {
            return asset("imgs/" . $this->logo);
        }
        return null;
    }
}
