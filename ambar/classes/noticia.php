<?php
require_once 'Database.php';

class Noticia {
    private $conn;

    public function __construct() {
        $this->conn = Database::getConnection();
    }

    public function listar() {
        $sql = "SELECT n.*, u.nome as autor 
                FROM noticias n 
                JOIN usuarios u ON n.usuario_id = u.id 
                ORDER BY n.data_publicacao DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function publicarLink($link, $usuario_id) {
        // 1. Busca os metadados do site (Raspagem)
        $meta = $this->obterMetadados($link);

        // Se não conseguiu ler o site, usa valores padrão
        $titulo = $meta['title'] ?? 'Link Compartilhado';
        $descricao = $meta['description'] ?? $link;
        $imagem = $meta['image'] ?? 'assets/img/default-news.jpg'; // Tenha uma img padrão

        $sql = "INSERT INTO noticias (link_original, titulo_site, descricao_site, imagem_site, usuario_id) 
                VALUES (:link, :titulo, :descricao, :imagem, :uid)";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':link', $link);
        $stmt->bindValue(':titulo', $titulo);
        $stmt->bindValue(':descricao', $descricao);
        $stmt->bindValue(':imagem', $imagem);
        $stmt->bindValue(':uid', $usuario_id);
        
        return $stmt->execute();
    }

    public function excluir($id) {
        $sql = "DELETE FROM noticias WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':id', $id);
        return $stmt->execute();
    }

    // --- A MÁGICA DO LINK PREVIEW ---
    private function obterMetadados($url) {
        // Valida se é uma URL válida
        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            return [];
        }

        // Tenta baixar o HTML da página
        // O '@' suprime erros se o site bloquear o acesso
        $html = @file_get_contents($url);

        if (!$html) return [];

        $doc = new DOMDocument();
        @$doc->loadHTML($html); // O '@' evita warnings de HTML malformado

        $tags = $doc->getElementsByTagName('meta');
        $metadata = [];

        foreach ($tags as $tag) {
            // Busca tags Open Graph (padrão do Facebook/WhatsApp)
            if ($tag->getAttribute('property') == 'og:title') {
                $metadata['title'] = $tag->getAttribute('content');
            }
            if ($tag->getAttribute('property') == 'og:description') {
                $metadata['description'] = $tag->getAttribute('content');
            }
            if ($tag->getAttribute('property') == 'og:image') {
                $metadata['image'] = $tag->getAttribute('content');
            }
        }

        // Fallback: Se não achar OG tags, tenta tags normais
        if (empty($metadata['title'])) {
            $nodes = $doc->getElementsByTagName('title');
            $metadata['title'] = $nodes->length > 0 ? $nodes->item(0)->nodeValue : '';
        }
        if (empty($metadata['description'])) {
            foreach ($tags as $tag) {
                if ($tag->getAttribute('name') == 'description') {
                    $metadata['description'] = $tag->getAttribute('content');
                }
            }
        }

        return $metadata;
    }
}
?>