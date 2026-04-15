<?php

/**
 * =============================================================
 *  LECCION 05: FUNCIONES
 * =============================================================
 *  Las funciones son bloques de codigo reutilizable.
 *  Drupal esta lleno de funciones: hooks, callbacks, helpers...
 * =============================================================
 */

echo "=== 1. FUNCIONES BASICAS ===" . PHP_EOL;

// Una funcion es un bloque de codigo con un nombre que puedes llamar
// cuando quieras. Evita repetir codigo.

function saludar() {
    echo "Hola! Soy una funcion" . PHP_EOL;
}

// Llamar (ejecutar) la funcion
saludar();
saludar();  // puedes llamarla cuantas veces quieras
echo PHP_EOL;


echo "=== 2. FUNCIONES CON PARAMETROS ===" . PHP_EOL;

// Los parametros son datos que le pasas a la funcion
function saludar_a($nombre) {
    echo "Hola, $nombre!" . PHP_EOL;
}

saludar_a("Jesus");
saludar_a("Ana");

// Multiples parametros
function presentar($nombre, $edad, $ciudad) {
    echo "$nombre tiene $edad anios y vive en $ciudad" . PHP_EOL;
}

presentar("Jesus", 25, "Madrid");
echo PHP_EOL;


echo "=== 3. VALORES POR DEFECTO ===" . PHP_EOL;

// Puedes dar un valor por defecto a un parametro
function crear_usuario($nombre, $rol = "usuario", $activo = true) {
    $estado = $activo ? "activo" : "inactivo";
    echo "Usuario: $nombre | Rol: $rol | Estado: $estado" . PHP_EOL;
}

crear_usuario("Jesus");                    // usa los valores por defecto
crear_usuario("Ana", "admin");             // sobreescribe el rol
crear_usuario("Pedro", "editor", false);   // sobreescribe todo
echo PHP_EOL;


echo "=== 4. RETURN (DEVOLVER VALORES) ===" . PHP_EOL;

// Las funciones pueden devolver un resultado con return
function sumar($a, $b) {
    return $a + $b;
}

$resultado = sumar(10, 5);
echo "10 + 5 = $resultado" . PHP_EOL;

// Puedes usar el resultado directamente
echo "20 + 30 = " . sumar(20, 30) . PHP_EOL;

// Una funcion mas util
function calcular_iva($precio, $porcentaje = 21) {
    $iva = $precio * $porcentaje / 100;
    return [
        "precio_base" => $precio,
        "iva" => $iva,
        "total" => $precio + $iva,
    ];
}

$factura = calcular_iva(100);
echo "Base: {$factura['precio_base']} | IVA: {$factura['iva']} | Total: {$factura['total']}" . PHP_EOL;
echo PHP_EOL;


echo "=== 5. TYPE HINTS (TIPOS EN PARAMETROS) ===" . PHP_EOL;

// Desde PHP 7+, puedes especificar que tipo de datos espera la funcion
// Esto ayuda a prevenir errores

function dividir(float $a, float $b): float {
    if ($b == 0) {
        return 0;
    }
    return $a / $b;
}

echo "10 / 3 = " . dividir(10, 3) . PHP_EOL;

// Tipos disponibles: int, float, string, bool, array, ?string (nullable)
function buscar_usuario(int $id): ?string {
    $usuarios = [1 => "Jesus", 2 => "Ana", 3 => "Pedro"];
    return $usuarios[$id] ?? null;  // devuelve null si no existe
}

echo "Usuario 1: " . (buscar_usuario(1) ?? "no encontrado") . PHP_EOL;
echo "Usuario 9: " . (buscar_usuario(9) ?? "no encontrado") . PHP_EOL;

// Union types (PHP 8+)
function procesar(int|string $valor): string {
    return "Recibido: $valor";
}
echo procesar(42) . PHP_EOL;
echo procesar("hola") . PHP_EOL;
echo PHP_EOL;


echo "=== 6. FUNCIONES ANONIMAS (CLOSURES) ===" . PHP_EOL;

// Una funcion sin nombre, guardada en una variable.
// Muy usadas en array_map, array_filter, callbacks, etc.

$multiplicar = function ($a, $b) {
    return $a * $b;
};

echo "3 x 4 = " . $multiplicar(3, 4) . PHP_EOL;

