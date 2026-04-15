<?php

/**
 * =============================================================
 *  LECCION 19: PLUGIN API EN PROFUNDIDAD
 * =============================================================
 *  El sistema de plugins es el corazon de Drupal. Bloques,
 *  formateadores de campos, plugins de Views, tipos de campo...
 *  todos son plugins. Aqui aprendemos como funciona internamente
 *  y como crear nuestros propios tipos de plugin.
 * =============================================================
 */

echo "=== 1. QUE ES EL SISTEMA DE PLUGINS ===" . PHP_EOL;
echo PHP_EOL;
echo "Un plugin en Drupal es una pieza de funcionalidad intercambiable." . PHP_EOL;
echo "Piensa en ello como el patron Strategy: muchas implementaciones," . PHP_EOL;
echo "una misma interfaz." . PHP_EOL;
echo PHP_EOL;
echo "Ejemplos de tipos de plugin en Drupal:" . PHP_EOL;
echo "  - Block        -> cada bloque es un plugin" . PHP_EOL;
echo "  - Field Type   -> text, integer, boolean, etc." . PHP_EOL;
echo "  - Field Widget -> como se edita un campo en formularios" . PHP_EOL;
echo "  - Field Formatter -> como se muestra un campo" . PHP_EOL;
echo "  - Views Plugin -> filtros, sorts, fields, etc." . PHP_EOL;
echo "  - ImageEffect  -> redimensionar, recortar, rotar" . PHP_EOL;
echo "  - Condition     -> condiciones de visibilidad" . PHP_EOL;
echo "  - Action        -> acciones de Drupal" . PHP_EOL;
echo PHP_EOL;
echo "Cada tipo de plugin tiene:" . PHP_EOL;
echo "  1. Un Plugin Manager (descubre y crea instancias)" . PHP_EOL;
echo "  2. Una interfaz (contrato que deben cumplir)" . PHP_EOL;
echo "  3. Una clase base (implementacion parcial comun)" . PHP_EOL;
echo "  4. Un mecanismo de descubrimiento (anotaciones o atributos)" . PHP_EOL;
echo PHP_EOL;

echo "=== 2. TIPOS DE DESCUBRIMIENTO ===" . PHP_EOL;
echo PHP_EOL;
echo "Drupal soporta varios mecanismos para descubrir plugins:" . PHP_EOL;
echo PHP_EOL;

echo "--- a) Anotaciones (Drupal 8/9/10) ---" . PHP_EOL;
echo "El mas comun. Metadata en un bloque de comentario PHP." . PHP_EOL;
echo <<<'CODE'
/**
 * @Block(
 *   id = "mi_bloque",
 *   admin_label = @Translation("Mi Bloque"),
 *   category = @Translation("Custom"),
 * )
 */
class MiBloque extends BlockBase {
  // ...
}
CODE;
echo PHP_EOL . PHP_EOL;

echo "--- b) Atributos PHP (Drupal 10.2+) ---" . PHP_EOL;
echo "La forma moderna, usando atributos nativos de PHP 8." . PHP_EOL;
echo <<<'CODE'
use Drupal\Core\Block\Attribute\Block;
use Drupal\Core\StringTranslation\TranslatableMarkup;

#[Block(
  id: 'mi_bloque',
  admin_label: new TranslatableMarkup('Mi Bloque'),
  category: new TranslatableMarkup('Custom'),
)]
class MiBloque extends BlockBase {
  // ...
}
CODE;
echo PHP_EOL . PHP_EOL;
echo "Ventajas de atributos sobre anotaciones:" . PHP_EOL;
echo "  - Sintaxis nativa de PHP (no docblocks)" . PHP_EOL;
echo "  - Mejor autocompletado en IDEs" . PHP_EOL;
echo "  - Validacion en tiempo de compilacion" . PHP_EOL;
echo "  - Es el futuro de Drupal (anotaciones estan deprecadas)" . PHP_EOL;
echo PHP_EOL;

echo "--- c) YAML Discovery ---" . PHP_EOL;
echo "Plugins definidos en archivos .yml (ej: menu links, local tasks)." . PHP_EOL;
echo <<<'CODE'
# mi_modulo.links.menu.yml
mi_modulo.admin:
  title: 'Mi Modulo'
  route_name: mi_modulo.admin
  parent: system.admin_structure
  weight: 10
CODE;
echo PHP_EOL . PHP_EOL;

