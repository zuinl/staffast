<?php
    session_start();
    include('include/connect.php');
    include('src/meta.php');
?>
<!DOCTYPE html>
<html>
<head>
    <title>Cadastre sua empresa</title>
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
	<script src="https://igorescobar.github.io/jQuery-Mask-Plugin/js/jquery.mask.min.js"></script>  
    <script type="text/javascript">
        $('#cpf').mask('000.000.000-00');
        $('#telefone').mask('(00) 00000-0000');
    </script>
    <script>
        function escreveEmpresa (razaoSocial) {
            document.getElementById('empresa').innerHTML = razaoSocial;
            document.getElementById('empresa1').innerHTML = razaoSocial;
            document.getElementById('empresa2').innerHTML = razaoSocial;
        }
    </script>
</head>
<body style="margin-top: 0em;">
<div class="container-fluid">
    <?php
    if(isset($_SESSION['msg'])) {
        ?>
        <div class="alert alert-info alert-dismissible fade show" role="alert">
            <?php echo $_SESSION['msg']; unset($_SESSION['msg']); ?>
        </div>
        <?php
    }
    ?>
    <div class="row">
        <div class="col-sm-10 offset-sm-2">
            <h1 class="high-text big-text">Traga sua <i><span class="destaque-text">empresa</span></i> pro Staffast</h1>
            <h5 class="text">Você vai poder usar 15 dias de Staffast e depois poderá aderir ao plano Premium</h5>
        </div>
    </div>

    <hr class="hr-divide">

    <div class="row">
        <div class="col-sm-2">
        <form method="POST" action="database/empresa.php?novaEmpresa=true" id="form">
            <label for="razaoSocial" class="text">Razão social *</label>
            <input type="text" name="razaoSocial" id="razaoSocial" class="all-input" maxlength="50" required="" onblur="escreveEmpresa(this.value);">
        </div>
        <div class="col-sm-2">
            <label for="telefone" class="text">Telefone</label>
            <input type="text" name="telefone" id="telefone" class="all-input" maxlength="15">
        </div>
        <div class="col-sm-3">
            <label for="linkedin" class="text">LinkedIn</label>
            <input type="text" name="linkedin" id="linkedin" class="all-input" maxlength="120" placeholder="Link para o perfil">
        </div>
        <!-- <div class="col-sm-3">
            <label for="linkedin" class="text">Envie o logotipo (opcional)</label>
            <input type="file" name="logotipo" id="logotipo" class="button button3">
            <small class="text">Se você não enviar um logotipo, o nome "STAFFAST" será exbidio nos relatórios</small>
        </div> -->
    </div>

    <hr class="hr-divide-light">

    <div class="row">
        <div class="col-sm-8 offset-sm-4">
            <h1 class="high-text">Olá, <i><span id="empresa1" class="destaque-text">nova empresa</span></i></h1>
        </div>
        <div class="col-sm-12">
            <h2 class="high-text">Agora, defina as <i><span class="destaque-text">competências</span></i> da sua empresa</h2>
            <h4 class="text">Apenas 4 são obrigatórias</h4>
        </div>
        <div class="col-sm-12">
            <h6 class="text">Nas avaliações e autoavaliações, o Staffast usa de 4 a 20 competências que são definidas neste momento. 
            Tudo o que você deve pensar é: o que <b><span id="empresa">sua empresa</span></b> quer avaliar de seus colaboradores?</h6>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-3">
            <input type="text" class="all-input" name="competencia1" id="competencia1" maxlength="50" required="" placeholder="Competência 1. Ex: Comunicação em equipe">
        </div>
        <div class="col-sm-3">
            <input type="text" class="all-input" name="competencia2" id="competencia2" maxlength="50" required="" placeholder="Competência 2. Ex: Tolerância">
        </div>
        <div class="col-sm-3">
            <input type="text" class="all-input" name="competencia3" id="competencia3" maxlength="50" required="" placeholder="Competência 3. Ex: Conhecimento técnico">
        </div>
        <div class="col-sm-3">
            <input type="text" class="all-input" name="competencia4" id="competencia4" maxlength="50" required="" placeholder="Competência 4. Ex: Responsabilidade">
        </div>
    </div>
    <div class="row" style="margin-top: 1em; margin-left: 3em;">
        <div class="col-sm-2">
            <input type="text" class="all-input" name="competencia5" id="competencia5" maxlength="50" placeholder="Competência 5">
        </div>
        <div class="col-sm-2">
            <input type="text" class="all-input" name="competencia6" id="competencia6" maxlength="50" placeholder="Competência 6">
        </div>
        <div class="col-sm-2">
            <input type="text" class="all-input" name="competencia7" id="competencia7" maxlength="50" placeholder="Competência 7">
        </div>
        <div class="col-sm-2">
            <input type="text" class="all-input" name="competencia8" id="competencia8" maxlength="50" placeholder="Competência 8">
        </div>
    </div>
    <div class="row" style="margin-top: 1em; margin-left: 3em;">
        <div class="col-sm-2">
            <input type="text" class="all-input" name="competencia9" id="competencia9" maxlength="50" placeholder="Competência 9">
        </div>
        <div class="col-sm-2">
            <input type="text" class="all-input" name="competencia10" id="competencia10" maxlength="50" placeholder="Competência 10">
        </div>
        <div class="col-sm-2">
            <input type="text" class="all-input" name="competencia11" id="competencia11" maxlength="50" placeholder="Competência 11">
        </div>
        <div class="col-sm-2">
            <input type="text" class="all-input" name="competencia12" id="competencia12" maxlength="50" placeholder="Competência 12">
        </div>
    </div>
    <div class="row" style="margin-top: 1em; margin-left: 3em;">
        <div class="col-sm-2">
            <input type="text" class="all-input" name="competencia13" id="competencia13" maxlength="50" placeholder="Competência 13">
        </div>
        <div class="col-sm-2">
            <input type="text" class="all-input" name="competencia14" id="competencia14" maxlength="50" placeholder="Competência 14">
        </div>
        <div class="col-sm-2">
            <input type="text" class="all-input" name="competencia15" id="competencia15" maxlength="50" placeholder="Competência 15">
        </div>
        <div class="col-sm-2">
            <input type="text" class="all-input" name="competencia16" id="competencia16" maxlength="50" placeholder="Competência 16">
        </div>
    </div>
    <div class="row" style="margin-top: 1em; margin-left: 3em;">
        <div class="col-sm-2">
            <input type="text" class="all-input" name="competencia17" id="competencia17" maxlength="50" placeholder="Competência 17">
        </div>
        <div class="col-sm-2">
            <input type="text" class="all-input" name="competencia18" id="competencia18" maxlength="50" placeholder="Competência 18">
        </div>
        <div class="col-sm-2">
            <input type="text" class="all-input" name="competencia19" id="competencia19" maxlength="50" placeholder="Competência 19">
        </div>
        <div class="col-sm-2">
            <input type="text" class="all-input" name="competencia20" id="competencia20" maxlength="50" placeholder="Competência 20">
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <h6 class="text">Está em dúvidas sobre o que são competências? Fique tranquilo(a). O Staffast foi desenvolvido até mesmo 
            para quem não tem formação em RH. <a href="#">Leia nosso artigo</a> sobre as competências. Lá também tem sugestões para cada tipo de empresa!</h6>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12">
            <h2 class="high-text">Agora, defina o que você quer os colaboradores avaliem da <i><span class="destaque-text" id="empresa2">empresa</span></i></h2>
            <h4 class="text">Apenas 4 são obrigatórias</h4>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-3">
            <input type="text" class="all-input" name="avg1" id="avg1" maxlength="30" required="" placeholder="Ex: Plano de carreira">
        </div>
        <div class="col-sm-3">
            <input type="text" class="all-input" name="avg2" id="avg2" maxlength="30" required="" placeholder="Ex: Ambiente de trabalho">
        </div>
        <div class="col-sm-3">
            <input type="text" class="all-input" name="avg3" id="avg3" maxlength="30" required="" placeholder="Ex: Liderança da gestão">
        </div>
        <div class="col-sm-3">
            <input type="text" class="all-input" name="avg4" id="avg4" maxlength="30" required="" placeholder="Ex: Transparência">
        </div>
    </div>
    <div class="row" style="margin-top: 1em; margin-left: 3em;">
        <div class="col-sm-2">
            <input type="text" class="all-input" name="avg5" id="avg5" maxlength="30" placeholder="Ex: Pontualidade">
        </div>
        <div class="col-sm-2">
            <input type="text" class="all-input" name="avg6" id="avg6" maxlength="30" placeholder="Ex: Incentivo">
        </div>
        <div class="col-sm-2">
            <input type="text" class="all-input" name="avg7" id="avg7" maxlength="30" placeholder="Ex: Responsabilidade">
        </div>
        <div class="col-sm-2">
            <input type="text" class="all-input" name="avg8" id="avg8" maxlength="30" placeholder="Ex: Oportunidades">
        </div>
        <div class="col-sm-2">
            <input type="text" class="all-input" name="avg9" id="avg9" maxlength="30" placeholder="Ex: Comforto">
        </div>
        <div class="col-sm-2">
            <input type="text" class="all-input" name="avg10" id="avg10" maxlength="30" placeholder="Ex: Segurança">
        </div>
    </div>

    <hr class="hr-divide-light">

    <div class="row">
        <div class="col-sm-2 offset-sm-4">
            <input type="submit" value="Cadastrar" class="button button2" onclick="">
        </div>
        <div class="col-sm-2">
            <input type="reset" value="Limpar" class="button button1" onclick="">
        </div>
    </div>
    </form>
</div>
</body>
</html>