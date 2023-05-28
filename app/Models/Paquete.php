<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Paquete extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $fillable = ['id', 'nombre', 'descripcion'];
    protected $table = 'paquete';

    public function disciplinas():BelongsToMany {
        return $this->belongsToMany(Disciplina::class, 'disciplina_paquete', 'id_paquete', 'id_disciplina');
    }

}
