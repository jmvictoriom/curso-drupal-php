<?php

/**
 * =============================================================
 *  LECCION 24: DRUSH Y HERRAMIENTAS DE DESARROLLO
 * =============================================================
 *  Comandos Drush custom, Devel, Xdebug, coding standards
 *  y flujo de trabajo con Git para Drupal.
 * =============================================================
 */

echo "=== 1. DRUSH: COMANDOS ESENCIALES ===" . PHP_EOL;
echo PHP_EOL;
echo "Drush (DRUpal SHell) es la herramienta CLI de Drupal." . PHP_EOL;
echo PHP_EOL;
echo "# Cache" . PHP_EOL;
echo "ddev drush cr                       # limpiar toda la cache" . PHP_EOL;
echo "ddev drush cc render                # solo cache de render" . PHP_EOL;
echo PHP_EOL;
echo "# Modulos" . PHP_EOL;
echo "ddev drush en mi_modulo -y          # habilitar modulo" . PHP_EOL;
echo "ddev drush pmu mi_modulo -y         # deshabilitar modulo" . PHP_EOL;
echo "ddev drush pm:list --status=enabled # listar modulos activos" . PHP_EOL;
echo PHP_EOL;
echo "# Configuracion" . PHP_EOL;
echo "ddev drush cex -y                   # exportar config" . PHP_EOL;
echo "ddev drush cim -y                   # importar config" . PHP_EOL;
echo "ddev drush config:get system.site   # ver un config" . PHP_EOL;
echo PHP_EOL;
echo "# Usuarios" . PHP_EOL;
echo "ddev drush user:create editor --password=123" . PHP_EOL;
echo "ddev drush user:role:add editor administrator" . PHP_EOL;
echo "ddev drush uli                      # login de un uso como admin" . PHP_EOL;
echo PHP_EOL;
echo "# Base de datos" . PHP_EOL;
echo "ddev drush sql:dump > backup.sql    # backup de la BD" . PHP_EOL;
echo "ddev drush sql:cli                  # consola MySQL" . PHP_EOL;
echo "ddev drush updb -y                  # ejecutar updates pendientes" . PHP_EOL;
echo PHP_EOL;


echo "=== 2. CREAR UN COMANDO DRUSH CUSTOM ===" . PHP_EOL;
echo PHP_EOL;
echo "Los comandos Drush van en src/Commands/ de tu modulo." . PHP_EOL;
echo PHP_EOL;
echo "Estructura:" . PHP_EOL;
echo "web/modules/custom/mi_modulo/" . PHP_EOL;
echo "├── mi_modulo.info.yml" . PHP_EOL;
echo "├── mi_modulo.services.yml" . PHP_EOL;
echo "├── drush.services.yml              <- registro de comandos Drush" . PHP_EOL;
echo "└── src/" . PHP_EOL;
echo "    └── Commands/" . PHP_EOL;
echo "        └── MiModuloCommands.php" . PHP_EOL;
echo PHP_EOL;

echo "Paso 1: drush.services.yml" . PHP_EOL;
echo PHP_EOL;
$drushServices = <<<'YAML'
# drush.services.yml
services:
  mi_modulo.commands:
    class: Drupal\mi_modulo\Commands\MiModuloCommands
    arguments: ['@entity_type.manager']
    tags:
      - { name: drush.command }
YAML;
echo $drushServices . PHP_EOL;
echo PHP_EOL;

echo "Paso 2: La clase de comandos" . PHP_EOL;
echo PHP_EOL;
echo <<<'CODE'
<?php
// src/Commands/MiModuloCommands.php

namespace Drupal\mi_modulo\Commands;

use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drush\Attributes as CLI;
use Drush\Commands\DrushCommands;

class MiModuloCommands extends DrushCommands {

  public function __construct(
    protected EntityTypeManagerInterface $entityTypeManager,
  ) {
    parent::__construct();
  }

