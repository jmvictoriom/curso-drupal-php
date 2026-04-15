<?php

/**
 * =============================================================
 *  LECCION 07: NAMESPACES Y COMPOSER
 * =============================================================
 *  Drupal usa namespaces y Composer para organizar todo su codigo.
 *  Sin entender esto, no entenderas la estructura de Drupal.
 * =============================================================
 */

echo "=== 1. EL PROBLEMA: COLISIONES DE NOMBRES ===" . PHP_EOL;

// Imagina que dos librerias tienen una clase llamada "Logger".
// Sin namespaces, PHP no sabria cual usar. Los namespaces son como
// "apellidos" para las clases.

// Sin namespaces (problema):
//   class Logger {} // libreria A
//   class Logger {} // libreria B -> ERROR: clase duplicada

// Con namespaces (solucion):
//   namespace LibreriaA;  class Logger {}
//   namespace LibreriaB;  class Logger {}
//   Ahora son: LibreriaA\Logger y LibreriaB\Logger (distintas)

echo "Los namespaces evitan conflictos de nombres" . PHP_EOL;
echo "Son como carpetas para organizar clases" . PHP_EOL;
echo PHP_EOL;


echo "=== 2. COMO FUNCIONAN LOS NAMESPACES ===" . PHP_EOL;

// Un namespace se declara al principio del archivo:
//
//   namespace MiApp\Modelos;
//
//   class Usuario {
//       // ...
//   }
//
// El nombre completo de esta clase es: MiApp\Modelos\Usuario
// La barra invertida \ separa los niveles (como carpetas)

// Para USAR una clase de otro namespace:
//
//   use MiApp\Modelos\Usuario;      // importar
//   $user = new Usuario();           // ahora puedes usarla sin el path completo
//
//   // O sin importar (nombre completo):
//   $user = new \MiApp\Modelos\Usuario();

// Alias: si dos clases tienen el mismo nombre
//   use MiApp\Modelos\Usuario as UsuarioModelo;
//   use MiApp\Auth\Usuario as UsuarioAuth;

echo "namespace MiApp\\Modelos;  -> organiza la clase" . PHP_EOL;
echo "use MiApp\\Modelos\\Usuario; -> importa la clase" . PHP_EOL;
echo PHP_EOL;


echo "=== 3. NAMESPACES EN DRUPAL ===" . PHP_EOL;

// En Drupal, los namespaces siguen la estructura de carpetas:
//
// modules/mi_modulo/src/Controller/PaginaController.php
// -> namespace Drupal\mi_modulo\Controller;
//
// modules/mi_modulo/src/Form/ConfigForm.php
// -> namespace Drupal\mi_modulo\Form;
//
// modules/mi_modulo/src/Plugin/Block/MiBloque.php
// -> namespace Drupal\mi_modulo\Plugin\Block;

echo "Patron Drupal: Drupal\\nombre_modulo\\Carpeta\\Clase" . PHP_EOL;
echo PHP_EOL;
echo "Ejemplos reales:" . PHP_EOL;
echo "  Drupal\\node\\Controller\\NodeController" . PHP_EOL;
echo "  Drupal\\user\\Form\\UserLoginForm" . PHP_EOL;
echo "  Drupal\\block\\Plugin\\Block\\SystemBrandingBlock" . PHP_EOL;
echo PHP_EOL;


echo "=== 4. AUTOLOADING (PSR-4) ===" . PHP_EOL;

// En vez de hacer require/include de cada archivo, PHP puede
// cargar clases automaticamente basandose en el namespace.
// Esto se llama "autoloading" y sigue el estandar PSR-4.

// PSR-4 mapea:
//   Namespace     ->  Carpeta
//   MiApp\Modelos\Usuario  ->  src/Modelos/Usuario.php

// Drupal mapea:
//   Drupal\mi_modulo\  ->  modules/mi_modulo/src/

echo "PSR-4: el namespace = la ruta del archivo" . PHP_EOL;
echo "PHP carga las clases automaticamente" . PHP_EOL;
echo "No necesitas hacer require de cada archivo" . PHP_EOL;
echo PHP_EOL;


echo "=== 5. COMPOSER: GESTOR DE DEPENDENCIAS ===" . PHP_EOL;

// Composer es para PHP lo que npm es para JavaScript.
// Gestiona las librerias que usa tu proyecto.

// Comandos basicos:
echo "Comandos de Composer:" . PHP_EOL;
echo "  composer init          -> crear un nuevo proyecto" . PHP_EOL;
echo "  composer require X     -> instalar una libreria" . PHP_EOL;
echo "  composer install       -> instalar dependencias del composer.json" . PHP_EOL;
echo "  composer update        -> actualizar dependencias" . PHP_EOL;
echo "  composer dump-autoload -> regenerar el autoloader" . PHP_EOL;
echo PHP_EOL;

