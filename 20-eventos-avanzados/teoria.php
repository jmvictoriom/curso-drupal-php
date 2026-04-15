<?php

/**
 * =============================================================
 *  LECCION 20: EVENTOS AVANZADOS
 * =============================================================
 *  Mas alla de lo basico: crear eventos custom, despacharlos,
 *  controlar prioridades, detener propagacion y dominar
 *  los KernelEvents de Symfony/Drupal.
 * =============================================================
 */

echo "=== 1. REPASO: COMO FUNCIONA EL SISTEMA DE EVENTOS ===" . PHP_EOL;
echo PHP_EOL;
echo "El sistema de eventos de Drupal usa el EventDispatcher de Symfony." . PHP_EOL;
echo "Hay tres piezas:" . PHP_EOL;
echo "  1. Evento: objeto que contiene datos del suceso" . PHP_EOL;
echo "  2. Dispatcher: servicio que lanza el evento" . PHP_EOL;
echo "  3. Subscriber: clase que escucha y reacciona al evento" . PHP_EOL;
echo PHP_EOL;
echo "Flujo: algo ocurre -> se crea un objeto Event -> el dispatcher" . PHP_EOL;
echo "notifica a todos los subscribers registrados -> cada subscriber" . PHP_EOL;
echo "ejecuta su logica." . PHP_EOL;
echo PHP_EOL;

echo "=== 2. KERNEL EVENTS EN PROFUNDIDAD ===" . PHP_EOL;
echo PHP_EOL;
echo "Symfony define eventos clave en el ciclo de vida HTTP:" . PHP_EOL;
echo PHP_EOL;
echo "  KernelEvents::REQUEST      -> al recibir una peticion" . PHP_EOL;
echo "  KernelEvents::CONTROLLER   -> antes de ejecutar el controller" . PHP_EOL;
echo "  KernelEvents::VIEW         -> si el controller no devuelve Response" . PHP_EOL;
echo "  KernelEvents::RESPONSE     -> antes de enviar la respuesta" . PHP_EOL;
echo "  KernelEvents::FINISH_REQUEST -> al terminar de procesar" . PHP_EOL;
echo "  KernelEvents::TERMINATE    -> despues de enviar la respuesta" . PHP_EOL;
echo "  KernelEvents::EXCEPTION    -> cuando ocurre una excepcion" . PHP_EOL;
echo PHP_EOL;

echo "--- Ejemplo: Redirigir en KernelEvents::REQUEST ---" . PHP_EOL;
echo <<<'CODE'
<?php
// src/EventSubscriber/RedirectSubscriber.php

namespace Drupal\mi_modulo\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class RedirectSubscriber implements EventSubscriberInterface {

  public static function getSubscribedEvents() {
    return [
      // Prioridad alta (se ejecuta antes que otros subscribers)
      KernelEvents::REQUEST => ['onRequest', 300],
    ];
  }

  public function onRequest(RequestEvent $event) {
    $request = $event->getRequest();
    $path = $request->getPathInfo();

    // Redirigir /vieja-pagina a /nueva-pagina
    if ($path === '/vieja-pagina') {
      $response = new RedirectResponse('/nueva-pagina', 301);
      $event->setResponse($response);
      // Al setear Response, Drupal ya no ejecuta el controller
    }
  }

}
CODE;
echo PHP_EOL . PHP_EOL;

echo "--- Ejemplo: Modificar la respuesta en KernelEvents::RESPONSE ---" . PHP_EOL;
echo <<<'CODE'
<?php

namespace Drupal\mi_modulo\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class ResponseSubscriber implements EventSubscriberInterface {

  public static function getSubscribedEvents() {
    return [
      KernelEvents::RESPONSE => ['onResponse', 0],
    ];
  }

  public function onResponse(ResponseEvent $event) {
    $response = $event->getResponse();

    // Agregar headers custom
    $response->headers->set('X-Mi-Modulo', 'activo');

    // Agregar header de seguridad
    $response->headers->set('X-Content-Type-Options', 'nosniff');
  }

}
CODE;
echo PHP_EOL . PHP_EOL;

echo "--- Ejemplo: Manejar excepciones ---" . PHP_EOL;
echo <<<'CODE'
<?php

