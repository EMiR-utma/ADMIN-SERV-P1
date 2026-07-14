<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require 'dbcon.php';

// Reutilizamos el sistema de alertas SweetAlert2 que ya tienes en usuarios.php
$alert = isset($_SESSION['alert']) ? $_SESSION['alert'] : null;

if (!empty($alert)) {
    $title = isset($alert['title']) ? json_encode($alert['title']) : '"Notificación"';
    $message = isset($alert['message']) ? json_encode($alert['message']) : '""';
    $icon = isset($alert['icon']) ? json_encode($alert['icon']) : '"info"';

    echo "<script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    title: $title,
                    " . (!empty($alert['message']) ? "text: $message," : "") . "
                    icon: $icon,
                    confirmButtonText: 'OK'
                });
            });
        </script>";
    unset($_SESSION['alert']);
}

// Procesar el formulario cuando se envía por POST
if (isset($_POST['login_btn'])) {
    $username = mysqli_real_escape_string($con, $_POST['username']);
    $password = $_POST['password'];

    $query = "SELECT * FROM usuarios WHERE username = '$username' AND estatus = '1' LIMIT 1";
    $query_run = mysqli_query($con, $query);

    if (mysqli_num_rows($query_run) > 0) {
        $row = mysqli_fetch_assoc($query_run);
        
        // Verificar la contraseña encriptada
        if (password_verify($password, $row['password'])) {
            // Guardar la sesión como lo espera usuarios.php
            $_SESSION['username'] = $row['username'];
            
            header("Location: usuarios.php");
            exit(0);
        } else {
            $_SESSION['alert'] = [
                'title' => 'CONTRASEÑA INCORRECTA',
                'message' => 'Por favor, verifica tus datos.',
                'icon' => 'error'
            ];
            header("Location: login.php");
            exit(0);
        }
    } else {
        $_SESSION['alert'] = [
            'title' => 'USUARIO NO ENCONTRADO',
            'message' => 'El correo electrónico no está registrado o está inactivo.',
            'icon' => 'error'
        ];
        header("Location: login.php");
        exit(0);
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión | Mi Empresa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; display: flex; align-items: center; justify-content: center; height: 100vh; }
        .card-login { width: 100%; max-width: 400px; padding: 20px; border-radius: 10px; box-shadow: 0px 4px 10px rgba(0,0,0,0.1); }
    </style>
</head>
<body>

<div class="card card-login bg-white">
    <h3 class="text-center mb-4">Iniciar Sesión</h3>
    <form action="login.php" method="POST">
        <div class="mb-3">
            <label for="username" class="form-label">Correo Electrónico</label>
            <input type="email" name="username" id="username" class="form-control" placeholder="ejemplo@correo.com" required>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Contraseña</label>
            <input type="password" name="password" id="password" class="form-control" placeholder="********" required>
        </div>
        <div class="d-grid gap-2">
            <button type="submit" name="login_btn" class="btn btn-primary">Ingresar</button>
        </div>
    </form>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src='https://cdn.jsdelivr.net/npm/sweetalert2@10'></script>
</body>
</html>