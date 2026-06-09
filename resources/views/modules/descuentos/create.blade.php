<x-layouts.app>
    <div class="flex flex-col gap-2 sm:flex-row sm:justify-between sm:items-center mb-6">
        <div>
            <h1 class="text-2xl font-semibold">Nuevo Descuento</h1>
            <p class="text-sm text-gray-600 dark:text-gray-400">
                Registra un descuento nuevo. Al guardar, este quedara como activo y los anteriores se desactivaran.
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
        <form action="{{ route('descuentos.store') }}" method="POST" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="nombre" class="module-form-label">
                        Nombre
                    </label>
                    <flux:input
                        id="nombre"
                        name="nombre"
                        placeholder="Nombre del descuento"
                        value="{{ old('nombre') }}"
                        required
                    />
                </div>

                <div>
                    <label for="puntos" class="module-form-label">
                        Puntos requeridos
                    </label>
                    <flux:input
                        id="puntos"
                        name="puntos"
                        type="number"
                        min="1"
                        step="1"
                        placeholder="Ej: 100"
                        value="{{ old('puntos') }}"
                        required
                    />
                </div>

                <div>
                    <label for="descuento" class="module-form-label">
                        Monto de descuento (Bs)
                    </label>
                    <flux:input
                        id="descuento"
                        name="descuento"
                        type="number"
                        min="0.01"
                        step="0.01"
                        placeholder="Ej: 10.00"
                        value="{{ old('descuento') }}"
                        required
                    />
                </div>

                <div class="md:col-span-2">
                    <label for="descripcion" class="module-form-label">
                        Descripción
                    </label>
                    <textarea
                        id="descripcion"
                        name="descripcion"
                        rows="3"
                        class="block w-full border border-gray-300 dark:border-gray-700 dark:bg-gray-800 rounded-md shadow-sm px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500"
                        placeholder="Describe el descuento"
                        required
                    >{{ old('descripcion') }}</textarea>
                </div>
            </div>

            <div class="flex justify-end gap-2 pt-2">
                <a href="{{ route('descuentos.index') }}">
                    <flux:button variant="primary" color="gray">
                        Volver
                    </flux:button>
                </a>

                <flux:button variant="primary" color="orange" type="submit">
                    Guardar
                </flux:button>
            </div>
        </form>
    </x-module-card-lg>
</x-layouts.app>
