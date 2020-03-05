<?php
    include('../include/auth.php');
    include('../src/meta.php');
    require_once('../classes/class_conexao_padrao.php');
    require_once('../classes/class_queryHelper.php');
    require_once('../classes/class_setor.php');
    require_once('../classes/class_gestor.php');

    $conexao = new ConexaoPadrao();
    $conn = $conexao->conecta();
    $helper = new QueryHelper($conn);

    $select = "SELECT cod_string as codigo, cod_validade as validade, cod_usado as usos
    FROM tbl_codigo_avaliacao_empresa 
    WHERE emp_id = ".$_SESSION['empresa']['emp_id']." ORDER BY cod_validade DESC LIMIT 10";
    
    $query = $helper->select($select, 1);

    $gestor = new Gestor();
    $setor = new Setor();
    
?>
<!DOCTYPE html>
<html>
<head>
    <title>Avaliação da gestão</title>
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
            <li class="breadcrumb-item active" aria-current="page">Avaliação da Gestão</li>
        </ol>
    </nav>
    <!-- FIM DA NAV DE CAMINHO DE TELA -->

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

    <div class="row" style="text-align: center;">
        <div class="col-sm">
            <h2 class="high-text">Avaliação da gestão</h2>
        </div>
    </div>
    <div class="row" style="text-align: center;">
        <div class="col-sm">
            <a href="avaliacoesGestao.php?semfiltro=true"><input type="button" class="button button2" value="Visão geral"></a>
        </div>
    </div>
    <?php if($_SESSION['user']['permissao'] == "GESTOR-1") { ?>
    <div class="row" style="text-align: center;">
        <div class="col-sm">
            <input type="button" class="button button1" value="Gerar um código agora" data-toggle="modal" data-target="#modal">
        </div>
    </div>
    <?php } ?>

    <hr class="hr-divide">

</div>

<div class="container">
    <?php if($_SESSION['user']['permissao'] == "GESTOR-1" || $_SESSION['user']['permissao'] == "GESTOR-2") { ?>
    <div class="row">
        <div class="col-sm">
            <form action="avaliacoesGestao.php" method="POST">
            <label class="text"><b>Quero ver resultados de...</b></label>
            <input type="date" name="dataI" class="all-input" required>
        </div>
        <div class="col-sm">
            <label class="text"><b>... até esta data</b></label>
            <input type="date" name="dataF" class="all-input" required>
        </div>
        <div class="col-sm">
            <label class="text"><b>... do seguinte gestor</b></label>
            <select class="all-input" name="gestor" id="gestor">
                <option value="all">Todos os gestores</option>
                <?php echo $gestor->popularSelect($_SESSION['empresa']['database']); ?>
            </select>
        </div>
        <div class="col-sm">
            <label class="text"><b>... do seguinte setor</b></label>
            <select class="all-input" name="setor" id="setor">
                <option value="all">Todos os setores</option>
                <?php echo $setor->popularSelect($_SESSION['empresa']['database']); ?>
            </select>
        </div>
        <div class="col-sm" style="margin-top: 2em;">
            <input type="submit" class="button button2" value="Ver avaliações"></a>
            </form>
        </div>
    </div>
    <?php } ?>

    <div class="row">
        <div class="col-sm">
            <small class="text"><b>LEMBRE-SE: </b> o link para avaliar a gestão da empresa é <a href="https://sistemastaffast.com/staffast/avaliacao-empresa/" target="blank_">sistemastaffast.com/staffast/avaliacao-empresa/</a> e os colaboradores precisam do código dentro do prazo de validade.</small>
        </div>
    </div>
    
    <table class="table-site">
        <tr>
            <th>Código</th>
            <th>Valido até</th>
            <th>Usado por</th>
            <?php if ($_SESSION['user']['permissao'] == "GESTOR-1") { ?>
                <th>Invalidar</th>
            <?php } ?> 
            <th>Resultados</th>
        </tr>
        <?php
            while($fetch = mysqli_fetch_assoc($query)) {

                $codigo = $fetch['codigo'];
                $validade = $fetch['validade'];
                $usos = $fetch['usos'];

                if ($validade > date('Y-m-d H:i:s')) {
                    $valido = 'Válido até: '.date('d/m/Y H:i', strtotime($validade));
                    $isValido = true;
                } else {
                    $valido = '<span style="color: red;">Código vencido</span>';
                    $isValido = false;
                }

                //ADICIONAR SELECT DE QUANTAS AVALIAÇÕES USARAM ESTE CÓDIGO
                
        ?>
        <tr>
            <td><b><?php echo $codigo; ?></b></td>
            <td><?php echo $valido; ?></td>
            <td><?php echo $usos; ?> avaliações</td>
            <?php if ($_SESSION['user']['permissao'] == "GESTOR-1" && $isValido) { ?>
                <td><a href="../database/codigo.php?invalidar=true&codigo=<?php echo $codigo; ?>"><input type="button" class="button button3" value="Invalidar código agora"></a></td>
            <?php } else if ($_SESSION['user']['permissao'] == "GESTOR-1" && !$isValido) { ?>
                <td>Já invalidado</td>
            <?php } ?>
            <td><a href="avaliacoesGestao.php?codigo=<?php echo $codigo; ?>"><button class="button button1">Veja as avaliações feitas com este código</button></a></td>
    <?php } ?>   
    </table>
</div>
</body>


<div class="modal" tabindex="-1" role="dialog" id="modal" data-target=".bd-example-modal-lg">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Gerar código de avaliação</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" style="text-align: center; padding-right: 2em;">

        <div class="row">
            <div class="col-sm">
                <form action="../database/codigo.php?gerar=true" method="POST">
                <label class="text">Seleciona a data de expiração do código</label>
                <input type="date" class="all-input" name="validade" id="validade">
            </div>
        </div>

        <div class="row">
            <div class="col-sm">
                <small class="text">Enquanto o código estiver válido, os colaboradores poderão realizar quantas avaliações desejarem 
                usando o link <a href="../avaliacao-empresa/" target="_blank">sistemastaffast.com/staffast/avaliacao-empresa/</a> ou 
                então acessando o Staffast pela página principal < sistemastaffast.com >, clicando em "Entrar no Staffast" e depois em "Avalie sua empresa" 
                <br> Além disso, todos os funcionários receberão um e-mail notificando sobre o código criado e orientando sobre como avaliar a empresa.</small>
            </div>
        </div>

        <hr class="hr-divide-super-light">

        <div class="row">
            <div class="col-sm">
                <input type="submit" class="button button1" value="Gerar código">
                </form>
            </div>
        </div>
        
      </div>
    </div>
  </div>
</div>
</html>