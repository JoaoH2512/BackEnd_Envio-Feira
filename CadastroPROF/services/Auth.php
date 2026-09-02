<?php
declare(strict_types=1);

require_once __DIR__ . '/Session.php';

class Auth
{
    private Professor $professorModel;

    public function __construct(Professor $professorModel)
    {
        $this->professorModel = $professorModel;
    }

    public function loginPorRA(string $ra, string $senha): bool
    {
        $professor = $this->professorModel->buscarPorRA($ra);

        if (!$professor || !(int) $professor['ativo']) {
            return false;
        }

        if (!password_verify($senha, $professor['senha'])) {
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

    public function login(string $email, string $senha): bool
    {
        $professor = $this->professorModel->buscarPorEmail($email);

        if (!$professor || !(int) $professor['ativo']) {
            return false;
        }

        if (!password_verify($senha, $professor['senha'])) {
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