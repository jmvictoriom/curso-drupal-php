<?php

/**
 * =============================================================
 *  SOLUCION 04: ARRAYS
 * =============================================================
 */

// EJERCICIO 1
echo "--- Ejercicio 1 ---" . PHP_EOL;
$peliculas = ["Inception", "Matrix", "Interstellar", "Gladiator", "El Padrino"];
echo "Primera: " . $peliculas[0] . PHP_EOL;
echo "Ultima: " . $peliculas[count($peliculas) - 1] . PHP_EOL;
$peliculas[] = "Pulp Fiction";
echo "Total peliculas: " . count($peliculas) . PHP_EOL;


// EJERCICIO 2
echo PHP_EOL . "--- Ejercicio 2 ---" . PHP_EOL;
$producto = [
    "nombre" => "Teclado Mecanico",
    "precio" => 89.99,
    "stock" => 15,
    "categoria" => "Perifericos",
];
echo "Producto: " . $producto["nombre"] . PHP_EOL;
echo "Precio: " . $producto["precio"] . " euros" . PHP_EOL;
echo "Stock: " . $producto["stock"] . " unidades" . PHP_EOL;
echo "Categoria: " . $producto["categoria"] . PHP_EOL;


// EJERCICIO 3
echo PHP_EOL . "--- Ejercicio 3 ---" . PHP_EOL;
$equipo = [
    ["nombre" => "Messi", "posicion" => "Delantero", "goles" => 30],
    ["nombre" => "Ramos", "posicion" => "Defensa", "goles" => 5],
    ["nombre" => "Modric", "posicion" => "Centrocampista", "goles" => 12],
];

foreach ($equipo as $jugador) {
    echo $jugador["nombre"] . " - " . $jugador["posicion"] . " - " . $jugador["goles"] . " goles" . PHP_EOL;
}


// EJERCICIO 4
echo PHP_EOL . "--- Ejercicio 4 ---" . PHP_EOL;
$notas = [7, 3, 9, 5, 8, 2, 10, 6];

sort($notas);
echo "Ordenadas: " . implode(", ", $notas) . PHP_EOL;

$media = array_sum($notas) / count($notas);
echo "Media: " . round($media, 2) . PHP_EOL;

$aprobados = array_filter($notas, function ($nota) {
    return $nota >= 5;
});
echo "Aprobados: " . implode(", ", $aprobados) . PHP_EOL;


// EJERCICIO 5
echo PHP_EOL . "--- Ejercicio 5 ---" . PHP_EOL;
$csv = "PHP,JavaScript,Python,Go,Rust";
$lenguajes = explode(",", $csv);
foreach ($lenguajes as $lenguaje) {
    echo "- $lenguaje" . PHP_EOL;
}

$palabras = ["Hola", "mundo", "desde", "PHP"];
$frase = implode(" ", $palabras);
echo $frase . PHP_EOL;


// EJERCICIO 6 (BONUS)
echo PHP_EOL . "--- Ejercicio 6 (Bonus) ---" . PHP_EOL;
$carrito = [
    ["nombre" => "Camiseta", "precio" => 19.99, "cantidad" => 2],
    ["nombre" => "Pantalon", "precio" => 39.99, "cantidad" => 1],
    ["nombre" => "Zapatillas", "precio" => 79.99, "cantidad" => 1],
];

$total = 0;
foreach ($carrito as $item) {
    $subtotal = $item["precio"] * $item["cantidad"];
    $total += $subtotal;
    echo $item["nombre"] . " x" . $item["cantidad"] . " = " . number_format($subtotal, 2) . " euros" . PHP_EOL;
}
echo "--------------------" . PHP_EOL;
echo "TOTAL: " . number_format($total, 2) . " euros" . PHP_EOL;


echo PHP_EOL . "Ejercicios completados! Pidele a Claude que revise tu codigo." . PHP_EOL;
