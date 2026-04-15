<?php

/**
 * =============================================================
 *  LECCION 13: FORM API
 * =============================================================
 *  Drupal tiene su propio sistema de formularios: Form API.
 *  Los forms se construyen como arrays PHP (render arrays).
 * =============================================================
 */

echo "=== 1. TIPOS DE FORMULARIOS ===" . PHP_EOL;
echo PHP_EOL;
echo "Drupal tiene 3 clases base para formularios:" . PHP_EOL;
echo "  FormBase        -> formulario generico" . PHP_EOL;
echo "  ConfigForm      -> formulario de configuracion del modulo" . PHP_EOL;
echo "  ConfirmFormBase -> formulario de confirmacion (borrar, etc.)" . PHP_EOL;
echo PHP_EOL;

echo "=== 2. FORMULARIO BASICO (FormBase) ===" . PHP_EOL;
echo PHP_EOL;
echo <<<'CODE'
<?php
// src/Form/ContactoForm.php

namespace Drupal\mi_modulo\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

class ContactoForm extends FormBase {

  // ID unico del formulario
  public function getFormId() {
    return 'mi_modulo_contacto_form';
  }

  // Construir el formulario (los campos)
  public function buildForm(array $form, FormStateInterface $form_state) {

    $form['nombre'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Nombre'),
      '#required' => TRUE,
      '#maxlength' => 100,
      '#placeholder' => 'Tu nombre completo',
    ];

    $form['email'] = [
      '#type' => 'email',
      '#title' => $this->t('Email'),
      '#required' => TRUE,
    ];

    $form['asunto'] = [
      '#type' => 'select',
      '#title' => $this->t('Asunto'),
      '#options' => [
        'consulta' => $this->t('Consulta general'),
        'soporte' => $this->t('Soporte tecnico'),
        'sugerencia' => $this->t('Sugerencia'),
      ],
      '#required' => TRUE,
    ];

    $form['mensaje'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Mensaje'),
      '#required' => TRUE,
      '#rows' => 5,
    ];

    $form['acepto'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Acepto los terminos'),
      '#required' => TRUE,
    ];

    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Enviar'),
    ];

    return $form;
  }

  // Validar datos
  public function validateForm(array &$form, FormStateInterface $form_state) {
    $email = $form_state->getValue('email');
    if (!str_contains($email, '@')) {
      $form_state->setErrorByName('email', $this->t('Email no valido.'));
    }

    $nombre = $form_state->getValue('nombre');
    if (strlen($nombre) < 3) {
      $form_state->setErrorByName('nombre', $this->t('El nombre debe tener al menos 3 caracteres.'));
    }
  }

  // Procesar el envio
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $nombre = $form_state->getValue('nombre');
    $email = $form_state->getValue('email');

    // Mostrar mensaje al usuario
    $this->messenger()->addStatus(
      $this->t('Gracias @nombre! Te responderemos en @email.', [
        '@nombre' => $nombre,
        '@email' => $email,
      ])
    );

    // Redirigir despues del envio
    $form_state->setRedirect('<front>');
  }

}
CODE;
echo PHP_EOL . PHP_EOL;


echo "=== 3. REGISTRAR LA RUTA DEL FORMULARIO ===" . PHP_EOL;
echo PHP_EOL;
$ruta_form = <<<'YAML'
# mi_modulo.routing.yml
mi_modulo.contacto:
  path: '/contacto'
  defaults:
    _form: '\Drupal\mi_modulo\Form\ContactoForm'
    _title: 'Contacto'
  requirements:
    _permission: 'access content'
YAML;
echo $ruta_form . PHP_EOL;
echo PHP_EOL;
echo "Nota: usa _form en vez de _controller para formularios." . PHP_EOL;
echo PHP_EOL;


echo "=== 4. TIPOS DE CAMPOS DISPONIBLES ===" . PHP_EOL;
echo PHP_EOL;
echo "Campos de texto:" . PHP_EOL;
echo "  textfield    -> input text" . PHP_EOL;
echo "  textarea     -> textarea" . PHP_EOL;
echo "  email        -> input email" . PHP_EOL;
echo "  password     -> input password" . PHP_EOL;
echo "  number       -> input number" . PHP_EOL;
echo "  tel          -> input telefono" . PHP_EOL;
echo "  url          -> input URL" . PHP_EOL;
echo "  hidden       -> campo oculto" . PHP_EOL;
echo PHP_EOL;
echo "Seleccion:" . PHP_EOL;
echo "  select       -> desplegable" . PHP_EOL;
echo "  checkboxes   -> grupo de checkboxes" . PHP_EOL;
echo "  radios       -> grupo de radio buttons" . PHP_EOL;
echo "  checkbox     -> checkbox individual" . PHP_EOL;
echo PHP_EOL;
echo "Archivos:" . PHP_EOL;
echo "  file         -> subir archivo" . PHP_EOL;
echo "  managed_file -> archivo gestionado por Drupal" . PHP_EOL;
echo PHP_EOL;
echo "Especiales:" . PHP_EOL;
echo "  date         -> selector de fecha" . PHP_EOL;
echo "  datetime     -> fecha y hora" . PHP_EOL;
echo "  color        -> selector de color" . PHP_EOL;
echo "  range        -> slider" . PHP_EOL;
echo "  entity_autocomplete -> buscar entidades" . PHP_EOL;
echo PHP_EOL;
echo "Estructura:" . PHP_EOL;
echo "  fieldset     -> agrupar campos" . PHP_EOL;
echo "  details      -> fieldset colapsable" . PHP_EOL;
echo "  container    -> div contenedor" . PHP_EOL;
echo PHP_EOL;


