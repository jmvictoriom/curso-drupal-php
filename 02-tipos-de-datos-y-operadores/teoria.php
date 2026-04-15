<?php

/**
 * =============================================================
 *  LECCION 02: TIPOS DE DATOS Y OPERADORES
 * =============================================================
 */

echo "=== 1. TIPOS DE DATOS EN DETALLE ===" . PHP_EOL;

// --- STRING ---
// Cadenas de texto. Se pueden crear de varias formas:
$texto1 = "Hola con comillas dobles";
$texto2 = 'Hola con comillas simples';

// Caracteres especiales (solo funcionan con comillas dobles):
// \n = salto de linea
// \t = tabulacion
// \\ = barra invertida
// \" = comilla doble dentro de comillas dobles
echo "Linea 1\nLinea 2" . PHP_EOL;
echo "Columna1\tColumna2" . PHP_EOL;
echo PHP_EOL;

// --- INTEGER ---
// Numeros enteros (positivos, negativos, cero)
$positivo = 42;
$negativo = -15;
$cero = 0;

// Tambien puedes escribir enteros en otras bases:
$hexadecimal = 0xFF;  // 255 en decimal
$octal = 0755;         // 493 en decimal
$binario = 0b1010;     // 10 en decimal
echo "Hex: $hexadecimal, Octal: $octal, Binario: $binario" . PHP_EOL;
echo PHP_EOL;

// --- FLOAT ---
// Numeros con decimales
$pi = 3.14159;
$cientifico = 1.5e3;  // 1.5 x 10^3 = 1500
echo "Pi: $pi" . PHP_EOL;
echo "Notacion cientifica: $cientifico" . PHP_EOL;
echo PHP_EOL;

// --- BOOLEAN ---
// Solo dos valores: true o false
// MUY IMPORTANTE: estos valores se evaluan como false:
//   false, 0, 0.0, "" (string vacio), "0", null, array vacio []
// Todo lo demas es true
$verdadero = true;
$falso = false;

echo "=== 2. COMPROBACION DE TIPOS ===" . PHP_EOL;

// is_string(), is_int(), is_float(), is_bool(), is_null(), is_array()
$valor = "Hola";
echo "Es string? " . (is_string($valor) ? "Si" : "No") . PHP_EOL;
echo "Es entero? " . (is_int($valor) ? "Si" : "No") . PHP_EOL;
echo PHP_EOL;

// isset() — comprueba si una variable existe y NO es null
$nombre = "Jesus";
$vacio = null;
echo "isset(\$nombre): " . (isset($nombre) ? "Si" : "No") . PHP_EOL;   // Si
echo "isset(\$vacio): " . (isset($vacio) ? "Si" : "No") . PHP_EOL;     // No
echo PHP_EOL;

// empty() — comprueba si una variable esta vacia (false, 0, "", null, [])
$texto = "";
$numero = 0;
$lleno = "Hola";
echo "empty(\"\") : " . (empty($texto) ? "Si" : "No") . PHP_EOL;       // Si
echo "empty(0)  : " . (empty($numero) ? "Si" : "No") . PHP_EOL;        // Si
echo "empty(\"Hola\"): " . (empty($lleno) ? "Si" : "No") . PHP_EOL;    // No
echo PHP_EOL;


echo "=== 3. CONVERSION DE TIPOS (CASTING) ===" . PHP_EOL;

// PHP puede convertir tipos automaticamente, pero tu puedes forzarlo:
$texto_numero = "42";
$como_entero = (int) $texto_numero;
$como_float = (float) "3.14";
$como_string = (string) 100;
$como_bool = (bool) "hola";  // cualquier string no vacio es true

echo "String '42' a entero: ";
var_dump($como_entero);
echo "String '3.14' a float: ";
var_dump($como_float);
echo "Entero 100 a string: ";
var_dump($como_string);
echo "String 'hola' a boolean: ";
var_dump($como_bool);
echo PHP_EOL;

// intval(), floatval(), strval() — funciones alternativas
echo "intval('99 botellas'): " . intval("99 botellas") . PHP_EOL;  // 99
echo PHP_EOL;


echo "=== 4. OPERADORES ARITMETICOS ===" . PHP_EOL;

$a = 15;
$b = 4;
echo "a = $a, b = $b" . PHP_EOL;
echo "Suma:       a + b  = " . ($a + $b) . PHP_EOL;    // 19
echo "Resta:      a - b  = " . ($a - $b) . PHP_EOL;    // 11
echo "Multiplicar: a * b = " . ($a * $b) . PHP_EOL;    // 60
echo "Division:   a / b  = " . ($a / $b) . PHP_EOL;    // 3.75
echo "Modulo:     a % b  = " . ($a % $b) . PHP_EOL;    // 3
echo "Potencia:   a ** b = " . ($a ** $b) . PHP_EOL;   // 50625
echo PHP_EOL;


