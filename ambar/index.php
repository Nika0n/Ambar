<?php
session_start();
include 'config/conexao.php'; 

$dinoObj = new Dinossauro();
$filtros = [];

if (isset($_GET['dieta']) && !empty($_GET['dieta'])) {
    $filtros['dieta'] = $_GET['dieta'];
}

if (isset($_GET['periodo']) && !empty($_GET['periodo'])) {
    $filtros['periodo'] = $_GET['periodo'];
}

$lista_dinos = $dinoObj->listar($filtros);
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Âmbar - Enciclopédia de Dinossauros</title>
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
                    <li class="nav-item"><a class="nav-link active" href="index.php">Início</a></li>
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

    <header class="hero-section text-center mb-5">
        <div class="container">
            <h1 class="display-4 fw-bold">Projeto Âmbar</h1>
            <p class="lead">Preservando o conhecimento pré-histórico.</p>
            <p class="mx-auto" style="max-width: 700px;">
                Explore informações sobre diferentes espécies de dinossauros de forma clara e visual. 
                Assim como o âmbar preserva a história, nós preservamos o conhecimento.
            </p>
        </div>
    </header>

    <main class="container my-5">
        
        <div class="row mb-4 p-3 bg-light rounded border">
            <div class="col-md-12">
                <h4 class="mb-3">Filtrar Dinossauros</h4>
                
                <form action="index.php" method="GET" class="row g-3 align-items-end">
                    
                    <div class="col-md-4">
                        <label for="filtro_dieta" class="form-label">Dieta</label>
                        <select class="form-select" id="filtro_dieta" name="dieta">
                            <option value="">Todas as Dietas</option>
                            <option value="Herbívoro" <?php echo (isset($_GET['dieta']) && $_GET['dieta'] == 'Herbívoro') ? 'selected' : ''; ?>>Herbívoro</option>
                            <option value="Carnívoro" <?php echo (isset($_GET['dieta']) && $_GET['dieta'] == 'Carnívoro') ? 'selected' : ''; ?>>Carnívoro</option>
                            <option value="Onívoro" <?php echo (isset($_GET['dieta']) && $_GET['dieta'] == 'Onívoro') ? 'selected' : ''; ?>>Onívoro</option>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label for="filtro_periodo" class="form-label">Período Geológico</label>
                        <select class="form-select" id="filtro_periodo" name="periodo">
                            <option value="">Todos os Períodos</option>
                            <option value="Triássico" <?php echo (isset($_GET['periodo']) && $_GET['periodo'] == 'Triássico') ? 'selected' : ''; ?>>Triássico</option>
                            <option value="Jurássico" <?php echo (isset($_GET['periodo']) && $_GET['periodo'] == 'Jurássico') ? 'selected' : ''; ?>>Jurássico</option>
                            <option value="Cretáceo" <?php echo (isset($_GET['periodo']) && $_GET['periodo'] == 'Cretáceo') ? 'selected' : ''; ?>>Cretáceo</option>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <button type="submit" class="btn btn-warning w-100">Aplicar Filtros</button>
                    </div>
                </form>
            </div>
        </div>
        
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
            
            <?php
            if (count($lista_dinos) > 0) {
                foreach($lista_dinos as $dino) {
                    $classe_dieta = "bg-" . strtolower(str_replace('ívoro', 'ivoro', $dino['dieta']));
                    $classe_periodo = "bg-" . strtolower(str_replace('á', 'a', $dino['periodo'])); 
            ?>

            <div class="col">
                <div class="card h-100 shadow-sm border-0">
                    <img src="assets/img/<?php echo $dino['imagem_url']; ?>" class="card-img-top" alt="<?php echo $dino['nome']; ?>">
                    <div class="card-body">
                        <h5 class="card-title fw-bold"><?php echo $dino['nome']; ?></h5>
                        <span class="badge <?php echo $classe_dieta; ?>"><?php echo $dino['dieta']; ?></span>
                        <span class="badge <?php echo $classe_periodo; ?>"><?php echo $dino['periodo']; ?></span>
                        <p class="card-text mt-3"><?php echo substr($dino['curiosidades'], 0, 80) . '...'; ?></p>
                    </div>
                    <div class="card-footer bg-white border-0 text-end">
                        <a href="detalhes.php?id=<?php echo $dino['id']; ?>" class="btn btn-outline-warning btn-sm">Ver Detalhes</a>
                    </div>
                </div>
            </div>
            <?php
                }
            } else {
                echo '<div class="col-12"><p class="alert alert-info">Nenhum dinossauro encontrado com os filtros aplicados.</p></div>';
            }
            ?>

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