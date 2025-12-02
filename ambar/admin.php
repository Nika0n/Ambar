<?php
session_start();

// Verifica permissão de Admin
if (!isset($_SESSION['usuario_id']) || $_SESSION['nivel_acesso'] !== 'admin') {
    header("Location: login.php");
    exit();
}

include 'config/conexao.php'; // Carrega classes automaticamente

// Instancia os objetos
$dinoObj = new Dinossauro();
$userObj = new Usuario();
$newsObj = new Noticia();

$mensagem_alerta = '';

// --- LÓGICA DE EXCLUSÃO CENTRALIZADA ---
if (isset($_GET['acao']) && isset($_GET['id'])) {
    $id = $_GET['id'];
    $acao = $_GET['acao'];
    $sucesso = false;

    if ($acao == 'excluir_dino') {
        $sucesso = $dinoObj->excluir($id);
    } elseif ($acao == 'excluir_usuario') {
        // Impede excluir o próprio usuário logado
        if ($id == $_SESSION['usuario_id']) {
            $mensagem_alerta = '<div class="alert alert-danger">Você não pode excluir sua própria conta!</div>';
        } else {
            $sucesso = $userObj->excluir($id);
        }
    } elseif ($acao == 'excluir_noticia') {
        $sucesso = $newsObj->excluir($id);
    }

    if ($sucesso) {
        $mensagem_alerta = '<div class="alert alert-success">Item excluído com sucesso!</div>';
    } elseif (!$mensagem_alerta) {
        $mensagem_alerta = '<div class="alert alert-danger">Erro ao excluir item.</div>';
    }
}

// Verifica mensagens vindas de redirecionamentos (ex: cadastrar.php)
if (isset($_GET['status'])) {
    switch ($_GET['status']) {
        case 'success_create': $mensagem_alerta = '<div class="alert alert-success">Cadastro realizado!</div>'; break;
        case 'success_update': $mensagem_alerta = '<div class="alert alert-success">Atualização realizada!</div>'; break;
        case 'error_id_not_found': $mensagem_alerta = '<div class="alert alert-danger">ID não encontrado.</div>'; break;
    }
}

// --- CONTROLE DE ABAS ---
// Define qual aba está ativa (padrão: dinos)
$aba_ativa = $_GET['aba'] ?? 'dinos';

