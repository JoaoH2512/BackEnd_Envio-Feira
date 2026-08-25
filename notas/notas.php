<?php
session_start();

// O sistema começa sempre sem avaliações. Depois do primeiro acesso, os dados salvos são preservados.
if (!isset($_SESSION['estado_inicial_zerado'])) {
    $_SESSION['avaliacoes'] = [];
    $_SESSION['estado_inicial_zerado'] = true;
}

$projetosBase = [
    1 => [
        'nome' => 'Farm Bot',
        'curso' => 'Informática para Internet',
        'turma' => '3º',
        'estande' => 'A-01',
        'status' => 'Pendente',
        'descricao' => 'Sistema automatizado para acompanhamento e irrigação inteligente de pequenas plantações.',
        'equipe' => 'Mariana Costa, Pedro Alves e João Mendes',
        'orientador' => 'Laura Martins',
    ],
    2 => [
        'nome' => 'Eco Filter',
        'curso' => 'Recursos Humanos',
        'turma' => '2º',
        'estande' => 'B-03',
        'status' => 'Pendente',
        'descricao' => 'Solução sustentável para reaproveitamento de água e redução do desperdício na escola.',
        'equipe' => 'Beatriz Lima, Rafael Santos e Ana Clara',
        'orientador' => 'Laura Martins',
    ],
    3 => [
        'nome' => 'ReciclaLú',
        'curso' => 'Informática para Internet',
        'turma' => '3º',
        'estande' => 'B-12',
        'status' => 'Pendente',
        'descricao' => 'Aplicativo para triagem inteligente de resíduos e comunicação de alertas para a comunidade escolar.',
        'equipe' => 'João Pedro, Larissa Freitas e Raquel Souza',
        'orientador' => 'Ana Beatriz Silva',
    ],
    4 => [
        'nome' => 'Edu+',
        'curso' => 'Administração',
        'turma' => '2º',
        'estande' => 'C-02',
        'status' => 'Pendente',
        'descricao' => 'Plataforma de apoio aos estudos com trilhas personalizadas para estudantes.',
        'equipe' => 'Lucas Oliveira, Camila Rocha e Victor Hugo',
        'orientador' => 'Laura Martins',
    ],
    5 => [
        'nome' => 'Smart Horta',
        'curso' => 'Informática para Internet',
        'turma' => '3º',
        'estande' => 'A-02',
        'status' => 'Pendente',
        'descricao' => 'Horta conectada com sensores de umidade e painel de acompanhamento em tempo real.',
        'equipe' => 'Gabriel Souza, Helena Dias e Felipe Nunes',
        'orientador' => 'Laura Martins',
    ],
    6 => [
        'nome' => 'Energia Solar',
        'curso' => 'Química',
        'turma' => '2º',
        'estande' => 'B-01',
        'status' => 'Pendente',
        'descricao' => 'Protótipo de baixo custo para captação de energia solar em espaços escolares.',
        'equipe' => 'Isabela Martins, Caio Mendes e Sofia Reis',
        'orientador' => 'Laura Martins',
    ],
];

// Não existem avaliações fictícias: o professor preenche cada projeto manualmente.

$pagina = $_GET['pagina'] ?? 'painel';
$modoVisualizacao = ($_GET['modo'] ?? '') === 'visualizar';
$projetoId = (int)($_GET['projeto'] ?? 3);
if (!isset($projetosBase[$projetoId])) {
    $projetoId = 3;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['salvar_avaliacao'])) {
    $projetoId = (int)($_POST['projeto_id'] ?? 3);
    if (isset($projetosBase[$projetoId])) {
        $_SESSION['avaliacoes'][$projetoId] = [
            'inovacao' => max(0, min(10, (int)round((float)($_POST['inovacao'] ?? 0)))),
            'viabilidade' => max(0, min(10, (int)round((float)($_POST['viabilidade'] ?? 0)))),
            'impacto' => max(0, min(10, (int)round((float)($_POST['impacto'] ?? 0)))),
            'apresentacao' => max(0, min(10, (int)round((float)($_POST['apresentacao'] ?? 0)))),
            'comentarios' => trim($_POST['comentarios'] ?? ''),
        ];
        $_SESSION['mensagem'] = 'Avaliação salva com sucesso.';
    }
    header('Location: ?pagina=painel');
    exit;
}

