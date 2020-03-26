<?php
    include('../include/auth.php');
    include('../src/meta.php');
    require_once('../classes/class_conexao_empresa.php');
    require_once('../classes/class_queryHelper.php');
    require_once('../classes/class_pdi.php');

    if($_SESSION['empresa']['plano'] != "REVOLUCAO") {
        $_SESSION['msg'] = "O plano atualmente utilizado pela sua empresa não permite acesso a este 
        módulo do Staffast. <a href='../planos.php'>Conheça nossos planos</a>.";
        header('Location: home.php');
        die();
    }

    $conexao = new ConexaoEmpresa($_SESSION['empresa']['database']);
    $conn = $conexao->conecta();
    $helper = new QueryHelper($conn);
    $cpf = $_SESSION['user']['cpf'];

    $select = "SELECT t1.pdi_id as id, t1.pdi_titulo as titulo,
    t1.pdi_status as status,
    CASE 
        WHEN t3.col_nome_completo IS NOT NULL THEN t3.col_nome_completo
        ELSE t4.ges_nome_completo 
    END as dono,
    CASE 
        WHEN t5.ges_nome_completo IS NOT NULL THEN t5.ges_nome_completo
        ELSE 'Nenhum'
    END as orientador
    FROM tbl_pdi t1 
    LEFT JOIN tbl_colaborador t3 
        ON t3.col_cpf = t1.pdi_cpf
    LEFT JOIN tbl_gestor t4 
        ON t4.ges_cpf = t1.pdi_cpf
    LEFT JOIN tbl_gestor t5
        ON t5.ges_cpf = t1.ges_cpf
    WHERE t1.pdi_arquivado = 0 AND (t1.pdi_cpf = '$cpf' OR t1.ges_cpf = '$cpf')";
    $query = $helper->select($select, 1);


    $select = "SELECT t1.pdi_id as id, t1.pdi_titulo as titulo,
    t1.pdi_status as status,
    CASE 
        WHEN t3.col_nome_completo IS NOT NULL THEN t3.col_nome_completo
        ELSE t4.ges_nome_completo 
    END as dono,
    CASE 
        WHEN t5.ges_nome_completo IS NOT NULL THEN t5.ges_nome_completo
        ELSE 'Nenhum'
    END as orientador
    FROM tbl_pdi t1 
    LEFT JOIN tbl_colaborador t3 
        ON t3.col_cpf = t1.pdi_cpf
    LEFT JOIN tbl_gestor t4 
        ON t4.ges_cpf = t1.pdi_cpf
    LEFT JOIN tbl_gestor t5
        ON t5.ges_cpf = t1.ges_cpf
    WHERE t1.pdi_arquivado = 0 AND t1.pdi_publico = 1";
    $query_publico = $helper->select($select, 1);
    
?>
<!DOCTYPE html>
<html>
<head>
    <title>PDIs</title>
</head>
<body>
<?php
    include('../include/navbar.php');
?>
<div class="container-fluid">

    <!-- NAV DE CAMINHO DE TELA -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="home.php">Início</a></li>
            <li class="breadcrumb-item active" aria-current="page">Planos de Desenvolvimento Individual (PDIs)</li>
        </ol>
    </nav>
    <!-- FIM DA NAV DE CAMINHO DE TELA -->

    <div class="row" style="text-align: center;">
        <div class="col-sm-1">
            <img src="img/pdi.png" width="60">
        </div>
        <div class="col-sm">
            <h3 class="high-text">Planos de Desenvolvimento Individual</h3>
        </div>
    </div>
    <div class="row" style="text-align: center;">
        <div class="col-sm">
            <a href="novoPDI.php"><input type="button" class="button button1" value="Criar PDI"></a>
        </div>
        <div class="col-sm">
            <a href="PDIsArquivados.php"><input type="button" class="button button1" value="PDIs arquivados"></a>
        </div>
        <div class="col-sm">
            <input type="button" class="button button3" data-toggle="modal" data-target="#modal" value="Dúvidas sobre PDI">       
        </div>
    </div>

    <?php
    if(isset($_SESSION['msg'])) {
        ?>
		<div class="row">
            <div class="col-sm">
                <div class="alert alert-info alert-dismissible fade show" role="alert">
                    <?php echo $_SESSION['msg']; unset($_SESSION['msg']); ?>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            </div>
		</div>
        <?php
    }
    ?>

    <hr class="hr-divide">
