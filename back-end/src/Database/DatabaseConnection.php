<?php
namespace Src\Database;

use PDO;
use PDOException;


class DatabaseConnection
{
    private PDO $pdo;

    /**
     *
     * @throws PDOException se não conseguir conectar.
     */
    public function __construct()
    {
        $host     = getenv('DB_HOST') ?: '127.0.0.1';
        $db       = getenv('MYSQL_DATABASE') ?: '';
        $user     = getenv('MYSQL_USER') ?: '';
        $pass     = getenv('MYSQL_PASSWORD') ?: '';
        $port     = getenv('DB_PORT') ?: '3306';
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