namespace Drupal\mi_modulo\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class ExceptionSubscriber implements EventSubscriberInterface {

  public static function getSubscribedEvents() {
    return [
      KernelEvents::EXCEPTION => ['onException', 50],
    ];
  }

  public function onException(ExceptionEvent $event) {
    $exception = $event->getThrowable();

    // Logear la excepcion
    \Drupal::logger('mi_modulo')->error(
      'Excepcion capturada: @message',
      ['@message' => $exception->getMessage()]
    );

    // Opcionalmente, devolver una respuesta custom
    if ($exception->getCode() === 403) {
      $response = new Response(
        '<h1>Acceso Denegado</h1><p>No tienes permiso.</p>',
        403
      );
      $event->setResponse($response);
    }
  }

}
CODE;
echo PHP_EOL . PHP_EOL;

echo "=== 3. PRIORIDADES DE EVENTOS ===" . PHP_EOL;
echo PHP_EOL;
echo "La prioridad determina el ORDEN de ejecucion." . PHP_EOL;
echo "  - Mayor numero = se ejecuta PRIMERO" . PHP_EOL;
echo "  - Menor numero = se ejecuta DESPUES" . PHP_EOL;
echo "  - Default = 0" . PHP_EOL;
echo PHP_EOL;
echo <<<'CODE'
public static function getSubscribedEvents() {
  return [
    // Se ejecuta MUY temprano (antes que casi todos)
    KernelEvents::REQUEST => ['onRequestEarly', 1000],
  ];
}

// Tambien puedes suscribirte MULTIPLES VECES al mismo evento:
public static function getSubscribedEvents() {
  return [
    KernelEvents::REQUEST => [
      ['primerMetodo', 100],   // se ejecuta primero
      ['segundoMetodo', 50],   // se ejecuta segundo
      ['tercerMetodo', -10],   // se ejecuta al final
    ],
  ];
}
CODE;
echo PHP_EOL . PHP_EOL;
echo "Prioridades tipicas en Drupal:" . PHP_EOL;
echo "  1000+ : Seguridad, autenticacion" . PHP_EOL;
echo "  300   : Redireccionamientos" . PHP_EOL;
echo "  200   : Negociacion de formato" . PHP_EOL;
echo "  0     : Logica general (default)" . PHP_EOL;
echo "  -100  : Procesamiento final" . PHP_EOL;
echo PHP_EOL;

echo "=== 4. DETENER LA PROPAGACION ===" . PHP_EOL;
echo PHP_EOL;
echo "Un subscriber puede impedir que los demas se ejecuten." . PHP_EOL;
echo <<<'CODE'
public function onRequest(RequestEvent $event) {
  $path = $event->getRequest()->getPathInfo();

  if ($path === '/mantenimiento') {
    $response = new Response('<h1>Sitio en mantenimiento</h1>', 503);
    $event->setResponse($response);

    // Detener: ningun otro subscriber de este evento se ejecutara
    $event->stopPropagation();
  }
}

// Para verificar si se detuvo:
$event->isPropagationStopped(); // true o false
CODE;
echo PHP_EOL . PHP_EOL;
echo "CUIDADO: stopPropagation() es muy agresivo. Usalo solo cuando" . PHP_EOL;
echo "realmente necesites que nadie mas procese el evento." . PHP_EOL;
echo PHP_EOL;

echo "=== 5. CREAR EVENTOS CUSTOM ===" . PHP_EOL;
echo PHP_EOL;
echo "Puedes crear tus propios eventos para que otros modulos" . PHP_EOL;
echo "reaccionen a cosas que pasan en tu modulo." . PHP_EOL;
echo PHP_EOL;

echo "--- Paso 1: Definir la clase del Evento ---" . PHP_EOL;
echo <<<'CODE'
<?php
// src/Event/PedidoEvent.php

namespace Drupal\mi_tienda\Event;

use Drupal\Component\EventDispatcher\Event;

/**
 * Evento que se lanza cuando ocurre algo con un pedido.
 */
class PedidoEvent extends Event {

  /**
   * Nombre del evento cuando se crea un pedido.
   */
  const PEDIDO_CREADO = 'mi_tienda.pedido.creado';

  /**
   * Nombre del evento cuando se cancela un pedido.
   */
  const PEDIDO_CANCELADO = 'mi_tienda.pedido.cancelado';

