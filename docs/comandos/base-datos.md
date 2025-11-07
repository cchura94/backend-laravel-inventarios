```
php artisan make:model Sucursal -m
php artisan make:model Almacen -m

php artisan make:migration create_almacen_producto_table
php artisan make:migration create_sucursal_usuario_table

php artisan make:model Permiso -m

php artisan make:migration create_permiso_role_table

php artisan make:model Cliente -m

php artisan make:model Nota -m

php artisan make:migration create_movimiento_table
```