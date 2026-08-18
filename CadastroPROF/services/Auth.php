<?php

declare(strict_types=1);

class Auth
{
    private Professor $professorModel;

    public function __construct(Professor $professorModel)
    {
        $this->professorModel = $professorModel;
    }

    public function login(string $email, string $senha): bool
    {
        $professor = $this->professorModel->buscarPorEmail($email);

        if (!$professor) {
            return false;
        }

        if (!$professor['ativo']) {
            return false;
        }

        if (!password_verify($senha, $professor['senha'])) {
            return false;
        }

        session_regenerate_id(true);

        $_SESSION['professor'] = [
            'id' => $professor['id'],
            'nome' => $professor['nome'],
            'ra' => $professor['ra'],
            'email' => $professor['email']
        ];

        return true;
    }

    public function logout(): void
    {
        $_SESSION = [];

        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();

            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params['path'],
                $params['domain'],
                $params['secure'],
                $params['httponly']
            );
        }

        session_destroy();
    }

    public function estaAutenticado(): bool
    {
        return isset($_SESSION['professor']);
    }

    public function professor(): ?array
    {
        return $_SESSION['professor'] ?? null;
    }
}