// Carrega os dados da aba selecionada
$lista_dinos = ($aba_ativa == 'dinos') ? $dinoObj->listar() : [];
$lista_users = ($aba_ativa == 'usuarios') ? $userObj->listar() : [];
$lista_news  = ($aba_ativa == 'noticias') ? $newsObj->listar() : [];

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Painel Admin - Âmbar</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .nav-tabs .nav-link { color: #495057; }
        .nav-tabs .nav-link.active { font-weight: bold; color: #000; border-top: 3px solid #ffc107; }
    </style>
</head>
<body class="bg-light">

    <div class="container my-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="mb-0">🦖 Painel Administrativo</h1>
            <div>
                <span class="me-3 text-muted">Olá, <?php echo $_SESSION['usuario_nome']; ?></span>
                <a href="logout.php" class="btn btn-outline-danger btn-sm">Sair</a>
            </div>
        </div>
        
        <?php echo $mensagem_alerta; ?>

        <ul class="nav nav-tabs mb-4">
            <li class="nav-item">
                <a class="nav-link <?php echo ($aba_ativa == 'dinos') ? 'active' : ''; ?>" href="admin.php?aba=dinos">🦕 Dinossauros</a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo ($aba_ativa == 'usuarios') ? 'active' : ''; ?>" href="admin.php?aba=usuarios">👥 Usuários</a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo ($aba_ativa == 'noticias') ? 'active' : ''; ?>" href="admin.php?aba=noticias">📰 Notícias</a>
            </li>
        </ul>

        <?php if ($aba_ativa == 'dinos'): ?>
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h4 class="card-title">Gerenciar Espécies</h4>
                        <a href="cadastrar.php" class="btn btn-success">+ Nova Espécie</a>
                    </div>
                    
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-dark">
                                <tr>
                                    <th>Img</th>
                                    <th>Nome</th>
                                    <th>Período</th>
                                    <th>Dieta</th>
                                    <th class="text-end">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (count($lista_dinos) > 0): ?>
                                    <?php foreach($lista_dinos as $dino): ?>
                                        <tr>
                                            <td>
                                                <?php if($dino['imagem_url']): ?>
                                                    <img src="assets/img/<?php echo $dino['imagem_url']; ?>" width="40" height="40" style="object-fit:cover; border-radius:4px;">
                                                <?php else: ?> - <?php endif; ?>
                                            </td>
                                            <td><?php echo $dino['nome']; ?></td>
                                            <td><span class="badge bg-secondary"><?php echo $dino['periodo']; ?></span></td>
                                            <td><?php echo $dino['dieta']; ?></td>
                                            <td class="text-end">
                                                <a href="cadastrar.php?id=<?php echo $dino['id']; ?>" class="btn btn-sm btn-primary">Editar</a>
                                                <a href="admin.php?aba=dinos&acao=excluir_dino&id=<?php echo $dino['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Excluir este dinossauro?');">Excluir</a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr><td colspan="5" class="text-center py-3">Nenhum dinossauro encontrado.</td></tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <?php if ($aba_ativa == 'usuarios'): ?>
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h4 class="card-title mb-3">Gerenciar Usuários</h4>
                    <p class="text-muted small">Administradores podem excluir contas de usuários comuns ou outros administradores.</p>
                    
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-dark">
                                <tr>
                                    <th>ID</th>
                                    <th>Nome</th>
                                    <th>Email</th>
                                    <th>Nível</th>
                                    <th class="text-end">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (count($lista_users) > 0): ?>
                                    <?php foreach($lista_users as $user): ?>
                                        <tr>
                                            <td>#<?php echo $user['id']; ?></td>
                                            <td><?php echo $user['nome']; ?></td>
                                            <td><?php echo $user['email']; ?></td>
                                            <td>
                                                <?php if($user['nivel_acesso'] == 'admin'): ?>
                                                    <span class="badge bg-warning text-dark">Admin</span>
                                                <?php else: ?>
                                                    <span class="badge bg-info">Comum</span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="text-end">
                                                <?php if($user['id'] == $_SESSION['usuario_id']): ?>
                                                    <span class="text-muted small me-2">(Você)</span>
                                                <?php else: ?>
                                                    <a href="admin.php?aba=usuarios&acao=excluir_usuario&id=<?php echo $user['id']; ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Tem certeza? Isso excluirá também as notícias postadas por este usuário.');">Excluir</a>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr><td colspan="5" class="text-center py-3">Nenhum usuário encontrado.</td></tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <?php if ($aba_ativa == 'noticias'): ?>
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h4 class="card-title">Gerenciar Notícias</h4>
                        <a href="novidades.php" class="btn btn-primary btn-sm">Ir para Feed / Postar</a>
                    </div>
                    
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-dark">
                                <tr>
                                    <th>Data</th>
                                    <th>Título</th>
                                    <th>Autor</th>
                                    <th class="text-end">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (count($lista_news) > 0): ?>
                                    <?php foreach($lista_news as $news): ?>
                                        <tr>
                                            <td><?php echo date('d/m/Y', strtotime($news['data_publicacao'])); ?></td>
                                            <td><?php echo $news['titulo']; ?></td>
                                            <td><?php echo $news['autor']; ?></td>
                                            <td class="text-end">
                                                <a href="admin.php?aba=noticias&acao=excluir_noticia&id=<?php echo $news['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Excluir esta notícia permanentemente?');">Excluir</a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr><td colspan="4" class="text-center py-3">Nenhuma notícia encontrada.</td></tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <div class="mt-4 text-center">
            <a href="index.php" class="text-secondary text-decoration-none">← Voltar para o site público</a>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>