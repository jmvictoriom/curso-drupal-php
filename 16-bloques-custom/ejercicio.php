<?php

/**
 * =============================================================
 *  EJERCICIO 16: BLOQUES CUSTOM
 * =============================================================
 */

echo "=== EJERCICIO: TRES BLOQUES ===" . PHP_EOL;
echo PHP_EOL;

echo "Crea un modulo 'mis_bloques' con 3 bloques:" . PHP_EOL;
echo PHP_EOL;

echo "1. BienvenidaBlock:" . PHP_EOL;
echo "   - Si el usuario esta logueado: 'Bienvenido, [nombre]!'" . PHP_EOL;
echo "   - Si es anonimo: 'Inicia sesion para acceder'" . PHP_EOL;
echo "   - Inyecta current_user" . PHP_EOL;
echo "   - Cache por usuario" . PHP_EOL;
echo PHP_EOL;

echo "2. UltimosArticulosBlock (configurable):" . PHP_EOL;
echo "   - Configurable: cantidad de articulos a mostrar (1-10)" . PHP_EOL;
echo "   - Lista los ultimos N articulos publicados" . PHP_EOL;
echo "   - Cada item es un enlace al nodo" . PHP_EOL;
echo "   - Inyecta entity_type.manager" . PHP_EOL;
echo PHP_EOL;

echo "3. InfoSitioBlock:" . PHP_EOL;
echo "   - Muestra: nombre del sitio, fecha actual, total de nodos" . PHP_EOL;
echo "   - Lee el nombre del sitio de la config (system.site)" . PHP_EOL;
echo "   - Solo visible para usuarios autenticados (blockAccess)" . PHP_EOL;
echo PHP_EOL;

echo "--- Estructura ---" . PHP_EOL;
echo "web/modules/custom/mis_bloques/" . PHP_EOL;
echo "├── mis_bloques.info.yml" . PHP_EOL;
echo "└── src/" . PHP_EOL;
echo "    └── Plugin/" . PHP_EOL;
echo "        └── Block/" . PHP_EOL;
echo "            ├── BienvenidaBlock.php" . PHP_EOL;
echo "            ├── UltimosArticulosBlock.php" . PHP_EOL;
echo "            └── InfoSitioBlock.php" . PHP_EOL;
echo PHP_EOL;

echo "--- Pasos ---" . PHP_EOL;
echo "1. ddev drush en mis_bloques -y && ddev drush cr" . PHP_EOL;
echo "2. Ve a /admin/structure/block" . PHP_EOL;
echo "3. Coloca cada bloque en una region" . PHP_EOL;
echo "4. Configura UltimosArticulosBlock (elige cantidad)" . PHP_EOL;
echo "5. Verifica que todo se muestra correctamente" . PHP_EOL;
echo PHP_EOL;
echo "Pidele a Claude que revise tus bloques!" . PHP_EOL;
