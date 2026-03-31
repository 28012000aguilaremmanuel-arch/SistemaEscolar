<?php 
require '../config/db.php'; 
if($_SESSION['user_tipo'] != 'directora') header('Location: ../login.php');
$total_estudiantes = $pdo->query("SELECT COUNT(*) FROM estudiantes")->fetchColumn();
$total_califs = $pdo->query("SELECT COUNT(*) FROM calificaciones")->fetchColumn();
$promedio_general = $pdo->query("SELECT AVG(calificacion) FROM calificaciones")->fetchColumn();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - Directora</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <nav class="navbar">
        <h2>👩‍💼 Bienvenida, <?php echo $_SESSION['user_nombre']; ?></h2>
        <div class="nav-buttons">
            <a href="estudiantes.php" class="nav-btn">📚 Estudiantes</a>
            <a href="calificaciones.php" class="nav-btn">📊 Calificaciones</a>
            <a href="logout.php" class="btn-logout">🚪 Cerrar</a>
        </div>
    </nav>
    
    <div class="dashboard-grid">
        <!-- Cards Estadísticas -->
        <div class="stat-card primary">
            <div class="stat-icon">👥</div>
            <div class="stat-info">
                <h3><?php echo $total_estudiantes; ?></h3>
                <p>Total Estudiantes</p>
            </div>
        </div>
        
        <div class="stat-card success">
            <div class="stat-icon">📊</div>
            <div class="stat-info">
                <h3><?php echo $total_califs; ?></h3>
                <p>Calificaciones</p>
            </div>
        </div>
        
        <div class="stat-card warning">
            <div class="stat-icon">📈</div>
            <div class="stat-info">
                <h3><?php echo $promedio_general ? round($promedio_general,1) : '0'; ?></h3>
                <p>Promedio General</p>
            </div>
        </div>
    </div>
</body>
</html>