echo "--- d) Hook Discovery ---" . PHP_EOL;
echo "Plugins definidos via hooks (legado, ej: hook_field_info)." . PHP_EOL;
echo "Casi no se usa en Drupal moderno." . PHP_EOL;
echo PHP_EOL;

echo "=== 3. PLUGIN MANAGER ===" . PHP_EOL;
echo PHP_EOL;
echo "El Plugin Manager es el servicio que descubre, instancia y" . PHP_EOL;
echo "gestiona todos los plugins de un tipo determinado." . PHP_EOL;
echo PHP_EOL;
echo <<<'CODE'
// Obtener el manager de bloques
$block_manager = \Drupal::service('plugin.manager.block');

// Listar todos los plugins de bloque disponibles
$definitions = $block_manager->getDefinitions();

foreach ($definitions as $plugin_id => $definition) {
  echo "Plugin: $plugin_id -> " . $definition['admin_label'] . PHP_EOL;
}

// Crear una instancia de un plugin especifico
$block = $block_manager->createInstance('mi_modulo_hola_mundo', []);

// Otros managers comunes:
$field_type_manager = \Drupal::service('plugin.manager.field.field_type');
$widget_manager = \Drupal::service('plugin.manager.field.widget');
$formatter_manager = \Drupal::service('plugin.manager.field.formatter');
$image_effect_manager = \Drupal::service('plugin.manager.image.effect');
$condition_manager = \Drupal::service('plugin.manager.condition');
CODE;
echo PHP_EOL . PHP_EOL;

echo "=== 4. CREAR UN TIPO DE PLUGIN CUSTOM ===" . PHP_EOL;
echo PHP_EOL;
echo "Vamos a crear un sistema de plugins 'Saludo' donde cada" . PHP_EOL;
echo "plugin genera un saludo diferente." . PHP_EOL;
echo PHP_EOL;

echo "--- Paso 1: Definir la anotacion ---" . PHP_EOL;
echo <<<'CODE'
<?php
// src/Annotation/Saludo.php

namespace Drupal\mi_modulo\Annotation;

use Drupal\Component\Annotation\Plugin;

/**
 * Define la anotacion para plugins de tipo Saludo.
 *
 * @Annotation
 */
class Saludo extends Plugin {

  /**
   * El ID del plugin.
   *
   * @var string
   */
  public $id;

  /**
   * Nombre legible del saludo.
   *
   * @var \Drupal\Core\Annotation\Translation
   *
   * @ingroup plugin_translatable
   */
  public $label;

  /**
   * Descripcion del saludo.
   *
   * @var \Drupal\Core\Annotation\Translation
   *
   * @ingroup plugin_translatable
   */
  public $description;

}
CODE;
echo PHP_EOL . PHP_EOL;

echo "--- Paso 2: Definir la interfaz ---" . PHP_EOL;
echo <<<'CODE'
<?php
// src/Plugin/Saludo/SaludoInterface.php

namespace Drupal\mi_modulo\Plugin\Saludo;

use Drupal\Component\Plugin\PluginInspectionInterface;

/**
 * Interfaz para plugins de tipo Saludo.
 */
interface SaludoInterface extends PluginInspectionInterface {

  /**
   * Devuelve el texto del saludo.
   *
   * @param string $nombre
   *   El nombre de la persona a saludar.
   *
   * @return string
   *   El saludo formateado.
   */
  public function saludar(string $nombre): string;

  /**
   * Devuelve el label del plugin.
   *
   * @return string
   */
  public function getLabel(): string;

}
CODE;
echo PHP_EOL . PHP_EOL;

echo "--- Paso 3: Crear la clase base ---" . PHP_EOL;
echo <<<'CODE'
<?php
// src/Plugin/Saludo/SaludoBase.php

namespace Drupal\mi_modulo\Plugin\Saludo;

use Drupal\Component\Plugin\PluginBase;

/**
 * Clase base para plugins de tipo Saludo.
 */
abstract class SaludoBase extends PluginBase implements SaludoInterface {

  /**
   * {@inheritdoc}
   */
  public function getLabel(): string {
    return $this->pluginDefinition['label'];
  }

}
CODE;
echo PHP_EOL . PHP_EOL;

echo "--- Paso 4: Crear el Plugin Manager ---" . PHP_EOL;
echo <<<'CODE'
<?php
// src/Plugin/Saludo/SaludoManager.php

namespace Drupal\mi_modulo\Plugin\Saludo;

