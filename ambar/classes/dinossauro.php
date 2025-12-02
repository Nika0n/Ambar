<?php
require_once 'database.php';

class Dinossauro {
    private $conn;

    public function __construct() {
        $this->conn = Database::getConnection();
    }

    public function listar($filtros = []) {
        $sql = "SELECT * FROM dinossauros";
        $params = [];
        $clausulas = [];

        if (!empty($filtros['dieta'])) {
            $clausulas[] = "dieta = :dieta";
            $params[':dieta'] = $filtros['dieta'];
        }

        if (!empty($filtros['periodo'])) {
            $clausulas[] = "periodo = :periodo";
            $params[':periodo'] = $filtros['periodo'];
        }

        if (!empty($clausulas)) {
            $sql .= " WHERE " . implode(" AND ", $clausulas);
        }

        $sql .= " ORDER BY nome ASC";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function buscar($id) {
        $sql = "SELECT * FROM dinossauros WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':id', $id);
        $stmt->execute();
        return $stmt->fetch();
    }

    public function cadastrar($dados, $arquivo) {
        $nome_imagem = $this->uploadImagem($arquivo);
        
        if (!$nome_imagem) {
            return false; 
        }

        $sql = "INSERT INTO dinossauros (nome, imagem_url, periodo, dieta, tamanho, habitat, curiosidades) 
                VALUES (:nome, :imagem, :periodo, :dieta, :tamanho, :habitat, :curiosidades)";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':nome', $dados['nome']);
        $stmt->bindValue(':imagem', $nome_imagem);
        $stmt->bindValue(':periodo', $dados['periodo']);
        $stmt->bindValue(':dieta', $dados['dieta']);
        $stmt->bindValue(':tamanho', $dados['tamanho']);
        $stmt->bindValue(':habitat', $dados['habitat']);
        $stmt->bindValue(':curiosidades', $dados['curiosidades']);

        return $stmt->execute();
    }

    public function atualizar($id, $dados, $arquivo) {
        $campos = "nome = :nome, periodo = :periodo, dieta = :dieta, tamanho = :tamanho, habitat = :habitat, curiosidades = :curiosidades";
        
        // Se uma nova imagem foi enviada, processa o upload
        $nova_imagem = null;
        if (!empty($arquivo['name'])) {
            $nova_imagem = $this->uploadImagem($arquivo);
            if ($nova_imagem) {
                $campos .= ", imagem_url = :imagem";
            }
        }

        $sql = "UPDATE dinossauros SET $campos WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        
        $stmt->bindValue(':nome', $dados['nome']);
        $stmt->bindValue(':periodo', $dados['periodo']);
        $stmt->bindValue(':dieta', $dados['dieta']);
        $stmt->bindValue(':tamanho', $dados['tamanho']);
        $stmt->bindValue(':habitat', $dados['habitat']);
        $stmt->bindValue(':curiosidades', $dados['curiosidades']);
        $stmt->bindValue(':id', $id);

        if ($nova_imagem) {
            $stmt->bindValue(':imagem', $nova_imagem);
        }

        return $stmt->execute();
    }

    public function excluir($id) {
        $sql = "DELETE FROM dinossauros WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':id', $id);
        return $stmt->execute();
    }

    private function uploadImagem($arquivo) {
        if (!isset($arquivo) || $arquivo['error'] != UPLOAD_ERR_OK) {
            return null;
        }

        $extensao = strtolower(pathinfo($arquivo['name'], PATHINFO_EXTENSION));
        $permitidas = ['jpg', 'jpeg', 'png', 'gif'];

        if (in_array($extensao, $permitidas)) {
            $novo_nome = uniqid() . "." . $extensao;
            $destino = __DIR__ . "/../assets/img/" . $novo_nome;

            if (move_uploaded_file($arquivo['tmp_name'], $destino)) {
                return $novo_nome;
            }
        }
        return null;
    }
}

?>
