<?php
    include('../include/auth.php');
    include('../src/meta.php');
    require_once('../classes/class_colaborador.php');
    require_once('../classes/class_autoavaliacao.php');
    require_once('../classes/class_usuario.php');
    require_once('../classes/class_queryHelper.php');
    require_once('../classes/class_conexao_empresa.php');

    if($_SESSION['empresa']['plano'] != "REVOLUCAO" && $_SESSION['empresa']['plano'] != "AVALIACAO") {
        $_SESSION['msg'] = "O plano atualmente utilizado pela sua empresa não permite acesso a este 
        módulo do Staffast. <a href='../planos.php'>Conheça nossos planos</a>.";
        header('Location: home.php');
        die();
    }

    if(!isset($_GET['id']) || !isset($_GET['col'])) {
        header('Location: painelAvaliacao.php');
        die();
    }

    $cpf = base64_decode($_GET['col']);
    $id = $_GET['id'];

    $autoavaliacao = new Autoavaliacao();
    $autoavaliacao->setID($id);

    if($_SESSION['user']['permissao'] == "COLABORADOR" && $_SESSION['user']['cpf'] != $cpf) {
        include('../include/acessoNegado.php');
        die();
    }

    if($_SESSION['user']['permissao'] == "GESTOR-2" && $_SESSION['user']['cpf'] != $cpf) {
        if(!$autoavaliacao->isAutorizado($_SESSION['empresa']['database'], $_SESSION['user']['cpf'], $cpf)) {
            include('../include/acessoNegado.php');
            die();
        }
    }

    $conexao = new ConexaoEmpresa($_SESSION['empresa']['database']);
    $conn = $conexao->conecta();
    $helper = new QueryHelper($conn);

    $autoavaliacao = $autoavaliacao->retornarAutoavaliacao($_SESSION['empresa']['database']);

    $colaborador = new Colaborador();
    $colaborador->setCpf($autoavaliacao->getCpfColaborador());
    $colaborador = $colaborador->retornarColaborador($_SESSION['empresa']['database']);

    $usuario = new Usuario();
    $usuario->setID($colaborador->getIDUser());
    $usuario = $usuario->retornarUsuario();


    //COLETANDO SETORES INSERIDO

    $select = "SELECT t2.set_nome as setor, t2.set_id as id FROM tbl_setor_funcionario t1 INNER JOIN tbl_setor t2 
    ON t2.set_id = t1.set_id WHERE t1.col_cpf = '".$colaborador->getCpf()."'";

    $query = $helper->select($select, 1);

    $num_setores = mysqli_num_rows($query);

    $setores = array("id"=>array(), "setor"=>array());
    $i = 0;
    if($num_setores > 0) {
      while($f = mysqli_fetch_assoc($query)) {
        $setores["id"][$i] = $f['id'];
        $setores["setor"][$i] = $f['setor'];
        $i++;
      }
    }

    //

    function getSmile($nota) {
        switch($nota) {
            case 1:
                return 'img/unhappy.png'; break;
            case 2:
                return 'img/sad.png'; break;
            case 3:
                return 'img/confused.png'; break;
            case 4:
                return 'img/smiling.png'; break;
            case 5:
                return 'img/happy.png'; break;
        }
    }
