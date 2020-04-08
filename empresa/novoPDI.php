<?php
    include('../include/auth.php');
    include('../src/meta.php');
    include('../classes/class_gestor.php');
    include('../classes/class_colaborador.php');
    include('../classes/class_pdi.php');


    if($_SESSION['empresa']['plano'] != "REVOLUCAO") {
        $_SESSION['msg'] = "O plano atualmente utilizado pela sua empresa não permite acesso a este 
        módulo do Staffast. <a href='../planos.php'>Conheça nossos planos</a>.";
        header('Location: home.php');
        die();
    }

    $colaborador = new Colaborador();
    $gestor = new Gestor();

    $pdi = new PDI();

    if(isset($_GET['editar'])) {
        $pdi->setID($_GET['id']);
        $title = 'Editar';
        $action = 'editar';
        $button = 'Salvar alterações';
    } else {
        $title = 'Novo';
        $action = 'novo';
        $button = 'Salvar e ver meu PDI';
    }

    $pdi = $pdi->retornarPDI($_SESSION['empresa']['database']);
?>
<!DOCTYPE html>
<html>
<head>
    <title><?php echo $title; ?> PDI</title>    
    <script>
        function preencherExemplo() {
            var exemplo = document.getElementById('exemplo');
            var btn = document.getElementById('btnExemplo');

            if(exemplo.style.display == 'none') {
                exemplo.style.display = 'block';
                btn.value = 'Ocultar exemplo';
                exemplo.focus();
            } else {
                exemplo.style.display = 'none';
                btn.value = 'Me mostre um exemplo';
                document.getElementById('titulo').focus();
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
        function criarPDI(action = 'novo') {
            var resposta = document.getElementById("div-resposta");
            var titulo = document.getElementById("titulo");
            var prazo = document.getElementById("prazo");
            var pdi_id = 0;
            var dono = '';
            var orientador = '';

            if(action == 'editar') {
                pdi_id = document.getElementById("pdi_id");
            } else if(action == 'novo') {
                dono = document.getElementById("dono");
                orientador = document.getElementById("orientador");
            } 

            if(titulo.value == "") {
                resposta.innerHTML = '<div class="col-sm"><div class="alert alert-warning alert-dismissible fade show" role="alert">Insira o <b>título</b> do Plano de Desenvolvimento Individual<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div></div>';
                return false;
            }
            
            if(dono.value == "") {
                resposta.innerHTML = '<div class="col-sm"><div class="alert alert-warning alert-dismissible fade show" role="alert">Selecione <b>para quem</b> é este PDI<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div></div>';
                return false;
            }

            if(prazo.value == "") {
                resposta.innerHTML = '<div class="col-sm"><div class="alert alert-warning alert-dismissible fade show" role="alert">Insira o <b>prazo</b> do Plano de Desenvolvimento Individual<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div></div>';
                return false;
            }

            var xmlreq = CriaRequest();
            resposta.innerHTML = '<div class="row"><div class="col-sm"><div class="spinner-border text-success" role="status"><span class="sr-only">Loading...</span></div></div></div>';
            xmlreq.open("GET", "../database/pdi.php?" + action +"=true&titulo=" + titulo.value + "&dono=" + dono.value + "&orientador=" + orientador.value + "&prazo=" + prazo.value + "&pdi_id=" + pdi_id.value, true);
            xmlreq.onreadystatechange = function(){
                if (xmlreq.readyState == 4) {
                    if (xmlreq.status == 200) {
                        resposta.innerHTML = '<div class="col-sm"><div class="alert alert-success alert-dismissible fade show" role="alert">'+xmlreq.responseText+'<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div></div>';
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
<div class="container-fluid">

    <!-- NAV DE CAMINHO DE TELA -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="home.php">Início</a></li>
            <li class="breadcrumb-item"><a href="PDIs.php">Planos de Desenvolvimento Individual (PDIs)</a></li>
            <?php if(isset($_GET['editar'])) { ?> <li class="breadcrumb-item"><a href="verPDI.php?id=<?php echo $pdi->getID(); ?>">PDI - <?php echo $pdi->getTitulo(); ?></a></li> <?php } ?>
            <li class="breadcrumb-item active" aria-current="page"><?php echo $title; ?> PDI</li>
        </ol>
    </nav>
    <!-- FIM DA NAV DE CAMINHO DE TELA -->

    <div class="row">
        <div class="col-sm-6 offset-sm-2">
            <h4 class="high-text"><?php echo $title; ?> <span class="destaque-text">Plano de Desenvolvimento Individual (PDI)</span></h4>
        </div>
        <div class="col-sm-2">
            <input type="button" class="button button3" id="btnExemplo" value="Me mostre um exemplo" onclick="preencherExemplo();">
        </div>
    </div>

    <hr class="hr-divide">

    <!-- <?php
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
    ?> -->
    <div class="row" id="div-resposta" style="text-align: center;"></div>
</div>
<div class="container">
    <div class="row" style="margin-top: 1em;">
        <div class="col-sm">
            <label for="titulo" class="text">Qual o objetivo principal a ser atingido? *</label>
            <input type="text" name="titulo" id="titulo" value="<?php echo $pdi->getTitulo(); ?>" class="all-input" maxlength="60" required>
        </div>
    </div>

    <div class="row" style="margin-top: 1em;">
        <div class="col-sm">
            <label class="text">Até quando esse objetivo deve ser atingido? *</label>
            <input type="date" name="prazo" id="prazo" value="<?php echo $pdi->getPrazo_format(); ?>"  class="all-input" required>
        </div>   
    </div>

    <div id="direcionados">

        <div class="row" style="margin-top: 1em;">
            <?php if(!isset($_GET['editar'])) { ?>
            <div class="col-sm">
                <label class="text">Este Plano de Desenvolvimento Individual é para...</label>
                <select name="dono" id="dono" class="all-input" required>
                    <?php if($_SESSION['user']['permissao'] == 'COLABORADOR') { ?>
                        <option value="<?php echo $_SESSION['user']['cpf'] ?>" selected><?php echo $_SESSION['user']['nome_completo'] ?></option>
                    <?php } else if ($_SESSION['user']['permissao'] == 'GESTOR-2') {
                        ?>
                        <option value="">- Selecione -</option>
                        <option value="<?php echo $_SESSION['user']['cpf'] ?>">Eu mesmo - <?php echo $_SESSION['user']['nome_completo'] ?></option>
                        <?php
                        $colaborador->popularSelectAvaliacao($_SESSION['empresa']['database'], $_SESSION['user']['cpf'], $_SESSION['user']['permissao']);
                        } else { ?>
                        <option value="">- Selecione -</option>
                        <option value="<?php echo $_SESSION['user']['cpf'] ?>">Eu mesmo - <?php echo $_SESSION['user']['nome_completo'] ?></option>
                        <option value="" disabled>--- COLABORADORES</option>
                        <?php
                        $colaborador->popularSelect($_SESSION['empresa']['database']);
                        ?>
                        <option value="" disabled>--- GESTORES</option>
                        <?php
                        $gestor->popularSelect($_SESSION['empresa']['database']);
                        ?>
                    <?php } ?>
                </select>
                <?php if($_SESSION['user']['permissao'] == 'GESTOR-2') { ?>
                    <small class="text">Atenção: como Gestor Operacional, você só poderá criar um PDI para você mesmo ou os colaboradores 
                    do(s) seu(s) setor(es)</small>
                <?php } ?>
                <?php if($_SESSION['user']['permissao'] == 'COLABORADOR') { ?>
                    <small class="text">Atenção: como Colaborador, você só pode criar um PDI para si mesmo</small>
                <?php } ?>
            </div>

            <div class="col-sm">
                <label class="text">... e o gestor que orientará é...</label>
                <select name="orientador" id="orientador" value="<?php echo $pdi->getCpfGestor(); ?>" class="all-input" required>
                    <?php if($_SESSION['user']['permissao'] == 'GESTOR-2') { ?>
                        <option value="Nenhum" value="" selected>Nenhum</option>
                        <option value="<?php echo $_SESSION['user']['cpf']; ?>" selected>Eu mesmo(a) - <?php echo $_SESSION['user']['nome_completo']; ?></option>
                    <?php } else { ?>
                        <option value="Nenhum" selected>Nenhum</option>
                        <?php
                        $gestor->popularSelect($_SESSION['empresa']['database']);
                    } ?>
                </select>
                <small class="text">Deixando "nenhum" selecionado, o PDI será administrado apenas pelo funcionário 
                dono do mesmo.</small>
            </div>
            <?php } ?>
        </div>

    </div>

    <div class="row" style="margin-top: 1em;">
        <div class="col-sm">
            <small class="text">Após salvar este Plano de Desenvolvimento Individual, você poderá adicionar competências e metas para ele.</small>
        </div>   
    </div>

    <hr class="hr-divide-super-light">

    <div class="row">
        <div class="col-sm-2 offset-sm-4">
            <?php if(isset($_GET['editar'])) { ?> <input type="hidden" name="pdi_id" id="pdi_id" value="<?php echo $pdi->getID(); ?>"> <?php } ?>
            <input type="button" value="<?php echo $button; ?>" id="btnSalvar" class="button button1" onclick="criarPDI('<?php echo $action; ?>');">
        </div>
        <div class="col-sm-2">
            <input type="reset" value="Limpar" id="btnLimpar" class="button button1">
        </div>
    </div>

    <div id="exemplo" style="display: none;">

        <hr class="hr-divide-super-light">

        <div class="row">
            <div class="col-sm">
                <h3 class="high-text">Título do PDI: Ser um escritor de artigos científicos</h3>
            </div>
        </div>
        <div class="row">
            <div class="col-sm">
                <h4 class="high-text">Competências a desenvolver (2)</h4>
            </div>
        </div>
        <div class="row">
            <div class="col-sm">
                <ul>
                    <li>Conhecimento científico</li>
                        <ul>
                            Metas a cumprir para desenvolver a competência
                            <li>Buscar <i>websites</i>, revistas, livros e outros artigos da área de Tecnologia da Informação que 
                            possuam conteúdo cientíco de referência</li>
                            <li>Definir quais temas são mais relevantes para abordar em artigos científicos da área</li>
                        </ul>
                    <li>Escrita</li>
                        <ul>
                            Metas a cumprir para desenvolver a competência
                            <li>Escrever, ler e revisar pequenos trechos de artigos de acordo com a Língua Portuguesa formal</li>
                            <li>Ler diversos artigos científicos de diversas áreas para levantar referências de técnicas de escrita</li>
                            <li>Estudar as regras e convenções de escrita de artigos científicos</li>
                        </ul>
                </ul>
            </div>
        </div>
    </div>
</div>
</body>



</html>