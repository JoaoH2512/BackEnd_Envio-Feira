<?php

session_start();

// banco_feira

/*
|--------------------------------------------------------------------------
| CREDENCIAIS PROVISÓRIAS
|--------------------------------------------------------------------------
*/

$emailCorreto = 'arthur.silva175@aluno.cps.sp.gov.br';
$raCorreto    = '24407';
$senhaCorreta = 'evex-IS-back';

$erro = '';

/*
|--------------------------------------------------------------------------
| LOGIN
|--------------------------------------------------------------------------
*/

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $email = trim($_POST['email'] ?? '');
    $ra    = trim($_POST['ra'] ?? '');
    $senha = $_POST['senha'] ?? '';

    /*
    |--------------------------------------------------------------------------
    | VALIDAÇÃO
    |--------------------------------------------------------------------------
    */

    if (
        $email === $emailCorreto &&
        $ra === $raCorreto &&
        $senha === $senhaCorreta
    ) {

        /*
        |--------------------------------------------------------------------------
        | LOGIN CORRETO
        |--------------------------------------------------------------------------
        */

        session_regenerate_id(true);

        $_SESSION['logado'] = true;
        $_SESSION['nome']   = 'Arthur Silva';
        $_SESSION['email']  = $email;
        $_SESSION['ra']     = $ra;

        header('Location: correto.php');
        exit;

    } else {

        /*
        |--------------------------------------------------------------------------
        | LOGIN INCORRETO
        |--------------------------------------------------------------------------
        */

        $erro = 'Os dados informados não conferem. Verifique seu e-mail, RA e senha.';
    }
}

