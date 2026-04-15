<?php

/**
 * =============================================================
 *  LECCION 25: DEPLOY Y CONFIG MANAGEMENT
 * =============================================================
 *  Exportar/importar configuracion, config split, settings
 *  por entorno, flujo de deploy y actualizaciones.
 * =============================================================
 */

echo "=== 1. CONFIGURACION EN DRUPAL ===" . PHP_EOL;
echo PHP_EOL;
echo "Toda la configuracion de Drupal (tipos de contenido, vistas," . PHP_EOL;
echo "permisos, bloques, etc.) se almacena en la BD pero se puede" . PHP_EOL;
echo "exportar a archivos YAML para versionarla con Git." . PHP_EOL;
echo PHP_EOL;
echo "Directorio por defecto: config/sync/" . PHP_EOL;
echo PHP_EOL;
echo "Configurar en settings.php:" . PHP_EOL;
echo PHP_EOL;
echo <<<'CODE'
// web/sites/default/settings.php
$settings['config_sync_directory'] = '../config/sync';
CODE;
echo PHP_EOL . PHP_EOL;


echo "=== 2. EXPORTAR E IMPORTAR CONFIG ===" . PHP_EOL;
echo PHP_EOL;
echo "# Exportar: BD -> archivos YAML" . PHP_EOL;
echo "ddev drush cex -y" . PHP_EOL;
echo PHP_EOL;
echo "# Importar: archivos YAML -> BD" . PHP_EOL;
echo "ddev drush cim -y" . PHP_EOL;
echo PHP_EOL;
echo "# Ver diferencias entre BD y archivos" . PHP_EOL;
echo "ddev drush config:status" . PHP_EOL;
echo PHP_EOL;
echo "# Ver un config especifico" . PHP_EOL;
echo "ddev drush config:get system.site" . PHP_EOL;
echo "ddev drush config:get node.type.article" . PHP_EOL;
echo PHP_EOL;

echo "Flujo basico:" . PHP_EOL;
echo "1. Haces cambios en la admin (crear campo, vista, etc.)" . PHP_EOL;
echo "2. ddev drush cex -y (exportar a archivos)" . PHP_EOL;
echo "3. git add config/sync/ && git commit" . PHP_EOL;
echo "4. En otro entorno: git pull && ddev drush cim -y" . PHP_EOL;
echo PHP_EOL;

echo "Ejemplo de archivo de configuracion:" . PHP_EOL;
echo PHP_EOL;
$configExample = <<<'YAML'
# config/sync/system.site.yml
uuid: abc123-def456-...
name: 'Mi Sitio Drupal'
mail: admin@ejemplo.com
slogan: 'Un sitio con Drupal'
page:
  front: /node
  403: ''
  404: ''
YAML;
echo $configExample . PHP_EOL;
echo PHP_EOL;


echo "=== 3. CONFIG SPLIT ===" . PHP_EOL;
echo PHP_EOL;
echo "Config Split permite tener configuracion diferente por entorno." . PHP_EOL;
echo "Ejemplo: Devel activo solo en desarrollo, no en produccion." . PHP_EOL;
echo PHP_EOL;
echo "# Instalar" . PHP_EOL;
echo "ddev composer require drupal/config_split" . PHP_EOL;
echo "ddev drush en config_split -y" . PHP_EOL;
echo PHP_EOL;

echo "Estructura con config split:" . PHP_EOL;
echo PHP_EOL;
echo "config/" . PHP_EOL;
echo "├── sync/                  <- config compartida (todos los entornos)" . PHP_EOL;
echo "├── dev/                   <- config solo para desarrollo" . PHP_EOL;
echo "├── staging/               <- config solo para staging" . PHP_EOL;
echo "└── prod/                  <- config solo para produccion" . PHP_EOL;
echo PHP_EOL;

echo "Configurar splits en la admin:" . PHP_EOL;
echo "/admin/config/development/configuration/config-split" . PHP_EOL;
echo PHP_EOL;

