<div>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />

    <x-primary-button wire:click="abrirModalVersiones"><i class="fas fa-history mr-1"></i>Versiones</x-primary-button>

    @if($modal)
        @livewire('modal-versiones', ['archivoId' => $archivoId], key($archivoId))
    @endif
</div>
