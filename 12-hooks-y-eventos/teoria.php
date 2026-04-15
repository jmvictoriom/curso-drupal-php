<?php

/**
 * =============================================================
 *  LECCION 12: HOOKS Y EVENTOS
 * =============================================================
 *  Los hooks permiten que tu modulo "se enganche" al sistema
 *  y modifique comportamiento de Drupal y otros modulos.
 * =============================================================
 */

echo "=== 1. QUE SON LOS HOOKS ===" . PHP_EOL;
echo PHP_EOL;
echo "Un hook es una funcion con un nombre especial que Drupal llama" . PHP_EOL;
echo "automaticamente en momentos concretos." . PHP_EOL;
echo PHP_EOL;
echo "Convencion: nombre_modulo_nombre_hook()" . PHP_EOL;
echo "Ejemplo: mi_modulo_form_alter() se llama cuando se construye un formulario" . PHP_EOL;
echo PHP_EOL;
echo "Los hooks van en el archivo .module" . PHP_EOL;
echo PHP_EOL;

echo "=== 2. HOOKS MAS USADOS ===" . PHP_EOL;
echo PHP_EOL;

echo "--- hook_form_alter() ---" . PHP_EOL;
echo "Modifica CUALQUIER formulario del sistema." . PHP_EOL;
echo <<<'CODE'

/**
 * Implements hook_form_alter().
 */
function mi_modulo_form_alter(&$form, $form_state, $form_id) {
  // Modificar un formulario especifico
  if ($form_id === 'node_article_form') {
    // Agregar una clase CSS
    $form['#attributes']['class'][] = 'mi-formulario-custom';

    // Cambiar el texto de un boton
    $form['actions']['submit']['#value'] = t('Publicar articulo');

    // Ocultar un campo
    $form['field_tags']['#access'] = FALSE;

    // Agregar un mensaje
    \Drupal::messenger()->addMessage('Estas creando un articulo');
  }
}
CODE;
echo PHP_EOL . PHP_EOL;

echo "--- hook_form_FORM_ID_alter() ---" . PHP_EOL;
echo "Version especifica para un solo formulario (mas eficiente)." . PHP_EOL;
echo <<<'CODE'

/**
 * Implements hook_form_FORM_ID_alter() for node_article_form.
 */
function mi_modulo_form_node_article_form_alter(&$form, $form_state, $form_id) {
  $form['actions']['submit']['#value'] = t('Guardar mi articulo');
}
CODE;
echo PHP_EOL . PHP_EOL;

echo "--- hook_page_attachments() ---" . PHP_EOL;
echo "Agrega CSS/JS a las paginas." . PHP_EOL;
echo <<<'CODE'

/**
 * Implements hook_page_attachments().
 */
function mi_modulo_page_attachments(array &$attachments) {
  $attachments['#attached']['library'][] = 'mi_modulo/mi-estilos';
}
CODE;
echo PHP_EOL . PHP_EOL;

echo "--- hook_theme() ---" . PHP_EOL;
echo "Registra templates Twig para tu modulo." . PHP_EOL;
echo <<<'CODE'

/**
 * Implements hook_theme().
 */
function mi_modulo_theme() {
  return [
    'mi_template' => [
      'variables' => [
        'titulo' => NULL,
        'items' => [],
      ],
    ],
  ];
}
// Esto busca el template: templates/mi-template.html.twig
CODE;
echo PHP_EOL . PHP_EOL;

echo "--- hook_cron() ---" . PHP_EOL;
echo "Se ejecuta periodicamente (tareas programadas)." . PHP_EOL;
echo <<<'CODE'

/**
 * Implements hook_cron().
 */
function mi_modulo_cron() {
  \Drupal::logger('mi_modulo')->info('Cron ejecutado');
  // Limpiar datos antiguos, enviar emails, etc.
}
CODE;
echo PHP_EOL . PHP_EOL;

echo "--- hook_install() / hook_uninstall() ---" . PHP_EOL;
echo "Se ejecutan al instalar/desinstalar el modulo." . PHP_EOL;
echo "Van en mi_modulo.install (no en .module)" . PHP_EOL;
echo <<<'CODE'

/**
 * Implements hook_install().
 */
function mi_modulo_install() {
  \Drupal::messenger()->addMessage('Modulo instalado correctamente!');
}
CODE;
echo PHP_EOL . PHP_EOL;

echo "--- hook_preprocess_HOOK() ---" . PHP_EOL;
echo "Modifica variables antes de enviarlas al template Twig." . PHP_EOL;
echo <<<'CODE'

/**
 * Implements hook_preprocess_node().
 */
function mi_modulo_preprocess_node(&$variables) {
  // Agregar una variable al template de nodos
  $variables['mi_variable'] = 'Valor custom';

  // Disponible en node.html.twig como {{ mi_variable }}
}
CODE;
echo PHP_EOL . PHP_EOL;