  /**
   * Muestra estadisticas del sitio.
   */
  #[CLI\Command(name: 'mi_modulo:stats', aliases: ['mm:stats'])]
  #[CLI\Help(description: 'Muestra estadisticas de contenido del sitio.')]
  #[CLI\Usage(name: 'drush mi_modulo:stats', description: 'Ver estadisticas')]
  public function stats(): void {
    $storage = $this->entityTypeManager->getStorage('node');

    // Contar nodos por tipo
    $tipos = ['article', 'page'];
    foreach ($tipos as $tipo) {
      $count = $storage->getQuery()
        ->condition('type', $tipo)
        ->accessCheck(FALSE)
        ->count()
        ->execute();
      $this->io()->writeln("$tipo: $count nodos");
    }

    // Total
    $total = $storage->getQuery()
      ->accessCheck(FALSE)
      ->count()
      ->execute();
    $this->io()->success("Total de nodos: $total");
  }

  /**
   * Elimina nodos de un tipo especifico.
   */
  #[CLI\Command(name: 'mi_modulo:limpiar', aliases: ['mm:clean'])]
  #[CLI\Argument(name: 'tipo', description: 'Tipo de contenido a limpiar')]
  #[CLI\Option(name: 'limit', description: 'Maximo de nodos a eliminar')]
  #[CLI\Help(description: 'Elimina nodos de un tipo de contenido.')]
  public function limpiar(
    string $tipo,
    array $options = ['limit' => 10],
  ): void {
    $storage = $this->entityTypeManager->getStorage('node');
    $limit = (int) $options['limit'];

    $nids = $storage->getQuery()
      ->condition('type', $tipo)
      ->accessCheck(FALSE)
      ->range(0, $limit)
      ->execute();

    if (empty($nids)) {
      $this->io()->warning("No hay nodos de tipo '$tipo'.");
      return;
    }

    // Confirmar antes de eliminar
    $count = count($nids);
    if (!$this->io()->confirm("Eliminar $count nodo(s) de tipo '$tipo'?")) {
      $this->io()->writeln('Cancelado.');
      return;
    }

    $nodos = $storage->loadMultiple($nids);
    $storage->delete($nodos);
    $this->io()->success("$count nodo(s) eliminados.");
  }

}
CODE;
echo PHP_EOL . PHP_EOL;

echo "Uso:" . PHP_EOL;
echo "ddev drush mi_modulo:stats" . PHP_EOL;
echo "ddev drush mi_modulo:limpiar article --limit=5" . PHP_EOL;
echo "ddev drush mm:stats               # alias corto" . PHP_EOL;
echo PHP_EOL;


echo "=== 3. MODULO DEVEL ===" . PHP_EOL;
echo PHP_EOL;
echo "Devel es esencial para desarrollo. Instalacion:" . PHP_EOL;
echo PHP_EOL;
echo "ddev composer require drupal/devel" . PHP_EOL;
echo "ddev drush en devel devel_generate -y" . PHP_EOL;
echo PHP_EOL;

echo "Herramientas que incluye:" . PHP_EOL;
echo PHP_EOL;
echo <<<'CODE'
// En tu codigo, para depurar:

// Imprimir variable formateada (como var_dump pero mejor)
dpm($variable);           // muestra en la pagina (mensaje de Drupal)
dsm($variable);           // alias de dpm
kint($variable);          // Kint: dump interactivo y visual

// Generar contenido de prueba
// ddev drush devel-generate:content 50       # 50 nodos
// ddev drush devel-generate:users 20         # 20 usuarios
// ddev drush devel-generate:terms 30 tags    # 30 terminos

// En Twig (activar devel en twig.config)
// {{ kint(node) }}
// {{ devel_dump(variable) }}

// Ver servicios disponibles
// /devel/container/info

// Ver rutas del sitio
// /devel/routes

// Ver eventos registrados
// /devel/events
CODE;
echo PHP_EOL . PHP_EOL;


echo "=== 4. XDEBUG CON DDEV ===" . PHP_EOL;
echo PHP_EOL;
echo "Xdebug permite depurar paso a paso con breakpoints." . PHP_EOL;
echo PHP_EOL;
echo "# Activar Xdebug en DDEV" . PHP_EOL;
echo "ddev xdebug on" . PHP_EOL;
echo PHP_EOL;
echo "# Desactivar (ralentiza el sitio)" . PHP_EOL;
echo "ddev xdebug off" . PHP_EOL;
echo PHP_EOL;
echo "# Verificar que esta activo" . PHP_EOL;
echo "ddev xdebug status" . PHP_EOL;
echo PHP_EOL;

