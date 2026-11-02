<?php
include 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id_comentario']) && isset($_POST['accion_like'])) {
    $id_comentario = $_POST['id_comentario'];
    $accion_like = $_POST['accion_like'];

    if ($accion_like === 'dar') {
        // Sumar 1 like
        $sql = "UPDATE comentarios SET likes = likes + 1 WHERE id_comentario = :id";
    } else {
        // Restar 1 like (asegurándonos de que nunca baje de 0)
        $sql = "UPDATE comentarios SET likes = GREATEST(0, likes - 1) WHERE id_comentario = :id";
    }

    $stmt = $pdo->prepare($sql);
    $stmt->execute(['id' => $id_comentario]);

    // Consultar el total actualizado
    $stmtCheck = $pdo->prepare("SELECT likes FROM comentarios WHERE id_comentario = :id");
    $stmtCheck->execute(['id' => $id_comentario]);
    $resultado = $stmtCheck->fetch();

    echo json_encode([
        'success' => true,
        'nuevos_likes' => $resultado['likes']
    ]);
    exit();
}