<?php
    include('../include/auth.php');
    include('../src/meta.php');
    require_once('../classes/class_colaborador.php');
    require_once('../classes/class_gestor.php');
    require_once('../classes/class_autoavaliacao.php');
    require_once('../classes/class_avaliacao.php');
    require_once('../classes/class_queryHelper.php');
    require_once('../classes/class_conexao_empresa.php');

    if($_SESSION['empresa']['plano'] != "REVOLUCAO" && $_SESSION['empresa']['plano'] != "AVALIACAO") {
        $_SESSION['msg'] = "O plano atualmente utilizado pela sua empresa não permite acesso a este 
        módulo do Staffast. <a href='../planos.php'>Conheça nossos planos</a>.";
        header('Location: home.php');
        die();
    }

    if(!isset($_GET['id_ata']) || !isset($_GET['col']) || !isset($_GET['id_ava'])) {
        header('Location: painelAvaliacao.php');
        die();
    }

    $cpf = base64_decode($_GET['col']);
    $id_ata = $_GET['id_ata'];
    $id_ava = $_GET['id_ava'];

    $autoavaliacao = new Autoavaliacao();
    $autoavaliacao->setID($id_ata);
    
    $avaliacao = new Avaliacao();
    $avaliacao->setID($id_ava);

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
    $avaliacao = $avaliacao->retornarAvaliacao($_SESSION['empresa']['database']);

    $colaborador = new Colaborador();
    $colaborador->setCpf($autoavaliacao->getCpfColaborador());
    $colaborador = $colaborador->retornarColaborador($_SESSION['empresa']['database']);

    $gestor = new Gestor();
    $gestor->setCpf($avaliacao->getCpfGestor());
    $gestor = $gestor->retornarGestor($_SESSION['empresa']['database']);

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
        <title>Avaliação vs. Autoavaliação</title>
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
                        <h2 class="high-text">Avaliação vs. Autoavaliação</h2>
                    </div>
                </div>
                <div class="row" style="text-align: center;">
                    <div class="col-sm">
                        <p class="text">Colaborador: <?php echo $colaborador->getNomeCompleto(); ?></p>
                    </div>
                    <div class="col-sm">
                        <p class="text">Gestor: <?php echo $gestor->getNomeCompleto(); ?></p>
                    </div>
                    <div class="col-sm">
                        <p class="text">Avaliação realizada em <?php echo $avaliacao->getDataCriacao(); ?></p>
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
                        <th>Nota avaliação</th>
                        <th>Observações de <?php echo $colaborador->getPrimeiroNome(); ?></th>
                        <th><i>vs.</i></th>
                        <th>Nota autoavaliação</th>
                        <th>Observações de <?php echo $gestor->getPrimeiroNome(); ?></th>
                    </tr>
                </thead>
                    <tr style="text-align: center;">
                        <td><b><?php echo $_SESSION['empresa']['compet_um']; ?></b></td>
                        <td><b><?php echo $avaliacao->getSessaoUm(); ?></b> <img src="<?php echo getSmile($avaliacao->getSessaoUm()); ?>" width="20"></td>
                        <td><?php if($avaliacao->getSessaoUmObs() == "") { echo "Nenhuma"; } else { echo $avaliacao->getSessaoUmObs(); } ?></td>
                        <th><i>versus</i></th>
                        <td><b><?php echo $autoavaliacao->getSessaoUm(); ?></b> <img src="<?php echo getSmile($autoavaliacao->getSessaoUm()); ?>" width="20"></td>
                        <td><?php if($autoavaliacao->getSessaoUmObs() == "") { echo "Nenhuma"; } else { echo $autoavaliacao->getSessaoUmObs(); } ?></td>
                    </tr>
                    <tr style="text-align: center;">
                        <td><b><?php echo $_SESSION['empresa']['compet_dois']; ?></b></td>
                        <td><b><?php echo $avaliacao->getSessaoDois(); ?></b> <img src="<?php echo getSmile($avaliacao->getSessaoDois()); ?>" width="20"></td>
                        <td><?php if($avaliacao->getSessaoDoisObs() == "") { echo "Nenhuma"; } else { echo $avaliacao->getSessaoDoisObs(); } ?></td>
                        <th><i>versus</i></th>
                        <td><b><?php echo $autoavaliacao->getSessaoDois(); ?></b> <img src="<?php echo getSmile($autoavaliacao->getSessaoDois()); ?>" width="20"></td>
                        <td><?php if($autoavaliacao->getSessaoDoisObs() == "") { echo "Nenhuma"; } else { echo $autoavaliacao->getSessaoDoisObs(); } ?></td>
                    </tr>
                    <tr style="text-align: center;">
                        <td><b><?php echo $_SESSION['empresa']['compet_tres']; ?></b></td>
                        <td><b><?php echo $avaliacao->getSessaoTres(); ?></b> <img src="<?php echo getSmile($avaliacao->getSessaoTres()); ?>" width="20"></td>
                        <td><?php if($avaliacao->getSessaoTresObs() == "") { echo "Nenhuma"; } else { echo $avaliacao->getSessaoTresObs(); } ?></td>
                        <th><i>versus</i></th>
                        <td><b><?php echo $autoavaliacao->getSessaoTres(); ?></b> <img src="<?php echo getSmile($autoavaliacao->getSessaoTres()); ?>" width="20"></td>
                        <td><?php if($autoavaliacao->getSessaoTresObs() == "") { echo "Nenhuma"; } else { echo $autoavaliacao->getSessaoTresObs(); } ?></td>
                    </tr>
                    <tr style="text-align: center;">
                        <td><b><?php echo $_SESSION['empresa']['compet_quatro']; ?></b></td>
                        <td><b><?php echo $avaliacao->getSessaoQuatro(); ?></b> <img src="<?php echo getSmile($avaliacao->getSessaoQuatro()); ?>" width="20"></td>
                        <td><?php if($avaliacao->getSessaoQuatroObs() == "") { echo "Nenhuma"; } else { echo $avaliacao->getSessaoQuatroObs(); } ?></td>
                        <th><i>versus</i></th>
                        <td><b><?php echo $autoavaliacao->getSessaoQuatro(); ?></b> <img src="<?php echo getSmile($autoavaliacao->getSessaoQuatro()); ?>" width="20"></td>
                        <td><?php if($autoavaliacao->getSessaoQuatroObs() == "") { echo "Nenhuma"; } else { echo $autoavaliacao->getSessaoQuatroObs(); } ?></td>
                    </tr>
                    <?php if($_SESSION['empresa']['compet_cinco'] != "") { ?>
                    <tr style="text-align: center;">
                        <td><b><?php echo $_SESSION['empresa']['compet_cinco']; ?></b></td>
                        <td><b><?php echo $avaliacao->getSessaoCinco(); ?></b> <img src="<?php echo getSmile($avaliacao->getSessaoCinco()); ?>" width="20"></td>
                        <td><?php if($avaliacao->getSessaoCincoObs() == "") { echo "Nenhuma"; } else { echo $avaliacao->getSessaoCincoObs(); } ?></td>
                        <th><i>versus</i></th>
                        <td><b><?php echo $autoavaliacao->getSessaoCinco(); ?></b> <img src="<?php echo getSmile($autoavaliacao->getSessaoCinco()); ?>" width="20"></td>
                        <td><?php if($autoavaliacao->getSessaoCincoObs() == "") { echo "Nenhuma"; } else { echo $autoavaliacao->getSessaoCincoObs(); } ?></td>
                    </tr>
                    <?php } ?>
                    <?php if($_SESSION['empresa']['compet_seis'] != "") { ?>
                    <tr style="text-align: center;">
                        <td><b><?php echo $_SESSION['empresa']['compet_seis']; ?></b></td>
                        <td><b><?php echo $avaliacao->getSessaoSeis(); ?></b> <img src="<?php echo getSmile($avaliacao->getSessaoSeis()); ?>" width="20"></td>
                        <td><?php if($avaliacao->getSessaoSeisObs() == "") { echo "Nenhuma"; } else { echo $avaliacao->getSessaoSeisObs(); } ?></td>
                        <th><i>versus</i></th>
                        <td><b><?php echo $autoavaliacao->getSessaoSeis(); ?></b> <img src="<?php echo getSmile($autoavaliacao->getSessaoSeis()); ?>" width="20"></td>
                        <td><?php if($autoavaliacao->getSessaoSeisObs() == "") { echo "Nenhuma"; } else { echo $autoavaliacao->getSessaoSeisObs(); } ?></td>
                    </tr>
                    <?php } ?>
                    <?php if($_SESSION['empresa']['compet_sete'] != "") { ?>
                    <tr style="text-align: center;">
                        <td><b><?php echo $_SESSION['empresa']['compet_sete']; ?></b></td>
                        <td><b><?php echo $avaliacao->getSessaoSete(); ?></b> <img src="<?php echo getSmile($avaliacao->getSessaoSete()); ?>" width="20"></td>
                        <td><?php if($avaliacao->getSessaoSeteObs() == "") { echo "Nenhuma"; } else { echo $avaliacao->getSessaoSeteObs(); } ?></td>
                        <th><i>versus</i></th>
                        <td><b><?php echo $autoavaliacao->getSessaoSete(); ?></b> <img src="<?php echo getSmile($autoavaliacao->getSessaoSete()); ?>" width="20"></td>
                        <td><?php if($autoavaliacao->getSessaoSeteObs() == "") { echo "Nenhuma"; } else { echo $autoavaliacao->getSessaoSeteObs(); } ?></td>
                    </tr>
                    <?php } ?>
                    <?php if($_SESSION['empresa']['compet_oito'] != "") { ?>
                    <tr style="text-align: center;">
                        <td><b><?php echo $_SESSION['empresa']['compet_oito']; ?></b></td>
                        <td><b><?php echo $avaliacao->getSessaoOito(); ?></b> <img src="<?php echo getSmile($avaliacao->getSessaoOito()); ?>" width="20"></td>
                        <td><?php if($avaliacao->getSessaoOitoObs() == "") { echo "Nenhuma"; } else { echo $avaliacao->getSessaoOitoObs(); } ?></td>
                        <th><i>versus</i></th>
                        <td><b><?php echo $autoavaliacao->getSessaoOito(); ?></b> <img src="<?php echo getSmile($autoavaliacao->getSessaoOito()); ?>" width="20"></td>
                        <td><?php if($autoavaliacao->getSessaoOitoObs() == "") { echo "Nenhuma"; } else { echo $autoavaliacao->getSessaoOitoObs(); } ?></td>
                    </tr>
                    <?php } ?>
                    <?php if($_SESSION['empresa']['compet_nove'] != "") { ?>
                    <tr style="text-align: center;">
                        <td><b><?php echo $_SESSION['empresa']['compet_nove']; ?></b></td>
                        <td><b><?php echo $avaliacao->getSessaoNove(); ?></b> <img src="<?php echo getSmile($avaliacao->getSessaoNove()); ?>" width="20"></td>
                        <td><?php if($avaliacao->getSessaoNoveObs() == "") { echo "Nenhuma"; } else { echo $avaliacao->getSessaoNoveObs(); } ?></td>
                        <th><i>versus</i></th>
                        <td><b><?php echo $autoavaliacao->getSessaoNove(); ?></b> <img src="<?php echo getSmile($autoavaliacao->getSessaoNove()); ?>" width="20"></td>
                        <td><?php if($autoavaliacao->getSessaoNoveObs() == "") { echo "Nenhuma"; } else { echo $autoavaliacao->getSessaoNoveObs(); } ?></td>
                    </tr>
                    <?php } ?>
                    <?php if($_SESSION['empresa']['compet_dez'] != "") { ?>
                    <tr style="text-align: center;">
                        <td><b><?php echo $_SESSION['empresa']['compet_dez']; ?></b></td>
                        <td><b><?php echo $avaliacao->getSessaoDez(); ?></b> <img src="<?php echo getSmile($avaliacao->getSessaoDez()); ?>" width="20"></td>
                        <td><?php if($avaliacao->getSessaoDezObs() == "") { echo "Nenhuma"; } else { echo $avaliacao->getSessaoDezObs(); } ?></td>
                        <th><i>versus</i></th>
                        <td><b><?php echo $autoavaliacao->getSessaoDez(); ?></b> <img src="<?php echo getSmile($autoavaliacao->getSessaoDez()); ?>" width="20"></td>
                        <td><?php if($autoavaliacao->getSessaoDezObs() == "") { echo "Nenhuma"; } else { echo $autoavaliacao->getSessaoDezObs(); } ?></td>
                    </tr>
                    <?php } ?>
                    <?php if($_SESSION['empresa']['compet_onze'] != "") { ?>
                    <tr style="text-align: center;">
                        <td><b><?php echo $_SESSION['empresa']['compet_onze']; ?></b></td>
                        <td><b><?php echo $avaliacao->getSessaoOnze(); ?></b> <img src="<?php echo getSmile($avaliacao->getSessaoOnze()); ?>" width="20"></td>
                        <td><?php if($avaliacao->getSessaoOnzeObs() == "") { echo "Nenhuma"; } else { echo $avaliacao->getSessaoOnzeObs(); } ?></td>
                        <th><i>versus</i></th>
                        <td><b><?php echo $autoavaliacao->getSessaoOnze(); ?></b> <img src="<?php echo getSmile($autoavaliacao->getSessaoOnze()); ?>" width="20"></td>
                        <td><?php if($autoavaliacao->getSessaoOnzeObs() == "") { echo "Nenhuma"; } else { echo $autoavaliacao->getSessaoOnzeObs(); } ?></td>
                    </tr>
                    <?php } ?>
                    <?php if($_SESSION['empresa']['compet_doze'] != "") { ?>
                    <tr style="text-align: center;">
                        <td><b><?php echo $_SESSION['empresa']['compet_doze']; ?></b></td>
                        <td><b><?php echo $avaliacao->getSessaoDoze(); ?></b> <img src="<?php echo getSmile($avaliacao->getSessaoDoze()); ?>" width="20"></td>
                        <td><?php if($avaliacao->getSessaoDozeObs() == "") { echo "Nenhuma"; } else { echo $avaliacao->getSessaoDozeObs(); } ?></td>
                        <th><i>versus</i></th>
                        <td><b><?php echo $autoavaliacao->getSessaoDoze(); ?></b> <img src="<?php echo getSmile($autoavaliacao->getSessaoDoze()); ?>" width="20"></td>
                        <td><?php if($autoavaliacao->getSessaoDozeObs() == "") { echo "Nenhuma"; } else { echo $autoavaliacao->getSessaoDozeObs(); } ?></td>
                    </tr>
                    <?php } ?>
                    <?php if($_SESSION['empresa']['compet_treze'] != "") { ?>
                    <tr style="text-align: center;">
                        <td><b><?php echo $_SESSION['empresa']['compet_treze']; ?></b></td>
                        <td><b><?php echo $avaliacao->getSessaoTreze(); ?></b> <img src="<?php echo getSmile($avaliacao->getSessaoTreze()); ?>" width="20"></td>
                        <td><?php if($avaliacao->getSessaoTrezeObs() == "") { echo "Nenhuma"; } else { echo $avaliacao->getSessaoTrezeObs(); } ?></td>
                        <th><i>versus</i></th>
                        <td><b><?php echo $autoavaliacao->getSessaoTreze(); ?></b> <img src="<?php echo getSmile($autoavaliacao->getSessaoTreze()); ?>" width="20"></td>
                        <td><?php if($autoavaliacao->getSessaoTrezeObs() == "") { echo "Nenhuma"; } else { echo $autoavaliacao->getSessaoTrezeObs(); } ?></td>
                    </tr>
                    <?php } ?>
                    <?php if($_SESSION['empresa']['compet_quatorze'] != "") { ?>
                    <tr style="text-align: center;">
                        <td><b><?php echo $_SESSION['empresa']['compet_quatorze']; ?></b></td>
                        <td><b><?php echo $avaliacao->getSessaoQuatorze(); ?></b> <img src="<?php echo getSmile($avaliacao->getSessaoQuatorze()); ?>" width="20"></td>
                        <td><?php if($avaliacao->getSessaoQuatorzeObs() == "") { echo "Nenhuma"; } else { echo $avaliacao->getSessaoQuatorzeObs(); } ?></td>
                        <th><i>versus</i></th>
                        <td><b><?php echo $autoavaliacao->getSessaoQuatorze(); ?></b> <img src="<?php echo getSmile($autoavaliacao->getSessaoQuatorze()); ?>" width="20"></td>
                        <td><?php if($autoavaliacao->getSessaoQuatorzeObs() == "") { echo "Nenhuma"; } else { echo $autoavaliacao->getSessaoQuatorzeObs(); } ?></td>
                    </tr>
                    <?php } ?>
                    <?php if($_SESSION['empresa']['compet_quinze'] != "") { ?>
                    <tr style="text-align: center;">
                        <td><b><?php echo $_SESSION['empresa']['compet_quinze']; ?></b></td>
                        <td><b><?php echo $avaliacao->getSessaoQuinze(); ?></b> <img src="<?php echo getSmile($avaliacao->getSessaoQuinze()); ?>" width="20"></td>
                        <td><?php if($avaliacao->getSessaoQuinzeObs() == "") { echo "Nenhuma"; } else { echo $avaliacao->getSessaoQuinzeObs(); } ?></td>
                        <th><i>versus</i></th>
                        <td><b><?php echo $autoavaliacao->getSessaoQuinze(); ?></b> <img src="<?php echo getSmile($autoavaliacao->getSessaoQuinze()); ?>" width="20"></td>
                        <td><?php if($autoavaliacao->getSessaoQuinzeObs() == "") { echo "Nenhuma"; } else { echo $autoavaliacao->getSessaoQuinzeObs(); } ?></td>
                    </tr>
                    <?php } ?>
                    <?php if($_SESSION['empresa']['compet_dezesseis'] != "") { ?>
                    <tr style="text-align: center;">
                        <td><b><?php echo $_SESSION['empresa']['compet_dezesseis']; ?></b></td>
                        <td><b><?php echo $avaliacao->getSessaoDezesseis(); ?></b> <img src="<?php echo getSmile($avaliacao->getSessaoDezesseis()); ?>" width="20"></td>
                        <td><?php if($avaliacao->getSessaoDezesseisObs() == "") { echo "Nenhuma"; } else { echo $avaliacao->getSessaoDezesseisObs(); } ?></td>
                        <th><i>versus</i></th>
                        <td><b><?php echo $autoavaliacao->getSessaoDezesseis(); ?></b> <img src="<?php echo getSmile($autoavaliacao->getSessaoDezesseis()); ?>" width="20"></td>
                        <td><?php if($autoavaliacao->getSessaoDezesseisObs() == "") { echo "Nenhuma"; } else { echo $autoavaliacao->getSessaoDezesseisObs(); } ?></td>
                    </tr>
                    <?php } ?>
                    <?php if($_SESSION['empresa']['compet_dezessete'] != "") { ?>
                    <tr style="text-align: center;">
                        <td><b><?php echo $_SESSION['empresa']['compet_dezessete']; ?></b></td>
                        <td><b><?php echo $avaliacao->getSessaoDezessete(); ?></b> <img src="<?php echo getSmile($avaliacao->getSessaoDezessete()); ?>" width="20"></td>
                        <td><?php if($avaliacao->getSessaoDezesseteObs() == "") { echo "Nenhuma"; } else { echo $avaliacao->getSessaoDezesseteObs(); } ?></td>
                        <th><i>versus</i></th>
                        <td><b><?php echo $autoavaliacao->getSessaoDezessete(); ?></b> <img src="<?php echo getSmile($autoavaliacao->getSessaoDezessete()); ?>" width="20"></td>
                        <td><?php if($autoavaliacao->getSessaoDezesseteObs() == "") { echo "Nenhuma"; } else { echo $autoavaliacao->getSessaoDezesseteObs(); } ?></td>
                    </tr>
                    <?php } ?>
                    <?php if($_SESSION['empresa']['compet_dezoito'] != "") { ?>
                    <tr style="text-align: center;">
                        <td><b><?php echo $_SESSION['empresa']['compet_dezoito']; ?></b></td>
                        <td><b><?php echo $avaliacao->getSessaoDezoito(); ?></b> <img src="<?php echo getSmile($avaliacao->getSessaoDezoito()); ?>" width="20"></td>
                        <td><?php if($avaliacao->getSessaoDezoitoObs() == "") { echo "Nenhuma"; } else { echo $avaliacao->getSessaoDezoitoObs(); } ?></td>
                        <th><i>versus</i></th>
                        <td><b><?php echo $autoavaliacao->getSessaoDezoito(); ?></b> <img src="<?php echo getSmile($autoavaliacao->getSessaoDezoito()); ?>" width="20"></td>
                        <td><?php if($autoavaliacao->getSessaoDezoitoObs() == "") { echo "Nenhuma"; } else { echo $autoavaliacao->getSessaoDezoitoObs(); } ?></td>
                    </tr>
                    <?php } ?>
                    <?php if($_SESSION['empresa']['compet_dezenove'] != "") { ?>
                    <tr style="text-align: center;">
                        <td><b><?php echo $_SESSION['empresa']['compet_dezenove']; ?></b></td>
                        <td><b><?php echo $avaliacao->getSessaoDezenove(); ?></b> <img src="<?php echo getSmile($avaliacao->getSessaoDezenove()); ?>" width="20"></td>
                        <td><?php if($avaliacao->getSessaoDezenoveObs() == "") { echo "Nenhuma"; } else { echo $avaliacao->getSessaoDezenoveObs(); } ?></td>
                        <th><i>versus</i></th>
                        <td><b><?php echo $autoavaliacao->getSessaoDezenove(); ?></b> <img src="<?php echo getSmile($autoavaliacao->getSessaoDezenove()); ?>" width="20"></td>
                        <td><?php if($autoavaliacao->getSessaoDezenoveObs() == "") { echo "Nenhuma"; } else { echo $autoavaliacao->getSessaoDezenoveObs(); } ?></td>
                    </tr>
                    <?php } ?>
                    <?php if($_SESSION['empresa']['compet_vinte'] != "") { ?>
                    <tr style="text-align: center;">
                        <td><b><?php echo $_SESSION['empresa']['compet_vinte']; ?></b></td>
                        <td><b><?php echo $avaliacao->getSessaoVinte(); ?></b> <img src="<?php echo getSmile($avaliacao->getSessaoVinte()); ?>" width="20"></td>
                        <td><?php if($avaliacao->getSessaoVinteObs() == "") { echo "Nenhuma"; } else { echo $avaliacao->getSessaoVinteObs(); } ?></td>
                        <th><i>versus</i></th>
                        <td><b><?php echo $autoavaliacao->getSessaoVinte(); ?></b> <img src="<?php echo getSmile($autoavaliacao->getSessaoVinte()); ?>" width="20"></td>
                        <td><?php if($autoavaliacao->getSessaoVinteObs() == "") { echo "Nenhuma"; } else { echo $autoavaliacao->getSessaoVinteObs(); } ?></td>
                    </tr>
                    <?php } ?>
                </table>
            </div>
        </div>
    </body>
</html>
