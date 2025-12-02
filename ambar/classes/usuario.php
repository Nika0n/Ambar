<?php
require_once 'Database.php';

class Usuario {
    private $conn;

    public function __construct() {
        $this->conn = Database::getConnection();
    }

    public function cadastrar($nome, $email, $senha) {
        if ($this->emailExiste($email)) {
            return false;
        }

        $sql = "INSERT INTO usuarios (nome, email, senha_hash, nivel_acesso) VALUES (:nome, :email, :senha, 'comum')";
        $stmt = $this->conn->prepare($sql);
        
        $stmt->bindValue(':nome', $nome);
        $stmt->bindValue(':email', $email);
        $stmt->bindValue(':senha', password_hash($senha, PASSWORD_DEFAULT));

        return $stmt->execute();
    }

    public function logar($email, $senha) {
        $sql = "SELECT id, nome, senha_hash, nivel_acesso FROM usuarios WHERE email = :email";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':email', $email);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $dados = $stmt->fetch();
            if (password_verify($senha, $dados['senha_hash'])) {
                return $dados;
            }
        }
        return false;
    }

    public function emailExiste($email) {
        $sql = "SELECT id FROM usuarios WHERE email = :email";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':email', $email);
        $stmt->execute();
        return $stmt->rowCount() > 0;
    }

    public function listar() {
        $sql = "SELECT * FROM usuarios ORDER BY nome ASC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function excluir($id) {
        $sql = "DELETE FROM usuarios WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':id', $id);
        return $stmt->execute();
    }
}
?>