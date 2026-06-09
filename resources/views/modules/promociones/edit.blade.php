<x-layouts.app>
    <div class="flex flex-col gap-2 sm:flex-row sm:justify-between sm:items-center mb-6">
        <div>
            <h1 class="text-2xl font-semibold">Editar Promoción</h1>
            <p class="text-sm text-gray-600 dark:text-gray-400">
                Modifica los datos de la promoción seleccionada.
            </p>
        </div>
    </div>

    @if ($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg mb-6">
            <strong class="font-semibold">Revisa los campos:</strong>
            <ul class="mt-2 list-disc list-inside text-sm space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <x-module-card-lg>
        <form action="{{ route('promociones.update', $promocion->id) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label for="nombre" class="module-form-label">
                        Nombre
                    </label>
                    <flux:input
                        id="nombre"
                        name="nombre"
                        placeholder="Nombre de la promoción"
                        value="{{ old('nombre', $promocion->nombre) }}"
                        required
                    />
                </div>

                <div>
                    <label for="descuento" class="module-form-label">
                        Descuento (%)
                    </label>
                    <flux:input
                        id="descuento"
                        name="descuento"
                        placeholder="Ej: 10"
                        value="{{ old('descuento', $promocion->descuento) }}"
                        type="number"
                        step="0.01"
                        min="0"
                        max="100"
                        required
                    />
                </div>

                <div>
                    <label for="festividad_id" class="module-form-label">
                        Festividad
                    </label>
                    <select
                        id="festividad_id"
                        name="festividad_id"
                        class="block w-full h-10 border border-gray-300 dark:border-gray-700 dark:bg-gray-800 rounded-md shadow-sm px-3 text-sm
                               focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500"
                        required
                    >
                        <option value="">Selecciona una festividad</option>
                        @foreach ($festividades as $festividad)
                            <option value="{{ $festividad->id }}"
                                {{ old('festividad_id', $promocion->festividad_id) == $festividad->id ? 'selected' : '' }}>
                                {{ $festividad->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label for="fecha_inicio" class="module-form-label">
                        Fecha de inicio
                    </label>
                    <flux:input
                        id="fecha_inicio"
                        name="fecha_inicio"
                        type="date"
                        value="{{ old('fecha_inicio', optional($promocion->fecha_inicio)->toDateString()) }}"
                        required
                    />
                </div>

                <div>
                    <label for="fecha_fin" class="module-form-label">
                        Fecha de fin
                    </label>
                    <flux:input
                        id="fecha_fin"
                        name="fecha_fin"
                        type="date"
                        value="{{ old('fecha_fin', optional($promocion->fecha_fin)->toDateString()) }}"
                        required
                    />
                </div>

                <div>
                    <label for="compra_minima" class="module-form-label">
                        Compra mínima (Bs)
                    </label>
                    <flux:input
                        id="compra_minima"
                        name="compra_minima"
                        placeholder="Ej: 50.00"
                        value="{{ old('compra_minima', $promocion->compra_minima) }}"
                        type="number"
                        step="0.01"
                        min="0"
                    />
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label for="limite_uso" class="module-form-label">
                        Límite de uso (por cliente)
                    </label>
                    <flux:input
                        id="limite_uso"
                        name="limite_uso"
                        placeholder="Ej: 1"
                        value="{{ old('limite_uso', $promocion->limite_uso) }}"
                        type="number"
                        min="0"
                    />
                </div>
            </div>

            <div class="flex justify-end gap-2 pt-2">
                <a href="{{ route('promociones.index') }}">
                    <flux:button variant="primary" color="gray">
                        Cancelar
                    </flux:button>
                </a>

                <flux:button type="submit" variant="primary" color="orange">
                    Guardar cambios
                </flux:button>
            </div>
        </form>
    </x-module-card-lg>
</x-layouts.app>