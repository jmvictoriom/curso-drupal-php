<?php

/**
 * =============================================================
 *  SOLUCION 05: FUNCIONES
 * =============================================================
 */

// EJERCICIO 1
echo "--- Ejercicio 1 ---" . PHP_EOL;

function presentarse(string $nombre, string $profesion): void {
    echo "Hola, soy $nombre y soy $profesion" . PHP_EOL;
}

presentarse("Jesus", "Desarrollador");
presentarse("Ana", "Disenadora");
presentarse("Pedro", "Sysadmin");


// EJERCICIO 2
echo PHP_EOL . "--- Ejercicio 2 ---" . PHP_EOL;

function calcular_descuento(float $precio, float $porcentaje = 10): float {
    return $precio - ($precio * $porcentaje / 100);
}

echo "100 con 20% dto: " . calcular_descuento(100, 20) . PHP_EOL;
echo "50 con dto default (10%): " . calcular_descuento(50) . PHP_EOL;


// EJERCICIO 3
echo PHP_EOL . "--- Ejercicio 3 ---" . PHP_EOL;

function analizar_numeros(array $numeros): array {
    $total = count($numeros);
    $suma = array_sum($numeros);
    return [
        "total" => $total,
        "suma" => $suma,
        "media" => $total > 0 ? $suma / $total : 0,
        "maximo" => max($numeros),
        "minimo" => min($numeros),
    ];
}

$datos = [15, 8, 23, 42, 4, 16];
$analisis = analizar_numeros($datos);
echo "Total: " . $analisis["total"] . PHP_EOL;
echo "Suma: " . $analisis["suma"] . PHP_EOL;
echo "Media: " . round($analisis["media"], 2) . PHP_EOL;
echo "Maximo: " . $analisis["maximo"] . PHP_EOL;
echo "Minimo: " . $analisis["minimo"] . PHP_EOL;


// EJERCICIO 4
echo PHP_EOL . "--- Ejercicio 4 ---" . PHP_EOL;

function es_palindromo(string $texto): bool {
    $limpio = strtolower($texto);
    return $limpio === strrev($limpio);
}

$palabras = ["Ana", "Hola", "reconocer", "PHP", "radar"];
foreach ($palabras as $palabra) {
    $resultado = es_palindromo($palabra) ? "SI" : "NO";
    echo "'$palabra' es palindromo? $resultado" . PHP_EOL;
}


// EJERCICIO 5
echo PHP_EOL . "--- Ejercicio 5 ---" . PHP_EOL;

$productos = [
    ["nombre" => "Camiseta", "precio" => 25.00],
    ["nombre" => "Pantalon", "precio" => 45.00],
    ["nombre" => "Zapatos", "precio" => 80.00],
    ["nombre" => "Gorra", "precio" => 15.00],
    ["nombre" => "Chaqueta", "precio" => 120.00],
];

// 1. Solo nombres
$nombres = array_map(fn($p) => $p["nombre"], $productos);
echo "Nombres: " . implode(", ", $nombres) . PHP_EOL;

// 2. Productos > 30 euros
$caros = array_filter($productos, fn($p) => $p["precio"] > 30);
echo "Mas de 30 euros: " . implode(", ", array_map(fn($p) => $p["nombre"], $caros)) . PHP_EOL;

// 3. Con 10% de descuento
$rebajados = array_map(fn($p) => [
    "nombre" => $p["nombre"],
    "precio" => $p["precio"] * 0.9,
], $productos);
foreach ($rebajados as $p) {
    echo "  {$p['nombre']}: " . number_format($p["precio"], 2) . " euros" . PHP_EOL;
}


// EJERCICIO 6 (BONUS)
echo PHP_EOL . "--- Ejercicio 6 (Bonus) ---" . PHP_EOL;

function validar_email(string $email): bool {
    return str_contains($email, "@") && str_contains($email, ".");
}

function validar_password(string $password): bool {
    return strlen($password) >= 8;
}

$emails = ["user@email.com", "invalido", "otro@test.es"];
foreach ($emails as $email) {
    $ok = validar_email($email) ? "VALIDO" : "INVALIDO";
    echo "Email '$email': $ok" . PHP_EOL;
}

$passwords = ["abc", "12345678", "pass"];
foreach ($passwords as $pass) {
    $ok = validar_password($pass) ? "VALIDO" : "INVALIDO";
    echo "Password '$pass': $ok" . PHP_EOL;
}


echo PHP_EOL . "Ejercicios completados! Pidele a Claude que revise tu codigo." . PHP_EOL;