$projetos = $projetosBase;
foreach ($projetos as $id => &$projeto) {
    if (isset($_SESSION['avaliacoes'][$id])) {
        $projeto['status'] = 'Avaliado';
    }
}
unset($projeto);

foreach ($_SESSION['avaliacoes'] as &$avaliacaoSalva) {
    foreach (['inovacao', 'viabilidade', 'impacto', 'apresentacao'] as $campoNota) {
        $avaliacaoSalva[$campoNota] = max(0, min(10, (int)round((float)($avaliacaoSalva[$campoNota] ?? 0))));
    }
}
unset($avaliacaoSalva);
$avaliacoes = $_SESSION['avaliacoes'];
$pendentes = count($projetos) - count($avaliacoes);
$concluidas = count($avaliacoes);
$mediaGeral = 0;
$quantidadeNotas = 0;
foreach ($avaliacoes as $avaliacao) {
    $mediaGeral += ($avaliacao['inovacao'] + $avaliacao['viabilidade'] + $avaliacao['impacto'] + $avaliacao['apresentacao']) / 4;
    $quantidadeNotas++;
}
$mediaGeral = $quantidadeNotas ? $mediaGeral / $quantidadeNotas : 0;
$conceitoGeral = conceito($mediaGeral);
$avaliacaoAtual = $avaliacoes[$projetoId] ?? ['inovacao' => 0, 'viabilidade' => 0, 'impacto' => 0, 'apresentacao' => 0, 'comentarios' => ''];
$mediaAtual = ($avaliacaoAtual['inovacao'] + $avaliacaoAtual['viabilidade'] + $avaliacaoAtual['impacto'] + $avaliacaoAtual['apresentacao']) / 4;
$conceitoAtual = conceito($mediaAtual);
$projetoAtual = $projetos[$projetoId];
$mensagem = $_SESSION['mensagem'] ?? '';
unset($_SESSION['mensagem']);