echo "Configuracion en VS Code (launch.json):" . PHP_EOL;
echo PHP_EOL;
$launch = <<<'JSON'
{
  "version": "0.2.0",
  "configurations": [
    {
      "name": "Listen for Xdebug (DDEV)",
      "type": "php",
      "request": "launch",
      "hostname": "0.0.0.0",
      "port": 9003,
      "pathMappings": {
        "/var/www/html": "${workspaceFolder}"
      }
    }
  ]
}
JSON;
echo $launch . PHP_EOL;
echo PHP_EOL;

echo "Pasos para depurar:" . PHP_EOL;
echo "1. ddev xdebug on" . PHP_EOL;
echo "2. En VS Code: Run > Start Debugging (F5)" . PHP_EOL;
echo "3. Pon un breakpoint (click en el margen izquierdo)" . PHP_EOL;
echo "4. Visita la pagina en el navegador" . PHP_EOL;
echo "5. VS Code se detiene en el breakpoint" . PHP_EOL;
echo "6. Inspecciona variables, avanza paso a paso" . PHP_EOL;
echo PHP_EOL;

echo "Para depurar comandos Drush con Xdebug:" . PHP_EOL;
echo 'ddev exec XDEBUG_TRIGGER=1 drush cr' . PHP_EOL;
echo PHP_EOL;


echo "=== 5. CODING STANDARDS (phpcs / phpcbf) ===" . PHP_EOL;
echo PHP_EOL;
echo "Drupal tiene estandares de codigo estrictos." . PHP_EOL;
echo "phpcs los verifica, phpcbf los corrige automaticamente." . PHP_EOL;
echo PHP_EOL;
echo "# Instalar" . PHP_EOL;
echo "ddev composer require --dev drupal/coder dealerdirect/phpcodesniffer-composer-installer" . PHP_EOL;
echo PHP_EOL;
echo "# Verificar estandares" . PHP_EOL;
echo "ddev exec phpcs --standard=Drupal web/modules/custom/mi_modulo/" . PHP_EOL;
echo "ddev exec phpcs --standard=DrupalPractice web/modules/custom/mi_modulo/" . PHP_EOL;
echo PHP_EOL;
echo "# Corregir automaticamente" . PHP_EOL;
echo "ddev exec phpcbf --standard=Drupal web/modules/custom/mi_modulo/" . PHP_EOL;
echo PHP_EOL;

echo "Ejemplo de errores comunes que detecta:" . PHP_EOL;
echo PHP_EOL;
echo <<<'CODE'
// INCORRECTO (phpcs lo marca)
function mi_funcion( $param ){    // espacios incorrectos
  $x=1;                           // falta espacio en asignacion
  IF($x){                         // if en mayuscula, falta espacio
    echo 'hola';
  }
}

// CORRECTO (estandar Drupal)
function mi_funcion($param) {     // sin espacio antes del parentesis
  $x = 1;                         // espacios en asignacion
  if ($x) {                       // if minuscula, espacio antes de {
    echo 'hola';
  }
}
CODE;
echo PHP_EOL . PHP_EOL;

echo "Configurar phpcs.xml en la raiz del proyecto:" . PHP_EOL;
echo PHP_EOL;
$phpcs = <<<'XML'
<!-- phpcs.xml -->
<ruleset name="Mi Proyecto Drupal">
  <description>Coding standards para mi proyecto.</description>
  <rule ref="Drupal"/>
  <rule ref="DrupalPractice"/>
  <file>web/modules/custom</file>
  <file>web/themes/custom</file>
  <arg name="extensions" value="php,module,inc,install,theme,info,yml"/>
</ruleset>
XML;
echo $phpcs . PHP_EOL;
echo PHP_EOL;


echo "=== 6. GIT WORKFLOW PARA DRUPAL ===" . PHP_EOL;
echo PHP_EOL;
echo "Flujo recomendado con ramas:" . PHP_EOL;
echo PHP_EOL;
echo "main (produccion)" . PHP_EOL;
echo "  └── develop (desarrollo)" . PHP_EOL;
echo "       ├── feature/agregar-blog" . PHP_EOL;
echo "       ├── feature/formulario-contacto" . PHP_EOL;
echo "       └── hotfix/fix-login" . PHP_EOL;
echo PHP_EOL;

