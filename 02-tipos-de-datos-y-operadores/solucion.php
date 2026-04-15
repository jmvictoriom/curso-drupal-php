<?php

/**
 * =============================================================
 *  SOLUCION 02: TIPOS DE DATOS Y OPERADORES
 * =============================================================
 */

// EJERCICIO 1
echo "--- Ejercicio 1 ---" . PHP_EOL;
$texto = "123";
$numero = 45;
$decimal = "9.99";

$texto_int = (int) $texto;
$numero_str = (string) $numero;
$decimal_float = (float) $decimal;

var_dump($texto_int);
var_dump($numero_str);
var_dump($decimal_float);


// EJERCICIO 2
echo PHP_EOL . "--- Ejercicio 2 ---" . PHP_EOL;
$base = 12;
$altura_rect = 7;
$area = $base * $altura_rect;
echo "El area del rectangulo es: $area" . PHP_EOL;

$resto = 27 % 5;
echo "El resto de 27 / 5 es: $resto" . PHP_EOL;


// EJERCICIO 3
echo PHP_EOL . "--- Ejercicio 3 ---" . PHP_EOL;
$saldo = 1000;
echo "Saldo inicial: $saldo" . PHP_EOL;

$saldo += 500;
echo "Despues de sumar 500: $saldo" . PHP_EOL;

$saldo -= 200;
echo "Despues de restar 200: $saldo" . PHP_EOL;

$saldo *= 2;
echo "Despues de multiplicar por 2: $saldo" . PHP_EOL;

$saldo /= 4;
echo "Despues de dividir entre 4: $saldo" . PHP_EOL;


// EJERCICIO 4
echo PHP_EOL . "--- Ejercicio 4 ---" . PHP_EOL;
echo "10 == '10' : " . (10 == '10' ? "true" : "false") . PHP_EOL;
echo "10 === '10': " . (10 === '10' ? "true" : "false") . PHP_EOL;
echo "0 == false : " . (0 == false ? "true" : "false") . PHP_EOL;
echo "0 === false: " . (0 === false ? "true" : "false") . PHP_EOL;
echo "'' == false: " . ('' == false ? "true" : "false") . PHP_EOL;
echo "null == false: " . (null == false ? "true" : "false") . PHP_EOL;


// EJERCICIO 5
echo PHP_EOL . "--- Ejercicio 5 ---" . PHP_EOL;
$temperatura = 35;
$estado = $temperatura >= 30 ? "Hace calor" : "Temperatura agradable";
echo "Con $temperatura grados: $estado" . PHP_EOL;


// EJERCICIO 6
echo PHP_EOL . "--- Ejercicio 6 ---" . PHP_EOL;
$nombre_usuario = null;
echo "Usuario: " . ($nombre_usuario ?? "Invitado") . PHP_EOL;

$es_mayor_de_edad = true;
$tiene_entrada = false;
$puede_entrar = $es_mayor_de_edad && $tiene_entrada;
echo $puede_entrar ? "Puede entrar" : "No puede entrar";
echo PHP_EOL;


echo PHP_EOL . "Ejercicios completados! Pidele a Claude que revise tu codigo." . PHP_EOL;
