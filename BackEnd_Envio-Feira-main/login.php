<?php
require_once __DIR__ . '/conexao.php';
?>
<!doctype html>
<html lang="pt-BR">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#7a0714">
    <meta name="description" content="Login do professor da Feira Tecnológica.">
    <title>Login do Professor | Feira Tecnológica</title>
    <link rel="stylesheet" href="style.css">
  </head>

  <body data-page="login">

    <main class="tela-acesso">
      <section class="estrutura-acesso">

        <span class="chamada-acesso">login oficial</span>

        <!-- CORRIGIDO: dashboard.php → dashboard.php -->
        <form class="cartao-acesso" id="login-form" action="dashboard.php" method="get">

          <span class="emblema-marca acesso" aria-label="MCM">MCM</span>

          <div class="titulo-acesso">
            <h1>Login do Professor</h1>
            <p>Acesse com suas credenciais institucionais</p>
          </div>

          <div class="grupo-campo">
            <label for="institutional-email">
              E-mail institucional
            </label>

            <div class="caixa-campo">
              <svg class="icone" aria-hidden="true" viewBox="0 0 24 24">
                <path d="M4 5h16v14H4z"></path>
                <path d="m4 7 8 6 8-6"></path>
              </svg>

              <input
                id="institutional-email"
                name="email"
                type="email"
                value="professor@etec.sp.gov.br"
                required
              >
            </div>
          </div>


          <div class="grupo-campo">
            <label for="registration">
              Matrícula
            </label>

            <div class="caixa-campo">
              <svg class="icone" aria-hidden="true" viewBox="0 0 24 24">
                <path d="M5 4h14v16H5z"></path>
                <path d="M8 8h8M8 12h8M8 16h5"></path>
              </svg>

              <input
                id="registration"
                name="registration"
                placeholder="Digite sua matrícula"
                required
              >
            </div>
          </div>


          <div class="grupo-campo">
            <label for="password">
              Senha
            </label>

            <div class="caixa-campo">

              <svg class="icone" aria-hidden="true" viewBox="0 0 24 24">
                <rect x="5" y="10" width="14" height="10" rx="2"></rect>
                <path d="M8 10V7a4 4 0 0 1 8 0v3"></path>
              </svg>

              <input
                id="password"
                name="password"
                type="password"
                placeholder="Senha padrão"
                required
              >

              <button
                id="toggle-password"
                type="button"
                aria-label="Mostrar senha"
              >
                <svg
                  class="icone"
                  aria-hidden="true"
                  viewBox="0 0 24 24"
                >
                  <path d="M2 12s3.5-6 10-6 10 6 10 6-3.5 6-10 6S2 12 2 12Z"></path>
                  <circle cx="12" cy="12" r="2.5"></circle>
                </svg>
              </button>

            </div>
          </div>


          <p class="nota-campo">
            <svg class="icone" aria-hidden="true" viewBox="0 0 24 24">
              <path d="M12 3 5 6v5c0 4.6 2.9 8 7 10 4.1-2 7-5.4 7-10V6l-7-3Z"></path>
              <path d="m9 12 2 2 4-4"></path>
            </svg>

            No primeiro acesso, utilize a senha padrão fornecida pela secretaria.
          </p>


          <!-- BOTÃO QUE LEVA AO DASHBOARD -->
          <button
            class="botao-principal"
            type="submit"
          >
            Entrar

            <svg
              class="icone"
              aria-hidden="true"
              viewBox="0 0 24 24"
            >
              <path d="M5 12h14M13 6l6 6-6 6"></path>
            </svg>
          </button>


          <button
            class="voltar-acesso"
            id="login-return"
            type="button"
          >
            <svg
              class="icone"
              aria-hidden="true"
              viewBox="0 0 24 24"
            >
              <path d="M19 12H5M11 18l-6-6 6-6"></path>
            </svg>

            Voltar para seleção de perfil
          </button>

        </form>

      </section>
    </main>


    <script src="script.js"></script>

  </body>
</html>
