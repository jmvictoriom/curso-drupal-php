<?php

$archivo = $_GET['f'] ?? '';

if (empty($archivo)
    || !preg_match('/^\d{2}-[a-z\-]+\/(teoria|ejercicio|solucion)(\.php|\/[a-z_\/]+\.php)$/', $archivo)
    || !file_exists($archivo)
) {
    header('Location: /');
    exit;
}

$nombre_leccion = basename(dirname($archivo));
$num_leccion = substr($nombre_leccion, 0, 2);
$titulo_leccion = ucwords(str_replace('-', ' ', substr($nombre_leccion, 3)));
$tipo = basename($archivo, '.php');
$tipo_label = ucfirst($tipo);

$colores = [
    'teoria' => ['bg' => '#eff6ff', 'text' => '#2563eb'],
    'ejercicio' => ['bg' => '#f0fdf4', 'text' => '#16a34a'],
    'solucion' => ['bg' => '#fffbeb', 'text' => '#d97706'],
];
$color = $colores[$tipo] ?? $colores['teoria'];

// Capturar la salida del archivo PHP
ob_start();
include $archivo;
$output = ob_get_clean();

// Parsear la salida para darle formato
$lines = explode("\n", htmlspecialchars($output));
$formatted = [];
foreach ($lines as $line) {
    // Titulos con ===
    if (preg_match('/^===(.+)===$/', trim($line), $m)) {
        $formatted[] = '<span class="section-title">' . trim($m[1]) . '</span>';
    }
    // Separadores ---
    elseif (preg_match('/^-{3,}/', trim($line))) {
        $formatted[] = '<span class="separator"></span>';
    }
    // Lineas con --- Ejercicio/Paso ---
    elseif (preg_match('/^---(.+)---$/', trim($line), $m)) {
        $formatted[] = '<span class="subsection">' . trim($m[1]) . '</span>';
    }
    else {
        $formatted[] = $line;
    }
}
$output_html = implode("\n", $formatted);

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars("$tipo_label - $titulo_leccion") ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg: #fafafa;
            --surface: #ffffff;
            --border: #e5e5e5;
            --text: #171717;
            --text-secondary: #737373;
            --radius: 12px;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Inter', -apple-system, sans-serif;
            background: var(--bg);
            color: var(--text);
            -webkit-font-smoothing: antialiased;
        }

        .topbar {
            background: var(--surface);
            border-bottom: 1px solid var(--border);
            padding: 0.85rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 10;
        }

        .topbar-left {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .topbar a {
            color: var(--text-secondary);
            text-decoration: none;
            font-size: 0.85rem;
            font-weight: 500;
            transition: color 0.15s;
        }

        .topbar a:hover { color: var(--text); }

        .topbar-title {
            font-size: 0.85rem;
            font-weight: 600;
            color: var(--text);
        }

        .topbar-badge {
            font-size: 0.72rem;
            font-weight: 600;
            padding: 0.3rem 0.75rem;
            border-radius: 100px;
            letter-spacing: 0.02em;
        }

        .content-wrap {
            max-width: 800px;
            margin: 2rem auto;
            padding: 0 1.5rem 4rem;
        }

        .lesson-header {
            margin-bottom: 2rem;
        }

        .lesson-num {
            font-family: 'JetBrains Mono', monospace;
            font-size: 0.8rem;
            color: var(--text-secondary);
            margin-bottom: 0.25rem;
        }

        .lesson-title {
            font-size: 1.5rem;
            font-weight: 700;
            letter-spacing: -0.02em;
        }

        .output-card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            overflow: hidden;
        }

        .output-bar {
            background: #f5f5f5;
            border-bottom: 1px solid var(--border);
            padding: 0.6rem 1.25rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .output-bar .dot { width: 10px; height: 10px; border-radius: 50%; }
        .dot-red { background: #ef4444; }
        .dot-yellow { background: #f59e0b; }
        .dot-green { background: #22c55e; }

        .output-bar span {
            font-family: 'JetBrains Mono', monospace;
            font-size: 0.75rem;
            color: var(--text-secondary);
            margin-left: 0.5rem;
        }

        .output-body {
            padding: 1.5rem;
            overflow-x: auto;
        }

        .output-body pre {
            font-family: 'JetBrains Mono', monospace;
            font-size: 0.85rem;
            line-height: 1.8;
            white-space: pre-wrap;
            word-wrap: break-word;
            color: #374151;
        }

        .section-title {
            display: block;
            font-weight: 700;
            font-size: 0.95rem;
            color: #111;
            margin-top: 1.25rem;
            margin-bottom: 0.25rem;
            padding-bottom: 0.4rem;
            border-bottom: 2px solid #e5e5e5;
        }

        .subsection {
            display: block;
            font-weight: 600;
            color: #2563eb;
            margin-top: 0.75rem;
        }

        .separator {
            display: block;
            height: 1px;
            background: #e5e5e5;
            margin: 0.5rem 0;
        }

        .nav-bottom {
            display: flex;
            justify-content: space-between;
            margin-top: 1.5rem;
            gap: 1rem;
        }

        .nav-bottom a {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            text-decoration: none;
            font-size: 0.85rem;
            font-weight: 500;
            color: var(--text-secondary);
            padding: 0.6rem 1rem;
            border: 1px solid var(--border);
            border-radius: 8px;
            background: var(--surface);
            transition: all 0.15s;
        }

        .nav-bottom a:hover {
            color: var(--text);
            border-color: #d4d4d4;
        }

        @media (max-width: 640px) {
            .topbar { padding: 0.75rem 1rem; }
            .content-wrap { padding: 0 1rem 3rem; }
            .lesson-title { font-size: 1.2rem; }
            .output-body { padding: 1rem; }
            .output-body pre { font-size: 0.8rem; }
        }
    </style>
</head>
<body>
    <div class="topbar">
        <div class="topbar-left">
            <a href="/">&larr; Indice</a>
            <span class="topbar-title">Leccion <?= $num_leccion ?></span>
        </div>
        <span class="topbar-badge" style="background: <?= $color['bg'] ?>; color: <?= $color['text'] ?>"><?= $tipo_label ?></span>
    </div>

    <div class="content-wrap">
        <div class="lesson-header">
            <div class="lesson-num">Leccion <?= $num_leccion ?></div>
            <h1 class="lesson-title"><?= htmlspecialchars($titulo_leccion) ?></h1>
        </div>

        <div class="output-card">
            <div class="output-bar">
                <div class="dot dot-red"></div>
                <div class="dot dot-yellow"></div>
                <div class="dot dot-green"></div>
                <span>php <?= htmlspecialchars(basename($archivo)) ?></span>
            </div>
            <div class="output-body">
                <pre><?= $output_html ?></pre>
            </div>
        </div>

        <div class="nav-bottom">
            <?php
            $dir = dirname($archivo);
            $otros = [];
            if ($tipo !== 'teoria' && file_exists("$dir/teoria.php"))
                $otros[] = ['label' => 'Teoria', 'file' => "$dir/teoria.php"];
            if ($tipo !== 'ejercicio' && file_exists("$dir/ejercicio.php"))
                $otros[] = ['label' => 'Ejercicio', 'file' => "$dir/ejercicio.php"];
            if ($tipo !== 'solucion' && file_exists("$dir/solucion.php"))
                $otros[] = ['label' => 'Solucion', 'file' => "$dir/solucion.php"];
            ?>
            <a href="/">Volver al indice</a>
            <div style="display:flex;gap:0.5rem">
                <?php foreach ($otros as $o): ?>
                    <a href="ver.php?f=<?= urlencode($o['file']) ?>"><?= $o['label'] ?> &rarr;</a>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</body>
</html>
