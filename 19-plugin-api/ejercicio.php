<?php

/**
 * =============================================================
 *  EJERCICIO 19: PLUGIN API - SISTEMA DE SALUDOS
 * =============================================================
 */

echo "=== EJERCICIO: CREAR UN TIPO DE PLUGIN CUSTOM ===" . PHP_EOL;
echo PHP_EOL;

echo "Crea un modulo 'saludos' que defina un nuevo tipo de plugin" . PHP_EOL;
echo "llamado 'Saludo'. Cada plugin de saludo genera un mensaje" . PHP_EOL;
echo "diferente para saludar a un usuario." . PHP_EOL;
echo PHP_EOL;

echo "--- PARTE 1: Infraestructura del Plugin Type ---" . PHP_EOL;
echo PHP_EOL;

echo "1. Crear la anotacion (Annotation/Saludo.php):" . PHP_EOL;
echo "   - Propiedades: id, label, description" . PHP_EOL;
echo "   - Extiende de Drupal\\Component\\Annotation\\Plugin" . PHP_EOL;
echo PHP_EOL;

echo "2. Crear la interfaz (Plugin/Saludo/SaludoInterface.php):" . PHP_EOL;
echo "   - Metodo: saludar(string \$nombre): string" . PHP_EOL;
echo "   - Metodo: getLabel(): string" . PHP_EOL;
echo "   - Metodo: getDescription(): string" . PHP_EOL;
echo "   - Extiende PluginInspectionInterface" . PHP_EOL;
echo PHP_EOL;

echo "3. Crear la clase base (Plugin/Saludo/SaludoBase.php):" . PHP_EOL;
echo "   - Extiende PluginBase, implementa SaludoInterface" . PHP_EOL;
echo "   - Implementa getLabel() y getDescription() (leen de pluginDefinition)" . PHP_EOL;
echo "   - Deja saludar() como abstracto" . PHP_EOL;
echo PHP_EOL;

echo "4. Crear el Plugin Manager (Plugin/Saludo/SaludoManager.php):" . PHP_EOL;
echo "   - Extiende DefaultPluginManager" . PHP_EOL;
echo "   - Subdirectorio: 'Plugin/Saludo'" . PHP_EOL;
echo "   - alterInfo: 'saludo_info'" . PHP_EOL;
echo "   - Cache backend: 'saludo_plugins'" . PHP_EOL;
echo PHP_EOL;

echo "5. Registrar el manager en saludos.services.yml:" . PHP_EOL;
echo "   - Servicio: plugin.manager.saludo" . PHP_EOL;
echo "   - parent: default_plugin_manager" . PHP_EOL;
echo PHP_EOL;

echo "--- PARTE 2: Implementar 4 Plugins de Saludo ---" . PHP_EOL;
echo PHP_EOL;

echo "6. SaludoFormal (id: 'formal'):" . PHP_EOL;
echo "   - saludar('Juan') -> 'Estimado/a Juan, reciba un cordial saludo.'" . PHP_EOL;
echo PHP_EOL;

echo "7. SaludoInformal (id: 'informal'):" . PHP_EOL;
echo "   - saludar('Juan') -> 'Que tal, Juan! Como andas?'" . PHP_EOL;
echo PHP_EOL;

echo "8. SaludoConFecha (id: 'con_fecha'):" . PHP_EOL;
echo "   - Implementa ContainerFactoryPluginInterface" . PHP_EOL;
echo "   - Inyecta el servicio 'date.formatter'" . PHP_EOL;
echo "   - saludar('Juan') -> 'Buenos dias Juan! Hoy es 15/04/2026.'" . PHP_EOL;
echo PHP_EOL;

echo "9. SaludoConfigurable (id: 'configurable'):" . PHP_EOL;
echo "   - Implementa ConfigurableInterface" . PHP_EOL;
echo "   - Tiene una configuracion 'idioma' con default 'es'" . PHP_EOL;
echo "   - Si idioma es 'es': 'Hola, Juan!'" . PHP_EOL;
echo "   - Si idioma es 'en': 'Hello, Juan!'" . PHP_EOL;
echo "   - Si idioma es 'fr': 'Bonjour, Juan!'" . PHP_EOL;
echo PHP_EOL;

echo "--- PARTE 3: Controller para probar ---" . PHP_EOL;
echo PHP_EOL;

echo "10. Crear un Controller (SaludosController.php):" . PHP_EOL;
echo "    - Ruta: /saludos/{nombre}" . PHP_EOL;
echo "    - Inyecta plugin.manager.saludo" . PHP_EOL;
echo "    - Lista TODOS los plugins de saludo disponibles" . PHP_EOL;
echo "    - Para cada uno, llama saludar(\$nombre)" . PHP_EOL;
echo "    - Muestra los resultados en una tabla" . PHP_EOL;
echo PHP_EOL;

echo "--- Estructura esperada ---" . PHP_EOL;
echo "web/modules/custom/saludos/" . PHP_EOL;
echo "├── saludos.info.yml" . PHP_EOL;
echo "├── saludos.routing.yml" . PHP_EOL;
echo "├── saludos.services.yml" . PHP_EOL;
echo "└── src/" . PHP_EOL;
echo "    ├── Annotation/" . PHP_EOL;
echo "    │   └── Saludo.php" . PHP_EOL;
echo "    ├── Controller/" . PHP_EOL;
echo "    │   └── SaludosController.php" . PHP_EOL;
echo "    └── Plugin/" . PHP_EOL;
echo "        └── Saludo/" . PHP_EOL;
echo "            ├── SaludoInterface.php" . PHP_EOL;
echo "            ├── SaludoBase.php" . PHP_EOL;
echo "            ├── SaludoManager.php" . PHP_EOL;
echo "            ├── SaludoFormal.php" . PHP_EOL;
echo "            ├── SaludoInformal.php" . PHP_EOL;
echo "            ├── SaludoConFecha.php" . PHP_EOL;
echo "            └── SaludoConfigurable.php" . PHP_EOL;
echo PHP_EOL;

echo "--- BONUS ---" . PHP_EOL;
echo PHP_EOL;
echo "11. Crea un segundo modulo 'saludos_extra' que agregue un nuevo" . PHP_EOL;
echo "    plugin SaludoPirata (id: 'pirata'):" . PHP_EOL;
echo "    - saludar('Juan') -> 'Arrr! Bienvenido a bordo, Juan!'" . PHP_EOL;
echo "    - Esto demuestra que OTRO modulo puede agregar plugins" . PHP_EOL;
echo "      a tu tipo de plugin." . PHP_EOL;
echo PHP_EOL;

echo "12. En saludos_extra.module, implementa hook_saludo_info_alter()" . PHP_EOL;
echo "    para cambiar el label del plugin 'formal' a 'Saludo Super Formal'." . PHP_EOL;
echo PHP_EOL;

echo "--- Para probar ---" . PHP_EOL;
echo "ddev drush en saludos -y && ddev drush cr" . PHP_EOL;
echo "Visita: /saludos/Maria" . PHP_EOL;
echo "Deberias ver una tabla con todos los saludos para Maria." . PHP_EOL;
echo PHP_EOL;
echo "Pidele a Claude que revise tu sistema de plugins!" . PHP_EOL;
