<?php
declare(strict_types=1);

require_once __DIR__ . '/Session.php';

class Auth
{
    public function __construct(private Professor $professorModel) {}

    public function loginPorRA(string $ra, string $senha): bool
    {
        return $this->autenticar($this->professorModel->buscarPorRA($ra), $senha);
    }

    public function login(string $email, string $senha): bool
    {
        return $this->autenticar($this->professorModel->buscarPorEmail($email), $senha);
    }

    private function autenticar(?array $professor, string $senha): bool
    {
        if (!$professor || !password_verify($senha, $professor['senha'])) {
            return false;
        }

        Session::regenerar();

        $_SESSION['professor'] = [
            'id' => (int) $professor['id'],
            'nome' => $professor['nome'],
            'ra' => $professor['ra'],
            'email' => $professor['email'],
            'tipo' => $professor['tipo'],
        ];

        return true;
    }

    public function estaAutenticado(): bool
    {
        return isset($_SESSION['professor']['id']);
    }

    public function professor(): ?array
    {
        return $_SESSION['professor'] ?? null;
    }

    public function logout(): void
    {
        Session::destruir();
    }
}
