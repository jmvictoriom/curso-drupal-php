<?php

/**
 * =============================================================
 *  LECCION 08: ARQUITECTURA DE DRUPAL
 * =============================================================
 *  Antes de tocar codigo, necesitas entender COMO funciona
 *  Drupal por dentro. Esta leccion es 100% teoria.
 * =============================================================
 */

echo "=== 1. QUE ES DRUPAL ===" . PHP_EOL;
echo PHP_EOL;
echo "Drupal es un CMS (Content Management System) de codigo abierto." . PHP_EOL;
echo "Sirve para crear sitios web, aplicaciones web, APIs, etc." . PHP_EOL;
echo "Escrito en PHP, basado en Symfony, usa Twig para templates." . PHP_EOL;
echo PHP_EOL;
echo "Lo usan: gobiernos, universidades, medios, empresas grandes." . PHP_EOL;
echo "Ejemplos: NASA, The Economist, gobierno de Australia, etc." . PHP_EOL;
echo PHP_EOL;


echo "=== 2. ESTRUCTURA DE ARCHIVOS ===" . PHP_EOL;
echo PHP_EOL;
echo "mi-drupal/                          <- raiz del proyecto" . PHP_EOL;
echo "├── composer.json                   <- dependencias" . PHP_EOL;
echo "├── composer.lock" . PHP_EOL;
echo "├── vendor/                         <- librerias PHP (Symfony, etc.)" . PHP_EOL;
echo "└── web/                            <- document root del servidor" . PHP_EOL;
echo "    ├── core/                       <- nucleo de Drupal (NO tocar)" . PHP_EOL;
echo "    │   ├── lib/                    <- clases del core" . PHP_EOL;
echo "    │   ├── modules/                <- modulos del core" . PHP_EOL;
echo "    │   ├── themes/                 <- temas del core" . PHP_EOL;
echo "    │   └── core.services.yml       <- servicios del core" . PHP_EOL;
echo "    ├── modules/" . PHP_EOL;
echo "    │   ├── contrib/                <- modulos de la comunidad" . PHP_EOL;
echo "    │   └── custom/                 <- TUS modulos (aqui trabajas)" . PHP_EOL;
echo "    ├── themes/" . PHP_EOL;
echo "    │   ├── contrib/                <- temas de la comunidad" . PHP_EOL;
echo "    │   └── custom/                 <- TUS temas" . PHP_EOL;
echo "    ├── sites/" . PHP_EOL;
echo "    │   └── default/" . PHP_EOL;
echo "    │       └── settings.php        <- config de base de datos, etc." . PHP_EOL;
echo "    ├── index.php                   <- punto de entrada" . PHP_EOL;
echo "    └── .htaccess" . PHP_EOL;
echo PHP_EOL;


echo "=== 3. CONCEPTOS FUNDAMENTALES ===" . PHP_EOL;
echo PHP_EOL;

echo "--- NODOS (Content) ---" . PHP_EOL;
echo "Los nodos son las piezas de contenido: articulos, paginas, productos..." . PHP_EOL;
echo "Cada tipo de contenido tiene sus propios campos." . PHP_EOL;
echo "Son la entidad mas importante de Drupal." . PHP_EOL;
echo PHP_EOL;

echo "--- ENTIDADES (Entities) ---" . PHP_EOL;
echo "Todo en Drupal es una entidad: nodos, usuarios, taxonomias, bloques..." . PHP_EOL;
echo "Las entidades pueden tener campos (fields) adjuntos." . PHP_EOL;
echo "Tipos principales:" . PHP_EOL;
echo "  - Node (contenido)" . PHP_EOL;
echo "  - User (usuarios)" . PHP_EOL;
echo "  - Taxonomy Term (categorias/etiquetas)" . PHP_EOL;
echo "  - Block (bloques de contenido)" . PHP_EOL;
echo "  - File / Media (archivos)" . PHP_EOL;
echo "  - Comment (comentarios)" . PHP_EOL;
echo PHP_EOL;

