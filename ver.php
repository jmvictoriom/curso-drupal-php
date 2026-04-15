<?php

$archivo = $_GET['f'] ?? '';

// Seguridad: solo permitir archivos .php dentro de las carpetas del curso
if (empty($archivo)
    || !preg_match('/^\d{2}-[a-z\-]+\/(teoria|ejercicio|solucion)\.php$/', $archivo)
    || !file_exists($archivo)
) {
    header('Location: /');
    exit;
}

$nombre_leccion = basename(dirname($archivo));
$tipo = basename($archivo, '.php');
$tipo_label = ucfirst($tipo);

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($tipo_label . ' - ' . $nombre_leccion) ?></title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Courier New', monospace; background: #0f172a; color: #e2e8f0; line-height: 1.7; }
        .nav { background: #1e293b; padding: 1rem 2rem; display: flex; justify-content: space-between; align-items: center; }
        .nav a { color: #60a5fa; text-decoration: none; font-weight: 600; }
        .nav a:hover { color: #93c5fd; }
        .nav .badge { background: #3b82f6; color: white; padding: 0.2rem 0.6rem; border-radius: 4px; font-size: 0.8rem; }
        .content { max-width: 900px; margin: 0 auto; padding: 2rem; }
        pre { white-space: pre-wrap; word-wrap: break-word; font-size: 0.95rem; }
    </style>
</head>
<body>
    <div class="nav">
        <a href="/">&larr; Volver al indice</a>
        <span class="badge"><?= htmlspecialchars($tipo_label) ?></span>
    </div>
    <div class="content">
        <pre><?php
            ob_start();
            include $archivo;
            $output = ob_get_clean();
            echo htmlspecialchars($output);
        ?></pre>
    </div>
</body>
</html>
