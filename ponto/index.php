<?php
    session_start();
    include('../src/meta.php');

    if(isset($_COOKIE['staffast_ponto_email'])) $email = $_COOKIE['staffast_ponto_email'];
    else if (isset($_GET['email'])) $email = base64_decode($_GET['email']);
    else $email = '';
?>
<!DOCTYPE html>
<html>
<head>
    <title>Registrar Ponto</title>
    <script>
    $(document).ready(function() { 
        var email = $('#email').val();
        if (email != '') {
            identificarFuncionario(email);
        }
     });

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
        function bater(tipo) {
            var resposta = document.getElementById("resposta");
            var email = document.getElementById("email").value;
            if(email == "") {
                alert("Insira o e-mail");
                return;
            }
            var xmlreq = CriaRequest();
            resposta.innerHTML = '<div class="spinner-border text-success" role="status"><span class="sr-only">Loading...</span></div>';
            xmlreq.open("GET", "ajax/bater.php?email=" + email + "&tipo=" + tipo, true);
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
        function enviarAnotacao() {
            var resposta = document.getElementById("resposta");
            var anotacao = document.getElementById("anotacao").value;
            var email = document.getElementById("email").value;
            if(anotacao == "") {
                alert("Escreve algo como anotação");
                return;
            }
            var xmlreq = CriaRequest();
            resposta.innerHTML = '<div class="spinner-border text-success" role="status"><span class="sr-only">Loading...</span></div>';
            xmlreq.open("GET", "ajax/anotar.php?anotacao=" + anotacao + "&email=" + email, true);
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
        function identificarFuncionario(email) {
            var resposta = document.getElementById("funcionario");
            var xmlreq = CriaRequest();
            resposta.innerHTML = 'Buscando...';
            xmlreq.open("GET", "ajax/identificar.php?email=" + email, true);
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
<body style="margin-top: 0em;">
<div class="container-fluid" style="text-align: center;">
    <div class="row">
        <div class="col-sm">
            <img src="../img/logo_staffast.png" width="200">
        </div>
    </div>

    <hr class="hr-divide-super-light">
</div>
<div class="container" style="text-align: center;">

    <div class="row" style="margin-bottom: 2em;">
        <div class="col-sm" id="resposta">
            
        </div>
    </div>

    <div class="row">
        <div class="col-sm">
            <label class="text">Insira o e-mail usado no Staffast</label>
            <input type="email" name="email" id="email" class="all-input" value="<?php echo $email; ?>" onkeyup="identificarFuncionario(this.value);">
        </div>
    </div>

    <div class="row">
        <div class="col-sm" style="margin-top: 1em;">
            <h6 class="text" id="funcionario"></h6>
        </div>
    </div>

    <div class="row">    
        <div class="col-sm" style="margin-top: 4em;">
            <h5 class="text">Entrada</h5>
            <img src="img/entrance.png" width="150" data-toggle="tooltip" data-placement="top" title="Entrada" onClick="bater('1');">
        </div>

        <div class="col-sm" style="margin-top: 4em;">
            <h5 class="text">Pausa</h5>
            <img src="img/fruits.png" width="150" data-toggle="tooltip" data-placement="top" title="Almoço/Jantar/Lanche" onClick="bater('2');">
        </div>

        <div class="col-sm" style="margin-top: 4em;">
            <h5 class="text">Retorno pausa</h5>
            <img src="img/time.png" width="150" data-toggle="tooltip" data-placement="top" title="Almoço/Jantar/Lanche" onClick="bater('3');">
        </div>

        <div class="col-sm" style="margin-top: 4em;">
            <h5 class="text">Saída</h5>
            <img src="img/exit.png" width="150" data-toggle="tooltip" data-placement="top" title="Saída" onClick="bater('4');">
        </div>
    </div>

    <hr class="hr-divide-super-light">

    <div class="row">
        <div class="col-sm">
            <a href="../login.php?historicoPonto=true"><button class="button button1">Quero ver meu histórico</button></a>
        </div>
    </div>

</div>


<script>
    $(function () {
        $('[data-toggle="tooltip"]').tooltip()
    })
</script>