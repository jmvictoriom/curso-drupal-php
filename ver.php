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

$es_ejercicio = ($tipo === 'ejercicio');
$es_solucion = ($tipo === 'solucion');
$es_teoria = ($tipo === 'teoria');

if ($es_ejercicio || $es_teoria) {
    $source = file_get_contents($archivo);
    $source = preg_replace('/^<\?php\s*/', '', $source);
} elseif ($es_solucion) {
    $source_raw = file_get_contents($archivo);
    $source_clean = preg_replace('/^<\?php\s*/', '', $source_raw);
    ob_start();
    include $archivo;
    $output = ob_get_clean();
} else {
    ob_start();
    include $archivo;
    $output = ob_get_clean();
}

/**
 * Parsea ejercicios con editores interactivos.
 */
function parseExerciseSource(string $source): string {
    $lines = explode("\n", $source);
    $html = '';
    $in_block_comment = false;
    $editor_id = 0;
    $context_lines = []; // Lineas de contexto PHP antes del editor

    foreach ($lines as $line) {
        $trimmed = trim($line);

        // Bloque docblock
        if (str_starts_with($trimmed, '/**') || str_starts_with($trimmed, '/*')) {
            $in_block_comment = true;
        }
        if ($in_block_comment) {
            if (str_contains($trimmed, '*/')) {
                $in_block_comment = false;
            }
            $text = preg_replace('/^\s*\/?\*+\/?\s?/', '', $line);
            $text = trim($text);
            if (preg_match('/^=+$/', $text) || empty($text)) continue;
            if (preg_match('/^(LECCION|EJERCICIO)\s+\d+/', $text)) {
                $html .= '<div class="ex-main-title">' . htmlspecialchars($text) . '</div>';
            } elseif (!empty($text)) {
                $html .= '<div class="ex-desc">' . htmlspecialchars($text) . '</div>';
            }
            continue;
        }

        if (empty($trimmed)) {
            $html .= '<div class="ex-spacer"></div>';
            continue;
        }

        if (preg_match('/^\/\/\s*-{5,}/', $trimmed)) {
            $html .= '<div class="ex-divider"></div>';
            continue;
        }

        // TU CODIGO AQUI -> editor interactivo (ANTES del check de comentarios)
        if (str_contains($trimmed, 'TU CODIGO AQUI')) {
            $editor_id++;
            $ctx = !empty($context_lines) ? htmlspecialchars(implode("\n", $context_lines)) : '';
            $html .= '<div class="editor-block" id="editor-block-' . $editor_id . '">';
            if (!empty($ctx)) {
                $html .= '<div class="editor-context"><pre>' . $ctx . '</pre></div>';
            }
            $html .= '<div class="editor-wrap">';
            $html .= '<textarea class="code-editor" id="editor-' . $editor_id . '" placeholder="Escribe tu codigo PHP aqui..." spellcheck="false"></textarea>';
            $html .= '<div class="editor-actions">';
            $html .= '<button class="btn-run" onclick="ejecutarCodigo(' . $editor_id . ')"><span class="btn-icon">&#9654;</span> Ejecutar</button>';
            $html .= '<button class="btn-clear" onclick="limpiar(' . $editor_id . ')">Limpiar</button>';
            $html .= '</div>';
            $html .= '</div>';
            $html .= '<div class="output-panel" id="output-' . $editor_id . '" style="display:none">';
            $html .= '<div class="output-panel-bar">Resultado</div>';
            $html .= '<pre class="output-panel-content" id="output-content-' . $editor_id . '"></pre>';
            $html .= '</div>';
            $html .= '</div>';
            $context_lines = [];
            continue;
        }

        // Titulo de ejercicio: // EJERCICIO 1: ...
        if (preg_match('/^\/\/\s*(EJERCICIO\s+\d+.*)/i', $trimmed, $m)) {
            $html .= '<div class="ex-title">' . htmlspecialchars(trim($m[1])) . '</div>';
            $context_lines = [];
            continue;
        }

        // Instruccion (comentario //)
        if (preg_match('/^\/\/\s?(.*)$/', $trimmed, $m)) {
            $text = $m[1];
            if (preg_match('/^(Pista|Formato|Ejemplo|Nota|Muestra|Prueba|Operaciones)/i', trim($text))) {
                $html .= '<div class="ex-hint">' . htmlspecialchars($text) . '</div>';
            } else {
                $html .= '<div class="ex-instruction">' . htmlspecialchars($text) . '</div>';
            }
            continue;
        }

        // Codigo PHP real (echo, variables, etc.)
        if (!empty($trimmed)) {
            $context_lines[] = $line;
            $html .= '<div class="ex-code">' . htmlspecialchars($line) . '</div>';
            continue;
        }
    }

    return $html;
}

