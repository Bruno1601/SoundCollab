<form class="md:w-1/2" wire:submit.prevent='crearProyecto'>

    <div>
        <x-input-label for="nombre_proyecto" :value="__('Nombre')" />

        <x-text-input id="nombre_proyecto"
        class="block mt-1 w-full"
        type="text"
        wire:model="nombre_proyecto"
        :value="old('nombre_proyecto')"
        placeholder="Nombre Proyecto"
        />
        @error('nombre_proyecto')
            {{$message}}
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
        @error('descripcion')
            {{$message}}
        @enderror
    </div>

    <div class="flex justify-center">
    <x-primary-button class=" mt-2">
        Crear Proyecto
    </x-primary-button>
    </div>

</form>
