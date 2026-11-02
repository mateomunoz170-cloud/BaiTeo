<?php
include 'conexion.php';

// Procesar cuando se guarda un comentario nuevo
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['accion']) && $_POST['accion'] == 'guardar_comentario') {
    $nombre = trim($_POST['nombre']);
    $texto = trim($_POST['texto']);

    if (!empty($nombre) && !empty($texto)) {
        $sql = "INSERT INTO comentarios (nombre, texto, likes) VALUES (:nombre, :texto, 0)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['nombre' => $nombre, 'texto' => $texto]);
    }
    
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// Consultar comentarios recientes
$stmtComentarios = $pdo->query("SELECT * FROM comentarios ORDER BY id_comentario DESC");
$comentarios = $stmtComentarios->fetchAll();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Foro</title>
    <link rel="stylesheet" href="../common.css">
    <link rel="stylesheet" href="Foro.css?v=4">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="shortcut icon" href="../escudo.png" type="image/x-icon">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
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
                    <li class="dropdown">
                        <a href="../Nosotros/nosotros.html">Nosotros</a>
                        <ul class="submenu">
                            <li><a href="../Nosotros/Portal Alumnos/portal-alumnos.html">Portal Alumnos</a></li>
                            <li><a href="../Nosotros/Portal Docentes/portal-docentes.html">Portal Docentes</a></li>
                        </ul>
                    </li>
                    <li><a href="../Proyectos/Proyectos.html">Proyectos</a></li>
                    <li><a href="Foro.php" class="active">Foro</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <main>
        <div class="breadcrumb"><a href="../index.html">Inicio</a> / Foro</div>

        <section class="intro-section">
            <h2 class="section-title">Foro Institucional</h2>
            <p class="lead-text">Espacio para comentar, dejar ideas y debatir con la comunidad del C.E.T. N°1.</p>
        </section>

        <!-- Contenedor Principal del Foro -->
        <section class="foro-container">
            
            <!-- Zona Superior: Formulario -->
            <div class="foro-form-box">
                <h3>💬 Deja tu comentario</h3>
                <form action="" method="POST">
                    <input type="hidden" name="accion" value="guardar_comentario">
                    <div class="form-group">
                        <input type="text" name="nombre" placeholder="Tu nombre y apellido" required autocomplete="off">
                    </div>
                    <div class="form-group">
                        <textarea name="texto" rows="3" placeholder="Escribe tu mensaje aquí..." required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Publicar mensaje</button>
                </form>
            </div>

            <!-- Zona Inferior: Lista de comentarios -->
            <div class="foro-mensajes-box">
                <h3>Publicaciones recientes</h3>
                <div class="mensajes-lista">
                    <?php if (empty($comentarios)): ?>
                        <div class="empty-state">
                            <p>Todavía no hay comentarios. ¡Sé el primero en escribir uno! 🚀</p>
                        </div>
                    <?php else: ?>
                        <?php foreach ($comentarios as $c): ?>
                            <div class="comentario-burbuja">
                                <div class="burbuja-avatar">
                                    <?php echo strtoupper(substr(trim($c['nombre']), 0, 1)); ?>
                                </div>
                                <div class="burbuja-contenido">
                                    <div class="burbuja-header">
                                        <strong><?php echo htmlspecialchars($c['nombre']); ?></strong>
                                    </div>
                                    <div class="burbuja-texto">
                                        <p><?php echo nl2br(htmlspecialchars($c['texto'])); ?></p>
                                    </div>
                                    <div class="burbuja-footer">
                                        <!-- Botón con atributo data-id para controlarlo por JS -->
                                        <button type="button" class="btn-like" data-id="<?php echo $c['id_comentario']; ?>">
                                            ❤️ <span class="like-count"><?php echo $c['likes']; ?></span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>

        </section>
    </main>

    <footer>
        <p>C.E.T. N°1 General Roca — La Pampa 1020 — Formando técnicos desde 1938</p>
    </footer>

  <!-- Script para alternar like (dar y quitar) controlado por localStorage -->
    <script>
    document.addEventListener("DOMContentLoaded", function() {
        let likesDados = JSON.parse(localStorage.getItem('foro_likes')) || [];

        document.querySelectorAll('.btn-like').forEach(button => {
            let idComentario = button.getAttribute('data-id');
            
            // Si ya votó antes, marcarlo visualmente como activo
            if (likesDados.includes(idComentario)) {
                button.classList.add('liked');
            }

            button.addEventListener('click', function() {
                // Determinar si ya tiene like o no
                let yaDioLike = likesDados.includes(idComentario);
                let accion = yaDioLike ? 'quitar' : 'dar';

                // Petición a PHP
                let formData = new FormData();
                formData.append('id_comentario', idComentario);
                formData.append('accion_like', accion);

                fetch('dar_like.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Actualizar número en pantalla
                        button.querySelector('.like-count').textContent = data.nuevos_likes;
                        
                        if (accion === 'dar') {
                            // Agregar el like
                            button.classList.add('liked');
                            likesDados.push(idComentario);
                        } else {
                            // Quitar el like
                            button.classList.remove('liked');
                            likesDados = likesDados.filter(id => id !== idComentario);
                        }

                        // Guardar actualización en la memoria del navegador
                        localStorage.setItem('foro_likes', JSON.stringify(likesDados));
                    }
                })
                .catch(error => console.error('Error:', error));
            });
        });
    });
    </script>
</body>
</html>