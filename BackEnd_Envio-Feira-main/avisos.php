<?php
require_once __DIR__ . '/conexao.php';
?>

<link rel="stylesheet" href="style.css">

    <main class="portal">
      <aside class="barra-lateral" aria-label="Navegação da área do professor">
        <a class="marca-cabecalho" href="dashboard.php" aria-label="Feira Tecnológica — página inicial"><span class="emblema-marca">MCM</span><span class="texto-marca"><strong>Feira Tecnológica</strong><span>ETEC Maria Cristina Medeiros</span></span></a>
        <p class="rotulo-barra-lateral">Área do Professor</p>
        <nav class="navegacao-lateral" aria-label="Navegação principal">
          <button type="button" data-page="dashboard.php"><svg class="icone" aria-hidden="true" viewBox="0 0 24 24"><path d="M5 4h14v16H5z"></path><path d="M8 4v4h8V4M8 13l2 2 4-4"></path></svg>Avaliações Gerais</button>
          <button type="button" data-page="trabalhos.php"><svg class="icone" aria-hidden="true" viewBox="0 0 24 24"><path d="M4 5h7v14H4zM13 5h7v14h-7z"></path><path d="M7 9h1M7 13h1M16 9h1M16 13h1"></path></svg>Trabalhos Orientados</button>
          <button class="ativo" type="button" data-page="avisos.php"><svg class="icone" aria-hidden="true" viewBox="0 0 24 24"><path d="M18 8a6 6 0 0 0-12 0c0 7-3 7-3 9h18c0-2-3-2-3-9M10 21h4"></path></svg>Avisos</button>
          <button type="button" data-page="perfil.php"><svg class="icone" aria-hidden="true" viewBox="0 0 24 24"><circle cx="12" cy="8" r="4"></circle><path d="M4.5 21a7.5 7.5 0 0 1 15 0"></path></svg>Perfil</button>
        </nav>
        <p class="rodape-barra-lateral">Feira Tecnológica · 2026</p>
      </aside>
      <section class="coluna-conteudo">
        <header class="barra-superior"><div><p class="rotulo-destaque">COMUNICAÇÃO</p><h1>Avisos</h1><p>Mensagens institucionais relacionadas à Feira Tecnológica.</p></div><button class="botao-notificacao" type="button" data-notification="Todos os avisos foram lidos." aria-label="Marcar avisos como lidos"><svg class="icone" aria-hidden="true" viewBox="0 0 24 24"><path d="M18 8a6 6 0 0 0-12 0c0 7-3 7-3 9h18c0-2-3-2-3-9M10 21h4"></path></svg><i class="ponto-notificacao"></i></button></header>
        <section class="lista-projetos"><div class="estado-vazio"><svg class="icone" aria-hidden="true" viewBox="0 0 24 24" style="width:27px;height:27px;margin-bottom:9px;color:var(--wine)"><path d="M4 5h16v12H4z"></path><path d="m7 9 3 2 3-2 3 2"></path><path d="M8 21h8"></path></svg><br>Não há novos avisos para você no momento.</div></section>
      </section>
      <nav class="navegacao-movel" aria-label="Navegação móvel"><button type="button" data-page="dashboard.php"><svg class="icone" aria-hidden="true" viewBox="0 0 24 24"><path d="M5 4h14v16H5z"></path><path d="M8 4v4h8V4M8 13l2 2 4-4"></path></svg><span>Avaliações</span></button><button type="button" data-page="trabalhos.php"><svg class="icone" aria-hidden="true" viewBox="0 0 24 24"><path d="M4 5h7v14H4zM13 5h7v14h-7z"></path></svg><span>Orientados</span></button><button type="button" data-page="perfil.php"><svg class="icone" aria-hidden="true" viewBox="0 0 24 24"><circle cx="12" cy="8" r="4"></circle><path d="M4.5 21a7.5 7.5 0 0 1 15 0"></path></svg><span>Perfil</span></button></nav>
    </main>
  </body>
</html>