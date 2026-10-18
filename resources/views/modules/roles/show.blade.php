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
            <p class="text-zinc-900 dark:text-white">{{ $rol->nombre }}</p>
        </div>

        <div>
            <h2 class="module-form-label text-lg">Descripción</h2>
            <p class="text-zinc-900 dark:text-white">{{ $rol->descripcion ?? 'Sin descripción' }}</p>
        </div>

        <div>
            <h2 class="module-form-label text-lg mb-2">Privilegios asignados</h2>
            @if($rol->privilegios->isEmpty())
                <p class="module-muted">No hay privilegios asignados a este rol.</p>
            @else
                <ul class="list-inside list-disc text-zinc-900 dark:text-white">
                    @foreach ($rol->privilegios as $privilegio)
                        <li>{{ $privilegio->nombre }}</li>
                    @endforeach
                </ul>
            @endif
        </div>
    </x-module-card-lg>
</x-layouts.app>
