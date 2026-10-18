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

    <x-module-card-lg class="space-y-4">
        <div>
            <h2 class="module-form-label text-lg">Nombre</h2>
            <p class="text-gray-900 dark:text-gray-300">{{ $rol->nombre }}</p>
        </div>

        <div>
            <h2 class="module-form-label text-lg">Descripción</h2>
            <p class="text-gray-900 dark:text-gray-300">{{ $rol->descripcion ?? 'Sin descripción' }}</p>
        </div>

        <div>
            <h2 class="module-form-label text-lg mb-2">Privilegios asignados</h2>
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
    </x-module-card-lg>
</x-layouts.app>
