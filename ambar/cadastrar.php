<?php
session_start();

if (!isset($_SESSION['usuario_id']) || $_SESSION['nivel_acesso'] !== 'admin') {
    header("Location: login.php");
    exit();
}

include 'config/conexao.php';

$dinoObj = new Dinossauro();
$dino_id = null;
$titulo = "➕ Cadastrar Nova Espécie";
$botao_texto = "Cadastrar Dinossauro";
$mensagem = '';

// Dados padrão vazios
$dino = [
    'id' => '', 'nome' => '', 'imagem_url' => '', 'periodo' => '', 
    'dieta' => '', 'tamanho' => '', 'habitat' => '', 'curiosidades' => ''
];

// --- LÓGICA DE BUSCA (EDITAR) ---
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $dino_id = $_GET['id'];
    $titulo = "✏️ Editar Espécie";
    $botao_texto = "Salvar Alterações";
    
    $dados_banco = $dinoObj->buscar($dino_id);
    
    if ($dados_banco) {
        $dino = $dados_banco;
    } else {
        header("Location: admin.php?status=error_id_not_found");
        exit();
    }
}

// --- LÓGICA DE SUBMISSÃO ---
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_post = $_POST['dino_id'] ?? null;
    
    // Coleta dados do POST
    $dados_form = [
        'nome' => trim($_POST['nome']),
        'periodo' => $_POST['periodo'],
        'dieta' => $_POST['dieta'],
        'tamanho' => trim($_POST['tamanho']),
        'habitat' => trim($_POST['habitat']),
        'curiosidades' => trim($_POST['curiosidades'])
    ];
    
    $arquivo = $_FILES['arquivo_imagem'] ?? null;
    $sucesso = false;

    if (!empty($id_post)) {
        // Atualizar
        if ($dinoObj->atualizar($id_post, $dados_form, $arquivo)) {
            $sucesso = true;
            header("Location: admin.php?status=success_update");
            exit();
        }
    } else {
        // Cadastrar
        if ($dinoObj->cadastrar($dados_form, $arquivo)) {
            $sucesso = true;
            header("Location: admin.php?status=success_create");
            exit();
        }
    }

    if (!$sucesso) {
        $mensagem = "<div class='alert alert-danger'>Erro ao salvar. Verifique se enviou uma imagem válida (JPG/PNG).</div>";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title><?php echo str_replace(['➕ ', '✏️ '], '', $titulo); ?> - Âmbar</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

    <div class="container my-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="mb-0"><?php echo $titulo; ?></h1>
            <a href="admin.php" class="btn btn-secondary">Voltar</a>
        </div>
        
        <?php echo $mensagem; ?>

        <div class="card p-4 shadow-sm">
            <form method="POST" action="cadastrar.php<?php echo $dino_id ? '?id='.$dino_id : ''; ?>" enctype="multipart/form-data">
                
                <input type="hidden" name="dino_id" value="<?php echo $dino['id']; ?>">
                
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Nome da Espécie</label>
                        <input type="text" class="form-control" name="nome" value="<?php echo htmlspecialchars($dino['nome']); ?>" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Imagem do Dinossauro</label>
                        <input type="file" class="form-control" name="arquivo_imagem" accept="image/*" <?php echo empty($dino['id']) ? 'required' : ''; ?>>
                        <?php if (!empty($dino['imagem_url'])): ?>
                            <div class="form-text">Atual: <?php echo $dino['imagem_url']; ?> (Deixe vazio para manter)</div>
                        <?php endif; ?>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Período Geológico</label>
                        <select class="form-select" name="periodo" required>
                            <option value="">Selecione...</option>
                            <option value="Triássico" <?php echo ($dino['periodo'] == 'Triássico') ? 'selected' : ''; ?>>Triássico</option>
                            <option value="Jurássico" <?php echo ($dino['periodo'] == 'Jurássico') ? 'selected' : ''; ?>>Jurássico</option>
                            <option value="Cretáceo" <?php echo ($dino['periodo'] == 'Cretáceo') ? 'selected' : ''; ?>>Cretáceo</option>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Dieta</label>
                        <select class="form-select" name="dieta" required>
                            <option value="">Selecione...</option>
                            <option value="Herbívoro" <?php echo ($dino['dieta'] == 'Herbívoro') ? 'selected' : ''; ?>>Herbívoro</option>
                            <option value="Carnívoro" <?php echo ($dino['dieta'] == 'Carnívoro') ? 'selected' : ''; ?>>Carnívoro</option>
                            <option value="Onívoro" <?php echo ($dino['dieta'] == 'Onívoro') ? 'selected' : ''; ?>>Onívoro</option>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Tamanho Estimado</label>
                        <input type="text" class="form-control" name="tamanho" value="<?php echo htmlspecialchars($dino['tamanho']); ?>" placeholder="Ex: 12 metros">
                    </div>

                    <div class="col-12">
                        <label class="form-label">Habitat</label>
                        <input type="text" class="form-control" name="habitat" value="<?php echo htmlspecialchars($dino['habitat']); ?>" placeholder="Ex: América do Norte">
                    </div>

                    <div class="col-12">
                        <label class="form-label">Curiosidades e Ficha Técnica</label>
                        <textarea class="form-control" name="curiosidades" rows="5" required><?php echo htmlspecialchars($dino['curiosidades']); ?></textarea>
                    </div>

                    <div class="col-12 mt-4">
                        <button type="submit" class="btn btn-primary btn-lg"><?php echo $botao_texto; ?></button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>