echo ".gitignore para un proyecto Drupal:" . PHP_EOL;
echo PHP_EOL;
$gitignore = <<<'CODE'
# .gitignore
# Vendor y dependencias (se regeneran con composer install)
/vendor/
/web/core/
/web/modules/contrib/
/web/themes/contrib/
/web/profiles/contrib/
/web/libraries/

# Archivos generados
/web/sites/default/files/
/web/sites/simpletest/

# Configuracion local
/web/sites/default/settings.local.php

# DDEV
/.ddev/db_snapshots/
/.ddev/.ddev-docker-compose-*
CODE;
echo $gitignore . PHP_EOL;
echo PHP_EOL;

echo "Lo que SI se commitea:" . PHP_EOL;
echo "  - composer.json y composer.lock" . PHP_EOL;
echo "  - web/modules/custom/ (tu codigo)" . PHP_EOL;
echo "  - web/themes/custom/ (tu tema)" . PHP_EOL;
echo "  - config/sync/ (configuracion exportada)" . PHP_EOL;
echo "  - .ddev/config.yaml (configuracion de DDEV)" . PHP_EOL;
echo "  - settings.php (sin credenciales)" . PHP_EOL;
echo PHP_EOL;

echo "Flujo diario:" . PHP_EOL;
echo PHP_EOL;
echo "# Crear rama para nueva funcionalidad" . PHP_EOL;
echo "git checkout -b feature/mi-funcionalidad develop" . PHP_EOL;
echo PHP_EOL;
echo "# Trabajar, commitear" . PHP_EOL;
echo "git add web/modules/custom/mi_modulo/" . PHP_EOL;
echo "git commit -m 'feat(mi_modulo): agregar formulario de contacto'" . PHP_EOL;
echo PHP_EOL;
echo "# Exportar config y commitear" . PHP_EOL;
echo "ddev drush cex -y" . PHP_EOL;
echo "git add config/sync/" . PHP_EOL;
echo "git commit -m 'config: exportar config del formulario'" . PHP_EOL;
echo PHP_EOL;
echo "# Merge a develop" . PHP_EOL;
echo "git checkout develop" . PHP_EOL;
echo "git merge feature/mi-funcionalidad" . PHP_EOL;
echo PHP_EOL;


echo "=== 7. PRE-COMMIT HOOKS ===" . PHP_EOL;
echo PHP_EOL;
echo "Automatizar verificaciones antes de cada commit:" . PHP_EOL;
echo PHP_EOL;
echo <<<'CODE'
#!/bin/bash
# .git/hooks/pre-commit (darle permisos: chmod +x)

echo "Ejecutando phpcs..."
ddev exec phpcs --standard=Drupal web/modules/custom/
if [ $? -ne 0 ]; then
  echo "ERROR: Corrige los errores de coding standards antes de commitear."
  echo "Ejecuta: ddev exec phpcbf --standard=Drupal web/modules/custom/"
  exit 1
fi

echo "Ejecutando tests..."
ddev exec phpunit --group mi_modulo
if [ $? -ne 0 ]; then
  echo "ERROR: Los tests estan fallando."
  exit 1
fi

echo "Todo OK. Commiteando..."
CODE;
echo PHP_EOL . PHP_EOL;


echo "=== RESUMEN ===" . PHP_EOL;
echo "1. Drush: CLI esencial para Drupal (cache, config, modulos)" . PHP_EOL;
echo "2. Comandos Drush custom: DrushCommands + atributos PHP 8" . PHP_EOL;
echo "3. Devel: dpm(), kint(), devel-generate para depuracion" . PHP_EOL;
echo "4. Xdebug: depuracion paso a paso (ddev xdebug on + VS Code)" . PHP_EOL;
echo "5. phpcs/phpcbf: mantener estandares de codigo Drupal" . PHP_EOL;
echo "6. Git: ramas feature, commitear config, .gitignore apropiado" . PHP_EOL;
echo "7. Pre-commit hooks: automatizar phpcs y tests" . PHP_EOL;
echo PHP_EOL;
echo "Siguiente: Leccion 25 - Deploy y config management" . PHP_EOL;
