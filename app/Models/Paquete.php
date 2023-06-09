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
    protected $table = 'PAQUETE';

    public function disciplinas():BelongsToMany {
        return $this->belongsToMany(Disciplina::class, 'DISCIPLINA_PAQUETE', 'id_paquete', 'id_disciplina');
    }

    public function duraciones():BelongsToMany {
        return $this->belongsToMany(Duracion::class, 'PAQUETE_DURACION', 'id_paquete', 'id_duracion')->withPivot('precio', 'descuento');
    }

    public function inscripciones():BelongsToMany {
        return $this->belongsToMany(Inscripcion::class, 'id_paquete');
    }

}