echo "--- CAMPOS (Fields) ---" . PHP_EOL;
echo "Los campos son los 'datos' de una entidad." . PHP_EOL;
echo "Ejemplos: titulo, cuerpo, imagen, fecha, referencia a otro contenido..." . PHP_EOL;
echo "Se configuran desde la admin sin escribir codigo." . PHP_EOL;
echo PHP_EOL;

echo "--- TAXONOMIAS ---" . PHP_EOL;
echo "Sistema de clasificacion con vocabularios y terminos." . PHP_EOL;
echo "Ejemplo: Vocabulario 'Categorias' con terminos: Tecnologia, Deportes, Cocina" . PHP_EOL;
echo PHP_EOL;

echo "--- VISTAS (Views) ---" . PHP_EOL;
echo "Views es el modulo que crea listas/consultas de contenido sin codigo." . PHP_EOL;
echo "Ejemplo: 'Ultimos 5 articulos', 'Productos por categoria', etc." . PHP_EOL;
echo "Genera paginas, bloques, feeds RSS, JSON..." . PHP_EOL;
echo PHP_EOL;

echo "--- BLOQUES (Blocks) ---" . PHP_EOL;
echo "Piezas de contenido que se colocan en regiones del tema." . PHP_EOL;
echo "Ejemplo: menu lateral, banner, widget de ultimos posts." . PHP_EOL;
echo "Pueden ser creados por modulos o por la admin." . PHP_EOL;
echo PHP_EOL;

echo "--- TEMAS (Themes) ---" . PHP_EOL;
echo "Controlan la apariencia visual. Usan Twig como motor de plantillas." . PHP_EOL;
echo "Drupal 10 trae: Olivero (frontend) y Claro (admin)." . PHP_EOL;
echo PHP_EOL;

echo "--- MODULOS (Modules) ---" . PHP_EOL;
echo "Extienden la funcionalidad de Drupal." . PHP_EOL;
echo "3 tipos:" . PHP_EOL;
echo "  - Core: vienen con Drupal (node, user, views, block...)" . PHP_EOL;
echo "  - Contrib: de la comunidad (drupal.org)" . PHP_EOL;
echo "  - Custom: los que TU creas" . PHP_EOL;
echo PHP_EOL;


echo "=== 4. CICLO DE UNA PETICION ===" . PHP_EOL;
echo PHP_EOL;
echo "1. Usuario pide /articulo/hola-mundo" . PHP_EOL;
echo "2. index.php arranca el kernel de Drupal" . PHP_EOL;
echo "3. Symfony Routing busca que ruta coincide" . PHP_EOL;
echo "4. Se ejecuta el Controller asociado a esa ruta" . PHP_EOL;
echo "5. El Controller carga datos (entidades, servicios...)" . PHP_EOL;
echo "6. Se construye un Render Array (array PHP que describe el HTML)" . PHP_EOL;
echo "7. El Theme System convierte el render array en HTML con Twig" . PHP_EOL;
echo "8. Se envia la respuesta HTTP al navegador" . PHP_EOL;
echo PHP_EOL;


echo "=== 5. RENDER ARRAYS ===" . PHP_EOL;
echo PHP_EOL;
echo "Drupal NO genera HTML directamente." . PHP_EOL;
echo "En vez de eso, todo se describe como arrays PHP:" . PHP_EOL;
echo PHP_EOL;

$render_array = [
    '#type' => 'markup',
    '#markup' => '<p>Hola Mundo</p>',
];
echo "Ejemplo render array simple:" . PHP_EOL;
print_r($render_array);
echo PHP_EOL;

$render_array_complejo = [
    '#theme' => 'item_list',
    '#title' => 'Mis frutas',
    '#items' => ['Manzana', 'Platano', 'Naranja'],
];
echo "Ejemplo render array con tema:" . PHP_EOL;
print_r($render_array_complejo);
echo PHP_EOL;

