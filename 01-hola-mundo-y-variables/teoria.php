<?php

/**
 * =============================================================
 *  LECCION 01: HOLA MUNDO Y VARIABLES
 * =============================================================
 *
 *  En esta leccion aprenderas:
 *    1. Que es PHP y para que sirve
 *    2. Como escribir y ejecutar tu primer programa
 *    3. Que son las variables y como usarlas
 *    4. Los tipos basicos de datos
 *    5. Como mostrar informacion en pantalla
 *
 *  Para ejecutar este archivo:
 *    php teoria.php
 *
 * =============================================================
 */

// -----------------------------------------------
// 1. TU PRIMER PROGRAMA: HOLA MUNDO
// -----------------------------------------------

// En PHP, todo el codigo empieza con la etiqueta de apertura (<?php)
// "echo" es el comando para imprimir texto en pantalla.
// Cada instruccion termina con punto y coma ;

echo "=== 1. HOLA MUNDO ===" . PHP_EOL;
echo "Hola Mundo!" . PHP_EOL;
echo PHP_EOL;

// PHP_EOL es un salto de linea (como pulsar Enter).
// El punto (.) sirve para unir (concatenar) textos.


// -----------------------------------------------
// 2. VARIABLES
// -----------------------------------------------

// Una variable es una "caja" donde guardas un dato.
// En PHP, todas las variables empiezan con el signo $
//
// Reglas para nombres de variables:
//   - Empiezan con $ seguido de una letra o guion bajo _
//   - No pueden empezar con un numero
//   - Son sensibles a mayusculas: $nombre y $Nombre son DISTINTAS
//   - No pueden tener espacios

echo "=== 2. VARIABLES ===" . PHP_EOL;

$nombre = "Jesus";
$edad = 25;
$altura = 1.75;
$esta_estudiando = true;

echo "Nombre: " . $nombre . PHP_EOL;
echo "Edad: " . $edad . PHP_EOL;
echo "Altura: " . $altura . PHP_EOL;
echo PHP_EOL;

// Puedes cambiar el valor de una variable en cualquier momento:
$nombre = "Jesus Victor";
echo "Nombre actualizado: " . $nombre . PHP_EOL;
echo PHP_EOL;


// -----------------------------------------------
// 3. TIPOS DE DATOS BASICOS
// -----------------------------------------------

// PHP tiene varios tipos de datos. Los mas importantes:

echo "=== 3. TIPOS DE DATOS ===" . PHP_EOL;

// STRING (texto) — va entre comillas dobles " " o simples ' '
$saludo = "Hola que tal";
$despedida = 'Hasta luego';
echo "String: " . $saludo . PHP_EOL;

// INTEGER (numero entero) — sin decimales
$cantidad = 42;
echo "Integer: " . $cantidad . PHP_EOL;

// FLOAT (numero decimal) — con punto decimal
$precio = 19.99;
echo "Float: " . $precio . PHP_EOL;

// BOOLEAN (verdadero o falso) — solo puede ser true o false
$activo = true;
$eliminado = false;
echo "Boolean activo: " . ($activo ? "true" : "false") . PHP_EOL;
echo "Boolean eliminado: " . ($eliminado ? "true" : "false") . PHP_EOL;

// NULL — significa "sin valor" o "vacio"
$dato_vacio = null;
echo "Null: " . var_export($dato_vacio, true) . PHP_EOL;
echo PHP_EOL;


// -----------------------------------------------
// 4. COMILLAS DOBLES vs SIMPLES
// -----------------------------------------------

// Comillas dobles: las variables DENTRO se reemplazan por su valor
// Comillas simples: todo se trata como texto literal

echo "=== 4. COMILLAS DOBLES vs SIMPLES ===" . PHP_EOL;

$lenguaje = "PHP";

echo "Estoy aprendiendo $lenguaje" . PHP_EOL;     // Muestra: Estoy aprendiendo PHP
echo 'Estoy aprendiendo $lenguaje' . PHP_EOL;     // Muestra: Estoy aprendiendo $lenguaje
echo PHP_EOL;

// Truco: dentro de comillas dobles puedes usar {$variable} para mayor claridad
echo "El lenguaje {$lenguaje} es genial" . PHP_EOL;
echo PHP_EOL;


// -----------------------------------------------
// 5. OPERACIONES BASICAS CON VARIABLES
// -----------------------------------------------

echo "=== 5. OPERACIONES BASICAS ===" . PHP_EOL;

// Operaciones matematicas
$a = 10;
$b = 3;

echo "a = $a, b = $b" . PHP_EOL;
echo "Suma:           " . ($a + $b) . PHP_EOL;    // 13
echo "Resta:          " . ($a - $b) . PHP_EOL;    // 7
echo "Multiplicacion: " . ($a * $b) . PHP_EOL;    // 30
echo "Division:       " . ($a / $b) . PHP_EOL;    // 3.333...
echo "Modulo (resto): " . ($a % $b) . PHP_EOL;    // 1
echo PHP_EOL;

// Concatenacion de strings (unir textos)
$nombre_completo = "Jesus" . " " . "Victor";
echo "Nombre completo: " . $nombre_completo . PHP_EOL;
echo PHP_EOL;


// -----------------------------------------------
// 6. FUNCIONES UTILES PARA EMPEZAR
// -----------------------------------------------

echo "=== 6. FUNCIONES UTILES ===" . PHP_EOL;

// gettype() — te dice el tipo de una variable
echo "Tipo de \$nombre: " . gettype($nombre) . PHP_EOL;          // string
echo "Tipo de \$edad: " . gettype($edad) . PHP_EOL;              // integer
echo "Tipo de \$altura: " . gettype($altura) . PHP_EOL;          // double (es float)
echo "Tipo de \$esta_estudiando: " . gettype($esta_estudiando) . PHP_EOL; // boolean
echo PHP_EOL;

// strlen() — cuenta los caracteres de un string
echo "Longitud de '$saludo': " . strlen($saludo) . PHP_EOL;
echo PHP_EOL;

// var_dump() — muestra el tipo Y el valor (muy util para depurar)
echo "var_dump de distintas variables:" . PHP_EOL;
var_dump($nombre);
var_dump($edad);
var_dump($esta_estudiando);
var_dump($dato_vacio);
echo PHP_EOL;


// -----------------------------------------------
// RESUMEN
// -----------------------------------------------

echo "=== RESUMEN ===" . PHP_EOL;
echo '1. Todo codigo PHP va entre las etiquetas de apertura y cierre' . PHP_EOL;
echo "2. Las variables empiezan con \$" . PHP_EOL;
echo "3. echo muestra texto en pantalla" . PHP_EOL;
echo "4. Tipos basicos: string, integer, float, boolean, null" . PHP_EOL;
echo "5. Comillas dobles interpretan variables, simples no" . PHP_EOL;
echo "6. El punto (.) concatena strings" . PHP_EOL;
echo "7. Cada instruccion termina con ;" . PHP_EOL;
echo PHP_EOL;
echo "Ahora ve a ejercicio.php y pon en practica lo aprendido!" . PHP_EOL;
