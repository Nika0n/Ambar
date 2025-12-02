<?php
session_start();
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sobre o Projeto - Âmbar</title>
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
        <h1 class="mb-4 text-dark">Sobre o Projeto Âmbar</h1>
        
        <div class="p-4 bg-white shadow-sm rounded">
            
            <h2 class="h4 text-warning mb-3">Introdução e Objetivo</h2>
            <p class="lead">O projeto Âmbar é uma aplicação web interativa, responsiva e temática, desenvolvida com o objetivo de apresentar informações sobre diferentes espécies de dinossauros.</p>
            
            <p>O foco do projeto é aplicar os conhecimentos de desenvolvimento web para construir uma plataforma educativa e de fácil navegação, transformando o acesso a dados paleontológicos em uma experiência clara e visualmente agradável.</p>
            
            <p>A escolha do nome **"Âmbar"** remete ao conceito central do projeto: o âmbar é um material que preserva organismos por milhões de anos, simbolizando a ideia de **preservação e organização do conhecimento** sobre o período pré-histórico.</p>

            <h3 class="mt-5 text-warning">Tecnologias Utilizadas</h3>
            <p>O projeto integra uma stack completa de desenvolvimento web para garantir a funcionalidade e o design:</p>
            
            <div class="row g-3">
                <div class="col-md-6">
                    <ul class="list-group">
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <strong>HTML5</strong>
                            <span class="badge bg-secondary">Estruturação do Conteúdo</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <strong>CSS3 + Bootstrap 5</strong>
                            <span class="badge bg-secondary">Estilos, Layout e Responsividade</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <strong>JavaScript</strong>
                            <span class="badge bg-secondary">Interatividade e Lógica Front-end</span>
                        </li>
                    </ul>
                </div>
                <div class="col-md-6">
                    <ul class="list-group">
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <strong>PHP</strong>
                            <span class="badge bg-secondary">Processamento Back-end e CRUD</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <strong>MySQL</strong>
                            <span class="badge bg-secondary">Armazenamento e Persistência de Dados</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <strong>Bootstrap 5</strong>
                            <span class="badge bg-secondary">Auxílio no Layout Responsivo</span>
                        </li>
                    </ul>
                </div>
            </div>
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