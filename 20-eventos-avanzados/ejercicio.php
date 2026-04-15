<?php

/**
 * =============================================================
 *  EJERCICIO 20: EVENTOS AVANZADOS - SISTEMA DE NOTIFICACIONES
 * =============================================================
 */

echo "=== EJERCICIO: MODULO DE NOTIFICACIONES CON EVENTOS ===" . PHP_EOL;
echo PHP_EOL;

echo "Crea un modulo 'notificaciones' que use eventos custom para" . PHP_EOL;
echo "implementar un sistema de notificaciones desacoplado." . PHP_EOL;
echo "Cuando algo pasa (nuevo usuario, contenido publicado, etc.)," . PHP_EOL;
echo "se lanza un evento y los subscribers deciden como notificar." . PHP_EOL;
echo PHP_EOL;

echo "--- PARTE 1: Clase del Evento ---" . PHP_EOL;
echo PHP_EOL;

echo "1. Crear NotificacionEvent (src/Event/NotificacionEvent.php):" . PHP_EOL;
echo "   - Extiende Drupal\\Component\\EventDispatcher\\Event" . PHP_EOL;
echo "   - Constantes:" . PHP_EOL;
echo "     USUARIO_REGISTRADO = 'notificaciones.usuario_registrado'" . PHP_EOL;
echo "     CONTENIDO_PUBLICADO = 'notificaciones.contenido_publicado'" . PHP_EOL;
echo "     COMENTARIO_NUEVO = 'notificaciones.comentario_nuevo'" . PHP_EOL;
echo "   - Propiedades: tipo (string), mensaje (string), datos (array)" . PHP_EOL;
echo "   - Getters para todas las propiedades" . PHP_EOL;
echo "   - Setter para 'procesado' (bool) -> los subscribers lo marcan" . PHP_EOL;
echo PHP_EOL;

echo "--- PARTE 2: Servicio que despacha eventos ---" . PHP_EOL;
echo PHP_EOL;

echo "2. Crear NotificacionService (src/Service/NotificacionService.php):" . PHP_EOL;
echo "   - Inyecta event_dispatcher" . PHP_EOL;
echo "   - Metodo: notificarUsuarioRegistrado(\$username, \$email)" . PHP_EOL;
echo "     -> Crea NotificacionEvent y lo despacha con USUARIO_REGISTRADO" . PHP_EOL;
echo "   - Metodo: notificarContenidoPublicado(\$titulo, \$tipo, \$autor)" . PHP_EOL;
echo "     -> Crea NotificacionEvent y lo despacha con CONTENIDO_PUBLICADO" . PHP_EOL;
echo "   - Metodo: notificarComentarioNuevo(\$contenido, \$autor)" . PHP_EOL;
echo "     -> Crea NotificacionEvent y lo despacha con COMENTARIO_NUEVO" . PHP_EOL;
echo PHP_EOL;

echo "--- PARTE 3: Tres Subscribers con distintas prioridades ---" . PHP_EOL;
echo PHP_EOL;

echo "3. LogSubscriber (prioridad 200 - se ejecuta PRIMERO):" . PHP_EOL;
echo "   - Escucha los 3 eventos" . PHP_EOL;
echo "   - Logea cada notificacion con \\Drupal::logger()" . PHP_EOL;
echo "   - Ejemplo: 'Notificacion [tipo]: [mensaje]'" . PHP_EOL;
echo PHP_EOL;

echo "4. EmailSubscriber (prioridad 100 - se ejecuta SEGUNDO):" . PHP_EOL;
echo "   - Escucha USUARIO_REGISTRADO y CONTENIDO_PUBLICADO" . PHP_EOL;
echo "   - Simula envio de email (usa logger en vez de mail real)" . PHP_EOL;
echo "   - Si el evento ya fue procesado, no hace nada" . PHP_EOL;
echo PHP_EOL;

echo "5. SlackSubscriber (prioridad 50 - se ejecuta TERCERO):" . PHP_EOL;
echo "   - Escucha solo CONTENIDO_PUBLICADO" . PHP_EOL;
echo "   - Simula envio a Slack (usa logger)" . PHP_EOL;
echo "   - Marca el evento como procesado" . PHP_EOL;
echo PHP_EOL;

echo "--- PARTE 4: Subscriber con stopPropagation ---" . PHP_EOL;
echo PHP_EOL;

echo "6. FiltroSpamSubscriber (prioridad 999 - se ejecuta ANTES QUE TODOS):" . PHP_EOL;
echo "   - Escucha COMENTARIO_NUEVO" . PHP_EOL;
echo "   - Revisa si el mensaje contiene palabras prohibidas" . PHP_EOL;
echo "     (por ejemplo: 'spam', 'compra ya', 'gratis')" . PHP_EOL;
echo "   - Si detecta spam: logea 'Spam detectado', llama stopPropagation()" . PHP_EOL;
echo "     -> Los demas subscribers NO se ejecutan" . PHP_EOL;
echo "   - Si no es spam: no hace nada, deja que los demas procesen" . PHP_EOL;
echo PHP_EOL;