/**
 * Parsea el codigo fuente de una teoria en contenido formateado.
 */
function parseTheorySource(string $source): string {
    $lines = explode("\n", $source);
    $html = '';
    $in_block_comment = false;
    $code_buffer = [];
    $skip_docblock = true;

    $flushCode = function() use (&$code_buffer, &$html) {
        if (empty($code_buffer)) return;
        $code = implode("\n", $code_buffer);
        $escaped = htmlspecialchars($code);
        // Syntax highlighting basico
        $escaped = preg_replace('/(\$[a-zA-Z_]\w*)/', '<span class="th-var">$1</span>', $escaped);
        $escaped = preg_replace('/(&quot;[^&]*&quot;|&#039;[^&]*&#039;)/', '<span class="th-str">$1</span>', $escaped);
        $keywords = ['echo', 'return', 'function', 'class', 'new', 'if', 'else', 'elseif', 'for', 'foreach', 'while', 'switch', 'case', 'break', 'continue', 'match', 'fn', 'use', 'as', 'true', 'false', 'null', 'array', 'int', 'float', 'string', 'bool', 'void', 'public', 'private', 'protected', 'static', 'abstract', 'interface', 'extends', 'implements', 'namespace', 'readonly', 'trait'];
        foreach ($keywords as $kw) {
            $escaped = preg_replace('/\b(' . $kw . ')\b/', '<span class="th-kw">$1</span>', $escaped);
        }
        $html .= '<div class="th-code-block"><pre>' . $escaped . '</pre></div>';
        $code_buffer = [];
    };

    foreach ($lines as $line) {
        $trimmed = trim($line);

        // Docblock del inicio -> skip
        if ($skip_docblock) {
            if (str_starts_with($trimmed, '/**') || str_starts_with($trimmed, '/*')) {
                $in_block_comment = true;
                continue;
            }
            if ($in_block_comment) {
                if (str_contains($trimmed, '*/')) {
                    $in_block_comment = false;
                    $skip_docblock = false;
                }
                continue;
            }
            if (empty($trimmed)) continue;
            $skip_docblock = false;
        }

        // Linea vacia
        if (empty($trimmed)) {
            if (!empty($code_buffer)) {
                $code_buffer[] = '';
            } else {
                $html .= '<div class="th-spacer"></div>';
            }
            continue;
        }

        // Separador visual -------
        if (preg_match('/^\/\/\s*-{5,}/', $trimmed)) {
            $flushCode();
            continue;
        }

        // Seccion principal: // === TITULO ===
        if (preg_match('/^echo\s+["\']===\s*(.+?)\s*===["\']/', $trimmed, $m)) {
            $flushCode();
            $html .= '<div class="th-section">' . htmlspecialchars(trim($m[1])) . '</div>';
            continue;
        }

        // Subseccion: // --- titulo ---
        if (preg_match('/^echo\s+["\']---\s*(.+?)\s*---["\']/', $trimmed, $m)) {
            $flushCode();
            $html .= '<div class="th-subsection">' . htmlspecialchars(trim($m[1])) . '</div>';
            continue;
        }

        // Seccion en comentario
        if (preg_match('/^\/\/\s*===\s*(.+?)\s*===/', $trimmed, $m)) {
            $flushCode();
            $html .= '<div class="th-section">' . htmlspecialchars(trim($m[1])) . '</div>';
            continue;
        }

        // Subseccion en comentario --- Titulo ---
        if (preg_match('/^\/\/\s*---\s*(.+?)\s*---/', $trimmed, $m)) {
            $flushCode();
            $html .= '<div class="th-subsection">' . htmlspecialchars(trim($m[1])) . '</div>';
            continue;
        }

        // Numero de seccion seguido de titulo
        if (preg_match('/^\/\/\s*(\d+\.)\s+(.+)/', $trimmed, $m)) {
            $flushCode();
            $html .= '<div class="th-subsection">' . htmlspecialchars($m[1] . ' ' . $m[2]) . '</div>';
            continue;
        }

        // Comentario explicativo (la teoria principal)
        if (preg_match('/^\/\/\s?(.*)$/', $trimmed, $m)) {
            $flushCode();
            $text = $m[1];
            if (empty(trim($text))) {
                $html .= '<div class="th-spacer"></div>';
            } elseif (preg_match('/^(Reglas|Importante|Nota|Ejemplo|Pista|Cuidado|Truco)/i', trim($text))) {
                $html .= '<div class="th-callout">' . htmlspecialchars($text) . '</div>';
            } else {
                $html .= '<div class="th-text">' . htmlspecialchars($text) . '</div>';
            }
            continue;
        }

        // Bloque heredoc o texto largo con echo -> skip echo wrapper, mostrar contenido
        if (preg_match('/^echo\s+PHP_EOL/', $trimmed) || $trimmed === 'echo PHP_EOL;') {
            continue;
        }

        // echo simple con solo un salto de linea
        if ($trimmed === 'echo PHP_EOL;' || preg_match('/^echo\s+["\']["\']/', $trimmed)) {
            continue;
        }

        // echo con texto informativo -> mostrar como texto
        if (preg_match('/^echo\s+["\'](.+?)["\'](\s*\.\s*PHP_EOL\s*)?;?\s*$/', $trimmed, $m)) {
            $text = $m[1];
            // Limpiar escapes
            $text = str_replace(['\\$', '\\"', "\\'", "\\n", "\\t"], ['$', '"', "'", "\n", "\t"], $text);
            if (empty(trim($text)) || preg_match('/^=+$/', trim($text)) || preg_match('/^-+$/', trim($text))) {
                continue;
            }
            $flushCode();
            $html .= '<div class="th-output">' . htmlspecialchars($text) . '</div>';
            continue;
        }

        // Todo lo demas es codigo PHP
        $code_buffer[] = $line;
    }

    $flushCode();
    return $html;
}

