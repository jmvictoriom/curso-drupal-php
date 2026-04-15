<?php

/**
 * =============================================================
 *  LECCION 06: CLASES Y OBJETOS (OOP)
 * =============================================================
 *  Drupal usa Programacion Orientada a Objetos intensivamente.
 *  Controllers, Services, Plugins, Entities... todo son clases.
 *  Esta leccion es CRITICA para entender Drupal.
 * =============================================================
 */

echo "=== 1. CLASES Y OBJETOS BASICOS ===" . PHP_EOL;

// Una CLASE es un plano/molde. Un OBJETO es una instancia de ese plano.
// Piensa: Clase = "Receta de galletas", Objeto = "una galleta concreta"

class Coche {
    // Propiedades (datos del objeto)
    public string $marca;
    public string $color;
    public int $velocidad = 0;

    // Metodos (acciones del objeto)
    public function acelerar(int $cantidad): void {
        $this->velocidad += $cantidad;
        echo "{$this->marca} acelera a {$this->velocidad} km/h" . PHP_EOL;
    }

    public function frenar(): void {
        $this->velocidad = 0;
        echo "{$this->marca} se detiene" . PHP_EOL;
    }
}

// Crear objetos con "new"
$mi_coche = new Coche();
$mi_coche->marca = "Toyota";
$mi_coche->color = "Rojo";
$mi_coche->acelerar(60);
$mi_coche->acelerar(40);
$mi_coche->frenar();

// Otro objeto de la misma clase
$otro_coche = new Coche();
$otro_coche->marca = "BMW";
$otro_coche->acelerar(120);
echo PHP_EOL;


echo "=== 2. CONSTRUCTOR ===" . PHP_EOL;

// El constructor se ejecuta automaticamente al crear el objeto con new
class Usuario {
    public string $nombre;
    public string $email;
    public string $rol;

    // __construct es el constructor
    public function __construct(string $nombre, string $email, string $rol = "usuario") {
        $this->nombre = $nombre;
        $this->email = $email;
        $this->rol = $rol;
    }

    public function info(): string {
        return "{$this->nombre} ({$this->email}) - Rol: {$this->rol}";
    }
}

$user1 = new Usuario("Jesus", "jesus@ejemplo.com");
$user2 = new Usuario("Ana", "ana@ejemplo.com", "admin");
echo $user1->info() . PHP_EOL;
echo $user2->info() . PHP_EOL;
echo PHP_EOL;

// Constructor promotion (PHP 8+) — forma corta y moderna
// Las propiedades se declaran directamente en el constructor
class Producto {
    public function __construct(
        public string $nombre,
        public float $precio,
        public int $stock = 0,
    ) {}

    public function __toString(): string {
        return "{$this->nombre} - {$this->precio} EUR ({$this->stock} uds)";
    }
}

$prod = new Producto("Teclado", 49.99, 15);
echo $prod . PHP_EOL;  // usa __toString automaticamente
echo PHP_EOL;


echo "=== 3. VISIBILIDAD: PUBLIC, PRIVATE, PROTECTED ===" . PHP_EOL;

// public    — accesible desde cualquier sitio
// private   — solo accesible dentro de la propia clase
// protected — accesible desde la clase y sus hijas (herencia)

class CuentaBancaria {
    private float $saldo;  // nadie puede tocar el saldo directamente

    public function __construct(float $saldo_inicial) {
        $this->saldo = $saldo_inicial;
    }

    public function depositar(float $cantidad): void {
        if ($cantidad > 0) {
            $this->saldo += $cantidad;
            echo "Depositado: $cantidad. Saldo: {$this->saldo}" . PHP_EOL;
        }
    }

    public function retirar(float $cantidad): void {
        if ($cantidad > 0 && $cantidad <= $this->saldo) {
            $this->saldo -= $cantidad;
            echo "Retirado: $cantidad. Saldo: {$this->saldo}" . PHP_EOL;
        } else {
            echo "Fondos insuficientes" . PHP_EOL;
        }
    }

    public function getSaldo(): float {
        return $this->saldo;
    }
}

$cuenta = new CuentaBancaria(1000);
$cuenta->depositar(500);
$cuenta->retirar(200);
// $cuenta->saldo = 999999;  // ERROR: saldo es private
echo "Saldo actual: " . $cuenta->getSaldo() . PHP_EOL;
echo PHP_EOL;


echo "=== 4. HERENCIA ===" . PHP_EOL;

// Una clase puede heredar de otra con "extends"
// La clase hija hereda propiedades y metodos de la padre

class Animal {
    public function __construct(
        public string $nombre,
        protected string $sonido = "",
    ) {}

    public function hablar(): string {
        return "{$this->nombre} hace {$this->sonido}";
    }
}

class Perro extends Animal {
    public function __construct(string $nombre) {
        parent::__construct($nombre, "guau");  // llama al constructor padre
    }

    // Puede agregar nuevos metodos
    public function buscar(string $objeto): string {
        return "{$this->nombre} busca el $objeto!";
    }
}

class Gato extends Animal {
    public function __construct(string $nombre) {
        parent::__construct($nombre, "miau");
    }

    // Puede sobreescribir metodos del padre
    public function hablar(): string {
        return "{$this->nombre} dice {$this->sonido} (y te ignora)";
    }
}

$perro = new Perro("Rex");
$gato = new Gato("Misi");
echo $perro->hablar() . PHP_EOL;
echo $perro->buscar("palo") . PHP_EOL;
echo $gato->hablar() . PHP_EOL;
echo PHP_EOL;