echo "Las claves con # son propiedades de renderizado." . PHP_EOL;
echo "Las claves sin # son elementos hijos." . PHP_EOL;
echo "Esto permite que el tema modifique la salida sin tocar el modulo." . PHP_EOL;
echo PHP_EOL;


echo "=== 6. SERVICIOS Y DEPENDENCY INJECTION ===" . PHP_EOL;
echo PHP_EOL;
echo "Drupal usa el Service Container de Symfony." . PHP_EOL;
echo "Un 'servicio' es un objeto que hace algo concreto:" . PHP_EOL;
echo "  - entity_type.manager: gestiona entidades" . PHP_EOL;
echo "  - current_user: info del usuario actual" . PHP_EOL;
echo "  - database: conexion a la base de datos" . PHP_EOL;
echo "  - messenger: mostrar mensajes al usuario" . PHP_EOL;
echo "  - logger.factory: escribir logs" . PHP_EOL;
echo PHP_EOL;
echo "En vez de crear objetos manualmente, se los 'pides' al contenedor." . PHP_EOL;
echo "Esto se llama Dependency Injection y lo veremos en la leccion 11." . PHP_EOL;
echo PHP_EOL;


echo "=== 7. HOOKS Y EVENTOS ===" . PHP_EOL;
echo PHP_EOL;
echo "Drupal tiene dos formas de 'engancharse' al sistema:" . PHP_EOL;
echo PHP_EOL;
echo "--- HOOKS (sistema clasico) ---" . PHP_EOL;
echo "Funciones con nombre especial que Drupal llama automaticamente." . PHP_EOL;
echo "Ejemplo: mi_modulo_form_alter() se llama cuando se construye un form." . PHP_EOL;
echo "Convencion: nombre_modulo_nombre_hook()" . PHP_EOL;
echo PHP_EOL;
echo "--- EVENTOS (sistema moderno, Symfony) ---" . PHP_EOL;
echo "Clases que 'escuchan' eventos del sistema." . PHP_EOL;
echo "Mas limpios y orientados a objetos que los hooks." . PHP_EOL;
echo "Drupal esta migrando gradualmente de hooks a eventos." . PHP_EOL;
echo PHP_EOL;


echo "=== 8. CONFIGURATION MANAGEMENT (CMI) ===" . PHP_EOL;
echo PHP_EOL;
echo "La configuracion de Drupal (tipos de contenido, views, permisos...)" . PHP_EOL;
echo "se almacena en la BD y se puede exportar/importar como YAML." . PHP_EOL;
echo PHP_EOL;
echo "Comandos Drush:" . PHP_EOL;
echo "  drush config:export (cex)  -> exportar config a archivos YAML" . PHP_EOL;
echo "  drush config:import (cim)  -> importar config desde YAML" . PHP_EOL;
echo PHP_EOL;
echo "Esto permite:" . PHP_EOL;
echo "  - Versionar la configuracion con Git" . PHP_EOL;
echo "  - Mover cambios entre entornos (dev -> staging -> prod)" . PHP_EOL;
echo "  - Trabajar en equipo sin pisar configuraciones" . PHP_EOL;
echo PHP_EOL;


echo "=== RESUMEN ===" . PHP_EOL;
echo "1. Drupal es un CMS basado en PHP/Symfony/Twig" . PHP_EOL;
echo "2. Todo son entidades con campos" . PHP_EOL;
echo "3. Modulos extienden funcionalidad, temas controlan apariencia" . PHP_EOL;
echo "4. Render arrays describen HTML como arrays PHP" . PHP_EOL;
echo "5. Servicios + Dependency Injection (patron Symfony)" . PHP_EOL;
echo "6. Hooks (clasico) y Eventos (moderno) para engancharse" . PHP_EOL;
echo "7. Config Management para versionar configuracion" . PHP_EOL;
echo PHP_EOL;
echo "Siguiente leccion: Instalar Drupal y explorarlo!" . PHP_EOL;