/**
 * Parsea el codigo fuente de una solucion con syntax highlighting basico.
 */
function parseSolutionSource(string $source): string {
    $lines = explode("\n", $source);
    $html = '';
    $in_block_comment = false;

    foreach ($lines as $line) {
        $trimmed = trim($line);

        if (str_starts_with($trimmed, '/**') || str_starts_with($trimmed, '/*')) {
            $in_block_comment = true;
        }
        if ($in_block_comment) {
            if (str_contains($trimmed, '*/')) {
                $in_block_comment = false;
            }
            $text = preg_replace('/^\s*\/?\*+\/?\s?/', '', $line);
            $text = trim($text);
            if (preg_match('/^=+$/', $text) || empty($text)) continue;
            if (preg_match('/^(LECCION|SOLUCION|EJERCICIO)\s+\d+/', $text)) {
                continue; // skip title in docblock
            }
            continue;
        }

        if (empty($trimmed)) {
            $html .= "\n";
            continue;
        }

        // Separador visual
        if (preg_match('/^\/\/\s*-{5,}/', $trimmed)) {
            continue;
        }

        // Titulo de ejercicio en comentario
        if (preg_match('/^\/\/\s*(EJERCICIO\s+\d+.*)/i', $trimmed, $m)) {
            $html .= '<span class="sol-title">' . htmlspecialchars(trim($m[1])) . '</span>' . "\n";
            continue;
        }

        // Otro comentario
        if (preg_match('/^\/\//', $trimmed)) {
            $html .= '<span class="sol-comment">' . htmlspecialchars($line) . '</span>' . "\n";
            continue;
        }

        // Codigo PHP: colorear basico
        $escaped = htmlspecialchars($line);
        // Strings
        $escaped = preg_replace('/(&quot;[^&]*&quot;|&#039;[^&]*&#039;)/', '<span class="sol-string">$1</span>', $escaped);
        // Variables
        $escaped = preg_replace('/(\$[a-zA-Z_]\w*)/', '<span class="sol-var">$1</span>', $escaped);
        // Keywords
        $keywords = ['echo', 'return', 'function', 'class', 'new', 'if', 'else', 'elseif', 'for', 'foreach', 'while', 'switch', 'case', 'break', 'continue', 'match', 'fn', 'use', 'as', 'true', 'false', 'null', 'array', 'int', 'float', 'string', 'bool', 'void'];
        foreach ($keywords as $kw) {
            $escaped = preg_replace('/\b(' . $kw . ')\b/', '<span class="sol-keyword">$1</span>', $escaped);
        }

        $html .= $escaped . "\n";
    }

    return $html;
}

