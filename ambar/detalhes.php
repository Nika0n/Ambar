<?php
session_start();
include 'config/conexao.php'; 

$dinoObj = new Dinossauro();
$erro = null;
$dino = null;

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $dino = $dinoObj->buscar($_GET['id']);
    
    if (!$dino) {
        $erro = "Dinossauro não encontrado.";
    }
} else {
    $erro = "ID do dinossauro não especificado.";
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $dino ? $dino['nome'] : 'Detalhes' ?> - Âmbar</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold text-warning" href="index.php">
                <i class="bi bi-gem"></i> ÂMBAR
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item"><a class="nav-link" href="index.php">Início</a></li>
                    <li class="nav-item"><a class="nav-link" href="periodos.php">Períodos</a></li>
                    <li class="nav-item"><a class="nav-link" href="dietas.php">Dietas</a></li>
                    <li class="nav-item"><a class="nav-link" href="novidades.php">Novidades</a></li>
                    <li class="nav-item"><a class="nav-link" href="sobre.php">Sobre</a></li>
                    
                    <?php if (isset($_SESSION['usuario_id'])): ?>
                        <li class="nav-item dropdown ms-lg-3">
                            <a class="nav-link dropdown-toggle active" href="#" role="button" data-bs-toggle="dropdown">
                                Olá, <?php echo $_SESSION['usuario_nome']; ?>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <?php if ($_SESSION['nivel_acesso'] === 'admin'): ?>
                                    <li><a class="dropdown-item" href="admin.php">Painel Admin</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                <?php endif; ?>
                                <li><a class="dropdown-item text-danger" href="logout.php">Sair</a></li>
                            </ul>
                        </li>
                    <?php else: ?>
                        <li class="nav-item ms-lg-3">
                            <a class="btn btn-outline-warning btn-sm px-4" href="login.php">Realizar Login</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <main class="container my-5">
        <?php if ($dino): ?>
            <div class="row">
                <div class="col-lg-4 mb-4">
                    <img src="assets/img/<?php echo $dino['imagem_url']; ?>" class="img-fluid rounded shadow" alt="<?php echo $dino['nome']; ?>">
                </div>

                <div class="col-lg-8">
                    <h1 class="display-5 fw-bold text-dark"><?php echo $dino['nome']; ?></h1>
                    
                    <hr>

                    <div class="d-flex flex-wrap mb-4 gap-2">
                        <span class="badge bg-primary fs-6 p-2">Período: <?php echo $dino['periodo']; ?></span>
                        <span class="badge bg-success fs-6 p-2">Dieta: <?php echo $dino['dieta']; ?></span>
                        <span class="badge bg-secondary fs-6 p-2">Tamanho Estimado: <?php echo $dino['tamanho']; ?></span>
                        <span class="badge bg-info fs-6 p-2">Habitat: <?php echo $dino['habitat']; ?></span>
                    </div>

                    <div class="card p-4 shadow-sm border-0">
                        <h4 class="card-title text-warning mb-3">Curiosidades e Ficha Técnica</h4>
                        <p class="text-muted"><?php echo nl2br($dino['curiosidades']); ?></p>
                    </div>

                    <a href="index.php" class="btn btn-outline-dark mt-4">Voltar para a Lista</a>
                </div>
            </div>
        <?php else: ?>
            <div class="alert alert-danger text-center p-5">
                <h2><?php echo $erro; ?></h2>
                <a href="index.php" class="btn btn-warning mt-3">Voltar à Página Inicial</a>
            </div>
        <?php endif; ?>
    </main>

    <footer class="bg-dark text-light text-center py-4 mt-5">
        <div class="container">
            <p class="mb-0">&copy; 2025 Projeto Âmbar - Níkollas | IFSul Campus Pelotas</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>