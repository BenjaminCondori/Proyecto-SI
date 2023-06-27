<?php

namespace App\Http\Livewire\Inscripcion;

use App\Models\Cliente;
use App\Models\Duracion;
use App\Models\Grupo;
use App\Models\Inscripcion;
use App\Models\Paquete;
use Carbon\Carbon;
use DateTime;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Create extends Component
{
    public $fecha_inscripcion, $fecha_inicio, $id_cliente, $id_administrativo, $id_paquete, $id_duracion, $id_pago;
    public $paquetes, $duraciones, $grupos;
    public $search;
    public $searchResults = [];
    public $selectedGrupos = [];

    protected $rules = [
        'fecha_inicio' => 'required',
        'id_cliente' => 'required|exists:CLIENTE,id',
        'id_paquete' => 'required',
        'id_duracion' => 'required',
        'selectedGrupos' => 'required'
    ];

    protected $validationAttributes = [
        'id_cliente' => 'cliente',
        'id_paquete' => 'paquete',
        'id_duracion' => 'duracion',
        'selectedGrupos' => 'grupo'
    ];

    public function updated($propertyName) {
        $this->validateOnly($propertyName);
    }

    private function obtenerIdCliente($cadena) {
        $partes = explode('-', $cadena);
        $id = trim($partes[0]);
        return $id;
    }

    private function obtenerIdAdmin() {
        $user = Auth::user();
        return $user->empleado->id;
    }

    private function obtenerFechaActual() {
        date_default_timezone_set('America/La_Paz');
        $fechaHoraActual = new DateTime();
        $fechaHoraActualString = $fechaHoraActual->format('Y-m-d H:i:s');
        return $fechaHoraActualString;
    }

    public function obtenerGrupos($paqueteId)
    {
        return Grupo::join('DISCIPLINA', 'GRUPO.id_disciplina', '=', 'DISCIPLINA.id')
            ->join('DISCIPLINA_PAQUETE', 'DISCIPLINA.id', '=', 'DISCIPLINA_PAQUETE.id_disciplina')
            ->join('PAQUETE', 'DISCIPLINA_PAQUETE.id_paquete', '=', 'PAQUETE.id')
            ->where('PAQUETE.id', $paqueteId)
            ->select('GRUPO.*')
            ->get();
    }

    public function formatoHora($hora)
    {
        return Carbon::createFromFormat('H:i:s', $hora)->format('H:i A');
    }

    public function updatedIdPaquete() {
        $this->grupos = $this->obtenerGrupos($this->id_paquete);
    }

    public function updatedSearch()
    {
        if (!empty($this->search)) {
            $this->searchResults = Cliente::where(function ($query) {
                $query->where('nombres', 'like', '%'.$this->search.'%')
                    ->orWhere('apellidos', 'like', '%'.$this->search.'%');
            })->get();

            $this->id_cliente = $this->obtenerIdCliente($this->search);
            $this->validate([
                'id_cliente' => 'required|exists:CLIENTE,id',
            ]);
        } else {
            $this->searchResults = [];
            $this->id_cliente = null;
        }
    }

    public function mount() {
        $this->id_administrativo = $this->obtenerIdAdmin();
        $this->paquetes = Paquete::all();
        $this->duraciones = Duracion::pluck('nombre', 'id')->toArray();
        $this->grupos = Grupo::all();
    }

    public function cancelar()
    {
        $this->emitTo('inscripcion.show', 'cerrarVista');
    }

    public function guardarInscripcion() {

        $this->validate();

        try {
            $inscripcion = new Inscripcion();

            $inscripcion->fecha_inicio = $this->fecha_inicio;
            $inscripcion->id_paquete = $this->id_paquete;
            $inscripcion->id_duracion = $this->id_duracion;
            $inscripcion->id_cliente = $this->id_cliente;
            $inscripcion->id_administrativo = $this->id_administrativo;
            $inscripcion->fecha_inscripcion = $this->obtenerFechaActual();

            $inscripcion->save();

            // Obtén los IDs de los grupos seleccionados
            $gruposSeleccionados = $this->selectedGrupos;

            // Asocia los grupos seleccionados a la inscripción
            $inscripcion->grupos()->attach($gruposSeleccionados);

            $this->emitTo('inscripcion.show', 'cerrarVista');
            $this->emit('alert', 'guardado');
        } catch (\Exception $e) {
            $message = $e->getMessage();
            $this->emit('error', $message);
        }
    }

    public function render()
    {
        return view('livewire.inscripcion.create');
    }
    
}
