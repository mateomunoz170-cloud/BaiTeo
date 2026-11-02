<?php
include '../../Php/conexion.php';

$mensaje_exito = "";
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['accion']) && $_POST['accion'] == 'analitico_incompleto') {
    $nombre = trim($_POST['nombre']);
    $dni = trim($_POST['dni']);
    $legajo = trim($_POST['legajo']);
    $curso = trim($_POST['curso']);
    $email = trim($_POST['email']);
    $consulta = trim($_POST['consulta']);

    if (!empty($nombre) && !empty($dni) && !empty($curso) && !empty($email) && !empty($consulta)) {
        $sql = "INSERT INTO consultas_analitico_incompleto (nombre_completo, dni, legajo, curso_actual, email, detalle_consulta) VALUES (:nombre, :dni, :legajo, :curso, :email, :consulta)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'nombre' => $nombre,
            'dni' => $dni,
            'legajo' => $legajo,
            'curso' => $curso,
            'email' => $email,
            'consulta' => $consulta
        ]);
        $mensaje_exito = "✔ Consulta enviada correctamente. Te vamos a responder por email.";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consulta de Analítico Incompleto</title>
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
        <div class="breadcrumb"><a href="../../index.html">Inicio</a> / <a href="portal-alumnos.html">Portal Alumnos</a> / Consulta de Analítico Incompleto</div>

        <section class="content-box form-box">
            <h2 class="section-title">Consulta de Analítico Incompleto</h2>
            <div class="form-note">Usá este formulario si todavía estás cursando o adeudás materias y necesitás consultar el estado de tu analítico.</div>

            <form class="tramite-form" action="" method="POST">
                <input type="hidden" name="accion" value="analitico_incompleto">
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
                        <label for="curso">Curso / Año que cursás actualmente</label>
                        <input type="text" id="curso" name="curso" required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="email">Email de contacto</label>
                    <input type="email" id="email" name="email" required>
                </div>

                <div class="form-group">
                    <label for="consulta">Detalle de la consulta</label>
                    <textarea id="consulta" name="consulta" placeholder="Ej: materias pendientes, qué necesitás saber sobre tu analítico, etc." required></textarea>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">Enviar consulta</button>
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