  /**
   * Nombre del evento cuando se completa un pedido.
   */
  const PEDIDO_COMPLETADO = 'mi_tienda.pedido.completado';

  /**
   * El ID del pedido.
   */
  protected int $pedidoId;

  /**
   * El total del pedido.
   */
  protected float $total;

  /**
   * Mensaje opcional que los subscribers pueden modificar.
   */
  protected string $mensaje = '';

  public function __construct(int $pedidoId, float $total) {
    $this->pedidoId = $pedidoId;
    $this->total = $total;
  }

  public function getPedidoId(): int {
    return $this->pedidoId;
  }

  public function getTotal(): float {
    return $this->total;
  }

  public function getMensaje(): string {
    return $this->mensaje;
  }

  /**
   * Permite que los subscribers modifiquen el mensaje.
   */
  public function setMensaje(string $mensaje): void {
    $this->mensaje = $mensaje;
  }

}
CODE;
echo PHP_EOL . PHP_EOL;
echo "Nota: El evento extiende Drupal\\Component\\EventDispatcher\\Event" . PHP_EOL;
echo "(que es compatible con Symfony\\Contracts\\EventDispatcher\\Event)." . PHP_EOL;
echo PHP_EOL;

echo "--- Paso 2: Despachar el evento ---" . PHP_EOL;
echo <<<'CODE'
<?php
// src/Service/PedidoService.php

namespace Drupal\mi_tienda\Service;

use Drupal\mi_tienda\Event\PedidoEvent;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

class PedidoService {

  public function __construct(
    protected EventDispatcherInterface $eventDispatcher,
  ) {}

  public function crearPedido(array $datos): int {
    // ... logica para crear el pedido en la base de datos ...
    $pedido_id = 42; // ID generado
    $total = $datos['total'];

    // Crear y despachar el evento
    $event = new PedidoEvent($pedido_id, $total);
    $this->eventDispatcher->dispatch($event, PedidoEvent::PEDIDO_CREADO);

    // Podemos leer datos que los subscribers hayan modificado
    $mensaje = $event->getMensaje();
    if ($mensaje) {
      \Drupal::messenger()->addMessage($mensaje);
    }

    return $pedido_id;
  }

  public function cancelarPedido(int $pedido_id, float $total): void {
    // ... logica para cancelar ...

    $event = new PedidoEvent($pedido_id, $total);
    $this->eventDispatcher->dispatch($event, PedidoEvent::PEDIDO_CANCELADO);
  }

}
CODE;
echo PHP_EOL . PHP_EOL;

echo "--- Paso 3: Registrar PedidoService como servicio ---" . PHP_EOL;
$services_tienda = <<<'YAML'
# mi_tienda.services.yml
services:
  mi_tienda.pedido_service:
    class: Drupal\mi_tienda\Service\PedidoService
    arguments: ['@event_dispatcher']
YAML;
echo $services_tienda . PHP_EOL;
echo PHP_EOL;

echo "--- Paso 4: Crear subscribers que reaccionen ---" . PHP_EOL;
echo <<<'CODE'
<?php
// src/EventSubscriber/PedidoEmailSubscriber.php

namespace Drupal\mi_tienda\EventSubscriber;

use Drupal\mi_tienda\Event\PedidoEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Envia email cuando se crea un pedido.
 */
class PedidoEmailSubscriber implements EventSubscriberInterface {

  public static function getSubscribedEvents() {
    return [
      PedidoEvent::PEDIDO_CREADO => ['enviarConfirmacion', 100],
      PedidoEvent::PEDIDO_CANCELADO => ['enviarCancelacion', 100],
    ];
  }

  public function enviarConfirmacion(PedidoEvent $event) {
    $id = $event->getPedidoId();
    $total = $event->getTotal();

    // Enviar email de confirmacion
    \Drupal::logger('mi_tienda')->info(
      'Email de confirmacion enviado para pedido #@id (total: @total)',
      ['@id' => $id, '@total' => $total]
    );
  }

  public function enviarCancelacion(PedidoEvent $event) {
    \Drupal::logger('mi_tienda')->info(
      'Email de cancelacion enviado para pedido #@id',
      ['@id' => $event->getPedidoId()]
    );
  }

}
CODE;
echo PHP_EOL . PHP_EOL;

