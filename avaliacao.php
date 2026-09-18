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
        <button class="link-voltar" type="button" data-page="dashboard.php"><svg class="icone" aria-hidden="true" viewBox="0 0 24 24"><path d="m15 18-6-6 6-6"></path></svg>Voltar</button>
        <header class="barra-superior"><div><p class="rotulo-destaque">AVALIAÇÕES GERAIS</p><h1>EcoSense</h1><p>Revise as informações para concluir a avaliação do projeto.</p></div><button class="botao-notificacao" type="button" data-notification="Sem novos avisos." aria-label="Ver avisos"><svg class="icone" aria-hidden="true" viewBox="0 0 24 24"><path d="M18 8a6 6 0 0 0-12 0c0 7-3 7-3 9h18c0-2-3-2-3-9M10 21h4"></path></svg><i class="ponto-notificacao"></i></button></header>

        <section class="grade-avaliacao">
          <article class="cartao-avaliacao">
            <div class="identificacao-projeto"><span class="icone-redondo-projeto"><svg class="icone" aria-hidden="true" viewBox="0 0 24 24"><path d="M5 4h14v16H5z"></path><path d="M8 4v4h8V4M8 13l2 2 4-4"></path></svg></span><div><h2>Avaliar: ReciclaU — Triagem Inteligente de Resíduos</h2><p>Estande B-12 · Equipe: João Pedro, Larissa Freitas, Raquel Souza</p></div></div>
            <div class="secao-avaliacao"><h3>Descrição do projeto</h3><p>Sistema IoT para monitoramento da qualidade e do nível de poluição em tempo real, com alertas automáticos para a comunidade escolar.</p></div>
            <div class="secao-avaliacao formulario-avaliacao" id="evaluation-form"><h3>Critérios de avaliação</h3>
              <div class="criterio" data-criterion><div class="texto-criterio"><h3>Inovação e originalidade <span class="nota-rubrica" data-score-label>9 / 10</span></h3><p>Analise a criatividade da solução e o diferencial apresentado.</p></div><div class="controle-nota"><label class="somente-leitor-tela" for="range-innovation">Nota de inovação e originalidade</label><input id="range-innovation" class="faixa-nota" type="range" min="0" max="10" step="0.5" value="9"><label class="somente-leitor-tela" for="score-innovation">Campo de nota de inovação e originalidade</label><input id="score-innovation" class="entrada-nota" type="number" min="0" max="10" step="0.5" value="9" inputmode="decimal"></div></div>
              <div class="criterio" data-criterion><div class="texto-criterio"><h3>Viabilidade técnica <span class="nota-rubrica" data-score-label>8,5 / 10</span></h3><p>Considere a execução, os recursos utilizados e o funcionamento do protótipo.</p></div><div class="controle-nota"><label class="somente-leitor-tela" for="range-technical">Nota de viabilidade técnica</label><input id="range-technical" class="faixa-nota" type="range" min="0" max="10" step="0.5" value="8.5"><label class="somente-leitor-tela" for="score-technical">Campo de nota de viabilidade técnica</label><input id="score-technical" class="entrada-nota" type="number" min="0" max="10" step="0.5" value="8.5" inputmode="decimal"></div></div>
              <div class="criterio" data-criterion><div class="texto-criterio"><h3>Impacto e sustentabilidade <span class="nota-rubrica" data-score-label>9,5 / 10</span></h3><p>Avalie o potencial de impacto social, ambiental ou educacional.</p></div><div class="controle-nota"><label class="somente-leitor-tela" for="range-impact">Nota de impacto e sustentabilidade</label><input id="range-impact" class="faixa-nota" type="range" min="0" max="10" step="0.5" value="9.5"><label class="somente-leitor-tela" for="score-impact">Campo de nota de impacto e sustentabilidade</label><input id="score-impact" class="entrada-nota" type="number" min="0" max="10" step="0.5" value="9.5" inputmode="decimal"></div></div>
              <div class="criterio" data-criterion><div class="texto-criterio"><h3>Apresentação e domínio do tema <span class="nota-rubrica" data-score-label>8 / 10</span></h3><p>Observe a comunicação da equipe e o domínio dos conteúdos apresentados.</p></div><div class="controle-nota"><label class="somente-leitor-tela" for="range-presentation">Nota de apresentação e domínio do tema</label><input id="range-presentation" class="faixa-nota" type="range" min="0" max="10" step="0.5" value="8"><label class="somente-leitor-tela" for="score-presentation">Campo de nota de apresentação e domínio do tema</label><input id="score-presentation" class="entrada-nota" type="number" min="0" max="10" step="0.5" value="8" inputmode="decimal"></div></div>
            </div>
            <div class="secao-avaliacao"><h3>Comentários do avaliador</h3><textarea class="caixa-comentario" id="general-summary" maxlength="800" placeholder="Descreva os principais pontos observados durante a avaliação, recomendações e comentários para a equipe.">Ótimo projeto! Parabéns pela iniciativa e pela entrega.</textarea></div>
            <div class="acoes-formulario"><p id="form-status" role="status" aria-live="polite"></p><button class="botao-compacto" id="save-evaluation" type="button"><svg class="icone" aria-hidden="true" viewBox="0 0 24 24"><path d="M5 3h12l2 2v16H5V3Z"></path><path d="M8 3v6h8V3M8 21v-7h8v7"></path></svg>Salvar avaliação</button><button class="botao-secundario" type="button" data-page="dashboard.php">Cancelar</button></div>
          </article>
          <aside class="lateral-avaliacao">
            <article class="cartao-lateral"><h3>Professor orientador</h3><div class="orientador"><span class="avatar-pequeno">AS</span><div><strong>Ana Beatriz Silva</strong><span>Orientadora responsável</span></div></div></article>
            <article class="cartao-lateral"><h3>Foto do projeto</h3><img class="foto-projeto" src="popuptrabalhos.png" alt="Protótipo tecnológico de monitoramento agrícola"></article>
            <article class="cartao-lateral"><h3>Resumo da avaliação</h3><dl class="lista-resumo"><div><dt>Período</dt><dd>Manhã</dd></div><div><dt>Turma</dt><dd>1º DSA</dd></div><div><dt>Série</dt><dd>Série 1</dd></div><div><dt>Nota</dt><dd style="color:var(--green)">7,2</dd></div><div><dt>Avaliado em</dt><dd>06/05/2026</dd></div></dl></article>
          </aside>
        </section>
      </section>
      <nav class="navegacao-movel" aria-label="Navegação móvel"><button class="ativo" type="button" data-page="dashboard.php"><svg class="icone" aria-hidden="true" viewBox="0 0 24 24"><path d="M5 4h14v16H5z"></path><path d="M8 4v4h8V4M8 13l2 2 4-4"></path></svg><span>Avaliações</span></button><button type="button" data-page="trabalhos.php"><svg class="icone" aria-hidden="true" viewBox="0 0 24 24"><path d="M4 5h7v14H4zM13 5h7v14h-7z"></path></svg><span>Orientados</span></button><button type="button" data-page="perfil.php"><svg class="icone" aria-hidden="true" viewBox="0 0 24 24"><circle cx="12" cy="8" r="4"></circle><path d="M4.5 21a7.5 7.5 0 0 1 15 0"></path></svg><span>Perfil</span></button></nav>
    </main>
  </body>
</html>