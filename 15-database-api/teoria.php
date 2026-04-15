<?php

/**
 * =============================================================
 *  LECCION 15: DATABASE API
 * =============================================================
 *  Drupal tiene su propia API de base de datos.
 *  Para entidades usa Entity Query, pero para tablas custom
 *  o consultas complejas necesitas Database API.
 * =============================================================
 */

echo "=== 1. CONEXION A LA BD ===" . PHP_EOL;
echo PHP_EOL;
echo <<<'CODE'
// Obtener la conexion (servicio 'database')
$database = \Drupal::database();

// En un servicio/controller (inyeccion)
use Drupal\Core\Database\Connection;

public function __construct(
  protected Connection $database,
) {}

public static function create(ContainerInterface $container) {
  return new static(
    $container->get('database'),
  );
}
CODE;
echo PHP_EOL . PHP_EOL;

echo "=== 2. SELECT ===" . PHP_EOL;
echo PHP_EOL;
echo <<<'CODE'
$database = \Drupal::database();

// Select simple
$result = $database->select('node_field_data', 'n')
  ->fields('n', ['nid', 'title', 'type'])
  ->condition('n.status', 1)
  ->condition('n.type', 'article')
  ->orderBy('n.created', 'DESC')
  ->range(0, 10)
  ->execute();

foreach ($result as $row) {
  echo "$row->nid - $row->title ($row->type)\n";
}

// Select con JOIN
$query = $database->select('node_field_data', 'n');
$query->join('users_field_data', 'u', 'n.uid = u.uid');
$query->fields('n', ['title']);
$query->fields('u', ['name']);
$query->condition('n.type', 'article');
$result = $query->execute();

// Obtener un solo valor
$count = $database->select('node_field_data', 'n')
  ->condition('n.type', 'article')
  ->countQuery()
  ->execute()
  ->fetchField();

// Obtener una sola fila
$row = $database->select('node_field_data', 'n')
  ->fields('n')
  ->condition('n.nid', 1)
  ->execute()
  ->fetchObject();

// Obtener todos como array asociativo
$rows = $database->select('node_field_data', 'n')
  ->fields('n', ['nid', 'title'])
  ->execute()
  ->fetchAllAssoc('nid');
CODE;
echo PHP_EOL . PHP_EOL;

echo "=== 3. INSERT ===" . PHP_EOL;
echo PHP_EOL;
echo <<<'CODE'
// Insertar un registro
$database->insert('mi_tabla')
  ->fields([
    'nombre' => 'Jesus',
    'email' => 'jesus@ejemplo.com',
    'created' => time(),
  ])
  ->execute();

// Insertar multiples registros
$query = $database->insert('mi_tabla')
  ->fields(['nombre', 'email', 'created']);

$datos = [
  ['Ana', 'ana@ejemplo.com', time()],
  ['Pedro', 'pedro@ejemplo.com', time()],
];

foreach ($datos as $dato) {
  $query->values($dato);
}
$query->execute();
CODE;
echo PHP_EOL . PHP_EOL;

echo "=== 4. UPDATE ===" . PHP_EOL;
echo PHP_EOL;
echo <<<'CODE'
$database->update('mi_tabla')
  ->fields(['email' => 'nuevo@email.com'])
  ->condition('nombre', 'Jesus')
  ->execute();
CODE;
echo PHP_EOL . PHP_EOL;

echo "=== 5. DELETE ===" . PHP_EOL;
echo PHP_EOL;
echo <<<'CODE'
$database->delete('mi_tabla')
  ->condition('nombre', 'Jesus')
  ->execute();
CODE;
echo PHP_EOL . PHP_EOL;

echo "=== 6. CREAR TABLAS CUSTOM (hook_schema) ===" . PHP_EOL;
echo PHP_EOL;
echo "Para crear tablas propias, usa hook_schema() en .install:" . PHP_EOL;
echo <<<'CODE'
<?php
// mi_modulo.install

function mi_modulo_schema() {
  $schema['mi_tabla'] = [
    'description' => 'Tabla de ejemplo',
    'fields' => [
      'id' => [
        'type' => 'serial',
        'unsigned' => TRUE,
        'not null' => TRUE,
      ],
      'nombre' => [
        'type' => 'varchar',
        'length' => 255,
        'not null' => TRUE,
      ],
      'email' => [
        'type' => 'varchar',
        'length' => 255,
        'not null' => TRUE,
      ],
      'created' => [
        'type' => 'int',
        'not null' => TRUE,
        'default' => 0,
      ],
    ],
    'primary key' => ['id'],
    'indexes' => [
      'nombre' => ['nombre'],
    ],
  ];
  return $schema;
}
CODE;
echo PHP_EOL . PHP_EOL;

echo "La tabla se crea al instalar el modulo." . PHP_EOL;
echo "Si el modulo ya esta instalado: ddev drush pmu mi_modulo && ddev drush en mi_modulo" . PHP_EOL;
echo PHP_EOL;

echo "=== 7. TRANSACCIONES ===" . PHP_EOL;
echo PHP_EOL;
echo <<<'CODE'
$transaction = $database->startTransaction();
try {
  $database->insert('mi_tabla')->fields([...])->execute();
  $database->update('otra_tabla')->fields([...])->condition(...)->execute();
  // Si todo va bien, el commit es automatico
}
catch (\Exception $e) {
  $transaction->rollBack();
  \Drupal::logger('mi_modulo')->error($e->getMessage());
}
CODE;
echo PHP_EOL . PHP_EOL;

echo "=== 8. ENTITY QUERY vs DATABASE API ===" . PHP_EOL;
echo PHP_EOL;
echo "Usa Entity Query cuando:" . PHP_EOL;
echo "  - Trabajas con entidades (nodos, usuarios, terminos...)" . PHP_EOL;
echo "  - Necesitas respetar permisos de acceso" . PHP_EOL;
echo "  - La consulta es relativamente simple" . PHP_EOL;
echo PHP_EOL;
echo "Usa Database API cuando:" . PHP_EOL;
echo "  - Trabajas con tablas custom" . PHP_EOL;
echo "  - Necesitas JOINs complejos" . PHP_EOL;
echo "  - Necesitas funciones de agregacion (COUNT, SUM, AVG)" . PHP_EOL;
echo "  - Quieres maximo rendimiento" . PHP_EOL;
echo PHP_EOL;

echo "=== RESUMEN ===" . PHP_EOL;
echo "1. \$database = \\Drupal::database() o inyectar Connection" . PHP_EOL;
echo "2. Select: ->select()->fields()->condition()->execute()" . PHP_EOL;
echo "3. Insert: ->insert()->fields()->execute()" . PHP_EOL;
echo "4. Update: ->update()->fields()->condition()->execute()" . PHP_EOL;
echo "5. Delete: ->delete()->condition()->execute()" . PHP_EOL;
echo "6. Tablas custom: hook_schema() en .install" . PHP_EOL;
echo "7. Entity Query para entidades, Database API para tablas custom" . PHP_EOL;
