<?php

/**
 * =============================================================
 *  LECCION 14: ENTITY API Y CAMPOS
 * =============================================================
 *  Como trabajar con entidades de Drupal programaticamente.
 * =============================================================
 */

echo "=== 1. CARGAR ENTIDADES ===" . PHP_EOL;
echo PHP_EOL;
echo <<<'CODE'
// Cargar un nodo por ID
$node = \Drupal\node\Entity\Node::load(1);

// Via entity_type.manager (recomendado en servicios/controllers)
$storage = \Drupal::entityTypeManager()->getStorage('node');
$node = $storage->load(1);

// Cargar multiples
$nodes = $storage->loadMultiple([1, 2, 3]);

// Cargar usuario
$user = \Drupal\user\Entity\User::load(1);

// Cargar termino de taxonomia
$term = \Drupal\taxonomy\Entity\Term::load(5);
CODE;
echo PHP_EOL . PHP_EOL;

echo "=== 2. LEER CAMPOS ===" . PHP_EOL;
echo PHP_EOL;
echo <<<'CODE'
$node = Node::load(1);

// Titulo
$titulo = $node->getTitle();       // o $node->get('title')->value

// Campos base
$nid = $node->id();
$tipo = $node->bundle();           // 'article', 'page', etc.
$creado = $node->getCreatedTime(); // timestamp
$autor = $node->getOwnerId();
$publicado = $node->isPublished();

// Campos custom (field_*)
$cuerpo = $node->get('body')->value;           // texto plano
$cuerpo_html = $node->get('body')->processed;  // HTML procesado

// Campo de referencia a entidad
$ref = $node->get('field_categoria')->entity;  // la entidad referenciada
$nombre_cat = $ref->getName();

// Campo de imagen
$imagen = $node->get('field_imagen')->entity;  // entidad File
$url_imagen = $imagen->createFileUrl();

// Campo multiple (devuelve varios valores)
$tags = $node->get('field_tags')->referencedEntities();
foreach ($tags as $tag) {
  echo $tag->getName();
}
CODE;
echo PHP_EOL . PHP_EOL;

echo "=== 3. CREAR ENTIDADES ===" . PHP_EOL;
echo PHP_EOL;
echo <<<'CODE'
use Drupal\node\Entity\Node;

// Crear un nodo
$node = Node::create([
  'type' => 'article',
  'title' => 'Mi articulo programatico',
  'body' => [
    'value' => '<p>Contenido del articulo</p>',
    'format' => 'full_html',
  ],
  'field_tags' => [1, 2, 3],  // IDs de terminos
  'status' => 1,               // publicado
]);
$node->save();
echo "Nodo creado con ID: " . $node->id();

// Crear un termino de taxonomia
use Drupal\taxonomy\Entity\Term;

$term = Term::create([
  'vid' => 'categorias',       // vocabulario
  'name' => 'Tecnologia',
]);
$term->save();
CODE;
echo PHP_EOL . PHP_EOL;

echo "=== 4. ACTUALIZAR ENTIDADES ===" . PHP_EOL;
echo PHP_EOL;
echo <<<'CODE'
$node = Node::load(1);
$node->setTitle('Titulo actualizado');
$node->set('body', [
  'value' => '<p>Nuevo contenido</p>',
  'format' => 'full_html',
]);
$node->set('field_categoria', 5);  // cambiar referencia
$node->save();
CODE;
echo PHP_EOL . PHP_EOL;

echo "=== 5. ELIMINAR ENTIDADES ===" . PHP_EOL;
echo PHP_EOL;
echo <<<'CODE'
$node = Node::load(1);
$node->delete();

// Eliminar multiples
$storage = \Drupal::entityTypeManager()->getStorage('node');
$nodes = $storage->loadMultiple([2, 3, 4]);
$storage->delete($nodes);
CODE;
echo PHP_EOL . PHP_EOL;

echo "=== 6. ENTITY QUERIES ===" . PHP_EOL;
echo PHP_EOL;
echo "Entity Query es la forma de buscar entidades con condiciones:" . PHP_EOL;
echo <<<'CODE'
// Buscar articulos publicados
$nids = \Drupal::entityQuery('node')
  ->condition('type', 'article')
  ->condition('status', 1)
  ->sort('created', 'DESC')
  ->range(0, 10)
  ->accessCheck(TRUE)
  ->execute();

$nodes = Node::loadMultiple($nids);

// Buscar con condiciones complejas
$nids = \Drupal::entityQuery('node')
  ->condition('type', 'article')
  ->condition('field_categoria', 5)              // referencia
  ->condition('created', strtotime('-30 days'), '>')  // ultimos 30 dias
  ->condition('title', '%drupal%', 'LIKE')       // titulo contiene "drupal"
  ->sort('created', 'DESC')
  ->range(0, 20)
  ->accessCheck(TRUE)
  ->execute();

// Buscar usuarios por rol
$uids = \Drupal::entityQuery('user')
  ->condition('roles', 'administrator')
  ->accessCheck(TRUE)
  ->execute();

// Buscar terminos de un vocabulario
$tids = \Drupal::entityQuery('taxonomy_term')
  ->condition('vid', 'categorias')
  ->sort('name')
  ->accessCheck(TRUE)
  ->execute();

// Contar resultados
$total = \Drupal::entityQuery('node')
  ->condition('type', 'article')
  ->count()
  ->accessCheck(TRUE)
  ->execute();
CODE;
echo PHP_EOL . PHP_EOL;

echo "=== 7. ENTITY QUERY OPERADORES ===" . PHP_EOL;
echo PHP_EOL;
echo "condition(\$field, \$value, \$operator)" . PHP_EOL;
echo PHP_EOL;
echo "  =        igual (default)" . PHP_EOL;
echo "  <>       distinto" . PHP_EOL;
echo "  >        mayor que" . PHP_EOL;
echo "  <        menor que" . PHP_EOL;
echo "  >=       mayor o igual" . PHP_EOL;
echo "  <=       menor o igual" . PHP_EOL;
echo "  LIKE     contiene (usa % como comodin)" . PHP_EOL;
echo "  IN       esta en una lista de valores" . PHP_EOL;
echo "  NOT IN   no esta en la lista" . PHP_EOL;
echo "  BETWEEN  entre dos valores" . PHP_EOL;
echo "  IS NULL   es null" . PHP_EOL;
echo "  IS NOT NULL  no es null" . PHP_EOL;
echo PHP_EOL;


echo "=== RESUMEN ===" . PHP_EOL;
echo "1. Cargar: Node::load(\$id), Storage->loadMultiple()" . PHP_EOL;
echo "2. Leer: ->getTitle(), ->get('field_name')->value" . PHP_EOL;
echo "3. Crear: Node::create([...])->save()" . PHP_EOL;
echo "4. Actualizar: ->set('campo', valor)->save()" . PHP_EOL;
echo "5. Eliminar: ->delete()" . PHP_EOL;
echo "6. Buscar: entityQuery con conditions, sort, range" . PHP_EOL;
echo "7. SIEMPRE: ->accessCheck(TRUE) en entity queries" . PHP_EOL;
