<?php
require_once __DIR__ . '/conexao.php';
?>
<link rel="stylesheet" href="style.css">

    <main class="portal">
      <aside class="barra-lateral" aria-label="Navegação da área do professor">
        <a class="marca-cabecalho" href="dashboard.php" aria-label="Feira Tecnológica — página inicial"><span class="emblema-marca">MCM</span><span class="texto-marca"><strong>Feira Tecnológica</strong><span>ETEC Maria Cristina Medeiros</span></span></a>
        <p class="rotulo-barra-lateral">Área do Professor</p>
        <nav class="navegacao-lateral" aria-label="Navegação principal">
          <button class="ativo" type="button" data-page="dashboard.php"><svg class="icone" aria-hidden="true" viewBox="0 0 24 24"><path d="M5 4h14v16H5z"></path><path d="M8 4v4h8V4M8 13l2 2 4-4"></path></svg>Avaliações Gerais</button>
          <button type="button" data-page="trabalhos.php"><svg class="icone" aria-hidden="true" viewBox="0 0 24 24"><path d="M4 5h7v14H4zM13 5h7v14h-7z"></path><path d="M7 9h1M7 13h1M16 9h1M16 13h1"></path></svg>Trabalhos Orientados</button>
          <button type="button" data-page="avisos.php"><svg class="icone" aria-hidden="true" viewBox="0 0 24 24"><path d="M18 8a6 6 0 0 0-12 0c0 7-3 7-3 9h18c0-2-3-2-3-9M10 21h4"></path></svg>Avisos</button>
          <button type="button" data-page="perfil.php"><svg class="icone" aria-hidden="true" viewBox="0 0 24 24"><circle cx="12" cy="8" r="4"></circle><path d="M4.5 21a7.5 7.5 0 0 1 15 0"></path></svg>Perfil</button>
        </nav>
        <p class="rodape-barra-lateral">Feira Tecnológica · 2026</p>
      </aside>

      <section class="coluna-conteudo">
        <header class="barra-superior">
          <div><p class="rotulo-destaque">PAINEL DO PROFESSOR</p><h1>Bem-vindo! Professor(a) Laura</h1><p>Confira o panorama dos projetos sob sua orientação.</p></div>
          <button class="botao-notificacao" type="button" data-notification="Você não possui novos avisos." aria-label="Ver avisos"><svg class="icone" aria-hidden="true" viewBox="0 0 24 24"><path d="M18 8a6 6 0 0 0-12 0c0 7-3 7-3 9h18c0-2-3-2-3-9M10 21h4"></path></svg><i class="ponto-notificacao"></i></button>
        </header>

        <section class="metricas" aria-label="Resumo geral">
          <article class="cartao-metrica" style="--metric: var(--wine)"><span class="linha-metrica"></span><p>Projetos orientados</p><strong>4</strong><small>+2 este mês</small><span class="icone-metrica"><svg class="icone" aria-hidden="true" viewBox="0 0 24 24"><path d="M4 5h7v14H4zM13 5h7v14h-7z"></path></svg></span></article>
          <article class="cartao-metrica" style="--metric: var(--gold)"><span class="linha-metrica"></span><p>Avaliações pendentes</p><strong>2</strong><small>2 urgentes</small><span class="icone-metrica"><svg class="icone" aria-hidden="true" viewBox="0 0 24 24"><path d="M5 4h14v16H5z"></path><path d="M8 4v4h8V4M8 13l2 2 4-4"></path></svg></span></article>
          <article class="cartao-metrica" style="--metric: var(--blue)"><span class="linha-metrica"></span><p>Avaliações concluídas</p><strong>2</strong><small>+1 este mês</small><span class="icone-metrica"><svg class="icone" aria-hidden="true" viewBox="0 0 24 24"><path d="M12 3 5 6v5c0 4.6 2.9 8 7 10 4.1-2 7-5.4 7-10V6l-7-3Z"></path><path d="m9 12 2 2 4-4"></path></svg></span></article>
          <article class="cartao-metrica" style="--metric: var(--green)"><span class="linha-metrica"></span><p>Média das notas finais</p><strong>8,85</strong><small>+0,35 este mês</small><span class="icone-metrica"><svg class="icone" aria-hidden="true" viewBox="0 0 24 24"><path d="M4 20V9l8-5 8 5v11"></path><path d="M8 20v-6h8v6M8 10h.01M12 10h.01M16 10h.01"></path></svg></span></article>
        </section>

        <section aria-labelledby="projects-title">
          <div class="titulo-secao"><h2 id="projects-title">Projetos Orientados</h2><a class="acao-texto" href="trabalhos.php">Ver todos</a></div>
          <div class="lista-projetos">
            <table class="tabela-projetos">
              <caption class="somente-leitor-tela">Projetos acompanhados pela professora Laura</caption>
              <thead><tr><th>Projeto</th><th>Curso</th><th>Turma</th><th>Estande</th><th>Status</th><th>Visualização</th><th>Edição</th></tr></thead>
              <tbody>
                <tr><td class="nome-projeto">Farm Bot</td><td>Informática para Internet</td><td>3º</td><td>A-01</td><td><span class="situacao aprovado">Avaliado</span></td><td><div class="acoes-linha"><a class="botao-icone" href="popupolho.php?projeto=farm-bot" aria-label="Ver projeto Farm Bot"><svg class="icone" aria-hidden="true" viewBox="0 0 24 24"><path d="M2 12s3.5-6 10-6 10 6 10 6-3.5 6-10 6S2 12 2 12Z"></path><circle cx="12" cy="12" r="2.5"></circle></svg></a></div></td><td><div class="acoes-linha"><button class="botao-icone" type="button" data-page="avaliacao.php" aria-label="Editar avaliação de Farm Bot"><svg class="icone" aria-hidden="true" viewBox="0 0 24 24"><path d="m4 16-.8 4.8L8 20l11.5-11.5a2.1 2.1 0 0 0-3-3L5 17Z"></path><path d="m14.5 7.5 2 2"></path></svg></button></div></td></tr>
                <tr><td class="nome-projeto">Eco Filter</td><td>Recursos Humanos</td><td>2º</td><td>B-03</td><td><span class="situacao aprovado">Avaliado</span></td><td><div class="acoes-linha"><a class="botao-icone" href="popupolho.php?projeto=eco-filter" aria-label="Ver projeto Eco Filter"><svg class="icone" aria-hidden="true" viewBox="0 0 24 24"><path d="M2 12s3.5-6 10-6 10 6 10 6-3.5 6-10 6S2 12 2 12Z"></path><circle cx="12" cy="12" r="2.5"></circle></svg></a></div></td><td><div class="acoes-linha"><button class="botao-icone" type="button" data-page="avaliacao.php" aria-label="Editar avaliação de Eco Filter"><svg class="icone" aria-hidden="true" viewBox="0 0 24 24"><path d="m4 16-.8 4.8L8 20l11.5-11.5a2.1 2.1 0 0 0-3-3L5 17Z"></path><path d="m14.5 7.5 2 2"></path></svg></button></div></td></tr>
                <tr><td class="nome-projeto">App Recicla</td><td>Logística</td><td>3º</td><td>B-07</td><td><span class="situacao pendente">Pendente</span></td><td><div class="acoes-linha"><a class="botao-icone" href="popupolho.php?projeto=app-recicla" aria-label="Ver projeto App Recicla"><svg class="icone" aria-hidden="true" viewBox="0 0 24 24"><path d="M2 12s3.5-6 10-6 10 6 10 6-3.5 6-10 6S2 12 2 12Z"></path><circle cx="12" cy="12" r="2.5"></circle></svg></a></div></td><td><div class="acoes-linha"><button class="botao-icone" type="button" data-page="avaliacao.php" aria-label="Editar avaliação de App Recicla"><svg class="icone" aria-hidden="true" viewBox="0 0 24 24"><path d="m4 16-.8 4.8L8 20l11.5-11.5a2.1 2.1 0 0 0-3-3L5 17Z"></path><path d="m14.5 7.5 2 2"></path></svg></button></div></td></tr>
                <tr><td class="nome-projeto">Edu+</td><td>Administração</td><td>2º</td><td>C-02</td><td><span class="situacao aprovado">Avaliado</span></td><td><div class="acoes-linha"><a class="botao-icone" href="popupolho.php?projeto=edu" aria-label="Ver projeto Edu+"><svg class="icone" aria-hidden="true" viewBox="0 0 24 24"><path d="M2 12s3.5-6 10-6 10 6 10 6-3.5 6-10 6S2 12 2 12Z"></path><circle cx="12" cy="12" r="2.5"></circle></svg></a></div></td><td><div class="acoes-linha"><button class="botao-icone" type="button" data-page="avaliacao.php" aria-label="Editar avaliação de Edu+"><svg class="icone" aria-hidden="true" viewBox="0 0 24 24"><path d="m4 16-.8 4.8L8 20l11.5-11.5a2.1 2.1 0 0 0-3-3L5 17Z"></path><path d="m14.5 7.5 2 2"></path></svg></button></div></td></tr>
                <tr><td class="nome-projeto">Smart Horta</td><td>Informática para Internet</td><td>3º</td><td>A-02</td><td><span class="situacao pendente">Pendente</span></td><td><div class="acoes-linha"><a class="botao-icone" href="popupolho.php?projeto=smart-horta" aria-label="Ver projeto Smart Horta"><svg class="icone" aria-hidden="true" viewBox="0 0 24 24"><path d="M2 12s3.5-6 10-6 10 6 10 6-3.5 6-10 6S2 12 2 12Z"></path><circle cx="12" cy="12" r="2.5"></circle></svg></a></div></td><td><div class="acoes-linha"><button class="botao-icone" type="button" data-page="avaliacao.php" aria-label="Editar avaliação de Smart Horta"><svg class="icone" aria-hidden="true" viewBox="0 0 24 24"><path d="m4 16-.8 4.8L8 20l11.5-11.5a2.1 2.1 0 0 0-3-3L5 17Z"></path><path d="m14.5 7.5 2 2"></path></svg></button></div></td></tr>
                <tr><td class="nome-projeto">Energia Solar</td><td>Química</td><td>2º</td><td>B-01</td><td><span class="situacao aprovado">Avaliado</span></td><td><div class="acoes-linha"><a class="botao-icone" href="popupolho.php?projeto=energia-solar" aria-label="Ver projeto Energia Solar"><svg class="icone" aria-hidden="true" viewBox="0 0 24 24"><path d="M2 12s3.5-6 10-6 10 6 10 6-3.5 6-10 6S2 12 2 12Z"></path><circle cx="12" cy="12" r="2.5"></circle></svg></a></div></td><td><div class="acoes-linha"><button class="botao-icone" type="button" data-page="avaliacao.php" aria-label="Editar avaliação de Energia Solar"><svg class="icone" aria-hidden="true" viewBox="0 0 24 24"><path d="m4 16-.8 4.8L8 20l11.5-11.5a2.1 2.1 0 0 0-3-3L5 17Z"></path><path d="m14.5 7.5 2 2"></path></svg></button></div></td></tr>
              </tbody>
            </table>
          </div>
        </section>
      </section>

      <nav class="navegacao-movel" aria-label="Navegação móvel">
        <button class="ativo" type="button" data-page="dashboard.php"><svg class="icone" aria-hidden="true" viewBox="0 0 24 24"><path d="M5 4h14v16H5z"></path><path d="M8 4v4h8V4M8 13l2 2 4-4"></path></svg><span>Avaliações</span></button>
        <button type="button" data-page="trabalhos.php"><svg class="icone" aria-hidden="true" viewBox="0 0 24 24"><path d="M4 5h7v14H4zM13 5h7v14h-7z"></path></svg><span>Orientados</span></button>
        <button type="button" data-page="perfil.php"><svg class="icone" aria-hidden="true" viewBox="0 0 24 24"><circle cx="12" cy="8" r="4"></circle><path d="M4.5 21a7.5 7.5 0 0 1 15 0"></path></svg><span>Perfil</span></button>
      </nav>
    </main>
  </body>
</html>