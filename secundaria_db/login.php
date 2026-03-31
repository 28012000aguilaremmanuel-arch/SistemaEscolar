<?php require 'config/db.php'; ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>🎓 Sistema Escolar - Login</title>
    <link rel="stylesheet" href="css/style.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="login-body">
    <div class="login-wrapper">
        <!-- Fondo con ilustración -->
        <div class="login-hero">
            <div class="hero-content">
                <i class="fas fa-graduation-cap hero-icon"></i>
                <h1>Sistema Escolar</h1>
                <p>Secundaria Tecnica</p>
            </div>
        </div>
        
        <!-- Formulario Login -->
        <div class="login-card">
            <div class="login-header">
                <i class="fas fa-sign-in-alt"></i>
                <h2>Bienvenido</h2>
                <p>Inicia sesión para continuar</p>
            </div>
            
            <form method="POST" class="login-form">
                <div class="input-group">
                    <i class="fas fa-envelope input-icon"></i>
                    <input type="email" name="email" placeholder="Email" required>
                </div>
                <div class="input-group">
                    <i class="fas fa-lock input-icon"></i>
                    <input type="password" name="password" placeholder="Contraseña" required>
                </div>
                
                <button type="submit" name="login" class="btn-primary full">
                    <i class="fas fa-arrow-right"></i> Entrar
                </button>
            </form>
            
            <?php if(isset($_POST['login'])): 
                $email = $_POST['email'];
                $password = $_POST['password'];
                
                $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE email = ?");
                $stmt->execute([$email]);
                $user = $stmt->fetch();
                
                // Temporal: contraseña plana para pruebas
                if($user && $password === 'admin123' && $user['email'] === 'directora@secundaria.com') {
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['user_tipo'] = $user['tipo'];
                    $_SESSION['user_nombre'] = $user['nombre'];
                    
                    header('Location: admin/dashboard.php');
                    exit();
                } else {
                    $error = true;
                }
            endif; ?>
            
            <?php if(isset($error)): ?>
            <div class="alert error">
                <i class="fas fa-exclamation-triangle"></i>
                Credenciales incorrectas
            </div>
            <?php endif; ?>
            
            <!-- Credenciales de prueba -->
            <div class="credentials">
                <h4>🔑 Prueba rápida:</h4>
                <div class="credential-item">
                    <strong>Directora:</strong> directora@secundaria.com / admin123
                </div>
            </div>
            
            <!-- Acceso padres -->
            <div class="login-footer">
                <a href="padres/buscar.php" class="link-secondary">
                    <i class="fas fa-users"></i> Acceso Padres
                </a>
            </div>
        </div>
    </div>
</body>
</html>