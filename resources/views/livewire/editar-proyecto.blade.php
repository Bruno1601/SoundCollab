<form class="md:w-1/2" wire:submit.prevent='editarProyecto'>

    <div>
        <x-input-label for="nombre_proyecto" :value="__('Nombre')" />

        <x-text-input id="nombre_proyecto"
        class="block mt-1 w-full"
        type="text"
        wire:model="nombre_proyecto"
        :value="old('nombre_proyecto')"
        placeholder="Nombre Proyecto"
        />
        {{-- se trae el mensaje de error $message desde la vista livewire se nombra :message--}}
        @error('nombre_proyecto')
            <livewire:mostrar-alerta :message="$message" />
        @enderror
    </div>

    <div>
        <x-input-label for="descripcion" :value="__('Descripción')" />

        <x-text-input id="descripcion"
        class="block mt-1 w-full"
        type="text"
        wire:model="descripcion"
        :value="old('descripcion')"
        placeholder="Descripción del Proyecto"
        />
        {{-- se trae el mensaje de error $message desde la vista livewire se nombra :message--}}
        @error('descripcion')
            <livewire:mostrar-alerta :message="$message" />
        @enderror
    </div>

    <div class="flex justify-center">
    <x-primary-button class=" mt-2">
        Guardar Cambios
    </x-primary-button>
    </div>

</form>
