<script>
$(function () {
  $('[data-toggle="tooltip"]').tooltip()
})
</script>
<nav class="navbar fixed-top navbar-expand-lg navbar-light bg-light" style="position: fixed;top: 0; width: 100%;">
    <a class="navbar-brand high-text" href="home.php"><img src="/staffast/img/<?php echo $_SESSION['staffast']['logotipo']; ?>" width="120"></a>
  
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse" id="navbarNavDropdown">
    <ul class="navbar-nav">
      <li class="nav-item active">
        <a class="nav-link high-text" href="home.php"><img src="/staffast/include/icon/home.png" width="21" data-toggle="tooltip" data-placement="top" title="Início"></a>
      </li>
      <li class="nav-item dropdown" style="margin-left:1em;">
        <a class="nav-link dropdown-toggle text" href="#" id="navbarDropdownMenuLink" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          <img src="/staffast/include/icon/leadership.png" width="21" data-toggle="tooltip" data-placement="top" title="Gestores">
        </a>
        <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
          <a class="dropdown-item" href="gestores.php">Visualizar gestores</a>
          <?php if($_SESSION['user']['permissao'] == 'GESTOR-1') { ?>
          <a class="dropdown-item" href="novoGestor.php">Cadastrar gestor</a>
          <?php } ?>
          <?php if($_SESSION['user']['permissao'] == 'GESTOR-1') { ?>
          <a class="dropdown-item" href="gestoresDesativados.php">Gestores desativados</a>
          <?php } ?>
        </div>
      </li>
      <li class="nav-item dropdown" style="margin-left:1em;">
        <a class="nav-link dropdown-toggle text" href="#" id="navbarDropdownMenuLink" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          <img src="/staffast/include/icon/company-workers.png" width="21" data-toggle="tooltip" data-placement="top" title="Colaboradores">
        </a>
        <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
          <a class="dropdown-item" href="colaboradores.php">Visualizar colaboradores</a>
          <?php if($_SESSION['user']['permissao'] == 'GESTOR-1') { ?>
          <a class="dropdown-item" href="novoColaborador.php">Cadastrar colaborador</a>
          <a class="dropdown-item" href="colaboradoresDesativados.php">Colaboradores desativados</a>
          <?php } ?>
          <a class="dropdown-item" href="PDIs.php">Planos de Desenvolvimento Individual (PDIs)</a>
          <a class="dropdown-item" href="verRanking.php">Ver ranking</a>
        </div>
      </li>
      <li class="nav-item dropdown" style="margin-left:1em;">
        <a class="nav-link dropdown-toggle text" href="#" id="navbarDropdownMenuLink" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          <img src="/staffast/include/icon/time.png" width="21" data-toggle="tooltip" data-placement="top" title="Ponto eletrônico">
        </a>
        <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
          <a class="dropdown-item" href="historicoPontos.php">Histórico de pontos</a>
          <a class="dropdown-item" href="../ponto/index.php?email=<?php echo base64_encode($_SESSION['user']['email']) ?>" target="_blank">Registrar meu ponto</a>
          <?php if($_SESSION['user']['permissao'] == 'GESTOR-1') { ?>
          <a class="dropdown-item" href="horarios.php">Gerenciar horários dos funcionários</a>
          <?php } ?>
        </div>
      </li>
      <li class="nav-item dropdown" style="margin-left:1em;">
        <a class="nav-link dropdown-toggle text" href="#" id="navbarDropdownMenuLink" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          <img src="/staffast/include/icon/production.png" width="21" data-toggle="tooltip" data-placement="top" title="Setores">
        </a>
        <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
          <a class="dropdown-item" href="setores.php">Visualizar setores</a>
          <?php if($_SESSION['user']['permissao'] == 'GESTOR-1') { ?>
          <a class="dropdown-item" href="novoSetor.php">Cadastrar setor</a>
          <?php } ?>
          <?php if($_SESSION['user']['permissao'] == 'GESTOR-1' || $_SESSION['user']['permissao'] == 'GESTOR-2') { ?>
          <a class="dropdown-item" href="setoresDesativados.php">Visualizar setores desativados</a>
          <?php } ?>
        </div>
      </li>
      <li class="nav-item dropdown" style="margin-left:1em;">
        <a class="nav-link dropdown-toggle text" href="#" id="navbarDropdownMenuLink" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          <img src="/staffast/include/icon/test.png" width="21" data-toggle="tooltip" data-placement="top" title="Avaliações">
        </a>
        <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
          <a class="dropdown-item" href="avaliacaoGestao.php">Avaliação da gestão</a>
          <a class="dropdown-item" href="avaliacaoSetor.php">Avaliação de setores</a>
          <?php if($_SESSION['user']['permissao'] == 'GESTOR-1' || $_SESSION['user']['permissao'] == 'GESTOR-2') { ?>
          <a class="dropdown-item" href="novaAvaliacao.php">Nova avaliação de colaborador</a>
          <a class="dropdown-item" href="verModelosAvaliacao.php">Ver modelos de avalição</a>
          <!-- <a class="dropdown-item" href="agendamentosAvaliacao.php">Agendamentos de avaliação</!-->
          <?php } ?>
          <?php if($_SESSION['user']['permissao'] == 'GESTOR-1') { ?>
          <!-- <a class="dropdown-item" href="novoModeloAvaliacao.php">Novo modelo de avaliação</a> -->
          <?php } ?>
          <a class="dropdown-item" href="painelAvaliacao.php">Painel de controle</a>
          <a class="dropdown-item" href="novaAutoavaliacao.php">Fazer autovaliação</a>
        </div>
      </li>
      <?php if($_SESSION['user']['permissao'] == 'GESTOR-1') { ?>
      <li class="nav-item dropdown" style="margin-left:1em;">
        <a class="nav-link dropdown-toggle text" href="#" id="navbarDropdownMenuLink" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          <img src="/staffast/include/icon/video-conference.png" width="21" data-toggle="tooltip" data-placement="top" title="Processos Seletivos">
        </a>
        <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
        <?php if($_SESSION['user']['permissao'] == 'GESTOR-1' || $_SESSION['user']['permissao'] == 'GESTOR-2') { ?>
          <a class="dropdown-item" href="processosSeletivos.php">Ver processos seletivos em andamento</a>
          <a class="dropdown-item" href="processosSeletivosEncerrados.php">Ver processos seletivos encerrados</a>
          <?php } ?>
        <?php if($_SESSION['user']['permissao'] == 'GESTOR-1') { ?>
          <a class="dropdown-item" href="novoProcessoSeletivo.php">Novo processo seletivo</a>
          <?php } ?>
        </div>
      </li>
      <?php } ?>
      <li class="nav-item dropdown" style="margin-left:1em;">
        <a class="nav-link dropdown-toggle text" href="#" id="navbarDropdownMenuLink" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          <img src="/staffast/include/icon/round-table.png" width="21" data-toggle="tooltip" data-placement="top" title="Reuniões">
        </a>
        <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
          <a class="dropdown-item" href="reunioes.php">Suas próximas reuniões</a>
          <a class="dropdown-item" href="reunioesPassadas.php">Suas reuniões concluídas</a>
          <?php if($_SESSION['user']['permissao'] == 'GESTOR-1' || $_SESSION['user']['permissao'] == 'GESTOR-2') { ?>
          <a class="dropdown-item" href="novaReuniao.php">Nova reunião</a>
          <?php } ?>
        </div>
      </li>
      <li class="nav-item active" style="margin-left:1em;">
        <a class="nav-link high-text" href="eventos.php"><img src="/staffast/include/icon/calendar.png" width="21" data-toggle="tooltip" data-placement="top" title="Eventos"></a>
      </li>
      <li class="nav-item active" style="margin-left:1em;">
        <a class="nav-link high-text" href="metas.php"><img src="/staffast/include/icon/target.png" width="21" data-toggle="tooltip" data-placement="top" title="Metas"></a>
      </li>
      <li class="nav-item active" style="margin-left:1em;">
        <a class="nav-link high-text" href="novoFeedback.php"><img src="/staffast/include/icon/feedback.png" width="21" data-toggle="tooltip" data-placement="top" title="Feedback"></a>
      </li>
      <li class="nav-item active" style="margin-left:1em;">
        <a class="nav-link high-text" href="novaMensagem.php"><img src="/staffast/include/icon/envelope.png" width="21" data-toggle="tooltip" data-placement="top" title="Mensagem"></a>
      </li>
      <li class="nav-item active" style="margin-left:1em;">
        <a class="nav-link high-text" href="relatorios.php"><img src="/staffast/include/icon/laptop.png" width="21" data-toggle="tooltip" data-placement="top" title="Relatórios"></a>
      </li>
      <!-- <li class="nav-item active">
        <a class="nav-link high-text" href="../ajuda.php">Relatórios <span class="sr-only">(current)</span></a>
      </li> -->
      <li class="nav-item active" style="margin-left:1em;">
        <a class="nav-link high-text" href="documentos.php"><img src="/staffast/include/icon/file.png" width="21" data-toggle="tooltip" data-placement="top" title="Documetos"></a>
      </li>
      <li class="nav-item dropdown" style="margin-left:1em;">
        <a class="nav-link dropdown-toggle text" href="#" id="navbarDropdownMenuLink" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            Mais
        </a>
        <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
          <!-- <a class="dropdown-item" href="#">Minha empresa</a>
          <a class="dropdown-item" href="#">Meus dados</a> -->
          <a class="dropdown-item" href="meuUsuario.php">Minha conta</a>
          <a class="dropdown-item" href="minhaEmpresa.php">Minha empresa</a>
          <?php if($_SESSION['user']['permissao'] == "GESTOR-1") { ?>
          <a class="dropdown-item" href="logAlteracoes.php">Relatório de ações</a>
          <?php } ?>
          <a class="dropdown-item" href="../ajuda.php" target="_blank">Ajuda</a>
          
          <a class="dropdown-item" href="../suporte/" target="_blank">Contatar o suporte / Relatar problema</a>
          <a class="dropdown-item" href="../suporte/index.php?sugestao=true" target="_blank">Enviar sugestões de novas funcionalidades</a>
          <!-- <a class="dropdown-item" href="#">Conferir novidades do Staffast</a> -->
        </div>
      </li>
      <li class="nav-item active" style="margin-left:2em;">
        <a class="nav-link high-text" href="../database/logout.php"><img src="/staffast/include/icon/logout.png" width="21" data-toggle="tooltip" data-placement="top" title="Sair do Staffast"></a>
      </li>
    </ul>
  </div>
</nav>