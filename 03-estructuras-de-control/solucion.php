<?php

/**
 * =============================================================
 *  SOLUCION 03: ESTRUCTURAS DE CONTROL
 * =============================================================
 */

// EJERCICIO 1
echo "--- Ejercicio 1 ---" . PHP_EOL;
$nota = 8;

if ($nota === 10) {
    echo "Matricula de honor" . PHP_EOL;
} elseif ($nota >= 9) {
    echo "Sobresaliente" . PHP_EOL;
} elseif ($nota >= 7) {
    echo "Notable" . PHP_EOL;
} elseif ($nota >= 5) {
    echo "Aprobado" . PHP_EOL;
} else {
    echo "Suspendido" . PHP_EOL;
}


// EJERCICIO 2
echo PHP_EOL . "--- Ejercicio 2 ---" . PHP_EOL;
$num1 = 20;
$num2 = 4;
$operacion = "multiplicar";

$resultado = match ($operacion) {
    "sumar" => $num1 + $num2,
    "restar" => $num1 - $num2,
    "multiplicar" => $num1 * $num2,
    "dividir" => $num1 / $num2,
    default => "Operacion no valida",
};
echo "$num1 $operacion $num2 = $resultado" . PHP_EOL;


// EJERCICIO 3
echo PHP_EOL . "--- Ejercicio 3 ---" . PHP_EOL;
$cuenta = 10;
while ($cuenta >= 1) {
    echo "$cuenta..." . PHP_EOL;
    $cuenta--;
}
echo "Despegue!" . PHP_EOL;


// EJERCICIO 4
echo PHP_EOL . "--- Ejercicio 4 ---" . PHP_EOL;
$tabla = 7;
for ($i = 1; $i <= 10; $i++) {
    echo "$tabla x $i = " . ($tabla * $i) . PHP_EOL;
}


// EJERCICIO 5
echo PHP_EOL . "--- Ejercicio 5 ---" . PHP_EOL;
$lenguajes = ["PHP", "JavaScript", "Python", "Go", "Rust"];
$numero = 1;
foreach ($lenguajes as $lenguaje) {
    echo "$numero. $lenguaje" . PHP_EOL;
    $numero++;
}


// EJERCICIO 6 (BONUS)
echo PHP_EOL . "--- Ejercicio 6 (Bonus) ---" . PHP_EOL;
for ($i = 1; $i <= 30; $i++) {
    if ($i % 3 === 0 && $i % 5 === 0) {
        echo "FizzBuzz" . PHP_EOL;
    } elseif ($i % 3 === 0) {
        echo "Fizz" . PHP_EOL;
    } elseif ($i % 5 === 0) {
        echo "Buzz" . PHP_EOL;
    } else {
        echo "$i" . PHP_EOL;
    }
}


echo PHP_EOL . "Ejercicios completados! Pidele a Claude que revise tu codigo." . PHP_EOL;
