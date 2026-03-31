<?php require '../config/db.php'; 
$busqueda = $_GET['busqueda'] ?? '';

$stmt = $pdo->prepare("
    SELECT e.*, AVG(c.calificacion) as promedio 
    FROM estudiantes e 
    LEFT JOIN calificaciones c ON e.id = c.estudiante_id 
    WHERE CONCAT(e.nombre, ' ', e.apellido_paterno, ' ', e.apellido_materno) LIKE ? 
       OR e.curp LIKE ?
    GROUP BY e.id
    ORDER BY e.apellido_paterno, e.nombre
");
$stmt->execute(["%$busqueda%", "%$busqueda%"]);
$estudiantes = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>📊 Resultados - Padres</title>
    <link rel="stylesheet" href="../css/style.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="results-body">
    <!-- Header -->
    <nav class="results-nav">
        <div class="nav-left">
            <a href="buscar.php" class="nav-back">
                <i class="fas fa-arrow-left"></i> Nueva búsqueda
            </a>
        </div>
        <div class="nav-center">
            <h2><i class="fas fa-chart-bar"></i> Resultados</h2>
        </div>
    </nav>

    <div class="results-container">
        <?php if(empty($estudiantes)): ?>
            <!-- No resultados -->
            <div class="empty-state">
                <i class="fas fa-search fa-5x"></i>
                <h3>No se encontraron estudiantes</h3>
                <p>Verifica el nombre o CURP</p>
                <a href="buscar.php" class="btn-primary">🔍 Buscar de nuevo</a>
            </div>
        <?php else: ?>
            <!-- Resultados -->
            <?php foreach($estudiantes as $est): ?>
            <div class="student-profile">
                <!-- Header estudiante -->
                <div class="profile-header">
                    <div class="profile-avatar">
                        <?php echo strtoupper(substr($est['nombre'],0,1).substr($est['apellido_paterno'],0,1)); ?>
                    </div>
                    <div class="profile-info">
                        <h3><?php echo $est['nombre'].' '.$est['apellido_paterno'].' '.$est['apellido_materno']; ?></h3>
                        <div class="profile-meta">
                            <span><i class="fas fa-school"></i> <?php echo $est['grado'].' '.$est['grupo']; ?></span>
                            <span><i class="fas fa-user"></i> <?php echo $est['tutor'] ?: 'N/A'; ?></span>
                        </div>
                    </div>
                </div>
                
                <!-- Promedio principal -->
                <div class="promedio-main">
                    <?php 
                    $promedio = $est['promedio'] ? round($est['promedio'],1) : 0;
                    $color = $promedio >= 8 ? 'success' : ($promedio >= 6 ? 'warning' : 'danger');
                    ?>
                    <div class="promedio-circle <?php echo $color; ?>">
                        <span class="promedio-num"><?php echo $promedio; ?></span>
                        <span class="promedio-label">Promedio</span>
                    </div>
                </div>
                
                <!-- Calificaciones detalle -->
                <?php
                $califs = $pdo->prepare("SELECT * FROM calificaciones WHERE estudiante_id = ? ORDER BY periodo, materia");
                $califs->execute([$est['id']]);
                $calificaciones = $califs->fetchAll();
                ?>
                
                <?php if($calificaciones): ?>
                <div class="calificaciones-section">
                    <h4><i class="fas fa-list"></i> Calificaciones por materia</h4>
                    <div class="calif-grid">
                        <?php foreach($calificaciones as $calif): ?>
                        <div class="calif-card">
                            <div class="calif-header">
                                <span class="materia"><?php echo $calif['materia']; ?></span>
                                <span class="periodo">P<?php echo $calif['periodo']; ?></span>
                            </div>
                            <div class="calif-grade">
                                <?php echo $calif['calificacion']; ?>/10
                            </div>
                            <?php if($calif['comentarios']): ?>
                            <div class="calif-comment">
                                <i class="fas fa-comment"></i>
                                <?php echo $calif['comentarios']; ?>
                            </div>
                            <?php endif; ?>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php else: ?>
                <div class="empty-state small">
                    <i class="fas fa-clock"></i>
                    <p>Aún no hay calificaciones registradas</p>
                </div>
                <?php endif; ?>
            </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</body>
</html>