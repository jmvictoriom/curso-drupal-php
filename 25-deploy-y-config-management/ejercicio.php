<?php

/**
 * =============================================================
 *  EJERCICIO 25: DEPLOY Y CONFIG MANAGEMENT
 * =============================================================
 */

echo "=== EJERCICIO: FLUJO DE DEPLOY CON CONFIG MANAGEMENT ===" . PHP_EOL;
echo PHP_EOL;

echo "Configura un flujo completo de deploy para tu proyecto Drupal." . PHP_EOL;
echo PHP_EOL;

echo "--- Parte 1: Configurar config sync ---" . PHP_EOL;
echo PHP_EOL;

echo "1. Verifica que settings.php tiene configurado el directorio de sync:" . PHP_EOL;
echo '   $settings[\'config_sync_directory\'] = \'../config/sync\';' . PHP_EOL;
echo PHP_EOL;

echo "2. Crea el directorio config/sync/ si no existe" . PHP_EOL;
echo PHP_EOL;

echo "3. Exporta toda la configuracion actual:" . PHP_EOL;
echo "   ddev drush cex -y" . PHP_EOL;
echo PHP_EOL;

echo "4. Verifica que se crearon los archivos YAML:" . PHP_EOL;
echo "   ls config/sync/ | head -20" . PHP_EOL;
echo PHP_EOL;

echo "5. Commitea la configuracion:" . PHP_EOL;
echo "   git add config/sync/" . PHP_EOL;
echo "   git commit -m 'config: exportacion inicial de configuracion'" . PHP_EOL;
echo PHP_EOL;


echo "--- Parte 2: Config Split por entorno ---" . PHP_EOL;
echo PHP_EOL;

echo "1. Instala Config Split:" . PHP_EOL;
echo "   ddev composer require drupal/config_split" . PHP_EOL;
echo "   ddev drush en config_split -y" . PHP_EOL;
echo PHP_EOL;

echo "2. Crea los directorios para cada entorno:" . PHP_EOL;
echo "   mkdir -p config/dev config/staging config/prod" . PHP_EOL;
echo PHP_EOL;

echo "3. Configura un split 'dev' en /admin/config/development/configuration/config-split:" . PHP_EOL;
echo "   - Nombre: Development" . PHP_EOL;
echo "   - Directorio: ../config/dev" . PHP_EOL;
echo "   - Modulos a separar: devel, devel_generate, field_ui, views_ui" . PHP_EOL;
echo "   - Estado: activo" . PHP_EOL;
echo PHP_EOL;

echo "4. Configura un split 'prod' (inactivo por defecto):" . PHP_EOL;
echo "   - Nombre: Production" . PHP_EOL;
echo "   - Directorio: ../config/prod" . PHP_EOL;
echo "   - Configs a sobrescribir: system.logging (ocultar errores)" . PHP_EOL;
echo PHP_EOL;

echo "5. Exporta y commitea:" . PHP_EOL;
echo "   ddev drush cex -y" . PHP_EOL;
echo "   git add config/" . PHP_EOL;
echo "   git commit -m 'config: agregar config split dev/prod'" . PHP_EOL;
echo PHP_EOL;


echo "--- Parte 3: Settings por entorno ---" . PHP_EOL;
echo PHP_EOL;

echo "1. Edita settings.php para detectar el entorno:" . PHP_EOL;
echo '   - Usar getenv(\'APP_ENV\') para determinar el entorno' . PHP_EOL;
echo "   - Activar/desactivar config splits segun el entorno" . PHP_EOL;
echo "   - En 'local': desactivar cache de render" . PHP_EOL;
echo "   - En 'prod': ocultar errores, cache agresivo" . PHP_EOL;
echo PHP_EOL;

echo "2. Crea un settings.local.php con:" . PHP_EOL;
echo "   - Conexion a la BD local de DDEV" . PHP_EOL;
echo "   - Modo desarrollo activado" . PHP_EOL;
echo "   - CSS/JS sin comprimir" . PHP_EOL;
echo PHP_EOL;

echo "3. Asegurate de que settings.local.php esta en .gitignore" . PHP_EOL;
echo PHP_EOL;


