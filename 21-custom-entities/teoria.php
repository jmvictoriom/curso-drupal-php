<?php

/**
 * =============================================================
 *  LECCION 21: CREAR ENTIDADES DE CONTENIDO CUSTOM
 * =============================================================
 *  Las entidades son la base de datos de Drupal. Los nodos,
 *  usuarios, terminos de taxonomia... todos son entidades.
 *  Aqui aprendemos a crear nuestro propio tipo de entidad
 *  de contenido desde cero: "Contacto".
 * =============================================================
 */

echo "=== 1. TIPOS DE ENTIDADES EN DRUPAL ===" . PHP_EOL;
echo PHP_EOL;
echo "Drupal tiene dos tipos de entidades:" . PHP_EOL;
echo PHP_EOL;
echo "  Content Entity (entidad de contenido):" . PHP_EOL;
echo "    - Tienen campos configurables por la UI" . PHP_EOL;
echo "    - Se almacenan en la base de datos" . PHP_EOL;
echo "    - Ejemplos: Node, User, Comment, Term, Media" . PHP_EOL;
echo "    - Es lo que crearemos aqui" . PHP_EOL;
echo PHP_EOL;
echo "  Config Entity (entidad de configuracion):" . PHP_EOL;
echo "    - Almacenan configuracion exportable" . PHP_EOL;
echo "    - Se exportan a YAML" . PHP_EOL;
echo "    - Ejemplos: View, ImageStyle, NodeType, Vocabulary" . PHP_EOL;
echo PHP_EOL;

echo "=== 2. ANATOMIA DE UNA ENTIDAD CUSTOM ===" . PHP_EOL;
echo PHP_EOL;
echo "Para crear una entidad completa necesitamos:" . PHP_EOL;
echo "  1. Clase de la entidad (con anotacion)" . PHP_EOL;
echo "  2. Base fields (campos base definidos en codigo)" . PHP_EOL;
echo "  3. Form handlers (formularios de crear/editar/eliminar)" . PHP_EOL;
echo "  4. List builder (pagina de listado)" . PHP_EOL;
echo "  5. Access handler (control de permisos)" . PHP_EOL;
echo "  6. Rutas y enlaces de menu" . PHP_EOL;
echo "  7. Permisos" . PHP_EOL;
echo PHP_EOL;

echo "=== 3. LA CLASE DE LA ENTIDAD ===" . PHP_EOL;
echo PHP_EOL;
echo "Esta es la pieza central. La anotacion define TODO sobre la entidad." . PHP_EOL;
echo <<<'CODE'
<?php
// src/Entity/Contacto.php

namespace Drupal\contactos\Entity;

use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityChangedTrait;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\user\EntityOwnerTrait;

/**
 * Define la entidad Contacto.
 *
 * @ContentEntityType(
 *   id = "contacto",
 *   label = @Translation("Contacto"),
 *   label_collection = @Translation("Contactos"),
 *   label_singular = @Translation("contacto"),
 *   label_plural = @Translation("contactos"),
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\contactos\ContactoListBuilder",
 *     "form" = {
 *       "add" = "Drupal\contactos\Form\ContactoForm",
 *       "edit" = "Drupal\contactos\Form\ContactoForm",
 *       "delete" = "Drupal\Core\Entity\ContentEntityDeleteForm",
 *     },
 *     "access" = "Drupal\contactos\ContactoAccessControlHandler",
 *     "route_provider" = {
 *       "html" = "Drupal\Core\Entity\Routing\AdminHtmlRouteProvider",
 *     },
 *   },
 *   base_table = "contacto",
 *   admin_permission = "administer contactos",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "nombre",
 *     "uuid" = "uuid",
 *     "owner" = "uid",
 *   },
 *   links = {
 *     "canonical" = "/admin/contactos/{contacto}",
 *     "add-form" = "/admin/contactos/add",
 *     "edit-form" = "/admin/contactos/{contacto}/edit",
 *     "delete-form" = "/admin/contactos/{contacto}/delete",
 *     "collection" = "/admin/contactos",
 *   },
 * )
 */
