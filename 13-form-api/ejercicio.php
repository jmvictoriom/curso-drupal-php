<?php

/**
 * =============================================================
 *  EJERCICIO 13: FORM API
 * =============================================================
 */

echo "=== EJERCICIO: FORMULARIOS ===" . PHP_EOL;
echo PHP_EOL;

echo "Crea un modulo 'mi_formulario' con DOS formularios:" . PHP_EOL;
echo PHP_EOL;

echo "--- 1. Formulario de registro (FormBase) ---" . PHP_EOL;
echo "Ruta: /registro" . PHP_EOL;
echo "Clase: RegistroForm" . PHP_EOL;
echo "Campos:" . PHP_EOL;
echo "  - nombre (textfield, obligatorio, min 3 caracteres)" . PHP_EOL;
echo "  - email (email, obligatorio)" . PHP_EOL;
echo "  - password (password, obligatorio, min 8 caracteres)" . PHP_EOL;
echo "  - confirmar_password (password, obligatorio, debe coincidir)" . PHP_EOL;
echo "  - edad (number, obligatorio, min 18)" . PHP_EOL;
echo "  - genero (radios: Masculino, Femenino, Otro)" . PHP_EOL;
echo "  - intereses (checkboxes: Deportes, Musica, Tecnologia, Cocina, Viajes)" . PHP_EOL;
echo "  - biografia (textarea, opcional, max 500 caracteres)" . PHP_EOL;
echo "  - aceptar_terminos (checkbox, obligatorio)" . PHP_EOL;
echo PHP_EOL;
echo "Validaciones:" . PHP_EOL;
echo "  - Nombre: minimo 3 caracteres" . PHP_EOL;
echo "  - Password: minimo 8 caracteres" . PHP_EOL;
echo "  - Confirmar password: debe coincidir con password" . PHP_EOL;
echo "  - Edad: minimo 18" . PHP_EOL;
echo PHP_EOL;
echo "Submit: muestra mensaje 'Bienvenido, [nombre]!' y redirige al front" . PHP_EOL;
echo PHP_EOL;

echo "--- 2. Formulario de configuracion (ConfigFormBase) ---" . PHP_EOL;
echo "Ruta: /admin/config/mi-formulario/settings" . PHP_EOL;
echo "Clase: MiFormularioSettingsForm" . PHP_EOL;
echo "Campos:" . PHP_EOL;
echo "  - titulo_sitio (textfield)" . PHP_EOL;
echo "  - email_admin (email)" . PHP_EOL;
echo "  - items_por_pagina (number, 1-50)" . PHP_EOL;
echo "  - modo_mantenimiento (checkbox)" . PHP_EOL;
echo "  - Color del tema (select: azul, rojo, verde)" . PHP_EOL;
echo PHP_EOL;
echo "Los valores deben guardarse y recuperarse correctamente." . PHP_EOL;
echo PHP_EOL;

echo "--- Estructura ---" . PHP_EOL;
echo "web/modules/custom/mi_formulario/" . PHP_EOL;
echo "├── mi_formulario.info.yml" . PHP_EOL;
echo "├── mi_formulario.routing.yml" . PHP_EOL;
echo "└── src/" . PHP_EOL;
echo "    └── Form/" . PHP_EOL;
echo "        ├── RegistroForm.php" . PHP_EOL;
echo "        └── MiFormularioSettingsForm.php" . PHP_EOL;
echo PHP_EOL;
echo "Pidele a Claude que revise tus formularios!" . PHP_EOL;
