<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Rol extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $table = 'rol';
    protected $fillable = ['id', 'nombre'];

    public function usuarios(): HasMany {
        return $this->hasMany(Usuario::class, 'id_rol');
    }

    public function permisos(): BelongsToMany {
        return $this->belongsToMany(Permiso::class, 'rol_permiso', 'id_rol', 'id_permiso');
    }

}
