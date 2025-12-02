<?php
spl_autoload_register(function ($nome_classe) {
    $caminho = __DIR__ . '/../classes/' . $nome_classe . '.php';
    if (file_exists($caminho)) {
        require_once $caminho;
    }
});
?>