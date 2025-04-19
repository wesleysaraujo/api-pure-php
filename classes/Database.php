<?php
class Database
{
    private static $instance = null;
    private $connect;

    private function __construct()
    {
        $this->connect = null;
        try {

            $host = getenv('DB_HOST') ?: 'localhost';
            $dbname = getenv('DB_NAME') ?: 'api_database';
            $username = getenv('DB_USER') ?: 'username';
            $password = getenv('DB_PASSWORD') ?: 'password';

            $this->connect = new \PDO(
                "mysql:host={$host};dbname={$dbname};charset=utf8",
                $username,
                $password,
                [\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION]
            );
        } catch (\PDOException $e) {
            responseJson(
                [
                    'error' => 'Erro ao conectar ao banco de dados: ' . $e->getMessage()
                ],
                500
            );
        }
    }

    // Método para capturar a instancia da conexão
    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new Database();
        }

        return self::$instance;
    }

    // Método para pegar a conexão PDO
    public function getConnection()
    {
        return $this->connect;
    }
    // Método que bloqueia a clonagem do objeto de conexão
    private function __clone() {}

    // Método que bloqueia a desserialização do objeto de conexão
    public function __wakeup() {
        throw new \Exception("Não é possível deserializar singleton.");
    }
}