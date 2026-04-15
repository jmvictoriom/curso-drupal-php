<?php

/**
 * =============================================================
 *  EJERCICIO 09: INSTALAR DRUPAL
 * =============================================================
 *  Este ejercicio se hace en la terminal.
 *  Sigue cada paso y marca como completado.
 * =============================================================
 */

echo "=== CHECKLIST DE INSTALACION ===" . PHP_EOL;
echo PHP_EOL;

// Marca cada paso como true cuando lo completes
$pasos = [
    "Docker instalado y corriendo" => false,
    "DDEV instalado (brew install ddev/ddev/ddev)" => false,
    "Proyecto creado con ddev config" => false,
    "ddev start ejecutado" => false,
    "Drupal instalado con composer create" => false,
    "Drush instalado (composer require drush/drush)" => false,
    "Site install ejecutado" => false,
    "Puedo acceder a Drupal en el navegador" => false,
    "Puedo hacer login como admin" => false,
    "He creado mi primer articulo" => false,
    "He instalado admin_toolbar" => false,
    "He instalado devel" => false,
];

$completados = 0;
foreach ($pasos as $paso => $hecho) {
    $check = $hecho ? "[X]" : "[ ]";
    echo "$check $paso" . PHP_EOL;
    if ($hecho) $completados++;
}

echo PHP_EOL;
echo "Progreso: $completados/" . count($pasos) . PHP_EOL;

if ($completados === count($pasos)) {
    echo "PERFECTO! Drupal esta listo. Siguiente leccion: crear tu primer modulo!" . PHP_EOL;
} else {
    echo "Sigue los pasos de la teoria para completar la instalacion." . PHP_EOL;
    echo "Si te atascas, pidele ayuda a Claude!" . PHP_EOL;
}

echo PHP_EOL;
echo "=== COMANDOS RAPIDOS DE REFERENCIA ===" . PHP_EOL;
echo PHP_EOL;
echo "# Crear el proyecto" . PHP_EOL;
echo "mkdir ~/drupal-proyecto && cd ~/drupal-proyecto" . PHP_EOL;
echo "ddev config --project-type=drupal --docroot=web --php-version=8.3" . PHP_EOL;
echo "ddev start" . PHP_EOL;
echo "ddev composer create drupal/recommended-project" . PHP_EOL;
echo "ddev composer require drush/drush" . PHP_EOL;
echo PHP_EOL;
echo "# Instalar Drupal" . PHP_EOL;
echo "ddev drush site:install standard --account-name=admin --account-pass=admin -y" . PHP_EOL;
echo PHP_EOL;
echo "# Abrir y modulos basicos" . PHP_EOL;
echo "ddev launch" . PHP_EOL;
echo "ddev composer require drupal/admin_toolbar drupal/devel" . PHP_EOL;
echo "ddev drush en admin_toolbar admin_toolbar_tools devel -y" . PHP_EOL;
echo "ddev drush cr" . PHP_EOL;
