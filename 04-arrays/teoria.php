<?php

/**
 * =============================================================
 *  LECCION 04: ARRAYS
 * =============================================================
 *  Los arrays son la estructura de datos mas usada en PHP y Drupal.
 *  En Drupal, TODO son arrays: render arrays, form arrays, config...
 * =============================================================
 */

echo "=== 1. ARRAYS INDEXADOS ===" . PHP_EOL;

// Un array es una lista de valores. Cada valor tiene un indice (posicion).
// Los indices empiezan en 0 (no en 1).

$frutas = ["manzana", "platano", "naranja", "fresa"];

echo "Primera fruta: " . $frutas[0] . PHP_EOL;   // manzana
echo "Segunda fruta: " . $frutas[1] . PHP_EOL;    // platano
echo "Ultima fruta: " . $frutas[3] . PHP_EOL;     // fresa
echo PHP_EOL;

// Agregar elementos al final
$frutas[] = "uva";           // agrega al final
array_push($frutas, "kiwi"); // otra forma de agregar

// Contar elementos
echo "Total frutas: " . count($frutas) . PHP_EOL;
echo PHP_EOL;

// Mostrar todo el array
echo "Array completo:" . PHP_EOL;
print_r($frutas);
echo PHP_EOL;


echo "=== 2. ARRAYS ASOCIATIVOS ===" . PHP_EOL;

// En vez de indices numericos, usas claves (keys) con nombre.
// Esto es FUNDAMENTAL en Drupal — casi todo usa arrays asociativos.

$persona = [
    "nombre" => "Jesus",
    "edad" => 25,
    "ciudad" => "Madrid",
    "profesion" => "Desarrollador",
];

echo "Nombre: " . $persona["nombre"] . PHP_EOL;
echo "Ciudad: " . $persona["ciudad"] . PHP_EOL;
echo PHP_EOL;

// Agregar o modificar valores
$persona["email"] = "jesus@ejemplo.com";  // nuevo campo
$persona["edad"] = 26;                     // modificar existente
echo "Email: " . $persona["email"] . PHP_EOL;
echo "Edad actualizada: " . $persona["edad"] . PHP_EOL;
echo PHP_EOL;

// Comprobar si una clave existe
echo "Existe 'nombre'? " . (array_key_exists("nombre", $persona) ? "Si" : "No") . PHP_EOL;
echo "Existe 'telefono'? " . (array_key_exists("telefono", $persona) ? "Si" : "No") . PHP_EOL;
echo PHP_EOL;


echo "=== 3. ARRAYS MULTIDIMENSIONALES ===" . PHP_EOL;

// Arrays dentro de arrays. Muy comun en Drupal (render arrays, forms...)
$alumnos = [
    [
        "nombre" => "Ana",
        "notas" => [8, 7, 9],
    ],
    [
        "nombre" => "Pedro",
        "notas" => [6, 5, 7],
    ],
    [
        "nombre" => "Maria",
        "notas" => [10, 9, 10],
    ],
];

echo "Primer alumno: " . $alumnos[0]["nombre"] . PHP_EOL;
echo "Segunda nota de Ana: " . $alumnos[0]["notas"][1] . PHP_EOL;
echo PHP_EOL;

// Recorrer con foreach anidado
foreach ($alumnos as $alumno) {
    $media = array_sum($alumno["notas"]) / count($alumno["notas"]);
    echo $alumno["nombre"] . " - Media: " . round($media, 1) . PHP_EOL;
}
echo PHP_EOL;


echo "=== 4. FUNCIONES DE ARRAYS (LAS MAS USADAS) ===" . PHP_EOL;

$numeros = [3, 1, 4, 1, 5, 9, 2, 6];

// Ordenar
sort($numeros);  // ordena de menor a mayor (modifica el array original)
echo "Ordenado: " . implode(", ", $numeros) . PHP_EOL;

