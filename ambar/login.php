<?php
session_start();
include 'config/conexao.php'; // Carrega as classes automaticamente

$mensagem = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $senha = $_POST['senha']; 

    // Instancia o objeto Usuário
    $usuarioObj = new Usuario();
    
    // Tenta logar usando o método da classe
    $resultado = $usuarioObj->logar($email, $senha);

    if ($resultado) {
        // Login com sucesso
        $_SESSION['usuario_id'] = $resultado['id'];
        $_SESSION['usuario_nome'] = $resultado['nome'];
        $_SESSION['nivel_acesso'] = $resultado['nivel_acesso'];
        
        // Redirecionamento inteligente
        if ($resultado['nivel_acesso'] === 'admin') {
            header("Location: admin.php");
        } else {
            header("Location: novidades.php"); 
        }
        exit();
    } else {
        $mensagem = '<div class="alert alert-danger">Email ou senha incorretos.</div>';
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Login - Âmbar</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="bg-light">
    <div class="container d-flex justify-content-center align-items-center min-vh-100">
        <div class="col-md-5 col-lg-4">
            <div class="card shadow-lg p-4">
                <h2 class="card-title text-center text-dark mb-4">Acesso à Comunidade</h2>
                <?php echo $mensagem; ?>
                <form method="POST">
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" class="form-control" name="email" required>
                    </div>
                    <div class="mb-4">
                        <label class="form-label">Senha</label>
                        <input type="password" class="form-control" name="senha" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100 fw-bold">Entrar</button>
                </form>
                
                <hr>
                
                <div class="text-center">
                    <p class="mb-2">Não tem conta? <a href="registrar.php" class="fw-bold">Cadastre-se</a></p>
                    <a href="login_admin.php" class="btn btn-outline-dark btn-sm w-100">Realizar login como administrador</a>
                </div>
                
                <p class="mt-3 text-center">
                    <a href="index.php" class="text-secondary small">Voltar para o site público</a>
                </p>
            </div>
        </div>
    </div>
</body>
</html>