echo "--- Parte 4: Script de deploy ---" . PHP_EOL;
echo PHP_EOL;

echo "1. Crea un archivo scripts/deploy.sh que:" . PHP_EOL;
echo "   - Active modo mantenimiento" . PHP_EOL;
echo "   - Haga git pull" . PHP_EOL;
echo "   - Ejecute composer install --no-dev" . PHP_EOL;
echo "   - Ejecute drush updb -y" . PHP_EOL;
echo "   - Ejecute drush cim -y" . PHP_EOL;
echo "   - Limpie la cache" . PHP_EOL;
echo "   - Desactive modo mantenimiento" . PHP_EOL;
echo "   - Si cualquier paso falla, se detenga (set -e)" . PHP_EOL;
echo PHP_EOL;

echo "2. Dale permisos de ejecucion:" . PHP_EOL;
echo "   chmod +x scripts/deploy.sh" . PHP_EOL;
echo PHP_EOL;


echo "--- Parte 5: Deploy hook ---" . PHP_EOL;
echo PHP_EOL;

echo "1. Crea un deploy hook en tu modulo (mi_modulo.deploy.php) que:" . PHP_EOL;
echo "   - Otorgue un permiso nuevo al rol 'editor'" . PHP_EOL;
echo "   - Use el patron de batch (sandbox) si necesita procesar muchos datos" . PHP_EOL;
echo PHP_EOL;

echo "2. Ejecuta el deploy hook:" . PHP_EOL;
echo "   ddev drush deploy:hook" . PHP_EOL;
echo PHP_EOL;


echo "--- Parte 6: Simular un deploy ---" . PHP_EOL;
echo PHP_EOL;

echo "Simula el flujo completo:" . PHP_EOL;
echo PHP_EOL;
echo "1. Haz un cambio en la admin (por ejemplo, crear un campo nuevo)" . PHP_EOL;
echo "2. Exporta: ddev drush cex -y" . PHP_EOL;
echo "3. Commitea: git add config/sync/ && git commit -m 'config: nuevo campo'" . PHP_EOL;
echo "4. Deshaz el cambio en la admin (elimina el campo manualmente)" . PHP_EOL;
echo "5. Importa: ddev drush cim -y" . PHP_EOL;
echo "6. Verifica que el campo volvio a aparecer" . PHP_EOL;
echo PHP_EOL;
echo "Esto simula lo que pasa cuando otro desarrollador" . PHP_EOL;
echo "hace git pull y ejecuta drush cim." . PHP_EOL;
echo PHP_EOL;


echo "--- Estructura final del proyecto ---" . PHP_EOL;
echo "mi-proyecto-drupal/" . PHP_EOL;
echo "├── .ddev/" . PHP_EOL;
echo "│   └── config.yaml" . PHP_EOL;
echo "├── .gitignore" . PHP_EOL;
echo "├── composer.json" . PHP_EOL;
echo "├── composer.lock" . PHP_EOL;
echo "├── config/" . PHP_EOL;
echo "│   ├── sync/              <- config compartida" . PHP_EOL;
echo "│   ├── dev/               <- config solo desarrollo" . PHP_EOL;
echo "│   ├── staging/           <- config solo staging" . PHP_EOL;
echo "│   └── prod/              <- config solo produccion" . PHP_EOL;
echo "├── patches/               <- patches de Composer" . PHP_EOL;
echo "├── phpcs.xml" . PHP_EOL;
echo "├── phpunit.xml" . PHP_EOL;
echo "├── scripts/" . PHP_EOL;
echo "│   └── deploy.sh" . PHP_EOL;
echo "└── web/" . PHP_EOL;
echo "    ├── modules/custom/    <- tu codigo" . PHP_EOL;
echo "    ├── themes/custom/     <- tu tema" . PHP_EOL;
echo "    └── sites/default/" . PHP_EOL;
echo "        ├── settings.php   <- se commitea (sin credenciales)" . PHP_EOL;
echo "        └── settings.local.php <- NO se commitea" . PHP_EOL;
echo PHP_EOL;
echo "Pidele a Claude que revise tu configuracion de deploy!" . PHP_EOL;
