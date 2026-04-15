<?php

/**
 * =============================================================
 *  SOLUCION 07: NAMESPACES Y COMPOSER
 * =============================================================
 *  Esta solucion muestra el contenido de cada archivo.
 *  Para que funcione de verdad, debes crear los archivos
 *  en la estructura de carpetas indicada.
 * =============================================================
 */

echo "=== CONTENIDO DE CADA ARCHIVO ===" . PHP_EOL;
echo PHP_EOL;

echo "--- composer.json ---" . PHP_EOL;
echo <<<'JSON'
{
    "name": "jesus/tienda",
    "type": "project",
    "autoload": {
        "psr-4": {
            "Tienda\\": "src/"
        }
    },
    "require": {
        "php": ">=8.1"
    }
}
JSON;
echo PHP_EOL . PHP_EOL;

echo "--- src/Modelos/Producto.php ---" . PHP_EOL;
echo <<<'CODE'
<?php

namespace Tienda\Modelos;

class Producto {
    public function __construct(
        public readonly string $nombre,
        public readonly float $precio,
        public int $stock = 0,
    ) {}

    public function tieneStock(): bool {
        return $this->stock > 0;
    }

    public function __toString(): string {
        $disponible = $this->tieneStock() ? "En stock ({$this->stock})" : "Agotado";
        return "{$this->nombre} - {$this->precio} EUR - $disponible";
    }
}
CODE;
echo PHP_EOL . PHP_EOL;

echo "--- src/Modelos/Usuario.php ---" . PHP_EOL;
echo <<<'CODE'
<?php

namespace Tienda\Modelos;

class Usuario {
    private array $carrito = [];

    public function __construct(
        public readonly string $nombre,
        public readonly string $email,
    ) {}

    public function agregarAlCarrito(Producto $producto): void {
        if ($producto->tieneStock()) {
            $this->carrito[] = $producto;
            $producto->stock--;
            echo "Agregado '{$producto->nombre}' al carrito de {$this->nombre}" . PHP_EOL;
        } else {
            echo "'{$producto->nombre}' no tiene stock" . PHP_EOL;
        }
    }

    public function verCarrito(): void {
        echo "Carrito de {$this->nombre}:" . PHP_EOL;
        if (empty($this->carrito)) {
            echo "  (vacio)" . PHP_EOL;
            return;
        }
        foreach ($this->carrito as $i => $producto) {
            echo "  " . ($i + 1) . ". {$producto->nombre} - {$producto->precio} EUR" . PHP_EOL;
        }
    }

    public function totalCarrito(): float {
        $total = 0;
        foreach ($this->carrito as $producto) {
            $total += $producto->precio;
        }
        return $total;
    }
}
CODE;
echo PHP_EOL . PHP_EOL;

echo "--- src/Servicios/Tienda.php ---" . PHP_EOL;
echo <<<'CODE'
<?php

namespace Tienda\Servicios;

use Tienda\Modelos\Producto;

class Tienda {
    private array $productos = [];

    public function agregarProducto(Producto $producto): void {
        $this->productos[] = $producto;
    }

    public function buscarProducto(string $nombre): ?Producto {
        foreach ($this->productos as $producto) {
            if (strtolower($producto->nombre) === strtolower($nombre)) {
                return $producto;
            }
        }
        return null;
    }

    public function listarProductos(): void {
        echo "Catalogo de la tienda:" . PHP_EOL;
        foreach ($this->productos as $producto) {
            echo "  - $producto" . PHP_EOL;
        }
    }
}
CODE;
echo PHP_EOL . PHP_EOL;

echo "--- index.php ---" . PHP_EOL;
echo <<<'CODE'
<?php

require __DIR__ . '/vendor/autoload.php';

use Tienda\Modelos\Producto;
use Tienda\Modelos\Usuario;
use Tienda\Servicios\Tienda;

// Crear la tienda
$tienda = new Tienda();
$tienda->agregarProducto(new Producto("Camiseta", 19.99, 5));
$tienda->agregarProducto(new Producto("Pantalon", 39.99, 3));
$tienda->agregarProducto(new Producto("Zapatillas", 79.99, 2));

// Listar productos
$tienda->listarProductos();
echo PHP_EOL;

// Crear usuario y comprar
$usuario = new Usuario("Jesus", "jesus@ejemplo.com");

$camiseta = $tienda->buscarProducto("Camiseta");
$zapatillas = $tienda->buscarProducto("Zapatillas");

if ($camiseta) $usuario->agregarAlCarrito($camiseta);
if ($zapatillas) $usuario->agregarAlCarrito($zapatillas);

echo PHP_EOL;
$usuario->verCarrito();
echo "Total: " . number_format($usuario->totalCarrito(), 2) . " EUR" . PHP_EOL;
CODE;
echo PHP_EOL . PHP_EOL;

echo "--- Comandos para configurar ---" . PHP_EOL;
echo "cd 07-namespaces-y-composer/proyecto" . PHP_EOL;
echo "composer init --name='jesus/tienda' --type=project --no-interaction" . PHP_EOL;
echo "(editar composer.json para agregar el autoload psr-4)" . PHP_EOL;
echo "composer dump-autoload" . PHP_EOL;
echo "php index.php" . PHP_EOL;