class Contacto extends ContentEntityBase {

  use EntityChangedTrait;
  use EntityOwnerTrait;

  // Los base fields se definen aqui (siguiente seccion)

}
CODE;
echo PHP_EOL . PHP_EOL;

echo "Desglose de la anotacion:" . PHP_EOL;
echo "  id: identificador unico del tipo de entidad" . PHP_EOL;
echo "  label: nombres para la UI" . PHP_EOL;
echo "  handlers: clases que manejan listado, formularios, acceso, rutas" . PHP_EOL;
echo "  base_table: nombre de la tabla en MySQL" . PHP_EOL;
echo "  entity_keys: que campos son id, label, etc." . PHP_EOL;
echo "  links: URLs para las operaciones CRUD" . PHP_EOL;
echo PHP_EOL;

echo "=== 4. BASE FIELDS (CAMPOS BASE) ===" . PHP_EOL;
echo PHP_EOL;
echo "Los base fields son campos definidos en codigo (no configurables en UI)." . PHP_EOL;
echo "Se definen en el metodo estatico baseFieldDefinitions()." . PHP_EOL;
echo PHP_EOL;
echo <<<'CODE'
<?php
// Dentro de la clase Contacto:

  public static function baseFieldDefinitions(EntityTypeInterface $entity_type) {
    // Campos base del padre (id, uuid, etc.)
    $fields = parent::baseFieldDefinitions($entity_type);

    // Campo: Nombre (el label de la entidad)
    $fields['nombre'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Nombre'))
      ->setDescription(t('Nombre completo del contacto.'))
      ->setRequired(TRUE)
      ->setSettings([
        'max_length' => 255,
      ])
      ->setDisplayOptions('view', [
        'label' => 'hidden',
        'type' => 'string',
        'weight' => -10,
      ])
      ->setDisplayOptions('form', [
        'type' => 'string_textfield',
        'weight' => -10,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    // Campo: Email
    $fields['email'] = BaseFieldDefinition::create('email')
      ->setLabel(t('Email'))
      ->setDescription(t('Correo electronico del contacto.'))
      ->setRequired(TRUE)
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'basic_string',
        'weight' => -5,
      ])
      ->setDisplayOptions('form', [
        'type' => 'email_default',
        'weight' => -5,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    // Campo: Telefono
    $fields['telefono'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Telefono'))
      ->setDescription(t('Numero de telefono.'))
      ->setSettings([
        'max_length' => 20,
      ])
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'string',
        'weight' => 0,
      ])
      ->setDisplayOptions('form', [
        'type' => 'string_textfield',
        'weight' => 0,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    // Campo: Mensaje / Notas
    $fields['mensaje'] = BaseFieldDefinition::create('text_long')
      ->setLabel(t('Mensaje'))
      ->setDescription(t('Notas o mensaje del contacto.'))
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'text_default',
        'weight' => 5,
      ])
      ->setDisplayOptions('form', [
        'type' => 'text_textarea',
        'weight' => 5,
        'settings' => [
          'rows' => 5,
        ],
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    // Campo: Estado (activo/inactivo)
    $fields['estado'] = BaseFieldDefinition::create('boolean')
      ->setLabel(t('Activo'))
      ->setDescription(t('Indica si el contacto esta activo.'))
      ->setDefaultValue(TRUE)
      ->setDisplayOptions('form', [
        'type' => 'boolean_checkbox',
        'weight' => 10,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    // Campo: Fecha de creacion
    $fields['created'] = BaseFieldDefinition::create('created')
      ->setLabel(t('Fecha de creacion'))
      ->setDescription(t('Fecha en que se creo el contacto.'));

    // Campo: Fecha de modificacion
    $fields['changed'] = BaseFieldDefinition::create('changed')
      ->setLabel(t('Fecha de modificacion'))
      ->setDescription(t('Fecha de la ultima modificacion.'));

    // Campo: Autor (usuario que creo el contacto)
    // EntityOwnerTrait ya agrega el campo 'uid', pero podemos configurarlo:
    $fields += static::ownerBaseFieldDefinitions($entity_type);
    $fields['uid']
      ->setLabel(t('Autor'))
      ->setDescription(t('Usuario que creo el contacto.'))
      ->setDisplayOptions('form', [
        'type' => 'entity_reference_autocomplete',
        'weight' => 15,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    return $fields;
  }
CODE;
echo PHP_EOL . PHP_EOL;

echo "Tipos de campo disponibles para baseFieldDefinitions:" . PHP_EOL;
echo "  string       -> texto corto" . PHP_EOL;
echo "  string_long  -> texto largo sin formato" . PHP_EOL;
echo "  text         -> texto con formato" . PHP_EOL;
echo "  text_long    -> texto largo con formato" . PHP_EOL;
echo "  integer      -> numero entero" . PHP_EOL;
echo "  decimal      -> numero decimal" . PHP_EOL;
echo "  float        -> numero flotante" . PHP_EOL;
echo "  boolean      -> verdadero/falso" . PHP_EOL;
echo "  email        -> correo electronico" . PHP_EOL;
echo "  uri          -> URL" . PHP_EOL;
echo "  timestamp    -> fecha como timestamp" . PHP_EOL;
echo "  created      -> fecha de creacion (auto)" . PHP_EOL;
echo "  changed      -> fecha de modificacion (auto)" . PHP_EOL;
echo "  entity_reference -> referencia a otra entidad" . PHP_EOL;
echo "  image        -> imagen" . PHP_EOL;
echo "  file         -> archivo" . PHP_EOL;
echo "  list_string  -> lista de opciones (texto)" . PHP_EOL;
echo "  list_integer -> lista de opciones (entero)" . PHP_EOL;
echo PHP_EOL;

echo "=== 5. FORMULARIO DE LA ENTIDAD ===" . PHP_EOL;
echo PHP_EOL;
echo <<<'CODE'
<?php
// src/Form/ContactoForm.php

namespace Drupal\contactos\Form;

use Drupal\Core\Entity\ContentEntityForm;
use Drupal\Core\Form\FormStateInterface;

/**
 * Formulario para crear/editar contactos.
 */
class ContactoForm extends ContentEntityForm {

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    $entity = $this->getEntity();
    $status = parent::save($form, $form_state);

    if ($status === SAVED_NEW) {
      $this->messenger()->addMessage(
        $this->t('Contacto "%nombre" creado correctamente.', [
          '%nombre' => $entity->label(),
        ])
      );
    }
    else {
      $this->messenger()->addMessage(
        $this->t('Contacto "%nombre" actualizado.', [
          '%nombre' => $entity->label(),
        ])
      );
    }

    // Redirigir al listado
    $form_state->setRedirectUrl($entity->toUrl('collection'));
    return $status;
  }

}
CODE;
echo PHP_EOL . PHP_EOL;
echo "Nota: ContentEntityForm genera automaticamente los campos" . PHP_EOL;
echo "que tienen setDisplayOptions('form', ...) en baseFieldDefinitions." . PHP_EOL;
echo "No necesitas construir el formulario a mano!" . PHP_EOL;
echo PHP_EOL;

echo "=== 6. LIST BUILDER (PAGINA DE LISTADO) ===" . PHP_EOL;
echo PHP_EOL;
echo <<<'CODE'
<?php
// src/ContactoListBuilder.php

namespace Drupal\contactos;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityListBuilder;

/**
 * Lista de contactos en /admin/contactos.
 */
class ContactoListBuilder extends EntityListBuilder {

  /**
   * {@inheritdoc}
   */
  public function buildHeader() {
    $header['id'] = $this->t('ID');
    $header['nombre'] = $this->t('Nombre');
    $header['email'] = $this->t('Email');
    $header['estado'] = $this->t('Estado');
    $header['created'] = $this->t('Creado');
    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {
    $row['id'] = $entity->id();
    $row['nombre'] = $entity->toLink();
    $row['email'] = $entity->get('email')->value;
    $row['estado'] = $entity->get('estado')->value ? $this->t('Activo') : $this->t('Inactivo');
    $row['created'] = \Drupal::service('date.formatter')
      ->format($entity->get('created')->value, 'short');
    return $row + parent::buildRow($entity);
  }

}
CODE;
echo PHP_EOL . PHP_EOL;
echo "El listado muestra automaticamente enlaces de editar/eliminar" . PHP_EOL;
echo "por cada fila (parent::buildHeader/buildRow los agrega)." . PHP_EOL;
echo PHP_EOL;

echo "=== 7. ACCESS CONTROL HANDLER ===" . PHP_EOL;
echo PHP_EOL;
echo <<<'CODE'
<?php
// src/ContactoAccessControlHandler.php

namespace Drupal\contactos;

use Drupal\Core\Access\AccessResult;
use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;

/**
 * Control de acceso para la entidad Contacto.
 */
class ContactoAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(
    EntityInterface $entity,
    $operation,
    AccountInterface $account,
  ) {
    switch ($operation) {
      case 'view':
        return AccessResult::allowedIfHasPermission(
          $account, 'view contactos'
        );

      case 'update':
        return AccessResult::allowedIfHasPermission(
          $account, 'edit contactos'
        );

      case 'delete':
        return AccessResult::allowedIfHasPermission(
          $account, 'delete contactos'
        );
    }

    return AccessResult::neutral();
  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(
    AccountInterface $account,
    array $context,
    $entity_bundle = NULL,
  ) {
    return AccessResult::allowedIfHasPermission(
      $account, 'add contactos'
    );
  }

}
CODE;
echo PHP_EOL . PHP_EOL;

echo "=== 8. PERMISOS ===" . PHP_EOL;
echo PHP_EOL;
echo <<<'CODE'
# contactos.permissions.yml
administer contactos:
  title: 'Administrar contactos'
  description: 'Acceso total a la gestion de contactos.'

add contactos:
  title: 'Crear contactos'

view contactos:
  title: 'Ver contactos'

edit contactos:
  title: 'Editar contactos'

delete contactos:
  title: 'Eliminar contactos'
CODE;
echo PHP_EOL . PHP_EOL;

echo "=== 9. ENLACES DE MENU ===" . PHP_EOL;
echo PHP_EOL;

echo "--- Links de menu ---" . PHP_EOL;
echo <<<'CODE'
# contactos.links.menu.yml
contactos.admin:
  title: 'Contactos'
  route_name: entity.contacto.collection
  parent: system.admin_structure
  description: 'Gestionar contactos'
  weight: 10
CODE;
echo PHP_EOL . PHP_EOL;

echo "--- Links de accion (boton 'Agregar') ---" . PHP_EOL;
echo <<<'CODE'
# contactos.links.action.yml
entity.contacto.add_form:
  title: 'Agregar contacto'
  route_name: entity.contacto.add_form
  appears_on:
    - entity.contacto.collection
CODE;
echo PHP_EOL . PHP_EOL;

echo "=== 10. ARCHIVOS DE SOPORTE ===" . PHP_EOL;
echo PHP_EOL;
echo "--- contactos.info.yml ---" . PHP_EOL;
echo <<<'CODE'
name: Contactos
type: module
description: 'Entidad custom para gestionar contactos.'
core_version_requirement: ^10 || ^11
package: Custom
CODE;
echo PHP_EOL . PHP_EOL;

echo "--- Instalar y crear la tabla ---" . PHP_EOL;
echo "ddev drush en contactos -y" . PHP_EOL;
echo "ddev drush entity:updates    (crea la tabla en la BD)" . PHP_EOL;
echo "ddev drush cr" . PHP_EOL;
echo PHP_EOL;

echo "=== 11. ENTITY QUERY CON ENTIDAD CUSTOM ===" . PHP_EOL;
echo PHP_EOL;
echo <<<'CODE'
// Buscar contactos activos
$ids = \Drupal::entityQuery('contacto')
  ->condition('estado', TRUE)
  ->sort('nombre')
  ->accessCheck(TRUE)
  ->execute();

$contactos = \Drupal::entityTypeManager()
  ->getStorage('contacto')
  ->loadMultiple($ids);

foreach ($contactos as $contacto) {
  echo $contacto->get('nombre')->value . ' - '
    . $contacto->get('email')->value . PHP_EOL;
}

// Cargar un contacto especifico
$contacto = \Drupal::entityTypeManager()
  ->getStorage('contacto')
  ->load(1);
CODE;
echo PHP_EOL . PHP_EOL;

echo "=== 12. AGREGAR CAMPOS POR UI ===" . PHP_EOL;
echo PHP_EOL;
echo "Gracias a setDisplayConfigurable(TRUE), los campos base" . PHP_EOL;
echo "aparecen en la administracion de 'Manage fields'." . PHP_EOL;
echo "Ademas, puedes agregar campos adicionales por la UI" . PHP_EOL;
echo "si tu entidad tiene field_ui_base_route en la anotacion." . PHP_EOL;
echo PHP_EOL;
echo "Para habilitar la UI de campos, agrega esto a la anotacion:" . PHP_EOL;
echo <<<'CODE'
// En la anotacion @ContentEntityType, agregar:
//   field_ui_base_route = "entity.contacto.collection",

// Y agrega 'field_ui' como dependencia en contactos.info.yml:
// dependencies:
//   - field_ui
CODE;
echo PHP_EOL . PHP_EOL;

echo "=== 13. ESTRUCTURA COMPLETA DEL MODULO ===" . PHP_EOL;
echo PHP_EOL;
echo "web/modules/custom/contactos/" . PHP_EOL;
echo "├── contactos.info.yml" . PHP_EOL;
echo "├── contactos.permissions.yml" . PHP_EOL;
echo "├── contactos.links.menu.yml" . PHP_EOL;
echo "├── contactos.links.action.yml" . PHP_EOL;
echo "└── src/" . PHP_EOL;
echo "    ├── Entity/" . PHP_EOL;
echo "    │   └── Contacto.php" . PHP_EOL;
echo "    ├── Form/" . PHP_EOL;
echo "    │   └── ContactoForm.php" . PHP_EOL;
echo "    ├── ContactoListBuilder.php" . PHP_EOL;
echo "    └── ContactoAccessControlHandler.php" . PHP_EOL;
echo PHP_EOL;

echo "=== RESUMEN ===" . PHP_EOL;
echo "1. Entidad = clase PHP con anotacion @ContentEntityType" . PHP_EOL;
echo "2. baseFieldDefinitions(): define campos en codigo" . PHP_EOL;
echo "3. ContentEntityForm: formulario automatico basado en campos" . PHP_EOL;
echo "4. EntityListBuilder: tabla de listado con operaciones" . PHP_EOL;
echo "5. EntityAccessControlHandler: permisos por operacion (CRUD)" . PHP_EOL;
echo "6. AdminHtmlRouteProvider: genera rutas automaticamente" . PHP_EOL;
echo "7. entity_keys: mapea id, label, uuid, owner" . PHP_EOL;
echo "8. links: define URLs para canonical, add, edit, delete, collection" . PHP_EOL;
echo "9. Instalar: ddev drush en modulo -y && ddev drush entity:updates" . PHP_EOL;
echo "10. Entity queries funcionan igual que con nodos" . PHP_EOL;
