<?php

/**
 * =============================================================
 *  EJERCICIO 12: HOOKS Y EVENTOS
 * =============================================================
 */

echo "=== EJERCICIO: HOOKS EN PRACTICA ===" . PHP_EOL;
echo PHP_EOL;

echo "Crea un modulo 'mi_hooks' con los siguientes hooks:" . PHP_EOL;
echo PHP_EOL;

echo "1. hook_form_alter():" . PHP_EOL;
echo "   - En el formulario de creacion de articulos (node_article_form)" . PHP_EOL;
echo "   - Cambia el texto del boton submit a 'Publicar articulo'" . PHP_EOL;
echo "   - Muestra un mensaje al usuario: 'Estas creando un nuevo articulo'" . PHP_EOL;
echo PHP_EOL;

echo "2. hook_entity_presave():" . PHP_EOL;
echo "   - Solo para nodos de tipo 'article'" . PHP_EOL;
echo "   - Convierte el titulo a mayusculas automaticamente" . PHP_EOL;
echo PHP_EOL;

echo "3. hook_entity_insert():" . PHP_EOL;
echo "   - Solo para nodos" . PHP_EOL;
echo "   - Escribe un log: 'Nuevo contenido creado: [titulo]'" . PHP_EOL;
echo "   - Usa \\Drupal::logger()" . PHP_EOL;
echo PHP_EOL;

echo "4. BONUS: Event Subscriber" . PHP_EOL;
echo "   - Crea un subscriber que escuche KernelEvents::REQUEST" . PHP_EOL;
echo "   - Si la URL empieza por '/admin', logea 'Acceso a admin'" . PHP_EOL;
echo "   - Registralo en .services.yml" . PHP_EOL;
echo PHP_EOL;

echo "--- Estructura ---" . PHP_EOL;
echo "web/modules/custom/mi_hooks/" . PHP_EOL;
echo "├── mi_hooks.info.yml" . PHP_EOL;
echo "├── mi_hooks.module              <- hooks 1, 2, 3" . PHP_EOL;
echo "├── mi_hooks.services.yml        <- subscriber (bonus)" . PHP_EOL;
echo "└── src/" . PHP_EOL;
echo "    └── EventSubscriber/" . PHP_EOL;
echo "        └── AdminAccessSubscriber.php  <- bonus" . PHP_EOL;
echo PHP_EOL;

echo "--- Para probar ---" . PHP_EOL;
echo "ddev drush en mi_hooks -y" . PHP_EOL;
echo "ddev drush cr" . PHP_EOL;
echo "1. Ve a /node/add/article -> el boton debe decir 'Publicar articulo'" . PHP_EOL;
echo "2. Crea un articulo -> el titulo debe guardarse en MAYUSCULAS" . PHP_EOL;
echo "3. ddev drush ws -> debe aparecer el log del nuevo contenido" . PHP_EOL;
echo PHP_EOL;
echo "Pidele a Claude que revise tu modulo!" . PHP_EOL;