echo "Crear split 'dev':" . PHP_EOL;
echo PHP_EOL;
$splitDev = <<<'YAML'
# config/sync/config_split.config_split.dev.yml
uuid: ...
langcode: en
status: true
dependencies: {  }
id: dev
label: Development
weight: 0
status: true
folder: ../config/dev
# Modulos que solo van en desarrollo
module:
  devel: 0
  devel_generate: 0
  field_ui: 0
  views_ui: 0
theme: {  }
# Configs que se manejan aparte en dev
complete_list: []
partial_list: []
YAML;
echo $splitDev . PHP_EOL;
echo PHP_EOL;


echo "=== 4. SETTINGS.PHP POR ENTORNO ===" . PHP_EOL;
echo PHP_EOL;
echo "El truco: settings.php carga un archivo local segun el entorno." . PHP_EOL;
echo PHP_EOL;
echo <<<'CODE'
<?php
// web/sites/default/settings.php

// Configuracion base (compartida)
$databases['default']['default'] = [
  'driver' => 'mysql',
  'database' => getenv('DB_NAME') ?: 'drupal',
  'username' => getenv('DB_USER') ?: 'db',
  'password' => getenv('DB_PASS') ?: 'db',
  'host' => getenv('DB_HOST') ?: 'db',
  'port' => getenv('DB_PORT') ?: '3306',
];

$settings['config_sync_directory'] = '../config/sync';
$settings['hash_salt'] = getenv('HASH_SALT') ?: 'un-salt-por-defecto';

// Detectar entorno
$env = getenv('APP_ENV') ?: 'local';

// Activar config split segun entorno
switch ($env) {
  case 'prod':
    $config['config_split.config_split.dev']['status'] = FALSE;
    $config['config_split.config_split.prod']['status'] = TRUE;
    // Cache agresivo en produccion
    $settings['cache']['bins']['render'] = 'cache.backend.database';
    // Sin mensajes de error
    $config['system.logging']['error_level'] = 'hide';
    break;

  case 'staging':
    $config['config_split.config_split.dev']['status'] = FALSE;
    $config['config_split.config_split.staging']['status'] = TRUE;
    break;

  case 'dev':
  case 'local':
  default:
    $config['config_split.config_split.dev']['status'] = TRUE;
    $config['config_split.config_split.prod']['status'] = FALSE;
    // Desactivar cache en desarrollo
    $settings['cache']['bins']['render'] = 'cache.backend.null';
    $settings['cache']['bins']['page'] = 'cache.backend.null';
    $settings['cache']['bins']['dynamic_page_cache'] = 'cache.backend.null';
    break;
}

// Cargar settings locales (no commitear este archivo)
$local_settings = __DIR__ . '/settings.local.php';
if (file_exists($local_settings)) {
  include $local_settings;
}
CODE;
echo PHP_EOL . PHP_EOL;

echo "Archivo settings.local.php (NO se commitea, va en .gitignore):" . PHP_EOL;
echo PHP_EOL;
echo <<<'CODE'
<?php
// web/sites/default/settings.local.php
// Este archivo NO va en Git. Cada desarrollador tiene el suyo.

// Credenciales locales de DDEV
$databases['default']['default'] = [
  'driver' => 'mysql',
  'database' => 'db',
  'username' => 'db',
  'password' => 'db',
  'host' => 'db',
  'port' => '3306',
];

// Activar modo desarrollo
$settings['container_yamls'][] = DRUPAL_ROOT . '/sites/development.services.yml';
$config['system.performance']['css']['preprocess'] = FALSE;
$config['system.performance']['js']['preprocess'] = FALSE;
CODE;
echo PHP_EOL . PHP_EOL;


