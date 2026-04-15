<?php

/**
 * =============================================================
 *  LECCION 09: INSTALACION DE DRUPAL
 * =============================================================
 *  Vamos a instalar Drupal con DDEV (entorno Docker local)
 *  y Composer. Al final tendras Drupal corriendo en tu maquina.
 * =============================================================
 */

echo "=== 1. DDEV: ENTORNO DE DESARROLLO LOCAL ===" . PHP_EOL;
echo PHP_EOL;
echo "DDEV es una herramienta que crea entornos de desarrollo con Docker." . PHP_EOL;
echo "Te da: PHP + MySQL/MariaDB + Nginx + Mailhog + Drush... todo listo." . PHP_EOL;
echo "Es el estandar recomendado por la comunidad Drupal." . PHP_EOL;
echo PHP_EOL;

echo "--- Paso 1: Instalar Docker ---" . PHP_EOL;
echo "Si no tienes Docker Desktop:" . PHP_EOL;
echo "  - Mac: brew install --cask docker" . PHP_EOL;
echo "  - O descarga desde: https://docker.com/products/docker-desktop" . PHP_EOL;
echo "  - Abre Docker Desktop y espera a que arranque" . PHP_EOL;
echo PHP_EOL;

echo "--- Paso 2: Instalar DDEV ---" . PHP_EOL;
echo "  brew install ddev/ddev/ddev" . PHP_EOL;
echo PHP_EOL;

echo "--- Paso 3: Crear proyecto Drupal ---" . PHP_EOL;
echo "  mkdir ~/drupal-proyecto && cd ~/drupal-proyecto" . PHP_EOL;
echo "  ddev config --project-type=drupal --docroot=web --php-version=8.3" . PHP_EOL;
echo "  ddev start" . PHP_EOL;
echo "  ddev composer create drupal/recommended-project" . PHP_EOL;
echo "  ddev composer require drush/drush" . PHP_EOL;
echo PHP_EOL;

echo "--- Paso 4: Instalar Drupal ---" . PHP_EOL;
echo "  ddev drush site:install standard --account-name=admin --account-pass=admin -y" . PHP_EOL;
echo PHP_EOL;

echo "--- Paso 5: Abrir en el navegador ---" . PHP_EOL;
echo "  ddev launch" . PHP_EOL;
echo "  Login: admin / admin" . PHP_EOL;
echo PHP_EOL;


echo "=== 2. COMANDOS DDEV ESENCIALES ===" . PHP_EOL;
echo PHP_EOL;
echo "ddev start           -> arrancar el proyecto" . PHP_EOL;
echo "ddev stop            -> parar el proyecto" . PHP_EOL;
echo "ddev restart         -> reiniciar" . PHP_EOL;
echo "ddev launch          -> abrir en navegador" . PHP_EOL;
echo "ddev ssh             -> entrar al contenedor (terminal Linux)" . PHP_EOL;
echo "ddev describe        -> info del proyecto (URLs, puertos...)" . PHP_EOL;
echo "ddev composer [cmd]  -> ejecutar composer dentro del contenedor" . PHP_EOL;
echo "ddev drush [cmd]     -> ejecutar drush dentro del contenedor" . PHP_EOL;
echo "ddev logs            -> ver logs del servidor" . PHP_EOL;
echo "ddev poweroff        -> parar TODOS los proyectos DDEV" . PHP_EOL;
echo PHP_EOL;


echo "=== 3. DRUSH: CLI DE DRUPAL ===" . PHP_EOL;
echo PHP_EOL;
echo "Drush (Drupal Shell) es la herramienta de linea de comandos de Drupal." . PHP_EOL;
echo "Hace por terminal lo que harias en la interfaz web (pero mas rapido)." . PHP_EOL;
echo PHP_EOL;
echo "Comandos mas usados:" . PHP_EOL;
echo "  ddev drush cr           -> limpiar cache (cache:rebuild)" . PHP_EOL;
echo "  ddev drush en modulo    -> activar un modulo (pm:install)" . PHP_EOL;
echo "  ddev drush pmu modulo   -> desactivar un modulo (pm:uninstall)" . PHP_EOL;
echo "  ddev drush cex          -> exportar configuracion" . PHP_EOL;
echo "  ddev drush cim          -> importar configuracion" . PHP_EOL;
echo "  ddev drush uli          -> generar link de login (user:login)" . PHP_EOL;
echo "  ddev drush st           -> estado del sitio (core:status)" . PHP_EOL;
echo "  ddev drush ws           -> ver logs/watchdog" . PHP_EOL;
echo "  ddev drush updb         -> actualizar base de datos" . PHP_EOL;
echo PHP_EOL;


