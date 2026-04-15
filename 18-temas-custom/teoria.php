<?php

/**
 * =============================================================
 *  LECCION 18: TEMAS CUSTOM
 * =============================================================
 *  Crear un tema custom de Drupal desde cero.
 * =============================================================
 */

echo "=== 1. ESTRUCTURA DE UN TEMA ===" . PHP_EOL;
echo PHP_EOL;
echo "web/themes/custom/mi_tema/" . PHP_EOL;
echo "├── mi_tema.info.yml          <- metadatos del tema" . PHP_EOL;
echo "├── mi_tema.libraries.yml     <- CSS/JS" . PHP_EOL;
echo "├── mi_tema.theme             <- funciones preprocess (hooks)" . PHP_EOL;
echo "├── logo.svg" . PHP_EOL;
echo "├── screenshot.png            <- preview en admin" . PHP_EOL;
echo "├── css/" . PHP_EOL;
echo "│   ├── base.css" . PHP_EOL;
echo "│   ├── layout.css" . PHP_EOL;
echo "│   └── components.css" . PHP_EOL;
echo "├── js/" . PHP_EOL;
echo "│   └── mi_tema.js" . PHP_EOL;
echo "└── templates/" . PHP_EOL;
echo "    ├── layout/" . PHP_EOL;
echo "    │   └── page.html.twig" . PHP_EOL;
echo "    ├── content/" . PHP_EOL;
echo "    │   └── node.html.twig" . PHP_EOL;
echo "    └── navigation/" . PHP_EOL;
echo "        └── menu.html.twig" . PHP_EOL;
echo PHP_EOL;


echo "=== 2. ARCHIVO .info.yml DEL TEMA ===" . PHP_EOL;
echo PHP_EOL;
$info = <<<'YAML'
name: Mi Tema
type: theme
description: 'Tema custom para mi proyecto Drupal'
core_version_requirement: ^10 || ^11
base theme: false      # sin tema base (o 'classy', 'stable9')

regions:
  header: Header
  primary_menu: 'Menu principal'
  content: Content
  sidebar: Sidebar
  footer: Footer

libraries:
  - mi_tema/global      # cargar CSS/JS en todas las paginas
YAML;
echo $info . PHP_EOL;
echo PHP_EOL;


echo "=== 3. LIBRARIES ===" . PHP_EOL;
echo PHP_EOL;
$libraries = <<<'YAML'
# mi_tema.libraries.yml
global:
  css:
    base:
      css/base.css: {}
    layout:
      css/layout.css: {}
    component:
      css/components.css: {}
  js:
    js/mi_tema.js: {}
YAML;
echo $libraries . PHP_EOL;
echo PHP_EOL;


echo "=== 4. PAGE.HTML.TWIG ===" . PHP_EOL;
echo PHP_EOL;
echo <<<'TWIG'
{# templates/layout/page.html.twig #}
<div class="page">
  <header class="header">
    {{ page.header }}
    {% if page.primary_menu %}
      <nav class="nav-principal">
        {{ page.primary_menu }}
      </nav>
    {% endif %}
  </header>

  <main class="main">
    <div class="content">
      {{ page.content }}
    </div>

    {% if page.sidebar %}
      <aside class="sidebar">
        {{ page.sidebar }}
      </aside>
    {% endif %}
  </main>

  <footer class="footer">
    {{ page.footer }}
    <p>&copy; {{ "now"|date("Y") }} Mi Sitio Drupal</p>
  </footer>
</div>
TWIG;
echo PHP_EOL . PHP_EOL;


echo "=== 5. ARCHIVO .theme (PREPROCESS) ===" . PHP_EOL;
echo PHP_EOL;
echo <<<'CODE'
<?php
// mi_tema.theme

function mi_tema_preprocess_page(&$variables) {
  // Agregar variable custom a page.html.twig
  $config = \Drupal::config('system.site');
  $variables['nombre_sitio'] = $config->get('name');
}

function mi_tema_preprocess_node(&$variables) {
  $node = $variables['node'];
  // Agregar la fecha formateada
  $variables['fecha_publicacion'] = \Drupal::service('date.formatter')
    ->format($node->getCreatedTime(), 'custom', 'd/m/Y');
}

// Agregar template suggestions custom
function mi_tema_theme_suggestions_page_alter(array &$suggestions, array $variables) {
  $path = \Drupal::request()->getPathInfo();
  if ($path === '/') {
    $suggestions[] = 'page__front';  // page--front.html.twig para el home
  }
}
CODE;
echo PHP_EOL . PHP_EOL;


echo "=== 6. ACTIVAR EL TEMA ===" . PHP_EOL;
echo PHP_EOL;
echo "ddev drush theme:enable mi_tema" . PHP_EOL;
echo "ddev drush config:set system.theme default mi_tema -y" . PHP_EOL;
echo "ddev drush cr" . PHP_EOL;
echo PHP_EOL;
echo "O desde la admin: /admin/appearance" . PHP_EOL;
echo PHP_EOL;


echo "=== 7. BUENAS PRACTICAS ===" . PHP_EOL;
echo PHP_EOL;
echo "- Usa BEM para nombrar clases CSS (bloque__elemento--modificador)" . PHP_EOL;
echo "- Separa CSS en base, layout y componentes" . PHP_EOL;
echo "- Usa las regiones para estructurar, no hardcodees contenido" . PHP_EOL;
echo "- Sobreescribe solo los templates que necesites" . PHP_EOL;
echo "- Activa Twig debug durante desarrollo" . PHP_EOL;
echo "- No pongas logica compleja en .theme, usa modulos/servicios" . PHP_EOL;
echo PHP_EOL;


echo "=== RESUMEN ===" . PHP_EOL;
echo "1. .info.yml define regiones, base theme, libraries" . PHP_EOL;
echo "2. .libraries.yml para CSS/JS" . PHP_EOL;
echo "3. templates/ con page.html.twig como layout principal" . PHP_EOL;
echo "4. .theme para preprocess (agregar variables a templates)" . PHP_EOL;
echo "5. Sobreescribir templates del core copiandolos a tu tema" . PHP_EOL;
echo "6. Template suggestions para variantes especificas" . PHP_EOL;
echo PHP_EOL;
echo "Con esto terminamos la Fase 3!" . PHP_EOL;
echo "Siguiente: Fase 4 — Desarrollo avanzado" . PHP_EOL;
