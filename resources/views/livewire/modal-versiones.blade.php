<div class="fixed z-10 inset-0 overflow-y-auto ease-out duration-400">
    <div class="flex justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity">
            <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
        </div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen"></span>
        <div class="inline-block w-full align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full" role="dialog" aria-modal="true" aria-labelledby="modal-headline">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <h2 class="text-2xl font-bold mb-4">Historial de versiones</h2>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Archivo</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Versión</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subido por</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha de subida</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Bitrate</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sample Rate</th>
                                <!-- Agregar más th aquí para cada pieza de información que quieras mostrar -->
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($versiones && count($versiones) > 0)
                                @foreach ($versiones as $version)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $version['archivo']['nombre'] }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $version['version'] }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $version['usuario'] ?? 'N/A' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            {{ $version['created_at'] ? \Carbon\Carbon::parse($version['created_at'])->format('d/m/Y H:i:s') : 'N/A' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $version['bitrate'] ?? 'N/A' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $version['sample_rate'] ?? 'N/A' }}</td>
                                        <!-- Agregar más td aquí para cada pieza de información que quieras mostrar -->
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <a href="{{ route('archivos.descargar', ['archivoId' => $version['archivo']['id']]) }}" class="text-blue-500 hover:underline">Descargar</a>
                                            @if(!$archivo->bloqueado || optional($archivo->bloqueadoPor)->id === optional(auth()->user())->id)
                                            <button wire:click="eliminarArchivo({{ $version['id'] }})" class="text-red-500 hover:underline ml-2">Eliminar</button>
                                        @endif
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="7">No hay versiones disponibles</td>  <!-- Asegúrate de actualizar el colspan al número correcto de columnas -->
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
                <div class="bg-white px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse mt-4">
                    <span class="flex w-full rounded-md shadow-sm sm:ml-3 sm:w-auto">
                        <button wire:click="cerrarModalVersiones" type="button" class="inline-flex justify-center w-full rounded-md border border-gray-300 px-4 py-2 bg-gray-200 text-base leading-6 font-medium text-gray-700 shadow-sm hover:text-gray-500 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue transition ease-in-out duration-150 sm:text-sm sm:leading-5">Cerrar</button>
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>
