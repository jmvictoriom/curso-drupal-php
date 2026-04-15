<?php

/**
 * =============================================================
 *  LECCION 03: ESTRUCTURAS DE CONTROL
 * =============================================================
 *  if, else, elseif, switch, while, for, foreach, do-while
 * =============================================================
 */

echo "=== 1. IF / ELSE ===" . PHP_EOL;

// if comprueba una condicion. Si es true, ejecuta el bloque.
$edad = 20;

if ($edad >= 18) {
    echo "Eres mayor de edad" . PHP_EOL;
}

// if / else — dos caminos posibles
$nota = 4;

if ($nota >= 5) {
    echo "Aprobado con un $nota" . PHP_EOL;
} else {
    echo "Suspendido con un $nota" . PHP_EOL;
}

// if / elseif / else — multiples condiciones
$nota = 8;

if ($nota >= 9) {
    echo "Sobresaliente" . PHP_EOL;
} elseif ($nota >= 7) {
    echo "Notable" . PHP_EOL;
} elseif ($nota >= 5) {
    echo "Aprobado" . PHP_EOL;
} else {
    echo "Suspendido" . PHP_EOL;
}
echo PHP_EOL;

// Puedes anidar ifs dentro de otros ifs
$tiene_dni = true;
$edad = 20;

if ($edad >= 18) {
    if ($tiene_dni) {
        echo "Puede votar" . PHP_EOL;
    } else {
        echo "Necesita el DNI para votar" . PHP_EOL;
    }
} else {
    echo "No puede votar (menor de edad)" . PHP_EOL;
}
echo PHP_EOL;


echo "=== 2. SWITCH ===" . PHP_EOL;

// switch compara UNA variable contra varios valores posibles
// Util cuando tienes muchos elseif para el mismo valor

$dia = "martes";

switch ($dia) {
    case "lunes":
        echo "Inicio de semana" . PHP_EOL;
        break;  // MUY IMPORTANTE: sin break, sigue ejecutando los siguientes cases
    case "martes":
    case "miercoles":
    case "jueves":
        echo "Entre semana" . PHP_EOL;
        break;
    case "viernes":
        echo "Casi fin de semana!" . PHP_EOL;
        break;
    case "sabado":
    case "domingo":
        echo "Fin de semana!" . PHP_EOL;
        break;
    default:
        echo "Dia no valido" . PHP_EOL;
        break;
}
echo PHP_EOL;

// match (PHP 8+) — version moderna de switch, mas limpia
$codigo_http = 404;

$mensaje = match ($codigo_http) {
    200 => "OK",
    301 => "Redireccion permanente",
    404 => "No encontrado",
    500 => "Error del servidor",
    default => "Codigo desconocido",
};
echo "HTTP $codigo_http: $mensaje" . PHP_EOL;
echo PHP_EOL;


echo "=== 3. WHILE ===" . PHP_EOL;

// while repite un bloque MIENTRAS la condicion sea true
// Cuidado: si la condicion nunca es false, se crea un bucle infinito!

$contador = 1;
while ($contador <= 5) {
    echo "Vuelta numero: $contador" . PHP_EOL;
    $contador++;  // IMPORTANTE: sin esto, bucle infinito!
}
echo PHP_EOL;


echo "=== 4. DO-WHILE ===" . PHP_EOL;

// do-while ejecuta el bloque AL MENOS UNA VEZ, luego comprueba
$numero = 10;
do {
    echo "Numero: $numero" . PHP_EOL;
    $numero += 10;
} while ($numero <= 30);
echo PHP_EOL;


echo "=== 5. FOR ===" . PHP_EOL;

// for(inicio; condicion; incremento) — cuando sabes cuantas vueltas
// Es el bucle mas comun

for ($i = 0; $i < 5; $i++) {
    echo "Iteracion: $i" . PHP_EOL;
}
echo PHP_EOL;

// Ejemplo practico: tabla de multiplicar del 5
echo "Tabla del 5:" . PHP_EOL;
for ($i = 1; $i <= 10; $i++) {
    echo "5 x $i = " . (5 * $i) . PHP_EOL;
}
echo PHP_EOL;


echo "=== 6. FOREACH ===" . PHP_EOL;

// foreach recorre arrays (lo veremos a fondo en leccion 04)
// Es el bucle que MAS usaras en Drupal

$frutas = ["manzana", "platano", "naranja", "fresa"];

foreach ($frutas as $fruta) {
    echo "Fruta: $fruta" . PHP_EOL;
}
echo PHP_EOL;

// foreach con clave => valor
$edades = [
    "Ana" => 25,
    "Pedro" => 30,
    "Maria" => 28,
];

foreach ($edades as $nombre => $edad) {
    echo "$nombre tiene $edad anios" . PHP_EOL;
}
echo PHP_EOL;


echo "=== 7. BREAK Y CONTINUE ===" . PHP_EOL;

// break — sale del bucle completamente
echo "Break:" . PHP_EOL;
for ($i = 1; $i <= 10; $i++) {
    if ($i === 5) {
        break;  // para el bucle cuando i es 5
    }
    echo "$i ";
}
echo PHP_EOL;

// continue — salta a la siguiente iteracion
echo "Continue (saltar pares):" . PHP_EOL;
for ($i = 1; $i <= 10; $i++) {
    if ($i % 2 === 0) {
        continue;  // salta los numeros pares
    }
    echo "$i ";
}
echo PHP_EOL;
echo PHP_EOL;


echo "=== RESUMEN ===" . PHP_EOL;
echo "1. if/elseif/else: condiciones" . PHP_EOL;
echo "2. switch/match: comparar una variable contra varios valores" . PHP_EOL;
echo "3. while: repetir mientras condicion sea true" . PHP_EOL;
echo "4. do-while: ejecutar al menos una vez, luego comprobar" . PHP_EOL;
echo "5. for: cuando sabes el numero de iteraciones" . PHP_EOL;
echo "6. foreach: recorrer arrays (el mas usado en Drupal)" . PHP_EOL;
echo "7. break/continue: controlar el flujo dentro de bucles" . PHP_EOL;