</div>
<div class="container">

    <?php
    if(mysqli_num_rows($query) == 0) {
        ?>
        <div class="row">
            <div class="col-sm-2 offset-sm-3">
                <img src="img/pdi.png" width="110">
            </div>
            <div class="col-sm-7" style="margin-top: 2em;">
                <h4 class="text">Sem PDIs por enquanto.</h4>
            </div>
         </div>
        <?php
        } else {
    ?>
    
    <table class="table-site">
        <tr>
            <th>Título</th>
            <th>Dono</th>
            <th>Orientador</th>
            <th>Status</th>
            <th>Ver</th>
        </tr>
        <?php
            while($f = mysqli_fetch_assoc($query)) {
                $pdi = new PDI();
        ?>
        <tr>
            <td><b><?php echo $f['titulo']; ?></b></td>
            <td><?php echo $f['dono']; ?></td>
            <td><?php echo $f['orientador']; ?></td>
            <td><?php echo $pdi->traduzStatus($f['status']); ?></td>
            <td><a href="verPDI.php?id=<?php echo $f['id']; ?>"><input type="button" class="button button3" value="Ver"></a></td>
    <?php } ?>   
    </table>
 <?php } ?>

<div class="row" style="text-align: center; margin-top: 1em;">
    <div class="col-sm">
        <h4 class="text">PDIs públicos</h4>
        <small class="text">Planos de Desenvolvimento Individual públicos são aqueles que o dono ou o orientador decidiram o tornar 
        visível para todos. Eles devem ser utilizados como fonte de inspiração ou de consulta de como alguém desenvolveu habilidades e 
        competências</small>
    </div>
</div>

<hr class="hr-divide-super-light">

 <?php
    if(mysqli_num_rows($query_publico) == 0) {
        ?>
        <div class="row">
            <div class="col-sm-2 offset-sm-3">
                <img src="img/pdi.png" width="110">
            </div>
            <div class="col-sm-7" style="margin-top: 2em;">
                <h4 class="text">Sem PDIs públicos por enquanto.</h4>
            </div>
         </div>
        <?php
        } else {
    ?>
    
    <table class="table-site">
        <tr>
            <th>Título</th>
            <th>Dono</th>
            <th>Orientador</th>
            <th>Status</th>
            <th>Ver</th>
        </tr>
        <?php
            while($f = mysqli_fetch_assoc($query_publico)) {
                $pdi = new PDI();
        ?>
        <tr>
            <td><b><?php echo $f['titulo']; ?></b></td>
            <td><?php echo $f['dono']; ?></td>
            <td><?php echo $f['orientador']; ?></td>
            <td><?php echo $pdi->traduzStatus($f['status']); ?></td>
            <td><a href="verPDI.php?id=<?php echo $f['id']; ?>"><input type="button" class="button button3" value="Ver"></a></td>
    <?php } ?>   
    </table>
 <?php } ?>
</div>
</body>

<div class="modal" tabindex="-1" role="dialog" id="modal" data-target=".bd-example-modal-lg">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Planos de Desenvolvimento Individual (PDI)</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">

        <div class="row">
            <div class="col-sm">
                <h5 class="text">O que é um PDI?</h5>
            </div>
        </div>
        <hr class="hr-divide-super-light">
        <div class="row">
            <div class="col-sm">
                <p class="text">O PDI, abreviação de Plano de Desenvolvimento Individual, é um plano para unir diversas ações 
                necessárias para que você atinja um <b>objetivo específico</b>, o que pode ser uma promoção, um prêmio, 
                uma aprovação, etc. Pode ser também um objetivo pessoal!
                <br> Em outras palavras, é como criar um mapa para que você saia de onde está agora e caminhe até seu objetivo 
                de forma clara.
                <br> Na sua empresa, você pode ter um PDI orientado pelo seu gestor, porém nada te impede de ter seus próprios 
                PDI's também.</p>
            </div>
        </div>

        <div class="row">
            <div class="col-sm">
                <h5 class="text">Quais passos devo tomar?</h5>
            </div>
        </div>
        <hr class="hr-divide-super-light">
        <div class="row">
            <div class="col-sm">
                <p class="text"><b>Primeiro, </b> você deve definir um objetivo, que seja claro e direto (como "Ser fluente em inglês" ou "Ser promovido"). 
                <br><b>Segundo, </b>defina as competências que devem ser desenvolvidas para que você atinja esse objetivo, 
                como por exemplo "Gramática da Língua Inglesa", "Pronúncia da Língua Inglesa" ou "Trabalho em Equipe", "Mediação de Conflitos".
                <br><b>Em seguida, </b>basta definir quais metas você deve bater pra desenvolver suas competências e, consequentemente, atingir seu objetivo ;)
                <br>Exemplos: "Fazer 3 meses de curso intensivo de inglês", "Assistir 10 filmes com áudio e legenda em inglês", 
                "Usar apps para ter contato com nativos da língua inglesa e praticar escrita", "Participar de mais reuniões na empresa", 
                "Analisar processos em equipe e propor 2 ou mais melhorias", "Fazer um curso rápido de Mediação de Conflitos"</p>
            </div>
        </div>

        <small class="text">Nós usamos <a href="https://www.napratica.org.br/pdi-plano-de-desenvolvimento-individual/" target="_blank">este artigo</a> para desenvolver este passo-a-passo</small>
        
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
      </div>
    </div>
  </div>
</div>

</html>