use Drupal\Core\Cache\CacheBackendInterface;
use Drupal\Core\Extension\ModuleHandlerInterface;
use Drupal\Core\Plugin\DefaultPluginManager;

/**
 * Manager para plugins de tipo Saludo.
 */
class SaludoManager extends DefaultPluginManager {

  public function __construct(
    \Traversable $namespaces,
    CacheBackendInterface $cache_backend,
    ModuleHandlerInterface $module_handler,
  ) {
    parent::__construct(
      // Subdirectorio donde buscar plugins
      'Plugin/Saludo',
      $namespaces,
      $module_handler,
      // Interfaz que deben implementar
      'Drupal\mi_modulo\Plugin\Saludo\SaludoInterface',
      // Clase de la anotacion
      'Drupal\mi_modulo\Annotation\Saludo'
    );

    // Prefijo para hooks alter (permite hook_saludo_info_alter)
    $this->alterInfo('saludo_info');

    // Cache de las definiciones
    $this->setCacheBackend($cache_backend, 'saludo_plugins');
  }

}
CODE;
echo PHP_EOL . PHP_EOL;

echo "--- Paso 5: Registrar el manager como servicio ---" . PHP_EOL;
$services_yaml = <<<'YAML'
# mi_modulo.services.yml
services:
  plugin.manager.saludo:
    class: Drupal\mi_modulo\Plugin\Saludo\SaludoManager
    parent: default_plugin_manager
YAML;
echo $services_yaml . PHP_EOL;
echo PHP_EOL;
echo "Nota: 'parent: default_plugin_manager' inyecta automaticamente" . PHP_EOL;
echo "los argumentos comunes (namespaces, cache, module_handler)." . PHP_EOL;
echo PHP_EOL;

echo "=== 5. IMPLEMENTAR PLUGINS DEL NUEVO TIPO ===" . PHP_EOL;
echo PHP_EOL;
echo "Ahora cualquier modulo puede crear plugins de tipo Saludo." . PHP_EOL;
echo PHP_EOL;

echo "--- Plugin: Saludo Formal ---" . PHP_EOL;
echo <<<'CODE'
<?php
// src/Plugin/Saludo/SaludoFormal.php

namespace Drupal\mi_modulo\Plugin\Saludo;

/**
 * Saludo formal.
 *
 * @Saludo(
 *   id = "formal",
 *   label = @Translation("Saludo Formal"),
 *   description = @Translation("Un saludo formal y respetuoso"),
 * )
 */
class SaludoFormal extends SaludoBase {

  public function saludar(string $nombre): string {
    return "Estimado/a $nombre, es un placer saludarle.";
  }

}
CODE;
echo PHP_EOL . PHP_EOL;

echo "--- Plugin: Saludo Informal ---" . PHP_EOL;
echo <<<'CODE'
<?php
// src/Plugin/Saludo/SaludoInformal.php

namespace Drupal\mi_modulo\Plugin\Saludo;

/**
 * Saludo informal.
 *
 * @Saludo(
 *   id = "informal",
 *   label = @Translation("Saludo Informal"),
 *   description = @Translation("Un saludo casual y amigable"),
 * )
 */
class SaludoInformal extends SaludoBase {

  public function saludar(string $nombre): string {
    return "Que onda, $nombre! Como estas?";
  }

}
CODE;
echo PHP_EOL . PHP_EOL;

echo "--- Plugin: Saludo Navidad ---" . PHP_EOL;
echo <<<'CODE'
<?php
// src/Plugin/Saludo/SaludoNavidad.php

namespace Drupal\mi_modulo\Plugin\Saludo;

/**
 * Saludo navideno.
 *
 * @Saludo(
 *   id = "navidad",
 *   label = @Translation("Saludo Navideno"),
 *   description = @Translation("Saludo festivo para navidad"),
 * )
 */
class SaludoNavidad extends SaludoBase {

  public function saludar(string $nombre): string {
    return "Feliz Navidad, $nombre! Que tengas unas fiestas increibles!";
  }

}
CODE;
echo PHP_EOL . PHP_EOL;

echo "=== 6. USAR LOS PLUGINS ===" . PHP_EOL;
echo PHP_EOL;
echo <<<'CODE'
// En un Controller o servicio:
$saludo_manager = \Drupal::service('plugin.manager.saludo');

// Listar todos los saludos disponibles
$definiciones = $saludo_manager->getDefinitions();
foreach ($definiciones as $id => $definicion) {
  echo "Saludo disponible: $id - " . $definicion['label'] . PHP_EOL;
}

