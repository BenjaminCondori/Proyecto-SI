<div>

    @php
        use Carbon\Carbon;
    @endphp

    <div class="form-group px-4 pt-2">
        <i class="fas fa-pencil-alt fa-2x"></i>
        <h3 class="fs-1 d-inline-block ml-1">Editar grupo</h3>
    </div>
    <form  class="px-4 pt-2 pb-4">
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <label for="nombre" class="control-label">Nombre del grupo</label>
                    <input type="text" wire:model.defer="registroSeleccionado.nombre" class="form-control" id="nombre"
                        placeholder="Ej. Super Spinning">
                    @error('registroSeleccionado.nombre')
                        <span class="error text-danger">* {{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <label for="integrantes" class="control-label">Número de integrantes</label>
                    <input type="number" wire:model.defer="registroSeleccionado.nro_integrantes" class="form-control" id="integrantes"
                        placeholder="Ej: 20">
                    @error('registroSeleccionado.nro_integrantes')
                        <span class="error text-danger">* {{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <label for="disciplina" class="control-label">Disciplina</label>
                    <select class="form-control" wire:model.defer="registroSeleccionado.id_disciplina" name="disciplina" id="disciplina">
                        <option value="">Seleccionar</option>
                        @foreach ($disciplinas as $id => $nombre)
                            <option value="{{ $id }}">{{ $nombre }}</option>
                        @endforeach
                    </select>
                    @error('registroSeleccionado.id_disciplina')
                        <span class="error text-danger">* {{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <label for="entrenador" class="control-label">Entrenador</label>
                    <select class="form-control" wire:model.defer="registroSeleccionado.id_entrenador" name="entrenador" id="entrenador">
                        <option value="">Seleccionar</option>
                        @foreach ($entrenadores as $entrenador)
                            <option value="{{ $entrenador->id }}">{{ $entrenador->nombres }} {{ $entrenador->apellidos }}</option>
                        @endforeach
                    </select>
                    @error('registroSeleccionado.id_entrenador')
                        <span class="error text-danger">* {{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <label for="horario" class="control-label">Horario</label>
                    <select class="form-control" wire:model.defer="registroSeleccionado.id_horario" name="horario" id="horario">
                        <option value="">Seleccionar</option>
                        @foreach ($horarios as $horario)
                            <option value="{{ $horario->id }}">{{ $horario->descripcion }} &nbsp;&nbsp;&nbsp; {{ Carbon::createFromFormat('H:i:s', $horario->hora_inicio)->format('H:i A') }} - {{ Carbon::createFromFormat('H:i:s', $horario->hora_fin)->format('H:i A') }}  </option>
                        @endforeach
                    </select>
                    @error('registroSeleccionado.id_horario')
                        <span class="error text-danger">* {{ $message }}</span>
                    @enderror
                </div>
            </div>
            
        </div>

        <div class="form-group text-right m-b-0">
            <button type="button" wire:click="cancelar" wire:loading.attr="disabled"
                class="btn btn-danger waves-effect m-l-5">
                Cancelar
            </button>
            <button class="btn btn-primary waves-effect waves-light" wire:click="actualizarGrupo" wire:loading.attr="disabled" type="button">
                Guardar
            </button>
        </div>
    </form>

</div>