echo "=== 5. PROPIEDADES COMUNES DE CAMPOS ===" . PHP_EOL;
echo PHP_EOL;
echo "#type          -> tipo de campo" . PHP_EOL;
echo "#title         -> etiqueta" . PHP_EOL;
echo "#description   -> texto de ayuda" . PHP_EOL;
echo "#required      -> obligatorio (TRUE/FALSE)" . PHP_EOL;
echo "#default_value -> valor por defecto" . PHP_EOL;
echo "#placeholder   -> texto placeholder" . PHP_EOL;
echo "#maxlength     -> longitud maxima (textfield)" . PHP_EOL;
echo "#min / #max    -> minimo/maximo (number)" . PHP_EOL;
echo "#options       -> opciones (select, radios, checkboxes)" . PHP_EOL;
echo "#empty_option  -> opcion vacia en select ('- Selecciona -')" . PHP_EOL;
echo "#attributes    -> atributos HTML extra" . PHP_EOL;
echo "#prefix/#suffix-> HTML antes/despues del campo" . PHP_EOL;
echo "#access        -> mostrar/ocultar (TRUE/FALSE)" . PHP_EOL;
echo "#weight        -> orden (-10 arriba, 10 abajo)" . PHP_EOL;
echo PHP_EOL;


echo "=== 6. FORMULARIO DE CONFIGURACION (ConfigForm) ===" . PHP_EOL;
echo PHP_EOL;
echo <<<'CODE'
<?php
// src/Form/MiModuloSettingsForm.php

namespace Drupal\mi_modulo\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

class MiModuloSettingsForm extends ConfigFormBase {

  public function getFormId() {
    return 'mi_modulo_settings';
  }

  // Nombre de la configuracion que guarda/lee
  protected function getEditableConfigNames() {
    return ['mi_modulo.settings'];
  }

  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('mi_modulo.settings');

    $form['nombre_sitio'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Nombre del sitio'),
      '#default_value' => $config->get('nombre_sitio') ?? '',
    ];

    $form['items_por_pagina'] = [
      '#type' => 'number',
      '#title' => $this->t('Items por pagina'),
      '#default_value' => $config->get('items_por_pagina') ?? 10,
      '#min' => 1,
      '#max' => 100,
    ];

    return parent::buildForm($form, $form_state);
  }

  public function submitForm(array &$form, FormStateInterface $form_state) {
    $this->config('mi_modulo.settings')
      ->set('nombre_sitio', $form_state->getValue('nombre_sitio'))
      ->set('items_por_pagina', $form_state->getValue('items_por_pagina'))
      ->save();

    parent::submitForm($form, $form_state);
  }

}
CODE;
echo PHP_EOL . PHP_EOL;

echo "Ruta para ConfigForm (convencion: admin/config/...):" . PHP_EOL;
$ruta_config = <<<'YAML'
mi_modulo.settings:
  path: '/admin/config/mi-modulo/settings'
  defaults:
    _form: '\Drupal\mi_modulo\Form\MiModuloSettingsForm'
    _title: 'Configuracion de Mi Modulo'
  requirements:
    _permission: 'administer site configuration'
YAML;
echo $ruta_config . PHP_EOL;
echo PHP_EOL;


echo "=== 7. AJAX EN FORMULARIOS ===" . PHP_EOL;
echo PHP_EOL;
echo "Puedes hacer que un campo actualice parte del form sin recargar:" . PHP_EOL;
echo <<<'CODE'

$form['pais'] = [
  '#type' => 'select',
  '#title' => 'Pais',
  '#options' => ['es' => 'Espana', 'mx' => 'Mexico'],
  '#ajax' => [
    'callback' => '::actualizarCiudades',
    'wrapper' => 'ciudades-wrapper',
  ],
];

$form['ciudades_container'] = [
  '#type' => 'container',
  '#attributes' => ['id' => 'ciudades-wrapper'],
];

$pais = $form_state->getValue('pais');
$ciudades = $this->getCiudadesPorPais($pais);

$form['ciudades_container']['ciudad'] = [
  '#type' => 'select',
  '#title' => 'Ciudad',
  '#options' => $ciudades,
];

// Callback AJAX
public function actualizarCiudades(array &$form, FormStateInterface $form_state) {
  return $form['ciudades_container'];
}
CODE;
echo PHP_EOL . PHP_EOL;


echo "=== RESUMEN ===" . PHP_EOL;
echo "1. FormBase: formulario generico (buildForm, validateForm, submitForm)" . PHP_EOL;
echo "2. ConfigFormBase: guarda config persistente" . PHP_EOL;
echo "3. Ruta: usa _form en vez de _controller" . PHP_EOL;
echo "4. Campos: textfield, select, checkbox, textarea, email, file..." . PHP_EOL;
echo "5. FormStateInterface: getValue(), setErrorByName(), setRedirect()" . PHP_EOL;
echo "6. AJAX: #ajax callback para actualizaciones parciales" . PHP_EOL;
