<?php

/**
 * =============================================================
 *  LECCION 11: ROUTING, CONTROLLERS Y SERVICES
 * =============================================================
 *  Profundizamos en el sistema de rutas, controladores avanzados
 *  y el Service Container (Dependency Injection).
 * =============================================================
 */

echo "=== 1. ROUTING AVANZADO ===" . PHP_EOL;
echo PHP_EOL;

echo "--- Parametros con restricciones ---" . PHP_EOL;
$ruta1 = <<<'YAML'
mi_modulo.ver_articulo:
  path: '/articulo/{id}'
  defaults:
    _controller: '\Drupal\mi_modulo\Controller\ArticuloController::ver'
    _title: 'Ver articulo'
  requirements:
    _permission: 'access content'
    id: '\d+'           # <-- solo numeros (regex)
YAML;
echo $ruta1 . PHP_EOL . PHP_EOL;

echo "--- Parametros opcionales ---" . PHP_EOL;
$ruta2 = <<<'YAML'
mi_modulo.listar:
  path: '/listado/{pagina}'
  defaults:
    _controller: '\Drupal\mi_modulo\Controller\ListaController::listar'
    _title: 'Listado'
    pagina: 1              # <-- valor por defecto si no se pasa
  requirements:
    _permission: 'access content'
    pagina: '\d+'
YAML;
echo $ruta2 . PHP_EOL . PHP_EOL;

echo "--- Permisos personalizados ---" . PHP_EOL;
echo "Puedes crear tus propios permisos en mi_modulo.permissions.yml:" . PHP_EOL;
$permisos = <<<'YAML'
# mi_modulo.permissions.yml
administrar mi modulo:
  title: 'Administrar Mi Modulo'
  description: 'Permite configurar el modulo personalizado'
YAML;
echo $permisos . PHP_EOL;
echo PHP_EOL;
echo "Y usarlo en el routing:" . PHP_EOL;
echo "  _permission: 'administrar mi modulo'" . PHP_EOL;
echo PHP_EOL;

echo "--- Acceso por rol ---" . PHP_EOL;
echo "  _role: 'administrator'           # solo admins" . PHP_EOL;
echo "  _role: 'authenticated'           # solo logueados" . PHP_EOL;
echo PHP_EOL;

echo "--- Tipos de respuesta ---" . PHP_EOL;
echo "  _controller: para paginas HTML" . PHP_EOL;
echo "  _form: para formularios (lo veremos en leccion 13)" . PHP_EOL;
echo "  _entity_form: para formularios de entidades" . PHP_EOL;
echo PHP_EOL;


echo "=== 2. CONTROLLERS AVANZADOS ===" . PHP_EOL;
echo PHP_EOL;

echo "--- Controller con Dependency Injection ---" . PHP_EOL;
echo <<<'CODE'
<?php

namespace Drupal\mi_modulo\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Session\AccountProxyInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class MiController extends ControllerBase {

  // Inyectar servicios por constructor
  public function __construct(
    protected AccountProxyInterface $currentUser,
  ) {}

  // Este metodo le dice a Drupal como crear el controller
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('current_user'),
    );
  }

  public function dashboard() {
    $nombre = $this->currentUser->getDisplayName();
    return [
      '#markup' => "<h2>Bienvenido, $nombre!</h2>",
    ];
  }

}
CODE;
echo PHP_EOL . PHP_EOL;

echo "Puntos clave:" . PHP_EOL;
echo "  - create() es un 'factory method' que Drupal llama para crear el controller" . PHP_EOL;
echo "  - \$container->get('nombre_servicio') obtiene un servicio" . PHP_EOL;
echo "  - Los servicios se inyectan por constructor" . PHP_EOL;
echo "  - ControllerBase ya da acceso a servicios comunes via helpers" . PHP_EOL;
echo PHP_EOL;


echo "=== 3. SERVICIOS EN DRUPAL ===" . PHP_EOL;
echo PHP_EOL;
echo "Un servicio es un objeto gestionado por el Service Container." . PHP_EOL;
echo "Drupal tiene cientos de servicios disponibles." . PHP_EOL;
echo PHP_EOL;

echo "--- Servicios mas usados ---" . PHP_EOL;
echo "current_user              -> usuario logueado actual" . PHP_EOL;
echo "entity_type.manager       -> gestionar entidades" . PHP_EOL;
echo "messenger                 -> mostrar mensajes al usuario" . PHP_EOL;
echo "database                  -> conexion a BD" . PHP_EOL;
echo "logger.factory            -> escribir logs" . PHP_EOL;
echo "config.factory            -> leer configuracion" . PHP_EOL;
echo "path.current              -> ruta actual" . PHP_EOL;
echo "request_stack             -> acceso al request HTTP" . PHP_EOL;
echo "language_manager          -> idiomas" . PHP_EOL;
echo "module_handler            -> info de modulos" . PHP_EOL;
echo PHP_EOL;

