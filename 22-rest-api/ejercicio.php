<?php

/**
 * =============================================================
 *  EJERCICIO 22: REST API - MODULO DE TAREAS
 * =============================================================
 */

echo "=== EJERCICIO: API REST PARA GESTION DE TAREAS ===" . PHP_EOL;
echo PHP_EOL;

echo "Crea un modulo 'tareas_api' que exponga una API REST completa" . PHP_EOL;
echo "para gestionar tareas. Incluye tanto endpoints JSON:API" . PHP_EOL;
echo "automaticos como un REST Resource custom." . PHP_EOL;
echo PHP_EOL;

echo "--- PARTE 1: Preparar el tipo de contenido ---" . PHP_EOL;
echo PHP_EOL;

echo "1. Crea el tipo de contenido 'tarea' por la UI (/admin/structure/types):" . PHP_EOL;
echo "   - Machine name: tarea" . PHP_EOL;
echo "   - Campos:" . PHP_EOL;
echo "     - field_estado (List text): pendiente, en_progreso, completada, cancelada" . PHP_EOL;
echo "     - field_prioridad (List text): baja, media, alta, urgente" . PHP_EOL;
echo "     - field_fecha_limite (Date)" . PHP_EOL;
echo "     - field_asignado (Entity reference -> User)" . PHP_EOL;
echo "   - Crea 5-6 tareas de prueba con diferentes estados y prioridades" . PHP_EOL;
echo PHP_EOL;

echo "--- PARTE 2: Probar JSON:API (sin codigo!) ---" . PHP_EOL;
echo PHP_EOL;

echo "2. Habilita jsonapi: ddev drush en jsonapi -y && ddev drush cr" . PHP_EOL;
echo PHP_EOL;
echo "3. Prueba estos endpoints con curl o Postman:" . PHP_EOL;
echo "   a) Listar todas las tareas:" . PHP_EOL;
echo "      GET /jsonapi/node/tarea" . PHP_EOL;
echo PHP_EOL;
echo "   b) Filtrar tareas pendientes:" . PHP_EOL;
echo "      GET /jsonapi/node/tarea?filter[field_estado]=pendiente" . PHP_EOL;
echo PHP_EOL;
echo "   c) Filtrar tareas de prioridad alta:" . PHP_EOL;
echo "      GET /jsonapi/node/tarea?filter[field_prioridad]=alta" . PHP_EOL;
echo PHP_EOL;
echo "   d) Ordenar por fecha de creacion (mas recientes primero):" . PHP_EOL;
echo "      GET /jsonapi/node/tarea?sort=-created" . PHP_EOL;
echo PHP_EOL;
echo "   e) Incluir el usuario asignado:" . PHP_EOL;
echo "      GET /jsonapi/node/tarea?include=field_asignado" . PHP_EOL;
echo PHP_EOL;
echo "   f) Paginar (5 por pagina):" . PHP_EOL;
echo "      GET /jsonapi/node/tarea?page[limit]=5&page[offset]=0" . PHP_EOL;
echo PHP_EOL;
echo "   g) Crear una tarea via JSON:API (autenticado):" . PHP_EOL;
echo "      POST /jsonapi/node/tarea (con basic_auth)" . PHP_EOL;
echo PHP_EOL;

echo "--- PARTE 3: REST Resource Custom ---" . PHP_EOL;
echo PHP_EOL;

