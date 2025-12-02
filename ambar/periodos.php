<?php
session_start();
include 'config/conexao.php'; 

$dinoObj = new Dinossauro();
$todos_dinos = $dinoObj->listar();

$periodos_db = [
    'Triássico' => [],
    'Jurássico' => [],
    'Cretáceo' => []
];

// Agrupamento feito via PHP
foreach ($todos_dinos as $dino) {
    if (array_key_exists($dino['periodo'], $periodos_db)) {
        $periodos_db[$dino['periodo']][] = $dino;
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Períodos Geológicos - Âmbar</title>
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
                    <li class="nav-item"><a class="nav-link active" href="periodos.php">Períodos</a></li>
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
        <h1 class="mb-5 text-dark text-center">Linha do Tempo Geológica dos Dinossauros</h1>

        <div class="timeline row justify-content-between">

            <?php foreach ($periodos_db as $periodo => $dinossauros): ?>
                <div class="col-md-4 timeline-period">
                    <div class="period-title"><?php echo $periodo; ?></div>
                    
                    <div class="dino-card-list">
                        <?php if (!empty($dinossauros)): ?>
                            <?php foreach ($dinossauros as $dino): ?>
                                <?php 
                                    $classe_dieta = "bg-" . strtolower(str_replace('ívoro', 'ivoro', $dino['dieta']));
                                    $classe_periodo = "bg-" . strtolower(str_replace('á', 'a', $dino['periodo'])); 
                                ?>
                                <div class="card shadow-sm mb-4 mx-auto" style="max-width: 250px;">
                                    <img src="assets/img/<?php echo $dino['imagem_url']; ?>" class="card-img-top" alt="<?php echo $dino['nome']; ?>" style="height: 120px; object-fit: cover;">
                                    <div class="card-body p-3">
                                        <h6 class="card-title fw-bold mb-1"><?php echo $dino['nome']; ?></h6>
                                        <span class="badge <?php echo $classe_dieta; ?>"><?php echo $dino['dieta']; ?></span>
                                        <a href="detalhes.php?id=<?php echo $dino['id']; ?>" class="btn btn-sm btn-outline-warning mt-2 w-100">Ver Detalhes</a>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p class="text-muted">Nenhum dinossauro cadastrado neste período.</p>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>

        </div>
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