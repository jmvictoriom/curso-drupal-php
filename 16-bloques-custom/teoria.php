<?php

/**
 * =============================================================
 *  LECCION 16: BLOQUES CUSTOM (Block Plugin)
 * =============================================================
 *  Los bloques son piezas de contenido que se colocan en
 *  regiones del tema. Se crean como Plugins.
 * =============================================================
 */

echo "=== 1. QUE ES UN BLOQUE ===" . PHP_EOL;
echo PHP_EOL;
echo "Un bloque es un componente visual que se coloca en una region" . PHP_EOL;
echo "del tema (sidebar, header, footer, content...)." . PHP_EOL;
echo "Se gestionan en /admin/structure/block" . PHP_EOL;
echo PHP_EOL;

echo "=== 2. BLOQUE BASICO ===" . PHP_EOL;
echo PHP_EOL;
echo "Los bloques son Plugins. Van en src/Plugin/Block/" . PHP_EOL;
echo PHP_EOL;
echo <<<'CODE'
<?php
// src/Plugin/Block/HolaMundoBlock.php

namespace Drupal\mi_modulo\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Bloque Hola Mundo.
 *
 * @Block(
 *   id = "mi_modulo_hola_mundo",
 *   admin_label = @Translation("Hola Mundo"),
 *   category = @Translation("Custom"),
 * )
 */
class HolaMundoBlock extends BlockBase {

  public function build() {
    return [
      '#markup' => '<p>Hola Mundo desde un bloque!</p>',
    ];
  }

}
CODE;
echo PHP_EOL . PHP_EOL;

echo "Puntos clave:" . PHP_EOL;
echo "  - La anotacion @Block es OBLIGATORIA (Drupal la lee para descubrir el plugin)" . PHP_EOL;
echo "  - id: identificador unico" . PHP_EOL;
echo "  - admin_label: nombre en la admin" . PHP_EOL;
echo "  - build(): devuelve un render array" . PHP_EOL;
echo PHP_EOL;

echo "Despues de crear: ddev drush cr" . PHP_EOL;
echo "Colocar en: /admin/structure/block -> region -> Place block" . PHP_EOL;
echo PHP_EOL;


echo "=== 3. BLOQUE CON CONFIGURACION ===" . PHP_EOL;
echo PHP_EOL;
echo <<<'CODE'
<?php

namespace Drupal\mi_modulo\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Bloque configurable.
 *
 * @Block(
 *   id = "mi_modulo_configurable",
 *   admin_label = @Translation("Bloque Configurable"),
 *   category = @Translation("Custom"),
 * )
 */
class ConfigurableBlock extends BlockBase {

  // Valores por defecto de la configuracion
  public function defaultConfiguration() {
    return [
      'mensaje' => 'Mensaje por defecto',
      'cantidad' => 5,
    ];
  }

  // Formulario de configuracion del bloque
  public function blockForm($form, FormStateInterface $form_state) {
    $form['mensaje'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Mensaje'),
      '#default_value' => $this->configuration['mensaje'],
    ];

    $form['cantidad'] = [
      '#type' => 'number',
      '#title' => $this->t('Cantidad de items'),
      '#default_value' => $this->configuration['cantidad'],
      '#min' => 1,
      '#max' => 20,
    ];

    return $form;
  }

  // Guardar la configuracion
  public function blockSubmit($form, FormStateInterface $form_state) {
    $this->configuration['mensaje'] = $form_state->getValue('mensaje');
    $this->configuration['cantidad'] = $form_state->getValue('cantidad');
  }

  // Renderizar el bloque
  public function build() {
    $mensaje = $this->configuration['mensaje'];
    $cantidad = $this->configuration['cantidad'];

    $items = [];
    for ($i = 1; $i <= $cantidad; $i++) {
      $items[] = "Item $i";
    }

    return [
      'mensaje' => [
        '#markup' => "<p>$mensaje</p>",
      ],
      'lista' => [
        '#theme' => 'item_list',
        '#items' => $items,
      ],
    ];
  }

}
CODE;
echo PHP_EOL . PHP_EOL;


echo "=== 4. BLOQUE CON DEPENDENCY INJECTION ===" . PHP_EOL;
echo PHP_EOL;
echo <<<'CODE'
<?php

namespace Drupal\mi_modulo\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Session\AccountProxyInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * @Block(
 *   id = "mi_modulo_usuario_block",
 *   admin_label = @Translation("Info Usuario"),
 *   category = @Translation("Custom"),
 * )
 */
class UsuarioBlock extends BlockBase implements ContainerFactoryPluginInterface {

  public function __construct(
    array $configuration,
    $plugin_id,
    $plugin_definition,
    protected AccountProxyInterface $currentUser,
  ) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
  }

  public static function create(
    ContainerInterface $container,
    array $configuration,
    $plugin_id,
    $plugin_definition,
  ) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('current_user'),
    );
  }

  public function build() {
    return [
      '#markup' => '<p>Bienvenido, ' . $this->currentUser->getDisplayName() . '</p>',
    ];
  }

}
CODE;
echo PHP_EOL . PHP_EOL;

echo "=== 5. CONTROL DE ACCESO ===" . PHP_EOL;
echo PHP_EOL;
echo <<<'CODE'
use Drupal\Core\Access\AccessResult;
use Drupal\Core\Session\AccountInterface;

// Dentro del bloque:
public function blockAccess(AccountInterface $account) {
  // Solo visible para usuarios con permiso
  return AccessResult::allowedIfHasPermission($account, 'access content');

  // Solo para administradores
  return AccessResult::allowedIfHasRole($account, 'administrator');

  // Logica custom
  return AccessResult::allowedIf($account->isAuthenticated());
}
CODE;
echo PHP_EOL . PHP_EOL;

echo "=== 6. CACHE EN BLOQUES ===" . PHP_EOL;
echo PHP_EOL;
echo <<<'CODE'
public function getCacheMaxAge() {
  return 0;  // no cachear (para contenido dinamico)
  return 3600;  // cachear 1 hora
}

public function getCacheContexts() {
  return ['user'];  // diferente cache por usuario
}

public function getCacheTags() {
  return ['node_list'];  // invalidar cuando cambian los nodos
}
CODE;
echo PHP_EOL . PHP_EOL;

echo "=== RESUMEN ===" . PHP_EOL;
echo "1. Bloques son Plugins en src/Plugin/Block/" . PHP_EOL;
echo "2. Anotacion @Block obligatoria (id, admin_label)" . PHP_EOL;
echo "3. build() devuelve render array" . PHP_EOL;
echo "4. blockForm/blockSubmit para configuracion" . PHP_EOL;
echo "5. ContainerFactoryPluginInterface para inyectar servicios" . PHP_EOL;
echo "6. blockAccess() para controlar visibilidad" . PHP_EOL;
echo "7. Cache: getCacheMaxAge(), getCacheContexts(), getCacheTags()" . PHP_EOL;
