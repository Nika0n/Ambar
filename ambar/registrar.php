<?php
include 'config/conexao.php';

$mensagem = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = trim($_POST['nome']);
    $email = trim($_POST['email']);
    $senha = $_POST['senha'];
    
    $usuarioObj = new Usuario();
    
    if ($usuarioObj->emailExiste($email)) {
        $mensagem = '<div class="alert alert-danger">Este email já está cadastrado.</div>';
    } else {
        if ($usuarioObj->cadastrar($nome, $email, $senha)) {
            $mensagem = '<div class="alert alert-success">Cadastro realizado! <a href="login.php">Faça login aqui</a>.</div>';
        } else {
            $mensagem = '<div class="alert alert-danger">Erro ao realizar cadastro. Tente novamente.</div>';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Criar Conta - Âmbar</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="bg-light">
    <div class="container d-flex justify-content-center align-items-center min-vh-100">
        <div class="col-md-5">
            <div class="card shadow p-4">
                <h2 class="text-center mb-4">Crie sua Conta</h2>
                <?php echo $mensagem; ?>
                <form method="POST">
                    <div class="mb-3">
                        <label class="form-label">Nome Completo</label>
                        <input type="text" name="nome" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Senha</label>
                        <input type="password" name="senha" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-success w-100 fw-bold">Registrar</button>
                </form>
                <p class="mt-3 text-center"><a href="login.php">Já tenho conta</a></p>
                <p class="text-center"><a href="index.php" class="text-secondary">Voltar ao início</a></p>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>