echo "=== 5. OPERADORES DE ASIGNACION ===" . PHP_EOL;

$x = 10;
echo "x = $x" . PHP_EOL;

$x += 5;   // x = x + 5
echo "x += 5  -> x = $x" . PHP_EOL;   // 15

$x -= 3;   // x = x - 3
echo "x -= 3  -> x = $x" . PHP_EOL;   // 12

$x *= 2;   // x = x * 2
echo "x *= 2  -> x = $x" . PHP_EOL;   // 24

$x /= 4;   // x = x / 4
echo "x /= 4  -> x = $x" . PHP_EOL;   // 6

$x %= 4;   // x = x % 4
echo "x %%= 4 -> x = $x" . PHP_EOL;   // 2

// Concatenacion con .=
$saludo = "Hola";
$saludo .= " Mundo";   // $saludo = $saludo . " Mundo"
echo "saludo .= ' Mundo' -> $saludo" . PHP_EOL;
echo PHP_EOL;

// Incremento y decremento
$contador = 5;
$contador++;  // incrementa en 1 (ahora vale 6)
echo "contador++ -> $contador" . PHP_EOL;
$contador--;  // decrementa en 1 (ahora vale 5)
echo "contador-- -> $contador" . PHP_EOL;
echo PHP_EOL;


echo "=== 6. OPERADORES DE COMPARACION ===" . PHP_EOL;

// Estos operadores devuelven true o false
// == (igual valor) vs === (igual valor Y tipo)
echo "5 == '5'  : " . (5 == '5' ? "true" : "false") . PHP_EOL;    // true  (compara solo valor)
echo "5 === '5' : " . (5 === '5' ? "true" : "false") . PHP_EOL;   // false (distinto tipo)
echo "5 != '5'  : " . (5 != '5' ? "true" : "false") . PHP_EOL;    // false
echo "5 !== '5' : " . (5 !== '5' ? "true" : "false") . PHP_EOL;   // true
echo PHP_EOL;

echo "10 > 5  : " . (10 > 5 ? "true" : "false") . PHP_EOL;
echo "10 < 5  : " . (10 < 5 ? "true" : "false") . PHP_EOL;
echo "10 >= 10: " . (10 >= 10 ? "true" : "false") . PHP_EOL;
echo "10 <= 5 : " . (10 <= 5 ? "true" : "false") . PHP_EOL;
echo PHP_EOL;

// Operador nave espacial <=> (devuelve -1, 0, o 1)
echo "1 <=> 2: " . (1 <=> 2) . PHP_EOL;    // -1
echo "2 <=> 2: " . (2 <=> 2) . PHP_EOL;    //  0
echo "3 <=> 2: " . (3 <=> 2) . PHP_EOL;    //  1
echo PHP_EOL;


echo "=== 7. OPERADORES LOGICOS ===" . PHP_EOL;

// && (AND), || (OR), ! (NOT)
$edad = 25;
$tiene_dni = true;

echo "edad >= 18 && tiene_dni: " . ($edad >= 18 && $tiene_dni ? "true" : "false") . PHP_EOL;
echo "edad < 18 || tiene_dni:  " . ($edad < 18 || $tiene_dni ? "true" : "false") . PHP_EOL;
echo "!tiene_dni:              " . (!$tiene_dni ? "true" : "false") . PHP_EOL;
echo PHP_EOL;


echo "=== 8. OPERADOR TERNARIO ===" . PHP_EOL;

// Es un if/else en una sola linea:
// condicion ? valor_si_true : valor_si_false

$nota = 7;
$resultado = $nota >= 5 ? "Aprobado" : "Suspendido";
echo "Nota $nota: $resultado" . PHP_EOL;

// Operador null coalescing ?? (devuelve el primer valor que no sea null)
$usuario = null;
$nombre_mostrar = $usuario ?? "Anonimo";
echo "Usuario: $nombre_mostrar" . PHP_EOL;
echo PHP_EOL;


echo "=== RESUMEN ===" . PHP_EOL;
echo "1. Tipos: string, int, float, bool, null" . PHP_EOL;
echo "2. Comprobar tipos: is_string(), isset(), empty()" . PHP_EOL;
echo "3. Casting: (int), (float), (string), (bool)" . PHP_EOL;
echo "4. Aritmeticos: + - * / % **" . PHP_EOL;
echo "5. Asignacion: += -= *= /= .= ++ --" . PHP_EOL;
echo "6. Comparacion: == === != !== > < >= <= <=>" . PHP_EOL;
echo "7. Logicos: && || !" . PHP_EOL;
echo "8. Ternario: ? : y null coalescing: ??" . PHP_EOL;