echo "4. Crear TareasResource (src/Plugin/rest/resource/TareasResource.php):" . PHP_EOL;
echo "   - id: 'tareas_api'" . PHP_EOL;
echo "   - uri_paths:" . PHP_EOL;
echo "     - canonical: '/api/v1/tareas/{id}'" . PHP_EOL;
echo "     - create: '/api/v1/tareas'" . PHP_EOL;
echo PHP_EOL;
echo "   Metodos:" . PHP_EOL;
echo "   a) GET /api/v1/tareas/{id}" . PHP_EOL;
echo "      -> Devuelve una tarea especifica" . PHP_EOL;
echo "      -> Formato: {id, titulo, descripcion, estado, prioridad," . PHP_EOL;
echo "         fecha_limite, asignado, creado}" . PHP_EOL;
echo "      -> Si no existe: 404 con mensaje" . PHP_EOL;
echo PHP_EOL;
echo "   b) POST /api/v1/tareas" . PHP_EOL;
echo "      -> Crea una nueva tarea" . PHP_EOL;
echo "      -> Campos requeridos: titulo" . PHP_EOL;
echo "      -> Campos opcionales: descripcion, estado, prioridad, fecha_limite" . PHP_EOL;
echo "      -> Devuelve: {mensaje, id, titulo} con codigo 201" . PHP_EOL;
echo PHP_EOL;
echo "   c) PATCH /api/v1/tareas/{id}" . PHP_EOL;
echo "      -> Actualiza campos de una tarea existente" . PHP_EOL;
echo "      -> Solo actualiza los campos enviados" . PHP_EOL;
echo "      -> Devuelve: {mensaje, id} con codigo 200" . PHP_EOL;
echo PHP_EOL;
echo "   d) DELETE /api/v1/tareas/{id}" . PHP_EOL;
echo "      -> Elimina una tarea" . PHP_EOL;
echo "      -> Devuelve: {mensaje} con codigo 200" . PHP_EOL;
echo PHP_EOL;

echo "5. Implementar Dependency Injection en el resource:" . PHP_EOL;
echo "   - Inyectar entity_type.manager" . PHP_EOL;
echo "   - Inyectar current_user" . PHP_EOL;
echo PHP_EOL;

echo "--- PARTE 4: Controller API alternativo ---" . PHP_EOL;
echo PHP_EOL;

echo "6. Crear TareasApiController (src/Controller/TareasApiController.php):" . PHP_EOL;
echo "   - Ruta: /api/v2/tareas (GET) -> listar todas las tareas" . PHP_EOL;
echo "     -> Acepta query parameters: estado, prioridad, limite" . PHP_EOL;
echo "     -> Ejemplo: /api/v2/tareas?estado=pendiente&limite=10" . PHP_EOL;
echo "     -> Devuelve JsonResponse con {data: [...], total: N, filtros: {...}}" . PHP_EOL;
echo PHP_EOL;
echo "   - Ruta: /api/v2/tareas/estadisticas (GET)" . PHP_EOL;
echo "     -> Devuelve:" . PHP_EOL;
echo "        - Total de tareas" . PHP_EOL;
echo "        - Tareas por estado (pendiente: X, en_progreso: Y, ...)" . PHP_EOL;
echo "        - Tareas por prioridad (baja: X, media: Y, ...)" . PHP_EOL;
echo "        - Tareas vencidas (fecha_limite < hoy)" . PHP_EOL;
echo PHP_EOL;

echo "--- PARTE 5: Servicio para consumir API externa ---" . PHP_EOL;
echo PHP_EOL;

echo "7. BONUS: Crear ApiExternaService (src/Service/ApiExternaService.php):" . PHP_EOL;
echo "   - Inyecta http_client" . PHP_EOL;
echo "   - Metodo: obtenerTareasExternas()" . PHP_EOL;
echo "     -> Consume https://jsonplaceholder.typicode.com/todos" . PHP_EOL;
echo "     -> Devuelve las primeras 10 tareas mapeadas a nuestro formato" . PHP_EOL;
echo "   - Metodo: importarTareas()" . PHP_EOL;
echo "     -> Llama obtenerTareasExternas()" . PHP_EOL;
echo "     -> Crea nodos de tipo 'tarea' con los datos obtenidos" . PHP_EOL;
echo "     -> Devuelve cantidad de tareas importadas" . PHP_EOL;
echo PHP_EOL;

echo "8. BONUS: Ruta /api/v2/tareas/importar (POST):" . PHP_EOL;
echo "   -> Llama a ApiExternaService::importarTareas()" . PHP_EOL;
echo "   -> Devuelve {mensaje: 'X tareas importadas', total: X}" . PHP_EOL;
echo PHP_EOL;

