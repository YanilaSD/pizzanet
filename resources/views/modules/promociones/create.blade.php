<x-layouts.app>
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl">Nueva Promoción</h1>
            <p>Completa el formulario para registrar una nueva promoción.</p>
        </div>
        <a href="{{ route('promociones.index') }}">
            <flux:button color="gray">← Volver</flux:button>
        </a>
    </div>

    {{-- Mensajes de error --}}
    @if ($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
            <strong class="font-bold">¡Ups!</strong>
            <ul class="mt-2 list-disc list-inside text-sm">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Tarjeta del formulario --}}
    <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-6">
        <form action="{{ route('promociones.store') }}" method="POST">
            @csrf

            {{-- Inputs en una fila --}}
            <div class="flex flex-col md:flex-row md:space-x-4 space-y-4 md:space-y-0 mb-6">
                {{-- Nombre --}}
                <div class="md:w-1/3">
                    <label for="nombre" class="block text-sm font-medium text-gray-700 dark:text-white mb-1">Nombre</label>
                    <flux:input
                        id="nombre"
                        name="nombre"
                        placeholder="Nombre de la promoción"
                        value="{{ old('nombre') }}"
                        required
                    />
                </div>

                {{-- Descuento --}}
                <div class="md:w-1/3">
                    <label for="descuento" class="block text-sm font-medium text-gray-700 dark:text-white mb-1">Descuento (%)</label>
                    <flux:input
                        id="descuento"
                        name="descuento"
                        placeholder="Descuento de la promoción"
                        value="{{ old('descuento') }}"
                        required
                        type="number"
                        step="0.01"
                    />
                </div>

                {{-- Fecha de inicio --}}
                <div class="md:w-1/3">
                    <label for="fecha_inicio" class="block text-sm font-medium text-gray-700 dark:text-white mb-1">Fecha de Inicio</label>
                    <flux:input
                        id="fecha_inicio"
                        name="fecha_inicio"
                        value="{{ old('fecha_inicio') }}"
                        required
                        type="date"
                    />
                </div>
            </div>

            {{-- Inputs en una fila --}}
            <div class="flex flex-col md:flex-row md:space-x-4 space-y-4 md:space-y-0 mb-6">
                {{-- Fecha de fin --}}
                <div class="md:w-1/3">
                    <label for="fecha_fin" class="block text-sm font-medium text-gray-700 dark:text-white mb-1">Fecha de Fin</label>
                    <flux:input
                        id="fecha_fin"
                        name="fecha_fin"
                        value="{{ old('fecha_fin') }}"
                        required
                        type="date"
                    />
                </div>

                {{-- Compra mínima --}}
                <div class="md:w-1/3">
                    <label for="compra_minima" class="block text-sm font-medium text-gray-700 dark:text-white mb-1">Compra Mínima</label>
                    <flux:input
                        id="compra_minima"
                        name="compra_minima"
                        placeholder="Valor mínimo de compra"
                        value="{{ old('compra_minima') }}"
                        type="number"
                        step="0.01"
                    />
                </div>

                {{-- Límite de uso --}}
                <div class="md:w-1/3">
                    <label for="limite_uso" class="block text-sm font-medium text-gray-700 dark:text-white mb-1">Límite de Uso</label>
                    <flux:input
                        id="limite_uso"
                        name="limite_uso"
                        placeholder="Límite de uso por cliente"
                        value="{{ old('limite_uso') }}"
                        type="number"
                    />
                </div>
            </div>

            <div class="mb-6">
                <label for="festividad_id" class="block text-sm font-medium text-gray-700 dark:text-white mb-1">Festividad</label>
                <select
                    id="festividad_id"
                    name="festividad_id"
                    class="block w-full mt-1 border h-10 border-gray-300 dark:border-gray-700 dark:bg-gray-800 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                    required
                >
                    <option value="">Selecciona una festividad</option>
                    @foreach ($festividades as $festividad)
                        <option value="{{ $festividad->id }}" {{ old('festividad_id') == $festividad->id ? 'selected' : '' }}>
                            {{ $festividad->nombre }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Botón alineado a la derecha --}}
            <div class="flex justify-end">
                <flux:button type="submit">Guardar</flux:button>
            </div>
        </form>
    </div>
</x-layouts.app>
