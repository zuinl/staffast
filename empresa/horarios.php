<?php
    include('../include/auth.php');
    include('../src/meta.php');
    require_once('../classes/class_colaborador.php');
    require_once('../classes/class_gestor.php');

    if($_SESSION['user']['permissao'] != "GESTOR-1") {
        include("../include/acessoNegado.php");
        die();
    }

    $colaborador = new Colaborador();
    $gestor = new Gestor();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Gerenciar horários</title>
    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
    <script>
    function clonar(){
        $("#clonar").click(function(){
            if($('#clonar').val() == 0) {
                var confirma = confirm("Certeza de que deseja copiar os horários inseridos na Segunda-feira para os outros dias úteis?")
                if(confirma == true) {
                    $('#entrada_tuesday').val($('#entrada_monday').val());
                    $('#pausa_tuesday').val($('#pausa_monday').val());
                    $('#retorno_tuesday').val($('#retorno_monday').val());
                    $('#saida_tuesday').val($('#saida_monday').val());

                    $('#entrada_wednesday').val($('#entrada_monday').val());
                    $('#pausa_wednesday').val($('#pausa_monday').val());
                    $('#retorno_wednesday').val($('#retorno_monday').val());
                    $('#saida_wednesday').val($('#saida_monday').val());

                    $('#entrada_thursday').val($('#entrada_monday').val());
                    $('#pausa_thursday').val($('#pausa_monday').val());
                    $('#retorno_thursday').val($('#retorno_monday').val());
                    $('#saida_thursday').val($('#saida_monday').val());

                    $('#entrada_friday').val($('#entrada_monday').val());
                    $('#pausa_friday').val($('#pausa_monday').val());
                    $('#retorno_friday').val($('#retorno_monday').val());
                    $('#saida_friday').val($('#saida_monday').val());
                } else {
                    return false;
                }
                
                $('#clonar').val(1);
            } else {
                $('#entrada_tuesday').val("");
                $('#pausa_tuesday').val("");
                $('#retorno_tuesday').val("");
                $('#saida_tuesday').val("");

                $('#entrada_wednesday').val("");
                $('#pausa_wednesday').val("");
                $('#retorno_wednesday').val("");
                $('#saida_wednesday').val("");

                $('#entrada_thursday').val("");
                $('#pausa_thursday').val("");
                $('#retorno_thursday').val("");
                $('#saida_thursday').val("");

                $('#entrada_friday').val("");
                $('#pausa_friday').val("");
                $('#retorno_friday').val("");
                $('#saida_friday').val("");
            }
        });
    }
    </script>
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

        function atualizarHorario() {
            var cpf = document.getElementById("funcionario").value;
            var entrada_monday = document.getElementById("entrada_monday").value;
            var pausa_monday = document.getElementById("pausa_monday").value;
            var retorno_monday = document.getElementById("retorno_monday").value;
            var saida_monday = document.getElementById("saida_monday").value;

            var entrada_tuesday = document.getElementById("entrada_tuesday").value;
            var pausa_tuesday = document.getElementById("pausa_tuesday").value;
            var retorno_tuesday = document.getElementById("retorno_tuesday").value;
            var saida_tuesday = document.getElementById("saida_tuesday").value;

            var entrada_wednesday = document.getElementById("entrada_wednesday").value;
            var pausa_wednesday = document.getElementById("pausa_wednesday").value;
            var retorno_wednesday = document.getElementById("retorno_wednesday").value;
            var saida_wednesday = document.getElementById("saida_wednesday").value;

            var entrada_thursday = document.getElementById("entrada_thursday").value;
            var pausa_thursday = document.getElementById("pausa_thursday").value;
            var retorno_thursday = document.getElementById("retorno_thursday").value;
            var saida_thursday = document.getElementById("saida_thursday").value;

            var entrada_friday = document.getElementById("entrada_friday").value;
            var pausa_friday = document.getElementById("pausa_friday").value;
            var retorno_friday = document.getElementById("retorno_friday").value;
            var saida_friday = document.getElementById("saida_friday").value;

            var entrada_saturday = document.getElementById("entrada_saturday").value;
            var pausa_saturday = document.getElementById("pausa_saturday").value;
            var retorno_saturday = document.getElementById("retorno_saturday").value;
            var saida_saturday = document.getElementById("saida_saturday").value;

            var entrada_sunday = document.getElementById("entrada_sunday").value;
            var pausa_sunday = document.getElementById("pausa_sunday").value;
            var retorno_sunday = document.getElementById("retorno_sunday").value;
            var saida_sunday = document.getElementById("saida_sunday").value;

            var pausa_flexivel = document.getElementById("pausa_flexivel");
            var horario_flexivel = document.getElementById("horario_flexivel");
            var hora_extra = document.getElementById("hora_extra");
            var tolerancia = document.getElementById("tolerancia").value;
            var noturno = document.getElementById("noturno");
            var ponto_site = document.getElementById("ponto_site");

            if(pausa_flexivel.checked == true) pausa_flexivel.value = 1;
            else pausa_flexivel.value = 0;

            if(horario_flexivel.checked == true) horario_flexivel.value = 1;
            else horario_flexivel.value = 0;

            if(hora_extra.checked == true) hora_extra.value = 1;
            else hora_extra.value = 0;

            if(noturno.checked == true) noturno.value = 1;
            else noturno.value = 0;

            if(ponto_site.checked == true) ponto_site.value = 1;
            else ponto_site.value = 0;

            var resposta = document.getElementById("resposta");

            var xmlreq = CriaRequest();
            resposta.focus();
            resposta.innerHTML = '<div class="conteiner" style="text-align: center;"><div class="spinner-border text-success" role="status"><span class="sr-only">Loading...</span></div></div>';
            xmlreq.open("GET", "../database/horario.php?atualizar=true&cpf="+cpf+"&entrada_monday="+entrada_monday+"&pausa_monday="+pausa_monday+"&retorno_monday="+retorno_monday+"&saida_monday="+saida_monday+"&entrada_tuesday="+entrada_tuesday+"&pausa_tuesday="+pausa_tuesday+"&retorno_tuesday="+retorno_tuesday+"&saida_tuesday="+saida_tuesday+"&entrada_wednesday="+entrada_wednesday+"&pausa_wednesday="+pausa_wednesday+"&retorno_wednesday="+retorno_wednesday+"&saida_wednesday="+saida_wednesday+"&entrada_thursday="+entrada_thursday+"&pausa_thursday="+pausa_thursday+"&retorno_thursday="+retorno_thursday+"&saida_thursday="+saida_thursday+"&entrada_friday="+entrada_friday+"&pausa_friday="+pausa_friday+"&retorno_friday="+retorno_friday+"&saida_friday="+saida_friday+"&entrada_saturday="+entrada_saturday+"&pausa_saturday="+pausa_saturday+"&retorno_saturday="+retorno_saturday+"&saida_saturday="+saida_saturday+"&entrada_sunday="+entrada_sunday+"&pausa_sunday="+pausa_sunday+"&retorno_sunday="+retorno_sunday+"&saida_sunday="+saida_sunday+"&pausa_flexivel="+pausa_flexivel.value+"&horario_flexivel="+horario_flexivel.value+"&noturno="+noturno.value+"&tolerancia="+tolerancia+"&hora_extra="+hora_extra.value+"&ponto_site="+ponto_site.value, true);
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

        function mostrarHorarios() {
            var cpf = document.getElementById("funcionario").value;

            if(cpf == "") {
                alert('Selecione um funcionário');
                return false;
            }

            var resposta = document.getElementById("resposta");

            var xmlreq = CriaRequest();
            resposta.focus();
            resposta.innerHTML = '<div class="conteiner" style="text-align: center;"><div class="spinner-border text-success" role="status"><span class="sr-only">Loading...</span></div></div>';
            xmlreq.open("GET", "ajax/atualizarHorario.php?cpf="+cpf, true);
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
</head>
<body>
<?php
    include('../include/navbar.php');
?>
<div class="container-fluid" style="text-align: center;">
    <!-- NAV DE CAMINHO DE TELA -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="home.php">Início</a></li>
            <li class="breadcrumb-item active" aria-current="page">Horários dos funcionários</li>
        </ol>
    </nav>
    <!-- FIM DA NAV DE CAMINHO DE TELA -->

    <div class="row">
        <div class="col-sm">
            <h2 class="high-text">Horários dos funcionários</h2>
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

    <div class="row" style="text-align: center;">
        <div class="col-sm">
            <label class="text">Selecione funcionário</label>
            <select id="funcionario" name="funcionario" class="all-input">
                <option value="" disabled selected>-- Selecione --</option>
                <option value="" disabled>-- GESTORES</option>
                <?php
                    $gestor->popularSelect($_SESSION['empresa']['database']);
                ?>
                <option value="" disabled>-- COLABORADORES</option>
                <?php
                    $colaborador->popularSelect($_SESSION['empresa']['database']);
                ?>
            </select>
        </div>
    </div>
    <div class="row" style="text-align: center;">
        <div class="col-sm">
            <input type="button" class="button button1" value="Mostrar horários" onclick="mostrarHorarios();">
        </div>
    </div>

    <hr class="hr-divide-light">

    <div id="resposta"></div>

</div>
</body>
</html>