echo "--- Estructura esperada ---" . PHP_EOL;
echo "web/modules/custom/tareas_api/" . PHP_EOL;
echo "├── tareas_api.info.yml" . PHP_EOL;
echo "├── tareas_api.routing.yml" . PHP_EOL;
echo "├── tareas_api.services.yml" . PHP_EOL;
echo "├── tareas_api.permissions.yml" . PHP_EOL;
echo "├── config/" . PHP_EOL;
echo "│   └── install/" . PHP_EOL;
echo "│       └── rest.resource.tareas_api.yml" . PHP_EOL;
echo "└── src/" . PHP_EOL;
echo "    ├── Controller/" . PHP_EOL;
echo "    │   └── TareasApiController.php" . PHP_EOL;
echo "    ├── Plugin/" . PHP_EOL;
echo "    │   └── rest/" . PHP_EOL;
echo "    │       └── resource/" . PHP_EOL;
echo "    │           └── TareasResource.php" . PHP_EOL;
echo "    └── Service/" . PHP_EOL;
echo "        └── ApiExternaService.php" . PHP_EOL;
echo PHP_EOL;

echo "--- Para probar ---" . PHP_EOL;
echo "ddev drush en tareas_api rest serialization basic_auth -y" . PHP_EOL;
echo "ddev drush cr" . PHP_EOL;
echo PHP_EOL;
echo "Pruebas con curl:" . PHP_EOL;
echo PHP_EOL;

echo <<<'CODE'
# 1. Listar tareas (controller)
curl -s http://mi-drupal.ddev.site/api/v2/tareas | jq .

# 2. Filtrar por estado
curl -s "http://mi-drupal.ddev.site/api/v2/tareas?estado=pendiente" | jq .

# 3. Ver estadisticas
curl -s http://mi-drupal.ddev.site/api/v2/tareas/estadisticas | jq .

# 4. Obtener tarea por ID (REST resource)
curl -s -H "Accept: application/json" \
  http://mi-drupal.ddev.site/api/v1/tareas/1 | jq .

# 5. Crear tarea (autenticado)
curl -X POST \
  -H "Content-Type: application/json" \
  -u admin:password \
  http://mi-drupal.ddev.site/api/v1/tareas \
  -d '{"titulo": "Nueva tarea desde API", "estado": "pendiente", "prioridad": "alta"}'

# 6. Actualizar tarea
curl -X PATCH \
  -H "Content-Type: application/json" \
  -u admin:password \
  http://mi-drupal.ddev.site/api/v1/tareas/1 \
  -d '{"estado": "completada"}'

# 7. Eliminar tarea
curl -X DELETE \
  -u admin:password \
  http://mi-drupal.ddev.site/api/v1/tareas/1

# 8. JSON:API - listar
curl -s http://mi-drupal.ddev.site/jsonapi/node/tarea | jq .

# 9. BONUS: Importar tareas externas
curl -X POST \
  -u admin:password \
  http://mi-drupal.ddev.site/api/v2/tareas/importar
CODE;
echo PHP_EOL . PHP_EOL;

echo "--- Verificaciones ---" . PHP_EOL;
echo "1. GET /api/v2/tareas devuelve JSON con todas las tareas" . PHP_EOL;
echo "2. Los filtros de estado y prioridad funcionan" . PHP_EOL;
echo "3. POST crea tareas correctamente" . PHP_EOL;
echo "4. PATCH actualiza solo los campos enviados" . PHP_EOL;
echo "5. DELETE elimina y devuelve confirmacion" . PHP_EOL;
echo "6. Las rutas protegidas requieren autenticacion" . PHP_EOL;
echo "7. Los errores devuelven codigos HTTP apropiados (400, 404)" . PHP_EOL;
echo PHP_EOL;
echo "Pidele a Claude que revise tu API!" . PHP_EOL;
