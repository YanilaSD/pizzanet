<x-layouts.app>
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl">Detalle del Rol</h1>
            <p>Información detallada del rol y sus privilegios asignados.</p>
        </div>
        <a href="{{ route('roles.index') }}">
            <flux:button color="gray">← Volver</flux:button>
        </a>
    </div>

    <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-6 space-y-4">
        <div>
            <h2 class="text-lg font-semibold text-gray-700 dark:text-white">Nombre</h2>
            <p class="text-gray-900 dark:text-gray-300">{{ $rol->nombre }}</p>
        </div>

        <div>
            <h2 class="text-lg font-semibold text-gray-700 dark:text-white">Descripción</h2>
            <p class="text-gray-900 dark:text-gray-300">{{ $rol->descripcion ?? 'Sin descripción' }}</p>
        </div>

        <div>
            <h2 class="text-lg font-semibold text-gray-700 dark:text-white mb-2">Privilegios asignados</h2>
            @if($rol->privilegios->isEmpty())
                <p class="text-gray-500 dark:text-gray-400">No hay privilegios asignados a este rol.</p>
            @else
                <ul class="list-disc list-inside text-gray-900 dark:text-gray-300">
                    @foreach ($rol->privilegios as $privilegio)
                        <li>{{ $privilegio->nombre }}</li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>
</x-layouts.app>