echo "=== 5. FLUJO DE DEPLOY ===" . PHP_EOL;
echo PHP_EOL;
echo "Flujo completo: desarrollo -> staging -> produccion" . PHP_EOL;
echo PHP_EOL;
echo "               LOCAL                  STAGING              PRODUCCION" . PHP_EOL;
echo "          ┌─────────────┐       ┌──────────────┐     ┌──────────────┐" . PHP_EOL;
echo "          │ Desarrollo  │──────>│   Testing    │────>│  En vivo     │" . PHP_EOL;
echo "          │ feature/*   │ merge │   develop    │ tag │  main        │" . PHP_EOL;
echo "          └─────────────┘       └──────────────┘     └──────────────┘" . PHP_EOL;
echo PHP_EOL;

echo "Script de deploy (en staging o produccion):" . PHP_EOL;
echo PHP_EOL;
echo <<<'CODE'
#!/bin/bash
# scripts/deploy.sh

set -e  # Detener si hay error

echo "=== Iniciando deploy ==="

# 1. Modo mantenimiento
drush state:set system.maintenance_mode 1 -y
echo "Sitio en mantenimiento."

# 2. Traer codigo nuevo
git pull origin main
echo "Codigo actualizado."

# 3. Instalar dependencias
composer install --no-dev --optimize-autoloader
echo "Dependencias instaladas."

# 4. Ejecutar updates de base de datos
drush updb -y
echo "Updates de BD ejecutados."

# 5. Importar configuracion
drush cim -y
echo "Configuracion importada."

# 6. Limpiar cache
drush cr
echo "Cache limpiada."

# 7. Salir del modo mantenimiento
drush state:set system.maintenance_mode 0 -y
echo "=== Deploy completo ==="
CODE;
echo PHP_EOL . PHP_EOL;

echo "Con Drush deploy (atajo que ejecuta updb + cim + cr):" . PHP_EOL;
echo PHP_EOL;
echo "drush deploy" . PHP_EOL;
echo PHP_EOL;
echo "Equivale a:" . PHP_EOL;
echo "drush updatedb --no-cache-clear" . PHP_EOL;
echo "drush cache:rebuild" . PHP_EOL;
echo "drush config:import" . PHP_EOL;
echo "drush deploy:hook    # ejecuta hook_deploy_NAME()" . PHP_EOL;
echo PHP_EOL;


echo "=== 6. COMPOSER PATCHES ===" . PHP_EOL;
echo PHP_EOL;
echo "A veces necesitas parchear un modulo contrib o el core." . PHP_EOL;
echo PHP_EOL;
echo "# Instalar el plugin de patches" . PHP_EOL;
echo "ddev composer require cweagans/composer-patches" . PHP_EOL;
echo PHP_EOL;

echo "Agregar patches en composer.json:" . PHP_EOL;
echo PHP_EOL;
$patches = <<<'JSON'
{
  "extra": {
    "patches": {
      "drupal/core": {
        "Fix para el bug #12345": "https://www.drupal.org/files/issues/2024-01-01/12345-fix.patch"
      },
      "drupal/token": {
        "Soporte para entidad custom": "patches/token-custom-entity.patch"
      }
    }
  }
}
JSON;
echo $patches . PHP_EOL;
echo PHP_EOL;

echo "Los patches se aplican automaticamente al ejecutar composer install." . PHP_EOL;
echo "Guarda los patches locales en una carpeta patches/ en el repo." . PHP_EOL;
echo PHP_EOL;


echo "=== 7. ACTUALIZAR DRUPAL CORE Y MODULOS ===" . PHP_EOL;
echo PHP_EOL;
echo "# Ver actualizaciones disponibles" . PHP_EOL;
echo "ddev composer outdated drupal/*" . PHP_EOL;
echo PHP_EOL;
echo "# Actualizar un modulo" . PHP_EOL;
echo "ddev composer update drupal/token --with-dependencies" . PHP_EOL;
echo "ddev drush updb -y" . PHP_EOL;
echo "ddev drush cr" . PHP_EOL;
echo PHP_EOL;
echo "# Actualizar Drupal core" . PHP_EOL;
echo "ddev composer update drupal/core-* --with-dependencies" . PHP_EOL;
echo "ddev drush updb -y" . PHP_EOL;
echo "ddev drush cr" . PHP_EOL;
echo PHP_EOL;

