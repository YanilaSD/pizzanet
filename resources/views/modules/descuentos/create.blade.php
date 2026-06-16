<x-layouts.app>

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

    <x-module-card-lg title="Nuevo Descuento" description="Completa el formulario para registrar un nuevo descuento.">
        <form action="{{ route('descuentos.store') }}" method="POST">
            @csrf

            <div class="text-xs bg-yellow-100 border border-yellow-400 text-yellow-800 px-4 py-3 rounded mb-6">
                <strong class="font-bold">Atención:</strong>
                <span class="block sm:inline">
                    Al guardar, este quedara registro quedar[a] como activo y los anteriores se desactivaran.
                </span>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="md:col-span-2">
                    <label for="nombre" class="module-form-label">
                        Nombre <span class="text-red-500">*</span>
                    </label>
                    <flux:input
                        id="nombre"
                        name="nombre"
                        placeholder="Nombre del descuento"
                        value="{{ old('nombre') }}"
                        required
                    />
                </div>

                <div class="md:col-span-1">
                    <label for="puntos" class="module-form-label">
                        Puntos requeridos <span class="text-red-500">*</span>
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

                <div class="md:col-span-1">
                    <label for="descuento" class="module-form-label">
                        Monto de descuento (Bs) <span class="text-red-500">*</span>
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

                <div class="md:col-span-4">
                    <label for="descripcion" class="module-form-label">
                        Descripción <span class="text-red-500">*</span>
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
