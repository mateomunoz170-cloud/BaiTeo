<?php
session_start();

if (!isset($_SESSION['admin_logueado']) || $_SESSION['admin_logueado'] !== true) {
    header("Location: login.php");
    exit();
}

include 'conexion.php';

// Procesar eliminaciones según la tabla
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['accion'])) {
    $id = $_POST['id'];
    $tabla = $_POST['tabla'];

    $tablas_permitidas = [
        'comentarios' => 'id_comentario',
        'inscripciones_mesas' => 'id',
        'consultas_analitico_incompleto' => 'id',
        'solicitudes_analitico_final' => 'id'
    ];

    if (array_key_exists($tabla, $tablas_permitidas)) {
        $id_col = $tablas_permitidas[$tabla];
        $sql = "DELETE FROM $tabla WHERE $id_col = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['id' => $id]);
    }

    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// Consultas
$comentarios = $pdo->query("SELECT * FROM comentarios ORDER BY id_comentario DESC")->fetchAll();
$mesas = $pdo->query("SELECT * FROM inscripciones_mesas ORDER BY id DESC")->fetchAll();
$incompletos = $pdo->query("SELECT * FROM consultas_analitico_incompleto ORDER BY id DESC")->fetchAll();
$finales = $pdo->query("SELECT * FROM solicitudes_analitico_final ORDER BY id DESC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administración</title>
    <link rel="stylesheet" href="../common.css">
    <link rel="stylesheet" href="Foro.css?v=5">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="shortcut icon" href="../escudo.png" type="image/x-icon">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        .admin-section { margin-bottom: 3rem; }
        .admin-section h3 { border-bottom: 2px solid #e2e8f0; padding-bottom: 0.5rem; margin-bottom: 1.5rem; color: #1e293b; }
        .tramite-card { background: #f8fafc; border: 1px solid #e2e8f0; padding: 1.2rem; border-radius: 12px; margin-bottom: 1rem; }
        .tramite-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.5rem; }
        .tramite-body p { font-size: 0.95rem; color: #334155; margin: 0.2rem 0; }
    </style>
</head>
<body>

    <header>
        <div class="nav-container">
            <div class="logo">
                <img src="../escudo.png" alt="Escudo C.E.T. N°1" class="logo-img">
                C.E.T. N°1
            </div>
            <nav>
                <ul>
                    <li><a href="../index.html">Inicio</a></li>
                    <li><a href="foro.php">Ver Foro</a></li>
                    <li><a href="logout.php" class="btn-logout">Cerrar Sesión</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <main>
        <div class="breadcrumb"><a href="../index.html">Inicio</a> / Panel Admin</div>

        <section class="intro-section">
            <h2 class="section-title">Panel de Moderación General</h2>
            <p class="lead-text">Administra comentarios del foro y solicitudes enviadas desde el Portal de Alumnos.</p>
        </section>

        <section class="foro-container">
            
            <!-- SECCIÓN 1: FORO -->
            <div class="admin-section">
                <h3>💬 Comentarios del Foro (<?php echo count($comentarios); ?>)</h3>
                <div class="mensajes-lista">
                    <?php if (empty($comentarios)): ?>
                        <p style="color: #64748b;">No hay comentarios.</p>
                    <?php else: ?>
                        <?php foreach ($comentarios as $c): ?>
                            <div class="comentario-burbuja">
                                <div class="burbuja-avatar"><?php echo strtoupper(substr(trim($c['nombre']), 0, 1)); ?></div>
                                <div class="burbuja-contenido">
                                    <div class="burbuja-header">
                                        <strong><?php echo htmlspecialchars($c['nombre']); ?></strong>
                                        <span class="admin-badge">ID: #<?php echo $c['id_comentario']; ?></span>
                                    </div>
                                    <div class="burbuja-texto"><p><?php echo nl2br(htmlspecialchars($c['texto'])); ?></p></div>
                                    <div class="burbuja-footer-admin">
                                        <span class="likes-counter">❤️ <?php echo $c['likes']; ?> likes</span>
                                        <form action="" method="POST" onsubmit="return confirm('¿Eliminar comentario?');">
                                            <input type="hidden" name="tabla" value="comentarios">
                                            <input type="hidden" name="id" value="<?php echo $c['id_comentario']; ?>">
                                            <button type="submit" name="accion" value="eliminar" class="btn-eliminar">🗑️ Eliminar</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>

            <!-- SECCIÓN 2: INSCRIPCIONES A MESAS -->
            <div class="admin-section">
                <h3>📝 Inscripciones a Mesas de Examen (<?php echo count($mesas); ?>)</h3>
                <?php if (empty($mesas)): ?>
                    <p style="color: #64748b;">No hay inscripciones registradas.</p>
                <?php else: ?>
                    <?php foreach ($mesas as $m): ?>
                        <div class="tramite-card">
                            <div class="tramite-header">
                                <strong><?php echo htmlspecialchars($m['nombre_completo']); ?> (DNI: <?php echo htmlspecialchars($m['dni']); ?>)</strong>
                                <span class="admin-badge"><?php echo htmlspecialchars($m['turno_examen']); ?></span>
                            </div>
                            <div class="tramite-body">
                                <p><strong>Curso:</strong> <?php echo htmlspecialchars($m['curso_anio']); ?> | <strong>Legajo:</strong> <?php echo htmlspecialchars($m['legajo']); ?></p>
                                <p><strong>Materia:</strong> <?php echo htmlspecialchars($m['materia']); ?></p>
                                <p><strong>Email:</strong> <?php echo htmlspecialchars($m['email']); ?></p>
                                <?php if(!empty($m['observaciones'])): ?><p><strong>Obs:</strong> <?php echo htmlspecialchars($m['observaciones']); ?></p><?php endif; ?>
                            </div>
                            <div style="text-align: right; margin-top: 0.8rem;">
                                <form action="" method="POST" onsubmit="return confirm('¿Eliminar esta inscripción?');">
                                    <input type="hidden" name="tabla" value="inscripciones_mesas">
                                    <input type="hidden" name="id" value="<?php echo $m['id']; ?>">
                                    <button type="submit" name="accion" value="eliminar" class="btn-eliminar">🗑️ Eliminar / Procesado</button>
                                </form>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <!-- SECCIÓN 3: ANALÍTICO INCOMPLETO -->
            <div class="admin-section">
                <h3>📂 Consultas de Analítico Incompleto (<?php echo count($incompletos); ?>)</h3>
                <?php if (empty($incompletos)): ?>
                    <p style="color: #64748b;">No hay consultas registradas.</p>
                <?php else: ?>
                    <?php foreach ($incompletos as $i): ?>
                        <div class="tramite-card">
                            <div class="tramite-header">
                                <strong><?php echo htmlspecialchars($i['nombre_completo']); ?> (DNI: <?php echo htmlspecialchars($i['dni']); ?>)</strong>
                            </div>
                            <div class="tramite-body">
                                <p><strong>Curso Actual:</strong> <?php echo htmlspecialchars($i['curso_actual']); ?> | <strong>Legajo:</strong> <?php echo htmlspecialchars($i['legajo']); ?></p>
                                <p><strong>Email:</strong> <?php echo htmlspecialchars($i['email']); ?></p>
                                <p><strong>Consulta:</strong> <?php echo htmlspecialchars($i['detalle_consulta']); ?></p>
                            </div>
                            <div style="text-align: right; margin-top: 0.8rem;">
                                <form action="" method="POST" onsubmit="return confirm('¿Eliminar esta consulta?');">
                                    <input type="hidden" name="tabla" value="consultas_analitico_incompleto">
                                    <input type="hidden" name="id" value="<?php echo $i['id']; ?>">
                                    <button type="submit" name="accion" value="eliminar" class="btn-eliminar">🗑️ Eliminar / Procesado</button>
                                </form>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <!-- SECCIÓN 4: ANALÍTICO FINAL -->
            <div class="admin-section">
                <h3>🎓 Solicitudes de Analítico Final (<?php echo count($finales); ?>)</h3>
                <?php if (empty($finales)): ?>
                    <p style="color: #64748b;">No hay solicitudes registradas.</p>
                <?php else: ?>
                    <?php foreach ($finales as $f): ?>
                        <div class="tramite-card">
                            <div class="tramite-header">
                                <strong><?php echo htmlspecialchars($f['nombre_completo']); ?> (DNI: <?php echo htmlspecialchars($f['dni']); ?>)</strong>
                                <span class="admin-badge"><?php echo htmlspecialchars($f['titulo_obtenido']); ?></span>
                            </div>
                            <div class="tramite-body">
                                <p><strong>Año de Egreso:</strong> <?php echo htmlspecialchars($f['anio_egreso']); ?> | <strong>Legajo:</strong> <?php echo htmlspecialchars($f['legajo']); ?></p>
                                <p><strong>Email:</strong> <?php echo htmlspecialchars($f['email']); ?></p>
                                <p><strong>Motivo:</strong> <?php echo htmlspecialchars($f['motivo_solicitud']); ?></p>
                            </div>
                            <div style="text-align: right; margin-top: 0.8rem;">
                                <form action="" method="POST" onsubmit="return confirm('¿Eliminar esta solicitud?');">
                                    <input type="hidden" name="tabla" value="solicitudes_analitico_final">
                                    <input type="hidden" name="id" value="<?php echo $f['id']; ?>">
                                    <button type="submit" name="accion" value="eliminar" class="btn-eliminar">🗑️ Eliminar / Procesado</button>
                                </form>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

        </section>
    </main>

    <footer>
        <p>C.E.T. N°1 General Roca — La Pampa 1020 — Formando técnicos desde 1938</p>
    </footer>
</body>
</html>