function parseOutput(string $output): string {
    $lines = explode("\n", htmlspecialchars($output));
    $formatted = [];
    foreach ($lines as $line) {
        if (preg_match('/^===(.+)===$/', trim($line), $m)) {
            $formatted[] = '<span class="section-title">' . trim($m[1]) . '</span>';
        } elseif (preg_match('/^---(.+)---$/', trim($line), $m)) {
            $formatted[] = '<span class="subsection">' . trim($m[1]) . '</span>';
        } elseif (preg_match('/^-{3,}$/', trim($line))) {
            $formatted[] = '<span class="separator"></span>';
        } else {
            $formatted[] = $line;
        }
    }
    return implode("\n", $formatted);
}

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
            --bg: #fafafa; --surface: #ffffff; --border: #e5e5e5;
            --text: #171717; --text-secondary: #737373; --radius: 12px;
        }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Inter', -apple-system, sans-serif; background: var(--bg); color: var(--text); -webkit-font-smoothing: antialiased; }

        .topbar { background: var(--surface); border-bottom: 1px solid var(--border); padding: 0.85rem 2rem; display: flex; justify-content: space-between; align-items: center; position: sticky; top: 0; z-index: 10; }
        .topbar-left { display: flex; align-items: center; gap: 1rem; }
        .topbar a { color: var(--text-secondary); text-decoration: none; font-size: 0.85rem; font-weight: 500; transition: color 0.15s; }
        .topbar a:hover { color: var(--text); }
        .topbar-title { font-size: 0.85rem; font-weight: 600; color: var(--text); }
        .topbar-badge { font-size: 0.72rem; font-weight: 600; padding: 0.3rem 0.75rem; border-radius: 100px; letter-spacing: 0.02em; }

        .content-wrap { max-width: 800px; margin: 2rem auto; padding: 0 1.5rem 4rem; }
        .lesson-header { margin-bottom: 2rem; }
        .lesson-num { font-family: 'JetBrains Mono', monospace; font-size: 0.8rem; color: var(--text-secondary); margin-bottom: 0.25rem; }
        .lesson-title { font-size: 1.5rem; font-weight: 700; letter-spacing: -0.02em; }

        .output-card { background: var(--surface); border: 1px solid var(--border); border-radius: var(--radius); overflow: hidden; }
        .output-bar { background: #f5f5f5; border-bottom: 1px solid var(--border); padding: 0.6rem 1.25rem; display: flex; align-items: center; gap: 0.5rem; }
        .output-bar .dot { width: 10px; height: 10px; border-radius: 50%; }
        .dot-red { background: #ef4444; } .dot-yellow { background: #f59e0b; } .dot-green { background: #22c55e; }
        .output-bar span { font-family: 'JetBrains Mono', monospace; font-size: 0.75rem; color: var(--text-secondary); margin-left: 0.5rem; }

        .output-body { padding: 1.5rem; overflow-x: auto; }
        .output-body pre { font-family: 'JetBrains Mono', monospace; font-size: 0.85rem; line-height: 1.8; white-space: pre-wrap; word-wrap: break-word; color: #374151; }
        .section-title { display: block; font-weight: 700; font-size: 0.95rem; color: #111; margin-top: 1.25rem; margin-bottom: 0.25rem; padding-bottom: 0.4rem; border-bottom: 2px solid #e5e5e5; }
        .subsection { display: block; font-weight: 600; color: #2563eb; margin-top: 0.75rem; }
        .separator { display: block; height: 1px; background: #e5e5e5; margin: 0.5rem 0; }

        /* Teoria */
        .theory-content { padding: 2rem; }
        .th-section {
            font-size: 1.15rem; font-weight: 700; color: var(--text);
            margin-top: 2.5rem; margin-bottom: 0.75rem;
            padding-bottom: 0.5rem; border-bottom: 2px solid #e5e5e5;
        }
        .th-section:first-child { margin-top: 0; }
        .th-subsection {
            font-size: 0.95rem; font-weight: 700; color: #2563eb;
            margin-top: 1.5rem; margin-bottom: 0.5rem;
        }
        .th-text {
            font-size: 0.92rem; color: #374151; line-height: 1.8;
            padding-left: 0;
        }
        .th-callout {
            font-size: 0.9rem; color: #92400e;
            background: #fffbeb; border-left: 3px solid #f59e0b;
            padding: 0.5rem 0.85rem; border-radius: 0 6px 6px 0;
            margin: 0.5rem 0;
        }
        .th-output {
            font-family: 'JetBrains Mono', monospace;
            font-size: 0.85rem; color: #374151;
            background: #f0fdf4; border-left: 3px solid #22c55e;
            padding: 0.35rem 0.85rem; border-radius: 0 6px 6px 0;
            margin: 0.25rem 0;
        }
        .th-code-block {
            background: #1e293b; border-radius: 10px;
            padding: 1rem 1.25rem; margin: 0.75rem 0;
            overflow-x: auto;
        }
        .th-code-block pre {
            font-family: 'JetBrains Mono', monospace;
            font-size: 0.83rem; line-height: 1.75;
            color: #e2e8f0; margin: 0;
            white-space: pre-wrap; word-wrap: break-word;
        }
        .th-var { color: #7dd3fc; }
        .th-str { color: #86efac; }
        .th-kw { color: #c4b5fd; }
        .th-spacer { height: 0.5rem; }

        /* Ejercicios */
        .exercise-content { padding: 1.5rem; }
        .ex-main-title { font-size: 1.1rem; font-weight: 700; color: #111; margin-bottom: 0.5rem; }
        .ex-desc { font-size: 0.9rem; color: var(--text-secondary); margin-bottom: 0.15rem; }
        .ex-divider { height: 1px; background: linear-gradient(to right, var(--border), transparent); margin: 1.5rem 0 1rem; }
        .ex-title { font-family: 'Inter', sans-serif; font-size: 1rem; font-weight: 700; color: #16a34a; margin-bottom: 0.75rem; padding: 0.5rem 0.75rem; background: #f0fdf4; border-left: 3px solid #16a34a; border-radius: 0 6px 6px 0; }
        .ex-instruction { font-size: 0.9rem; color: #374151; line-height: 1.7; padding-left: 0.75rem; }
        .ex-hint { font-size: 0.85rem; color: #7c3aed; font-style: italic; padding-left: 0.75rem; margin-top: 0.25rem; }
        .ex-code { font-family: 'JetBrains Mono', monospace; font-size: 0.82rem; color: #6b7280; background: #f9fafb; padding: 0.15rem 0.5rem; border-radius: 4px; margin: 0.1rem 0; }
        .ex-spacer { height: 0.4rem; }

        /* Editor interactivo */
        .editor-block { margin: 1rem 0 1.5rem; }

        .editor-context {
            background: #f8fafc;
            border: 1px solid var(--border);
            border-bottom: none;
            border-radius: var(--radius) var(--radius) 0 0;
            padding: 0.6rem 0.85rem;
        }
        .editor-context pre {
            font-family: 'JetBrains Mono', monospace;
            font-size: 0.8rem;
            color: #9ca3af;
            margin: 0;
            white-space: pre-wrap;
        }

        .editor-wrap {
            border: 2px solid #d1d5db;
            border-radius: var(--radius);
            overflow: hidden;
            transition: border-color 0.2s;
        }
        .editor-wrap:focus-within { border-color: #3b82f6; }
        .editor-context + .editor-wrap { border-radius: 0 0 var(--radius) var(--radius); }

        .code-editor {
            width: 100%;
            min-height: 120px;
            padding: 1rem;
            border: none;
            outline: none;
            font-family: 'JetBrains Mono', monospace;
            font-size: 0.88rem;
            line-height: 1.7;
            resize: vertical;
            background: #fefefe;
            color: var(--text);
            tab-size: 4;
        }

        .editor-actions {
            display: flex;
            gap: 0.5rem;
            padding: 0.6rem 0.85rem;
            background: #f9fafb;
            border-top: 1px solid #e5e7eb;
        }

        .btn-run {
            display: inline-flex; align-items: center; gap: 0.4rem;
            padding: 0.5rem 1.25rem;
            background: #16a34a; color: white;
            border: none; border-radius: 8px;
            font-family: 'Inter', sans-serif; font-size: 0.82rem; font-weight: 600;
            cursor: pointer; transition: all 0.15s;
        }
        .btn-run:hover { background: #15803d; }
        .btn-run:disabled { background: #9ca3af; cursor: not-allowed; }
        .btn-run .btn-icon { font-size: 0.7rem; }

        .btn-clear {
            padding: 0.5rem 1rem;
            background: transparent; color: var(--text-secondary);
            border: 1px solid var(--border); border-radius: 8px;
            font-family: 'Inter', sans-serif; font-size: 0.82rem; font-weight: 500;
            cursor: pointer; transition: all 0.15s;
        }
        .btn-clear:hover { color: var(--text); border-color: #d4d4d4; }

        .output-panel {
            margin-top: 0.5rem;
            border: 1px solid var(--border);
            border-radius: var(--radius);
            overflow: hidden;
            animation: fadeIn 0.2s ease;
        }

        @keyframes fadeIn { from { opacity: 0; transform: translateY(-4px); } to { opacity: 1; transform: translateY(0); } }

        .output-panel-bar {
            background: #1e293b;
            color: #94a3b8;
            padding: 0.45rem 1rem;
            font-family: 'JetBrains Mono', monospace;
            font-size: 0.72rem;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .output-panel-content {
            background: #0f172a;
            color: #e2e8f0;
            padding: 1rem;
            font-family: 'JetBrains Mono', monospace;
            font-size: 0.85rem;
            line-height: 1.7;
            white-space: pre-wrap;
            word-wrap: break-word;
            margin: 0;
            min-height: 2rem;
        }

        .output-panel-content.error { color: #fca5a5; }
        .output-panel-content.success { color: #86efac; }

        .spinner { display: inline-block; width: 14px; height: 14px; border: 2px solid rgba(255,255,255,0.3); border-top-color: white; border-radius: 50%; animation: spin 0.6s linear infinite; }
        @keyframes spin { to { transform: rotate(360deg); } }

        /* Solucion: codigo + consola */
        .sol-section-label {
            font-size: 0.72rem; font-weight: 600; text-transform: uppercase;
            letter-spacing: 0.06em; color: var(--text-secondary);
            margin-bottom: 0.5rem; margin-top: 1.5rem;
        }
        .sol-section-label:first-child { margin-top: 0; }

        .sol-code-card {
            background: #1e293b; border-radius: var(--radius);
            overflow: hidden; margin-bottom: 1rem;
        }
        .sol-code-bar {
            background: #0f172a; padding: 0.5rem 1rem;
            display: flex; align-items: center; gap: 0.5rem;
        }
        .sol-code-bar span {
            font-family: 'JetBrains Mono', monospace;
            font-size: 0.72rem; color: #64748b; margin-left: 0.5rem;
        }
        .sol-code-body {
            padding: 1.25rem; overflow-x: auto;
        }
        .sol-code-body pre {
            font-family: 'JetBrains Mono', monospace;
            font-size: 0.84rem; line-height: 1.75;
            white-space: pre-wrap; word-wrap: break-word;
            color: #e2e8f0; margin: 0;
        }
        .sol-title { display: block; font-weight: 700; color: #fbbf24; margin-top: 1rem; margin-bottom: 0.25rem; }
        .sol-comment { color: #64748b; font-style: italic; }
        .sol-var { color: #7dd3fc; }
        .sol-string { color: #86efac; }
        .sol-keyword { color: #c4b5fd; }

        .sol-output-card {
            background: var(--surface); border: 1px solid var(--border);
            border-radius: var(--radius); overflow: hidden;
        }

        .nav-bottom { display: flex; justify-content: space-between; margin-top: 1.5rem; gap: 1rem; }
        .nav-bottom a { display: inline-flex; align-items: center; gap: 0.4rem; text-decoration: none; font-size: 0.85rem; font-weight: 500; color: var(--text-secondary); padding: 0.6rem 1rem; border: 1px solid var(--border); border-radius: 8px; background: var(--surface); transition: all 0.15s; }
        .nav-bottom a:hover { color: var(--text); border-color: #d4d4d4; }

        @media (max-width: 640px) {
            .topbar { padding: 0.75rem 1rem; }
            .content-wrap { padding: 0 1rem 3rem; }
            .lesson-title { font-size: 1.2rem; }
            .output-body, .exercise-content { padding: 1rem; }
            .code-editor { font-size: 0.82rem; min-height: 100px; }
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
                <span><?= $es_ejercicio ? htmlspecialchars(basename($archivo)) : 'php ' . htmlspecialchars(basename($archivo)) ?></span>
            </div>

            <?php if ($es_teoria): ?>
                <div class="theory-content">
                    <?= parseTheorySource($source) ?>
                </div>
            <?php elseif ($es_ejercicio): ?>
                <div class="exercise-content">
                    <?= parseExerciseSource($source) ?>
                </div>
            <?php elseif ($es_solucion): ?>
                <div style="padding: 1.5rem;">
                    <div class="sol-section-label">Codigo fuente</div>
                    <div class="sol-code-card">
                        <div class="sol-code-bar">
                            <div class="dot dot-red"></div>
                            <div class="dot dot-yellow"></div>
                            <div class="dot dot-green"></div>
                            <span>solucion.php</span>
                        </div>
                        <div class="sol-code-body">
                            <pre><?= parseSolutionSource($source_clean) ?></pre>
                        </div>
                    </div>

                    <div class="sol-section-label">Salida por consola</div>
                    <div class="sol-output-card">
                        <div class="output-panel-bar">php solucion.php</div>
                        <pre class="output-panel-content success"><?= htmlspecialchars($output) ?></pre>
                    </div>
                </div>
            <?php else: ?>
                <div class="output-body">
                    <pre><?= parseOutput($output) ?></pre>
                </div>
            <?php endif; ?>
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

    <?php if ($es_ejercicio): ?>
    <script>
    async function ejecutarCodigo(id) {
        const editor = document.getElementById('editor-' + id);
        const outputPanel = document.getElementById('output-' + id);
        const outputContent = document.getElementById('output-content-' + id);
        const btn = editor.closest('.editor-block').querySelector('.btn-run');
        const code = editor.value.trim();

        if (!code) {
            outputPanel.style.display = 'block';
            outputContent.textContent = 'Escribe algo de codigo primero.';
            outputContent.className = 'output-panel-content error';
            return;
        }

        btn.disabled = true;
        btn.innerHTML = '<span class="spinner"></span> Ejecutando...';
        outputPanel.style.display = 'block';
        outputContent.textContent = 'Ejecutando...';
        outputContent.className = 'output-panel-content';

        try {
            const resp = await fetch('/ejecutar.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ code: code })
            });
            const data = await resp.json();

            if (data.output && data.output.trim()) {
                outputContent.textContent = data.output;
                outputContent.className = 'output-panel-content ' + (data.success ? 'success' : 'error');
            } else if (!data.success) {
                outputContent.textContent = data.error || 'Error al ejecutar el codigo.';
                outputContent.className = 'output-panel-content error';
            } else {
                outputContent.textContent = '(sin salida)';
                outputContent.className = 'output-panel-content';
            }
        } catch (e) {
            outputContent.textContent = 'Error de conexion: ' + e.message;
            outputContent.className = 'output-panel-content error';
        }

        btn.disabled = false;
        btn.innerHTML = '<span class="btn-icon">&#9654;</span> Ejecutar';
    }

    function limpiar(id) {
        document.getElementById('editor-' + id).value = '';
        document.getElementById('output-' + id).style.display = 'none';
    }

    // Tab en el textarea inserta una tabulacion
    document.querySelectorAll('.code-editor').forEach(editor => {
        editor.addEventListener('keydown', function(e) {
            if (e.key === 'Tab') {
                e.preventDefault();
                const start = this.selectionStart;
                const end = this.selectionEnd;
                this.value = this.value.substring(0, start) + '    ' + this.value.substring(end);
                this.selectionStart = this.selectionEnd = start + 4;
            }
            // Ctrl+Enter para ejecutar
            if ((e.ctrlKey || e.metaKey) && e.key === 'Enter') {
                e.preventDefault();
                const block = this.closest('.editor-block');
                const id = block.id.replace('editor-block-', '');
                ejecutarCodigo(id);
            }
        });
    });
    </script>
    <?php endif; ?>
</body>
</html>
