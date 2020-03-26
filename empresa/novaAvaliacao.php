<?php
    include('../include/auth.php');
    include('../src/meta.php');
    require_once('../classes/class_colaborador.php');

    if($_SESSION['empresa']['plano'] != "REVOLUCAO" || $_SESSION['empresa']['plano'] != "AVALIACAO") {
        $_SESSION['msg'] = "O plano atualmente utilizado pela sua empresa não permite acesso a este 
        módulo do Staffast. <a href='../planos.php'>Conheça nossos planos</a>.";
        header('Location: home.php');
        die();
    }

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
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
    <script>

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
            var table_avaliacao = document.getElementById("table_avaliacao");
            table_avaliacao.innerHTML = "";
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

        function carregarModelos(cpf) {
            var resposta = document.getElementById("modelos_carregados");
            var xmlreq = CriaRequest();
            xmlreq.open("GET", "ajax/carregarModelos.php?cpf=" + cpf, true);
            xmlreq.onreadystatechange = function(){
                if (xmlreq.readyState == 4) {
                    if (xmlreq.status == 200) {
                        resposta.innerHTML = xmlreq.responseText;
                    }
                    else{
                        //do nothing
                    }
                }
            };
            xmlreq.send(null);
        }

        function exibirAvaliacao(modelo_id) {
            var resposta = document.getElementById("table_avaliacao");
            var xmlreq = CriaRequest();
            xmlreq.open("GET", "ajax/carregaTableAvaliacao.php?modelo_id=" + modelo_id, true);
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

        function validaForm(num_competencias) {
            var colaborador = $('#colaborador').val();
            var modelo = $('#modelo').val();
            var numCompetencias = 0;

            if(colaborador == "") {
                alert('Selecione o colaborador que está avaliando');
                return false;
            }

            var url = "/staffast/database/avaliacao.php?nova=true&colaborador="+colaborador+"&modelo="+modelo;
            if (!$("input[name='compet_um']:checked").val()) {
                alert('Selecione uma nota para a 1ª competência');
                return false;
            }
            url = url + "&compet_um="+$("input[name='compet_um']:checked").val()+"&compet_um_obs="+$('#compet_um_obs').val();
            numCompetencias = numCompetencias + 1;

            if (!$("input[name='compet_dois']:checked").val()) {
                alert('Selecione uma nota para a 2ª competência');
                return false;
            }
            url = url + "&compet_dois="+$("input[name='compet_dois']:checked").val()+"&compet_dois_obs="+$('#compet_dois_obs').val();
            numCompetencias = numCompetencias + 1;

            if (!$("input[name='compet_tres']:checked").val()) {
                alert('Selecione uma nota para a 3ª competência');
                return false;
            }
            url = url + "&compet_tres="+$("input[name='compet_tres']:checked").val()+"&compet_tres_obs="+$('#compet_tres_obs').val();
            numCompetencias = numCompetencias + 1;

            if (!$("input[name='compet_quatro']:checked").val()) {
                alert('Selecione uma nota para a 4ª competência');
                return false;
            }
            url = url + "&compet_quatro="+$("input[name='compet_quatro']:checked").val()+"&compet_quatro_obs="+$('#compet_quatro_obs').val();
            numCompetencias = numCompetencias + 1;

            if(num_competencias >= 5) {
                if (!$("input[name='compet_cinco']:checked").val()) {
                    alert('Selecione uma nota para a 5ª competência');
                    return false;
                }
                url = url + "&compet_cinco="+$("input[name='compet_cinco']:checked").val()+"&compet_cinco_obs="+$('#compet_cinco_obs').val();
                numCompetencias = numCompetencias + 1;
            }

            if(num_competencias >= 6) {
                if (!$("input[name='compet_seis']:checked").val()) {
                    alert('Selecione uma nota para a 6ª competência');
                    return false;
                }
                url = url + "&compet_seis="+$("input[name='compet_seis']:checked").val()+"&compet_seis_obs="+$('#compet_seis_obs').val();
                numCompetencias = numCompetencias + 1;
            }

            if(num_competencias >= 7) {
                if (!$("input[name='compet_sete']:checked").val()) {
                    alert('Selecione uma nota para a 7ª competência');
                    return false;
                }
                url = url + "&compet_sete="+$("input[name='compet_sete']:checked").val()+"&compet_sete_obs="+$('#compet_sete_obs').val();
                numCompetencias = numCompetencias + 1;
            }

            if(num_competencias >= 8) {
                if (!$("input[name='compet_oito']:checked").val()) {
                    alert('Selecione uma nota para a 8ª competência');
                    return false;
                }
                url = url + "&compet_oito="+$("input[name='compet_oito']:checked").val()+"&compet_oito_obs="+$('#compet_oito_obs').val();
                numCompetencias = numCompetencias + 1;
            }

            if(num_competencias >= 9) {
                if (!$("input[name='compet_nove']:checked").val()) {
                    alert('Selecione uma nota para a 9ª competência');
                    return false;
                }
                url = url + "&compet_nove="+$("input[name='compet_nove']:checked").val()+"&compet_nove_obs="+$('#compet_nove_obs').val();
                numCompetencias = numCompetencias + 1;
            }

            if(num_competencias >= 10) {
                if (!$("input[name='compet_dez']:checked").val()) {
                    alert('Selecione uma nota para a 10ª competência');
                    return false;
                }
                url = url + "&compet_dez="+$("input[name='compet_dez']:checked").val()+"&compet_dez_obs="+$('#compet_dez_obs').val();
                numCompetencias = numCompetencias + 1;
            }

            if(num_competencias >= 11) {
                if (!$("input[name='compet_onze']:checked").val()) {
                    alert('Selecione uma nota para a 11ª competência');
                    return false;
                }
                url = url + "&compet_onze="+$("input[name='compet_onze']:checked").val()+"&compet_onze_obs="+$('#compet_onze_obs').val();
                numCompetencias = numCompetencias + 1;
            }

            if(num_competencias >= 12) {
                if (!$("input[name='compet_doze']:checked").val()) {
                    alert('Selecione uma nota para a 12ª competência');
                    return false;
                }
                url = url + "&compet_doze="+$("input[name='compet_doze']:checked").val()+"&compet_doze_obs="+$('#compet_doze_obs').val();
                numCompetencias = numCompetencias + 1;
            }

            if(num_competencias >= 13) {
                if (!$("input[name='compet_treze']:checked").val()) {
                    alert('Selecione uma nota para a 13ª competência');
                    return false;
                }
                url = url + "&compet_treze="+$("input[name='compet_treze']:checked").val()+"&compet_treze_obs="+$('#compet_treze_obs').val();
                numCompetencias = numCompetencias + 1;
            }

            if(num_competencias >= 14) {
                if (!$("input[name='compet_quatorze']:checked").val()) {
                    alert('Selecione uma nota para a 14ª competência');
                    return false;
                }
                url = url + "&compet_quatorze="+$("input[name='compet_quatorze']:checked").val()+"&compet_quatorze_obs="+$('#compet_quatorze_obs').val();
                numCompetencias = numCompetencias + 1;
            }

            if(num_competencias >= 15) {
                if (!$("input[name='compet_quinze']:checked").val()) {
                    alert('Selecione uma nota para a 15ª competência');
                    return false;
                }
                url = url + "&compet_quinze="+$("input[name='compet_quinze']:checked").val()+"&compet_quinze_obs="+$('#compet_quinze_obs').val();
                numCompetencias = numCompetencias + 1;
            }

            if(num_competencias >= 16) {
                if (!$("input[name='compet_dezesseis']:checked").val()) {
                    alert('Selecione uma nota para a 16ª competência');
                    return false;
                }
                url = url + "&compet_dezesseis="+$("input[name='compet_dezesseis']:checked").val()+"&compet_dezesseis_obs="+$('#compet_dezesseis_obs').val();
                numCompetencias = numCompetencias + 1;
            }

            if(num_competencias >= 17) {
                if (!$("input[name='compet_dezessete']:checked").val()) {
                    alert('Selecione uma nota para a 17ª competência');
                    return false;
                }
                url = url + "&compet_dezessete="+$("input[name='compet_dezessete']:checked").val()+"&compet_dezessete_obs="+$('#compet_dezessete_obs').val();
                numCompetencias = numCompetencias + 1;
            }

            if(num_competencias >= 18) {
                if (!$("input[name='compet_dezoito']:checked").val()) {
                    alert('Selecione uma nota para a 18ª competência');
                    return false;
                }
                url = url + "&compet_dezoito="+$("input[name='compet_dezoito']:checked").val()+"&compet_dezoito_obs="+$('#compet_dezoito_obs').val();
                numCompetencias = numCompetencias + 1;
            }

            if(num_competencias >= 19) {
                if (!$("input[name='compet_dezenove']:checked").val()) {
                    alert('Selecione uma nota para a 19ª competência');
                    return false;
                }
                url = url + "&compet_dezenove="+$("input[name='compet_dezenove']:checked").val()+"&compet_dezenove_obs="+$('#compet_dezenove_obs').val();
                numCompetencias = numCompetencias + 1;
            }

            if(num_competencias >= 20) {
                if (!$("input[name='compet_vinte']:checked").val()) {
                    alert('Selecione uma nota para a 20ª competência');
                    return false;
                }
                url = url + "&compet_vinte="+$("input[name='compet_vinte']:checked").val()+"&compet_vinte_obs="+$('#compet_vinte_obs').val();
                numCompetencias = numCompetencias + 1;
            }

            //Enviar por GET
            window.location.href = url+"&numCompetencias="+numCompetencias;
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

    <!-- NAV DE CAMINHO DE TELA -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="./">Início</a></li>
            <li class="breadcrumb-item active" aria-current="page">Nova avaliação de colaborador</li>
        </ol>
    </nav>
    <!-- FIM DA NAV DE CAMINHO DE TELA -->

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
            <label for="colaborador" class="text">Selecione o colaborador avaliado*</label>
           <select name="colaborador" id="colaborador" class="all-input" onchange="getDados(this.value); carregarModelos(this.value);">
                <option value="" selected disabled>-- Selecione --</option>
                <?php if($_SESSION['user']['permissao'] == 'GESTOR-2') echo $colaborador->popularSelectAvaliacao($_SESSION['empresa']['database'], $_SESSION['user']['cpf']); ?>
                <?php if($_SESSION['user']['permissao'] == 'GESTOR-1') echo $colaborador->popularSelect($_SESSION['empresa']['database']); ?>
            </select>
        </div>
        <div class="col-sm">
            <div id="modelos_carregados"></div>
        </div>
    </div> 
    <?php if($_SESSION['user']['permissao'] != "GESTOR-1") { ?>
    <div class="row">
        <div class="col-sm">
            <h6 class="text">Lembrete: você só pode avaliar colaboradores que estão vinculados a setores os quais você está vinculado como responsável</h6>
        </div>
    </div>
    <?php } ?>

    <hr class="hr-divide-super-light">
    
    <div id="resposta"></div>

    <hr class="hr-divide-super-light">

    <div id="table_avaliacao"></div>
</div>
</body>
</html>