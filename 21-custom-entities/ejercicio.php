<?php

/**
 * =============================================================
 *  EJERCICIO 21: CREAR UNA ENTIDAD CUSTOM "PROYECTO"
 * =============================================================
 */

echo "=== EJERCICIO: ENTIDAD CUSTOM 'PROYECTO' ===" . PHP_EOL;
echo PHP_EOL;

echo "Crea un modulo 'proyectos' con una entidad de contenido custom" . PHP_EOL;
echo "llamada 'Proyecto' para gestionar proyectos de una empresa." . PHP_EOL;
echo PHP_EOL;

echo "--- PARTE 1: La entidad Proyecto ---" . PHP_EOL;
echo PHP_EOL;

echo "1. Crear la clase Proyecto (src/Entity/Proyecto.php):" . PHP_EOL;
echo "   - Anotacion @ContentEntityType con:" . PHP_EOL;
echo "     - id = 'proyecto'" . PHP_EOL;
echo "     - base_table = 'proyecto'" . PHP_EOL;
echo "     - entity_keys: id, label (titulo), uuid, owner (uid)" . PHP_EOL;
echo "     - handlers para: list_builder, form (add/edit/delete), access, routes" . PHP_EOL;
echo "     - links para: canonical, add-form, edit-form, delete-form, collection" . PHP_EOL;
echo "   - Usa EntityChangedTrait y EntityOwnerTrait" . PHP_EOL;
echo PHP_EOL;

echo "2. Base fields del Proyecto:" . PHP_EOL;
echo "   a) titulo (string, requerido, max 255)" . PHP_EOL;
echo "      -> Es el label de la entidad" . PHP_EOL;
echo "   b) descripcion (text_long)" . PHP_EOL;
echo "      -> Descripcion detallada del proyecto" . PHP_EOL;
echo "   c) cliente (string, requerido, max 255)" . PHP_EOL;
echo "      -> Nombre del cliente" . PHP_EOL;
echo "   d) estado (list_string) con opciones:" . PHP_EOL;
echo "      -> 'pendiente' = Pendiente" . PHP_EOL;
echo "      -> 'en_progreso' = En Progreso" . PHP_EOL;
echo "      -> 'completado' = Completado" . PHP_EOL;
echo "      -> 'cancelado' = Cancelado" . PHP_EOL;
echo "      -> Default: 'pendiente'" . PHP_EOL;
echo "   e) presupuesto (decimal, precision 10, scale 2)" . PHP_EOL;
echo "      -> Presupuesto del proyecto en euros/pesos" . PHP_EOL;
echo "   f) fecha_inicio (datetime, tipo 'date')" . PHP_EOL;
echo "      -> Cuando empieza el proyecto" . PHP_EOL;
echo "   g) fecha_fin (datetime, tipo 'date')" . PHP_EOL;
echo "      -> Cuando termina el proyecto" . PHP_EOL;
echo "   h) activo (boolean, default TRUE)" . PHP_EOL;
echo "   i) created (created)" . PHP_EOL;
echo "   j) changed (changed)" . PHP_EOL;
echo "   k) uid (owner - via EntityOwnerTrait)" . PHP_EOL;
echo PHP_EOL;
echo "   TODOS los campos deben tener setDisplayOptions para 'form' y 'view'" . PHP_EOL;
echo "   y setDisplayConfigurable(TRUE) en ambos." . PHP_EOL;
echo PHP_EOL;

echo "--- PARTE 2: Formulario ---" . PHP_EOL;
echo PHP_EOL;

echo "3. Crear ProyectoForm (src/Form/ProyectoForm.php):" . PHP_EOL;
echo "   - Extiende ContentEntityForm" . PHP_EOL;
echo "   - Override save() para mostrar mensajes:" . PHP_EOL;
echo "     -> SAVED_NEW: 'Proyecto [titulo] creado correctamente.'" . PHP_EOL;
echo "     -> SAVED_UPDATED: 'Proyecto [titulo] actualizado.'" . PHP_EOL;
echo "   - Redirige al listado despues de guardar" . PHP_EOL;
echo PHP_EOL;

echo "--- PARTE 3: List Builder ---" . PHP_EOL;
echo PHP_EOL;

echo "4. Crear ProyectoListBuilder (src/ProyectoListBuilder.php):" . PHP_EOL;
echo "   - Columnas: ID, Titulo (como enlace), Cliente, Estado, Presupuesto, Creado" . PHP_EOL;
echo "   - El presupuesto debe mostrarse formateado (ej: '1,500.00')" . PHP_EOL;
echo "   - El estado debe mostrar el label legible, no la clave" . PHP_EOL;
echo PHP_EOL;

echo "--- PARTE 4: Access Control ---" . PHP_EOL;
echo PHP_EOL;

