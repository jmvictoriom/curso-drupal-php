<?php

/**
 * =============================================================
 *  LECCION 17: TWIG Y THEMING
 * =============================================================
 *  Twig es el motor de plantillas de Drupal.
 *  Separa la logica PHP de la presentacion HTML.
 * =============================================================
 */

echo "=== 1. QUE ES TWIG ===" . PHP_EOL;
echo PHP_EOL;
echo "Twig es un motor de plantillas creado por Symfony." . PHP_EOL;
echo "Los archivos terminan en .html.twig" . PHP_EOL;
echo "Drupal pasa variables desde PHP a Twig para renderizar HTML." . PHP_EOL;
echo PHP_EOL;

echo "=== 2. SINTAXIS BASICA ===" . PHP_EOL;
echo PHP_EOL;
echo '{{ variable }}          -> imprimir una variable' . PHP_EOL;
echo '{% if condicion %}      -> logica (if, for, set...)' . PHP_EOL;
echo '{# comentario #}       -> comentarios (no se renderizan)' . PHP_EOL;
echo PHP_EOL;

echo "--- Imprimir variables ---" . PHP_EOL;
echo '{{ titulo }}' . PHP_EOL;
echo '{{ usuario.nombre }}' . PHP_EOL;
echo '{{ items[0] }}' . PHP_EOL;
echo PHP_EOL;

echo "--- Filtros (transformar datos) ---" . PHP_EOL;
echo '{{ nombre|upper }}              -> MAYUSCULAS' . PHP_EOL;
echo '{{ nombre|lower }}              -> minusculas' . PHP_EOL;
echo '{{ nombre|capitalize }}         -> Primera Letra' . PHP_EOL;
echo '{{ texto|striptags }}           -> quitar HTML' . PHP_EOL;
echo '{{ texto|length }}              -> longitud' . PHP_EOL;
echo '{{ fecha|date("d/m/Y") }}      -> formatear fecha' . PHP_EOL;
echo '{{ precio|number_format(2) }}  -> formatear numero' . PHP_EOL;
echo '{{ lista|join(", ") }}         -> unir lista' . PHP_EOL;
echo '{{ texto|default("N/A") }}     -> valor por defecto' . PHP_EOL;
echo '{{ html|raw }}                  -> HTML sin escapar (cuidado!)' . PHP_EOL;
echo "{{ 'texto'|t }}                -> traduccion en Drupal" . PHP_EOL;
echo PHP_EOL;

echo "--- Condicionales ---" . PHP_EOL;
echo <<<'TWIG'
{% if usuario.logueado %}
  <p>Hola, {{ usuario.nombre }}</p>
{% elseif usuario.invitado %}
  <p>Bienvenido, invitado</p>
{% else %}
  <p>Inicia sesion</p>
{% endif %}