// Crear una instancia y usarla
$plugin = $saludo_manager->createInstance('formal');
echo $plugin->saludar('Maria');
// Output: "Estimado/a Maria, es un placer saludarle."

$plugin2 = $saludo_manager->createInstance('informal');
echo $plugin2->saludar('Carlos');
// Output: "Que onda, Carlos! Como estas?"
CODE;
echo PHP_EOL . PHP_EOL;

echo "=== 7. PLUGINS CON CONFIGURACION ===" . PHP_EOL;
echo PHP_EOL;
echo "Los plugins pueden recibir y manejar configuracion." . PHP_EOL;
echo <<<'CODE'
<?php

namespace Drupal\mi_modulo\Plugin\Saludo;

use Drupal\Component\Plugin\ConfigurableInterface;
use Drupal\Core\Plugin\PluginFormInterface;
use Drupal\Core\Form\FormStateInterface;

/**
 * @Saludo(
 *   id = "personalizado",
 *   label = @Translation("Saludo Personalizado"),
 *   description = @Translation("Saludo con prefijo configurable"),
 * )
 */
class SaludoPersonalizado extends SaludoBase implements ConfigurableInterface, PluginFormInterface {

  public function getConfiguration() {
    return $this->configuration + $this->defaultConfiguration();
  }

  public function setConfiguration(array $configuration) {
    $this->configuration = $configuration;
  }

  public function defaultConfiguration() {
    return ['prefijo' => 'Hola'];
  }

  public function buildConfigurationForm(array $form, FormStateInterface $form_state) {
    $form['prefijo'] = [
      '#type' => 'textfield',
      '#title' => 'Prefijo del saludo',
      '#default_value' => $this->getConfiguration()['prefijo'],
    ];
    return $form;
  }

  public function validateConfigurationForm(array &$form, FormStateInterface $form_state) {
    // Validacion custom si es necesario.
  }

  public function submitConfigurationForm(array &$form, FormStateInterface $form_state) {
    $this->configuration['prefijo'] = $form_state->getValue('prefijo');
  }

  public function saludar(string $nombre): string {
    $prefijo = $this->getConfiguration()['prefijo'];
    return "$prefijo, $nombre!";
  }

}
CODE;
echo PHP_EOL . PHP_EOL;

echo "=== 8. PLUGINS CON DEPENDENCY INJECTION ===" . PHP_EOL;
echo PHP_EOL;
echo <<<'CODE'
<?php

namespace Drupal\mi_modulo\Plugin\Saludo;

use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Datetime\DateFormatterInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * @Saludo(
 *   id = "con_fecha",
 *   label = @Translation("Saludo con Fecha"),
 *   description = @Translation("Saludo que incluye la fecha actual"),
 * )
 */
class SaludoConFecha extends SaludoBase implements ContainerFactoryPluginInterface {

  public function __construct(
    array $configuration,
    $plugin_id,
    $plugin_definition,
    protected DateFormatterInterface $dateFormatter,
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
      $container->get('date.formatter'),
    );
  }

  public function saludar(string $nombre): string {
    $fecha = $this->dateFormatter->format(time(), 'custom', 'd/m/Y');
    return "Hola $nombre! Hoy es $fecha.";
  }

}
CODE;
echo PHP_EOL . PHP_EOL;

echo "=== 9. PLUGINS DE DRUPAL CORE MAS IMPORTANTES ===" . PHP_EOL;
echo PHP_EOL;
echo "--- Field Formatter (como se MUESTRA un campo) ---" . PHP_EOL;
echo <<<'CODE'
<?php

namespace Drupal\mi_modulo\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FormatterBase;
use Drupal\Core\Field\FieldItemListInterface;

/**
 * @FieldFormatter(
 *   id = "mi_texto_destacado",
 *   label = @Translation("Texto Destacado"),
 *   field_types = {"string", "string_long"},
 * )
 */
class TextoDestacadoFormatter extends FormatterBase {

  public function viewElements(FieldItemListInterface $items, $langcode) {
    $elements = [];
    foreach ($items as $delta => $item) {
      $elements[$delta] = [
        '#type' => 'markup',
        '#markup' => '<div class="texto-destacado"><strong>'
          . $item->value . '</strong></div>',
      ];
    }
    return $elements;
  }

}
CODE;
echo PHP_EOL . PHP_EOL;