// Archivos importantes:
echo "Archivos de Composer:" . PHP_EOL;
echo "  composer.json  -> lista de dependencias (como package.json)" . PHP_EOL;
echo "  composer.lock  -> versiones exactas instaladas" . PHP_EOL;
echo "  vendor/        -> carpeta con las librerias descargadas" . PHP_EOL;
echo PHP_EOL;


echo "=== 6. EJEMPLO PRACTICO: PROYECTO CON COMPOSER ===" . PHP_EOL;

// Estructura tipica de un proyecto PHP con Composer:
//
// mi-proyecto/
// ├── composer.json
// ├── composer.lock
// ├── vendor/              <- librerias (NO tocar, NO subir a git)
// │   └── autoload.php     <- el autoloader magico
// └── src/
//     ├── Modelos/
//     │   └── Usuario.php
//     ├── Servicios/
//     │   └── AuthService.php
//     └── Controllers/
//         └── HomeController.php

// composer.json basico:
echo "Ejemplo de composer.json:" . PHP_EOL;
$composer_json = [
    "name" => "jesus/mi-proyecto",
    "description" => "Mi primer proyecto PHP",
    "type" => "project",
    "autoload" => [
        "psr-4" => [
            "MiApp\\" => "src/",
        ],
    ],
    "require" => [
        "php" => ">=8.1",
    ],
];
echo json_encode($composer_json, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . PHP_EOL;
echo PHP_EOL;

// El autoload en composer.json dice:
// "Todo lo que empiece con MiApp\ buscalo en la carpeta src/"
// Asi MiApp\Modelos\Usuario -> src/Modelos/Usuario.php


echo "=== 7. REQUIRE VS USE ===" . PHP_EOL;

// require / include: cargar un archivo manualmente (viejo estilo)
//   require 'archivo.php';        // error fatal si no existe
//   require_once 'archivo.php';   // solo carga una vez
//   include 'archivo.php';        // warning si no existe
//   include_once 'archivo.php';   // solo carga una vez

// use: importar una clase por su namespace (moderno, con autoloading)
//   use MiApp\Modelos\Usuario;
//   $user = new Usuario();

echo "require: carga manual de archivos (viejo)" . PHP_EOL;
echo "use: importar clases con namespaces (moderno)" . PHP_EOL;
echo "En Drupal siempre usaras 'use'" . PHP_EOL;
echo PHP_EOL;


echo "=== 8. COMPOSER EN DRUPAL ===" . PHP_EOL;

// Drupal se instala y gestiona con Composer:
echo "Instalacion de Drupal:" . PHP_EOL;
echo "  composer create-project drupal/recommended-project mi_drupal" . PHP_EOL;
echo PHP_EOL;

echo "Instalar modulos contrib:" . PHP_EOL;
echo "  composer require drupal/admin_toolbar" . PHP_EOL;
echo "  composer require drupal/pathauto" . PHP_EOL;
echo "  composer require drupal/token" . PHP_EOL;
echo PHP_EOL;

echo "Estructura de Drupal:" . PHP_EOL;
echo "  mi_drupal/" . PHP_EOL;
echo "  ├── composer.json" . PHP_EOL;
echo "  ├── vendor/" . PHP_EOL;
echo "  └── web/                  <- document root" . PHP_EOL;
echo "      ├── core/             <- nucleo de Drupal" . PHP_EOL;
echo "      ├── modules/" . PHP_EOL;
echo "      │   ├── contrib/      <- modulos descargados" . PHP_EOL;
echo "      │   └── custom/       <- TUS modulos" . PHP_EOL;
echo "      ├── themes/" . PHP_EOL;
echo "      └── sites/" . PHP_EOL;
echo PHP_EOL;


echo "=== RESUMEN ===" . PHP_EOL;
echo "1. Namespaces organizan clases (como carpetas)" . PHP_EOL;
echo "2. use importa clases para usarlas sin el path completo" . PHP_EOL;
echo "3. PSR-4: namespace = ruta del archivo" . PHP_EOL;
echo "4. Composer gestiona dependencias y autoloading" . PHP_EOL;
echo "5. Drupal usa namespace Drupal\\modulo\\Carpeta\\Clase" . PHP_EOL;
echo "6. vendor/ contiene las librerias (nunca tocar)" . PHP_EOL;
echo "7. En Drupal TODO se gestiona con Composer" . PHP_EOL;
echo PHP_EOL;
echo "Con esto terminamos la Fase 1 (PHP)!" . PHP_EOL;
echo "Siguiente: Fase 2 — Fundamentos de Drupal" . PHP_EOL;
