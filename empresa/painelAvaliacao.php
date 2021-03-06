<?php
    include('../include/auth.php');
    include('../src/meta.php');
    require_once('../classes/class_avaliacao.php');
    require_once('../classes/class_autoavaliacao.php');
    require_once('../classes/class_avaliacao.php');
    require_once('../classes/class_conexao_empresa.php');
    require_once('../classes/class_queryHelper.php');
    require_once('../classes/class_colaborador.php');

    if($_SESSION['empresa']['plano'] != "REVOLUCAO" && $_SESSION['empresa']['plano'] != "AVALIACAO") {
        $_SESSION['msg'] = "O plano atualmente utilizado pela sua empresa não permite acesso a este 
        módulo do Staffast. <a href='../planos.php'>Conheça nossos planos</a>.";
        header('Location: home.php');
        die();
    }

    $conexao = new ConexaoEmpresa($_SESSION['empresa']['database']);
    $conn = $conexao->conecta();
    $helper = new QueryHelper($conn);

    $cpf = $_SESSION['user']['cpf'];

    if($_SESSION['user']['permissao'] == 'GESTOR-1') $selectCol = "SELECT col_nome_completo as nome, col_cpf as cpf FROM tbl_colaborador ORDER BY col_nome_completo ASC";
    else if($_SESSION['user']['permissao'] == 'GESTOR-2') $selectCol = "SELECT t2.col_nome_completo as nome, t2.col_cpf as cpf FROM tbl_gestor_funcionario t1 INNER JOIN tbl_colaborador t2 ON t2.col_cpf = t1.col_cpf WHERE t1.ges_cpf = '$cpf' ORDER BY t2.col_nome_completo ASC";
    else $selectCol = "SELECT col_nome_completo as nome, col_cpf as cpf FROM tbl_colaborador WHERE col_cpf = '$cpf'";

    $query = mysqli_query($conn, $selectCol);
    
?>
<!DOCTYPE html>
<html>
<head>
    <title>Avaliações</title>
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
            <li class="breadcrumb-item active" aria-current="page">Painel de Avaliação</li>
        </ol>
    </nav>
    <!-- FIM DA NAV DE CAMINHO DE TELA -->

    <div class="row" style="text-align: center;">
        <div class="col-sm">
            <h2 class="high-text">Avaliações dos colaboradores</h2>
        </div>
    </div>

    <hr class="hr-divide">

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
</div>
<div class="container">
    <table class="table-site" style="font-size: 0.8em;">
        <tr>
            <th>Colaborador</th>
            <?php if ($_SESSION['user']['permissao'] == "GESTOR-1" || $_SESSION['user']['permissao'] == "GESTOR-2") { ?>
            <th>Liberar autoavaliação</th>
            <th>Última avaliação</th>
            <?php } ?>
            <th>Visualizar resultados</th>
        </tr>
        <?php
            while($fetch = mysqli_fetch_assoc($query)) {
                $colaborador = new Colaborador();
                $colaborador->setNomeCompleto($fetch['nome']);
                $colaborador->setCpf($fetch['cpf']);

                $ata = new Autoavaliacao();
                $ata->setCpfColaborador($colaborador->getCpf());

                $ava = new Avaliacao();
                $ava->setCpfColaborador($colaborador->getCpf());

                $liberarAva = false;

                if(!$ava->isLiberada($_SESSION['empresa']['database'])) {
                    $liberarAva = true;
                }

                $liberarAta = false;
                if($ata->checarLiberada($_SESSION['empresa']['database'])) {
                    if(!$ata->checarPreenchida($_SESSION['empresa']['database'])) {
                        //LIBERADA E PREENCHIDA
                        $liberarAta = true;
                    } else {
                        //LIBERADA MAS NÃO PREENCHIDA
                        $liberarAta = false;
                    }
                } else {
                    //NENHUMA LIBERADA
                    $liberarAta = true;
                }

                $visualizar = false;

                if($_SESSION['user']['permissao'] == 'GESTOR-1' || $_SESSION['user']['permissao'] == "GESTOR-2") {
                    $visualizar = true;
                } else if ($_SESSION['user']['permissao'] == 'COLABORADOR') {
                    if($ava->isLiberada($_SESSION['empresa']['database']) && $_SESSION['user']['cpf'] == $colaborador->getCpf()) {
                        $visualizar = true;
                    }
                }
        ?>
        <tr>
            <td><?php echo $colaborador->getNomeCompleto(); ?></td>

        <?php
        if ($_SESSION['user']['permissao'] == "GESTOR-1" || $_SESSION['user']['permissao'] == "GESTOR-2") {
            ?>
            <td class="text">
            <?php
            if($liberarAta) {
                ?>  
                <a href="../database/autoavaliacao.php?liberar=true&id=<?php echo base64_encode($colaborador->getCpf()); ?>"><button class="button button1">Liberar</button></a></<a>
                <?php
            } else {
                ?>
                Existe autoavaliação liberada que o colaborador não preencheu
                <?php
            }
            $datas_recentes = $ata->retornaUltima($_SESSION['empresa']['database']);
            ?>
            <br><small>Última autoavaliação preenchida: <?php echo $datas_recentes["preenchida"]; ?>
            <?php if($datas_recentes["existe"]) { echo ' - Liberada em: '.$datas_recentes["liberacao"]; } ?> 
            </small>
            
            </td>

            <td class="text">
        <?php
            if($liberarAva) {
                ?>  
                <a href="../database/avaliacao.php?liberar=true&id=<?php echo base64_encode($colaborador->getCpf()); ?>"><button class="button button1">Liberar</button></a></<a>
                <?php
            } else {
                ?>
                Não há avaliações não liberadas
                <?php
            }
            ?>
            <br><small>Última avaliação liberada: <?php echo $ava->retornarUltimaComGestor($_SESSION['empresa']['database']); ?></small>
            </td>
        <?php } ?>
            <td class="text">
        <?php
        if($visualizar && $_SESSION['user']['permissao'] == "GESTOR-1" || $visualizar && $_SESSION['user']['permissao'] == "GESTOR-2" || ($_SESSION['user']['permissao'] == "COLABORADOR" && $colaborador->getCpf() == $_SESSION['user']['cpf'])) {
            ?>  
            <a href="resultados.php?id=<?php echo base64_encode($colaborador->getCpf()); ?>"><button class="button button2">Visualizar resultados</button></a><a>
            <br><small>Avaliações liberadas até: <?php echo $ava->retornarUltimaLiberada($_SESSION['empresa']['database']); ?></small>
            <br><small>Autovaliações liberadas até: <?php echo $ata->retornarUltimaLiberada($_SESSION['empresa']['database']); ?></small>
            <?php
        } else {
            ?>
            Não disponível
            <?php
        }
        ?>
            </td>
        </tr>

    <?php } ?>

        
    </table>
</div>
</body>
</html>