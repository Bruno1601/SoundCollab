<div>
    <h1 class="text-2xl font-bold text-center mb-5">Archivos</h1>

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
        @foreach ($archivos as $archivo)
            <livewire:archivo-item :archivo="$archivo" :key="$archivo->id" />
        @endforeach
    </div>
    <div class="flex justify-center mt-10">
    {{ $archivos->links() }}
    </div>
</div>
