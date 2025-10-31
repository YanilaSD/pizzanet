<x-layouts.app>
    <div class="flex justify-between mb-6">
        <div>
            <h1 class="text-2xl font-semibold">Gestión de Ventas</h1>
            <p>Registra y gestiona todas las ventas de la pizzería con promociones</p>
        </div>
    </div>

    @if ($errors->any())
        <div class="my-4 gap-4 space-y-2">
            @foreach ($errors->all() as $error)
                <flux:callout variant="danger" icon="x-circle" heading="{{ $error }}" />
            @endforeach
        </div>
    @endif

    <flux:callout variant="secondary" icon="information-circle" heading="Por cada 20 Bs acumulados, el cliente obtiene 1 punto." />


    <div class="mt-8">
        <h2 class="text-2xl font-semibold text-gray-800">Crear Venta</h2>
        <form action="{{ route('ventas.store') }}" method="POST" class="mt-4 space-y-4">
            <meta name="csrf-token" content="{{ csrf_token() }}">
            @csrf

            <!-- Sección 1: Usuario, Fecha, Cliente -->
            <div class="grid grid-cols-5 gap-6">
                <div class="col-span-2">
                    <label for="usuario" class="block text-sm font-medium text-gray-700 mb-1">Usuario</label>
                    <flux:input id="usuario" name="usuario" value="{{ auth()->user()->name }}" disabled />
                </div>
                <div class="col-span-1">
                    <label for="fecha" class="block text-sm font-medium text-gray-700 mb-1">Fecha</label>
                    <flux:input id="fecha" name="fecha" type="date" value="{{ \Carbon\Carbon::now()->setTimezone('America/La_Paz')->format('Y-m-d') }}" disabled />
                </div>
                <div class="col-span-2">
                    <label for="cliente_id" class="block text-sm font-medium text-gray-700">Cliente</label>
                    <flux:select name="cliente_id" id="cliente_id">
                        <option value="">Selecciona un cliente</option>
                        @foreach ($clientes as $cliente)
                            <option data-descuento="{{ $cliente->descuento }}" value="{{ $cliente->id }}">
                                {{ $cliente->nombre }}
                            </option>
                        @endforeach
                    </flux:select>

                </div>
            </div>

            <!-- Sección: Promoción, Tipo de Pago, Puntos -->
            <div class="grid grid-cols-6 gap-6 mt-4">
                @php
                    $hoy = \Carbon\Carbon::today();
                    $promociones = \App\Models\Promocion::where('estado', 1)
                        ->whereDate('fecha_inicio', '<=', $hoy)
                        ->whereDate('fecha_fin', '>=', $hoy)
                        ->where('limite_uso', '>', 0)
                        ->get();
                @endphp
                <div class="col-span-2">
                    <label for="promocion_id" class="block text-sm font-medium text-gray-700">Promoción</label>
                    <flux:select name="promocion_id" id="promocion_id">
                        <option value="">Selecciona una promoción</option>
                        @foreach ($promociones as $promocion)
                            <option value="{{ $promocion->id }}">
                                {{ $promocion->nombre }} (-{{ $promocion->descuento }}%, hasta {{ \Carbon\Carbon::parse($promocion->fecha_fin)->format('d/m/Y') }})
                            </option>
                        @endforeach
                    </flux:select>
                </div>
                <div class="col-span-2">
                    <label for="tipo_pago" class="block text-sm font-medium text-gray-700">Tipo de Pago</label>
                    <flux:select name="tipo_pago_id" id="tipo_pago">
                        <option value="">Selecciona un tipo de pago</option>
                        @foreach ($tipo_pagos as $tipo_pago)
                            <option value="{{ $tipo_pago->id }}" {{ old('tipo_pago_id') == $tipo_pago->id ? 'selected' : '' }}>
                                {{ $tipo_pago->nombre }}
                            </option>
                        @endforeach
                    </flux:select>

                </div>
                <!-- <div class="col-span-1">
                    <label for="puntos" class="block text-sm font-medium text-gray-700">Puntos</label>
                    <flux:input type="number" name="puntos" id="puntos" min="0" placeholder="Puntos" />
                </div> -->
            </div>

            <flux:separator />

            <!-- Agregar producto -->
            <div class="mt-4 flex gap-4">
                <div class="w-full">
                    <label for="producto_id" class="block text-sm font-medium text-gray-700">Producto</label>
                    <flux:select name="producto_id" id="producto_id">
                        <option value="">Selecciona un producto</option>
                        @foreach ($productos as $producto)
                            <option value="{{ $producto->id }}" data-precio="{{ $producto->precio }}">{{ $producto->nombre }} / {{ $producto->categoria->nombre }} / Bs {{ $producto->precio }}</option>
                        @endforeach
                    </flux:select>
                </div>
                <div class="w-full">
                    <label for="cantidad" class="block text-sm font-medium text-gray-700">Cantidad</label>
                    <flux:input type="number" name="cantidad" id="cantidad" placeholder="Cantidad" min="1" />
                </div>
                <div class="flex items-end">
                    <flux:button type="button" variant="primary" id="add-product" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600" disabled>Agregar Producto</flux:button>
                </div>
            </div>

            <!-- Detalle y Totales: izquierda/derecha -->
            <div class="grid grid-cols-3 gap-6 mt-8">
                <!-- Tabla productos -->
                <div class="col-span-2">
                    <h3 class="text-lg font-semibold text-gray-700 mb-2">Detalle de Venta</h3>
                    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                        <table class="w-full text-sm text-left text-gray-500">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3">Producto</th>
                                    <th class="px-6 py-3">Cantidad</th>
                                    <th class="px-6 py-3">Precio Unitario</th>
                                    <th class="px-6 py-3">Subtotal</th>
                                    <th class="px-6 py-3">Acción</th>
                                </tr>
                            </thead>
                            <tbody id="productos-tbody">
                                @if(session('productos') && count(session('productos')) > 0)
                                    @foreach(session('productos') as $producto)
                                        <tr class="bg-white border-b">
                                            <td class="px-6 py-4">{{ $producto['nombre'] }}</td>
                                            <td class="px-6 py-4">{{ $producto['cantidad'] }}</td>
                                            <td class="px-6 py-4">${{ number_format($producto['precio_unitario'], 2) }}</td>
                                            <td class="px-6 py-4">${{ number_format($producto['subtotal'], 2) }}</td>
                                            <td class="px-6 py-4 text-right">
                                                <button type="button" class="text-red-500 hover:text-red-700" onclick="removeProduct({{ $producto['id'] }})">Eliminar</button>
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr id="productos-empty-row">
                                        <td colspan="5" class="text-center py-4 text-gray-500">Agrega productos a esta venta.</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Totales -->
                <div class="col-span-1">
                    <h3 class="text-lg font-semibold text-gray-700 mb-2">Detalle de pagos</h3>
                    <div class="p-6 bg-white border border-gray-200 rounded-xl shadow-md space-y-4 ml-auto">

                        <!-- Total productos -->
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600 font-medium">Total Productos</span>
                            <span id="total-general" class="text-gray-900 text-lg font-semibold">Bs0.00</span>
                        </div>

                        <!-- Descuento -->
                        <div id="cardPromocion" class="flex justify-between items-center border-t border-dashed border-gray-300 pt-3 hidden">
                            <span class="text-blue-600 font-medium">Descuento</span>
                            <span id="total-descuento" class="text-blue-600 text-lg font-semibold">-Bs0.00</span>
                        </div>

                        <!-- Total a pagar -->
                        <div class="flex justify-between items-center border-t border-gray-300 pt-3">
                            <span class="text-gray-800 text-xl font-bold">Total a Pagar</span>
                            <span id="total-pagar" class="text-green-600 text-2xl font-extrabold">Bs0.00</span>
                        </div>

                        <flux:button
                            type="submit"
                            variant="primary"
                            color="green"
                            id="add-product"
                            class="text-white px-4 py-2 rounded-lg w-full"
                        >
                            Confirmar Venta
                        </flux:button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            console.log('Documento cargado');
            toggleAddProductButton();
            toggleRegisterButton();
            updateTotalCompra();
            document.getElementById('producto_id').addEventListener('change', toggleAddProductButton);
            document.getElementById('cantidad').addEventListener('input', toggleAddProductButton);

            document.getElementById('add-product').addEventListener('click', addProducto);
            document.getElementById('promocion_id').addEventListener('change', handlePromoChange);
        });

        // Habilitar/deshabilitar botón Agregar Producto
        function toggleAddProductButton() {
            const productoId = document.getElementById('producto_id').value;
            const cantidad = document.getElementById('cantidad').value;
            const addButton = document.getElementById('add-product');
            if (!addButton) return; // ⚠️ Si no existe, salir
            addButton.disabled = !(productoId && cantidad > 0);
        }

        // Habilitar/deshabilitar botón Registrar Venta
        function toggleRegisterButton() {
            const productos = @json(session('productos') ?? []);
            const registerButton = document.getElementById('register-sale');
            if (!registerButton) return; // ⚠️ Si no existe, salir
            registerButton.disabled = productos.length === 0;
        }

        // Manejo de cambio de promoción
        function handlePromoChange(event) {
            const promoId = event.target.value;
            console.log('Promoción seleccionada:', typeof promoId);
            if (!promoId) {
                fetch("{{ route('ventas.setPromocion') }}", {
                    method: "POST",
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ promocion_id: null })
                }).finally(updateTotalCompra);
                return;
            }


            fetch("{{ route('ventas.setPromocion') }}", {
                method: "POST",
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ promocion_id: promoId })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) updateTotalCompra();
                else {
                    alert(data.message);
                    event.target.value = ""; // limpiar select
                    updateTotalCompra();
                }
            });
        }

        // Agregar producto a la sesión
        function addProducto() {
            const productoId = document.getElementById('producto_id').value;
            const cantidad = Number(document.getElementById('cantidad').value);
            const precioStr = document.querySelector(`#producto_id option[value="${productoId}"]`)?.getAttribute('data-precio') || '0';
            const precio = parseFloat(precioStr);

            if (!productoId || cantidad <= 0) {
                alert('Debe seleccionar un producto y una cantidad válida.');
                return;
            }

            fetch("{{ route('ventas.addProducto') }}", {
                method: "POST",
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ producto_id: productoId, cantidad: cantidad, precio: precio })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    renderProductos(data.productos);
                    updateTotalCompra();
                    toggleRegisterButton();
                    document.getElementById('cantidad').value = '';
                    document.getElementById('producto_id').value = '';
                    toggleAddProductButton();
                } else {
                    alert('Error al agregar el producto');
                }
            })
            .catch(err => {
                console.error(err);
                alert('Error de comunicación con el servidor');
            });
        }

        // Renderizar productos en la tabla
        function renderProductos(productos) {
            const tbody = document.getElementById('productos-tbody');
            if (!tbody) return;
            tbody.innerHTML = '';

            if (!productos || productos.length === 0) {
                tbody.innerHTML = `<tr id="productos-empty-row">
                    <td colspan="5" class="text-center py-4 text-gray-500">Agrega productos a esta venta.</td>
                </tr>`;
                return;
            }

            productos.forEach(prod => {
                const tr = document.createElement('tr');
                tr.className = 'bg-white border-b dark:bg-gray-800 dark:border-gray-700 border-gray-200';
                tr.innerHTML = `
                    <td class="px-6 py-4">${prod.nombre}</td>
                    <td class="px-6 py-4">${Number(prod.cantidad)}</td>
                    <td class="px-6 py-4">Bs ${Number(prod.precio_unitario).toFixed(2)}</td>
                    <td class="px-6 py-4">Bs ${Number(prod.subtotal).toFixed(2)}</td>
                    <td class="px-6 py-4">
                        <button type="button" class="text-red-500 hover:text-red-700" onclick="removeProduct(${prod.id})">Eliminar</button>
                    </td>
                `;
                tbody.appendChild(tr);
            });
        }

        // Actualizar totales
        function updateTotalCompra() {
            fetch("{{ route('ventas.getTotalCompra') }}")
                .then(res => res.json())
                .then(data => {
                    console.log('Totales actualizados:', data);
                    document.getElementById('total-general').textContent = `Bs ${data.total.toFixed(2)}`;
                    document.getElementById('total-descuento').textContent = `- Bs ${data.descuento.toFixed(2)}`;
                    document.getElementById('total-pagar').textContent = `Bs ${data.total_pagar.toFixed(2)}`;

                    const cardPromocion = document.getElementById('cardPromocion');
                    if (data.descuento > 0) {
                        cardPromocion.classList.remove('hidden');
                    } else {
                        cardPromocion.classList.add('hidden');
                    }
                });
        }

        // Eliminar producto de la sesión
        function removeProduct(productId) {
            fetch("{{ route('ventas.removeProducto') }}", {
                method: "DELETE",
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ id: productId })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    renderProductos(data.productos);
                    updateTotalCompra();
                    toggleRegisterButton();
                }
            });
        }
        document.addEventListener('DOMContentLoaded', function () {
            const clienteSelect = document.getElementById('cliente_id');
            const promoSelect = document.getElementById('promocion_id');

            clienteSelect.addEventListener('change', function () {
                const clienteId = this.options[this.selectedIndex];

                if (!clienteId.value) {
                    console.log('No hay cliente seleccionado');
                    return;
                }

                // ✅ Así accedes correctamente a los data-attributes
                const cliente = {
                    id: clienteId.value,
                    nombre: clienteId.dataset.nombre,
                    puntos: parseInt(clienteId.dataset.puntos || 0, 10),
                    descuento: parseFloat(clienteId.dataset.descuento || 0)
                };

                console.log('Cliente seleccionado:', cliente);

                // Eliminar cualquier opción previa de "Descuento del Cliente"
                const clientePromo = document.getElementById('promo-descuento-cliente');
                if (clientePromo) clientePromo.remove();


                if (!clienteId) return;

                if (cliente.descuento > 0) {
                    // Crear nueva opción para descuento del cliente
                    const option = document.createElement('option');
                    option.value = `cliente-${cliente.descuento}`;
                    option.id = 'promo-descuento-cliente';
                    option.textContent = `Descuento Cliente (-${cliente.descuento}%)`;
                    promoSelect.appendChild(option);

                    // Seleccionar automáticamente la promo del cliente
                    promoSelect.value = `cliente-${cliente.descuento}`;

                    // Enviar al backend para actualizar total
                    // handleClientePromo(cliente.descuento);
                    fetch("{{ route('ventas.setPromocion') }}", {
                        method: "POST",
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({
                            promocion_id: null,
                            descuento_cliente: cliente.descuento
                        })
                    }).then(() => updateTotalCompra());
                } else {
                    updateTotalCompra();
                }
            });
        });
    </script>

</x-layouts.app>