echo "Flujo seguro para actualizar:" . PHP_EOL;
echo PHP_EOL;
echo "1. git checkout -b update/drupal-core develop" . PHP_EOL;
echo "2. ddev composer update drupal/core-* --with-dependencies" . PHP_EOL;
echo "3. ddev drush updb -y && ddev drush cr" . PHP_EOL;
echo "4. Probar que todo funciona" . PHP_EOL;
echo "5. ddev drush cex -y  (si cambian configs)" . PHP_EOL;
echo "6. git add . && git commit -m 'update: Drupal core a 10.x.x'" . PHP_EOL;
echo "7. Merge a develop, probar en staging, luego a main" . PHP_EOL;
echo PHP_EOL;


echo "=== 8. DEPLOY HOOKS ===" . PHP_EOL;
echo PHP_EOL;
echo "Para ejecutar codigo custom durante el deploy:" . PHP_EOL;
echo PHP_EOL;
echo <<<'CODE'
<?php
// mi_modulo.deploy.php

/**
 * Actualizar roles despues del deploy.
 *
 * Los deploy hooks se ejecutan con: drush deploy:hook
 * Se ejecutan UNA SOLA VEZ (como los update hooks).
 */
function mi_modulo_deploy_actualizar_roles(&$sandbox) {
  $role = \Drupal\user\Entity\Role::load('editor');
  if ($role) {
    $role->grantPermission('access content overview');
    $role->save();
  }
  return 'Permisos del rol editor actualizados.';
}

/**
 * Migrar datos de un campo viejo a uno nuevo.
 */
function mi_modulo_deploy_migrar_campo_descripcion(&$sandbox) {
  $storage = \Drupal::entityTypeManager()->getStorage('node');

  if (!isset($sandbox['total'])) {
    $nids = $storage->getQuery()
      ->condition('type', 'article')
      ->accessCheck(FALSE)
      ->execute();
    $sandbox['nids'] = array_values($nids);
    $sandbox['total'] = count($nids);
    $sandbox['procesados'] = 0;
  }

  // Procesar en lotes de 50
  $lote = array_splice($sandbox['nids'], 0, 50);
  $nodos = $storage->loadMultiple($lote);

  foreach ($nodos as $nodo) {
    // Migrar campo viejo a nuevo
    $valor = $nodo->get('field_descripcion_viejo')->value;
    if ($valor) {
      $nodo->set('field_descripcion_nuevo', $valor);
      $nodo->save();
    }
    $sandbox['procesados']++;
  }

  $sandbox['#finished'] = $sandbox['total'] > 0
    ? $sandbox['procesados'] / $sandbox['total']
    : 1;

  return "Procesados {$sandbox['procesados']}/{$sandbox['total']} nodos.";
}
CODE;
echo PHP_EOL . PHP_EOL;


echo "=== RESUMEN ===" . PHP_EOL;
echo "1. ddev drush cex/cim: exportar/importar configuracion" . PHP_EOL;
echo "2. Config Split: config diferente por entorno (dev, staging, prod)" . PHP_EOL;
echo "3. settings.php: detectar entorno y cargar settings locales" . PHP_EOL;
echo "4. Deploy: git pull + composer install + drush deploy" . PHP_EOL;
echo "5. Composer patches: parchear core y contrib" . PHP_EOL;
echo "6. Actualizar: composer update + drush updb + drush cex" . PHP_EOL;
echo "7. Deploy hooks: codigo que se ejecuta una vez durante deploy" . PHP_EOL;
echo PHP_EOL;
echo "Con esto tienes un flujo profesional de desarrollo Drupal!" . PHP_EOL;