echo <<<'CODE'
<?php
// src/EventSubscriber/PedidoInventarioSubscriber.php

namespace Drupal\mi_tienda\EventSubscriber;

use Drupal\mi_tienda\Event\PedidoEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Actualiza inventario cuando cambia un pedido.
 */
class PedidoInventarioSubscriber implements EventSubscriberInterface {

  public static function getSubscribedEvents() {
    return [
      PedidoEvent::PEDIDO_CREADO => ['reducirInventario', 50],
      PedidoEvent::PEDIDO_CANCELADO => ['restaurarInventario', 50],
    ];
  }

  public function reducirInventario(PedidoEvent $event) {
    \Drupal::logger('mi_tienda')->info(
      'Inventario reducido para pedido #@id',
      ['@id' => $event->getPedidoId()]
    );
  }

  public function restaurarInventario(PedidoEvent $event) {
    \Drupal::logger('mi_tienda')->info(
      'Inventario restaurado para pedido #@id',
      ['@id' => $event->getPedidoId()]
    );
  }

}
CODE;
echo PHP_EOL . PHP_EOL;

echo <<<'CODE'
<?php
// src/EventSubscriber/PedidoMensajeSubscriber.php

namespace Drupal\mi_tienda\EventSubscriber;

use Drupal\mi_tienda\Event\PedidoEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Agrega un mensaje al evento (demuestra comunicacion bidireccional).
 */
class PedidoMensajeSubscriber implements EventSubscriberInterface {

  public static function getSubscribedEvents() {
    return [
      // Prioridad MAYOR que el de email -> se ejecuta antes
      PedidoEvent::PEDIDO_CREADO => ['agregarMensaje', 200],
    ];
  }

  public function agregarMensaje(PedidoEvent $event) {
    $event->setMensaje(
      'Gracias! Tu pedido #' . $event->getPedidoId() . ' ha sido creado.'
    );
  }

}
CODE;
echo PHP_EOL . PHP_EOL;

echo "--- Registrar todos los subscribers ---" . PHP_EOL;
$services_subscribers = <<<'YAML'
# Agregar a mi_tienda.services.yml
services:
  mi_tienda.pedido_service:
    class: Drupal\mi_tienda\Service\PedidoService
    arguments: ['@event_dispatcher']

  mi_tienda.pedido_email_subscriber:
    class: Drupal\mi_tienda\EventSubscriber\PedidoEmailSubscriber
    tags:
      - { name: event_subscriber }

  mi_tienda.pedido_inventario_subscriber:
    class: Drupal\mi_tienda\EventSubscriber\PedidoInventarioSubscriber
    tags:
      - { name: event_subscriber }

  mi_tienda.pedido_mensaje_subscriber:
    class: Drupal\mi_tienda\EventSubscriber\PedidoMensajeSubscriber
    tags:
      - { name: event_subscriber }
YAML;
echo $services_subscribers . PHP_EOL;
echo PHP_EOL;

echo "=== 6. ORDEN DE EJECUCION DEL EJEMPLO ===" . PHP_EOL;
echo PHP_EOL;
echo "Cuando se crea un pedido, los subscribers se ejecutan asi:" . PHP_EOL;
echo PHP_EOL;
echo "  1. PedidoMensajeSubscriber::agregarMensaje     (prioridad 200)" . PHP_EOL;
echo "  2. PedidoEmailSubscriber::enviarConfirmacion    (prioridad 100)" . PHP_EOL;
echo "  3. PedidoInventarioSubscriber::reducirInventario (prioridad 50)" . PHP_EOL;
echo PHP_EOL;
echo "El mensaje se agrega ANTES de enviar el email, porque tiene" . PHP_EOL;
echo "mayor prioridad. Esto demuestra la coordinacion entre subscribers." . PHP_EOL;
echo PHP_EOL;

echo "=== 7. EVENTO CON STOPPAGE CONDICIONAL ===" . PHP_EOL;
echo PHP_EOL;
echo <<<'CODE'
<?php
// Ejemplo: Validar pedido y detener si es fraude

namespace Drupal\mi_tienda\EventSubscriber;

