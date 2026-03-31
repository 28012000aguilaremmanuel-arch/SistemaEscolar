<?php
require '../config/db.php';

$id = $_GET['id'] ?? 0;

$stmt = $pdo->prepare("SELECT * FROM estudiantes WHERE id = ?");
$stmt->execute([$id]);
$est = $stmt->fetch();

if(!$est){
    echo "Estudiante no encontrado";
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <title>Ver Estudiante</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

<div class="form-card">
    <h2>👤 Perfil del Estudiante</h2>

    <p><strong>Nombre:</strong> <?php echo $est['nombre']; ?></p>
    <p><strong>Apellido:</strong> <?php echo $est['apellido_paterno'].' '.$est['apellido_materno']; ?></p>
    <p><strong>CURP:</strong> <?php echo $est['curp']; ?></p>
    <p><strong>Grado/Grupo:</strong> <?php echo $est['grado'].' '.$est['grupo']; ?></p>
    <p><strong>Tutor:</strong> <?php echo $est['tutor']; ?></p>

    <br>
    <a href="estudiantes.php" class="btn-primary">← Volver</a>
</div>

</body>
</html>