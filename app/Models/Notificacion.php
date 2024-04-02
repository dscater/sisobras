<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notificacion extends Model
{
    use HasFactory;

    protected $fillable = [
        "registro_id",
        "modelo",
        "descripcion",
    ];

    public function avance_obra()
    {
        return $this->belongsTo(AvanceObra::class, 'registro_id');
    }

    public function notificacion_users()
    {
        return $this->hasMany(NotificacionUser::class, 'notificacion_id');
    }
}
