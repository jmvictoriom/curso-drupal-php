<?php

/**
 * =============================================================
 *  LECCION 22: REST API Y WEB SERVICES
 * =============================================================
 *  Drupal puede funcionar como backend API. Tiene soporte
 *  integrado para REST, JSON:API y GraphQL. Aqui aprendemos
 *  a exponer y consumir datos via API.
 * =============================================================
 */

echo "=== 1. OPCIONES DE API EN DRUPAL ===" . PHP_EOL;
echo PHP_EOL;
echo "Drupal ofrece varias formas de servir APIs:" . PHP_EOL;
echo PHP_EOL;
echo "  REST (core):" . PHP_EOL;
echo "    - Modulo rest incluido en core" . PHP_EOL;
echo "    - Control total sobre endpoints" . PHP_EOL;
echo "    - Ideal para APIs custom" . PHP_EOL;
echo PHP_EOL;
echo "  JSON:API (core):" . PHP_EOL;
echo "    - Sigue la especificacion JSON:API" . PHP_EOL;
echo "    - Automatico para TODAS las entidades" . PHP_EOL;
echo "    - Relaciones, filtros, paginacion incluidos" . PHP_EOL;
echo "    - Ideal para headless Drupal (con React, Vue, etc.)" . PHP_EOL;
echo PHP_EOL;
echo "  GraphQL (contrib):" . PHP_EOL;
echo "    - Modulo contribuido (graphql)" . PHP_EOL;
echo "    - Query language flexible" . PHP_EOL;
echo PHP_EOL;

echo "=== 2. HABILITAR LOS MODULOS ===" . PHP_EOL;
echo PHP_EOL;
echo "ddev drush en rest serialization jsonapi -y" . PHP_EOL;
echo "ddev drush cr" . PHP_EOL;
echo PHP_EOL;
echo "Modulos necesarios:" . PHP_EOL;
echo "  - rest: provee la infraestructura REST" . PHP_EOL;
echo "  - serialization: serializa datos a JSON/XML/HAL" . PHP_EOL;
echo "  - jsonapi: provee endpoints JSON:API automaticos" . PHP_EOL;
echo "  - basic_auth: autenticacion HTTP basica (opcional)" . PHP_EOL;
echo PHP_EOL;

echo "=== 3. JSON:API (LO MAS FACIL) ===" . PHP_EOL;
echo PHP_EOL;
echo "JSON:API expone AUTOMATICAMENTE todas las entidades." . PHP_EOL;
echo "No necesitas escribir codigo!" . PHP_EOL;
echo PHP_EOL;
echo "--- Endpoints automaticos ---" . PHP_EOL;
echo <<<'CODE'
# Listar articulos
GET /jsonapi/node/article

# Un articulo especifico
GET /jsonapi/node/article/{uuid}

# Filtrar articulos publicados
GET /jsonapi/node/article?filter[status]=1

# Filtrar por titulo
GET /jsonapi/node/article?filter[title]=Mi%20Articulo

# Incluir relaciones (autor, tags)
GET /jsonapi/node/article?include=uid,field_tags

# Seleccionar campos especificos
GET /jsonapi/node/article?fields[node--article]=title,body,created

# Paginacion
GET /jsonapi/node/article?page[limit]=10&page[offset]=0

# Ordenar
GET /jsonapi/node/article?sort=-created

# Usuarios
GET /jsonapi/user/user

# Taxonomia
GET /jsonapi/taxonomy_term/tags

# Crear un articulo (POST)
POST /jsonapi/node/article
Content-Type: application/vnd.api+json

{
  "data": {
    "type": "node--article",
    "attributes": {
      "title": "Nuevo articulo via API",
      "body": {
        "value": "<p>Contenido del articulo</p>",
        "format": "full_html"
      }
    }
  }
}

# Actualizar un articulo (PATCH)
PATCH /jsonapi/node/article/{uuid}
Content-Type: application/vnd.api+json

{
  "data": {
    "type": "node--article",
    "id": "{uuid}",
    "attributes": {
      "title": "Titulo actualizado"
    }
  }
}

# Eliminar un articulo (DELETE)
DELETE /jsonapi/node/article/{uuid}
CODE;
echo PHP_EOL . PHP_EOL;

