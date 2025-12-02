<?php
session_start();

if (!isset($_SESSION['usuario_id']) || $_SESSION['nivel_acesso'] !== 'admin') {
    header("Location: login.php");
    exit();
}

include 'config/conexao.php';

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $dinoObj = new Dinossauro();
    
    if ($dinoObj->excluir($_GET['id'])) {
        header("Location: admin.php?status=success_delete");
    } else {
        header("Location: admin.php?status=error_delete");
    }
} else {
    header("Location: admin.php");
}
exit();
?>