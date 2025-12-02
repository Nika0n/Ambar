<?php
class Database {
    private static $conn = null;

    public static function getConnection() {
        // Tenta pegar variáveis de ambiente (Vercel). Se não tiver, usa o padrão XAMPP.
        $host = getenv('DB_HOST') ?: 'localhost';
        $port = getenv('DB_PORT') ?: '3306';
        $db_name = getenv('DB_NAME') ?: 'ambar';
        $username = getenv('DB_USER') ?: 'root';
        $password = getenv('DB_PASS') ?: '';

        if (self::$conn === null) {
            try {
                // SSL é obrigatório para bancos na nuvem (TiDB/Aiven)
                $opcoes = [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                ];

                // Se estiver na nuvem (não for localhost), adiciona configuração SSL
                if ($host !== 'localhost') {
                    $opcoes[PDO::MYSQL_ATTR_SSL_CA] = '/etc/ssl/certs/ca-certificates.crt';
                }

                self::$conn = new PDO(
                    "mysql:host=$host;port=$port;dbname=$db_name;charset=utf8", 
                    $username, 
                    $password,
                    $opcoes
                );
            } catch(PDOException $exception) {
                echo "Erro de conexão: " . $exception->getMessage();
                exit();
            }
        }
        return self::$conn;
    }
}
?>