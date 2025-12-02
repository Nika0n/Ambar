<?php
session_start();
include 'config/conexao.php'; 

$mensagem = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $senha = $_POST['senha']; 

    // Instancia a classe Usuario
    $usuarioObj = new Usuario();
    
    // Tenta logar
    $resultado = $usuarioObj->logar($email, $senha);
    
    if ($resultado) {
        // Verifica se é Admin
        if ($resultado['nivel_acesso'] === 'admin') {
            $_SESSION['usuario_id'] = $resultado['id'];
            $_SESSION['usuario_nome'] = $resultado['nome'];
            $_SESSION['nivel_acesso'] = 'admin';
            
            header("Location: admin.php");
            exit();
        } else {
            $mensagem = '<div class="alert alert-warning">Acesso negado. Esta área é restrita para administradores.</div>';
        }
    } else {
        $mensagem = '<div class="alert alert-danger">Credenciais inválidas.</div>';
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Admin - Âmbar</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
    <style>body { background-color: #212529; }</style> 
</head>
<body class="d-flex align-items-center min-vh-100">
    <div class="container d-flex justify-content-center">
        <div class="col-md-4">
            <div class="card shadow-lg p-4 border-warning">
                <div class="text-center mb-4">
                    <h2 class="text-warning fw-bold">Área Administrativa</h2>
                    <p class="text-muted">Acesso restrito</p>
                </div>
                <?php echo $mensagem; ?>
                <form method="POST">
                    <div class="mb-3">
                        <label class="form-label">Email Corporativo</label>
                        <input type="email" class="form-control" name="email" required>
                    </div>
                    <div class="mb-4">
                        <label class="form-label">Senha</label>
                        <input type="password" class="form-control" name="senha" required>
                    </div>
                    <button type="submit" class="btn btn-warning w-100 fw-bold">Acessar Painel</button>
                </form>
                <p class="mt-4 text-center">
                    <a href="login.php" class="text-secondary text-decoration-none">← Voltar para login de usuário</a>
                </p>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>