<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rol_Permiso extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $primaryKey = ['id_rol', 'id_permiso'];
    protected $table = 'ROL_PERMISO';
    protected $fillable = ['id_rol', 'id_permiso'];
}
