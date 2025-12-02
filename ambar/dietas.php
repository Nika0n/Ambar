<?php
session_start();
include 'config/conexao.php'; 

$dinoObj = new Dinossauro();
$todos_dinos = $dinoObj->listar();

$dietas_db = [
    'Carnívoro' => [],
    'Herbívoro' => [],
    'Onívoro' => []
];

// Agrupamento feito via PHP
foreach ($todos_dinos as $dino) {
    if (array_key_exists($dino['dieta'], $dietas_db)) {
        $dietas_db[$dino['dieta']][] = $dino;
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tipos de Dieta - Âmbar</title>
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
                    <li class="nav-item"><a class="nav-link active" href="dietas.php">Dietas</a></li>
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
        <h1 class="mb-5 text-dark text-center">Classificação de Dietas</h1>

        <?php foreach ($dietas_db as $dieta => $dinossauros): ?>
            <div class="card shadow-lg mb-5 border-0">
                <div class="card-header bg-warning text-dark fw-bold h4">
                    <?php echo $dieta; ?>s
                </div>
                <div class="card-body">
                    <p class="mb-4">
                        <?php 
                            if ($dieta == 'Carnívoro') {
                                echo 'Dinossauros que se alimentam primariamente de carne e outros animais.';
                            } else if ($dieta == 'Herbívoro') {
                                echo 'Dinossauros que se alimentam primariamente de plantas, folhas e vegetação.';
                            } else if ($dieta == 'Onívoro') {
                                echo 'Dinossauros que consomem tanto matéria vegetal quanto animal.';
                            }
                        ?>
                    </p>
                    <div class="row row-cols-2 row-cols-md-3 row-cols-lg-4 g-3">
                        <?php if (!empty($dinossauros)): ?>
                            <?php foreach ($dinossauros as $dino): ?>
                                <div class="col">
                                    <div class="card h-100 bg-light">
                                        <img src="assets/img/<?php echo $dino['imagem_url']; ?>" class="card-img-top" alt="<?php echo $dino['nome']; ?>" style="height: 100px; object-fit: cover;">
                                        <div class="card-body p-2 text-center">
                                            <h6 class="card-title fw-bold mb-1"><?php echo $dino['nome']; ?></h6>
                                            <a href="detalhes.php?id=<?php echo $dino['id']; ?>" class="btn btn-sm btn-outline-dark mt-1">Detalhes</a>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="col-12"><p class="text-muted">Nenhum dinossauro cadastrado nesta categoria de dieta.</p></div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>

    </main>

    <footer class="bg-dark text-light text-center py-4 mt-5">
        <div class="container">
            <p class="mb-0">&copy; 2025 Projeto Âmbar - Níkollas | IFSul Campus Pelotas</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/script.js"></script>
</body>
</html>