<?php

/**
 * =============================================================
 *  EJERCICIO 15: DATABASE API
 * =============================================================
 */

echo "=== EJERCICIO: LIBRO DE VISITAS ===" . PHP_EOL;
echo PHP_EOL;

echo "Crea un modulo 'libro_visitas' con:" . PHP_EOL;
echo PHP_EOL;
echo "1. Tabla custom 'libro_visitas' (hook_schema):" . PHP_EOL;
echo "   - id (serial, PK)" . PHP_EOL;
echo "   - nombre (varchar 255)" . PHP_EOL;
echo "   - email (varchar 255)" . PHP_EOL;
echo "   - mensaje (text)" . PHP_EOL;
echo "   - created (int, timestamp)" . PHP_EOL;
echo PHP_EOL;

echo "2. Formulario en /libro-visitas/nuevo (FormBase):" . PHP_EOL;
echo "   - Campos: nombre, email, mensaje" . PHP_EOL;
echo "   - Validar email y nombre" . PHP_EOL;
echo "   - Guardar en la tabla con Database API" . PHP_EOL;
echo "   - Redirigir a /libro-visitas" . PHP_EOL;
echo PHP_EOL;

echo "3. Pagina /libro-visitas (Controller):" . PHP_EOL;
echo "   - Muestra todas las entradas en una tabla" . PHP_EOL;
echo "   - Ordenadas por fecha (mas recientes primero)" . PHP_EOL;
echo "   - Columnas: Nombre, Mensaje, Fecha" . PHP_EOL;
echo "   - Enlace a 'Firmar el libro' (/libro-visitas/nuevo)" . PHP_EOL;
echo PHP_EOL;

echo "4. BONUS: /libro-visitas/eliminar/{id}" . PHP_EOL;
echo "   - Elimina una entrada por ID" . PHP_EOL;
echo "   - Solo para administradores" . PHP_EOL;
echo "   - Redirige a /libro-visitas con mensaje de confirmacion" . PHP_EOL;
echo PHP_EOL;

echo "--- Estructura ---" . PHP_EOL;
echo "web/modules/custom/libro_visitas/" . PHP_EOL;
echo "├── libro_visitas.info.yml" . PHP_EOL;
echo "├── libro_visitas.install           <- hook_schema" . PHP_EOL;
echo "├── libro_visitas.routing.yml" . PHP_EOL;
echo "└── src/" . PHP_EOL;
echo "    ├── Controller/" . PHP_EOL;
echo "    │   └── LibroVisitasController.php" . PHP_EOL;
echo "    └── Form/" . PHP_EOL;
echo "        └── NuevaEntradaForm.php" . PHP_EOL;
echo PHP_EOL;
echo "Pidele a Claude que revise tu modulo!" . PHP_EOL;
