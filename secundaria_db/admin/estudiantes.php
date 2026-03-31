<?php 
require '../config/db.php'; 
if($_SESSION['user_tipo'] != 'directora') header('Location: ../login.php');

// ELIMINAR ESTUDIANTE
if(isset($_GET['eliminar'])) {
    $id = $_GET['eliminar'];

    $stmt = $pdo->prepare("DELETE FROM estudiantes WHERE id = ?");
    $stmt->execute([$id]);

    header("Location: estudiantes.php");
    exit;
}

// AGREGAR ESTUDIANTE
if(isset($_POST['agregar'])) {
    $stmt = $pdo->prepare("INSERT INTO estudiantes (nombre, apellido_paterno, apellido_materno, curp, grado, grupo, tutor) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([
        $_POST['nombre'], 
        $_POST['apellido_paterno'], 
        $_POST['apellido_materno'], 
        $_POST['curp'], 
        $_POST['grado'], 
        $_POST['grupo'], 
        $_POST['tutor']
    ]);
}

// PAGINACIÓN + FILTRO
$page = $_GET['page'] ?? 1;
$grupo = $_GET['grupo'] ?? '';

$limit = 10;
$offset = ($page - 1) * $limit;

// CONSULTA CON FILTRO
if($grupo != '') {
    $stmt = $pdo->prepare("
        SELECT * FROM estudiantes 
        WHERE CONCAT(grado, grupo) = ?
        ORDER BY apellido_paterno, nombre 
        LIMIT $limit OFFSET $offset
    ");
    $stmt->execute([$grupo]);

    $stmtTotal = $pdo->prepare("
        SELECT COUNT(*) FROM estudiantes 
        WHERE CONCAT(grado, grupo) = ?
    ");
    $stmtTotal->execute([$grupo]);

} else {
    $stmt = $pdo->prepare("
        SELECT * FROM estudiantes 
        ORDER BY apellido_paterno, nombre 
        LIMIT $limit OFFSET $offset
    ");
    $stmt->execute();

    $stmtTotal = $pdo->prepare("SELECT COUNT(*) FROM estudiantes");
    $stmtTotal->execute();
}

$estudiantes = $stmt->fetchAll();
$total_estudiantes = $stmtTotal->fetchColumn();
$total_pages = ceil($total_estudiantes / $limit);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <title>Estudiantes</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <nav class="navbar">
        <h2>📚 Estudiantes (<?php echo $total_estudiantes; ?>)</h2>
        <div class="nav-buttons">
            <a href="dashboard.php" class="nav-btn secondary">← Dashboard</a>
            <a href="calificaciones.php" class="nav-btn">📊 Calificaciones</a>
            <a href="logout.php" class="btn-logout">🚪</a>
        </div>
    </nav>

    <!-- FORMULARIO -->
    <div class="form-card">
        <h3>➕ Nuevo Estudiante</h3>
        <form method="POST" class="form-grid">
            <input type="text" name="nombre" placeholder="Nombre(s)" required>
            <input type="text" name="apellido_paterno" placeholder="Apellido Paterno" required>
            <input type="text" name="apellido_materno" placeholder="Apellido Materno" required>
            <input type="text" name="curp" placeholder="CURP" maxlength="18" required>
            <input type="text" name="grado" placeholder="Grado (1, 2, 3)" required>
            <input type="text" name="grupo" placeholder="Grupo (A)" required>
            <input type="text" name="tutor" placeholder="Tutor">
            <button type="submit" name="agregar" class="btn-primary full">Agregar</button>
        </form>
    </div>

    <!-- TABLA -->
    <div class="table-card">
        <div class="table-header">
            <h3>📋 Lista de Estudiantes</h3>

            <!-- FILTRO -->
            <div class="table-actions">
                <form method="GET">
                    <select name="grupo" class="search-input">
                        <option value="">Todos</option>
                        <option value="1A" <?php if($grupo=="1A") echo "selected"; ?>>1A</option>
                        <option value="2A" <?php if($grupo=="2A") echo "selected"; ?>>2A</option>
                        <option value="3A" <?php if($grupo=="3A") echo "selected"; ?>>3A</option>
                    </select>
                    <button type="submit" class="btn-sm primary">Filtrar</button>
                </form>
            </div>
        </div>

        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Foto</th>
                        <th>Nombre Completo</th>
                        <th>CURP</th>
                        <th>Grado/Grupo</th>
                        <th>Tutor</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($estudiantes as $est): ?>
                    <tr>
                        <td><div class="avatar"><?php echo strtoupper(substr($est['nombre'],0,1)); ?></div></td>
                        <td>
                            <strong><?php echo $est['nombre']; ?></strong><br>
                            <small><?php echo $est['apellido_paterno'].' '.$est['apellido_materno']; ?></small>
                        </td>
                        <td><code><?php echo $est['curp']; ?></code></td>
                        <td><span class="badge"><?php echo $est['grado'].' '.$est['grupo']; ?></span></td>
                        <td><?php echo $est['tutor'] ?: 'N/A'; ?></td>
                        <td>
                            <!-- VER -->
                            <a href="ver_estudiante.php?id=<?php echo $est['id']; ?>" class="btn-sm primary">
                                Ver
                            </a>

                            <!-- ELIMINAR -->
                            <a href="?eliminar=<?php echo $est['id']; ?>&grupo=<?php echo $grupo; ?>&page=<?php echo $page; ?>" 
                               class="btn-sm danger"
                               onclick="return confirm('¿Seguro que quieres eliminar este estudiante?')">
                               Eliminar
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- PAGINACIÓN -->
        <div class="pagination">
            <?php for($i=1; $i<=$total_pages; $i++): ?>
                <a href="?page=<?php echo $i; ?>&grupo=<?php echo $grupo; ?>" 
                   class="<?php echo $i==$page ? 'active' : ''; ?>">
                   <?php echo $i; ?>
                </a>
            <?php endfor; ?>
        </div>
    </div>
</body>
</html>