echo "--- Probar con curl ---" . PHP_EOL;
echo <<<'CODE'
# Listar articulos
curl -s http://mi-drupal.ddev.site/jsonapi/node/article | jq .

# Crear articulo (autenticado)
curl -X POST \
  -H "Content-Type: application/vnd.api+json" \
  -u admin:password \
  http://mi-drupal.ddev.site/jsonapi/node/article \
  -d '{
    "data": {
      "type": "node--article",
      "attributes": {
        "title": "Desde la API"
      }
    }
  }'
CODE;
echo PHP_EOL . PHP_EOL;

echo "=== 4. REST RESOURCES CUSTOM ===" . PHP_EOL;
echo PHP_EOL;
echo "Para endpoints con logica personalizada, creamos REST Resources." . PHP_EOL;
echo "Son plugins en src/Plugin/rest/resource/" . PHP_EOL;
echo PHP_EOL;

echo "--- REST Resource basico (GET) ---" . PHP_EOL;
echo <<<'CODE'
<?php
// src/Plugin/rest/resource/InfoSitioResource.php

namespace Drupal\mi_modulo\Plugin\rest\resource;

use Drupal\rest\Plugin\ResourceBase;
use Drupal\rest\ResourceResponse;

/**
 * Devuelve informacion del sitio.
 *
 * @RestResource(
 *   id = "info_sitio",
 *   label = @Translation("Informacion del Sitio"),
 *   uri_paths = {
 *     "canonical" = "/api/v1/info-sitio",
 *   }
 * )
 */
class InfoSitioResource extends ResourceBase {

  /**
   * Responde a peticiones GET.
   */
  public function get() {
    $config = \Drupal::config('system.site');

    $data = [
      'nombre' => $config->get('name'),
      'slogan' => $config->get('slogan'),
      'email' => $config->get('mail'),
      'timestamp' => time(),
    ];

    $response = new ResourceResponse($data, 200);

    // Importante: agregar cache metadata
    $response->addCacheableDependency($config);

    return $response;
  }

}
CODE;
echo PHP_EOL . PHP_EOL;

echo "--- REST Resource CRUD completo ---" . PHP_EOL;
echo <<<'CODE'
<?php
// src/Plugin/rest/resource/TareasResource.php

namespace Drupal\mi_modulo\Plugin\rest\resource;

use Drupal\rest\Plugin\ResourceBase;
use Drupal\rest\ModifiedResourceResponse;
use Drupal\rest\ResourceResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

/**
 * API para gestionar tareas.
 *
 * @RestResource(
 *   id = "tareas",
 *   label = @Translation("Tareas API"),
 *   uri_paths = {
 *     "canonical" = "/api/v1/tareas/{id}",
 *     "create" = "/api/v1/tareas",
 *   }
 * )
 */
class TareasResource extends ResourceBase {

  /**
   * GET /api/v1/tareas/{id}
   * Obtener una tarea por ID.
   */
  public function get($id) {
    $storage = \Drupal::entityTypeManager()->getStorage('node');
    $node = $storage->load($id);

    if (!$node || $node->bundle() !== 'tarea') {
      throw new NotFoundHttpException("Tarea $id no encontrada.");
    }

    $data = [
      'id' => (int) $node->id(),
      'titulo' => $node->getTitle(),
      'descripcion' => $node->get('body')->value,
      'estado' => $node->get('field_estado')->value,
      'creado' => date('Y-m-d H:i:s', $node->getCreatedTime()),
    ];

    return new ResourceResponse($data);
  }

  /**
   * POST /api/v1/tareas
   * Crear una nueva tarea.
   */
  public function post($data) {
    if (empty($data['titulo'])) {
      throw new BadRequestHttpException('El campo titulo es obligatorio.');
    }

    $node = \Drupal::entityTypeManager()
      ->getStorage('node')
      ->create([
        'type' => 'tarea',
        'title' => $data['titulo'],
        'body' => [
          'value' => $data['descripcion'] ?? '',
          'format' => 'plain_text',
        ],
        'field_estado' => $data['estado'] ?? 'pendiente',
      ]);

    $node->save();

    $respuesta = [
      'mensaje' => 'Tarea creada correctamente.',
      'id' => (int) $node->id(),
      'titulo' => $node->getTitle(),
    ];

    // ModifiedResourceResponse: para POST/PATCH/DELETE (sin cache)
    return new ModifiedResourceResponse($respuesta, 201);
  }

