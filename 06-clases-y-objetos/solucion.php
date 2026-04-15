<?php

/**
 * =============================================================
 *  SOLUCION 06: CLASES Y OBJETOS
 * =============================================================
 */

// EJERCICIO 1
echo "--- Ejercicio 1 ---" . PHP_EOL;

class Libro {
    public bool $leido = false;

    public function __construct(
        public string $titulo,
        public string $autor,
        public int $paginas,
    ) {}

    public function leer(): void {
        $this->leido = true;
        echo "'{$this->titulo}' marcado como leido" . PHP_EOL;
    }

    public function info(): void {
        $estado = $this->leido ? "Leido" : "Pendiente";
        echo "'{$this->titulo}' por {$this->autor} ({$this->paginas} pags) - $estado" . PHP_EOL;
    }
}

$libro1 = new Libro("El Quijote", "Cervantes", 863);
$libro2 = new Libro("1984", "Orwell", 326);
$libro1->leer();
$libro1->info();
$libro2->info();


// EJERCICIO 2
echo PHP_EOL . "--- Ejercicio 2 ---" . PHP_EOL;

class Jugador {
    private int $vida = 100;

    public function __construct(public string $nombre) {}

    public function recibirDanio(int $cantidad): void {
        $this->vida = max(0, $this->vida - $cantidad);
        echo "{$this->nombre} recibe $cantidad de danio. Vida: {$this->vida}" . PHP_EOL;
    }

    public function curar(int $cantidad): void {
        $this->vida = min(100, $this->vida + $cantidad);
        echo "{$this->nombre} se cura $cantidad. Vida: {$this->vida}" . PHP_EOL;
    }

    public function getVida(): int {
        return $this->vida;
    }

    public function estaVivo(): bool {
        return $this->vida > 0;
    }
}

$j1 = new Jugador("Guerrero");
$j1->recibirDanio(30);
$j1->recibirDanio(50);
$j1->curar(20);
echo "Vivo? " . ($j1->estaVivo() ? "Si" : "No") . PHP_EOL;
$j1->recibirDanio(100);
echo "Vivo? " . ($j1->estaVivo() ? "Si" : "No") . PHP_EOL;


// EJERCICIO 3
echo PHP_EOL . "--- Ejercicio 3 ---" . PHP_EOL;

abstract class Forma {
    public function __construct(public string $color) {}

    abstract public function calcularArea(): float;

    public function descripcion(): void {
        $clase = get_class($this);
        echo "$clase de color {$this->color} con area " . round($this->calcularArea(), 2) . PHP_EOL;
    }
}

class Rectangulo extends Forma {
    public function __construct(
        string $color,
        public float $ancho,
        public float $alto,
    ) {
        parent::__construct($color);
    }

    public function calcularArea(): float {
        return $this->ancho * $this->alto;
    }
}

class Circulo extends Forma {
    public function __construct(
        string $color,
        public float $radio,
    ) {
        parent::__construct($color);
    }

    public function calcularArea(): float {
        return M_PI * $this->radio ** 2;
    }
}

$rect = new Rectangulo("azul", 10, 5);
$circ = new Circulo("rojo", 7);
$rect->descripcion();
$circ->descripcion();


// EJERCICIO 4
echo PHP_EOL . "--- Ejercicio 4 ---" . PHP_EOL;

interface Imprimible {
    public function imprimir(): string;
}

interface MiSerializable {
    public function serializar(): string;
}

class Tarea implements Imprimible, MiSerializable {
    public function __construct(
        public string $titulo,
        public bool $completada = false,
    ) {}

    public function imprimir(): string {
        $check = $this->completada ? "X" : " ";
        return "[$check] {$this->titulo}";
    }

    public function serializar(): string {
        return json_encode([
            "titulo" => $this->titulo,
            "completada" => $this->completada,
        ]);
    }
}

$tareas = [
    new Tarea("Aprender PHP"),
    new Tarea("Instalar Drupal"),
    new Tarea("Crear un modulo"),
];
$tareas[0]->completada = true;

foreach ($tareas as $tarea) {
    echo $tarea->imprimir() . PHP_EOL;
}
echo "JSON: " . $tareas[0]->serializar() . PHP_EOL;


// EJERCICIO 5
echo PHP_EOL . "--- Ejercicio 5 ---" . PHP_EOL;

class Evento {
    public function __construct(
        public readonly string $nombre,
        public readonly string $fecha,
        public readonly string $lugar,
        public int $asistentes = 0,
    ) {}

    public function registrar(): void {
        $this->asistentes++;
    }

    public function __toString(): string {
        return "{$this->nombre} | {$this->fecha} | {$this->lugar} | {$this->asistentes} asistentes";
    }
}

$evento = new Evento("DrupalCon", "2025-09-15", "Barcelona");
$evento->registrar();
$evento->registrar();
$evento->registrar();
echo $evento . PHP_EOL;


// EJERCICIO 6 (BONUS)
echo PHP_EOL . "--- Ejercicio 6 (Bonus) ---" . PHP_EOL;

trait Notificable {
    private array $notificaciones = [];

    public function notificar(string $mensaje): void {
        $this->notificaciones[] = $mensaje;
    }

    public function verNotificaciones(): void {
        echo "Notificaciones de {$this->nombre}:" . PHP_EOL;
        foreach ($this->notificaciones as $i => $msg) {
            echo "  " . ($i + 1) . ". $msg" . PHP_EOL;
        }
    }
}

class Cliente {
    use Notificable;
    public function __construct(public string $nombre) {}
}

class Empleado {
    use Notificable;
    public function __construct(
        public string $nombre,
        public string $departamento,
    ) {}
}

$cliente = new Cliente("Jesus");
$cliente->notificar("Tu pedido ha sido enviado");
$cliente->notificar("Tu pedido ha llegado");
$cliente->verNotificaciones();

$empleado = new Empleado("Ana", "Desarrollo");
$empleado->notificar("Reunion a las 10:00");
$empleado->verNotificaciones();


echo PHP_EOL . "Ejercicios completados! Pidele a Claude que revise tu codigo." . PHP_EOL;