function e($valor): string {
    return htmlspecialchars((string)$valor, ENT_QUOTES, 'UTF-8');
}
function nota($valor, int $casas = 0): string {
    return number_format((float)$valor, $casas, ',', '');
}
function conceito($media): string {
    $media = (float)$media;
    if ($media <= 4) return 'I';
    if ($media <= 6) return 'R';
    if ($media <= 8) return 'B';
    return 'MB';
}
function icone(string $nome): string {
    $icons = [
        'bell' => '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M18 8a6 6 0 0 0-12 0c0 7-3 7-3 9h18c0-2-3-2-3-9M10 21h4"/></svg>',
        'eye' => '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M2 12s3.5-6 10-6 10 6 10 6-3.5 6-10 6S2 12 2 12Z"/><circle cx="12" cy="12" r="2.5"/></svg>',
        'edit' => '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="m4 16.5-.7 3.7 3.7-.7L18.3 8.2a2.1 2.1 0 0 0-3-3L4 16.5Z"/><path d="m13.8 6.2 3 3"/></svg>',
        'book' => '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M5 4.5A2.5 2.5 0 0 1 7.5 2H20v17H7.5A2.5 2.5 0 0 0 5 21.5v-17Z"/><path d="M5 4.5v17M8 6h8M8 10h8"/></svg>',
        'check' => '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="m5 12 4 4L19 6"/></svg>',
        'clock' => '<svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/></svg>',
        'chart' => '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4 20V10M10 20V4M16 20v-7M22 20H2"/></svg>',
        'save' => '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M5 3h12l2 2v16H5V3Z"/><path d="M8 3v6h8V3M8 21v-7h8v7"/></svg>',
    ];
    return $icons[$nome] ?? '';
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pagina === 'avaliar' ? 'Avaliar projeto' : 'Painel do professor'; ?> | EcoSense</title>
    <style>
        :root { --vinho:#760b20; --vinho-escuro:#5f0819; --dourado:#bd8c19; --verde:#2f6e58; --azul:#377f9c; --texto:#332c2d; --cinza:#777173; --borda:#e5e1df; --fundo:#fffdfc; }
        * { box-sizing:border-box; }
        body { margin:0; background:var(--fundo); color:var(--texto); font:12px Arial, Helvetica, sans-serif; }
        a { color:inherit; text-decoration:none; }
        button, input, textarea { font:inherit; }
        .container { width:calc(100% - 52px); max-width:1500px; margin:0 auto; }
        .topo { padding:31px 0 16px; border-bottom:1px solid var(--borda); position:relative; }
        .voltar { display:inline-flex; align-items:center; gap:8px; color:var(--vinho); font-weight:bold; margin-bottom:18px; }
        .voltar span { font-size:20px; line-height:10px; }
        .label { display:block; text-transform:uppercase; color:#716d6d; letter-spacing:.04em; font-size:10px; font-weight:bold; }
        h1 { color:#4d0919; font-family:Georgia, 'Times New Roman', serif; font-size:22px; margin:1px 0 4px; }
        .subtitulo { margin:0; color:#777; }
        .notificacao { position:absolute; right:0; top:31px; width:33px; height:33px; border:1px solid var(--borda); border-radius:50%; background:#fff; color:var(--vinho); display:grid; place-items:center; }
        .notificacao svg { width:16px; height:16px; fill:none; stroke:currentColor; stroke-width:1.6; }
        .cards { display:grid; grid-template-columns:repeat(4,1fr); gap:15px; margin:27px 0; }
        .card { background:#fff; border:1px solid var(--borda); border-radius:7px; box-shadow:0 4px 14px rgba(48,34,30,.05); }
        .resumo { min-height:94px; padding:14px 15px 10px; border-top:3px solid var(--vinho); position:relative; }
        .resumo.dourado { border-top-color:var(--dourado); } .resumo.azul { border-top-color:var(--azul); } .resumo.verde { border-top-color:var(--verde); }
        .resumo-titulo { color:#666; font-size:11px; margin-bottom:8px; }
        .resumo-numero { font: bold 25px Georgia, serif; color:var(--vinho); }
        .resumo.dourado .resumo-numero { color:var(--dourado); } .resumo.azul .resumo-numero { color:var(--azul); } .resumo.verde .resumo-numero { color:var(--verde); }
        .resumo-rodape { color:#999; font-size:10px; margin-top:5px; }
        .resumo-icone { position:absolute; right:14px; bottom:14px; color:var(--vinho); opacity:.7; }
        .resumo-icone svg { width:21px; height:21px; fill:none; stroke:currentColor; stroke-width:1.4; }
        .resumo.dourado .resumo-icone { color:var(--dourado); } .resumo.azul .resumo-icone { color:var(--azul); } .resumo.verde .resumo-icone { color:var(--verde); }
        .secao-cabecalho { display:flex; align-items:center; justify-content:space-between; margin:22px 0 11px; }
        h2 { font: bold 16px Georgia, serif; color:#53202b; margin:0; }
        .ver-todos { color:var(--vinho); font-size:11px; font-weight:bold; }
        .tabela-wrap { background:#fff; border:1px solid var(--borda); border-radius:7px; overflow:hidden; box-shadow:0 3px 10px rgba(48,34,30,.03); }
        table { width:100%; border-collapse:collapse; }
        th, td { padding:13px 11px; text-align:left; border-bottom:1px solid #eeeae8; white-space:nowrap; }
        th { color:#777; font-size:9px; text-transform:uppercase; font-weight:bold; letter-spacing:.03em; }
        td { color:#514b4d; font-size:11px; }
        tr:last-child td { border-bottom:0; }
        .status { display:inline-block; border-radius:12px; padding:4px 8px; font-size:9px; font-weight:bold; }
        .status.ok { background:#e3f2eb; color:#317254; } .status.pendente { background:#fff2cf; color:#a6750d; }
        .acao { display:inline-grid; place-items:center; width:24px; height:24px; margin-right:7px; border:1px solid #eee2e1; color:var(--vinho); background:#fff; border-radius:3px; }
        .acao svg { width:14px; height:14px; fill:none; stroke:currentColor; stroke-width:1.5; }
        .mensagem { margin-top:16px; border:1px solid #bde1cc; border-radius:5px; background:#eefaf2; color:#276d43; padding:11px 13px; }
        .avaliacao-layout { display:grid; grid-template-columns:minmax(0, 1.65fr) minmax(280px, 1fr); gap:18px; margin-top:27px; align-items:start; }
        .painel { padding:17px 18px; }
        .projeto-cabecalho { display:flex; align-items:center; gap:12px; padding-bottom:15px; border-bottom:1px solid var(--borda); }
        .projeto-icone { width:33px; height:33px; border-radius:50%; color:#fff; background:var(--vinho); display:grid; place-items:center; }
        .projeto-icone svg { width:16px; height:16px; fill:none; stroke:currentColor; stroke-width:1.5; }
        .projeto-titulo { font-weight:bold; font-size:12px; } .projeto-meta { font-size:10px; color:#777; margin-top:4px; }
        .bloco { padding-top:17px; } .bloco + .bloco { margin-top:17px; border-top:1px solid var(--borda); }
        .bloco h3 { font-size:12px; color:#5a1d29; margin:0 0 8px; } .bloco p { margin:0; color:#666; line-height:1.5; }
        .criterio { margin-top:20px; } .criterio:first-of-type { margin-top:0; }
        .criterio-topo { display:flex; justify-content:space-between; align-items:baseline; margin-bottom:4px; }
        .criterio-nome { font-weight:bold; } .criterio-nota { color:var(--vinho); font-weight:bold; }
        .criterio-ajuda { color:#888; font-size:10px; margin-bottom:10px; }
        .controle-nota { display:grid; grid-template-columns:1fr 54px; gap:11px; align-items:center; }
        input[type=range] { appearance:none; width:100%; height:4px; border-radius:4px; background:linear-gradient(to right, var(--vinho) 0%, var(--vinho) var(--valor,50%), #e4e2e1 var(--valor,50%), #e4e2e1 100%); outline:none; }
        input[type=range]::-webkit-slider-thumb { appearance:none; width:14px; height:14px; border:3px solid #fff; background:var(--vinho); border-radius:50%; box-shadow:0 0 0 1px var(--vinho); cursor:pointer; }
        input[type=range]::-moz-range-thumb { width:10px; height:10px; border:3px solid #fff; background:var(--vinho); border-radius:50%; box-shadow:0 0 0 1px var(--vinho); cursor:pointer; }
        .campo-nota { width:54px; padding:8px 5px; border:1px solid var(--borda); border-radius:4px; text-align:center; color:#555; background:#fff; }
        textarea { width:100%; min-height:68px; resize:vertical; border:1px solid var(--borda); border-radius:5px; padding:10px; color:#555; outline:none; }
        textarea:focus, .campo-nota:focus { border-color:var(--vinho); }
        .botoes { display:flex; gap:10px; margin-top:17px; }
        .botao { border:1px solid var(--borda); border-radius:5px; background:#fff; color:var(--vinho); padding:10px 15px; font-weight:bold; cursor:pointer; }
        .botao.principal { border-color:var(--vinho); background:var(--vinho); color:#fff; }
        .botao.principal:hover { background:var(--vinho-escuro); }
        .lateral { display:grid; gap:16px; }
        .lateral .card { padding:15px; } .lateral h3 { color:#5a1d29; font-size:12px; margin:0 0 11px; padding-bottom:9px; border-bottom:1px solid var(--borda); }
        .orientador { display:flex; align-items:center; gap:10px; } .avatar { width:27px; height:27px; border-radius:50%; background:#c79626; }
        .orientador strong { display:block; font-size:11px; } .orientador small { color:#999; font-size:9px; }
        .ilustracao { height:401px; border-radius:5px; overflow:hidden; background:linear-gradient(#dfe5ce,#c5d2ae 64%,#70412d 65%,#5f3628); position:relative; }
        .sol { position:absolute; width:100px; height:100px; border-radius:50%; background:#f3efc8; right:14px; top:7px; opacity:.85; } .sol-pequeno { position:absolute; width:49px; height:49px; border-radius:50%; background:#f8da7c; left:73px; top:62px; }
        .vaso { position:absolute; width:69%; height:143px; left:15%; bottom:104px; border:6px solid #6e4a40; border-radius:18px; background:#efeee4; }
        .sensor { position:absolute; width:94px; height:57px; left:24%; top:31px; border-radius:9px; background:#223c40; } .sensor:after { content:''; position:absolute; width:30px; height:30px; border-radius:50%; left:34px; top:13px; background:#8bcf72; }
        .folha { position:absolute; border:7px solid #367d50; border-left-color:transparent; border-bottom-color:transparent; border-radius:100% 0 0 0; transform:rotate(-43deg); }
        .f1 { width:195px; height:235px; left:39%; bottom:120px; } .f2 { width:135px; height:180px; left:24%; bottom:126px; border-color:#5b9e57; border-left-color:transparent; border-bottom-color:transparent; transform:rotate(-24deg); } .f3 { width:142px; height:160px; left:48%; bottom:177px; transform:rotate(9deg); }
        .resumo-avaliacao dl { display:grid; grid-template-columns:1fr auto; gap:8px; margin:0; color:#888; font-size:10px; } .resumo-avaliacao dd { margin:0; color:#5b5556; font-weight:bold; }
        .nota-visual { display:flex; justify-content:space-between; align-items:center; padding:12px 0; border-bottom:1px solid #eeeae8; color:#555; }
        .nota-visual strong { color:var(--vinho); font-size:13px; }
        .nota-final { display:flex; justify-content:space-between; align-items:center; margin-top:14px; padding:13px 0 0; color:#4d4546; font-weight:bold; }
        .nota-final strong { color:var(--vinho); }
        .voltar-painel { margin-top:27px; }
        @media (max-width:900px) { .cards { grid-template-columns:repeat(2,1fr); } .avaliacao-layout { grid-template-columns:1fr; } .ilustracao { height:300px; } }
        @media (max-width:620px) { .container { width:calc(100% - 28px); } .cards { grid-template-columns:1fr 1fr; gap:8px; } .resumo { padding:11px; } .tabela-wrap { overflow-x:auto; } table { min-width:800px; } h1 { font-size:19px; } .notificacao { top:29px; } }
    </style>
</head>
<body>
    <header class="topo">
        <div class="container">
            <?php if ($pagina === 'avaliar'): ?>
                <a class="voltar" href="?pagina=painel"><span>‹</span> Voltar</a>
                <span class="label">Avaliações gerais</span>
                <h1>EcoSense</h1>
                <p class="subtitulo">Revise as informações para concluir a avaliação do projeto.</p>
            <?php else: ?>
                <span class="label">Painel do professor</span>
                <h1>Bem-vindo! Professor(a) Laura</h1>
                <p class="subtitulo">Confira o panorama dos projetos sob sua orientação.</p>
            <?php endif; ?>
            <div class="notificacao"><?php echo icone('bell'); ?></div>
        </div>
    </header>

            <main class="container">
        <?php if ($pagina === 'avaliar' && $modoVisualizacao): ?>
            <section class="card painel visualizacao">
                <div class="projeto-cabecalho">
                    <div class="projeto-icone"><?php echo icone('eye'); ?></div>
                    <div>
                        <div class="projeto-titulo">Visualizar: <?php echo e($projetoAtual['nome']); ?></div>
                        <div class="projeto-meta">Estande <?php echo e($projetoAtual['estande']); ?> · Equipe: <?php echo e($projetoAtual['equipe']); ?></div>
                    </div>
                </div>
                <div class="bloco">
                    <h3>Descrição do projeto</h3>
                    <p><?php echo e($projetoAtual['descricao']); ?></p>
                </div>
                <div class="bloco">
                    <h3>Notas da avaliação</h3>
                    <?php
                    $criteriosVisualizacao = [
                        'inovacao' => 'Inovação e originalidade',
                        'viabilidade' => 'Viabilidade técnica',
                        'impacto' => 'Impacto e sustentabilidade',
                        'apresentacao' => 'Apresentação e domínio do tema',
                    ];
                    foreach ($criteriosVisualizacao as $campo => $nomeVisualizacao):
                    ?>
                        <div class="nota-visual"><span><?php echo e($nomeVisualizacao); ?></span><strong><?php echo nota($avaliacaoAtual[$campo]); ?> / 10</strong></div>
                    <?php endforeach; ?>
                    <div class="nota-final"><span>Média final: <?php echo nota($mediaAtual, 1); ?></span><strong>Conceito <?php echo e($conceitoAtual); ?></strong></div>
                </div>
                <div class="botoes">
                    <a class="botao principal" href="?pagina=avaliar&projeto=<?php echo $projetoId; ?>"><?php echo icone('edit'); ?> <span style="vertical-align:2px; margin-left:4px;">Corrigir</span></a>
                    <a class="botao" href="?pagina=painel">Voltar</a>
                </div>
            </section>
        <?php elseif ($pagina === 'avaliar'): ?>
            <div class="avaliacao-layout">
                <section class="card painel">
                    <div class="projeto-cabecalho">
                        <div class="projeto-icone"><?php echo icone('book'); ?></div>
                        <div>
                            <div class="projeto-titulo">Avaliar: <?php echo e($projetoAtual['nome']); ?></div>
                            <div class="projeto-meta">Estande <?php echo e($projetoAtual['estande']); ?> · Equipe: <?php echo e($projetoAtual['equipe']); ?></div>
                        </div>
                    </div>
                    <div class="bloco">
                        <h3>Descrição do projeto</h3>
                        <p><?php echo e($projetoAtual['descricao']); ?></p>
                    </div>
                    <div class="bloco">
                        <h3>Critérios de avaliação</h3>
                        <?php
                        $criterios = [
                            'inovacao' => ['Inovação e originalidade', 'Analise a criatividade da solução e o diferencial apresentado.'],
                            'viabilidade' => ['Viabilidade técnica', 'Considere a execução, os recursos utilizados e o funcionamento do protótipo.'],
                            'impacto' => ['Impacto e sustentabilidade', 'Avalie o potencial de impacto social, ambiental ou educacional.'],
                            'apresentacao' => ['Apresentação e domínio do tema', 'Observe a comunicação da equipe e o domínio dos conteúdos apresentados.'],
                        ]; 
                        foreach ($criterios as $campo => [$nome, $ajuda]):
                            $valor = (float)$avaliacaoAtual[$campo];
                            $percentual = ($valor / 10) * 100;
                        ?>
                            <div class="criterio">
                                <div class="criterio-topo"><span class="criterio-nome"><?php echo e($nome); ?></span><span class="criterio-nota"><output id="texto-<?php echo $campo; ?>"><?php echo nota($valor); ?></output> / 10</span></div>
                                <div class="criterio-ajuda"><?php echo e($ajuda); ?></div>
                                <div class="controle-nota">
                                    <input type="range" name="<?php echo $campo; ?>" form="form-avaliacao" min="0" max="10" step="1" value="<?php echo e($valor); ?>" style="--valor:<?php echo $percentual; ?>%" oninput="atualizarNota(this, 'texto-<?php echo $campo; ?>', 'numero-<?php echo $campo; ?>')">
                                    <input class="campo-nota" id="numero-<?php echo $campo; ?>" type="number" min="0" max="10" step="1" value="<?php echo e($valor); ?>" oninput="atualizarSlider(this, '<?php echo $campo; ?>')">
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <form id="form-avaliacao" method="post" action="?pagina=avaliar&projeto=<?php echo $projetoId; ?>">
                        <input type="hidden" name="projeto_id" value="<?php echo $projetoId; ?>">
                        <div class="bloco">
                            <h3>Comentários do avaliador</h3>
                            <textarea name="comentarios" placeholder="Escreva um comentário sobre o projeto..."><?php echo e($avaliacaoAtual['comentarios']); ?></textarea>
                        </div>
                        <div class="botoes">
                            <button class="botao principal" type="submit" name="salvar_avaliacao"><?php echo icone('save'); ?> <span style="vertical-align:2px; margin-left:4px;">Salvar avaliação</span></button>
                            <a class="botao" href="?pagina=painel">Cancelar</a>
                        </div>
                    </form>
                </section>
                <aside class="lateral">
                    <div class="card">
                        <h3>Professor orientador</h3>
                        <div class="orientador"><div class="avatar"></div><div><strong><?php echo e($projetoAtual['orientador']); ?></strong><small>Orientadora responsável</small></div></div>
                    </div>
                    <div class="card">
                        <h3>Foto do projeto</h3>
                        <div class="ilustracao" role="img" aria-label="Ilustração de um sensor em um projeto sustentável"><div class="sol"></div><div class="sol-pequeno"></div><div class="vaso"><div class="sensor"></div></div><div class="folha f1"></div><div class="folha f2"></div><div class="folha f3"></div></div>
                    </div>
                    <div class="card resumo-avaliacao">
                        <h3>Resumo da avaliação</h3>
                        <dl><dt>Período</dt><dd>Manhã</dd><dt>Turma</dt><dd>1º DS A</dd><dt>Série</dt><dd>Série 1</dd><dt>Nota</dt><dd id="nota-resumo"><?php echo nota($mediaAtual, 1); ?></dd><dt>Conceito</dt><dd id="conceito-resumo"><?php echo $conceitoAtual; ?></dd><dt>Avaliado em</dt><dd><?php echo date('d/m/Y'); ?></dd></dl>
                    </div>
                </aside>
            </div>
        <?php else: ?>
            <?php if ($mensagem): ?><div class="mensagem"><?php echo e($mensagem); ?></div><?php endif; ?>
            <section class="cards">
                <div class="card resumo"><div class="resumo-titulo">Projetos orientados</div><div class="resumo-numero"><?php echo count($projetos); ?></div><div class="resumo-rodape"><?php echo count($projetos); ?> projetos ativos</div><span class="resumo-icone"><?php echo icone('book'); ?></span></div>
                <div class="card resumo dourado"><div class="resumo-titulo">Avaliações pendentes</div><div class="resumo-numero"><?php echo $pendentes; ?></div><div class="resumo-rodape"><?php echo $pendentes; ?> aguardando</div><span class="resumo-icone"><?php echo icone('clock'); ?></span></div>
                <div class="card resumo azul"><div class="resumo-titulo">Avaliações concluídas</div><div class="resumo-numero"><?php echo $concluidas; ?></div><div class="resumo-rodape"><?php echo $concluidas; ?> este mês</div><span class="resumo-icone"><?php echo icone('check'); ?></span></div>
                <div class="card resumo verde"><div class="resumo-titulo">Média das notas finais</div><div class="resumo-numero"><?php echo nota($mediaGeral, 2); ?></div><div class="resumo-rodape">Conceito geral: <?php echo $conceitoGeral; ?></div><span class="resumo-icone"><?php echo icone('chart'); ?></span></div>
            </section>
            <div class="secao-cabecalho"><h2>Projetos Orientados</h2><a class="ver-todos" href="?pagina=painel">Ver todos</a></div>
            <div class="tabela-wrap">
                <table>
                    <thead><tr><th>Projeto</th><th>Curso</th><th>Turma</th><th>Estande</th><th>Status</th><th>Visualização</th><th>Edição</th></tr></thead>
                    <tbody>
                    <?php foreach ($projetos as $id => $projeto): ?>
                        <tr>
                            <td><strong><?php echo e($projeto['nome']); ?></strong></td><td><?php echo e($projeto['curso']); ?></td><td><?php echo e($projeto['turma']); ?></td><td><?php echo e($projeto['estande']); ?></td>
                            <td><span class="status <?php echo $projeto['status'] === 'Avaliado' ? 'ok' : 'pendente'; ?>"><?php echo e($projeto['status']); ?></span></td>
                            <td><a class="acao" title="Visualizar avaliação" href="?pagina=avaliar&modo=visualizar&projeto=<?php echo $id; ?>"><?php echo icone('eye'); ?></a></td>
                            <td><a class="acao" title="Editar avaliação" href="?pagina=avaliar&projeto=<?php echo $id; ?>"><?php echo icone('edit'); ?></a></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </main>
<script>
function formatarNumero(valor) {
    return String(Math.round(Number(valor)));
}
function atualizarNota(slider, textoId, numeroId) {
    const valor = Math.round(Math.max(0, Math.min(10, Number(slider.value) || 0)));
    slider.style.setProperty('--valor', (valor * 10) + '%');
    document.getElementById(textoId).textContent = formatarNumero(valor);
    document.getElementById(numeroId).value = valor;
    atualizarResumo();
}
function atualizarSlider(campo, nome) {
    let valor = Math.max(0, Math.min(10, Number(campo.value) || 0));
    valor = Math.round(valor);
    campo.value = valor;
    const slider = document.querySelector('input[type="range"][name="' + nome + '"]');
    if (slider) {
        slider.value = valor;
        slider.style.setProperty('--valor', (valor * 10) + '%');
        const texto = document.getElementById('texto-' + nome);
        if (texto) texto.textContent = formatarNumero(valor);
    }
    atualizarResumo();
}
function obterConceito(media) {
    if (media <= 4) return 'I';
    if (media <= 6) return 'R';
    if (media <= 8) return 'B';
    return 'MB';
}
function atualizarResumo() {
    const campos = ['inovacao', 'viabilidade', 'impacto', 'apresentacao'];
    const valores = campos.map(campo => Number(document.querySelector('input[type="range"][name="' + campo + '"]')?.value || 0));
    const media = valores.reduce((total, valor) => total + valor, 0) / valores.length;
    const resumo = document.getElementById('nota-resumo');
    if (resumo) resumo.textContent = media.toFixed(1).replace('.', ',');
    const conceito = document.getElementById('conceito-resumo');
    if (conceito) conceito.textContent = obterConceito(media);
}
document.querySelectorAll('input[type="range"]').forEach(slider => slider.dispatchEvent(new Event('input')));
atualizarResumo();
</script>
</body>
</html>
