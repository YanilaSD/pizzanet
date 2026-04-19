<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolPrivilegioSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('roles')->insert([
            ['nombre' => 'Administrador', 'descripcion' => 'Acceso total al sistema', 'estado' => 1, 'created_at' => now(), 'updated_at' => now()],
        ]);

        $privilegios = [
            ['nombre' => 'Ver privilegios', 'slug' => 'privilegios.index', 'descripcion' => 'Ver listado de privilegios'],
            ['nombre' => 'Crear privilegio', 'slug' => 'privilegios.create', 'descripcion' => 'Formulario para crear privilegio'],
            ['nombre' => 'Guardar privilegio', 'slug' => 'privilegios.store', 'descripcion' => 'Registrar nuevo privilegio'],
            ['nombre' => 'Editar privilegio', 'slug' => 'privilegios.edit', 'descripcion' => 'Formulario para editar privilegio'],
            ['nombre' => 'Actualizar privilegio', 'slug' => 'privilegios.update', 'descripcion' => 'Actualizar privilegio'],
            ['nombre' => 'Activar/Desactivar privilegio', 'slug' => 'privilegios.toggle', 'descripcion' => 'Cambiar estado del privilegio'],
            ['nombre' => 'Ver roles', 'slug' => 'roles.index', 'descripcion' => 'Ver listado de roles'],
            ['nombre' => 'Crear rol', 'slug' => 'roles.create', 'descripcion' => 'Formulario para crear rol'],
            ['nombre' => 'Guardar rol', 'slug' => 'roles.store', 'descripcion' => 'Registrar nuevo rol'],
            ['nombre' => 'Ver detalle rol', 'slug' => 'roles.show', 'descripcion' => 'Ver detalle de rol'],
            ['nombre' => 'Editar rol', 'slug' => 'roles.edit', 'descripcion' => 'Formulario para editar rol'],
            ['nombre' => 'Actualizar rol', 'slug' => 'roles.update', 'descripcion' => 'Actualizar rol'],
            ['nombre' => 'Activar/Desactivar rol', 'slug' => 'roles.toggle', 'descripcion' => 'Cambiar estado del rol'],
            ['nombre' => 'Ver usuarios', 'slug' => 'usuarios.index', 'descripcion' => 'Ver listado de usuarios'],
            ['nombre' => 'Crear usuario', 'slug' => 'usuarios.create', 'descripcion' => 'Formulario para crear usuario'],
            ['nombre' => 'Guardar usuario', 'slug' => 'usuarios.store', 'descripcion' => 'Registrar nuevo usuario'],
            ['nombre' => 'Ver detalle usuario', 'slug' => 'usuarios.show', 'descripcion' => 'Ver detalle de usuario'],
            ['nombre' => 'Editar usuario', 'slug' => 'usuarios.edit', 'descripcion' => 'Formulario para editar usuario'],
            ['nombre' => 'Actualizar usuario', 'slug' => 'usuarios.update', 'descripcion' => 'Actualizar usuario'],
            ['nombre' => 'Activar/Desactivar usuario', 'slug' => 'usuarios.toggle', 'descripcion' => 'Cambiar estado del usuario'],
            ['nombre' => 'Resetear contraseña', 'slug' => 'usuarios.reset', 'descripcion' => 'Resetear contraseña de usuario'],
            ['nombre' => 'Resetear 2FA', 'slug' => 'usuarios.reset_f2a', 'descripcion' => 'Resetear autenticación de dos factores'],
            ['nombre' => 'Ver clientes', 'slug' => 'clientes.index', 'descripcion' => 'Ver listado de clientes'],
            ['nombre' => 'Crear cliente', 'slug' => 'clientes.create', 'descripcion' => 'Formulario para crear cliente'],
            ['nombre' => 'Guardar cliente', 'slug' => 'clientes.store', 'descripcion' => 'Registrar nuevo cliente'],
            ['nombre' => 'Ver detalle cliente', 'slug' => 'clientes.show', 'descripcion' => 'Ver detalle de cliente'],
            ['nombre' => 'Editar cliente', 'slug' => 'clientes.edit', 'descripcion' => 'Formulario para editar cliente'],
            ['nombre' => 'Actualizar cliente', 'slug' => 'clientes.update', 'descripcion' => 'Actualizar cliente'],
            ['nombre' => 'Eliminar cliente', 'slug' => 'clientes.destroy', 'descripcion' => 'Eliminar cliente'],
            ['nombre' => 'Canjear puntos cliente', 'slug' => 'clientes.canjear', 'descripcion' => 'Canjear puntos del cliente'],
            ['nombre' => 'Ver categorias', 'slug' => 'categorias.index', 'descripcion' => 'Ver listado de categorias'],
            ['nombre' => 'Crear categoria', 'slug' => 'categorias.create', 'descripcion' => 'Formulario para crear categoria'],
            ['nombre' => 'Guardar categoria', 'slug' => 'categorias.store', 'descripcion' => 'Registrar nueva categoria'],
            ['nombre' => 'Ver detalle categoria', 'slug' => 'categorias.show', 'descripcion' => 'Ver detalle de categoria'],
            ['nombre' => 'Editar categoria', 'slug' => 'categorias.edit', 'descripcion' => 'Formulario para editar categoria'],
            ['nombre' => 'Actualizar categoria', 'slug' => 'categorias.update', 'descripcion' => 'Actualizar categoria'],
            ['nombre' => 'Eliminar categoria', 'slug' => 'categorias.destroy', 'descripcion' => 'Eliminar categoria'],
            ['nombre' => 'Ver productos', 'slug' => 'productos.index', 'descripcion' => 'Ver listado de productos'],
            ['nombre' => 'Crear producto', 'slug' => 'productos.create', 'descripcion' => 'Formulario para crear producto'],
            ['nombre' => 'Guardar producto', 'slug' => 'productos.store', 'descripcion' => 'Registrar nuevo producto'],
            ['nombre' => 'Ver detalle producto', 'slug' => 'productos.show', 'descripcion' => 'Ver detalle de producto'],
            ['nombre' => 'Editar producto', 'slug' => 'productos.edit', 'descripcion' => 'Formulario para editar producto'],
            ['nombre' => 'Actualizar producto', 'slug' => 'productos.update', 'descripcion' => 'Actualizar producto'],
            ['nombre' => 'Activar/Desactivar producto', 'slug' => 'productos.toggle', 'descripcion' => 'Cambiar estado del producto'],
            ['nombre' => 'Ver tipos de pago', 'slug' => 'tipo_pagos.index', 'descripcion' => 'Ver listado de tipos de pago'],
            ['nombre' => 'Crear tipo de pago', 'slug' => 'tipo_pagos.create', 'descripcion' => 'Formulario para crear tipo de pago'],
            ['nombre' => 'Guardar tipo de pago', 'slug' => 'tipo_pagos.store', 'descripcion' => 'Registrar nuevo tipo de pago'],
            ['nombre' => 'Editar tipo de pago', 'slug' => 'tipo_pagos.edit', 'descripcion' => 'Formulario para editar tipo de pago'],
            ['nombre' => 'Actualizar tipo de pago', 'slug' => 'tipo_pagos.update', 'descripcion' => 'Actualizar tipo de pago'],
            ['nombre' => 'Activar/Desactivar tipo de pago', 'slug' => 'tipo_pagos.toggle', 'descripcion' => 'Cambiar estado del tipo de pago'],
            ['nombre' => 'Ver festividades', 'slug' => 'festividades.index', 'descripcion' => 'Ver listado de festividades'],
            ['nombre' => 'Crear festividad', 'slug' => 'festividades.create', 'descripcion' => 'Formulario para crear festividad'],
            ['nombre' => 'Guardar festividad', 'slug' => 'festividades.store', 'descripcion' => 'Registrar nueva festividad'],
            ['nombre' => 'Editar festividad', 'slug' => 'festividades.edit', 'descripcion' => 'Formulario para editar festividad'],
            ['nombre' => 'Actualizar festividad', 'slug' => 'festividades.update', 'descripcion' => 'Actualizar festividad'],
            ['nombre' => 'Eliminar festividad', 'slug' => 'festividades.destroy', 'descripcion' => 'Eliminar festividad'],
            ['nombre' => 'Ver promociones', 'slug' => 'promociones.index', 'descripcion' => 'Ver listado de promociones'],
            ['nombre' => 'Crear promocion', 'slug' => 'promociones.create', 'descripcion' => 'Formulario para crear promocion'],
            ['nombre' => 'Guardar promocion', 'slug' => 'promociones.store', 'descripcion' => 'Registrar nueva promocion'],
            ['nombre' => 'Ver detalle promocion', 'slug' => 'promociones.show', 'descripcion' => 'Ver detalle de promocion'],
            ['nombre' => 'Editar promocion', 'slug' => 'promociones.edit', 'descripcion' => 'Formulario para editar promocion'],
            ['nombre' => 'Actualizar promocion', 'slug' => 'promociones.update', 'descripcion' => 'Actualizar promocion'],
            ['nombre' => 'Eliminar promocion', 'slug' => 'promociones.destroy', 'descripcion' => 'Eliminar promocion'],
            ['nombre' => 'Ver ventas', 'slug' => 'ventas.index', 'descripcion' => 'Ver listado de ventas'],
            ['nombre' => 'Crear venta', 'slug' => 'ventas.create', 'descripcion' => 'Formulario para crear venta'],
            ['nombre' => 'Guardar venta', 'slug' => 'ventas.store', 'descripcion' => 'Registrar nueva venta'],
            ['nombre' => 'Ver detalle venta', 'slug' => 'ventas.show', 'descripcion' => 'Ver detalle de venta'],
            ['nombre' => 'Actualizar venta', 'slug' => 'ventas.update', 'descripcion' => 'Actualizar venta'],
            ['nombre' => 'Eliminar venta', 'slug' => 'ventas.destroy', 'descripcion' => 'Eliminar venta'],
            ['nombre' => 'Buscar cliente en venta', 'slug' => 'ventas.searchClient', 'descripcion' => 'Buscar cliente para venta'],
            ['nombre' => 'Agregar producto a venta', 'slug' => 'ventas.addProducto', 'descripcion' => 'Agregar producto a la venta'],
            ['nombre' => 'Eliminar producto de venta', 'slug' => 'ventas.removeProducto', 'descripcion' => 'Eliminar producto de la venta'],
            ['nombre' => 'Calcular total venta', 'slug' => 'ventas.getTotalCompra', 'descripcion' => 'Calcular total de la compra'],
            ['nombre' => 'Aplicar promoción', 'slug' => 'ventas.setPromocion', 'descripcion' => 'Aplicar promoción a la venta'],
            ['nombre' => 'Obtener puntos cliente', 'slug' => 'ventas.getPuntosCliente', 'descripcion' => 'Consultar puntos del cliente'],
            ['nombre' => 'Usar puntos cliente', 'slug' => 'ventas.setUsoPuntos', 'descripcion' => 'Aplicar uso de puntos'],
            ['nombre' => 'Ver reportes', 'slug' => 'reportes.index', 'descripcion' => 'Acceso a reportes'],
            ['nombre' => 'Reporte ventas', 'slug' => 'reportes.ventas', 'descripcion' => 'Ver reporte de ventas'],
            ['nombre' => 'Exportar ventas PDF', 'slug' => 'reportes.ventas.pdf', 'descripcion' => 'Exportar reporte de ventas en PDF'],
            ['nombre' => 'Reporte usuarios', 'slug' => 'reportes.usuarios', 'descripcion' => 'Ver reporte de usuarios'],
            ['nombre' => 'Exportar usuarios PDF', 'slug' => 'reportes.usuarios.pdf', 'descripcion' => 'Exportar reporte de usuarios en PDF'],
            ['nombre' => 'Reporte productos', 'slug' => 'reportes.productos', 'descripcion' => 'Ver reporte de productos'],
            ['nombre' => 'Exportar productos PDF', 'slug' => 'reportes.productos.pdf', 'descripcion' => 'Exportar reporte de productos en PDF'],
        ];



        foreach ($privilegios as $privilegio) {
            DB::table('privilegios')->insert([
                ...$privilegio,
                'estado' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $totalPrivilegios = DB::table('privilegios')->count();
        for ($i = 1; $i <= $totalPrivilegios; $i++) {
            DB::table('rol_privilegio')->insert([
                'rol_id'       => 1,
                'privilegio_id' => $i,
                'created_at'   => now(),
                'updated_at'   => now(),
            ]);
        }
    }
}