use Drupal\mi_tienda\Event\PedidoEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class PedidoFraudeSubscriber implements EventSubscriberInterface {

  public static function getSubscribedEvents() {
    return [
      // Prioridad MUY ALTA: se ejecuta antes que todos
      PedidoEvent::PEDIDO_CREADO => ['verificarFraude', 999],
    ];
  }

  public function verificarFraude(PedidoEvent $event) {
    $total = $event->getTotal();

    if ($total > 10000) {
      \Drupal::logger('mi_tienda')->warning(
        'Pedido #@id bloqueado por sospecha de fraude (total: @total)',
        ['@id' => $event->getPedidoId(), '@total' => $total]
      );

      // DETENER: ningun otro subscriber procesara este evento
      $event->stopPropagation();

      // Marcar el evento para que el servicio sepa que fue bloqueado
      $event->setMensaje('Pedido en revision por seguridad.');
    }
  }

}
CODE;
echo PHP_EOL . PHP_EOL;

echo "=== 8. EVENTOS PROPIOS DE DRUPAL ===" . PHP_EOL;
echo PHP_EOL;
echo "Ademas de KernelEvents, Drupal tiene eventos propios:" . PHP_EOL;
echo PHP_EOL;
echo <<<'CODE'
use Drupal\Core\Config\ConfigEvents;
use Drupal\Core\Routing\RoutingEvents;
use Drupal\Core\Entity\EntityTypeEvents;

// Evento: cuando cambia una configuracion
public static function getSubscribedEvents() {
  return [
    ConfigEvents::SAVE => ['onConfigSave', 0],
  ];
}

public function onConfigSave(ConfigCrudEvent $event) {
  $config = $event->getConfig();
  $nombre = $config->getName();
  \Drupal::logger('mi_modulo')->info('Config modificada: @name', ['@name' => $nombre]);
}

// Evento: cuando se reconstruyen las rutas
public static function getSubscribedEvents() {
  return [
    RoutingEvents::ALTER => ['onRouteAlter', 0],
  ];
}
CODE;
echo PHP_EOL . PHP_EOL;

echo "=== 9. PATRON: EVENTO + PLUGIN ===" . PHP_EOL;
echo PHP_EOL;
echo "Combinar eventos con plugins es un patron poderoso." . PHP_EOL;
echo "El evento se lanza, y un subscriber usa un plugin manager" . PHP_EOL;
echo "para ejecutar logica dinamica." . PHP_EOL;
echo PHP_EOL;
echo <<<'CODE'
class NotificacionSubscriber implements EventSubscriberInterface {

  public function __construct(
    protected NotificacionManagerInterface $notificacionManager,
  ) {}

  public static function getSubscribedEvents() {
    return [
      PedidoEvent::PEDIDO_CREADO => ['notificar', 0],
    ];
  }

  public function notificar(PedidoEvent $event) {
    // Obtener todos los plugins de notificacion activos
    $plugins = $this->notificacionManager->getDefinitions();

    foreach ($plugins as $id => $definition) {
      $notificador = $this->notificacionManager->createInstance($id);
      $notificador->enviar(
        'Nuevo pedido #' . $event->getPedidoId(),
        'Total: ' . $event->getTotal()
      );
    }
  }

}
CODE;
echo PHP_EOL . PHP_EOL;

echo "=== RESUMEN ===" . PHP_EOL;
echo "1. KernelEvents: REQUEST, RESPONSE, EXCEPTION, TERMINATE, etc." . PHP_EOL;
echo "2. Prioridad: numero mayor = se ejecuta primero (default 0)" . PHP_EOL;
echo "3. stopPropagation(): detiene la ejecucion de los demas subscribers" . PHP_EOL;
echo "4. Eventos custom: crear clase Event + constantes + dispatch()" . PHP_EOL;
echo "5. Despachar: \$eventDispatcher->dispatch(\$event, NombreEvento::CONSTANTE)" . PHP_EOL;
echo "6. Comunicacion bidireccional: el evento puede ser modificado por subscribers" . PHP_EOL;
echo "7. Multiples subscribers por evento con diferentes prioridades" . PHP_EOL;
echo "8. ConfigEvents::SAVE para reaccionar a cambios de configuracion" . PHP_EOL;
echo "9. Combinar eventos + plugins para logica extensible" . PHP_EOL;