echo "=== 4. ESTRUCTURA DESPUES DE INSTALAR ===" . PHP_EOL;
echo PHP_EOL;
echo "drupal-proyecto/" . PHP_EOL;
echo "├── .ddev/                    <- configuracion de DDEV" . PHP_EOL;
echo "│   └── config.yaml" . PHP_EOL;
echo "├── composer.json             <- dependencias del proyecto" . PHP_EOL;
echo "├── composer.lock" . PHP_EOL;
echo "├── vendor/                   <- librerias PHP" . PHP_EOL;
echo "└── web/                      <- document root" . PHP_EOL;
echo "    ├── core/                 <- nucleo Drupal (no tocar)" . PHP_EOL;
echo "    ├── modules/" . PHP_EOL;
echo "    │   ├── contrib/          <- modulos descargados (composer)" . PHP_EOL;
echo "    │   └── custom/           <- TUS modulos (aqui trabajas)" . PHP_EOL;
echo "    ├── themes/" . PHP_EOL;
echo "    ├── sites/" . PHP_EOL;
echo "    │   └── default/" . PHP_EOL;
echo "    │       ├── settings.php  <- configuracion" . PHP_EOL;
echo "    │       └── files/        <- archivos subidos" . PHP_EOL;
echo "    └── index.php" . PHP_EOL;
echo PHP_EOL;


echo "=== 5. MODULOS CONTRIB RECOMENDADOS ===" . PHP_EOL;
echo PHP_EOL;
echo "Instala estos modulos basicos:" . PHP_EOL;
echo PHP_EOL;
echo "  ddev composer require drupal/admin_toolbar   <- toolbar mejorada" . PHP_EOL;
echo "  ddev composer require drupal/pathauto         <- URLs automaticas" . PHP_EOL;
echo "  ddev composer require drupal/token            <- tokens para pathauto" . PHP_EOL;
echo "  ddev composer require drupal/devel            <- herramientas desarrollo" . PHP_EOL;
echo "  ddev composer require drupal/webform          <- formularios" . PHP_EOL;
echo PHP_EOL;
echo "  ddev drush en admin_toolbar admin_toolbar_tools pathauto devel -y" . PHP_EOL;
echo PHP_EOL;


echo "=== 6. PRIMEROS PASOS EN DRUPAL ===" . PHP_EOL;
echo PHP_EOL;
echo "Despues de instalar, explora:" . PHP_EOL;
echo "  1. /admin                -> panel de administracion" . PHP_EOL;
echo "  2. /admin/content        -> gestionar contenido" . PHP_EOL;
echo "  3. /admin/structure      -> tipos de contenido, taxonomias, vistas" . PHP_EOL;
echo "  4. /admin/modules        -> activar/desactivar modulos" . PHP_EOL;
echo "  5. /admin/appearance     -> temas" . PHP_EOL;
echo "  6. /admin/config         -> configuracion del sitio" . PHP_EOL;
echo "  7. /admin/people         -> usuarios y permisos" . PHP_EOL;
echo PHP_EOL;
echo "Crea tu primer articulo: /node/add/article" . PHP_EOL;
echo PHP_EOL;


echo "=== RESUMEN ===" . PHP_EOL;
echo "1. DDEV + Docker = entorno de desarrollo local" . PHP_EOL;
echo "2. Composer instala Drupal y modulos" . PHP_EOL;
echo "3. Drush es el CLI de Drupal (cache, config, modulos...)" . PHP_EOL;
echo "4. web/modules/custom/ es donde va TU codigo" . PHP_EOL;
echo "5. Siempre: ddev drush cr despues de cambios" . PHP_EOL;
echo PHP_EOL;
echo "Siguiente: crear tu primer modulo custom!" . PHP_EOL;