  /**
   * PATCH /api/v1/tareas/{id}
   * Actualizar una tarea existente.
   */
  public function patch($id, $data) {
    $storage = \Drupal::entityTypeManager()->getStorage('node');
    $node = $storage->load($id);

    if (!$node || $node->bundle() !== 'tarea') {
      throw new NotFoundHttpException("Tarea $id no encontrada.");
    }

    if (isset($data['titulo'])) {
      $node->setTitle($data['titulo']);
    }
    if (isset($data['descripcion'])) {
      $node->set('body', [
        'value' => $data['descripcion'],
        'format' => 'plain_text',
      ]);
    }
    if (isset($data['estado'])) {
      $node->set('field_estado', $data['estado']);
    }

    $node->save();

    return new ModifiedResourceResponse([
      'mensaje' => 'Tarea actualizada.',
      'id' => (int) $node->id(),
    ], 200);
  }

  /**
   * DELETE /api/v1/tareas/{id}
   * Eliminar una tarea.
   */
  public function delete($id) {
    $storage = \Drupal::entityTypeManager()->getStorage('node');
    $node = $storage->load($id);

    if (!$node || $node->bundle() !== 'tarea') {
      throw new NotFoundHttpException("Tarea $id no encontrada.");
    }

    $node->delete();

    return new ModifiedResourceResponse([
      'mensaje' => "Tarea $id eliminada.",
    ], 200);
  }

}
CODE;
echo PHP_EOL . PHP_EOL;

echo "=== 5. CONFIGURAR REST RESOURCES ===" . PHP_EOL;
echo PHP_EOL;
echo "Los REST resources necesitan configuracion para activarse." . PHP_EOL;
echo PHP_EOL;
echo "--- Opcion 1: REST UI (modulo contrib) ---" . PHP_EOL;
echo "composer require drupal/restui" . PHP_EOL;
echo "ddev drush en restui -y" . PHP_EOL;
echo "Ir a /admin/config/services/rest y activar el recurso." . PHP_EOL;
echo PHP_EOL;

echo "--- Opcion 2: Configuracion manual (YAML) ---" . PHP_EOL;
echo <<<'CODE'
# config/install/rest.resource.tareas.yml
# O importar via drush config:import

id: tareas
plugin_id: tareas
granularity: resource
configuration:
  methods:
    - GET
    - POST
    - PATCH
    - DELETE
  formats:
    - json
  authentication:
    - basic_auth
    - cookie
CODE;
echo PHP_EOL . PHP_EOL;

echo "--- Opcion 3: Via Drush ---" . PHP_EOL;
echo "ddev drush cset rest.resource.tareas status true -y" . PHP_EOL;
echo PHP_EOL;

echo "=== 6. AUTENTICACION ===" . PHP_EOL;
echo PHP_EOL;
echo "Drupal soporta varios metodos de autenticacion para APIs:" . PHP_EOL;
echo PHP_EOL;
echo "--- a) Cookie (sesion de navegador) ---" . PHP_EOL;
echo "  - La mas comun para SPAs en el mismo dominio" . PHP_EOL;
echo "  - Requiere token CSRF para POST/PATCH/DELETE" . PHP_EOL;
echo <<<'CODE'
// Obtener token CSRF:
// GET /session/token

// Enviar en el header:
// X-CSRF-Token: {token}
CODE;
echo PHP_EOL . PHP_EOL;

echo "--- b) Basic Auth ---" . PHP_EOL;
echo "  - Usuario y contrasena en cada peticion" . PHP_EOL;
echo "  - Solo para desarrollo/testing, NO para produccion" . PHP_EOL;
echo <<<'CODE'
curl -u admin:password \
  -H "Content-Type: application/json" \
  http://mi-drupal.ddev.site/api/v1/tareas/1
CODE;
echo PHP_EOL . PHP_EOL;

echo "--- c) OAuth 2.0 (modulo contrib) ---" . PHP_EOL;
echo "  - composer require drupal/simple_oauth" . PHP_EOL;
echo "  - Para produccion y aplicaciones de terceros" . PHP_EOL;
echo "  - Tokens de acceso con scopes" . PHP_EOL;
echo PHP_EOL;

