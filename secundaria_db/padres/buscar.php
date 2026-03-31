<?php require '../config/db.php'; ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>🔍 Acceso Padres - Sistema Escolar</title>
    <link rel="stylesheet" href="../css/style.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="login-body">
    <div class="login-wrapper">
        <!-- Hero Padres -->
        <div class="login-hero">
            <div class="hero-content">
                <i class="fas fa-users hero-icon"></i>
                <h1>Padres de Familia</h1>
                <p>Consulta las calificaciones de tus hijos</p>
                <div class="hero-stats">
                    <div><i class="fas fa-chart-line"></i> Promedios</div>
                    <div><i class="fas fa-comment"></i> Comentarios</div>
                    <div><i class="fas fa-calendar"></i> Periodos</div>
                </div>
            </div>
        </div>
        
        <!-- Formulario Búsqueda -->
        <div class="login-card">
            <div class="login-header">
                <i class="fas fa-search"></i>
                <h2>Buscar Estudiante</h2>
                <p>Nombre completo o CURP</p>
            </div>
            
            <form method="GET" action="resultados.php" class="login-form">
                <div class="input-group large">
                    <i class="fas fa-magnifying-glass input-icon"></i>
                    <input type="text" name="busqueda" placeholder="Ej: Juan Pérez García o CURP" required>
                    <button type="submit" class="search-btn">
                        <i class="fas fa-arrow-right"></i>
                    </button>
                </div>
            </form>
            
            <!-- Ejemplos de búsqueda -->
            <div class="search-examples">
                <h4>💡 Ejemplos:</h4>
                <div class="example-grid">
                    <div class="example-item">
                        <i class="fas fa-user"></i>
                        <span>Juan Pérez García</span>
                    </div>
                    <div class="example-item">
                        <i class="fas fa-id-card"></i>
                        <span>CURP123456789...</span>
                    </div>
                </div>
            </div>
            
            <!-- Alertas útiles -->
            <div class="info-card">
                <i class="fas fa-info-circle"></i>
                <div>
                    <strong>Información importante:</strong><br>
                    Puedes consultar calificaciones de todos los periodos.
                </div>
            </div>
            
            <!-- Navegación -->
            <div class="login-footer">
                <a href="../login.php" class="link-secondary">
                    <i class="fas fa-arrow-left"></i> Admin / Directora
                </a>
            </div>
        </div>
    </div>
</body>
</html>