// Buscar
$colores = ["rojo", "verde", "azul", "amarillo"];
echo "Posicion de 'azul': " . array_search("azul", $colores) . PHP_EOL;
echo "Contiene 'verde'? " . (in_array("verde", $colores) ? "Si" : "No") . PHP_EOL;
echo PHP_EOL;

// Unir y separar strings <-> arrays
// implode: array -> string
$partes = ["Hola", "Mundo", "PHP"];
$texto = implode(" ", $partes);
echo "implode: $texto" . PHP_EOL;

// explode: string -> array
$csv = "uno,dos,tres,cuatro";
$valores = explode(",", $csv);
echo "explode: ";
print_r($valores);

// Extraer partes de un array
$letras = ["a", "b", "c", "d", "e"];
$parte = array_slice($letras, 1, 3);  // desde posicion 1, 3 elementos
echo "array_slice(1,3): " . implode(", ", $parte) . PHP_EOL;  // b, c, d
echo PHP_EOL;

// Combinar dos arrays
$array1 = [1, 2, 3];
$array2 = [4, 5, 6];
$combinado = array_merge($array1, $array2);
echo "array_merge: " . implode(", ", $combinado) . PHP_EOL;
echo PHP_EOL;

// Eliminar elementos
$animales = ["gato", "perro", "pajaro", "pez"];
unset($animales[2]);  // elimina "pajaro" (indice 2)
echo "Despues de unset: " . implode(", ", $animales) . PHP_EOL;

// Eliminar el ultimo / primero
$pila = ["a", "b", "c", "d"];
$ultimo = array_pop($pila);    // quita y devuelve el ultimo
$primero = array_shift($pila); // quita y devuelve el primero
echo "Pop: $ultimo, Shift: $primero, Quedan: " . implode(", ", $pila) . PHP_EOL;
echo PHP_EOL;


echo "=== 5. FUNCIONES AVANZADAS ===" . PHP_EOL;

// array_map — aplica una funcion a cada elemento
$precios = [10, 20, 30, 40];
$con_iva = array_map(function ($precio) {
    return $precio * 1.21;
}, $precios);
echo "Precios con IVA: " . implode(", ", $con_iva) . PHP_EOL;

// array_filter — filtra elementos segun una condicion
$edades = [15, 22, 17, 30, 12, 25];
$mayores = array_filter($edades, function ($edad) {
    return $edad >= 18;
});
echo "Mayores de edad: " . implode(", ", $mayores) . PHP_EOL;

// array_reduce — reduce un array a un solo valor
$carrito = [29.99, 15.50, 42.00, 8.99];
$total = array_reduce($carrito, function ($acumulador, $precio) {
    return $acumulador + $precio;
}, 0);
echo "Total carrito: $total" . PHP_EOL;
echo PHP_EOL;

// Obtener solo las claves o los valores
$persona2 = ["nombre" => "Ana", "edad" => 30, "ciudad" => "Barcelona"];
echo "Claves: " . implode(", ", array_keys($persona2)) . PHP_EOL;
echo "Valores: " . implode(", ", array_values($persona2)) . PHP_EOL;
echo PHP_EOL;


echo "=== 6. SPREAD OPERATOR (...) ===" . PHP_EOL;

// Desde PHP 7.4, puedes expandir arrays con ...
$primeros = [1, 2, 3];
$todos = [...$primeros, 4, 5, 6];
echo "Spread: " . implode(", ", $todos) . PHP_EOL;
echo PHP_EOL;


echo "=== RESUMEN ===" . PHP_EOL;
echo "1. Indexados: [valor1, valor2] — acceso por numero [0]" . PHP_EOL;
echo "2. Asociativos: [clave => valor] — acceso por nombre ['clave']" . PHP_EOL;
echo "3. Multidimensionales: arrays dentro de arrays" . PHP_EOL;
echo "4. Funciones clave: count, sort, in_array, array_merge" . PHP_EOL;
echo "5. String<->Array: implode/explode" . PHP_EOL;
echo "6. Funcionales: array_map, array_filter, array_reduce" . PHP_EOL;
echo "7. En Drupal TODO son arrays asociativos!" . PHP_EOL;
