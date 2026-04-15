<?php

/**
 * =============================================================
 *  EJERCICIO 08: ARQUITECTURA DE DRUPAL
 * =============================================================
 *  Este ejercicio es tipo cuestionario para afianzar conceptos.
 *  Rellena las respuestas en las variables.
 * =============================================================
 */

echo "=== CUESTIONARIO: ARQUITECTURA DRUPAL ===" . PHP_EOL;
echo PHP_EOL;

// -----------------------------------------------
// PREGUNTA 1
// -----------------------------------------------
// En que carpeta van los modulos que TU creas?
// a) web/core/modules/
// b) web/modules/contrib/
// c) web/modules/custom/
// d) vendor/modules/

$respuesta_1 = ""; // Escribe "a", "b", "c" o "d"
echo "P1: " . ($respuesta_1 === "c" ? "CORRECTO!" : "Revisa la leccion (pista: custom)") . PHP_EOL;


// -----------------------------------------------
// PREGUNTA 2
// -----------------------------------------------
// Que es un "Render Array" en Drupal?
// a) Un array de JavaScript para renderizar componentes
// b) Un array PHP que describe como generar HTML
// c) Un array de imagenes para renderizar
// d) Un array de rutas del sitio

$respuesta_2 = ""; // Escribe "a", "b", "c" o "d"
echo "P2: " . ($respuesta_2 === "b" ? "CORRECTO!" : "Revisa la seccion de Render Arrays") . PHP_EOL;


// -----------------------------------------------
// PREGUNTA 3
// -----------------------------------------------
// Que herramienta gestiona las dependencias en Drupal?
// a) npm
// b) pip
// c) composer
// d) apt

$respuesta_3 = ""; // Escribe "a", "b", "c" o "d"
echo "P3: " . ($respuesta_3 === "c" ? "CORRECTO!" : "Pista: lo vimos en la leccion 07") . PHP_EOL;


// -----------------------------------------------
// PREGUNTA 4
// -----------------------------------------------
// Que son las "entidades" en Drupal?
// a) Los archivos CSS del tema
// b) Los objetos principales de datos (nodos, usuarios, taxonomias...)
// c) Los plugins de JavaScript
// d) Las tablas de la base de datos

$respuesta_4 = ""; // Escribe "a", "b", "c" o "d"
echo "P4: " . ($respuesta_4 === "b" ? "CORRECTO!" : "Revisa la seccion de Entidades") . PHP_EOL;


// -----------------------------------------------
// PREGUNTA 5
// -----------------------------------------------
// Que motor de plantillas usa Drupal 10?
// a) Blade
// b) Smarty
// c) Twig
// d) Handlebars

$respuesta_5 = ""; // Escribe "a", "b", "c" o "d"
echo "P5: " . ($respuesta_5 === "c" ? "CORRECTO!" : "Pista: es de Symfony") . PHP_EOL;


// -----------------------------------------------
// PREGUNTA 6
// -----------------------------------------------
// Que comando de Drush exporta la configuracion?
// a) drush config:export
// b) drush site:install
// c) drush cache:rebuild
// d) drush module:install

$respuesta_6 = ""; // Escribe "a", "b", "c" o "d"
echo "P6: " . ($respuesta_6 === "a" ? "CORRECTO!" : "Revisa la seccion de Config Management") . PHP_EOL;


// -----------------------------------------------
// PREGUNTA 7
// -----------------------------------------------
// En un render array, las claves que empiezan con # son:
// a) Comentarios
// b) Propiedades de renderizado
// c) IDs de HTML
// d) Variables CSS

$respuesta_7 = ""; // Escribe "a", "b", "c" o "d"
echo "P7: " . ($respuesta_7 === "b" ? "CORRECTO!" : "Revisa: # = propiedad, sin # = hijo") . PHP_EOL;


// -----------------------------------------------
// PREGUNTA 8 (ABIERTA)
// -----------------------------------------------
// Describe con tus palabras el ciclo de una peticion en Drupal.
// No hay respuesta unica, pero debe incluir los pasos principales.
// Escribe tu respuesta como string:

$respuesta_8 = ""; // Escribe tu respuesta aqui
if (strlen($respuesta_8) > 20) {
    echo "P8: Has escrito una respuesta. Pidele a Claude que la revise!" . PHP_EOL;
} else {
    echo "P8: Escribe tu respuesta (mas de 20 caracteres)" . PHP_EOL;
}

echo PHP_EOL;

// Cuenta las correctas
$correctas = 0;
if ($respuesta_1 === "c") $correctas++;
if ($respuesta_2 === "b") $correctas++;
if ($respuesta_3 === "c") $correctas++;
if ($respuesta_4 === "b") $correctas++;
if ($respuesta_5 === "c") $correctas++;
if ($respuesta_6 === "a") $correctas++;
if ($respuesta_7 === "b") $correctas++;

echo "Resultado: $correctas/7 correctas" . PHP_EOL;
if ($correctas === 7) {
    echo "PERFECTO! Listo para la siguiente leccion!" . PHP_EOL;
} elseif ($correctas >= 5) {
    echo "Bien! Repasa las que fallaste y sigue adelante." . PHP_EOL;
} else {
    echo "Necesitas repasar la teoria antes de continuar." . PHP_EOL;
}
