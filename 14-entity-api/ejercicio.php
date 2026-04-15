<?php

/**
 * =============================================================
 *  EJERCICIO 14: ENTITY API
 * =============================================================
 */

echo "=== EJERCICIO: CRUD DE CONTENIDO ===" . PHP_EOL;
echo PHP_EOL;

echo "Crea un modulo 'mi_crud' con un Controller que tenga:" . PHP_EOL;
echo PHP_EOL;

echo "1. /mi-crud/listar" . PHP_EOL;
echo "   - Lista los ultimos 10 articulos publicados" . PHP_EOL;
echo "   - Muestra: titulo, autor, fecha de creacion" . PHP_EOL;
echo "   - Usa entity query + tabla render array" . PHP_EOL;
echo PHP_EOL;

echo "2. /mi-crud/crear" . PHP_EOL;
echo "   - Crea 3 articulos programaticamente con datos inventados" . PHP_EOL;
echo "   - Muestra mensaje de confirmacion por cada uno" . PHP_EOL;
echo "   - Redirige a /mi-crud/listar" . PHP_EOL;
echo PHP_EOL;

echo "3. /mi-crud/ver/{nid}" . PHP_EOL;
echo "   - Carga el nodo por ID" . PHP_EOL;
echo "   - Si no existe, lanza 404" . PHP_EOL;
echo "   - Muestra: titulo, tipo, cuerpo, fecha, estado" . PHP_EOL;
echo PHP_EOL;

echo "4. /mi-crud/estadisticas" . PHP_EOL;
echo "   - Cuenta total de nodos por tipo" . PHP_EOL;
echo "   - Cuenta total de usuarios" . PHP_EOL;
echo "   - Cuenta total de terminos de taxonomia" . PHP_EOL;
echo "   - Muestra en tabla" . PHP_EOL;
echo PHP_EOL;

echo "5. BONUS: /mi-crud/buscar/{texto}" . PHP_EOL;
echo "   - Busca nodos cuyo titulo contenga {texto}" . PHP_EOL;
echo "   - Muestra resultados en tabla" . PHP_EOL;
echo PHP_EOL;

echo "--- Pistas ---" . PHP_EOL;
echo "- Usa entityQuery para buscar" . PHP_EOL;
echo "- Usa Node::load() / Node::create()" . PHP_EOL;
echo "- No olvides accessCheck(TRUE)" . PHP_EOL;
echo "- Para 404: throw new NotFoundHttpException()" . PHP_EOL;
echo PHP_EOL;
echo "Pidele a Claude que revise tu modulo!" . PHP_EOL;
