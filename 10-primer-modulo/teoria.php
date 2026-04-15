<?php

/**
 * =============================================================
 *  LECCION 10: TU PRIMER MODULO CUSTOM
 * =============================================================
 *  Vamos a crear un modulo Drupal desde cero.
 *  Aprenderemos la estructura minima y los archivos necesarios.
 * =============================================================
 */

echo "=== 1. ESTRUCTURA DE UN MODULO ===" . PHP_EOL;
echo PHP_EOL;
echo "Un modulo vive en: web/modules/custom/nombre_modulo/" . PHP_EOL;
echo "El nombre debe ser: minusculas, sin espacios, guiones bajos." . PHP_EOL;
echo PHP_EOL;
echo "Estructura minima:" . PHP_EOL;
echo "  hola_mundo/" . PHP_EOL;
echo "  ├── hola_mundo.info.yml      <- OBLIGATORIO: metadatos del modulo" . PHP_EOL;
echo "  ├── hola_mundo.routing.yml   <- rutas (URLs)" . PHP_EOL;
echo "  ├── hola_mundo.module        <- hooks (funciones)" . PHP_EOL;
echo "  ├── hola_mundo.services.yml  <- servicios" . PHP_EOL;
echo "  └── src/                     <- clases PHP (PSR-4)" . PHP_EOL;
echo "      ├── Controller/          <- controladores" . PHP_EOL;
echo "      ├── Form/                <- formularios" . PHP_EOL;
echo "      └── Plugin/              <- plugins (bloques, etc.)" . PHP_EOL;
echo PHP_EOL;


echo "=== 2. ARCHIVO .info.yml ===" . PHP_EOL;
echo PHP_EOL;
echo "Es la tarjeta de identidad del modulo. Sin este archivo, Drupal" . PHP_EOL;
echo "no sabe que el modulo existe." . PHP_EOL;
echo PHP_EOL;

$info_yml = <<<'YAML'
name: Hola Mundo
type: module
description: 'Mi primer modulo custom de Drupal'
core_version_requirement: ^10 || ^11
package: Custom
YAML;

echo "Contenido de hola_mundo.info.yml:" . PHP_EOL;
echo "---" . PHP_EOL;
echo $info_yml . PHP_EOL;
echo "---" . PHP_EOL;
echo PHP_EOL;
echo "Campos:" . PHP_EOL;
echo "  name: nombre visible en la admin" . PHP_EOL;
echo "  type: siempre 'module' (o 'theme' para temas)" . PHP_EOL;
echo "  description: descripcion corta" . PHP_EOL;
echo "  core_version_requirement: compatibilidad" . PHP_EOL;
echo "  package: grupo en la pagina de modulos" . PHP_EOL;
echo PHP_EOL;


echo "=== 3. ARCHIVO .routing.yml ===" . PHP_EOL;
echo PHP_EOL;
echo "Define las URLs (rutas) de tu modulo y que controlador las maneja." . PHP_EOL;
echo PHP_EOL;

$routing_yml = <<<'YAML'
hola_mundo.saludo:
  path: '/hola'
  defaults:
    _controller: '\Drupal\hola_mundo\Controller\HolaMundoController::saludo'
    _title: 'Hola Mundo'
  requirements:
    _permission: 'access content'
YAML;

echo "Contenido de hola_mundo.routing.yml:" . PHP_EOL;
echo "---" . PHP_EOL;
echo $routing_yml . PHP_EOL;
echo "---" . PHP_EOL;
echo PHP_EOL;
echo "Partes de una ruta:" . PHP_EOL;
echo "  hola_mundo.saludo  -> nombre unico de la ruta" . PHP_EOL;
echo "  path               -> la URL (/hola)" . PHP_EOL;
echo "  _controller        -> clase y metodo que responde" . PHP_EOL;
echo "  _title             -> titulo de la pagina" . PHP_EOL;
echo "  _permission        -> permiso necesario para acceder" . PHP_EOL;
echo PHP_EOL;


echo "=== 4. CONTROLLER (CONTROLADOR) ===" . PHP_EOL;
echo PHP_EOL;
echo "El controlador es la clase que procesa la peticion y devuelve" . PHP_EOL;
echo "un render array (que Drupal convierte en HTML)." . PHP_EOL;
echo PHP_EOL;
echo "Archivo: src/Controller/HolaMundoController.php" . PHP_EOL;
echo "Namespace: Drupal\\hola_mundo\\Controller" . PHP_EOL;
echo PHP_EOL;

echo <<<'CODE'
<?php

namespace Drupal\hola_mundo\Controller;

use Drupal\Core\Controller\ControllerBase;

class HolaMundoController extends ControllerBase {

  public function saludo() {
    return [
      '#markup' => '<h2>Hola Mundo desde mi primer modulo!</h2>
                     <p>Si ves esto, tu modulo funciona correctamente.</p>',
    ];
  }

}
CODE;
echo PHP_EOL . PHP_EOL;

