<?php

/**
 * =============================================================
 *  EJERCICIO 07: NAMESPACES Y COMPOSER
 * =============================================================
 *  Este ejercicio es distinto: vas a crear archivos y carpetas
 *  siguiendo la estructura PSR-4 y usar Composer.
 * =============================================================
 */

echo "=== EJERCICIO 07: PROYECTO CON NAMESPACES ===" . PHP_EOL;
echo PHP_EOL;
echo "Este ejercicio se hace en la terminal, no aqui." . PHP_EOL;
echo "Sigue los pasos en orden:" . PHP_EOL;
echo PHP_EOL;

echo "--- PASO 1: Crear estructura del proyecto ---" . PHP_EOL;
echo "Crea esta estructura de carpetas:" . PHP_EOL;
echo PHP_EOL;
echo "  07-namespaces-y-composer/" . PHP_EOL;
echo "  └── proyecto/" . PHP_EOL;
echo "      ├── src/" . PHP_EOL;
echo "      │   ├── Modelos/" . PHP_EOL;
echo "      │   │   ├── Producto.php" . PHP_EOL;
echo "      │   │   └── Usuario.php" . PHP_EOL;
echo "      │   └── Servicios/" . PHP_EOL;
echo "      │       └── Tienda.php" . PHP_EOL;
echo "      └── index.php" . PHP_EOL;
echo PHP_EOL;
echo "Comando: mkdir -p proyecto/src/Modelos proyecto/src/Servicios" . PHP_EOL;
echo PHP_EOL;

echo "--- PASO 2: Inicializar Composer ---" . PHP_EOL;
echo "Entra en la carpeta proyecto/ y ejecuta:" . PHP_EOL;
echo "  cd proyecto" . PHP_EOL;
echo "  composer init --name='tu-nombre/tienda' --type=project --no-interaction" . PHP_EOL;
echo PHP_EOL;
echo "Luego edita composer.json y agrega el autoload:" . PHP_EOL;
echo '  "autoload": {' . PHP_EOL;
echo '      "psr-4": {' . PHP_EOL;
echo '          "Tienda\\\\": "src/"' . PHP_EOL;
echo '      }' . PHP_EOL;
echo '  }' . PHP_EOL;
echo PHP_EOL;
echo "Despues ejecuta: composer dump-autoload" . PHP_EOL;
echo PHP_EOL;

echo "--- PASO 3: Crear las clases ---" . PHP_EOL;
echo PHP_EOL;

echo "ARCHIVO: src/Modelos/Producto.php" . PHP_EOL;
echo "  - Namespace: Tienda\\Modelos" . PHP_EOL;
echo "  - Clase Producto con: nombre, precio, stock" . PHP_EOL;
echo "  - Metodo tieneStock(): bool" . PHP_EOL;
echo "  - Metodo __toString(): string" . PHP_EOL;
echo PHP_EOL;

echo "ARCHIVO: src/Modelos/Usuario.php" . PHP_EOL;
echo "  - Namespace: Tienda\\Modelos" . PHP_EOL;
echo "  - Clase Usuario con: nombre, email, carrito (array)" . PHP_EOL;
echo "  - Metodo agregarAlCarrito(Producto \$producto): void" . PHP_EOL;
echo "  - Metodo verCarrito(): void" . PHP_EOL;
echo "  - Metodo totalCarrito(): float" . PHP_EOL;
echo PHP_EOL;

echo "ARCHIVO: src/Servicios/Tienda.php" . PHP_EOL;
echo "  - Namespace: Tienda\\Servicios" . PHP_EOL;
echo "  - Clase Tienda con: productos (array)" . PHP_EOL;
echo "  - Metodo agregarProducto(Producto \$producto): void" . PHP_EOL;
echo "  - Metodo buscarProducto(string \$nombre): ?Producto" . PHP_EOL;
echo "  - Metodo listarProductos(): void" . PHP_EOL;
echo PHP_EOL;

echo "--- PASO 4: Crear index.php ---" . PHP_EOL;
echo "En index.php:" . PHP_EOL;
echo "  1. Require el autoloader de Composer" . PHP_EOL;
echo "  2. Importa las clases con 'use'" . PHP_EOL;
echo "  3. Crea una tienda con 3 productos" . PHP_EOL;
echo "  4. Crea un usuario" . PHP_EOL;
echo "  5. Agrega productos al carrito" . PHP_EOL;
echo "  6. Muestra el carrito y el total" . PHP_EOL;
echo PHP_EOL;
echo "  require __DIR__ . '/vendor/autoload.php';" . PHP_EOL;
echo "  use Tienda\\Modelos\\Producto;" . PHP_EOL;
echo "  use Tienda\\Modelos\\Usuario;" . PHP_EOL;
echo "  use Tienda\\Servicios\\Tienda;" . PHP_EOL;
echo PHP_EOL;

echo "--- PASO 5: Ejecutar ---" . PHP_EOL;
echo "  php index.php" . PHP_EOL;
echo PHP_EOL;
echo "Si todo funciona, has completado la Fase 1 del curso!" . PHP_EOL;
echo "Pidele a Claude que revise tu proyecto." . PHP_EOL;