?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Login</title>

    <style>

        /* =========================================================
           RESET
           ========================================================= */

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html,
        body {
            min-height: 100%;
        }

        /* =========================================================
           BODY
           ========================================================= */

        body {

            min-height: 100vh;

            font-family:
                Inter,
                "Segoe UI",
                Roboto,
                Arial,
                sans-serif;

            color: #172033;

            background:
                radial-gradient(
                    circle at 15% 20%,
                    rgba(37, 99, 235, 0.18),
                    transparent 30%
                ),

                radial-gradient(
                    circle at 85% 80%,
                    rgba(14, 165, 233, 0.15),
                    transparent 32%
                ),

                linear-gradient(
                    135deg,
                    #eef4ff 0%,
                    #f8fafc 45%,
                    #e9f1ff 100%
                );

            display: flex;

            align-items: center;

            justify-content: center;

            position: relative;

            overflow-x: hidden;
        }

        /* =========================================================
           ELEMENTOS DECORATIVOS
           ========================================================= */

        body::before {

            content: "";

            position: fixed;

            width: 420px;
            height: 420px;

            border-radius: 50%;

            background:
                linear-gradient(
                    135deg,
                    rgba(37, 99, 235, 0.10),
                    rgba(59, 130, 246, 0.02)
                );

            top: -180px;
            right: -140px;

            filter: blur(2px);

            pointer-events: none;
        }

        body::after {

            content: "";

            position: fixed;

            width: 360px;
            height: 360px;

            border-radius: 50%;

            background:
                linear-gradient(
                    135deg,
                    rgba(14, 165, 233, 0.10),
                    rgba(37, 99, 235, 0.02)
                );

            bottom: -180px;
            left: -130px;

            pointer-events: none;
        }

        /* =========================================================
           CONTAINER
           ========================================================= */

        .page {

            width: 100%;

            min-height: 100vh;

            display: flex;

            align-items: center;

            justify-content: center;

            padding: 40px 20px;

            position: relative;

            z-index: 1;
        }

        /* =========================================================
           CARD
           ========================================================= */

        .login-card {

            width: 100%;

            max-width: 460px;

            background:
                rgba(255, 255, 255, 0.88);

            border:
                1px solid rgba(255, 255, 255, 0.8);

            border-radius: 28px;

            padding: 42px 44px 35px;

            box-shadow:
                0 30px 80px rgba(30, 64, 175, 0.13),
                0 8px 25px rgba(15, 23, 42, 0.06);

            backdrop-filter: blur(18px);

            -webkit-backdrop-filter: blur(18px);

            position: relative;

            overflow: hidden;

            animation:
                cardAppear 0.65s ease;
        }

        .login-card::before {

            content: "";

            position: absolute;

            top: 0;
            left: 0;
            right: 0;

            height: 5px;

            background:
                linear-gradient(
                    90deg,
                    #1d4ed8,
                    #2563eb,
                    #38bdf8
                );
        }

        @keyframes cardAppear {

            from {

                opacity: 0;

                transform:
                    translateY(20px)
                    scale(0.98);
            }

            to {

                opacity: 1;

                transform:
                    translateY(0)
                    scale(1);
            }
        }

        /* =========================================================
           LOGO
           ========================================================= */

        .logo-area {

            display: flex;

            flex-direction: column;

            align-items: center;

            justify-content: center;

            margin-bottom: 26px;
        }

        .logo-box {

            width: 138px;

            height: 138px;

            border-radius: 28px;

            display: flex;

            align-items: center;

            justify-content: center;

            background:
                linear-gradient(
                    145deg,
                    #ffffff,
                    #f4f7fb
                );

            border:
                1px solid #e5eaf2;

            box-shadow:
                0 15px 35px
                rgba(30, 64, 175, 0.10);

            padding: 17px;

            margin-bottom: 20px;
        }

        .logo {

            width: 100%;

            height: 100%;

            object-fit: contain;
        }

        /* =========================================================
           CABEÇALHO
           ========================================================= */

        .header {

            text-align: center;

            margin-bottom: 30px;
        }

        .header h1 {

            font-size: 28px;

            line-height: 1.2;

            letter-spacing: -0.7px;

            color: #172033;

            margin-bottom: 9px;
        }

        .header p {

            font-size: 14px;

            line-height: 1.6;

            color: #64748b;
        }

        /* =========================================================
           ALERTA DE ERRO
           ========================================================= */

        .alert {

            display: flex;

            align-items: flex-start;

            gap: 11px;

            padding: 13px 15px;

            border-radius: 13px;

            margin-bottom: 22px;

            background: #fff1f2;

            border:
                1px solid #fecdd3;

            color: #be123c;

            font-size: 13px;

            line-height: 1.5;
        }

        .alert-icon {

            min-width: 20px;

            height: 20px;

            display: flex;

            align-items: center;

            justify-content: center;

            border-radius: 50%;

            background: #e11d48;

            color: white;

            font-size: 12px;

            font-weight: 800;
        }

        /* =========================================================
           FORMULÁRIO
           ========================================================= */

        .form {

            display: flex;

            flex-direction: column;

            gap: 19px;
        }

        .field {

            position: relative;
        }

        .field label {

            display: block;

            font-size: 13px;

            font-weight: 700;

            color: #334155;

            margin-bottom: 8px;

            padding-left: 2px;
        }

        .input-wrapper {

            position: relative;
        }

        .input-icon {

            position: absolute;

            left: 15px;

            top: 50%;

            transform: translateY(-50%);

            color: #94a3b8;

            font-size: 16px;

            pointer-events: none;

            width: 20px;

            text-align: center;
        }

        .field input {

            width: 100%;

            height: 52px;

            padding:
                0 15px
                0 45px;

            border:
                1px solid #dbe2ec;

            border-radius: 13px;

            background: #f8fafc;

            color: #172033;

            outline: none;

            font-size: 14px;

            transition:
                border-color 0.2s ease,
                box-shadow 0.2s ease,
                background 0.2s ease,
                transform 0.2s ease;
        }

        .field input::placeholder {

            color: #a5afbd;
        }

        .field input:hover {

            background: #ffffff;

            border-color: #cbd5e1;
        }

        .field input:focus {

            background: #ffffff;

            border-color: #3b82f6;

            box-shadow:
                0 0 0 4px
                rgba(59, 130, 246, 0.11);

            transform: translateY(-1px);
        }

        /* =========================================================
           BOTÃO
           ========================================================= */

        .submit-button {

            height: 53px;

            width: 100%;

            border: 0;

            border-radius: 13px;

            margin-top: 5px;

            background:
                linear-gradient(
                    135deg,
                    #1d4ed8,
                    #2563eb 55%,
                    #0284c7
                );

            color: white;

            font-size: 14px;

            font-weight: 800;

            letter-spacing: 0.2px;

            cursor: pointer;

            box-shadow:
                0 10px 24px
                rgba(37, 99, 235, 0.25);

            transition:
                transform 0.2s ease,
                box-shadow 0.2s ease,
                filter 0.2s ease;
        }

        .submit-button:hover {

            transform: translateY(-2px);

            box-shadow:
                0 15px 30px
                rgba(37, 99, 235, 0.30);

            filter: brightness(1.03);
        }

        .submit-button:active {

            transform: translateY(0);

            box-shadow:
                0 7px 15px
                rgba(37, 99, 235, 0.22);
        }

        .button-content {

            display: flex;

            align-items: center;

            justify-content: center;

            gap: 9px;
        }

        .arrow {

            font-size: 17px;

            transition:
                transform 0.2s ease;
        }

        .submit-button:hover .arrow {

            transform: translateX(4px);
        }

        /* =========================================================
           RODAPÉ
           ========================================================= */

        .footer {

            text-align: center;

            color: #94a3b8;

            font-size: 11px;

            line-height: 1.6;

            margin-top: 25px;
        }

        .footer strong {

            color: #64748b;

            font-weight: 700;
        }

        /* =========================================================
           RESPONSIVO
           ========================================================= */

        @media (max-width: 520px) {

            .page {

                padding: 20px 15px;
            }

            .login-card {

                padding:
                    34px 23px 28px;

                border-radius: 23px;
            }

            .logo-box {

                width: 115px;

                height: 115px;

                border-radius: 23px;

                padding: 14px;
            }

            .header h1 {

                font-size: 24px;
            }
        }

        @media (max-height: 720px) {

            .page {

                align-items: flex-start;

                padding-top: 25px;

                padding-bottom: 25px;
            }

            .logo-box {

                width: 100px;

                height: 100px;
            }

            .logo-area {

                margin-bottom: 18px;
            }

            .header {

                margin-bottom: 22px;
            }
        }

    </style>

