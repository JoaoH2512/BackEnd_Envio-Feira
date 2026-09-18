<?php
// Captura o projeto enviado pela URL (ex: popupolho.php?projeto=eco-filter)
$projetoSlug = isset($_GET['projeto']) ? $_GET['projeto'] : 'farm-bot';

// Base de dados dos projetos
$projetos = [
  'farm-bot' => [
    'titulo' => 'Farm Bot',
    'categoria' => 'Tecnologia',
    'estande' => 'A-01',
    'orientador' => 'Carlos Almeida',
    'status' => 'Aprovado',
    'status_class' => 'aprovado',
    'descricao' => 'Robô autônomo para monitoramento de cultivos agrícolas utilizando sensores IoT e inteligência artificial.',
    'equipe' => [
      ['sigla' => 'AS', 'nome' => 'Ana Souza'],
      ['sigla' => 'BL', 'nome' => 'Bruno Lima'],
      ['sigla' => 'CD', 'nome' => 'Carla Dias']
    ]
  ],
  'eco-filter' => [
    'titulo' => 'Eco Filter',
    'categoria' => 'Sustentabilidade',
    'estande' => 'B-03',
    'orientador' => 'Laura Silva',
    'status' => 'Aprovado',
    'status_class' => 'aprovado',
    'descricao' => 'Filtro ecológico desenvolvido com materiais recicláveis para purificação de águas pluviais.',
    'equipe' => [
      ['sigla' => 'FM', 'nome' => 'Felipe Melo'],
      ['sigla' => 'GV', 'nome' => 'Gabriela Vieira']
    ]
  ],
  'app-recicla' => [
    'titulo' => 'App Recicla',
    'categoria' => 'Logística / TI',
    'estande' => 'B-07',
    'orientador' => 'Laura Silva',
    'status' => 'Pendente',
    'status_class' => 'pendente',
    'descricao' => 'Aplicativo para conectar geradores de resíduos recicláveis a cooperativas locais.',
    'equipe' => [
      ['sigla' => 'JR', 'nome' => 'João Rocha'],
      ['sigla' => 'MA', 'nome' => 'Mariana Alves']
    ]
  ],
  'edu' => [
    'titulo' => 'Edu+',
    'categoria' => 'Educação',
    'estande' => 'C-02',
    'orientador' => 'Laura Silva',
    'status' => 'Aprovado',
    'status_class' => 'aprovado',
    'descricao' => 'Plataforma interativa voltada ao suporte de aprendizagem para alunos do ensino fundamental.',
    'equipe' => [
      ['sigla' => 'PR', 'nome' => 'Pedro Rocha'],
      ['sigla' => 'TS', 'nome' => 'Tiago Santos']
    ]
  ],
  'smart-horta' => [
    'titulo' => 'Smart Horta',
    'categoria' => 'Automação',
    'estande' => 'A-02',
    'orientador' => 'Laura Silva',
    'status' => 'Pendente',
    'status_class' => 'pendente',
    'descricao' => 'Horta comunitária automatizada com controle de irrigação remota via celular.',
    'equipe' => [
      ['sigla' => 'LF', 'nome' => 'Lucas Fernandes'],
      ['sigla' => 'VR', 'nome' => 'Vanessa Ramos']
    ]
  ],
  'energia-solar' => [
    'titulo' => 'Energia Solar',
    'categoria' => 'Química / Energia',
    'estande' => 'B-01',
    'orientador' => 'Laura Silva',
    'status' => 'Aprovado',
    'status_class' => 'aprovado',
    'descricao' => 'Estudo sobre a otimização de painéis solares utilizando revestimentos fotovoltaicos.',
    'equipe' => [
      ['sigla' => 'DR', 'nome' => 'Diego Rocha'],
      ['sigla' => 'EL', 'nome' => 'Erica Lima']
    ]
  ]
];

// Carrega os dados do projeto escolhido ou o padrão caso não exista
$dados = isset($projetos[$projetoSlug]) ? $projetos[$projetoSlug] : $projetos['farm-bot'];
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo htmlspecialchars($dados['titulo']); ?> - Detalhes</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <main class="pagina-popup" style="display: flex; justify-content: center; align-items: center; min-height: 100vh;">
    <a class="fundo-modal" href="dashboard.php" aria-label="Fechar detalhes" style="position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 1;"></a>
    
    <section class="painel-modal" role="dialog" aria-modal="true" aria-labelledby="project-modal-title" style="position: relative; z-index: 2; background: #fff; max-width: 500px; width: 90%; padding: 24px; border-radius: 8px;">
      <a class="fechar-modal" href="dashboard.php" aria-label="Fechar" style="position: absolute; top: 16px; right: 16px; text-decoration: none; color: inherit;">
        <svg class="icone" aria-hidden="true" viewBox="0 0 24 24" width="24" height="24"><path d="M6 6l12 12M18 6 6 18" stroke="currentColor" stroke-width="2"></path></svg>
      </a>
      
      <div class="titulo-modal">
        <h1 id="project-modal-title"><?php echo htmlspecialchars($dados['titulo']); ?></h1>
      </div>
      
      <dl class="informacoes-modal">
        <dt>Categoria:</dt><dd><?php echo htmlspecialchars($dados['categoria']); ?></dd>
        <dt>Estande:</dt><dd><?php echo htmlspecialchars($dados['estande']); ?></dd>
        <dt>Orientador:</dt><dd><?php echo htmlspecialchars($dados['orientador']); ?></dd>
        <dt>Status:</dt><dd><span class="situacao <?php echo $dados['status_class']; ?>"><?php echo htmlspecialchars($dados['status']); ?></span></dd>
      </dl>
      
      <p class="texto-modal"><strong>Descrição:</strong><br><?php echo htmlspecialchars($dados['descricao']); ?></p>
      
      <h2 class="titulo-equipe">Equipe (<?php echo count($dados['equipe']); ?>):</h2>
      <ul class="lista-equipe">
        <?php foreach ($dados['equipe'] as $membro): ?>
          <li><span class="avatar-equipe"><?php echo htmlspecialchars($membro['sigla']); ?></span><?php echo htmlspecialchars($membro['nome']); ?></li>
        <?php endforeach; ?>
      </ul>
    </section>
  </main>
</body>
</html>