// Uso practico: como callback
$numeros = [1, 2, 3, 4, 5];
$dobles = array_map(function ($n) {
    return $n * 2;
}, $numeros);
echo "Dobles: " . implode(", ", $dobles) . PHP_EOL;
echo PHP_EOL;

// use — para acceder a variables externas desde el closure
$iva = 21;
$aplicar_iva = function ($precio) use ($iva) {
    return $precio * (1 + $iva / 100);
};
echo "100 con IVA: " . $aplicar_iva(100) . PHP_EOL;
echo PHP_EOL;


echo "=== 7. ARROW FUNCTIONS (PHP 7.4+) ===" . PHP_EOL;

// Forma corta de escribir funciones anonimas de una linea
// fn($x) => expresion
// Acceden automaticamente a variables externas (no necesitan use)

$cuadrado = fn($n) => $n * $n;
echo "5 al cuadrado: " . $cuadrado(5) . PHP_EOL;

$factor = 1.21;
$con_iva = fn($precio) => $precio * $factor;  // accede a $factor sin use
echo "50 con IVA: " . $con_iva(50) . PHP_EOL;

// Con array_map
$precios = [10, 20, 30];
$con_descuento = array_map(fn($p) => $p * 0.9, $precios);
echo "Con 10% descuento: " . implode(", ", $con_descuento) . PHP_EOL;
echo PHP_EOL;


echo "=== 8. SCOPE (ALCANCE DE VARIABLES) ===" . PHP_EOL;

// Las variables dentro de una funcion NO existen fuera, y viceversa
$mensaje_global = "Soy global";

function mostrar_scope() {
    // echo $mensaje_global;  // ERROR: no existe dentro de la funcion
    $mensaje_local = "Soy local";
    echo "Dentro: $mensaje_local" . PHP_EOL;
}

mostrar_scope();
// echo $mensaje_local;  // ERROR: no existe fuera de la funcion
echo "Fuera: $mensaje_global" . PHP_EOL;
echo PHP_EOL;

// Para usar una variable global dentro de una funcion: global (NO recomendado)
// Mejor pasar como parametro. global crea codigo dificil de mantener.


echo "=== 9. FUNCIONES UTILES DE PHP ===" . PHP_EOL;

// Strings
echo "strtoupper: " . strtoupper("hola mundo") . PHP_EOL;
echo "strtolower: " . strtolower("HOLA MUNDO") . PHP_EOL;
echo "trim: '" . trim("  espacios  ") . "'" . PHP_EOL;
echo "str_replace: " . str_replace("mundo", "PHP", "hola mundo") . PHP_EOL;
echo "substr: " . substr("Hola Mundo", 5, 5) . PHP_EOL;
echo "str_contains: " . (str_contains("Hola Mundo", "Mundo") ? "Si" : "No") . PHP_EOL;
echo "str_starts_with: " . (str_starts_with("Hola Mundo", "Hola") ? "Si" : "No") . PHP_EOL;
echo PHP_EOL;

// Numeros
echo "round(3.7): " . round(3.7) . PHP_EOL;
echo "ceil(3.2): " . ceil(3.2) . PHP_EOL;
echo "floor(3.9): " . floor(3.9) . PHP_EOL;
echo "rand(1, 100): " . rand(1, 100) . PHP_EOL;
echo "number_format(1234567.89, 2): " . number_format(1234567.89, 2, ',', '.') . PHP_EOL;
echo PHP_EOL;

// Fecha y hora
echo "date('Y-m-d'): " . date('Y-m-d') . PHP_EOL;
echo "date('H:i:s'): " . date('H:i:s') . PHP_EOL;
echo "time(): " . time() . " (timestamp)" . PHP_EOL;
echo PHP_EOL;


echo "=== RESUMEN ===" . PHP_EOL;
echo "1. function nombre() {} — declarar funciones" . PHP_EOL;
echo "2. Parametros con valores por defecto" . PHP_EOL;
echo "3. return devuelve un valor" . PHP_EOL;
echo "4. Type hints: function f(int \$x): string" . PHP_EOL;
echo "5. Closures: function(\$x) { return \$x; }" . PHP_EOL;
echo "6. Arrow functions: fn(\$x) => \$x * 2" . PHP_EOL;
echo "7. Las variables tienen scope local" . PHP_EOL;