echo "=== 5. INTERFACES ===" . PHP_EOL;

// Una interface define QUE metodos debe tener una clase, pero no COMO.
// Es un "contrato". Drupal usa interfaces por todas partes.

interface Almacenable {
    public function guardar(): bool;
    public function eliminar(): bool;
}

interface Exportable {
    public function exportarJson(): string;
}

// Una clase puede implementar multiples interfaces
class Articulo implements Almacenable, Exportable {
    public function __construct(
        public string $titulo,
        public string $contenido,
    ) {}

    public function guardar(): bool {
        echo "Articulo '{$this->titulo}' guardado" . PHP_EOL;
        return true;
    }

    public function eliminar(): bool {
        echo "Articulo '{$this->titulo}' eliminado" . PHP_EOL;
        return true;
    }

    public function exportarJson(): string {
        return json_encode([
            "titulo" => $this->titulo,
            "contenido" => $this->contenido,
        ]);
    }
}

$art = new Articulo("Mi primer post", "Hola mundo desde Drupal");
$art->guardar();
echo "JSON: " . $art->exportarJson() . PHP_EOL;
echo PHP_EOL;


echo "=== 6. CLASES ABSTRACTAS ===" . PHP_EOL;

// Una clase abstracta NO se puede instanciar directamente.
// Sirve como base para otras clases. Puede tener metodos con y sin implementar.

abstract class FormBase {
    abstract public function buildForm(): array;  // las hijas DEBEN implementar esto

    // Metodo con implementacion que las hijas heredan
    public function validate(array $datos): bool {
        return !empty($datos);
    }

    public function submit(array $datos): void {
        if ($this->validate($datos)) {
            echo "Formulario enviado correctamente" . PHP_EOL;
        } else {
            echo "Error: datos vacios" . PHP_EOL;
        }
    }
}

class FormContacto extends FormBase {
    public function buildForm(): array {
        return [
            "nombre" => ["type" => "text", "required" => true],
            "email" => ["type" => "email", "required" => true],
            "mensaje" => ["type" => "textarea", "required" => true],
        ];
    }
}

// $form = new FormBase();  // ERROR: no se puede instanciar una clase abstracta
$form = new FormContacto();
echo "Campos: " . implode(", ", array_keys($form->buildForm())) . PHP_EOL;
$form->submit(["nombre" => "Jesus"]);
echo PHP_EOL;


echo "=== 7. TRAITS ===" . PHP_EOL;

// Un trait es un conjunto de metodos reutilizables que se "inyectan" en clases.
// Resuelven el problema de que PHP no permite herencia multiple.

trait Timestamps {
    private string $created_at;
    private string $updated_at;

    public function setCreatedAt(): void {
        $this->created_at = date('Y-m-d H:i:s');
    }

    public function getCreatedAt(): string {
        return $this->created_at ?? 'no definido';
    }
}

trait SoftDelete {
    private bool $deleted = false;

    public function softDelete(): void {
        $this->deleted = true;
        echo "Marcado como eliminado (soft delete)" . PHP_EOL;
    }

    public function isDeleted(): bool {
        return $this->deleted;
    }
}

class Comentario {
    use Timestamps, SoftDelete;  // usa ambos traits

    public function __construct(public string $texto) {
        $this->setCreatedAt();
    }
}

$comentario = new Comentario("Buen articulo!");
echo "Creado: " . $comentario->getCreatedAt() . PHP_EOL;
echo "Eliminado? " . ($comentario->isDeleted() ? "Si" : "No") . PHP_EOL;
$comentario->softDelete();
echo "Eliminado? " . ($comentario->isDeleted() ? "Si" : "No") . PHP_EOL;
echo PHP_EOL;


echo "=== 8. METODOS Y PROPIEDADES ESTATICAS ===" . PHP_EOL;

// static pertenece a la CLASE, no a un objeto concreto.
// Se accede con :: en vez de ->

class Contador {
    private static int $cuenta = 0;

    public static function incrementar(): void {
        self::$cuenta++;
    }

    public static function getCuenta(): int {
        return self::$cuenta;
    }
}

Contador::incrementar();
Contador::incrementar();
Contador::incrementar();
echo "Cuenta: " . Contador::getCuenta() . PHP_EOL;  // 3
echo PHP_EOL;


echo "=== 9. READONLY (PHP 8.1+) ===" . PHP_EOL;

// readonly: una propiedad que solo se puede asignar una vez

class Configuracion {
    public function __construct(
        public readonly string $nombre_sitio,
        public readonly string $idioma,
    ) {}
}

$config = new Configuracion("Mi Drupal", "es");
echo "Sitio: {$config->nombre_sitio}" . PHP_EOL;
// $config->nombre_sitio = "Otro";  // ERROR: es readonly
echo PHP_EOL;


echo "=== RESUMEN ===" . PHP_EOL;
echo "1. class + new: crear clases y objetos" . PHP_EOL;
echo "2. __construct(): inicializar objetos" . PHP_EOL;
echo "3. public/private/protected: controlar acceso" . PHP_EOL;
echo "4. extends: herencia" . PHP_EOL;
echo "5. interface: contratos (muy usado en Drupal)" . PHP_EOL;
echo "6. abstract: clases base (FormBase en Drupal)" . PHP_EOL;
echo "7. trait: reutilizar codigo entre clases" . PHP_EOL;
echo "8. static: metodos/propiedades de clase" . PHP_EOL;
echo "9. readonly: propiedades inmutables" . PHP_EOL;
