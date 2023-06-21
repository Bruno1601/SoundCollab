<div>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />

    <x-primary-button wire:click="crear()" class=""><i class="fas fa-user-plus mr-1"></i>Nuevo Colaborador</x-primary-button>

    @if($modal)
            @include('livewire.agregar-colaborador')
    @endif
</div>


