<?php

declare(strict_types=1);

class Database
{
    private string $host = 'localhost';
    private string $database = 'sistema_professor';
    private string $username = 'root';
    private string $password = '';

    private ?PDO $connection = null;

    public function connect(): PDO
    {
        if ($this->connection !== null) {
            return $this->connection;
        }

        $dsn = "mysql:host={$this->host};dbname={$this->database};charset=utf8mb4";

        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ];

        $this->connection = new PDO(
            $dsn,
            $this->username,
            $this->password,
            $options
        );

        return $this->connection;
    }
}