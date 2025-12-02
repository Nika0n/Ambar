<?php
// Pega a URL solicitada
$request = $_SERVER['REQUEST_URI'];

// Remove query strings (?id=1)
$request = strtok($request, '?');

// Remove a barra inicial
$file = ltrim($request, '/');

// Se for a raiz, carrega o index.php principal
if ($file === '' || $file === 'index.php') {
    require __DIR__ . '/../index.php';
    exit;
}

// Se o arquivo existir na raiz, carrega ele
if (file_exists(__DIR__ . '/../' . $file)) {
    require __DIR__ . '/../' . $file;
} else {
    // Se não achar, carrega o index (ou mostra 404)
    require __DIR__ . '/../index.php';
}
?>
