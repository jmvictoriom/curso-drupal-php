<?php

/**
 * =============================================================
 *  EJERCICIO 18: TEMA CUSTOM
 * =============================================================
 */

echo "=== EJERCICIO: CREA TU TEMA ===" . PHP_EOL;
echo PHP_EOL;

echo "Crea un tema 'tema_portfolio' con:" . PHP_EOL;
echo PHP_EOL;

echo "1. Regiones: header, nav, content, sidebar, footer" . PHP_EOL;
echo PHP_EOL;

echo "2. page.html.twig con:" . PHP_EOL;
echo "   - Header con logo y nombre del sitio" . PHP_EOL;
echo "   - Navegacion" . PHP_EOL;
echo "   - Content area con sidebar opcional" . PHP_EOL;
echo "   - Footer con copyright y anio actual" . PHP_EOL;
echo PHP_EOL;

echo "3. CSS basico con:" . PHP_EOL;
echo "   - Reset/normalize" . PHP_EOL;
echo "   - Layout responsive (flexbox o grid)" . PHP_EOL;
echo "   - Tipografia basica" . PHP_EOL;
echo "   - Estilos para el header, nav, footer" . PHP_EOL;
echo PHP_EOL;

echo "4. Sobreescribir node--article.html.twig:" . PHP_EOL;
echo "   - Diseno tipo blog post" . PHP_EOL;
echo "   - Mostrar: titulo, fecha, autor, cuerpo, tags" . PHP_EOL;
echo PHP_EOL;

echo "5. Preprocess (.theme):" . PHP_EOL;
echo "   - Agregar fecha formateada a nodos" . PHP_EOL;
echo "   - Agregar nombre del sitio a todas las paginas" . PHP_EOL;
echo PHP_EOL;

echo "--- Estructura ---" . PHP_EOL;
echo "web/themes/custom/tema_portfolio/" . PHP_EOL;
echo "├── tema_portfolio.info.yml" . PHP_EOL;
echo "├── tema_portfolio.libraries.yml" . PHP_EOL;
echo "├── tema_portfolio.theme" . PHP_EOL;
echo "├── screenshot.png" . PHP_EOL;
echo "├── css/" . PHP_EOL;
echo "│   ├── base.css" . PHP_EOL;
echo "│   ├── layout.css" . PHP_EOL;
echo "│   └── components.css" . PHP_EOL;
echo "└── templates/" . PHP_EOL;
echo "    └── layout/" . PHP_EOL;
echo "        └── page.html.twig" . PHP_EOL;
echo PHP_EOL;

echo "--- Activar ---" . PHP_EOL;
echo "ddev drush theme:enable tema_portfolio -y" . PHP_EOL;
echo "ddev drush config:set system.theme default tema_portfolio -y" . PHP_EOL;
echo "ddev drush cr" . PHP_EOL;
echo PHP_EOL;
echo "Pidele a Claude que revise tu tema!" . PHP_EOL;
