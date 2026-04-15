<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Curso Drupal + PHP: De Cero a Experto</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif; background: #0f172a; color: #e2e8f0; line-height: 1.6; }
        .header { background: linear-gradient(135deg, #1e40af, #7c3aed); padding: 3rem 2rem; text-align: center; }
        .header h1 { font-size: 2.5rem; margin-bottom: 0.5rem; }
        .header p { font-size: 1.1rem; opacity: 0.9; }
        .container { max-width: 900px; margin: 0 auto; padding: 2rem; }
        .fase { margin-bottom: 2rem; }
        .fase-titulo { font-size: 1.3rem; color: #60a5fa; margin-bottom: 1rem; border-bottom: 2px solid #1e3a5f; padding-bottom: 0.5rem; }
        .leccion { display: flex; align-items: center; gap: 1rem; padding: 0.8rem 1rem; margin-bottom: 0.5rem; background: #1e293b; border-radius: 8px; transition: background 0.2s; }
        .leccion:hover { background: #334155; }
        .leccion-num { background: #3b82f6; color: white; width: 36px; height: 36px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold; font-size: 0.85rem; flex-shrink: 0; }
        .leccion-info { flex: 1; }
        .leccion-info h3 { font-size: 1rem; color: #f1f5f9; }
        .leccion-links { display: flex; gap: 0.5rem; flex-shrink: 0; }
        .leccion-links a { text-decoration: none; padding: 0.3rem 0.7rem; border-radius: 4px; font-size: 0.8rem; font-weight: 600; }
        .btn-teoria { background: #2563eb; color: white; }
        .btn-ejercicio { background: #059669; color: white; }
        .btn-solucion { background: #d97706; color: white; }
        .btn-teoria:hover { background: #1d4ed8; }
        .btn-ejercicio:hover { background: #047857; }
        .btn-solucion:hover { background: #b45309; }
        .footer { text-align: center; padding: 2rem; color: #64748b; font-size: 0.9rem; }
        pre { background: #0f172a; padding: 1rem; border-radius: 6px; overflow-x: auto; margin: 1rem 0; font-size: 0.9rem; }
        @media (max-width: 600px) {
            .header h1 { font-size: 1.5rem; }
            .leccion { flex-wrap: wrap; }
            .leccion-links { width: 100%; justify-content: flex-end; }
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Curso Drupal + PHP</h1>
        <p>De Cero a Experto &mdash; 25 lecciones interactivas</p>
    </div>

    <div class="container">
        <?php
        $fases = [
            'Fase 1 &mdash; Fundamentos PHP' => [
                ['num' => '01', 'titulo' => 'Hola Mundo y Variables', 'dir' => '01-hola-mundo-y-variables', 'solucion' => true],
                ['num' => '02', 'titulo' => 'Tipos de datos y operadores', 'dir' => '02-tipos-de-datos-y-operadores', 'solucion' => true],
                ['num' => '03', 'titulo' => 'Estructuras de control', 'dir' => '03-estructuras-de-control', 'solucion' => true],
                ['num' => '04', 'titulo' => 'Arrays', 'dir' => '04-arrays', 'solucion' => true],
                ['num' => '05', 'titulo' => 'Funciones', 'dir' => '05-funciones', 'solucion' => true],
                ['num' => '06', 'titulo' => 'Clases y Objetos (OOP)', 'dir' => '06-clases-y-objetos', 'solucion' => true],
                ['num' => '07', 'titulo' => 'Namespaces y Composer', 'dir' => '07-namespaces-y-composer', 'solucion' => true],
            ],
            'Fase 2 &mdash; Fundamentos Drupal' => [
                ['num' => '08', 'titulo' => 'Arquitectura de Drupal', 'dir' => '08-arquitectura-drupal'],
                ['num' => '09', 'titulo' => 'Instalacion de Drupal (DDEV)', 'dir' => '09-instalacion-drupal'],
                ['num' => '10', 'titulo' => 'Tu primer modulo custom', 'dir' => '10-primer-modulo'],
                ['num' => '11', 'titulo' => 'Routing, Controllers y Services', 'dir' => '11-routing-y-controllers'],
                ['num' => '12', 'titulo' => 'Hooks y Eventos', 'dir' => '12-hooks-y-eventos'],
            ],
            'Fase 3 &mdash; Desarrollo intermedio' => [
                ['num' => '13', 'titulo' => 'Form API (formularios)', 'dir' => '13-form-api'],
                ['num' => '14', 'titulo' => 'Entity API y campos', 'dir' => '14-entity-api'],
                ['num' => '15', 'titulo' => 'Database API', 'dir' => '15-database-api'],
                ['num' => '16', 'titulo' => 'Bloques custom (Block Plugin)', 'dir' => '16-bloques-custom'],
                ['num' => '17', 'titulo' => 'Twig y Theming', 'dir' => '17-twig-y-theming'],
                ['num' => '18', 'titulo' => 'Temas custom', 'dir' => '18-temas-custom'],
            ],
            'Fase 4 &mdash; Avanzado' => [
                ['num' => '19', 'titulo' => 'Plugin API en profundidad', 'dir' => '19-plugin-api'],
                ['num' => '20', 'titulo' => 'Eventos avanzados', 'dir' => '20-eventos-avanzados'],
                ['num' => '21', 'titulo' => 'Custom Entities', 'dir' => '21-custom-entities'],
                ['num' => '22', 'titulo' => 'REST API y servicios web', 'dir' => '22-rest-api'],
                ['num' => '23', 'titulo' => 'Testing (PHPUnit)', 'dir' => '23-testing'],
                ['num' => '24', 'titulo' => 'Drush y herramientas', 'dir' => '24-drush-y-herramientas'],
                ['num' => '25', 'titulo' => 'Deploy y Config Management', 'dir' => '25-deploy-y-config-management'],
            ],
        ];

        foreach ($fases as $fase_titulo => $lecciones): ?>
            <div class="fase">
                <h2 class="fase-titulo"><?= $fase_titulo ?></h2>
                <?php foreach ($lecciones as $l): ?>
                    <div class="leccion">
                        <div class="leccion-num"><?= $l['num'] ?></div>
                        <div class="leccion-info">
                            <h3><?= $l['titulo'] ?></h3>
                        </div>
                        <div class="leccion-links">
                            <?php if (file_exists($l['dir'] . '/teoria.php')): ?>
                                <a href="ver.php?f=<?= urlencode($l['dir'] . '/teoria.php') ?>" class="btn-teoria">Teoria</a>
                            <?php endif; ?>
                            <?php if (file_exists($l['dir'] . '/ejercicio.php')): ?>
                                <a href="ver.php?f=<?= urlencode($l['dir'] . '/ejercicio.php') ?>" class="btn-ejercicio">Ejercicio</a>
                            <?php endif; ?>
                            <?php if (!empty($l['solucion']) && file_exists($l['dir'] . '/solucion.php')): ?>
                                <a href="ver.php?f=<?= urlencode($l['dir'] . '/solucion.php') ?>" class="btn-solucion">Solucion</a>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endforeach; ?>
    </div>

    <div class="footer">
        <p>Curso creado con Claude &mdash; <?= date('Y') ?></p>
    </div>
</body>
</html>