echo "Puntos clave:" . PHP_EOL;
echo "  - Extiende ControllerBase (clase base de Drupal)" . PHP_EOL;
echo "  - El metodo devuelve un RENDER ARRAY, no HTML directo" . PHP_EOL;
echo "  - El namespace sigue PSR-4: Drupal\\modulo\\Controller" . PHP_EOL;
echo "  - #markup es la forma mas simple de devolver HTML" . PHP_EOL;
echo PHP_EOL;


echo "=== 5. ACTIVAR EL MODULO ===" . PHP_EOL;
echo PHP_EOL;
echo "Una vez creados los archivos:" . PHP_EOL;
echo "  ddev drush en hola_mundo -y    <- activar el modulo" . PHP_EOL;
echo "  ddev drush cr                  <- limpiar cache" . PHP_EOL;
echo "  ddev launch /hola              <- abrir la pagina" . PHP_EOL;
echo PHP_EOL;


echo "=== 6. RUTAS CON PARAMETROS ===" . PHP_EOL;
echo PHP_EOL;
echo "Puedes pasar datos por la URL:" . PHP_EOL;
echo PHP_EOL;

$routing_param = <<<'YAML'
hola_mundo.saludo_nombre:
  path: '/hola/{nombre}'
  defaults:
    _controller: '\Drupal\hola_mundo\Controller\HolaMundoController::saludoNombre'
    _title: 'Saludo personalizado'
  requirements:
    _permission: 'access content'
YAML;

echo "Ruta con parametro:" . PHP_EOL;
echo "---" . PHP_EOL;
echo $routing_param . PHP_EOL;
echo "---" . PHP_EOL;
echo PHP_EOL;

echo "Controlador:" . PHP_EOL;
echo <<<'CODE'
  public function saludoNombre(string $nombre) {
    return [
      '#markup' => "<h2>Hola, $nombre!</h2>",
    ];
  }
CODE;
echo PHP_EOL . PHP_EOL;
echo "Ahora /hola/Jesus muestra 'Hola, Jesus!'" . PHP_EOL;
echo PHP_EOL;


echo "=== 7. RENDER ARRAYS UTILES ===" . PHP_EOL;
echo PHP_EOL;

echo "--- Markup simple ---" . PHP_EOL;
$markup = [
    '#markup' => '<p>Texto con HTML</p>',
];
print_r($markup);
echo PHP_EOL;

echo "--- Lista ---" . PHP_EOL;
$lista = [
    '#theme' => 'item_list',
    '#title' => 'Mis items',
    '#items' => ['Item 1', 'Item 2', 'Item 3'],
];
print_r($lista);
echo PHP_EOL;

echo "--- Tabla ---" . PHP_EOL;
$tabla = [
    '#type' => 'table',
    '#header' => ['Nombre', 'Email'],
    '#rows' => [
        ['Jesus', 'jesus@ejemplo.com'],
        ['Ana', 'ana@ejemplo.com'],
    ],
];
print_r($tabla);
echo PHP_EOL;

echo "--- Enlace ---" . PHP_EOL;
echo "  use Drupal\\Core\\Link;" . PHP_EOL;
echo "  use Drupal\\Core\\Url;" . PHP_EOL;
echo '  $link = Link::fromTextAndUrl("Ir a inicio", Url::fromRoute("<front>"));' . PHP_EOL;
echo PHP_EOL;


echo "=== 8. ARCHIVO .module (HOOKS) ===" . PHP_EOL;
echo PHP_EOL;
echo "El archivo .module contiene hooks: funciones que Drupal" . PHP_EOL;
echo "llama en momentos especificos." . PHP_EOL;
echo PHP_EOL;

echo "Archivo: hola_mundo.module" . PHP_EOL;
echo <<<'CODE'
<?php

/**
 * @file
 * Modulo Hola Mundo.
 */

/**
 * Implements hook_help().
 */
function hola_mundo_help($route_name, $route_match) {
  if ($route_name === 'help.page.hola_mundo') {
    return '<p>Este es mi primer modulo de Drupal!</p>';
  }
}
CODE;
echo PHP_EOL . PHP_EOL;


echo "=== RESUMEN ===" . PHP_EOL;
echo "1. .info.yml: identidad del modulo (obligatorio)" . PHP_EOL;
echo "2. .routing.yml: define las URLs" . PHP_EOL;
echo "3. Controller: procesa peticiones, devuelve render arrays" . PHP_EOL;
echo "4. Namespace PSR-4: Drupal\\modulo\\Controller\\Clase" . PHP_EOL;
echo "5. drush en/cr: activar modulo y limpiar cache" . PHP_EOL;
echo "6. .module: hooks (funciones especiales)" . PHP_EOL;
