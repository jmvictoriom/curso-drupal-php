<?php

/**
 * =============================================================
 *  EJERCICIO 11: ROUTING, CONTROLLERS Y SERVICES
 * =============================================================
 *  Amplia el modulo mi_pagina (o crea uno nuevo: mi_dashboard)
 * =============================================================
 */

echo "=== EJERCICIO: MODULO 'mi_dashboard' ===" . PHP_EOL;
echo PHP_EOL;

echo "Crea un modulo 'mi_dashboard' con:" . PHP_EOL;
echo PHP_EOL;
echo "1. Un SERVICIO 'mi_dashboard.stats' (StatsService) que tenga:" . PHP_EOL;
echo "   - Metodo getEstadisticas(): devuelve array con datos inventados:" . PHP_EOL;
echo "     ['usuarios' => 150, 'articulos' => 45, 'comentarios' => 320]" . PHP_EOL;
echo "   - Metodo getSaludo(): devuelve saludo segun la hora del dia" . PHP_EOL;
echo PHP_EOL;

echo "2. TRES PAGINAS:" . PHP_EOL;
echo PHP_EOL;
echo "   /dashboard" . PHP_EOL;
echo "   - Muestra el saludo personalizado del servicio" . PHP_EOL;
echo "   - Muestra una tabla con las estadisticas del servicio" . PHP_EOL;
echo "   - Requiere permiso 'access content'" . PHP_EOL;
echo PHP_EOL;
echo "   /dashboard/usuario/{nombre}" . PHP_EOL;
echo "   - Saludo personalizado para el usuario" . PHP_EOL;
echo "   - Parametro nombre obligatorio" . PHP_EOL;
echo PHP_EOL;
echo "   /dashboard/api/stats" . PHP_EOL;
echo "   - Devuelve las estadisticas como JSON (JsonResponse)" . PHP_EOL;
echo "   - Requiere permiso 'access content'" . PHP_EOL;
echo PHP_EOL;

echo "--- Estructura ---" . PHP_EOL;
echo "web/modules/custom/mi_dashboard/" . PHP_EOL;
echo "├── mi_dashboard.info.yml" . PHP_EOL;
echo "├── mi_dashboard.routing.yml" . PHP_EOL;
echo "├── mi_dashboard.services.yml" . PHP_EOL;
echo "└── src/" . PHP_EOL;
echo "    ├── Controller/" . PHP_EOL;
echo "    │   └── DashboardController.php" . PHP_EOL;
echo "    └── Service/" . PHP_EOL;
echo "        └── StatsService.php" . PHP_EOL;
echo PHP_EOL;

echo "--- Pistas ---" . PHP_EOL;
echo "- StatsService no necesita inyectar nada (datos inventados)" . PHP_EOL;
echo "- El Controller inyecta StatsService via create()" . PHP_EOL;
echo "- Para JSON: return new JsonResponse(\$datos)" . PHP_EOL;
echo "- No olvides: ddev drush en mi_dashboard -y && ddev drush cr" . PHP_EOL;
echo PHP_EOL;
echo "Cuando funcione, pidele a Claude que revise tu modulo!" . PHP_EOL;
