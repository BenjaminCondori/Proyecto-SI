<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Disciplina extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $fillable = ['id', 'nombre', 'descripcion', 'precio', 'id_seccion'];
    protected $table = 'disciplina';

    public function seccion():BelongsTo
    {
        return $this->belongsTo(Seccion::class);
    }

}
