<x-layouts.app>
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl">Editar Promoción</h1>
            <p>Modifica los datos de la promoción seleccionada.</p>
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
        <form action="{{ route('promociones.update', $promocion->id) }}" method="POST">
            @csrf
            @method('PUT')

            {{-- Inputs en una fila --}}
            <div class="flex flex-col md:flex-row md:space-x-4 space-y-4 md:space-y-0 mb-6">
                {{-- Nombre --}}
                <div class="md:w-1/3">
                    <label for="nombre" class="block text-sm font-medium text-gray-700 dark:text-white mb-1">Nombre</label>
                    <flux:input
                        id="nombre"
                        name="nombre"
                        placeholder="Nombre de la promoción"
                        value="{{ old('nombre', $promocion->nombre) }}"
                        required
                    />
                </div>

                {{-- Descripción --}}
                <div class="md:w-2/3">
                    <label for="descripcion" class="block text-sm font-medium text-gray-700 dark:text-white mb-1">Descripción</label>
                    <flux:input
                        id="descripcion"
                        name="descripcion"
                        placeholder="Descripción de la promoción"
                        value="{{ old('descripcion', $promocion->descripcion) }}"
                    />
                </div>
            </div>

            {{-- Descuento --}}
            <div class="mb-6">
                <label for="descuento" class="block text-sm font-medium text-gray-700 dark:text-white mb-1">Descuento</label>
                <flux:input
                    id="descuento"
                    name="descuento"
                    placeholder="Descuento (%)"
                    value="{{ old('descuento', $promocion->descuento) }}"
                    type="number"
                    min="0"
                    max="100"
                    required
                />
            </div>

            {{-- Fechas --}}
            <div class="flex flex-col md:flex-row md:space-x-4 space-y-4 md:space-y-0 mb-6">
                {{-- Fecha Inicio --}}
                <div class="md:w-1/3">
                    <label for="fecha_inicio" class="block text-sm font-medium text-gray-700 dark:text-white mb-1">Fecha de Inicio</label>
                    <flux:input
                        id="fecha_inicio"
                        name="fecha_inicio"
                        type="date"
                        value="{{ old('fecha_inicio', $promocion->fecha_inicio->toDateString()) }}"
                        required
                    />
                </div>

                {{-- Fecha Fin --}}
                <div class="md:w-1/3">
                    <label for="fecha_fin" class="block text-sm font-medium text-gray-700 dark:text-white mb-1">Fecha de Fin</label>
                    <flux:input
                        id="fecha_fin"
                        name="fecha_fin"
                        type="date"
                        value="{{ old('fecha_fin', $promocion->fecha_fin->toDateString()) }}"
                        required
                    />
                </div>
            </div>

            {{-- Compra Mínima --}}
            <div class="mb-6">
                <label for="compra_minima" class="block text-sm font-medium text-gray-700 dark:text-white mb-1">Compra Mínima</label>
                <flux:input
                    id="compra_minima"
                    name="compra_minima"
                    placeholder="Compra mínima"
                    value="{{ old('compra_minima', $promocion->compra_minima) }}"
                    type="number"
                    min="0"
                />
            </div>

            {{-- Límite de Uso --}}
            <div class="mb-6">
                <label for="limite_uso" class="block text-sm font-medium text-gray-700 dark:text-white mb-1">Límite de Uso</label>
                <flux:input
                    id="limite_uso"
                    name="limite_uso"
                    placeholder="Límite de uso"
                    value="{{ old('limite_uso', $promocion->limite_uso) }}"
                    type="number"
                    min="1"
                />
            </div>

            {{-- Festividad --}}
            <div class="mb-6">
                <label for="festividad_id" class="block text-sm font-medium text-gray-700 dark:text-white mb-1">Festividad</label>
                <select id="festividad_id" name="festividad_id" class="block w-full mt-1 border h-10 border-gray-300 dark:border-gray-700 dark:bg-gray-800 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                     required>
                    @foreach ($festividades as $festividad)
                        <option value="{{ $festividad->id }}" {{ old('festividad_id', $promocion->festividad_id) == $festividad->id ? 'selected' : '' }}>
                            {{ $festividad->nombre }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Botón de Guardar --}}
            <div class="flex justify-end">
                <flux:button type="submit">Guardar</flux:button>
            </div>
        </form>
    </div>
</x-layouts.app>
