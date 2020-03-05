<?php
    include('../include/auth.php');
    include('../src/meta.php');
    require_once('../classes/class_colaborador.php');

    if($_SESSION['user']['permissao'] != "GESTOR-1" && $_SESSION['user']['permissao'] != "GESTOR-2") {
        include('../include/acessoNegado.php');
        die();
    }

    $colaborador = new Colaborador();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Nova avaliação</title>
    <script>
        function setValor(span, valor) {
            span.innerHTML = valor;
        }
        function validaForm() {
            var form = document.getElementById("form");
            var colaborador = document.getElementById("colaborador").value;

            if(colaborador == "") {
                alert("Selecione o colaborador que está avaliando");
                return true;
            } else {
                form.submit();
            }
        }

        function CriaRequest() {
            try{
                request = new XMLHttpRequest();        
            }
            catch (IEAtual) {
                try{
                    request = new ActiveXObject("Msxml2.XMLHTTP");       
                }
                catch(IEAntigo){
                    try{
                        request = new ActiveXObject("Microsoft.XMLHTTP");          
                    }   
                    catch(falha){
                    request = false;
                    }
                }
            }
      
            if (!request) 
                alert("Seu Navegador não suporta Ajax!");
            else
                return request;
        }
        function getDados(cpf) {
            var resposta = document.getElementById("resposta");
            var xmlreq = CriaRequest();
            resposta.innerHTML = '<h5 class="text">Buscando dados...</h5>';
            xmlreq.open("GET", "ajax/dadosAvaliacao.php?cpf=" + cpf, true);
            xmlreq.onreadystatechange = function(){
                if (xmlreq.readyState == 4) {
                    if (xmlreq.status == 200) {
                        resposta.innerHTML = xmlreq.responseText;
                    }
                    else{
                        resposta.innerHTML = "Erro: " + xmlreq.statusText;
                    }
                }
            };
            xmlreq.send(null);
        }
    </script>
    <style>
        .radioMy {
            width: 18px; 
            height: 20px;
		}
    </style>
</head>
<body>
<?php
    include('../include/navbar.php');
