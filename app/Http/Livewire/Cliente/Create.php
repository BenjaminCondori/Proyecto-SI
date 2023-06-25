<?php

namespace App\Http\Livewire\Cliente;

use App\Models\Cliente;
use App\Models\Empleado;
use App\Models\Rol;
use App\Models\Usuario;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithFileUploads;

class Create extends Component
{
    use WithFileUploads;

    public $id_cliente, $ci, $nombres, $apellidos, $email, $telefono, $fecha_nacimiento, $imagen, $genero, $id_usuario;

    protected $rules = [
        'ci' => 'required|max:10|unique:cliente',
        'nombres' => 'required|max:50',
        'apellidos' => 'required|max:50',
        'email' => 'required|email|max:100|unique:cliente',
        'telefono' => 'required|max:10',
        'genero' => 'required|max:1',
        'fecha_nacimiento' => 'required',
        'imagen' => 'nullable|sometimes|image|max:2048'
    ];

    public function updated($propertyName) {
        $this->validateOnly($propertyName);
    }

    public function cancelar() {
        $this->emitTo('cliente.show', 'cerrarVista');
    }

    public function mount() {
        $this->id_cliente = $this->generarID();
    }

    private function generarID() {
        $ultimoID = Cliente::max('id');
        $id = $ultimoID ? $ultimoID + 1 : 2300000000;
        return str_pad($id, 10, '0', STR_PAD_LEFT);
    }

    private function obtenerIdRol() {
        $idRol = Rol::where('nombre', 'Cliente')->value('id');
        return $idRol;
    }

    public function verificarPermiso($permiso) {
        $usuario = Auth::user();
        $rol = $usuario->rol;
        return ($rol && $rol->permisos->contains('nombre', $permiso));
    }

    public function guardarCliente() {

        $this->validate();

        try {

            $cliente = new Cliente;

            $cliente->id = $this->id_cliente;
            $cliente->ci = $this->ci;
            $cliente->nombres = $this->nombres;
            $cliente->apellidos = $this->apellidos;
            $cliente->fecha_nacimiento = $this->fecha_nacimiento;
            $cliente->telefono = $this->telefono;
            $cliente->email = $this->email;
            $cliente->genero = $this->genero;
            $cliente->fotografia = $this->imagen;

            $usuario = Usuario::create([
                'email' => $this->email,
                'password' => bcrypt($this->ci),
                'id_rol' => $this->obtenerIdRol()
            ]);

            $cliente->id_usuario = $usuario->id;

            $cliente->save();

            $this->emitTo('cliente.show', 'cerrarVista');
            $this->emit('alert', 'guardado');
        } catch (\Exception $e) {
            $this->emit('error');
        }
    }

    public function render() {
        return view('livewire.cliente.create');
    }

}