echo "=== 7. REST RESOURCE CON DEPENDENCY INJECTION ===" . PHP_EOL;
echo PHP_EOL;
echo <<<'CODE'
<?php

namespace Drupal\mi_modulo\Plugin\rest\resource;

use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\rest\Plugin\ResourceBase;
use Drupal\rest\ResourceResponse;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * @RestResource(
 *   id = "estadisticas",
 *   label = @Translation("Estadisticas"),
 *   uri_paths = {
 *     "canonical" = "/api/v1/estadisticas",
 *   }
 * )
 */
class EstadisticasResource extends ResourceBase {

  public function __construct(
    array $configuration,
    $plugin_id,
    $plugin_definition,
    array $serializer_formats,
    LoggerInterface $logger,
    protected EntityTypeManagerInterface $entityTypeManager,
  ) {
    parent::__construct(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $serializer_formats,
      $logger,
    );
  }

  public static function create(
    ContainerInterface $container,
    array $configuration,
    $plugin_id,
    $plugin_definition,
  ) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->getParameter('serializer.formats'),
      $container->get('logger.factory')->get('rest'),
      $container->get('entity_type.manager'),
    );
  }

  public function get() {
    $node_storage = $this->entityTypeManager->getStorage('node');

    // Contar nodos por tipo
    $tipos = ['article', 'page'];
    $estadisticas = [];

    foreach ($tipos as $tipo) {
      $count = $node_storage->getQuery()
        ->condition('type', $tipo)
        ->condition('status', 1)
        ->count()
        ->accessCheck(TRUE)
        ->execute();

      $estadisticas[$tipo] = (int) $count;
    }

    $data = [
      'total_contenido' => array_sum($estadisticas),
      'por_tipo' => $estadisticas,
      'generado' => date('Y-m-d H:i:s'),
    ];

    return new ResourceResponse($data);
  }

}
CODE;
echo PHP_EOL . PHP_EOL;

echo "=== 8. CONTROLLERS COMO API (ALTERNATIVA SIMPLE) ===" . PHP_EOL;
echo PHP_EOL;
echo "Para APIs simples, puedes usar Controllers normales con JsonResponse." . PHP_EOL;
echo "No necesitas el modulo rest." . PHP_EOL;
echo PHP_EOL;
echo <<<'CODE'
<?php
// src/Controller/ApiController.php

namespace Drupal\mi_modulo\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class ApiController extends ControllerBase {

  /**
   * GET /api/tareas
   */
  public function listar() {
    $nids = \Drupal::entityQuery('node')
      ->condition('type', 'tarea')
      ->condition('status', 1)
      ->sort('created', 'DESC')
      ->range(0, 50)
      ->accessCheck(TRUE)
      ->execute();

    $nodes = \Drupal::entityTypeManager()
      ->getStorage('node')
      ->loadMultiple($nids);

    $tareas = [];
    foreach ($nodes as $node) {
      $tareas[] = [
        'id' => (int) $node->id(),
        'titulo' => $node->getTitle(),
        'estado' => $node->get('field_estado')->value,
      ];
    }

    return new JsonResponse([
      'data' => $tareas,
      'total' => count($tareas),
    ]);
  }

  /**
   * POST /api/tareas
   */
  public function crear(Request $request) {
    $datos = json_decode($request->getContent(), TRUE);

    if (empty($datos['titulo'])) {
      return new JsonResponse(
        ['error' => 'Titulo requerido.'],
        400
      );
    }

    $node = \Drupal::entityTypeManager()
      ->getStorage('node')
      ->create([
        'type' => 'tarea',
        'title' => $datos['titulo'],
        'field_estado' => $datos['estado'] ?? 'pendiente',
      ]);
    $node->save();

    return new JsonResponse(
      ['mensaje' => 'Tarea creada.', 'id' => (int) $node->id()],
      201
    );
  }

}
CODE;
echo PHP_EOL . PHP_EOL;

echo "--- Rutas para el controller API ---" . PHP_EOL;
echo <<<'CODE'
# mi_modulo.routing.yml
mi_modulo.api.tareas.listar:
  path: '/api/tareas'
  defaults:
    _controller: '\Drupal\mi_modulo\Controller\ApiController::listar'
  methods: [GET]
  requirements:
    _permission: 'access content'

