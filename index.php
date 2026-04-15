<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Curso Drupal + PHP</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg: #fafafa;
            --surface: #ffffff;
            --surface-hover: #f5f5f5;
            --border: #e5e5e5;
            --text: #171717;
            --text-secondary: #737373;
            --accent: #2563eb;
            --accent-hover: #1d4ed8;
            --green: #16a34a;
            --green-hover: #15803d;
            --amber: #d97706;
            --amber-hover: #b45309;
            --fase1: #3b82f6;
            --fase2: #8b5cf6;
            --fase3: #f59e0b;
            --fase4: #ef4444;
            --radius: 12px;
            --shadow: 0 1px 3px rgba(0,0,0,0.06), 0 1px 2px rgba(0,0,0,0.04);
            --shadow-md: 0 4px 6px rgba(0,0,0,0.05), 0 2px 4px rgba(0,0,0,0.04);
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Inter', -apple-system, sans-serif;
            background: var(--bg);
            color: var(--text);
            line-height: 1.6;
            -webkit-font-smoothing: antialiased;
        }

        .hero {
            background: var(--surface);
            border-bottom: 1px solid var(--border);
            padding: 4rem 2rem 3rem;
            text-align: center;
        }

        .hero-badge {
            display: inline-block;
            background: #eff6ff;
            color: var(--accent);
            font-size: 0.8rem;
            font-weight: 600;
            padding: 0.35rem 1rem;
            border-radius: 100px;
            margin-bottom: 1.5rem;
            letter-spacing: 0.02em;
        }

        .hero h1 {
            font-size: 2.75rem;
            font-weight: 700;
            letter-spacing: -0.03em;
            margin-bottom: 0.75rem;
            color: var(--text);
        }

        .hero p {
            font-size: 1.15rem;
            color: var(--text-secondary);
            max-width: 500px;
            margin: 0 auto;
        }

        .stats {
            display: flex;
            justify-content: center;
            gap: 3rem;
            margin-top: 2rem;
        }

        .stat { text-align: center; }
        .stat-num { font-size: 1.75rem; font-weight: 700; color: var(--text); }
        .stat-label { font-size: 0.8rem; color: var(--text-secondary); text-transform: uppercase; letter-spacing: 0.05em; }

        .container { max-width: 720px; margin: 0 auto; padding: 2.5rem 1.5rem 4rem; }

        .fase { margin-bottom: 3rem; }

        .fase-header {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin-bottom: 1rem;
        }

        .fase-dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            flex-shrink: 0;
        }

        .fase-titulo {
            font-size: 0.85rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            color: var(--text-secondary);
        }

        .fase-grid { display: flex; flex-direction: column; gap: 6px; }

        .leccion {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1rem 1.25rem;
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            transition: all 0.15s ease;
            box-shadow: var(--shadow);
        }

        .leccion:hover {
            box-shadow: var(--shadow-md);
            border-color: #d4d4d4;
            transform: translateY(-1px);
        }

        .leccion-num {
            font-family: 'JetBrains Mono', monospace;
            font-size: 0.8rem;
            font-weight: 500;
            color: var(--text-secondary);
            width: 28px;
            flex-shrink: 0;
        }

        .leccion-info { flex: 1; min-width: 0; }

        .leccion-info h3 {
            font-size: 0.95rem;
            font-weight: 500;
            color: var(--text);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .leccion-links { display: flex; gap: 6px; flex-shrink: 0; }

        .leccion-links a {
            text-decoration: none;
            padding: 0.4rem 0.85rem;
            border-radius: 8px;
            font-size: 0.78rem;
            font-weight: 600;
            transition: all 0.15s ease;
            letter-spacing: 0.01em;
        }

        .btn-teoria { background: #eff6ff; color: #2563eb; }
        .btn-teoria:hover { background: #dbeafe; }

        .btn-ejercicio { background: #f0fdf4; color: #16a34a; }
        .btn-ejercicio:hover { background: #dcfce7; }

        .btn-solucion { background: #fffbeb; color: #d97706; }
        .btn-solucion:hover { background: #fef3c7; }

        .footer {
            text-align: center;
            padding: 2rem;
            color: var(--text-secondary);
            font-size: 0.8rem;
            border-top: 1px solid var(--border);
        }

        .footer a { color: var(--accent); text-decoration: none; }
        .footer a:hover { text-decoration: underline; }

        @media (max-width: 640px) {
            .hero { padding: 2.5rem 1.5rem 2rem; }
            .hero h1 { font-size: 1.75rem; }
            .hero p { font-size: 1rem; }
            .stats { gap: 2rem; }
            .stat-num { font-size: 1.25rem; }
            .leccion { flex-wrap: wrap; padding: 0.85rem 1rem; }
            .leccion-links { width: 100%; margin-top: 0.25rem; }
            .leccion-num { width: 24px; }
        }
    </style>
</head>
<body>
    <div class="hero">
        <span class="hero-badge">PHP + Drupal 10</span>
        <h1>Curso de Cero a Experto</h1>
        <p>25 lecciones interactivas para dominar Drupal desde los fundamentos de PHP</p>
        <div class="stats">
            <div class="stat">
                <div class="stat-num">25</div>
                <div class="stat-label">Lecciones</div>
            </div>
            <div class="stat">
                <div class="stat-num">4</div>
                <div class="stat-label">Fases</div>
            </div>
            <div class="stat">
                <div class="stat-num"><?= count(glob('*/ejercicio.php')) ?></div>
                <div class="stat-label">Ejercicios</div>
            </div>
        </div>
    </div>

    <div class="container">
        <?php
        $fases = [
            ['titulo' => 'Fundamentos PHP', 'color' => 'var(--fase1)', 'lecciones' => [
                ['num' => '01', 'titulo' => 'Hola Mundo y Variables', 'dir' => '01-hola-mundo-y-variables', 'solucion' => true],
                ['num' => '02', 'titulo' => 'Tipos de datos y operadores', 'dir' => '02-tipos-de-datos-y-operadores', 'solucion' => true],
                ['num' => '03', 'titulo' => 'Estructuras de control', 'dir' => '03-estructuras-de-control', 'solucion' => true],
                ['num' => '04', 'titulo' => 'Arrays', 'dir' => '04-arrays', 'solucion' => true],
                ['num' => '05', 'titulo' => 'Funciones', 'dir' => '05-funciones', 'solucion' => true],
                ['num' => '06', 'titulo' => 'Clases y Objetos (OOP)', 'dir' => '06-clases-y-objetos', 'solucion' => true],
                ['num' => '07', 'titulo' => 'Namespaces y Composer', 'dir' => '07-namespaces-y-composer', 'solucion' => true],
            ]],
            ['titulo' => 'Fundamentos Drupal', 'color' => 'var(--fase2)', 'lecciones' => [
                ['num' => '08', 'titulo' => 'Arquitectura de Drupal', 'dir' => '08-arquitectura-drupal'],
                ['num' => '09', 'titulo' => 'Instalacion de Drupal (DDEV)', 'dir' => '09-instalacion-drupal'],
                ['num' => '10', 'titulo' => 'Tu primer modulo custom', 'dir' => '10-primer-modulo'],
                ['num' => '11', 'titulo' => 'Routing, Controllers y Services', 'dir' => '11-routing-y-controllers'],
                ['num' => '12', 'titulo' => 'Hooks y Eventos', 'dir' => '12-hooks-y-eventos'],
            ]],
            ['titulo' => 'Desarrollo intermedio', 'color' => 'var(--fase3)', 'lecciones' => [
                ['num' => '13', 'titulo' => 'Form API (formularios)', 'dir' => '13-form-api'],
                ['num' => '14', 'titulo' => 'Entity API y campos', 'dir' => '14-entity-api'],
                ['num' => '15', 'titulo' => 'Database API', 'dir' => '15-database-api'],
                ['num' => '16', 'titulo' => 'Bloques custom (Block Plugin)', 'dir' => '16-bloques-custom'],
                ['num' => '17', 'titulo' => 'Twig y Theming', 'dir' => '17-twig-y-theming'],
                ['num' => '18', 'titulo' => 'Temas custom', 'dir' => '18-temas-custom'],
            ]],
            ['titulo' => 'Avanzado', 'color' => 'var(--fase4)', 'lecciones' => [
                ['num' => '19', 'titulo' => 'Plugin API en profundidad', 'dir' => '19-plugin-api'],
                ['num' => '20', 'titulo' => 'Eventos avanzados', 'dir' => '20-eventos-avanzados'],
                ['num' => '21', 'titulo' => 'Custom Entities', 'dir' => '21-custom-entities'],
                ['num' => '22', 'titulo' => 'REST API y servicios web', 'dir' => '22-rest-api'],
                ['num' => '23', 'titulo' => 'Testing (PHPUnit)', 'dir' => '23-testing'],
                ['num' => '24', 'titulo' => 'Drush y herramientas', 'dir' => '24-drush-y-herramientas'],
                ['num' => '25', 'titulo' => 'Deploy y Config Management', 'dir' => '25-deploy-y-config-management'],
            ]],
        ];

        foreach ($fases as $i => $fase): ?>
            <div class="fase">
                <div class="fase-header">
                    <div class="fase-dot" style="background: <?= $fase['color'] ?>"></div>
                    <h2 class="fase-titulo">Fase <?= $i + 1 ?> &mdash; <?= $fase['titulo'] ?></h2>
                </div>
                <div class="fase-grid">
                    <?php foreach ($fase['lecciones'] as $l): ?>
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
            </div>
        <?php endforeach; ?>
    </div>

    <div class="footer">
        <p>Creado con <a href="https://github.com/jmvictoriom/curso-drupal-php" target="_blank">Claude</a> &mdash; <?= date('Y') ?></p>
    </div>
</body>
</html>
