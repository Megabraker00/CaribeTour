
# Artisan
`artisan` es la línea de comandos de Laravel que ayuda a crear migraciones, clases, API, rutas, etc.

- `php artisan list`: Lista todos los comandos disponibles.
- `php artisan serve`: Inicia el servidor para ejecutar Laravel.
- `php artisan make:migration`: Crea un archivo de migración para crear tablas en la BBDD.
- `php artisan make:model Nombre_modelo (en singular) -m --api`: Crea un modelo dentro de `app/Models/` y opcionalmente una migración y un controlador CRUD.
- `php artisan make:Controller Nombre_Controller`: Crea un controlador en `app/Http/Controllers/`.

---

# Estructura de Carpetas

- **app/Models/**: Almacena los modelos.
- **app/Http/Controllers/**: Almacena los controladores.
- **resources/views/**: Almacena las vistas.
- **routes/web.php**: Define las rutas de la aplicación.
- **public/**: Contiene los assets públicos (CSS, JS, imágenes).
- **database/factories/**: Almacena las factorías.
- **database/migrations/**: Almacena las migraciones.
- **database/seeders/**: Almacena los seeders.

---

# Motor de Plantillas

- **`resources/views/`**: Almacena las vistas.
- **`@yield('nombre_bloque')`**: Espacio reservado para contenido de una plantilla hija.
- **`@extends('nombre_plantilla')`**: Hereda de la plantilla especificada.
- **`@section('nombre_bloque') ... @endsection`**: Define el contenido del bloque heredado.

---

# Vistas

- **`php artisan make:view nombre-carpeta/nombre_vista`**: Crea una vista `nombre_vista.blade.php`.

---

# Modelos

- **`php artisan make:model Nombre_modelo (en singular) -m --api`**: Crea un modelo en `app/Models/`.
- **Nombre del modelo diferente al de la tabla**:  
  ```php
  class Category {
      protected $table = 'categories';
  }
  ```

### Relaciones entre Modelos

- **1:N (Uno a muchos)**:  
  ```php
  class Producto {
      public function proveedor() {
          return $this->belongsTo(Proveedor::class, 'fk_producto', 'pk_proveedor');
      }
  }

  class Proveedor {
      public function productos() {
          return $this->hasMany(Producto::class, 'fk_producto', 'pk_proveedor');
      }
  }
  ```

### Insertar Registros  
Ejemplo de inserción en una relación 1:1:  
```php
$product->image()->create(['path' => 'src/product/imagen_1.jpg']);
```

---

# Migraciones

- **`php artisan migrate:status`**: Muestra el estado de las migraciones.
- **`php artisan migrate`**: Ejecuta las migraciones.
- **`php artisan migrate:rollback`**: Deshace la última migración.
- **`php artisan migrate:fresh`**: Rehace todas las migraciones. Solo en entornos de desarrollo.

**Ejemplo de clave primaria compuesta**:  
```php
$table->primary(['tabla1_id', 'tabla2_id']);
```

---

# Seeder

- **`php artisan make:seeder NombreClaseSeeder`**: Crea un seeder.
- **`$this->call(NombreClaseSeeder::class)`**: Ejecuta un seeder desde `DatabaseSeeder.php`.
- **`php artisan db:seed`**: Ejecuta todos los seeders.
- **`php artisan db:seed --class=NombreDelSeeder`**: Ejecuta un seeder específico.
- **`php artisan migrate --seed`**: Ejecuta las migraciones con los seeders.

---

# Factory

Las factorías ayudan a generar datos ficticios. Se pueden usar en seeders para poblar la base de datos con datos dinámicos.

---

# Consultas ORM

- `User::all()`: Recupera todos los registros.
- `User::select('name', 'email')`: Selecciona solo las columnas `name` y `email`.
- `User::where('categoria_id', 1)->get()`: Filtra por categoría.
- `User::find(5)`: Recupera el registro con ID 5.
- `User::orderBy('name', 'desc')->get()`: Ordena por nombre en orden descendente.
- `User::latest()->get()`: Ordena por fecha de creación descendente.

---

# Controlador

- **`routes/web.php`**: Define rutas públicas.
- **`php artisan make:Controller NombreController`**: Crea un controlador.
- **`Route::view('/', 'nombre.vista')`**: Devuelve una vista estática.
- **`Route::get("destinos/{cat}/{subcat?}", ...)`**: Parámetro opcional `subcat`.
- **`Route::ApiResource('clientes', ClienteController::class)`**: Crea todas las rutas RESTful para `ClienteController`.

### Redirecciones

```php
Route::get('/', function() {
    return redirect('/servicios'); // Redirige por URL
    return redirect()->route('service.index'); // Redirige por nombre
    return to_route('service.index'); // Otra forma de redirigir por nombre
});
```

**Listar rutas**:  
`php artisan route:list` o `php artisan r:l`.

### Request

- **Acceso a campos**:  
  ```php
  $request->input('nombre_campo');
  $request->nombre_campo;
  ```

- **Validación**:  
  ```php
  $request->validate([
      'nombre_campo' => 'required',
  ]);
  ```