?>
<div class="container-fluid">
    <div class="row" style="text-align: center;">
        <div class="col-sm">
            <h2 class="high-text">Nova avaliação de colaborador</h2>
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

    <div class="row"> 
        <div class="col-sm">
        <form method="POST" action="../database/avaliacao.php?nova=true" id="form">
            <label for="colaborador" class="text">Selecione o colaborador avaliado*</label>
           <select name="colaborador" id="colaborador" class="all-input" onchange="getDados(this.value);">
                <option value="" selected disabled>-- Selecione --</option>
                <?php if($_SESSION['user']['permissao'] == 'GESTOR-2') echo $colaborador->popularSelectAvaliacao($_SESSION['empresa']['database'], $_SESSION['user']['cpf']); ?>
                <?php if($_SESSION['user']['permissao'] == 'GESTOR-1') echo $colaborador->popularSelect($_SESSION['empresa']['database']); ?>
            </select>
        </div>
        <div class="col-sm">
           <label for="modelo" class="text">Selecione o modelo de avaliação*</label>
           <select name="modelo" id="modelo" class="all-input">
                <option value="1" selected>Padrão/Geral</option>
            </select>
        </div>
    </div> 
    <?php if($_SESSION['user']['permissao'] != "GESTOR-1") { ?>
    <div class="row">
        <div class="col-sm">
            <h6 class="text">Lembrete: você só pode avaliar colaboradores que estão vinculados a setores os quais você está vinculado como responsável</h6>
        </div>
    </div>
    <?php } ?>
    
    <div id="resposta"></div>

    <table class="table-site">
        <tr>
            <th>Competência avaliada</th>
            <th>Nota</th>
            <th>Observação</th>
        </tr>
        <tr>
            <td><?php echo $_SESSION['empresa']['compet_um']; ?></td>
            <td>
                <input type="radio" name="compet_um" value="1" class="radioMy" required> <img src="img/unhappy.png" width="30">
                <input type="radio" name="compet_um" value="2" required class="radioMy" style="margin-left: 2em;"> <img src="img/sad.png" width="30">
                <input type="radio" name="compet_um" value="3" class="radioMy" required style="margin-left: 2em;"> <img src="img/confused.png" width="30">
                <input type="radio" name="compet_um" value="4" class="radioMy" required style="margin-left: 2em;"> <img src="img/smiling.png" width="30">
                <input type="radio" name="compet_um" value="5" class="radioMy" required style="margin-left: 2em;"> <img src="img/happy.png" width="30">
            </td>
            <td>
                <textarea name="compet_um_obs" class="all-input"></textarea>
            </td>
        </tr>
        <tr>
            <td><?php echo $_SESSION['empresa']['compet_dois']; ?></td>
            <td>
                <input type="radio" name="compet_dois" value="1" class="radioMy" required> <img src="img/unhappy.png" width="30">
                <input type="radio" name="compet_dois" value="2" required class="radioMy" style="margin-left: 2em;"> <img src="img/sad.png" width="30">
                <input type="radio" name="compet_dois" value="3" class="radioMy" required style="margin-left: 2em;"> <img src="img/confused.png" width="30">
                <input type="radio" name="compet_dois" value="4" class="radioMy" required style="margin-left: 2em;"> <img src="img/smiling.png" width="30">
                <input type="radio" name="compet_dois" value="5" class="radioMy" required style="margin-left: 2em;"> <img src="img/happy.png" width="30">
            </td>
            <td>
                <textarea name="compet_dois_obs" class="all-input"></textarea>
            </td>
        </tr>
        <tr>
            <td><?php echo $_SESSION['empresa']['compet_tres']; ?></td>
            <td>
                <input type="radio" name="compet_tres" value="1" class="radioMy" required> <img src="img/unhappy.png" width="30">
                <input type="radio" name="compet_tres" value="2" required class="radioMy" style="margin-left: 2em;"> <img src="img/sad.png" width="30">
                <input type="radio" name="compet_tres" value="3" class="radioMy" required style="margin-left: 2em;"> <img src="img/confused.png" width="30">
                <input type="radio" name="compet_tres" value="4" class="radioMy" required style="margin-left: 2em;"> <img src="img/smiling.png" width="30">
                <input type="radio" name="compet_tres" value="5" class="radioMy" required style="margin-left: 2em;"> <img src="img/happy.png" width="30">
            </td>
            <td>
                <textarea name="compet_tres_obs" class="all-input"></textarea>
            </td>
        </tr>
        <tr>
            <td><?php echo $_SESSION['empresa']['compet_quatro']; ?></td>
            <td>
                <input type="radio" name="compet_quatro" value="1" class="radioMy" required> <img src="img/unhappy.png" width="30">
                <input type="radio" name="compet_quatro" value="2" required class="radioMy" style="margin-left: 2em;"> <img src="img/sad.png" width="30">
                <input type="radio" name="compet_quatro" value="3" class="radioMy" required style="margin-left: 2em;"> <img src="img/confused.png" width="30">
                <input type="radio" name="compet_quatro" value="4" class="radioMy" required style="margin-left: 2em;"> <img src="img/smiling.png" width="30">
                <input type="radio" name="compet_quatro" value="5" class="radioMy" required style="margin-left: 2em;"> <img src="img/happy.png" width="30">
            </td>
            <td>
                <textarea name="compet_quatro_obs" class="all-input"></textarea>
            </td>
        </tr>
        <?php if($_SESSION['empresa']['compet_cinco'] != "") { ?>
        <tr>
            <td><?php echo $_SESSION['empresa']['compet_cinco']; ?></td>
            <td>
                <input type="radio" name="compet_cinco" value="1" class="radioMy" required> <img src="img/unhappy.png" width="30">
                <input type="radio" name="compet_cinco" value="2" required class="radioMy" style="margin-left: 2em;"> <img src="img/sad.png" width="30">
                <input type="radio" name="compet_cinco" value="3" class="radioMy" required style="margin-left: 2em;"> <img src="img/confused.png" width="30">
                <input type="radio" name="compet_cinco" value="4" class="radioMy" required style="margin-left: 2em;"> <img src="img/smiling.png" width="30">
                <input type="radio" name="compet_cinco" value="5" class="radioMy" required style="margin-left: 2em;"> <img src="img/happy.png" width="30">
            </td>
            <td>
                <textarea name="compet_cinco_obs" class="all-input"></textarea>
            </td>
        </tr>
        <?php } ?>
        <?php if($_SESSION['empresa']['compet_seis'] != "") { ?>
        <tr>
            <td><?php echo $_SESSION['empresa']['compet_seis']; ?></td>
            <td>
                <input type="radio" name="compet_seis" value="1" class="radioMy" required> <img src="img/unhappy.png" width="30">
                <input type="radio" name="compet_seis" value="2" required class="radioMy" style="margin-left: 2em;"> <img src="img/sad.png" width="30">
                <input type="radio" name="compet_seis" value="3" class="radioMy" required style="margin-left: 2em;"> <img src="img/confused.png" width="30">
                <input type="radio" name="compet_seis" value="4" class="radioMy" required style="margin-left: 2em;"> <img src="img/smiling.png" width="30">
                <input type="radio" name="compet_seis" value="5" class="radioMy" required style="margin-left: 2em;"> <img src="img/happy.png" width="30">
            </td>
            <td>
                <textarea name="compet_seis_obs" class="all-input"></textarea>
            </td>
        </tr>
        <?php } ?>
        <?php if($_SESSION['empresa']['compet_sete'] != "") { ?>
        <tr>
            <td><?php echo $_SESSION['empresa']['compet_sete']; ?></td>
            <td>
                <input type="radio" name="compet_sete" value="1" class="radioMy" required> <img src="img/unhappy.png" width="30">
                <input type="radio" name="compet_sete" value="2" required class="radioMy" style="margin-left: 2em;"> <img src="img/sad.png" width="30">
                <input type="radio" name="compet_sete" value="3" class="radioMy" required style="margin-left: 2em;"> <img src="img/confused.png" width="30">
                <input type="radio" name="compet_sete" value="4" class="radioMy" required style="margin-left: 2em;"> <img src="img/smiling.png" width="30">
                <input type="radio" name="compet_sete" value="5" class="radioMy" required style="margin-left: 2em;"> <img src="img/happy.png" width="30">
            </td>
            <td>
                <textarea name="compet_sete_obs" class="all-input"></textarea>
            </td>
        </tr>
        <?php } ?>
        <?php if($_SESSION['empresa']['compet_oito'] != "") { ?>
        <tr>
            <td><?php echo $_SESSION['empresa']['compet_oito']; ?></td>
            <td>
                <input type="radio" name="compet_oito" value="1" class="radioMy" required> <img src="img/unhappy.png" width="30">
                <input type="radio" name="compet_oito" value="2" required class="radioMy" style="margin-left: 2em;"> <img src="img/sad.png" width="30">
                <input type="radio" name="compet_oito" value="3" class="radioMy" required style="margin-left: 2em;"> <img src="img/confused.png" width="30">
                <input type="radio" name="compet_oito" value="4" class="radioMy" required style="margin-left: 2em;"> <img src="img/smiling.png" width="30">
                <input type="radio" name="compet_oito" value="5" class="radioMy" required style="margin-left: 2em;"> <img src="img/happy.png" width="30">
            </td>
            <td>
                <textarea name="compet_oito_obs" class="all-input"></textarea>
            </td>
        </tr>
        <?php } ?>
        <?php if($_SESSION['empresa']['compet_nove'] != "") { ?>
        <tr>
            <td><?php echo $_SESSION['empresa']['compet_nove']; ?></td>
            <td>
                <input type="radio" name="compet_nove" value="1" class="radioMy" required> <img src="img/unhappy.png" width="30">
                <input type="radio" name="compet_nove" value="2" required class="radioMy" style="margin-left: 2em;"> <img src="img/sad.png" width="30">
                <input type="radio" name="compet_nove" value="3" class="radioMy" required style="margin-left: 2em;"> <img src="img/confused.png" width="30">
                <input type="radio" name="compet_nove" value="4" class="radioMy" required style="margin-left: 2em;"> <img src="img/smiling.png" width="30">
                <input type="radio" name="compet_nove" value="5" class="radioMy" required style="margin-left: 2em;"> <img src="img/happy.png" width="30">
            </td>
            <td>
                <textarea name="compet_nove_obs" class="all-input"></textarea>
            </td>
        </tr>
        <?php } ?>
        <?php if($_SESSION['empresa']['compet_dez'] != "") { ?>
        <tr>
            <td><?php echo $_SESSION['empresa']['compet_dez']; ?></td>
            <td>
                <input type="radio" name="compet_dez" value="1" class="radioMy" required> <img src="img/unhappy.png" width="30">
                <input type="radio" name="compet_dez" value="2" required class="radioMy" style="margin-left: 2em;"> <img src="img/sad.png" width="30">
                <input type="radio" name="compet_dez" value="3" class="radioMy" required style="margin-left: 2em;"> <img src="img/confused.png" width="30">
                <input type="radio" name="compet_dez" value="4" class="radioMy" required style="margin-left: 2em;"> <img src="img/smiling.png" width="30">
                <input type="radio" name="compet_dez" value="5" class="radioMy" required style="margin-left: 2em;"> <img src="img/happy.png" width="30">
            </td>
            <td>
                <textarea name="compet_dez_obs" class="all-input"></textarea>
            </td>
        </tr>
        <?php } ?>
        <?php if($_SESSION['empresa']['compet_onze'] != "") { ?>
        <tr>
            <td><?php echo $_SESSION['empresa']['compet_onze']; ?></td>
            <td>
                <input type="radio" name="compet_onze" value="1" class="radioMy" required> <img src="img/unhappy.png" width="30">
                <input type="radio" name="compet_onze" value="2" required class="radioMy" style="margin-left: 2em;"> <img src="img/sad.png" width="30">
                <input type="radio" name="compet_onze" value="3" class="radioMy" required style="margin-left: 2em;"> <img src="img/confused.png" width="30">
                <input type="radio" name="compet_onze" value="4" class="radioMy" required style="margin-left: 2em;"> <img src="img/smiling.png" width="30">
                <input type="radio" name="compet_onze" value="5" class="radioMy" required style="margin-left: 2em;"> <img src="img/happy.png" width="30">
            </td>
            <td>
                <textarea name="compet_onze_obs" class="all-input"></textarea>
            </td>
        </tr>
        <?php } ?>
        <?php if($_SESSION['empresa']['compet_doze'] != "") { ?>
        <tr>
            <td><?php echo $_SESSION['empresa']['compet_doze']; ?></td>
            <td>
                <input type="radio" name="compet_doze" value="1" class="radioMy" required> <img src="img/unhappy.png" width="30">
                <input type="radio" name="compet_doze" value="2" required class="radioMy" style="margin-left: 2em;"> <img src="img/sad.png" width="30">
                <input type="radio" name="compet_doze" value="3" class="radioMy" required style="margin-left: 2em;"> <img src="img/confused.png" width="30">
                <input type="radio" name="compet_doze" value="4" class="radioMy" required style="margin-left: 2em;"> <img src="img/smiling.png" width="30">
                <input type="radio" name="compet_doze" value="5" class="radioMy" required style="margin-left: 2em;"> <img src="img/happy.png" width="30">
            </td>
            <td>
                <textarea name="compet_doze_obs" class="all-input"></textarea>
            </td>
        </tr>
        <?php } ?>
        <?php if($_SESSION['empresa']['compet_treze'] != "") { ?>
        <tr>
            <td><?php echo $_SESSION['empresa']['compet_treze']; ?></td>
            <td>
                <input type="radio" name="compet_treze" value="1" class="radioMy" required> <img src="img/unhappy.png" width="30">
                <input type="radio" name="compet_treze" value="2" required class="radioMy" style="margin-left: 2em;"> <img src="img/sad.png" width="30">
                <input type="radio" name="compet_treze" value="3" class="radioMy" required style="margin-left: 2em;"> <img src="img/confused.png" width="30">
                <input type="radio" name="compet_treze" value="4" class="radioMy" required style="margin-left: 2em;"> <img src="img/smiling.png" width="30">
                <input type="radio" name="compet_treze" value="5" class="radioMy" required style="margin-left: 2em;"> <img src="img/happy.png" width="30">
            </td>
            <td>
                <textarea name="compet_treze_obs" class="all-input"></textarea>
            </td>
        </tr>
        <?php } ?>
        <?php if($_SESSION['empresa']['compet_quatorze'] != "") { ?>
        <tr>
            <td><?php echo $_SESSION['empresa']['compet_quatorze']; ?></td>
            <td>
                <input type="radio" name="compet_quatorze" value="1" class="radioMy" required> <img src="img/unhappy.png" width="30">
                <input type="radio" name="compet_quatorze" value="2" required class="radioMy" style="margin-left: 2em;"> <img src="img/sad.png" width="30">
                <input type="radio" name="compet_quatorze" value="3" class="radioMy" required style="margin-left: 2em;"> <img src="img/confused.png" width="30">
                <input type="radio" name="compet_quatorze" value="4" class="radioMy" required style="margin-left: 2em;"> <img src="img/smiling.png" width="30">
                <input type="radio" name="compet_quatorze" value="5" class="radioMy" required style="margin-left: 2em;"> <img src="img/happy.png" width="30">
            </td>
            <td>
                <textarea name="compet_quatorze_obs" class="all-input"></textarea>
            </td>
        </tr>
        <?php } ?>
        <?php if($_SESSION['empresa']['compet_quinze'] != "") { ?>
        <tr>
            <td><?php echo $_SESSION['empresa']['compet_quinze']; ?></td>
            <td>
                <input type="radio" name="compet_quinze" value="1" class="radioMy" required> <img src="img/unhappy.png" width="30">
                <input type="radio" name="compet_quinze" value="2" required class="radioMy" style="margin-left: 2em;"> <img src="img/sad.png" width="30">
                <input type="radio" name="compet_quinze" value="3" class="radioMy" required style="margin-left: 2em;"> <img src="img/confused.png" width="30">
                <input type="radio" name="compet_quinze" value="4" class="radioMy" required style="margin-left: 2em;"> <img src="img/smiling.png" width="30">
                <input type="radio" name="compet_quinze" value="5" class="radioMy" required style="margin-left: 2em;"> <img src="img/happy.png" width="30">
            </td>
            <td>
                <textarea name="compet_quinze_obs" class="all-input"></textarea>
            </td>
        </tr>
        <?php } ?>
        <?php if($_SESSION['empresa']['compet_dezesseis'] != "") { ?>
        <tr>
            <td><?php echo $_SESSION['empresa']['compet_dezesseis']; ?></td>
            <td>
                <input type="radio" name="compet_dezesseis" value="1" class="radioMy" required> <img src="img/unhappy.png" width="30">
                <input type="radio" name="compet_dezesseis" value="2" required class="radioMy" style="margin-left: 2em;"> <img src="img/sad.png" width="30">
                <input type="radio" name="compet_dezesseis" value="3" class="radioMy" required style="margin-left: 2em;"> <img src="img/confused.png" width="30">
                <input type="radio" name="compet_dezesseis" value="4" class="radioMy" required style="margin-left: 2em;"> <img src="img/smiling.png" width="30">
                <input type="radio" name="compet_dezesseis" value="5" class="radioMy" required style="margin-left: 2em;"> <img src="img/happy.png" width="30">
            </td>
            <td>
                <textarea name="compet_dezesseis_obs" class="all-input"></textarea>
            </td>
        </tr>
        <?php } ?>
        <?php if($_SESSION['empresa']['compet_dezessete'] != "") { ?>
        <tr>
            <td><?php echo $_SESSION['empresa']['compet_dezessete']; ?></td>
            <td>
                <input type="radio" name="compet_dezessete" value="1" class="radioMy" required> <img src="img/unhappy.png" width="30">
                <input type="radio" name="compet_dezessete" value="2" required class="radioMy" style="margin-left: 2em;"> <img src="img/sad.png" width="30">
                <input type="radio" name="compet_dezessete" value="3" class="radioMy" required style="margin-left: 2em;"> <img src="img/confused.png" width="30">
                <input type="radio" name="compet_dezessete" value="4" class="radioMy" required style="margin-left: 2em;"> <img src="img/smiling.png" width="30">
                <input type="radio" name="compet_dezessete" value="5" class="radioMy" required style="margin-left: 2em;"> <img src="img/happy.png" width="30">
            </td>
            <td>
                <textarea name="compet_dezessete_obs" class="all-input"></textarea>
            </td>
        </tr>
        <?php } ?>
        <?php if($_SESSION['empresa']['compet_dezoito'] != "") { ?>
        <tr>
            <td><?php echo $_SESSION['empresa']['compet_dezoito']; ?></td>
            <td>
                <input type="radio" name="compet_dezoito" value="1" class="radioMy" required> <img src="img/unhappy.png" width="30">
                <input type="radio" name="compet_dezoito" value="2" required class="radioMy" style="margin-left: 2em;"> <img src="img/sad.png" width="30">
                <input type="radio" name="compet_dezoito" value="3" class="radioMy" required style="margin-left: 2em;"> <img src="img/confused.png" width="30">
                <input type="radio" name="compet_dezoito" value="4" class="radioMy" required style="margin-left: 2em;"> <img src="img/smiling.png" width="30">
                <input type="radio" name="compet_dezoito" value="5" class="radioMy" required style="margin-left: 2em;"> <img src="img/happy.png" width="30">
            </td>
            <td>
                <textarea name="compet_dezoito_obs" class="all-input"></textarea>
            </td>
        </tr>
        <?php } ?>
        <?php if($_SESSION['empresa']['compet_dezenove'] != "") { ?>
        <tr>
            <td><?php echo $_SESSION['empresa']['compet_dezenove']; ?></td>
            <td>
                <input type="radio" name="compet_dezenove" value="1" class="radioMy" required> <img src="img/unhappy.png" width="30">
                <input type="radio" name="compet_dezenove" value="2" required class="radioMy" style="margin-left: 2em;"> <img src="img/sad.png" width="30">
                <input type="radio" name="compet_dezenove" value="3" class="radioMy" required style="margin-left: 2em;"> <img src="img/confused.png" width="30">
                <input type="radio" name="compet_dezenove" value="4" class="radioMy" required style="margin-left: 2em;"> <img src="img/smiling.png" width="30">
                <input type="radio" name="compet_dezenove" value="5" class="radioMy" required style="margin-left: 2em;"> <img src="img/happy.png" width="30">
            </td>
            <td>
                <textarea name="compet_dezenove_obs" class="all-input"></textarea>
            </td>
        </tr>
        <?php } ?>
        <?php if($_SESSION['empresa']['compet_vinte'] != "") { ?>
        <tr>
            <td><?php echo $_SESSION['empresa']['compet_vinte']; ?></td>
            <td>
                <input type="radio" name="compet_vinte" value="1" class="radioMy" required> <img src="img/unhappy.png" width="30">
                <input type="radio" name="compet_vinte" value="2" required class="radioMy" style="margin-left: 2em;"> <img src="img/sad.png" width="30">
                <input type="radio" name="compet_vinte" value="3" class="radioMy" required style="margin-left: 2em;"> <img src="img/confused.png" width="30">
                <input type="radio" name="compet_vinte" value="4" class="radioMy" required style="margin-left: 2em;"> <img src="img/smiling.png" width="30">
                <input type="radio" name="compet_vinte" value="5" class="radioMy" required style="margin-left: 2em;"> <img src="img/happy.png" width="30">
            </td>
            <td>
                <textarea name="compet_vinte_obs" class="all-input"></textarea>
            </td>
        </tr>
        <?php } ?>
    </table>

    <h6 class="text" style="margin-top: 1em;">O prazo pré definido para liberação desta avaliação ao colaborador é de 30 dias. 
    Você poderá liberá-la antes.</h6>

    <hr class="hr-divide-light">

    <div class="row">
        <div class="col-sm-2 offset-sm-4">
            <input type="button" onclick="validaForm();" value="Cadastrar" class="button button2">
        </div>
        <div class="col-sm-2">
            <input type="reset" value="Limpar" class="button button1">
        </div>
    </div>
    </form>
</div>
</body>
</html>