mi_modulo.api.tareas.crear:
  path: '/api/tareas'
  defaults:
    _controller: '\Drupal\mi_modulo\Controller\ApiController::crear'
  methods: [POST]
  requirements:
    _permission: 'create tarea content'
CODE;
echo PHP_EOL . PHP_EOL;

echo "=== 9. CONSUMIR APIS EXTERNAS DESDE DRUPAL ===" . PHP_EOL;
echo PHP_EOL;
echo "Drupal puede consumir APIs de terceros usando el cliente HTTP." . PHP_EOL;
echo <<<'CODE'
<?php
// Usando el servicio http_client (Guzzle)

// GET simple
$client = \Drupal::httpClient();
$response = $client->get('https://api.example.com/datos');
$data = json_decode($response->getBody()->getContents(), TRUE);

// POST con datos
$response = $client->post('https://api.example.com/crear', [
  'json' => [
    'nombre' => 'Test',
    'email' => 'test@mail.com',
  ],
  'headers' => [
    'Authorization' => 'Bearer mi-token-123',
  ],
]);

// Como servicio inyectable
// En tu servicio o controller:
use GuzzleHttp\ClientInterface;

class MiServicioApi {

  public function __construct(
    protected ClientInterface $httpClient,
  ) {}

  public function obtenerDatos(): array {
    try {
      $response = $this->httpClient->get('https://api.example.com/datos', [
        'timeout' => 10,
        'headers' => [
          'Accept' => 'application/json',
        ],
      ]);

      return json_decode($response->getBody()->getContents(), TRUE);
    }
    catch (\Exception $e) {
      \Drupal::logger('mi_modulo')->error(
        'Error API: @message',
        ['@message' => $e->getMessage()]
      );
      return [];
    }
  }

}
CODE;
echo PHP_EOL . PHP_EOL;

echo "--- Registrar el servicio ---" . PHP_EOL;
$services_api = <<<'YAML'
services:
  mi_modulo.api_client:
    class: Drupal\mi_modulo\Service\MiServicioApi
    arguments: ['@http_client']
YAML;
echo $services_api . PHP_EOL;
echo PHP_EOL;

echo "=== 10. CORS (CROSS-ORIGIN RESOURCE SHARING) ===" . PHP_EOL;
echo PHP_EOL;
echo "Si tu API va a ser consumida desde otro dominio (ej: React app)" . PHP_EOL;
echo "necesitas configurar CORS." . PHP_EOL;
echo PHP_EOL;
echo <<<'CODE'
# web/sites/default/services.yml
parameters:
  cors.config:
    enabled: true
    allowedHeaders:
      - '*'
    allowedMethods:
      - GET
      - POST
      - PATCH
      - DELETE
      - OPTIONS
    allowedOrigins:
      - 'http://localhost:3000'
      - 'https://mi-frontend.com'
    exposedHeaders: false
    maxAge: 3600
    supportsCredentials: true
CODE;
echo PHP_EOL . PHP_EOL;

echo "=== RESUMEN ===" . PHP_EOL;
echo "1. JSON:API: automatico para todas las entidades, ideal para headless" . PHP_EOL;
echo "2. REST Resources: plugins custom para endpoints con logica especifica" . PHP_EOL;
echo "3. Controllers + JsonResponse: alternativa simple sin modulo rest" . PHP_EOL;
echo "4. Autenticacion: cookie (SPA), basic_auth (dev), OAuth 2.0 (produccion)" . PHP_EOL;
echo "5. Consumir APIs: http_client (Guzzle) inyectable como servicio" . PHP_EOL;
echo "6. CORS: configurar en services.yml para permitir acceso cross-origin" . PHP_EOL;
echo "7. ResourceResponse: para GET (con cache), ModifiedResourceResponse: para escritura" . PHP_EOL;
echo "8. JSON:API endpoints: /jsonapi/{entity_type}/{bundle}" . PHP_EOL;
echo "9. Siempre validar datos de entrada y manejar excepciones en APIs" . PHP_EOL;
echo "10. Para produccion: usar OAuth 2.0 + HTTPS siempre" . PHP_EOL;