echo "5. Crear ProyectoAccessControlHandler (src/ProyectoAccessControlHandler.php):" . PHP_EOL;
echo "   - view: permiso 'view proyectos'" . PHP_EOL;
echo "   - update: permiso 'edit proyectos'" . PHP_EOL;
echo "   - delete: permiso 'delete proyectos'" . PHP_EOL;
echo "   - create: permiso 'add proyectos'" . PHP_EOL;
echo PHP_EOL;

echo "--- PARTE 5: Archivos YML ---" . PHP_EOL;
echo PHP_EOL;

echo "6. proyectos.info.yml" . PHP_EOL;
echo "7. proyectos.permissions.yml con 5 permisos:" . PHP_EOL;
echo "   - administer proyectos" . PHP_EOL;
echo "   - add proyectos" . PHP_EOL;
echo "   - view proyectos" . PHP_EOL;
echo "   - edit proyectos" . PHP_EOL;
echo "   - delete proyectos" . PHP_EOL;
echo "8. proyectos.links.menu.yml:" . PHP_EOL;
echo "   - Enlace en admin/structure -> 'Proyectos'" . PHP_EOL;
echo "9. proyectos.links.action.yml:" . PHP_EOL;
echo "   - Boton 'Agregar proyecto' en la pagina de listado" . PHP_EOL;
echo PHP_EOL;

echo "--- Estructura esperada ---" . PHP_EOL;
echo "web/modules/custom/proyectos/" . PHP_EOL;
echo "├── proyectos.info.yml" . PHP_EOL;
echo "├── proyectos.permissions.yml" . PHP_EOL;
echo "├── proyectos.links.menu.yml" . PHP_EOL;
echo "├── proyectos.links.action.yml" . PHP_EOL;
echo "└── src/" . PHP_EOL;
echo "    ├── Entity/" . PHP_EOL;
echo "    │   └── Proyecto.php" . PHP_EOL;
echo "    ├── Form/" . PHP_EOL;
echo "    │   └── ProyectoForm.php" . PHP_EOL;
echo "    ├── ProyectoListBuilder.php" . PHP_EOL;
echo "    └── ProyectoAccessControlHandler.php" . PHP_EOL;
echo PHP_EOL;

echo "--- BONUS ---" . PHP_EOL;
echo PHP_EOL;

echo "10. Agregar un campo entity_reference a 'node' (nodo):" . PHP_EOL;
echo "    -> Permite asignar un 'responsable' (referencia a User)" . PHP_EOL;
echo <<<'CODE'
// Dentro de baseFieldDefinitions(), agregar:
$fields['responsable'] = BaseFieldDefinition::create('entity_reference')
  ->setLabel(t('Responsable'))
  ->setDescription(t('Usuario responsable del proyecto.'))
  ->setSetting('target_type', 'user')
  ->setDisplayOptions('view', [
    'label' => 'above',
    'type' => 'entity_reference_label',
    'weight' => 20,
  ])
  ->setDisplayOptions('form', [
    'type' => 'entity_reference_autocomplete',
    'weight' => 20,
  ])
  ->setDisplayConfigurable('form', TRUE)
  ->setDisplayConfigurable('view', TRUE);
CODE;
echo PHP_EOL . PHP_EOL;

echo "11. Crear un Controller custom ProyectosDashboard:" . PHP_EOL;
echo "    - Ruta: /admin/proyectos/dashboard" . PHP_EOL;
echo "    - Muestra estadisticas:" . PHP_EOL;
echo "      -> Total de proyectos" . PHP_EOL;
echo "      -> Proyectos por estado (pendiente: X, en progreso: Y, ...)" . PHP_EOL;
echo "      -> Presupuesto total de proyectos activos" . PHP_EOL;
echo "    - Usa entity queries para obtener los datos" . PHP_EOL;
echo PHP_EOL;

echo "--- Para probar ---" . PHP_EOL;
echo "ddev drush en proyectos -y" . PHP_EOL;
echo "ddev drush entity:updates" . PHP_EOL;
echo "ddev drush cr" . PHP_EOL;
echo PHP_EOL;
echo "1. Ve a /admin/structure -> deberia aparecer 'Proyectos'" . PHP_EOL;
echo "2. Haz clic -> listado vacio con boton 'Agregar proyecto'" . PHP_EOL;
echo "3. Crea 3-4 proyectos con diferentes estados y presupuestos" . PHP_EOL;
echo "4. Verifica que el listado muestra correctamente todas las columnas" . PHP_EOL;
echo "5. Edita un proyecto -> verifica que se actualiza" . PHP_EOL;
echo "6. Elimina un proyecto -> verifica la confirmacion" . PHP_EOL;
echo PHP_EOL;
echo "Pidele a Claude que revise tu entidad!" . PHP_EOL;