?>
<html>
    <head>
        <title>Autoavaliação <?php echo $autoavaliacao->getID(); ?></title>
        <script>
            function imprimir() {
                document.getElementById("div-topo").style.display = 'none';
                window.print();
                document.getElementById("div-topo").style.display = 'block';
                return;
            }
        </script>
    </head>
    <body style="margin-top: 0em;">
        <div class="container-fluid">
            <div id="div-topo">
                <div class="row">
                    <div class="col-sm-1">
                        <input type="button" class="button button1" value="Voltar" onclick="history.back();">
                    </div>
                    <div class="col-sm-1">
                        <input type="button" class="button button3" value="Imprimir / Salvar PDF" onclick="imprimir();">
                    </div>
                </div>
            </div>
        </div>
        <div id="print" class="myDivToPrint">
            <div class="container-fluid">

                <div class="row">
                    <div class="col-sm-2">
                        <img src="../img/logo_staffast.png" width="110">
                    </div>
                    <?php if($_SESSION['empresa']['logotipo'] != '') { ?>
                    <div class="col-sm-2 offset-sm-8">
                        <img src="img/logos/<?php echo $_SESSION['empresa']['logotipo'] ?>" width="110">
                    </div>  
                    <?php } ?>
                </div>

                <div class="row" style="text-align: center;">
                    <div class="col-sm">
                        <h2 class="high-text">Autoavaliação de <?php echo substr($autoavaliacao->getDataCriacao(), 0, 10); ?> de <?php echo $colaborador->getNomeCompleto(); ?></h2>
                    </div>
                </div>
                <div class="row" style="text-align: center;">
                    <div class="col-sm">
                        <p class="text"><?php echo $colaborador->getPrimeiroNome(); ?> é <?php echo $colaborador->getCargo(); ?> em <?php echo $_SESSION['empresa']['nome']; ?></p>
                    </div>
                    <div class="col-sm">
                        <p class="text">Autoavaliação liberada em <?php echo $autoavaliacao->getDataCriacao(); ?></p>
                    </div>
                    <div class="col-sm">
                        <p class="text">Autoavaliação preenchida em <?php if($autoavaliacao->getPreenchida()) { echo $autoavaliacao->getDataPreenchida(); } else { echo 'Não preenchida'; } ?></p>
                    </div>
                </div>

                <hr class="hr-divide">
            </div>
            <div class="container">
                <table class="table-site">
                <thead>
                    <tr>
                        <th>Competência</th>
                        <th>Nota</th>
                        <th>Observações de <?php echo $colaborador->getPrimeiroNome(); ?></th>
                    </tr>
                </thead>
                    <tr style="text-align: center;">
                        <td><?php echo $_SESSION['empresa']['compet_um']; ?></td>
                        <td><b><?php echo $autoavaliacao->getSessaoUm(); ?></b> <img src="<?php echo getSmile($autoavaliacao->getSessaoUm()); ?>" width="20"></td>
                        <td><?php if($autoavaliacao->getSessaoUmObs() == "") { echo "Nenhuma"; } else { echo $autoavaliacao->getSessaoUmObs(); } ?></td>
                    </tr>
                    <tr style="text-align: center;">
                        <td><?php echo $_SESSION['empresa']['compet_dois']; ?></td>
                        <td><b><?php echo $autoavaliacao->getSessaoDois(); ?></b> <img src="<?php echo getSmile($autoavaliacao->getSessaoDois()); ?>" width="20"></td>
                        <td><?php if($autoavaliacao->getSessaoDoisObs() == "") { echo "Nenhuma"; } else { echo $autoavaliacao->getSessaoDoisObs(); } ?></td>
                    </tr>
                    <tr style="text-align: center;">
                        <td><?php echo $_SESSION['empresa']['compet_tres']; ?></td>
                        <td><b><?php echo $autoavaliacao->getSessaoTres(); ?></b> <img src="<?php echo getSmile($autoavaliacao->getSessaoTres()); ?>" width="20"></td>
                        <td><?php if($autoavaliacao->getSessaoTresObs() == "") { echo "Nenhuma"; } else { echo $autoavaliacao->getSessaoTresObs(); } ?></td>
                    </tr>
                    <tr style="text-align: center;">
                        <td><?php echo $_SESSION['empresa']['compet_quatro']; ?></td>
                        <td><b><?php echo $autoavaliacao->getSessaoQuatro(); ?></b> <img src="<?php echo getSmile($autoavaliacao->getSessaoQuatro()); ?>" width="20"></td>
                        <td><?php if($autoavaliacao->getSessaoQuatroObs() == "") { echo "Nenhuma"; } else { echo $autoavaliacao->getSessaoQuatroObs(); } ?></td>
                    </tr>
                    <?php if($_SESSION['empresa']['compet_cinco'] != "") { ?>
                    <tr style="text-align: center;">
                        <td><?php echo $_SESSION['empresa']['compet_cinco']; ?></td>
                        <td><b><?php echo $autoavaliacao->getSessaoCinco(); ?></b> <img src="<?php echo getSmile($autoavaliacao->getSessaoCinco()); ?>" width="20"></td>
                        <td><?php if($autoavaliacao->getSessaoCincoObs() == "") { echo "Nenhuma"; } else { echo $autoavaliacao->getSessaoCincoObs(); } ?></td>
                    </tr>
                    <?php } ?>
                    <?php if($_SESSION['empresa']['compet_seis'] != "") { ?>
                    <tr style="text-align: center;">
                        <td><?php echo $_SESSION['empresa']['compet_seis']; ?></td>
                        <td><b><?php echo $autoavaliacao->getSessaoSeis(); ?></b> <img src="<?php echo getSmile($autoavaliacao->getSessaoSeis()); ?>" width="20"></td>
                        <td><?php if($autoavaliacao->getSessaoSeisObs() == "") { echo "Nenhuma"; } else { echo $autoavaliacao->getSessaoSeisObs(); } ?></td>
                    </tr>
                    <?php } ?>
                    <?php if($_SESSION['empresa']['compet_sete'] != "") { ?>
                    <tr style="text-align: center;">
                        <td><?php echo $_SESSION['empresa']['compet_sete']; ?></td>
                        <td><b><?php echo $autoavaliacao->getSessaoSete(); ?></b> <img src="<?php echo getSmile($autoavaliacao->getSessaoSete()); ?>" width="20"></td>
                        <td><?php if($autoavaliacao->getSessaoSeteObs() == "") { echo "Nenhuma"; } else { echo $autoavaliacao->getSessaoSeteObs(); } ?></td>
                    </tr>
                    <?php } ?>
                    <?php if($_SESSION['empresa']['compet_oito'] != "") { ?>
                    <tr style="text-align: center;">
                        <td><?php echo $_SESSION['empresa']['compet_oito']; ?></td>
                        <td><b><?php echo $autoavaliacao->getSessaoOito(); ?></b> <img src="<?php echo getSmile($autoavaliacao->getSessaoOito()); ?>" width="20"></td>
                        <td><?php if($autoavaliacao->getSessaoOitoObs() == "") { echo "Nenhuma"; } else { echo $autoavaliacao->getSessaoOitoObs(); } ?></td>
                    </tr>
                    <?php } ?>
                    <?php if($_SESSION['empresa']['compet_nove'] != "") { ?>
                    <tr style="text-align: center;">
                        <td><?php echo $_SESSION['empresa']['compet_nove']; ?></td>
                        <td><b><?php echo $autoavaliacao->getSessaoNove(); ?></b> <img src="<?php echo getSmile($autoavaliacao->getSessaoNove()); ?>" width="20"></td>
                        <td><?php if($autoavaliacao->getSessaoNoveObs() == "") { echo "Nenhuma"; } else { echo $autoavaliacao->getSessaoNoveObs(); } ?></td>
                    </tr>
                    <?php } ?>
                    <?php if($_SESSION['empresa']['compet_dez'] != "") { ?>
                    <tr style="text-align: center;">
                        <td><?php echo $_SESSION['empresa']['compet_dez']; ?></td>
                        <td><b><?php echo $autoavaliacao->getSessaoDez(); ?></b> <img src="<?php echo getSmile($autoavaliacao->getSessaoDez()); ?>" width="20"></td>
                        <td><?php if($autoavaliacao->getSessaoDezObs() == "") { echo "Nenhuma"; } else { echo $autoavaliacao->getSessaoDezObs(); } ?></td>
                    </tr>
                    <?php } ?>
                    <?php if($_SESSION['empresa']['compet_onze'] != "") { ?>
                    <tr style="text-align: center;">
                        <td><?php echo $_SESSION['empresa']['compet_onze']; ?></td>
                        <td><b><?php echo $autoavaliacao->getSessaoOnze(); ?></b> <img src="<?php echo getSmile($autoavaliacao->getSessaoOnze()); ?>" width="20"></td>
                        <td><?php if($autoavaliacao->getSessaoOnzeObs() == "") { echo "Nenhuma"; } else { echo $autoavaliacao->getSessaoOnzeObs(); } ?></td>
                    </tr>
                    <?php } ?>
                    <?php if($_SESSION['empresa']['compet_doze'] != "") { ?>
                    <tr style="text-align: center;">
                        <td><?php echo $_SESSION['empresa']['compet_doze']; ?></td>
                        <td><b><?php echo $autoavaliacao->getSessaoDoze(); ?></b> <img src="<?php echo getSmile($autoavaliacao->getSessaoDoze()); ?>" width="20"></td>
                        <td><?php if($autoavaliacao->getSessaoDozeObs() == "") { echo "Nenhuma"; } else { echo $autoavaliacao->getSessaoDozeObs(); } ?></td>
                    </tr>
                    <?php } ?>
                    <?php if($_SESSION['empresa']['compet_treze'] != "") { ?>
                    <tr style="text-align: center;">
                        <td><?php echo $_SESSION['empresa']['compet_treze']; ?></td>
                        <td><b><?php echo $autoavaliacao->getSessaoTreze(); ?></b> <img src="<?php echo getSmile($autoavaliacao->getSessaoTreze()); ?>" width="20"></td>
                        <td><?php if($autoavaliacao->getSessaoTrezeObs() == "") { echo "Nenhuma"; } else { echo $autoavaliacao->getSessaoTrezeObs(); } ?></td>
                    </tr>
                    <?php } ?>
                    <?php if($_SESSION['empresa']['compet_quatorze'] != "") { ?>
                    <tr style="text-align: center;">
                        <td><?php echo $_SESSION['empresa']['compet_quatorze']; ?></td>
                        <td><b><?php echo $autoavaliacao->getSessaoQuatorze(); ?></b> <img src="<?php echo getSmile($autoavaliacao->getSessaoQuatorze()); ?>" width="20"></td>
                        <td><?php if($autoavaliacao->getSessaoQuatorzeObs() == "") { echo "Nenhuma"; } else { echo $autoavaliacao->getSessaoQuatorzeObs(); } ?></td>
                    </tr>
                    <?php } ?>
                    <?php if($_SESSION['empresa']['compet_quinze'] != "") { ?>
                    <tr style="text-align: center;">
                        <td><?php echo $_SESSION['empresa']['compet_quinze']; ?></td>
                        <td><b><?php echo $autoavaliacao->getSessaoQuinze(); ?></b> <img src="<?php echo getSmile($autoavaliacao->getSessaoQuinze()); ?>" width="20"></td>
                        <td><?php if($autoavaliacao->getSessaoQuinzeObs() == "") { echo "Nenhuma"; } else { echo $autoavaliacao->getSessaoQuinzeObs(); } ?></td>
                    </tr>
                    <?php } ?>
                    <?php if($_SESSION['empresa']['compet_dezesseis'] != "") { ?>
                    <tr style="text-align: center;">
                        <td><?php echo $_SESSION['empresa']['compet_dezesseis']; ?></td>
                        <td><b><?php echo $autoavaliacao->getSessaoDezesseis(); ?></b> <img src="<?php echo getSmile($autoavaliacao->getSessaoDezesseis()); ?>" width="20"></td>
                        <td><?php if($autoavaliacao->getSessaoDezesseisObs() == "") { echo "Nenhuma"; } else { echo $autoavaliacao->getSessaoDezesseisObs(); } ?></td>
                    </tr>
                    <?php } ?>
                    <?php if($_SESSION['empresa']['compet_dezessete'] != "") { ?>
                    <tr style="text-align: center;">
                        <td><?php echo $_SESSION['empresa']['compet_dezessete']; ?></td>
                        <td><b><?php echo $autoavaliacao->getSessaoDezessete(); ?></b> <img src="<?php echo getSmile($autoavaliacao->getSessaoDezessete()); ?>" width="20"></td>
                        <td><?php if($autoavaliacao->getSessaoDezesseteObs() == "") { echo "Nenhuma"; } else { echo $autoavaliacao->getSessaoDezesseteObs(); } ?></td>
                    </tr>
                    <?php } ?>
                    <?php if($_SESSION['empresa']['compet_dezoito'] != "") { ?>
                    <tr style="text-align: center;">
                        <td><?php echo $_SESSION['empresa']['compet_dezoito']; ?></td>
                        <td><b><?php echo $autoavaliacao->getSessaoDezoito(); ?></b> <img src="<?php echo getSmile($autoavaliacao->getSessaoDezoito()); ?>" width="20"></td>
                        <td><?php if($autoavaliacao->getSessaoDezoitoObs() == "") { echo "Nenhuma"; } else { echo $autoavaliacao->getSessaoDezoitoObs(); } ?></td>
                    </tr>
                    <?php } ?>
                    <?php if($_SESSION['empresa']['compet_dezenove'] != "") { ?>
                    <tr style="text-align: center;">
                        <td><?php echo $_SESSION['empresa']['compet_dezenove']; ?></td>
                        <td><b><?php echo $autoavaliacao->getSessaoDezenove(); ?></b> <img src="<?php echo getSmile($autoavaliacao->getSessaoDezenove()); ?>" width="20"></td>
                        <td><?php if($autoavaliacao->getSessaoDezenoveObs() == "") { echo "Nenhuma"; } else { echo $autoavaliacao->getSessaoDezenoveObs(); } ?></td>
                    </tr>
                    <?php } ?>
                    <?php if($_SESSION['empresa']['compet_vinte'] != "") { ?>
                    <tr style="text-align: center;">
                        <td><?php echo $_SESSION['empresa']['compet_vinte']; ?></td>
                        <td><b><?php echo $autoavaliacao->getSessaoVinte(); ?></b> <img src="<?php echo getSmile($autoavaliacao->getSessaoVinte()); ?>" width="20"></td>
                        <td><?php if($autoavaliacao->getSessaoVinteObs() == "") { echo "Nenhuma"; } else { echo $autoavaliacao->getSessaoVinteObs(); } ?></td>
                    </tr>
                    <?php } ?>
                </table>
            </div>
        </div>
    </body>
</html>
