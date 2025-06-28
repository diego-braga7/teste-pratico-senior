<?php

namespace Src\Database;

use Dotenv\Exception\InvalidFileException;
use PDO;
use PDOException;
use Src\LoggerFactory;

class DatabaseConnection
{
    private PDO $pdo;

    /**
     *
     * @throws PDOException se não conseguir conectar.
     */
    public function __construct()
    {
        $host     = $_ENV['DB_HOST'] ?? getenv('DB_HOST');
        $db     = $_ENV['MYSQL_DATABASE'] ?? getenv('MYSQL_DATABASE');
        $user     = $_ENV['MYSQL_USER'] ?? getenv('MYSQL_USER');
        $pass     = $_ENV['MYSQL_ROOT_PASSWORD'] ?? getenv('MYSQL_PASSWORD');
        $port     = $_ENV['DB_PORT'] ?? getenv('MYSQL_PASSWORD');
        $charset  = 'utf8mb4';

        $dsn = sprintf(
            'mysql:host=%s;port=%s;dbname=%s;charset=%s',
            $host,
            $port,
            $db,
            $charset
        );

        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];

        $this->pdo = new PDO($dsn, $user, $pass, $options);
    }


    public function getConnection(): PDO
    {
        return $this->pdo;
    }
}