{# Operadores: and, or, not, ==, !=, <, >, >=, <= #}
{% if edad >= 18 and tiene_dni %}
  <p>Puede votar</p>
{% endif %}

{# Comprobar si existe #}
{% if nombre is defined %}
{% if nombre is not empty %}
{% if nombre is not null %}
TWIG;
echo PHP_EOL . PHP_EOL;

echo "--- Bucles ---" . PHP_EOL;
echo <<<'TWIG'
{# Lista simple #}
<ul>
{% for item in items %}
  <li>{{ item }}</li>
{% endfor %}
</ul>

{# Con clave y valor #}
{% for key, value in datos %}
  <dt>{{ key }}</dt>
  <dd>{{ value }}</dd>
{% endfor %}

{# Con indice (loop) #}
{% for item in items %}
  <p>{{ loop.index }}. {{ item }}</p>
  {# loop.index (desde 1), loop.index0 (desde 0) #}
  {# loop.first, loop.last, loop.length #}
{% endfor %}

{# Si la lista esta vacia #}
{% for item in items %}
  <li>{{ item }}</li>
{% else %}
  <li>No hay items</li>
{% endfor %}
TWIG;
echo PHP_EOL . PHP_EOL;

echo "--- Set (crear variables) ---" . PHP_EOL;
echo <<<'TWIG'
{% set nombre = "Jesus" %}
{% set clases = ['card', 'destacado', tipo|clean_class] %}
TWIG;
echo PHP_EOL . PHP_EOL;


echo "=== 3. TEMPLATES EN DRUPAL ===" . PHP_EOL;
echo PHP_EOL;
echo "Drupal busca templates en este orden:" . PHP_EOL;
echo "  1. tu_tema/templates/" . PHP_EOL;
echo "  2. modulo/templates/" . PHP_EOL;
echo "  3. core/themes/templates/" . PHP_EOL;
echo PHP_EOL;
echo "Templates principales:" . PHP_EOL;
echo "  page.html.twig           -> estructura de la pagina" . PHP_EOL;
echo "  node.html.twig           -> nodo/contenido" . PHP_EOL;
echo "  block.html.twig          -> bloque" . PHP_EOL;
echo "  field.html.twig          -> campo" . PHP_EOL;
echo "  html.html.twig           -> estructura HTML base" . PHP_EOL;
echo "  views-view.html.twig     -> vista" . PHP_EOL;
echo "  region.html.twig         -> region del tema" . PHP_EOL;
echo PHP_EOL;

echo "Template suggestions (variantes especificas):" . PHP_EOL;
echo "  node.html.twig                  -> todos los nodos" . PHP_EOL;
echo "  node--article.html.twig         -> solo articulos" . PHP_EOL;
echo "  node--article--teaser.html.twig -> articulos en teaser" . PHP_EOL;
echo "  node--42.html.twig              -> nodo con ID 42" . PHP_EOL;
echo PHP_EOL;


echo "=== 4. CREAR TEMPLATES PARA TU MODULO ===" . PHP_EOL;
echo PHP_EOL;

echo "--- Paso 1: Registrar el template (hook_theme) ---" . PHP_EOL;
echo <<<'CODE'
// mi_modulo.module
function mi_modulo_theme() {
  return [
    'mi_tarjeta' => [
      'variables' => [
        'titulo' => NULL,
        'descripcion' => NULL,
        'imagen' => NULL,
        'enlace' => NULL,
      ],
    ],
  ];
}
CODE;
echo PHP_EOL . PHP_EOL;

echo "--- Paso 2: Crear el template ---" . PHP_EOL;
echo "Archivo: templates/mi-tarjeta.html.twig" . PHP_EOL;
echo "(el nombre usa guiones en vez de guiones bajos)" . PHP_EOL;
echo PHP_EOL;
echo <<<'TWIG'
{# templates/mi-tarjeta.html.twig #}
<div class="tarjeta">
  {% if imagen %}
    <div class="tarjeta__imagen">
      <img src="{{ imagen }}" alt="{{ titulo }}">
    </div>
  {% endif %}

  <div class="tarjeta__contenido">
    <h3 class="tarjeta__titulo">{{ titulo }}</h3>

    {% if descripcion %}
      <p class="tarjeta__descripcion">{{ descripcion }}</p>
    {% endif %}

    {% if enlace %}
      <a href="{{ enlace }}" class="tarjeta__enlace">Leer mas</a>
    {% endif %}
  </div>
</div>
TWIG;
echo PHP_EOL . PHP_EOL;

echo "--- Paso 3: Usar desde un Controller ---" . PHP_EOL;
echo <<<'CODE'
public function miPagina() {
  return [
    '#theme' => 'mi_tarjeta',
    '#titulo' => 'Mi primer articulo',
    '#descripcion' => 'Descripcion del articulo...',
    '#imagen' => '/sites/default/files/foto.jpg',
    '#enlace' => '/node/1',
  ];
}
CODE;
echo PHP_EOL . PHP_EOL;


echo "=== 5. DEBUG EN TWIG ===" . PHP_EOL;
echo PHP_EOL;
echo "Activa el modo debug en services.yml del sitio:" . PHP_EOL;
echo <<<'YAML'
# web/sites/default/services.yml (o development.services.yml)
parameters:
  twig.config:
    debug: true
    auto_reload: true
    cache: false
YAML;
echo PHP_EOL . PHP_EOL;
echo "Con debug activado, en el HTML veras comentarios como:" . PHP_EOL;
echo "  <!-- THEME DEBUG -->" . PHP_EOL;
echo "  <!-- THEME HOOK: 'node' -->" . PHP_EOL;
echo "  <!-- FILE NAME SUGGESTIONS:" . PHP_EOL;
echo "     * node--article--full.html.twig" . PHP_EOL;
echo "     * node--article.html.twig" . PHP_EOL;
echo "     x node.html.twig              <- el que se esta usando" . PHP_EOL;
echo "  -->" . PHP_EOL;
echo PHP_EOL;

echo "Funciones de debug dentro de Twig:" . PHP_EOL;
echo '  {{ dump(variable) }}    -> muestra contenido (requiere devel)' . PHP_EOL;
echo '  {{ kint(variable) }}    -> muestra contenido mejorado (requiere devel)' . PHP_EOL;
echo PHP_EOL;


echo "=== 6. AGREGAR CSS/JS ===" . PHP_EOL;
echo PHP_EOL;
echo "--- Definir libreria ---" . PHP_EOL;
echo "Archivo: mi_modulo.libraries.yml" . PHP_EOL;
$libraries = <<<'YAML'
mi-estilos:
  css:
    theme:
      css/mi-modulo.css: {}
  js:
    js/mi-modulo.js: {}
  dependencies:
    - core/jquery
YAML;
echo $libraries . PHP_EOL . PHP_EOL;

echo "--- Adjuntar en un render array ---" . PHP_EOL;
echo <<<'CODE'
$build['#attached']['library'][] = 'mi_modulo/mi-estilos';
CODE;
echo PHP_EOL . PHP_EOL;

echo "--- Adjuntar en Twig ---" . PHP_EOL;
echo '{{ attach_library("mi_modulo/mi-estilos") }}' . PHP_EOL;
echo PHP_EOL;


echo "=== RESUMEN ===" . PHP_EOL;
echo '1. {{ var }}, {% logica %}, {# comentario #}' . PHP_EOL;
echo "2. Filtros: |upper, |lower, |date, |t, |raw" . PHP_EOL;
echo "3. hook_theme() + templates/ para templates custom" . PHP_EOL;
echo "4. Template suggestions para variantes (node--article.html.twig)" . PHP_EOL;
echo "5. Debug: twig.config.debug: true" . PHP_EOL;
echo "6. Libraries: .libraries.yml para CSS/JS" . PHP_EOL;
