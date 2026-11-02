<?php
session_start();

$error = "";

// Credenciales de administrador (puedes cambiarlas aquí cuando gustes)
$usuario_admin = "admin";
$password_admin = "cet12026";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $usuario = trim($_POST['usuario']);
    $password = trim($_POST['password']);

    if ($usuario === $usuario_admin && $password === $password_admin) {
        // Autenticación correcta, creamos la sesión
        $_SESSION['admin_logueado'] = true;
        header("Location: admin.php");
        exit();
    } else {
        $error = "Usuario o contraseña incorrectos.";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Administración</title>
    <link rel="stylesheet" href="../common.css">
    <link rel="stylesheet" href="Foro.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="shortcut icon" href="../escudo.png" type="image/x-icon">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        .login-card {
            max-width: 400px;
            margin: 3rem auto;
            background-color: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 14px;
            padding: 2rem;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05);
        }
        .error-msg {
            background-color: #fee2e2;
            border: 1px solid #fca5a5;
            color: #dc2626;
            padding: 0.75rem;
            border-radius: 6px;
            font-size: 0.9rem;
            margin-bottom: 1rem;
            text-align: center;
        }
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
                    <li><a href="Foro.php">Ver Foro</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <main>
        <div class="breadcrumb"><a href="../index.html">Inicio</a> / <a href="Foro.php">Foro</a> / Login</div>

        <section class="login-card">
            <h2 style="font-size: 1.3rem; margin-bottom: 0.5rem; color: #1e293b;">Panel de Control</h2>
            <p style="font-size: 0.9rem; color: #64748b; margin-bottom: 1.5rem;">Ingresa tus credenciales de moderador.</p>

            <?php if (!empty($error)): ?>
                <div class="error-msg"><?php echo $error; ?></div>
            <?php endif; ?>

            <form action="" method="POST">
                <div class="form-group" style="margin-bottom: 1rem;">
                    <label style="display: block; font-size: 0.85rem; font-weight: 500; color: #475569; margin-bottom: 0.3rem;">Usuario</label>
                    <input type="text" name="usuario" required autocomplete="off" style="width: 100%; padding: 0.75rem; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 0.95rem;">
                </div>
                <div class="form-group" style="margin-bottom: 1.5rem;">
                    <label style="display: block; font-size: 0.85rem; font-weight: 500; color: #475569; margin-bottom: 0.3rem;">Contraseña</label>
                    <input type="password" name="password" required style="width: 100%; padding: 0.75rem; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 0.95rem;">
                </div>
                <button type="submit" class="btn btn-primary" style="width: 100%; padding: 0.75rem;">Iniciar Sesión</button>
            </form>
        </section>
    </main>

    <footer>
        <p>C.E.T. N°1 General Roca — La Pampa 1020 — Formando técnicos desde 1938</p>
    </footer>

</body>
</html>