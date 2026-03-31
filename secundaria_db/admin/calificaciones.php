<?php 
require '../config/db.php'; 
if($_SESSION['user_tipo'] != 'directora') header('Location: ../login.php');

if(isset($_POST['guardar_calif'])) {
    $stmt = $pdo->prepare("INSERT INTO calificaciones (estudiante_id, materia, periodo, calificacion, comentarios) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$_POST['estudiante_id'], $_POST['materia'], $_POST['periodo'], $_POST['calificacion'], $_POST['comentarios']]);
}

$estudiantes = $pdo->query("SELECT * FROM estudiantes ORDER BY apellido_paterno")->fetchAll();
$calificaciones = $pdo->query("SELECT c.*, e.nombre, e.apellido_paterno, e.apellido_materno FROM calificaciones c JOIN estudiantes e ON c.estudiante_id = e.id ORDER BY e.apellido_paterno, c.periodo DESC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <title>Calificaciones</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <nav class="navbar">
        <h2>📊 Calificaciones</h2>
        <div class="nav-buttons">
            <a href="dashboard.php" class="nav-btn secondary">← Dashboard</a>
            <a href="estudiantes.php" class="nav-btn">📚 Estudiantes</a>
            <a href="logout.php" class="btn-logout">🚪</a>
        </div>
    </nav>

    <!-- Formulario Nueva Calificación -->
    <div class="form-card">
        <h3>➕ Nueva Calificación</h3>
        <form method="POST" class="form-grid">
            <select name="estudiante_id" required>
                <option value="">👤 Seleccionar Estudiante</option>
                <?php foreach($estudiantes as $est): ?>
                <option value="<?php echo $est['id']; ?>">
                    <?php echo $est['nombre'].' '.$est['apellido_paterno'].' '.$est['apellido_materno']; ?>
                </option>
                <?php endforeach; ?>
            </select>
            <input type="text" name="materia" placeholder="📚 Materia" required>
            <input type="number" name="periodo" placeholder="📅 Periodo (1-5)" min="1" max="5" required>
            <input type="number" name="calificacion" placeholder="🎯 Calificación (0-10)" step="0.1" min="0" max="10" required>
            <textarea name="comentarios" placeholder="💬 Comentarios"></textarea>
            <button type="submit" name="guardar_calif" class="btn-primary full">💾 Guardar</button>
        </form>
    </div>

    <!-- Tabla Calificaciones Recientes -->
    <div class="table-card">
        <div class="table-header">
            <h3>📋 Calificaciones Registradas</h3>
            <span class="badge success"><?php echo count($calificaciones); ?> total</span>
        </div>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Estudiante</th>
                        <th>Materia</th>
                        <th>Periodo</th>
                        <th>Calificación</th>
                        <th>Comentarios</th>
                        <th>Fecha</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach(array_slice($calificaciones, 0, 20) as $calif): ?>
                    <tr>
                        <td>
                            <strong><?php echo $calif['nombre'].' '.$calif['apellido_paterno']; ?></strong>
                        </td>
                        <td><?php echo $calif['materia']; ?></td>
                        <td><span class="badge"><?php echo $calif['periodo']; ?></span></td>
                        <td>
                            <span class="grade <?php echo $calif['calificacion']>=8 ? 'excellent' : ($calif['calificacion']>=6 ? 'good' : 'bad'); ?>">
                                <?php echo $calif['calificacion']; ?>
                            </span>
                        </td>
                        <td><?php echo $calif['comentarios'] ?: '—'; ?></td>
                        <td><?php echo date('d/m', strtotime($calif['fecha'])); ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>