echo "=== 3. ENTITY HOOKS ===" . PHP_EOL;
echo PHP_EOL;
echo "Hooks que se ejecutan al crear, actualizar o borrar entidades." . PHP_EOL;
echo <<<'CODE'

/**
 * Implements hook_entity_presave().
 */
function mi_modulo_entity_presave($entity) {
  // Se ejecuta ANTES de guardar cualquier entidad
  if ($entity->getEntityTypeId() === 'node' && $entity->bundle() === 'article') {
    // Forzar que el titulo sea en mayusculas
    $titulo = $entity->getTitle();
    $entity->setTitle(strtoupper($titulo));
  }
}

/**
 * Implements hook_entity_insert().
 */
function mi_modulo_entity_insert($entity) {
  // Se ejecuta DESPUES de crear una nueva entidad
  if ($entity->getEntityTypeId() === 'node') {
    \Drupal::logger('mi_modulo')->info('Nuevo nodo creado: @title', [
      '@title' => $entity->getTitle(),
    ]);
  }
}

/**
 * Implements hook_entity_update().
 */
function mi_modulo_entity_update($entity) {
  // Se ejecuta DESPUES de actualizar una entidad existente
}

/**
 * Implements hook_entity_delete().
 */
function mi_modulo_entity_delete($entity) {
  // Se ejecuta DESPUES de borrar una entidad
}
CODE;
echo PHP_EOL . PHP_EOL;


echo "=== 4. EVENTOS (SISTEMA MODERNO) ===" . PHP_EOL;
echo PHP_EOL;
echo "Los eventos son la alternativa OOP a los hooks." . PHP_EOL;
echo "Usan el sistema de eventos de Symfony." . PHP_EOL;
echo PHP_EOL;

echo "--- Crear un Event Subscriber ---" . PHP_EOL;
echo <<<'CODE'
<?php
// src/EventSubscriber/MiSubscriber.php

namespace Drupal\mi_modulo\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class MiSubscriber implements EventSubscriberInterface {

  public static function getSubscribedEvents() {
    return [
      KernelEvents::REQUEST => ['onRequest', 100],
    ];
  }

  public function onRequest(RequestEvent $event) {
    // Se ejecuta en cada peticion HTTP
    $path = $event->getRequest()->getPathInfo();
    \Drupal::logger('mi_modulo')->info('Peticion a: @path', ['@path' => $path]);
  }

}
CODE;
echo PHP_EOL . PHP_EOL;

echo "--- Registrar el subscriber como servicio ---" . PHP_EOL;
$subscriber_service = <<<'YAML'
# mi_modulo.services.yml
services:
  mi_modulo.subscriber:
    class: Drupal\mi_modulo\EventSubscriber\MiSubscriber
    tags:
      - { name: event_subscriber }
YAML;
echo $subscriber_service . PHP_EOL;
echo PHP_EOL;

echo "El tag 'event_subscriber' es clave: le dice a Drupal que este" . PHP_EOL;
echo "servicio escucha eventos." . PHP_EOL;
echo PHP_EOL;


echo "=== 5. HOOKS vs EVENTOS ===" . PHP_EOL;
echo PHP_EOL;
echo "Hooks:" . PHP_EOL;
echo "  + Simples, faciles de entender" . PHP_EOL;
echo "  + Rapidos de implementar" . PHP_EOL;
echo "  - Funciones globales (no OOP)" . PHP_EOL;
echo "  - No se pueden priorizar facilmente" . PHP_EOL;
echo PHP_EOL;
echo "Eventos:" . PHP_EOL;
echo "  + Orientados a objetos" . PHP_EOL;
echo "  + Se pueden priorizar" . PHP_EOL;
echo "  + Se pueden parar (stopPropagation)" . PHP_EOL;
echo "  - Mas codigo para implementar" . PHP_EOL;
echo PHP_EOL;
echo "En la practica: usa hooks para lo comun (form_alter, preprocess)." . PHP_EOL;
echo "Usa eventos para logica compleja o cuando necesites prioridad." . PHP_EOL;
echo PHP_EOL;


echo "=== RESUMEN ===" . PHP_EOL;
echo "1. Hooks: funciones con nombre especial en .module" . PHP_EOL;
echo "2. hook_form_alter: modifica formularios" . PHP_EOL;
echo "3. hook_entity_presave/insert/update/delete: reacciona a entidades" . PHP_EOL;
echo "4. hook_preprocess_HOOK: modifica variables de templates" . PHP_EOL;
echo "5. Eventos: alternativa OOP con EventSubscriberInterface" . PHP_EOL;
echo "6. Siempre: ddev drush cr despues de cambios en hooks" . PHP_EOL;
