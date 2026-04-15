<?php

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['error' => 'Metodo no permitido']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);
$code = $input['code'] ?? '';

if (empty(trim($code))) {
    echo json_encode(['output' => '', 'error' => 'No hay codigo para ejecutar']);
    exit;
}

// Escribir codigo a archivo temporal
$tmp = tempnam(sys_get_temp_dir(), 'curso_');
$tmp_php = $tmp . '.php';
rename($tmp, $tmp_php);

// Envolver el codigo del usuario
$wrapper = "<?php\n";
$wrapper .= "error_reporting(E_ALL);\n";
$wrapper .= "ini_set('display_errors', 1);\n";
$wrapper .= $code . "\n";

file_put_contents($tmp_php, $wrapper);

// Ejecutar con timeout de 5 segundos
$descriptors = [
    0 => ['pipe', 'r'],
    1 => ['pipe', 'w'],
    2 => ['pipe', 'w'],
];

$process = proc_open(
    ['php', '-d', 'max_execution_time=5', '-d', 'memory_limit=32M', $tmp_php],
    $descriptors,
    $pipes
);

if (!is_resource($process)) {
    unlink($tmp_php);
    echo json_encode(['error' => 'No se pudo ejecutar el codigo']);
    exit;
}

fclose($pipes[0]);

$stdout = stream_get_contents($pipes[1]);
fclose($pipes[1]);

$stderr = stream_get_contents($pipes[2]);
fclose($pipes[2]);

$exit_code = proc_close($process);
unlink($tmp_php);

$output = $stdout;
if (!empty($stderr)) {
    // Limpiar rutas del servidor en mensajes de error
    $stderr = preg_replace('/in \/tmp\/[^\s]+/', '', $stderr);
    $output .= "\n" . $stderr;
}

echo json_encode([
    'output' => $output,
    'success' => $exit_code === 0,
]);