</head>

<body>

    <main class="page">

        <section class="login-card">

            <!-- =====================================================
                 LOGO
                 ===================================================== -->

            <div class="logo-area">

                <div class="logo-box">

                    <img
                        src="logo.png"
                        alt="Logo institucional"
                        class="logo"
                    >

                </div>

            </div>

            <!-- =====================================================
                 CABEÇALHO
                 ===================================================== -->

            <header class="header">

                <h1>
                    Bem-vindo!
                </h1>

                <p>
                    Acesse sua conta utilizando seus
                    dados institucionais.
                </p>

            </header>

            <!-- =====================================================
                 ERRO
                 ===================================================== -->

            <?php if ($erro !== ''): ?>

                <div class="alert">

                    <span class="alert-icon">
                        !
                    </span>

                    <span>
                        <?= htmlspecialchars($erro) ?>
                    </span>

                </div>

            <?php endif; ?>

            <!-- =====================================================
                 FORMULÁRIO
                 ===================================================== -->

            <form
                method="POST"
                action=""
                class="form"
            >

                <!-- E-MAIL -->

                <div class="field">

                    <label for="email">
                        E-mail institucional
                    </label>

                    <div class="input-wrapper">

                        <span class="input-icon">
                            ✉
                        </span>

                        <input
                            type="email"
                            id="email"
                            name="email"
                            placeholder="seu.email@aluno.cps.sp.gov.br"
                            autocomplete="username"
                            required
                        >

                    </div>

                </div>

                <!-- RA -->

                <div class="field">

                    <label for="ra">
                        Matrícula / RA
                    </label>

                    <div class="input-wrapper">

                        <span class="input-icon">
                            #
                        </span>

                        <input
                            type="text"
                            id="ra"
                            name="ra"
                            placeholder="Digite sua matrícula ou RA"
                            autocomplete="off"
                            required
                        >

                    </div>

                </div>

                <!-- SENHA -->

                <div class="field">

                    <label for="senha">
                        Senha
                    </label>

                    <div class="input-wrapper">

                        <span class="input-icon">
                            ●
                        </span>

                        <input
                            type="password"
                            id="senha"
                            name="senha"
                            placeholder="Digite sua senha"
                            autocomplete="current-password"
                            required
                        >

                    </div>

                </div>

                <!-- =================================================
                     BOTÃO
                     ================================================= -->

                <button
                    type="submit"
                    class="submit-button"
                >

                    <span class="button-content">

                        Entrar

                    </span>

                </button>

            </form>

            <!--=====================================================
                 RODAPÉ
                 ===================================================== -->

            <footer class="footer">

                <strong>
                </strong>

                <br>

            </footer>

        </section>

    </main>

</body>

</html>