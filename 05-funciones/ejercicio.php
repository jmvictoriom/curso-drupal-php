<?php

/**
 * =============================================================
 *  EJERCICIO 05: FUNCIONES
 * =============================================================
 */


// -----------------------------------------------
// EJERCICIO 1: Funcion basica
// -----------------------------------------------
// Crea una funcion llamada "presentarse" que reciba
// $nombre y $profesion como parametros y muestre:
//   "Hola, soy [nombre] y soy [profesion]"
// Llamala 3 veces con datos diferentes.

echo "--- Ejercicio 1 ---" . PHP_EOL;
// TU CODIGO AQUI



// -----------------------------------------------
// EJERCICIO 2: Funcion con return
// -----------------------------------------------
// Crea una funcion "calcular_descuento" que reciba:
//   $precio (float)
//   $porcentaje (float, por defecto 10)
// Debe DEVOLVER el precio con el descuento aplicado.
// Ejemplo: calcular_descuento(100, 20) -> 80

echo PHP_EOL . "--- Ejercicio 2 ---" . PHP_EOL;
// TU CODIGO AQUI



// -----------------------------------------------
// EJERCICIO 3: Funcion que devuelve un array
// -----------------------------------------------
// Crea una funcion "analizar_numeros" que reciba un array de numeros
// y devuelva un array asociativo con:
//   "total" => cantidad de numeros
//   "suma" => suma de todos
//   "media" => media (suma / total)
//   "maximo" => el numero mas grande
//   "minimo" => el numero mas pequenio
// Pista: usa count(), array_sum(), max(), min()

echo PHP_EOL . "--- Ejercicio 3 ---" . PHP_EOL;
// TU CODIGO AQUI

// Prueba con: $datos = [15, 8, 23, 42, 4, 16];



// -----------------------------------------------
// EJERCICIO 4: Type hints
// -----------------------------------------------
// Crea una funcion "es_palindromo" con type hints que:
//   - Reciba un string
//   - Devuelva un bool
//   - Compruebe si la palabra es igual al reves
// Pista: strrev() invierte un string, strtolower() convierte a minusculas
// Prueba con: "Ana", "Hola", "reconocer"

echo PHP_EOL . "--- Ejercicio 4 ---" . PHP_EOL;
// TU CODIGO AQUI



// -----------------------------------------------
// EJERCICIO 5: Arrow functions con arrays
// -----------------------------------------------
// Dado el array $productos:
$productos = [
    ["nombre" => "Camiseta", "precio" => 25.00],
    ["nombre" => "Pantalon", "precio" => 45.00],
    ["nombre" => "Zapatos", "precio" => 80.00],
    ["nombre" => "Gorra", "precio" => 15.00],
    ["nombre" => "Chaqueta", "precio" => 120.00],
];
// 1. Usa array_map con arrow function para obtener solo los nombres
// 2. Usa array_filter con arrow function para obtener productos > 30 euros
// 3. Usa array_map con arrow function para aplicar 10% de descuento a todos los precios

echo PHP_EOL . "--- Ejercicio 5 ---" . PHP_EOL;
// TU CODIGO AQUI



// -----------------------------------------------
// EJERCICIO 6 (BONUS): Mini validador
// -----------------------------------------------
// Crea una funcion "validar_email" que reciba un string y devuelva:
//   true si contiene "@" y "."
//   false si no
// Crea otra funcion "validar_password" que reciba un string y devuelva:
//   true si tiene 8 o mas caracteres
//   false si no
// Prueba ambas y muestra los resultados.
// Pista: str_contains() y strlen()

echo PHP_EOL . "--- Ejercicio 6 (Bonus) ---" . PHP_EOL;
// TU CODIGO AQUI



echo PHP_EOL . "Ejercicios completados! Pidele a Claude que revise tu codigo." . PHP_EOL;
