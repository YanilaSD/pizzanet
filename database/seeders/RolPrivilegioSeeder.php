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
            ['nombre' => 'Cajero', 'descripcion' => 'Realiza la gestión de ventas y clientes', 'estado' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'Encargado del negocio', 'descripcion' => 'Encargado de la gestión de festividades, clientes, reportes, promociones y categorías', 'estado' => 1, 'created_at' => now(), 'updated_at' => now()],
        ]);

        $privilegios = [
            ['nombre' => 'Ver privilegios', 'slug' => 'ver-privilegios', 'descripcion' => 'Ver listado de privilegios'],
            ['nombre' => 'Crear privilegio', 'slug' => 'crear-privilegio', 'descripcion' => 'Formulario para crear privilegio'],
            ['nombre' => 'Guardar privilegio', 'slug' => 'guardar-privilegio', 'descripcion' => 'Registrar nuevo privilegio'],
            ['nombre' => 'Editar privilegio', 'slug' => 'editar-privilegio', 'descripcion' => 'Formulario para editar privilegio'],
            ['nombre' => 'Actualizar privilegio', 'slug' => 'actualizar-privilegio', 'descripcion' => 'Actualizar privilegio'],
            ['nombre' => 'Activar/Desactivar privilegio', 'slug' => 'activar-desactivar-privilegio', 'descripcion' => 'Cambiar estado del privilegio'],
            ['nombre' => 'Ver roles', 'slug' => 'ver-roles', 'descripcion' => 'Ver listado de roles'],
            ['nombre' => 'Crear rol', 'slug' => 'crear-rol', 'descripcion' => 'Formulario para crear rol'],
            ['nombre' => 'Guardar rol', 'slug' => 'guardar-rol', 'descripcion' => 'Registrar nuevo rol'],
            ['nombre' => 'Ver detalle rol', 'slug' => 'ver-detalle-rol', 'descripcion' => 'Ver detalle de rol'],
            ['nombre' => 'Editar rol', 'slug' => 'editar-rol', 'descripcion' => 'Formulario para editar rol'],
            ['nombre' => 'Actualizar rol', 'slug' => 'actualizar-rol', 'descripcion' => 'Actualizar rol'],
            ['nombre' => 'Activar/Desactivar rol', 'slug' => 'activar-desactivar-rol', 'descripcion' => 'Cambiar estado del rol'],
            ['nombre' => 'Ver usuarios', 'slug' => 'ver-usuarios', 'descripcion' => 'Ver listado de usuarios'],
            ['nombre' => 'Crear usuario', 'slug' => 'crear-usuario', 'descripcion' => 'Formulario para crear usuario'],
            ['nombre' => 'Guardar usuario', 'slug' => 'guardar-usuario', 'descripcion' => 'Registrar nuevo usuario'],
            ['nombre' => 'Ver detalle usuario', 'slug' => 'ver-detalle-usuario', 'descripcion' => 'Ver detalle de usuario'],
            ['nombre' => 'Editar usuario', 'slug' => 'editar-usuario', 'descripcion' => 'Formulario para editar usuario'],
            ['nombre' => 'Actualizar usuario', 'slug' => 'actualizar-usuario', 'descripcion' => 'Actualizar usuario'],
            ['nombre' => 'Activar/Desactivar usuario', 'slug' => 'activar-desactivar-usuario', 'descripcion' => 'Cambiar estado del usuario'],
            ['nombre' => 'Resetear contraseña', 'slug' => 'resetear-contrasena', 'descripcion' => 'Resetear contraseña de usuario'],
            ['nombre' => 'Resetear 2FA', 'slug' => 'resetear-2fa', 'descripcion' => 'Resetear autenticación de dos factores'],
            ['nombre' => 'Ver clientes', 'slug' => 'ver-clientes', 'descripcion' => 'Ver listado de clientes'],
            ['nombre' => 'Crear cliente', 'slug' => 'crear-cliente', 'descripcion' => 'Formulario para crear cliente'],
            ['nombre' => 'Guardar cliente', 'slug' => 'guardar-cliente', 'descripcion' => 'Registrar nuevo cliente'],
            ['nombre' => 'Ver detalle cliente', 'slug' => 'ver-detalle-cliente', 'descripcion' => 'Ver detalle de cliente'],
            ['nombre' => 'Editar cliente', 'slug' => 'editar-cliente', 'descripcion' => 'Formulario para editar cliente'],
            ['nombre' => 'Actualizar cliente', 'slug' => 'actualizar-cliente', 'descripcion' => 'Actualizar cliente'],
            ['nombre' => 'Eliminar cliente', 'slug' => 'eliminar-cliente', 'descripcion' => 'Eliminar cliente'],
            ['nombre' => 'Canjear puntos cliente', 'slug' => 'canjear-puntos-cliente', 'descripcion' => 'Canjear puntos del cliente'],
            ['nombre' => 'Ver categorias', 'slug' => 'ver-categorias', 'descripcion' => 'Ver listado de categorias'],
            ['nombre' => 'Crear categoria', 'slug' => 'crear-categoria', 'descripcion' => 'Formulario para crear categoria'],
            ['nombre' => 'Guardar categoria', 'slug' => 'guardar-categoria', 'descripcion' => 'Registrar nueva categoria'],
            ['nombre' => 'Ver detalle categoria', 'slug' => 'ver-detalle-categoria', 'descripcion' => 'Ver detalle de categoria'],
            ['nombre' => 'Editar categoria', 'slug' => 'editar-categoria', 'descripcion' => 'Formulario para editar categoria'],
            ['nombre' => 'Actualizar categoria', 'slug' => 'actualizar-categoria', 'descripcion' => 'Actualizar categoria'],
            ['nombre' => 'Eliminar categoria', 'slug' => 'eliminar-categoria', 'descripcion' => 'Eliminar categoria'],
            ['nombre' => 'Ver productos', 'slug' => 'ver-productos', 'descripcion' => 'Ver listado de productos'],
            ['nombre' => 'Crear producto', 'slug' => 'crear-producto', 'descripcion' => 'Formulario para crear producto'],
            ['nombre' => 'Guardar producto', 'slug' => 'guardar-producto', 'descripcion' => 'Registrar nuevo producto'],
            ['nombre' => 'Ver detalle producto', 'slug' => 'ver-detalle-producto', 'descripcion' => 'Ver detalle de producto'],
            ['nombre' => 'Editar producto', 'slug' => 'editar-producto', 'descripcion' => 'Formulario para editar producto'],
            ['nombre' => 'Actualizar producto', 'slug' => 'actualizar-producto', 'descripcion' => 'Actualizar producto'],
            ['nombre' => 'Activar/Desactivar producto', 'slug' => 'activar-desactivar-producto', 'descripcion' => 'Cambiar estado del producto'],
            ['nombre' => 'Ver tipos de pago', 'slug' => 'ver-tipos-de-pago', 'descripcion' => 'Ver listado de tipos de pago'],
            ['nombre' => 'Crear tipo de pago', 'slug' => 'crear-tipo-de-pago', 'descripcion' => 'Formulario para crear tipo de pago'],
            ['nombre' => 'Guardar tipo de pago', 'slug' => 'guardar-tipo-de-pago', 'descripcion' => 'Registrar nuevo tipo de pago'],
            ['nombre' => 'Editar tipo de pago', 'slug' => 'editar-tipo-de-pago', 'descripcion' => 'Formulario para editar tipo de pago'],
            ['nombre' => 'Actualizar tipo de pago', 'slug' => 'actualizar-tipo-de-pago', 'descripcion' => 'Actualizar tipo de pago'],
            ['nombre' => 'Activar/Desactivar tipo de pago', 'slug' => 'activar-desactivar-tipo-de-pago', 'descripcion' => 'Cambiar estado del tipo de pago'],
            ['nombre' => 'Eliminar tipo de pago', 'slug' => 'eliminar-tipo-de-pago', 'descripcion' => 'Eliminar tipo de pago'],
            ['nombre' => 'Ver festividades', 'slug' => 'ver-festividades', 'descripcion' => 'Ver listado de festividades'],
            ['nombre' => 'Crear festividad', 'slug' => 'crear-festividad', 'descripcion' => 'Formulario para crear festividad'],
            ['nombre' => 'Guardar festividad', 'slug' => 'guardar-festividad', 'descripcion' => 'Registrar nueva festividad'],
            ['nombre' => 'Editar festividad', 'slug' => 'editar-festividad', 'descripcion' => 'Formulario para editar festividad'],
            ['nombre' => 'Actualizar festividad', 'slug' => 'actualizar-festividad', 'descripcion' => 'Actualizar festividad'],
            ['nombre' => 'Eliminar festividad', 'slug' => 'eliminar-festividad', 'descripcion' => 'Eliminar festividad'],
            ['nombre' => 'Ver promociones', 'slug' => 'ver-promociones', 'descripcion' => 'Ver listado de promociones'],
            ['nombre' => 'Crear promocion', 'slug' => 'crear-promocion', 'descripcion' => 'Formulario para crear promocion'],
            ['nombre' => 'Guardar promocion', 'slug' => 'guardar-promocion', 'descripcion' => 'Registrar nueva promocion'],
            ['nombre' => 'Ver detalle promocion', 'slug' => 'ver-detalle-promocion', 'descripcion' => 'Ver detalle de promocion'],
            ['nombre' => 'Editar promocion', 'slug' => 'editar-promocion', 'descripcion' => 'Formulario para editar promocion'],
            ['nombre' => 'Actualizar promocion', 'slug' => 'actualizar-promocion', 'descripcion' => 'Actualizar promocion'],
            ['nombre' => 'Eliminar promocion', 'slug' => 'eliminar-promocion', 'descripcion' => 'Eliminar promocion'],
            ['nombre' => 'Ver ventas', 'slug' => 'ver-ventas', 'descripcion' => 'Ver listado de ventas'],
            ['nombre' => 'Crear venta', 'slug' => 'crear-venta', 'descripcion' => 'Formulario para crear venta'],
            ['nombre' => 'Guardar venta', 'slug' => 'guardar-venta', 'descripcion' => 'Registrar nueva venta'],
            ['nombre' => 'Ver detalle venta', 'slug' => 'ver-detalle-venta', 'descripcion' => 'Ver detalle de venta'],
            ['nombre' => 'Actualizar venta', 'slug' => 'actualizar-venta', 'descripcion' => 'Actualizar venta'],
            ['nombre' => 'Eliminar venta', 'slug' => 'eliminar-venta', 'descripcion' => 'Eliminar venta'],
            ['nombre' => 'Buscar cliente en venta', 'slug' => 'buscar-cliente-en-venta', 'descripcion' => 'Buscar cliente para venta'],
            ['nombre' => 'Agregar producto a venta', 'slug' => 'agregar-producto-a-venta', 'descripcion' => 'Agregar producto a la venta'],
            ['nombre' => 'Eliminar producto de venta', 'slug' => 'eliminar-producto-de-venta', 'descripcion' => 'Eliminar producto de la venta'],
            ['nombre' => 'Calcular total venta', 'slug' => 'calcular-total-venta', 'descripcion' => 'Calcular total de la compra'],
            ['nombre' => 'Aplicar promoción', 'slug' => 'aplicar-promocion', 'descripcion' => 'Aplicar promoción a la venta'],
            ['nombre' => 'Obtener puntos cliente', 'slug' => 'obtener-puntos-cliente', 'descripcion' => 'Consultar puntos del cliente'],
            ['nombre' => 'Usar puntos cliente', 'slug' => 'usar-puntos-cliente', 'descripcion' => 'Aplicar uso de puntos'],
            ['nombre' => 'Ver reportes', 'slug' => 'ver-reportes', 'descripcion' => 'Acceso a reportes'],
            ['nombre' => 'Reporte ventas', 'slug' => 'reporte-ventas', 'descripcion' => 'Ver reporte de ventas'],
            ['nombre' => 'Exportar ventas PDF', 'slug' => 'exportar-ventas-pdf', 'descripcion' => 'Exportar reporte de ventas en PDF'],
            ['nombre' => 'Reporte usuarios', 'slug' => 'reporte-usuarios', 'descripcion' => 'Ver reporte de usuarios'],
            ['nombre' => 'Exportar usuarios PDF', 'slug' => 'exportar-usuarios-pdf', 'descripcion' => 'Exportar reporte de usuarios en PDF'],
            ['nombre' => 'Reporte productos', 'slug' => 'reporte-productos', 'descripcion' => 'Ver reporte de productos'],
            ['nombre' => 'Exportar productos PDF', 'slug' => 'exportar-productos-pdf', 'descripcion' => 'Exportar reporte de productos en PDF'],
        ];

        foreach ($privilegios as $privilegio) {
            DB::table('privilegios')->insert([
                ...$privilegio,
                'estado' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Asignar todos los privilegios existentes al Administrador.
        $adminId = DB::table('roles')
            ->where('nombre', 'Administrador')
            ->value('id');

        $todosLosPrivilegios = DB::table('privilegios')->pluck('id');

        foreach ($todosLosPrivilegios as $privilegioId) {
            DB::table('rol_privilegio')->updateOrInsert(
                [
                    'rol_id' => $adminId,
                    'privilegio_id' => $privilegioId,
                ],
                [
                    'updated_at' => now(),
                    'created_at' => now(),
                ]
            );
        }

        $cajeroSlugs = [
            'ver-ventas',
            'crear-venta',
            'guardar-venta',
            'ver-detalle-venta',
            'actualizar-venta',
            'buscar-cliente-en-venta',
            'agregar-producto-a-venta',
            'eliminar-producto-de-venta',
            'calcular-total-venta',
            'aplicar-promocion',
            'consultar-puntos-del-cliente',
            'aplicar-uso-de-puntos',

            'ver-clientes',
            'crear-cliente',
            'registrar-cliente',
            'ver-detalle-cliente',
            'editar-cliente',
            'actualizar-cliente',
            'canjear-puntos-cliente',

            'ver-promociones',
        ];

        $encargadoNegocioSlugs = [
            'ver-festividades',
            'crear-festividad',
            'guardar-festividad',
            'editar-festividad',
            'actualizar-festividad',
            'eliminar-festividad',

            'ver-clientes',
            'crear-cliente',
            'registrar-cliente',
            'ver-detalle-cliente',
            'editar-cliente',
            'actualizar-cliente',
            'eliminar-cliente',
            'canjear-puntos-cliente',

            'ver-reportes',
            'reporte-de-ventas',
            'exportar-ventas-en-pdf',
            'reporte-de-usuarios',
            'exportar-usuarios-en-pdf',
            'reporte-de-productos',
            'exportar-productos-en-pdf',

            'ver-promociones',
            'crear-promocion',
            'guardar-promocion',
            'ver-detalle-promocion',
            'editar-promocion',
            'actualizar-promocion',
            'eliminar-promocion',

            'ver-categorias',
            'crear-categoria',
            'guardar-categoria',
            'ver-detalle-categoria',
            'editar-categoria',
            'actualizar-categoria',
            'eliminar-categoria',
        ];

        $this->asignarPrivilegiosPorSlug('Cajero', $cajeroSlugs);
        $this->asignarPrivilegiosPorSlug('Encargado del negocio', $encargadoNegocioSlugs);
    }

    private function asignarPrivilegiosPorSlug(string $rolNombre, array $slugs): void
    {
        $rolId = DB::table('roles')->where('nombre', $rolNombre)->value('id');

        $privilegioIds = DB::table('privilegios')->whereIn('slug', $slugs)->pluck('id');

        foreach ($privilegioIds as $privilegioId) {
            DB::table('rol_privilegio')->insert([
                'rol_id'        => $rolId,
                'privilegio_id' => $privilegioId,
                'created_at'    => now(),
                'updated_at'    => now(),
            ]);
        }
    }
}