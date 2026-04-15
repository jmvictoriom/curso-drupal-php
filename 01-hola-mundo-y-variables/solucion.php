<?php

/**
 * =============================================================
 *  SOLUCION 01: HOLA MUNDO Y VARIABLES
 * =============================================================
 *
 *  NO MIRES ESTO hasta que hayas intentado el ejercicio!
 *  Primero intenta, luego compara.
 *
 * =============================================================
 */


// -----------------------------------------------
// EJERCICIO 1
// -----------------------------------------------

echo "--- Ejercicio 1 ---" . PHP_EOL;
echo "Estoy aprendiendo PHP" . PHP_EOL;


// -----------------------------------------------
// EJERCICIO 2
// -----------------------------------------------

echo PHP_EOL . "--- Ejercicio 2 ---" . PHP_EOL;
$mi_nombre = "Jesus";
$mi_edad = 25;
$mi_estatura = 1.75;
$soy_estudiante = true;

echo "Nombre: " . $mi_nombre . PHP_EOL;
echo "Edad: " . $mi_edad . PHP_EOL;
echo "Estatura: " . $mi_estatura . PHP_EOL;
echo "Soy estudiante: " . ($soy_estudiante ? "Si" : "No") . PHP_EOL;


// -----------------------------------------------
// EJERCICIO 3
// -----------------------------------------------

echo PHP_EOL . "--- Ejercicio 3 ---" . PHP_EOL;
$ciudad = "Madrid";
$pais = "Espana";
$ubicacion = "Vivo en " . $ciudad . ", " . $pais;
echo $ubicacion . PHP_EOL;

// Alternativa con comillas dobles:
// $ubicacion = "Vivo en $ciudad, $pais";


// -----------------------------------------------
// EJERCICIO 4
// -----------------------------------------------

echo PHP_EOL . "--- Ejercicio 4 ---" . PHP_EOL;
$precio_producto = 49.99;
$cantidad = 3;
$total = $precio_producto * $cantidad;
echo "El total es: $total euros" . PHP_EOL;


// -----------------------------------------------
// EJERCICIO 5
// -----------------------------------------------

echo PHP_EOL . "--- Ejercicio 5 ---" . PHP_EOL;
$animal = "gato";
echo "Mi animal favorito es el $animal" . PHP_EOL;          // comillas dobles
echo 'Mi animal favorito es el ' . $animal . PHP_EOL;       // comillas simples + concatenacion


// -----------------------------------------------
// EJERCICIO 6 (BONUS)
// -----------------------------------------------

echo PHP_EOL . "--- Ejercicio 6 (Bonus) ---" . PHP_EOL;
var_dump($mi_nombre);
var_dump($mi_edad);
var_dump($mi_estatura);
var_dump($soy_estudiante);


echo PHP_EOL . "Ejercicios completados! Pidele a Claude que revise tu codigo." . PHP_EOL;
