<?php
    include('../include/auth.php');
    include('../src/meta.php');
    require_once('../classes/class_gestor.php');
    require_once('../classes/class_colaborador.php');
    require_once('../classes/class_avaliacao.php');
    require_once('../classes/class_usuario.php');
    require_once('../classes/class_queryHelper.php');
    require_once('../classes/class_conexao_empresa.php');

    if(!isset($_GET['id']) || !isset($_GET['col'])) {
        header('Location: painelAvaliacao.php');
        die();
    }

    $cpf = base64_decode($_GET['col']);
    $id = $_GET['id'];

    $avaliacao = new Avaliacao();
    $avaliacao->setID($id);

    if($_SESSION['user']['permissao'] == "COLABORADOR" && $_SESSION['user']['cpf'] != $cpf) {
        include('../include/acessoNegado.php');
        die();
    }

    if($_SESSION['user']['permissao'] == "GESTOR-2" && $_SESSION['user']['cpf'] != $cpf) {
        if(!$avaliacao->isAutorizado($_SESSION['empresa']['database'], $_SESSION['user']['cpf'], $cpf)) {
            include('../include/acessoNegado.php');
            die();
        }
    }

    $conexao = new ConexaoEmpresa($_SESSION['empresa']['database']);
    $conn = $conexao->conecta();
    $helper = new QueryHelper($conn);

    $avaliacao = $avaliacao->retornarAvaliacao($_SESSION['empresa']['database']);

    if($avaliacao->getVisualizada() == 0 && $avaliacao->getCpfColaborador() == $_SESSION['user']['cpf']) {
        $avaliacao->setarVisualizada($_SESSION['empresa']['database']);
    }

    $avaliacao->getVisualizada() == 1 ? $avaliacao->setVisualizada("Sim") : $avaliacao->setVisualizada("Não");

    $gestor = new Gestor();
    $gestor->setCpf($avaliacao->getCpfGestor());
    $gestor = $gestor->retornarGestor($_SESSION['empresa']['database']);

    $colaborador = new Colaborador();
    $colaborador->setCpf($avaliacao->getCpfColaborador());
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
        <title>Avaliação <?php echo $avaliacao->getID(); ?></title>
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
                    <div class="col-sm-2">
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
                        <h3 class="high-text">Avaliação de <?php echo substr($avaliacao->getDataCriacao(), 0, 10); ?> de <?php echo $colaborador->getNomeCompleto(); ?></h3>
                    </div>
                </div>
                <div class="row" style="text-align: center;">
                    <div class="col-sm">
                        <p class="text"><?php echo $colaborador->getPrimeiroNome(); ?> é <?php echo $colaborador->getCargo(); ?> em <?php echo $_SESSION['empresa']['nome']; ?></p>
                    </div>
                    <div class="col-sm">
                        <p class="text">Avaliação criada em <?php echo $avaliacao->getDataCriacao(); ?></p>
                    </div>
                    <div class="col-sm">
                        <p class="text">Avaliação liberada em <?php echo $avaliacao->getDataLiberacao(); ?></p>
                    </div>
                    <div class="col-sm">
                        <p class="text">Avaliação realizada por <a href="perfilGestor.php?id=<?php echo base64_encode($gestor->getCpf()) ?>" target="blank_"><?php echo $gestor->getNomeCompleto(); ?></a></p>
                    </div>
                </div>

                <hr class="hr-divide-super-light">
            </div>
            <div class="container">

                <table class="table-site">
                <thead>
                    <tr>
                        <th>Competência</th>
                        <th>Nota</th>
                        <th>Observações de <?php echo $gestor->getPrimeiroNome(); ?></th>
                    </tr>
                </thead>
                    <tr style="text-align: center;">
                        <td><?php echo $_SESSION['empresa']['compet_um']; ?></td>
                        <td><b><?php echo $avaliacao->getSessaoUm(); ?></b> <img src="<?php echo getSmile($avaliacao->getSessaoUm()); ?>" width="20"></td>
                        <td><?php if($avaliacao->getSessaoUmObs() == "") { echo "Nenhuma"; } else { echo $avaliacao->getSessaoUmObs(); } ?></td>
                    </tr>
                    <tr style="text-align: center;">
                        <td><?php echo $_SESSION['empresa']['compet_dois']; ?></td>
                        <td><b><?php echo $avaliacao->getSessaoDois(); ?></b> <img src="<?php echo getSmile($avaliacao->getSessaoDois()); ?>" width="20"></td>
                        <td><?php if($avaliacao->getSessaoDoisObs() == "") { echo "Nenhuma"; } else { echo $avaliacao->getSessaoDoisObs(); } ?></td>
                    </tr>
                    <tr style="text-align: center;">
                        <td><?php echo $_SESSION['empresa']['compet_tres']; ?></td>
                        <td><b><?php echo $avaliacao->getSessaoTres(); ?></b> <img src="<?php echo getSmile($avaliacao->getSessaoTres()); ?>" width="20"></td>
                        <td><?php if($avaliacao->getSessaoTresObs() == "") { echo "Nenhuma"; } else { echo $avaliacao->getSessaoTresObs(); } ?></td>
                    </tr>
                    <tr style="text-align: center;">
                        <td><?php echo $_SESSION['empresa']['compet_quatro']; ?></td>
                        <td><b><?php echo $avaliacao->getSessaoQuatro(); ?></b> <img src="<?php echo getSmile($avaliacao->getSessaoQuatro()); ?>" width="20"></td>
                        <td><?php if($avaliacao->getSessaoQuatroObs() == "") { echo "Nenhuma"; } else { echo $avaliacao->getSessaoQuatroObs(); } ?></td>
                    </tr>
                    <?php if($_SESSION['empresa']['compet_cinco'] != "") { ?>
                    <tr style="text-align: center;">
                        <td><?php echo $_SESSION['empresa']['compet_cinco']; ?></td>
                        <td><b><?php echo $avaliacao->getSessaoCinco(); ?></b> <img src="<?php echo getSmile($avaliacao->getSessaoCinco()); ?>" width="20"></td>
                        <td><?php if($avaliacao->getSessaoCincoObs() == "") { echo "Nenhuma"; } else { echo $avaliacao->getSessaoCincoObs(); } ?></td>
                    </tr>
                    <?php } ?>
                    <?php if($_SESSION['empresa']['compet_seis'] != "") { ?>
                    <tr style="text-align: center;">
                        <td><?php echo $_SESSION['empresa']['compet_seis']; ?></td>
                        <td><b><?php echo $avaliacao->getSessaoSeis(); ?></b> <img src="<?php echo getSmile($avaliacao->getSessaoSeis()); ?>" width="20"></td>
                        <td><?php if($avaliacao->getSessaoSeisObs() == "") { echo "Nenhuma"; } else { echo $avaliacao->getSessaoSeisObs(); } ?></td>
                    </tr>
                    <?php } ?>
                    <?php if($_SESSION['empresa']['compet_sete'] != "") { ?>
                    <tr style="text-align: center;">
                        <td><?php echo $_SESSION['empresa']['compet_sete']; ?></td>
                        <td><b><?php echo $avaliacao->getSessaoSete(); ?></b> <img src="<?php echo getSmile($avaliacao->getSessaoSete()); ?>" width="20"></td>
                        <td><?php if($avaliacao->getSessaoSeteObs() == "") { echo "Nenhuma"; } else { echo $avaliacao->getSessaoSeteObs(); } ?></td>
                    </tr>
                    <?php } ?>
                    <?php if($_SESSION['empresa']['compet_oito'] != "") { ?>
                    <tr style="text-align: center;">
                        <td><?php echo $_SESSION['empresa']['compet_oito']; ?></td>
                        <td><b><?php echo $avaliacao->getSessaoOito(); ?></b> <img src="<?php echo getSmile($avaliacao->getSessaoOito()); ?>" width="20"></td>
                        <td><?php if($avaliacao->getSessaoOitoObs() == "") { echo "Nenhuma"; } else { echo $avaliacao->getSessaoOitoObs(); } ?></td>
                    </tr>
                    <?php } ?>
                    <?php if($_SESSION['empresa']['compet_nove'] != "") { ?>
                    <tr style="text-align: center;">
                        <td><?php echo $_SESSION['empresa']['compet_nove']; ?></td>
                        <td><b><?php echo $avaliacao->getSessaoNove(); ?></b> <img src="<?php echo getSmile($avaliacao->getSessaoNove()); ?>" width="20"></td>
                        <td><?php if($avaliacao->getSessaoNoveObs() == "") { echo "Nenhuma"; } else { echo $avaliacao->getSessaoNoveObs(); } ?></td>
                    </tr>
                    <?php } ?>
                    <?php if($_SESSION['empresa']['compet_dez'] != "") { ?>
                    <tr style="text-align: center;">
                        <td><?php echo $_SESSION['empresa']['compet_dez']; ?></td>
                        <td><b><?php echo $avaliacao->getSessaoDez(); ?></b> <img src="<?php echo getSmile($avaliacao->getSessaoDez()); ?>" width="20"></td>
                        <td><?php if($avaliacao->getSessaoDezObs() == "") { echo "Nenhuma"; } else { echo $avaliacao->getSessaoDezObs(); } ?></td>
                    </tr>
                    <?php } ?>
                    <?php if($_SESSION['empresa']['compet_onze'] != "") { ?>
                    <tr style="text-align: center;">
                        <td><?php echo $_SESSION['empresa']['compet_onze']; ?></td>
                        <td><b><?php echo $avaliacao->getSessaoOnze(); ?></b> <img src="<?php echo getSmile($avaliacao->getSessaoOnze()); ?>" width="20"></td>
                        <td><?php if($avaliacao->getSessaoOnzeObs() == "") { echo "Nenhuma"; } else { echo $avaliacao->getSessaoOnzeObs(); } ?></td>
                    </tr>
                    <?php } ?>
                    <?php if($_SESSION['empresa']['compet_doze'] != "") { ?>
                    <tr style="text-align: center;">
                        <td><?php echo $_SESSION['empresa']['compet_doze']; ?></td>
                        <td><b><?php echo $avaliacao->getSessaoDoze(); ?></b> <img src="<?php echo getSmile($avaliacao->getSessaoDoze()); ?>" width="20"></td>
                        <td><?php if($avaliacao->getSessaoDozeObs() == "") { echo "Nenhuma"; } else { echo $avaliacao->getSessaoDozeObs(); } ?></td>
                    </tr>
                    <?php } ?>
                    <?php if($_SESSION['empresa']['compet_treze'] != "") { ?>
                    <tr style="text-align: center;">
                        <td><?php echo $_SESSION['empresa']['compet_treze']; ?></td>
                        <td><b><?php echo $avaliacao->getSessaoTreze(); ?></b> <img src="<?php echo getSmile($avaliacao->getSessaoTreze()); ?>" width="20"></td>
                        <td><?php if($avaliacao->getSessaoTrezeObs() == "") { echo "Nenhuma"; } else { echo $avaliacao->getSessaoTrezeObs(); } ?></td>
                    </tr>
                    <?php } ?>
                    <?php if($_SESSION['empresa']['compet_quatorze'] != "") { ?>
                    <tr style="text-align: center;">
                        <td><?php echo $_SESSION['empresa']['compet_quatorze']; ?></td>
                        <td><b><?php echo $avaliacao->getSessaoQuatorze(); ?></b> <img src="<?php echo getSmile($avaliacao->getSessaoQuatorze()); ?>" width="20"></td>
                        <td><?php if($avaliacao->getSessaoQuatorzeObs() == "") { echo "Nenhuma"; } else { echo $avaliacao->getSessaoQuatorzeObs(); } ?></td>
                    </tr>
                    <?php } ?>
                    <?php if($_SESSION['empresa']['compet_quinze'] != "") { ?>
                    <tr style="text-align: center;">
                        <td><?php echo $_SESSION['empresa']['compet_quinze']; ?></td>
                        <td><b><?php echo $avaliacao->getSessaoQuinze(); ?></b> <img src="<?php echo getSmile($avaliacao->getSessaoQuinze()); ?>" width="20"></td>
                        <td><?php if($avaliacao->getSessaoQuinzeObs() == "") { echo "Nenhuma"; } else { echo $avaliacao->getSessaoQuinzeObs(); } ?></td>
                    </tr>
                    <?php } ?>
                    <?php if($_SESSION['empresa']['compet_dezesseis'] != "") { ?>
                    <tr style="text-align: center;">
                        <td><?php echo $_SESSION['empresa']['compet_dezesseis']; ?></td>
                        <td><b><?php echo $avaliacao->getSessaoDezesseis(); ?></b> <img src="<?php echo getSmile($avaliacao->getSessaoDezesseis()); ?>" width="20"></td>
                        <td><?php if($avaliacao->getSessaoDezesseisObs() == "") { echo "Nenhuma"; } else { echo $avaliacao->getSessaoDezesseisObs(); } ?></td>
                    </tr>
                    <?php } ?>
                    <?php if($_SESSION['empresa']['compet_dezessete'] != "") { ?>
                    <tr style="text-align: center;">
                        <td><?php echo $_SESSION['empresa']['compet_dezessete']; ?></td>
                        <td><b><?php echo $avaliacao->getSessaoDezessete(); ?></b> <img src="<?php echo getSmile($avaliacao->getSessaoDezessete()); ?>" width="20"></td>
                        <td><?php if($avaliacao->getSessaoDezesseteObs() == "") { echo "Nenhuma"; } else { echo $avaliacao->getSessaoDezesseteObs(); } ?></td>
                    </tr>
                    <?php } ?>
                    <?php if($_SESSION['empresa']['compet_dezoito'] != "") { ?>
                    <tr style="text-align: center;">
                        <td><?php echo $_SESSION['empresa']['compet_dezoito']; ?></td>
                        <td><b><?php echo $avaliacao->getSessaoDezoito(); ?></b> <img src="<?php echo getSmile($avaliacao->getSessaoDezoito()); ?>" width="20"></td>
                        <td><?php if($avaliacao->getSessaoDezoitoObs() == "") { echo "Nenhuma"; } else { echo $avaliacao->getSessaoDezoitoObs(); } ?></td>
                    </tr>
                    <?php } ?>
                    <?php if($_SESSION['empresa']['compet_dezenove'] != "") { ?>
                    <tr style="text-align: center;">
                        <td><?php echo $_SESSION['empresa']['compet_dezenove']; ?></td>
                        <td><b><?php echo $avaliacao->getSessaoDezenove(); ?></b> <img src="<?php echo getSmile($avaliacao->getSessaoDezenove()); ?>" width="20"></td>
                        <td><?php if($avaliacao->getSessaoDezenoveObs() == "") { echo "Nenhuma"; } else { echo $avaliacao->getSessaoDezenoveObs(); } ?></td>
                    </tr>
                    <?php } ?>
                    <?php if($_SESSION['empresa']['compet_vinte'] != "") { ?>
                    <tr style="text-align: center;">
                        <td><?php echo $_SESSION['empresa']['compet_vinte']; ?></td>
                        <td><b><?php echo $avaliacao->getSessaoVinte(); ?></b> <img src="<?php echo getSmile($avaliacao->getSessaoVinte()); ?>" width="20"></td>
                        <td><?php if($avaliacao->getSessaoVinteObs() == "") { echo "Nenhuma"; } else { echo $avaliacao->getSessaoVinteObs(); } ?></td>
                    </tr>
                    <?php } ?>
                </table>
            </div>
        </div>
    </body>
</html>
