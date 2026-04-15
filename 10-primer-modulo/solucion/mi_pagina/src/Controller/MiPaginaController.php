<?php

namespace Drupal\mi_pagina\Controller;

use Drupal\Core\Controller\ControllerBase;

class MiPaginaController extends ControllerBase {

  public function principal() {
    return [
      'presentacion' => [
        '#markup' => '<p>Bienvenido a mi pagina. Estoy aprendiendo Drupal!</p>',
      ],
      'lenguajes' => [
        '#theme' => 'item_list',
        '#title' => 'Mis lenguajes favoritos',
        '#items' => ['PHP', 'JavaScript', 'Python', 'Go', 'Rust'],
      ],
    ];
  }

  public function saludo(string $nombre) {
    $fecha = date('d/m/Y H:i:s');
    return [
      '#markup' => "<h2>Bienvenido, $nombre!</h2><p>Fecha actual: $fecha</p>",
    ];
  }

  public function about() {
    return [
      '#type' => 'table',
      '#header' => ['Campo', 'Valor'],
      '#rows' => [
        ['Nombre', 'Jesus'],
        ['Email', 'jesus@ejemplo.com'],
        ['Rol', 'Desarrollador Drupal Junior'],
      ],
    ];
  }

}
