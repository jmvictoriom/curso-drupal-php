<?php

/**
 * =============================================================
 *  EJERCICIO 10: CREA TU PRIMER MODULO
 * =============================================================
 *  Crea estos archivos en tu instalacion de Drupal:
 *  web/modules/custom/mi_modulo/
 * =============================================================
 */

echo "=== EJERCICIO: MODULO 'mi_pagina' ===" . PHP_EOL;
echo PHP_EOL;

echo "Crea un modulo llamado 'mi_pagina' que tenga:" . PHP_EOL;
echo PHP_EOL;
echo "1. Pagina principal en /mi-pagina que muestre:" . PHP_EOL;
echo "   - Un titulo" . PHP_EOL;
echo "   - Una lista de tus 5 lenguajes de programacion favoritos" . PHP_EOL;
echo "   - Un parrafo de presentacion" . PHP_EOL;
echo PHP_EOL;
echo "2. Pagina con parametro en /mi-pagina/saludo/{nombre} que muestre:" . PHP_EOL;
echo "   - 'Bienvenido, {nombre}!'" . PHP_EOL;
echo "   - La fecha y hora actual" . PHP_EOL;
echo PHP_EOL;
echo "3. Pagina de about en /mi-pagina/about que muestre:" . PHP_EOL;
echo "   - Una tabla con tu informacion:" . PHP_EOL;
echo "     Nombre | Tu nombre" . PHP_EOL;
echo "     Email  | Tu email" . PHP_EOL;
echo "     Rol    | Desarrollador Drupal Junior" . PHP_EOL;
echo PHP_EOL;

echo "--- Archivos que debes crear ---" . PHP_EOL;
echo PHP_EOL;
echo "web/modules/custom/mi_pagina/" . PHP_EOL;
echo "├── mi_pagina.info.yml" . PHP_EOL;
echo "├── mi_pagina.routing.yml          <- 3 rutas" . PHP_EOL;
echo "└── src/" . PHP_EOL;
echo "    └── Controller/" . PHP_EOL;
echo "        └── MiPaginaController.php  <- 3 metodos" . PHP_EOL;
echo PHP_EOL;

echo "--- Pistas ---" . PHP_EOL;
echo "- Lista: usa '#theme' => 'item_list'" . PHP_EOL;
echo "- Tabla: usa '#type' => 'table'" . PHP_EOL;
echo "- Fecha: date('d/m/Y H:i:s')" . PHP_EOL;
echo "- Recuerda: los metodos devuelven render arrays" . PHP_EOL;
echo PHP_EOL;

echo "--- Comandos para probar ---" . PHP_EOL;
echo "ddev drush en mi_pagina -y" . PHP_EOL;
echo "ddev drush cr" . PHP_EOL;
echo "ddev launch /mi-pagina" . PHP_EOL;
echo "ddev launch /mi-pagina/saludo/Jesus" . PHP_EOL;
echo "ddev launch /mi-pagina/about" . PHP_EOL;
echo PHP_EOL;
echo "Cuando funcione, pidele a Claude que revise tu modulo!" . PHP_EOL;