echo "--- Field Widget (como se EDITA un campo) ---" . PHP_EOL;
echo <<<'CODE'
<?php

namespace Drupal\mi_modulo\Plugin\Field\FieldWidget;

use Drupal\Core\Field\WidgetBase;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Form\FormStateInterface;

/**
 * @FieldWidget(
 *   id = "mi_texto_con_contador",
 *   label = @Translation("Texto con Contador"),
 *   field_types = {"string"},
 * )
 */
class TextoConContadorWidget extends WidgetBase {

  public function formElement(
    FieldItemListInterface $items,
    $delta,
    array $element,
    array &$form,
    FormStateInterface $form_state,
  ) {
    $element['value'] = [
      '#type' => 'textfield',
      '#title' => $element['#title'],
      '#default_value' => $items[$delta]->value ?? '',
      '#maxlength' => 255,
      '#description' => $this->t('Maximo 255 caracteres.'),
    ];
    return $element;
  }

}
CODE;
echo PHP_EOL . PHP_EOL;

echo "--- Condition Plugin (condiciones de visibilidad) ---" . PHP_EOL;
echo <<<'CODE'
<?php

namespace Drupal\mi_modulo\Plugin\Condition;

use Drupal\Core\Condition\ConditionPluginBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * @Condition(
 *   id = "dia_de_semana",
 *   label = @Translation("Dia de la semana"),
 * )
 */
class DiaDeSemana extends ConditionPluginBase {

  public function buildConfigurationForm(array $form, FormStateInterface $form_state) {
    $form['dias'] = [
      '#type' => 'checkboxes',
      '#title' => $this->t('Dias activos'),
      '#options' => [
        1 => $this->t('Lunes'),
        2 => $this->t('Martes'),
        3 => $this->t('Miercoles'),
        4 => $this->t('Jueves'),
        5 => $this->t('Viernes'),
        6 => $this->t('Sabado'),
        7 => $this->t('Domingo'),
      ],
      '#default_value' => $this->configuration['dias'] ?? [],
    ];
    return parent::buildConfigurationForm($form, $form_state);
  }

  public function submitConfigurationForm(array &$form, FormStateInterface $form_state) {
    $this->configuration['dias'] = $form_state->getValue('dias');
    parent::submitConfigurationForm($form, $form_state);
  }

  public function evaluate() {
    $dias = $this->configuration['dias'] ?? [];
    if (empty(array_filter($dias))) {
      return TRUE; // Sin restriccion si no hay dias seleccionados
    }
    $hoy = (int) date('N'); // 1=Lunes, 7=Domingo
    return !empty($dias[$hoy]);
  }

  public function summary() {
    return $this->t('Activo solo ciertos dias de la semana.');
  }

}
CODE;
echo PHP_EOL . PHP_EOL;

echo "=== 10. ALTERAR PLUGINS DE OTROS MODULOS ===" . PHP_EOL;
echo PHP_EOL;
echo <<<'CODE'
/**
 * Implements hook_saludo_info_alter().
 *
 * Este hook se genera por el alterInfo() en el manager.
 * Permite modificar las definiciones de plugins de tipo Saludo.
 */
function otro_modulo_saludo_info_alter(array &$definitions) {
  // Cambiar el label de un plugin existente
  if (isset($definitions['formal'])) {
    $definitions['formal']['label'] = t('Saludo muy formal');
  }

  // Eliminar un plugin
  unset($definitions['navidad']);
}
CODE;
echo PHP_EOL . PHP_EOL;

echo "=== RESUMEN ===" . PHP_EOL;
echo "1. Plugin = pieza de funcionalidad intercambiable con interfaz comun" . PHP_EOL;
echo "2. Plugin Manager = servicio que descubre e instancia plugins" . PHP_EOL;
echo "3. Descubrimiento: anotaciones (clasico), atributos PHP 8 (moderno), YAML" . PHP_EOL;
echo "4. Para crear un tipo de plugin: anotacion + interfaz + base + manager + servicio" . PHP_EOL;
echo "5. Plugins pueden tener configuracion (ConfigurableInterface)" . PHP_EOL;
echo "6. Plugins pueden inyectar servicios (ContainerFactoryPluginInterface)" . PHP_EOL;
echo "7. Tipos comunes: Block, FieldFormatter, FieldWidget, Condition, ImageEffect" . PHP_EOL;
echo "8. alterInfo() permite que otros modulos modifiquen definiciones de plugins" . PHP_EOL;
