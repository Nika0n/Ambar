<?php
session_start();
include 'config/conexao.php';

$noticiaObj = new Noticia();

// --- LÓGICA DE EXCLUSÃO ---
if (isset($_GET['excluir']) && isset($_SESSION['nivel_acesso']) && $_SESSION['nivel_acesso'] === 'admin') {
    $noticiaObj->excluir($_GET['excluir']);
    header("Location: novidades.php");
    exit();
}

// --- LÓGICA DE POSTAGEM (Só Link) ---
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_SESSION['usuario_id'])) {
    $link = trim($_POST['link_noticia']);
    
    if (!empty($link)) {
        $noticiaObj->publicarLink($link, $_SESSION['usuario_id']);
    }
    
    header("Location: novidades.php"); 
    exit();
}

$lista_noticias = $noticiaObj->listar();
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Novidades - Âmbar</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        /* Estilo Customizado para o Card de Link Preview */
        .link-preview-card {
            transition: transform 0.2s;
            text-decoration: none;
            color: inherit;
            display: block;
        }
        .link-preview-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
            color: inherit;
        }
        .preview-img {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-radius: 8px 8px 0 0;
        }
        @media (min-width: 768px) {
            .preview-img {
                height: 100%;
                width: 150px;
                border-radius: 8px 0 0 8px;
            }
        }
    </style>
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
                    <li class="nav-item"><a class="nav-link active" href="novidades.php">Novidades</a></li>
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
        <h1 class="mb-4 text-center">Mural de Descobertas</h1>

        <?php if (isset($_SESSION['usuario_id'])): ?>
            <div class="card mb-5 shadow-sm border-warning">
                <div class="card-body">
                    <h5 class="card-title mb-3">Encontrou uma notícia legal? Cole o link abaixo!</h5>
                    <form method="POST" class="d-flex gap-2">
                        <input type="url" name="link_noticia" class="form-control" required placeholder="https://exemplo.com/noticia-dinossauro...">
                        <button type="submit" class="btn btn-dark px-4">Compartilhar</button>
                    </form>
                </div>
            </div>
        <?php else: ?>
            <div class="alert alert-info text-center py-3 mb-5">
                Faça <a href="login.php" class="fw-bold">login</a> para compartilhar links com a comunidade.
            </div>
        <?php endif; ?>

        <div class="row g-4">
            <?php if (count($lista_noticias) > 0): ?>
                <?php foreach($lista_noticias as $news): ?>
                    <div class="col-md-10 offset-md-1">
                        
                        <div class="d-flex justify-content-between align-items-center mb-2 px-1">
                            <small class="text-muted">
                                <i class="bi bi-person-circle"></i> <strong><?php echo $news['autor']; ?></strong> compartilhou em <?php echo date('d/m/Y H:i', strtotime($news['data_publicacao'])); ?>
                            </small>
                            <?php if (isset($_SESSION['nivel_acesso']) && $_SESSION['nivel_acesso'] === 'admin'): ?>
                                <a href="novidades.php?excluir=<?php echo $news['id']; ?>" class="text-danger small text-decoration-none" onclick="return confirm('Apagar?');">[Excluir]</a>
                            <?php endif; ?>
                        </div>

                        <a href="<?php echo $news['link_original']; ?>" target="_blank" class="card shadow-sm link-preview-card h-100">
                            <div class="row g-0">
                                <div class="col-md-3">
                                    <img src="<?php echo $news['imagem_site'] ?: 'assets/img/default-placeholder.png'; ?>" class="preview-img" alt="Capa da notícia" onerror="this.src='https://via.placeholder.com/150?text=News';">
                                </div>
                                <div class="col-md-9">
                                    <div class="card-body h-100 d-flex flex-column justify-content-center">
                                        <h5 class="card-title fw-bold text-primary mb-2"><?php echo $news['titulo_site']; ?></h5>
                                        <p class="card-text text-muted mb-1 small text-truncate-2">
                                            <?php echo substr($news['descricao_site'], 0, 150) . '...'; ?>
                                        </p>
                                        <small class="text-secondary mt-auto">
                                            🔗 <?php echo parse_url($news['link_original'], PHP_URL_HOST); ?>
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </a>

                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-12">
                    <div class="alert alert-light text-center border p-5">
                        <p class="mb-0 text-muted">Nenhum link compartilhado ainda.</p>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </main>

    <footer class="bg-dark text-light text-center py-4 mt-5">
        <div class="container">
            <p class="mb-0">&copy; 2025 Projeto Âmbar - Níkollas | IFSul Campus Pelotas</p>
        </div>
    </footer>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>