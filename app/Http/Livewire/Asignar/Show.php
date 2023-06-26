<?php

namespace App\Http\Livewire\Asignar;

use App\Models\Permiso;
use App\Models\Rol;
use Exception;
use Livewire\Component;
use Livewire\WithPagination;

class Show extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';
    
    public $roles, $id_rol;
    public $cant = '10';

    protected $listeners = [
        'revocarTodos' => 'revocarTodos'
    ];

    public function updatedCant()
    {
        $this->resetPage();
        $this->gotoPage(1);
    }

    public function mount() {
        $this->roles = Rol::whereNotIn('nombre', ['cliente', 'instructor'])->get();
    }

    public function togglePermiso($idPermiso) {
        if ($this->id_rol) {
            $rol = Rol::find($this->id_rol);
            if ($rol) {
                if ($this->verificarPermiso($idPermiso)) {
                    $rol->permisos()->detach($idPermiso);
                    $this->emit('asignar_rol', 'success', '¡Permiso Revocado!', 'El permiso ha sido revocado exitosamente.');
                } else {
                    $rol->permisos()->attach($idPermiso);
                    $this->emit('asignar_rol', 'success', '¡Permiso Asignado!', 'El permiso ha sido asignado exitosamente.');
                }
            }
        } else {
            $this->emit('asignar_rol', 'info', 'Oops...', 'Debes seleccionar un rol para que se le asigne el permiso.');
        }
    }

    public function sincronizarTodos() {
        if ($this->id_rol) {
            try {
                $rol = Rol::findOrFail($this->id_rol);
                $permisos = Permiso::pluck('id')->toArray();
                $rol->permisos()->sync($permisos);

                $this->emit('asignar_rol', 'success', '¡Permisos Asignados!', 'Los permisos han sido sincronizados exitosamente.');
                // $this->id_rol = null;
            } catch (Exception $e) {
                $message = $e->getMessage();
                $this->emit('error', $message);
            }
        }else {
            $this->emit('asignar_rol', 'info', 'Oops...', 'Debes seleccionar un rol.');
        }
    }

    public function revocarTodos() {
        if ($this->id_rol) {
            try {
                $rol = Rol::findOrFail($this->id_rol);
                $rol->permisos()->detach();
                // $this->id_rol = null;
            } catch (Exception $e) {
                $message = $e->getMessage();
                $this->emit('error', $message);
            }
        }
    }

    public function verificarPermiso($idPermiso) {
        if ($this->id_rol) {
            $rol = Rol::find($this->id_rol);
            if ($rol) {
                return $rol->permisos->contains('id', $idPermiso);
            }
        }
        return false;
    }

    public function getCantidadRoles($idPermiso) {
        $permiso = Permiso::find($idPermiso);
        if ($permiso) {
            return $permiso->roles()->count();
        }
        return 0;
    }

    public function render()
    {
        $permisosPaginados = Permiso::paginate($this->cant);
        $permisos = $permisosPaginados->items();
        return view('livewire.asignar.show', ['permisos' => $permisos, 'permisosPaginados' => $permisosPaginados]);
    }
}
