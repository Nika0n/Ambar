<?php
// Define o caminho base da aplicação
$baseDir = __DIR__ . '/../';

// Carrega as classes manualmente (usando nomes minúsculos para compatibilidade Linux)
if (file_exists($baseDir . 'classes/database.php')) {
    require_once $baseDir . 'classes/database.php';
}

if (file_exists($baseDir . 'classes/usuario.php')) {
    require_once $baseDir . 'classes/usuario.php';
}

if (file_exists($baseDir . 'classes/dinossauro.php')) {
    require_once $baseDir . 'classes/dinossauro.php';
}

if (file_exists($baseDir . 'classes/noticia.php')) {
    require_once $baseDir . 'classes/noticia.php';
}

// Fallback para ambiente Vercel (caso a estrutura de pastas mude no build)
if (!class_exists('Database')) {
    $docRoot = $_SERVER['DOCUMENT_ROOT'] . '/';
    if (file_exists($docRoot . 'classes/database.php')) {
        require_once $docRoot . 'classes/database.php';
        require_once $docRoot . 'classes/usuario.php';
        require_once $docRoot . 'classes/dinossauro.php';
        require_once $docRoot . 'classes/noticia.php';
    }
}
?>
