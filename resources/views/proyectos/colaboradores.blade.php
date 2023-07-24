<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-4">
                <div class="container mx-auto">
                    <h2 class="text-2xl font-bold mb-4 text-center">Colaboradores del Proyecto</h2>
                    <table class="table-auto w-full">
                        <thead class="bg-gray-200 text-gray-600 uppercase text-sm leading-normal">
                            <tr>
                                <th class="px-4 py-2">Nombre</th>
                                <th class="px-4 py-2">Correo Electrónico</th>
                                <th class="px-4 py-2">Se unió al proyecto</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($colaboradores as $colaborador)
                                <tr>
                                    <td class="border px-4 py-2">{{ $colaborador->user->name }}</td>
                                    <td class="border px-4 py-2">{{ $colaborador->user->email }}</td>
                                    <td class="border px-4 py-2">{{ $colaborador->created_at }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>
