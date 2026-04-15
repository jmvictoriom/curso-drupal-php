<?php

/**
 * =============================================================
 *  EJERCICIO 24: DRUSH Y HERRAMIENTAS DE DESARROLLO
 * =============================================================
 */

echo "=== EJERCICIO: COMANDO DRUSH CUSTOM Y CODING STANDARDS ===" . PHP_EOL;
echo PHP_EOL;

echo "--- Parte 1: Comando Drush custom ---" . PHP_EOL;
echo PHP_EOL;
echo "Crea un modulo 'admin_tools' con un comando Drush que" . PHP_EOL;
echo "genere un reporte del sitio." . PHP_EOL;
echo PHP_EOL;

echo "1. Comando 'admin_tools:reporte' (alias 'at:report'):" . PHP_EOL;
echo "   - Muestra el nombre del sitio" . PHP_EOL;
echo "   - Cuenta nodos por cada tipo de contenido" . PHP_EOL;
echo "   - Cuenta usuarios por cada rol" . PHP_EOL;
echo "   - Muestra la version de Drupal" . PHP_EOL;
echo "   - Muestra la fecha y hora actual del servidor" . PHP_EOL;
echo PHP_EOL;

echo "2. Comando 'admin_tools:limpiar-logs' (alias 'at:clean-logs'):" . PHP_EOL;
echo "   - Acepta opcion --dias (default: 30)" . PHP_EOL;
echo "   - Elimina entradas del log (watchdog) mas antiguas que N dias" . PHP_EOL;
echo "   - Pide confirmacion antes de eliminar" . PHP_EOL;
echo "   - Muestra cuantas entradas se eliminaron" . PHP_EOL;
echo PHP_EOL;

echo "3. Comando 'admin_tools:exportar-usuarios' (alias 'at:export-users'):" . PHP_EOL;
echo "   - Acepta argumento 'formato' (tabla o csv)" . PHP_EOL;
echo "   - Lista todos los usuarios con: nombre, email, roles, ultimo acceso" . PHP_EOL;
echo "   - Si formato es 'csv', genera un archivo CSV" . PHP_EOL;
echo PHP_EOL;

echo "--- Estructura ---" . PHP_EOL;
echo "web/modules/custom/admin_tools/" . PHP_EOL;
echo "├── admin_tools.info.yml" . PHP_EOL;
echo "├── admin_tools.services.yml" . PHP_EOL;
echo "├── drush.services.yml" . PHP_EOL;
echo "└── src/" . PHP_EOL;
echo "    └── Commands/" . PHP_EOL;
echo "        └── AdminToolsCommands.php" . PHP_EOL;
echo PHP_EOL;

echo "--- Verificar ---" . PHP_EOL;
echo "ddev drush cr" . PHP_EOL;
echo "ddev drush admin_tools:reporte" . PHP_EOL;
echo "ddev drush at:report              # alias" . PHP_EOL;
echo "ddev drush at:clean-logs --dias=7" . PHP_EOL;
echo "ddev drush at:export-users csv" . PHP_EOL;
echo PHP_EOL;


echo "--- Parte 2: Configurar coding standards ---" . PHP_EOL;
echo PHP_EOL;

echo "1. Instala phpcs y coder:" . PHP_EOL;
echo "   ddev composer require --dev drupal/coder dealerdirect/phpcodesniffer-composer-installer" . PHP_EOL;
echo PHP_EOL;

echo "2. Crea un archivo phpcs.xml en la raiz con:" . PHP_EOL;
echo "   - Reglas Drupal y DrupalPractice" . PHP_EOL;
echo "   - Apuntando a web/modules/custom y web/themes/custom" . PHP_EOL;
echo PHP_EOL;

echo "3. Ejecuta phpcs sobre tu modulo admin_tools:" . PHP_EOL;
echo "   ddev exec phpcs --standard=Drupal web/modules/custom/admin_tools/" . PHP_EOL;
echo PHP_EOL;

echo "4. Corrige TODOS los errores que encuentre:" . PHP_EOL;
echo "   - Primero intenta con phpcbf (correccion automatica)" . PHP_EOL;
echo "   - Lo que no corrija automaticamente, arreglalo a mano" . PHP_EOL;
echo PHP_EOL;

echo "5. Verifica que phpcs pase sin errores:" . PHP_EOL;
echo "   ddev exec phpcs --standard=Drupal web/modules/custom/admin_tools/" . PHP_EOL;
echo "   (debe mostrar 0 errores y 0 warnings)" . PHP_EOL;
echo PHP_EOL;


echo "--- Parte 3: Pre-commit hook ---" . PHP_EOL;
echo PHP_EOL;

echo "1. Crea un pre-commit hook en .git/hooks/pre-commit que:" . PHP_EOL;
echo "   - Ejecute phpcs sobre web/modules/custom/" . PHP_EOL;
echo "   - Si hay errores, bloquee el commit y muestre un mensaje" . PHP_EOL;
echo "   - Si pasa, permita el commit" . PHP_EOL;
echo PHP_EOL;

echo "2. Dale permisos de ejecucion:" . PHP_EOL;
echo "   chmod +x .git/hooks/pre-commit" . PHP_EOL;
echo PHP_EOL;

echo "3. Prueba que funciona:" . PHP_EOL;
echo "   - Introduce un error de estilo a proposito" . PHP_EOL;
echo "   - Intenta commitear (debe fallar)" . PHP_EOL;
echo "   - Corrige el error y commitea de nuevo (debe funcionar)" . PHP_EOL;
echo PHP_EOL;


echo "--- Parte 4: Devel (bonus) ---" . PHP_EOL;
echo PHP_EOL;

echo "1. Instala y habilita Devel:" . PHP_EOL;
echo "   ddev composer require drupal/devel" . PHP_EOL;
echo "   ddev drush en devel devel_generate -y" . PHP_EOL;
echo PHP_EOL;

echo "2. Genera contenido de prueba:" . PHP_EOL;
echo "   ddev drush devel-generate:content 20" . PHP_EOL;
echo "   ddev drush devel-generate:users 10" . PHP_EOL;
echo PHP_EOL;

echo "3. Explora estas URLs en tu sitio:" . PHP_EOL;
echo "   /devel/container/info    <- servicios del contenedor" . PHP_EOL;
echo "   /devel/routes            <- rutas registradas" . PHP_EOL;
echo "   /devel/events            <- eventos del sistema" . PHP_EOL;
echo PHP_EOL;

echo "4. Prueba tu comando 'admin_tools:reporte' con el contenido generado." . PHP_EOL;
echo PHP_EOL;
echo "Pidele a Claude que revise tu comando Drush!" . PHP_EOL;
