<?php
include '../../Php/conexion.php';

$mensaje_exito = "";
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['accion']) && $_POST['accion'] == 'analitico_final') {
    $nombre = trim($_POST['nombre']);
    $dni = trim($_POST['dni']);
    $legajo = trim($_POST['legajo']);
    $anio_egreso = trim($_POST['anio-egreso']);
    $titulo = trim($_POST['titulo']);
    $email = trim($_POST['email']);
    $motivo = trim($_POST['motivo']);

    if (!empty($nombre) && !empty($dni) && !empty($anio_egreso) && !empty($titulo) && !empty($email) && !empty($motivo)) {
        $sql = "INSERT INTO solicitudes_analitico_final (nombre_completo, dni, legajo, anio_egreso, titulo_obtenido, email, motivo_solicitud) VALUES (:nombre, :dni, :legajo, :anio, :titulo, :email, :motivo)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'nombre' => $nombre,
            'dni' => $dni,
            'legajo' => $legajo,
            'anio' => $anio_egreso,
            'titulo' => $titulo,
            'email' => $email,
            'motivo' => $motivo
        ]);
        $mensaje_exito = "✔ Solicitud enviada correctamente. Te contactaremos por email.";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consulta de Analítico Final de Carrera</title>
    <link rel="stylesheet" href="../../common.css">
    <link rel="stylesheet" href="formularios.css">
    <link rel="shortcut icon" href="../../escudo.png" type="image/x-icon">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap" rel="stylesheet">
    <style>
        .form-success {
            display: <?php echo !empty($mensaje_exito) ? 'block' : 'none'; ?>;
            background-color: #d1fae5;
            color: #065f46;
            padding: 0.75rem;
            border-radius: 6px;
            margin-top: 1rem;
            font-weight: 500;
        }
    </style>
</head>
<body>
    <header>
        <div class="nav-container">
            <div class="logo">
                <img src="../../escudo.png" alt="Escudo C.E.T. N°1" class="logo-img">
                C.E.T. N°1
            </div>
            <nav>
                <ul>
                    <li><a href="../../index.html">Inicio</a></li>
                    <li class="dropdown">
                    <a href="../Nosotros.html">Nosotros</a>
                    <ul class="submenu">
                        <li><a href="portal-alumnos.html">Portal Alumnos</a></li>
                        <li><a href="../Portal Docentes/portal-docentes.html">Portal Docentes</a></li>
                    </ul>
                    </li>
                    <li><a href="../../Proyectos/Proyectos.html">Proyectos</a></li>
                    <li><a href="../../Php/foro.php">Foro</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <main>
        <div class="breadcrumb"><a href="../../index.html">Inicio</a> / <a href="portal-alumnos.html">Portal Alumnos</a> / Consulta de Analítico Final de Carrera</div>

        <section class="content-box form-box">
            <h2 class="section-title">Consulta de Analítico Final de Carrera</h2>
            <div class="form-note">Para egresados/as que necesitan consultar o solicitar su analítico final de carrera.</div>

            <form class="tramite-form" action="" method="POST">
                <input type="hidden" name="accion" value="analitico_final">
                <div class="form-row">
                    <div class="form-group">
                        <label for="nombre">Nombre completo</label>
                        <input type="text" id="nombre" name="nombre" required>
                    </div>
                    <div class="form-group">
                        <label for="dni">DNI</label>
                        <input type="text" id="dni" name="dni" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="legajo">N° de Legajo</label>
                        <input type="text" id="legajo" name="legajo">
                    </div>
                    <div class="form-group">
                        <label for="anio-egreso">Año de egreso</label>
                        <input type="text" id="anio-egreso" name="anio-egreso" required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="titulo">Título obtenido</label>
                    <select id="titulo" name="titulo" required>
                        <option value="">Seleccioná una opción</option>
                        <option value="Técnico en Informática Profesional y Personal">Técnico en Informática Profesional y Personal</option>
                        <option value="Técnico Mecánico">Técnico Mecánico</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="email">Email de contacto</label>
                    <input type="email" id="email" name="email" required>
                </div>

                <div class="form-group">
                    <label for="motivo">Motivo de la solicitud</label>
                    <textarea id="motivo" name="motivo" placeholder="Ej: trámite universitario, laboral, duplicado, etc." required></textarea>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">Enviar solicitud</button>
                    <a href="portal-alumnos.html" class="btn btn-secondary">Volver</a>
                </div>

                <div class="form-success"><?php echo $mensaje_exito; ?></div>
            </form>
        </section>
    </main>

    <footer>
        <p>C.E.T. N°1 General Roca — La Pampa 1020 — Formando técnicos desde 1938</p>
    </footer>
</body>
</html>