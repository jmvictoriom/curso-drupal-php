<?php

/**
 * =============================================================
 *  EJERCICIO 17: TWIG Y THEMING
 * =============================================================
 */

echo "=== EJERCICIO: PORTFOLIO CON TEMPLATES ===" . PHP_EOL;
echo PHP_EOL;

echo "Crea un modulo 'mi_portfolio' que use templates Twig:" . PHP_EOL;
echo PHP_EOL;

echo "1. Template 'proyecto' (mi-proyecto.html.twig):" . PHP_EOL;
echo "   Variables: titulo, descripcion, tecnologias (array), imagen_url, url" . PHP_EOL;
echo "   Muestra una tarjeta con todos los datos" . PHP_EOL;
echo "   Si no hay imagen, muestra un placeholder" . PHP_EOL;
echo "   Las tecnologias se muestran como badges/etiquetas" . PHP_EOL;
echo PHP_EOL;

echo "2. Template 'portfolio_lista' (mi-portfolio-lista.html.twig):" . PHP_EOL;
echo "   Variables: titulo, proyectos (array de render arrays)" . PHP_EOL;
echo "   Muestra el titulo y todos los proyectos" . PHP_EOL;
echo "   Si no hay proyectos, muestra 'No hay proyectos aun'" . PHP_EOL;
echo PHP_EOL;

echo "3. Controller en /portfolio:" . PHP_EOL;
echo "   - Crea 3-4 proyectos con datos inventados" . PHP_EOL;
echo "   - Renderiza usando el template 'portfolio_lista'" . PHP_EOL;
echo "   - Cada proyecto usa el template 'proyecto'" . PHP_EOL;
echo PHP_EOL;

echo "4. CSS: Crea una libreria con estilos basicos para las tarjetas" . PHP_EOL;
echo PHP_EOL;

echo "--- Estructura ---" . PHP_EOL;
echo "web/modules/custom/mi_portfolio/" . PHP_EOL;
echo "├── mi_portfolio.info.yml" . PHP_EOL;
echo "├── mi_portfolio.module           <- hook_theme" . PHP_EOL;
echo "├── mi_portfolio.routing.yml" . PHP_EOL;
echo "├── mi_portfolio.libraries.yml    <- CSS" . PHP_EOL;
echo "├── css/" . PHP_EOL;
echo "│   └── portfolio.css" . PHP_EOL;
echo "├── templates/" . PHP_EOL;
echo "│   ├── mi-proyecto.html.twig" . PHP_EOL;
echo "│   └── mi-portfolio-lista.html.twig" . PHP_EOL;
echo "└── src/" . PHP_EOL;
echo "    └── Controller/" . PHP_EOL;
echo "        └── PortfolioController.php" . PHP_EOL;
echo PHP_EOL;
echo "Pidele a Claude que revise tu portfolio!" . PHP_EOL;