echo "--- PARTE 5: Controller para probar ---" . PHP_EOL;
echo PHP_EOL;

echo "7. Crear NotificacionesController con 3 rutas:" . PHP_EOL;
echo "   - /notificaciones/test-usuario" . PHP_EOL;
echo "     -> Llama notificarUsuarioRegistrado('testuser', 'test@mail.com')" . PHP_EOL;
echo "   - /notificaciones/test-contenido" . PHP_EOL;
echo "     -> Llama notificarContenidoPublicado('Mi Articulo', 'article', 'admin')" . PHP_EOL;
echo "   - /notificaciones/test-comentario/{mensaje}" . PHP_EOL;
echo "     -> Llama notificarComentarioNuevo(\$mensaje, 'anonimo')" . PHP_EOL;
echo "     -> Prueba con /test-comentario/hola y /test-comentario/compra-ya-gratis" . PHP_EOL;
echo PHP_EOL;

echo "--- PARTE 6: Conectar con hooks reales ---" . PHP_EOL;
echo PHP_EOL;

echo "8. En notificaciones.module, implementa:" . PHP_EOL;
echo "   - hook_user_insert(): cuando se registra un usuario real," . PHP_EOL;
echo "     llama a notificaciones.servicio->notificarUsuarioRegistrado()" . PHP_EOL;
echo "   - hook_entity_insert(): cuando se crea un nodo," . PHP_EOL;
echo "     llama a notificaciones.servicio->notificarContenidoPublicado()" . PHP_EOL;
echo PHP_EOL;

echo "--- Estructura esperada ---" . PHP_EOL;
echo "web/modules/custom/notificaciones/" . PHP_EOL;
echo "├── notificaciones.info.yml" . PHP_EOL;
echo "├── notificaciones.module" . PHP_EOL;
echo "├── notificaciones.routing.yml" . PHP_EOL;
echo "├── notificaciones.services.yml" . PHP_EOL;
echo "└── src/" . PHP_EOL;
echo "    ├── Controller/" . PHP_EOL;
echo "    │   └── NotificacionesController.php" . PHP_EOL;
echo "    ├── Event/" . PHP_EOL;
echo "    │   └── NotificacionEvent.php" . PHP_EOL;
echo "    ├── EventSubscriber/" . PHP_EOL;
echo "    │   ├── LogSubscriber.php" . PHP_EOL;
echo "    │   ├── EmailSubscriber.php" . PHP_EOL;
echo "    │   ├── SlackSubscriber.php" . PHP_EOL;
echo "    │   └── FiltroSpamSubscriber.php" . PHP_EOL;
echo "    └── Service/" . PHP_EOL;
echo "        └── NotificacionService.php" . PHP_EOL;
echo PHP_EOL;

echo "--- services.yml esperado ---" . PHP_EOL;
$services_ejemplo = <<<'YAML'
services:
  notificaciones.servicio:
    class: Drupal\notificaciones\Service\NotificacionService
    arguments: ['@event_dispatcher']

  notificaciones.log_subscriber:
    class: Drupal\notificaciones\EventSubscriber\LogSubscriber
    tags:
      - { name: event_subscriber }

  notificaciones.email_subscriber:
    class: Drupal\notificaciones\EventSubscriber\EmailSubscriber
    tags:
      - { name: event_subscriber }

  notificaciones.slack_subscriber:
    class: Drupal\notificaciones\EventSubscriber\SlackSubscriber
    tags:
      - { name: event_subscriber }

  notificaciones.filtro_spam_subscriber:
    class: Drupal\notificaciones\EventSubscriber\FiltroSpamSubscriber
    tags:
      - { name: event_subscriber }
YAML;
echo $services_ejemplo . PHP_EOL;
echo PHP_EOL;

echo "--- Para probar ---" . PHP_EOL;
echo "ddev drush en notificaciones -y && ddev drush cr" . PHP_EOL;
echo PHP_EOL;
echo "1. Visita /notificaciones/test-usuario" . PHP_EOL;
echo "   -> Revisa logs: ddev drush ws --type=notificaciones" . PHP_EOL;
echo "   -> Deberia haber log del LogSubscriber Y del EmailSubscriber" . PHP_EOL;
echo PHP_EOL;
echo "2. Visita /notificaciones/test-contenido" . PHP_EOL;
echo "   -> Deberia haber log, email Y slack" . PHP_EOL;
echo PHP_EOL;
echo "3. Visita /notificaciones/test-comentario/hola-amigos" . PHP_EOL;
echo "   -> No es spam: todos los subscribers procesan" . PHP_EOL;
echo PHP_EOL;
echo "4. Visita /notificaciones/test-comentario/compra-ya-gratis" . PHP_EOL;
echo "   -> ES spam: solo FiltroSpamSubscriber se ejecuta (stopPropagation)" . PHP_EOL;
echo "   -> Los demas NO aparecen en los logs" . PHP_EOL;
echo PHP_EOL;
echo "Pidele a Claude que revise tu sistema de eventos!" . PHP_EOL;