echo "--- Helpers de ControllerBase ---" . PHP_EOL;
echo "Cuando extiendes ControllerBase, tienes metodos helper:" . PHP_EOL;
echo '  $this->currentUser()      -> usuario actual' . PHP_EOL;
echo '  $this->entityTypeManager() -> gestor de entidades' . PHP_EOL;
echo '  $this->config("system.site") -> leer config' . PHP_EOL;
echo '  $this->messenger()       -> mensajes' . PHP_EOL;
echo '  $this->t("texto")        -> traduccion' . PHP_EOL;
echo PHP_EOL;


echo "=== 4. CREAR TU PROPIO SERVICIO ===" . PHP_EOL;
echo PHP_EOL;

echo "--- Paso 1: Definir en .services.yml ---" . PHP_EOL;
$services_yml = <<<'YAML'
# mi_modulo.services.yml
services:
  mi_modulo.calculadora:
    class: Drupal\mi_modulo\Service\Calculadora

  mi_modulo.saludo:
    class: Drupal\mi_modulo\Service\SaludoService
    arguments: ['@current_user']    # inyectar otro servicio con @
YAML;
echo $services_yml . PHP_EOL . PHP_EOL;

echo "--- Paso 2: Crear la clase ---" . PHP_EOL;
echo <<<'CODE'
<?php
// src/Service/SaludoService.php

namespace Drupal\mi_modulo\Service;

use Drupal\Core\Session\AccountProxyInterface;

class SaludoService {

  public function __construct(
    protected AccountProxyInterface $currentUser,
  ) {}

  public function getSaludo(): string {
    $nombre = $this->currentUser->getDisplayName();
    $hora = (int) date('H');

    if ($hora < 12) {
      return "Buenos dias, $nombre!";
    } elseif ($hora < 20) {
      return "Buenas tardes, $nombre!";
    } else {
      return "Buenas noches, $nombre!";
    }
  }

}
CODE;
echo PHP_EOL . PHP_EOL;

echo "--- Paso 3: Usar el servicio en un Controller ---" . PHP_EOL;
echo <<<'CODE'
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('mi_modulo.saludo'),
    );
  }

  public function __construct(
    protected SaludoService $saludoService,
  ) {}

  public function pagina() {
    return [
      '#markup' => $this->saludoService->getSaludo(),
    ];
  }
CODE;
echo PHP_EOL . PHP_EOL;


echo "=== 5. RESPUESTAS AVANZADAS ===" . PHP_EOL;
echo PHP_EOL;

echo "--- Redireccion ---" . PHP_EOL;
echo <<<'CODE'
use Symfony\Component\HttpFoundation\RedirectResponse;
use Drupal\Core\Url;

public function redirigir() {
  $url = Url::fromRoute('mi_modulo.otra_pagina')->toString();
  return new RedirectResponse($url);
}
CODE;
echo PHP_EOL . PHP_EOL;

echo "--- Respuesta JSON ---" . PHP_EOL;
echo <<<'CODE'
use Symfony\Component\HttpFoundation\JsonResponse;

public function apiDatos() {
  $datos = ['nombre' => 'Jesus', 'edad' => 25];
  return new JsonResponse($datos);
}
CODE;
echo PHP_EOL . PHP_EOL;

echo "--- Pagina 404 ---" . PHP_EOL;
echo <<<'CODE'
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

public function ver(int $id) {
  $nodo = $this->entityTypeManager()->getStorage('node')->load($id);
  if (!$nodo) {
    throw new NotFoundHttpException();
  }
  // ...
}
CODE;
echo PHP_EOL . PHP_EOL;


echo "=== 6. LINKS Y URLS ===" . PHP_EOL;
echo PHP_EOL;
echo "--- Generar URLs ---" . PHP_EOL;
echo <<<'CODE'
use Drupal\Core\Url;

// URL a una ruta
$url = Url::fromRoute('mi_modulo.pagina');

// URL a una ruta con parametros
$url = Url::fromRoute('mi_modulo.ver', ['id' => 42]);

// URL externa
$url = Url::fromUri('https://drupal.org');

// URL al front page
$url = Url::fromRoute('<front>');
CODE;
echo PHP_EOL . PHP_EOL;

echo "--- Generar Links ---" . PHP_EOL;
echo <<<'CODE'
use Drupal\Core\Link;

$link = Link::fromTextAndUrl('Ver articulo', Url::fromRoute('mi_modulo.ver', ['id' => 1]));
$render = $link->toRenderable();  // render array
CODE;
echo PHP_EOL . PHP_EOL;


echo "=== RESUMEN ===" . PHP_EOL;
echo "1. Routing: parametros, restricciones, permisos custom" . PHP_EOL;
echo "2. Controllers: create() + constructor = Dependency Injection" . PHP_EOL;
echo "3. Servicios: objetos del contenedor (\$container->get())" . PHP_EOL;
echo "4. Servicios custom: .services.yml + clase PHP" . PHP_EOL;
echo "5. Respuestas: render array, redirect, JSON, 404" . PHP_EOL;
echo "6. URLs y Links: Url::fromRoute(), Link::fromTextAndUrl()" . PHP_EOL;
