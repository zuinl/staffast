<?php

    require_once '../../include/auth.php';
    require_once '../../src/meta.php';
    require_once '../../classes/class_conexao_empresa.php';
    require_once '../../classes/class_queryHelper.php';
    require_once '../../classes/class_horario.php';
    require_once '../../classes/class_ponto.php';

    $conexao_e = new ConexaoEmpresa($_SESSION['empresa']['database']);
    $conn = $conexao_e->conecta();
    $helper = new QueryHelper($conn);

    $cpf = $_GET['cpf'];

    $select = "SELECT 
                CASE 
                 WHEN t1.col_nome_completo IS NOT NULL THEN t1.col_nome_completo
                 ELSE t2.ges_nome_completo
                END as nome
               FROM tbl_colaborador t1
               LEFT JOIN tbl_gestor t2
                ON t2.ges_cpf = '$cpf'
               WHERE t1.col_cpf = '$cpf'";
    $fetch = $helper->select($select, 2);
    $nome = $fetch['nome'];

    $horario = new Horario();
    $horario->setCpf($cpf);
    $horario = $horario->retornarHorario($_SESSION['empresa']['database']);

    $tolerancia = $horario->getTolerancia() != "" ? $horario->getTolerancia() : $_SESSION['empresa']['tolerancia'];

?>
    <div class="row">
        <div class="col-sm" style="text-align: center;">
            <h5 class="text">Horários de <?php echo $nome; ?></h5>
        </div>
    </div>

    <hr class="hr-divide-super-light">

    <div class="row">
        <div class="col-sm" style="margin-top: 2em;">
            <p class="text"><b>Segunda-feira</b></p>
            <!-- <input type="checkbox" id="clonar" onclick="clonar()" value="0"> <span style="font-size: 0.7em">Aplicar até sexta-feira</span> -->
        </div>
        <div class="col-sm">
            <label class="text">Entrada</label>
            <input type="time" name="entrada_monday" id="entrada_monday" class="all-input" value="<?php echo $horario->getEntradaMonday(); ?>">
        </div>
        <div class="col-sm">
            <label class="text">Pausa (almoço/lanche)</label>
            <input type="time" name="pausa_monday" id="pausa_monday" class="all-input" value="<?php echo $horario->getPausaMonday(); ?>">
        </div>
        <div class="col-sm">
            <label class="text">Retorno pausa</label>
            <input type="time" name="retorno_monday" id="retorno_monday" class="all-input" value="<?php echo $horario->getRetornoMonday(); ?>">
        </div>
        <div class="col-sm">
            <label class="text">Saída</label>
            <input type="time" name="saida_monday" id="saida_monday" class="all-input" value="<?php echo $horario->getSaidaMonday(); ?>">
        </div>
    </div>

    <div class="row" style="margin-top: 1em;">
        <div class="col-sm" style="margin-top: 2em;">
            <p class="text"><b>Terça-feira</b></p>
        </div>
        <div class="col-sm">
            <label class="text">Entrada</label>
            <input type="time" name="entrada_tuesday" id="entrada_tuesday" class="all-input" value="<?php echo $horario->getEntradaTuesday(); ?>">
        </div>
        <div class="col-sm">
            <label class="text">Pausa (almoço/lanche)</label>
            <input type="time" name="pausa_tuesday" id="pausa_tuesday" class="all-input" value="<?php echo $horario->getPausaTuesday(); ?>">
        </div>
        <div class="col-sm">
            <label class="text">Retorno pausa</label>
            <input type="time" name="retorno_tuesday" id="retorno_tuesday" class="all-input" value="<?php echo $horario->getRetornoTuesday(); ?>">
        </div>
        <div class="col-sm">
            <label class="text">Saída</label>
            <input type="time" name="saida_tuesday" id="saida_tuesday" class="all-input" value="<?php echo $horario->getSaidaTuesday(); ?>">
        </div>
    </div>

    <div class="row" style="margin-top: 1em;">
        <div class="col-sm" style="margin-top: 2em;">
            <p class="text"><b>Quarta-feira</b></p>
        </div>
        <div class="col-sm">
            <label class="text">Entrada</label>
            <input type="time" name="entrada_wednesday" id="entrada_wednesday" class="all-input" value="<?php echo $horario->getEntradaWednesday(); ?>">
        </div>
        <div class="col-sm">
            <label class="text">Pausa (almoço/lanche)</label>
            <input type="time" name="pausa_wednesday" id="pausa_wednesday" class="all-input" value="<?php echo $horario->getPausaWednesday(); ?>">
        </div>
        <div class="col-sm">
            <label class="text">Retorno pausa</label>
            <input type="time" name="retorno_wednesday" id="retorno_wednesday" class="all-input" value="<?php echo $horario->getRetornoWednesday(); ?>">
        </div>
        <div class="col-sm">
            <label class="text">Saída</label>
            <input type="time" name="saida_wednesday" id="saida_wednesday" class="all-input" value="<?php echo $horario->getSaidaWednesday(); ?>">
        </div>
    </div>

    <div class="row" style="margin-top: 1em;">
        <div class="col-sm" style="margin-top: 2em;">
            <p class="text"><b>Quinta-feira</b></p>
        </div>
        <div class="col-sm">
            <label class="text">Entrada</label>
            <input type="time" name="entrada_thursday" id="entrada_thursday" class="all-input" value="<?php echo $horario->getEntradaThursday(); ?>">
        </div>
        <div class="col-sm">
            <label class="text">Pausa (almoço/lanche)</label>
            <input type="time" name="pausa_thursday" id="pausa_thursday" class="all-input" value="<?php echo $horario->getPausaThursday(); ?>">
        </div>
        <div class="col-sm">
            <label class="text">Retorno pausa</label>
            <input type="time" name="retorno_thursday" id="retorno_thursday" class="all-input" value="<?php echo $horario->getRetornoThursday(); ?>">
        </div>
        <div class="col-sm">
            <label class="text">Saída</label>
            <input type="time" name="saida_thursday" id="saida_thursday" class="all-input" value="<?php echo $horario->getSaidaThursday(); ?>">
        </div>
    </div>

    <div class="row" style="margin-top: 1em;">
        <div class="col-sm" style="margin-top: 2em;">
            <p class="text"><b>Sexta-feira</b></p>
        </div>
        <div class="col-sm">
            <label class="text">Entrada</label>
            <input type="time" name="entrada_friday" id="entrada_friday" class="all-input" value="<?php echo $horario->getEntradaFriday(); ?>">
        </div>
        <div class="col-sm">
            <label class="text">Pausa (almoço/lanche)</label>
            <input type="time" name="pausa_friday" id="pausa_friday" class="all-input" value="<?php echo $horario->getPausaFriday(); ?>">
        </div>
        <div class="col-sm">
            <label class="text">Retorno pausa</label>
            <input type="time" name="retorno_friday" id="retorno_friday" class="all-input" value="<?php echo $horario->getRetornoFriday(); ?>">
        </div>
        <div class="col-sm">
            <label class="text">Saída</label>
            <input type="time" name="saida_friday" id="saida_friday" class="all-input" value="<?php echo $horario->getSaidaFriday(); ?>">
        </div>
    </div>

    <div class="row" style="margin-top: 1em;">
        <div class="col-sm" style="margin-top: 2em;">
            <p class="text"><b>Sábado</b></p>
        </div>
        <div class="col-sm">
            <label class="text">Entrada</label>
            <input type="time" name="entrada_saturday" id="entrada_saturday" class="all-input" value="<?php echo $horario->getEntradaSaturday(); ?>">
        </div>
        <div class="col-sm">
            <label class="text">Pausa (almoço/lanche)</label>
            <input type="time" name="pausa_saturday" id="pausa_saturday" class="all-input" value="<?php echo $horario->getPausaSaturday(); ?>">
        </div>
        <div class="col-sm">
            <label class="text">Retorno pausa</label>
            <input type="time" name="retorno_saturday" id="retorno_saturday" class="all-input" value="<?php echo $horario->getRetornoSaturday(); ?>">
        </div>
        <div class="col-sm">
            <label class="text">Saída</label>
            <input type="time" name="saida_saturday" id="saida_saturday" class="all-input" value="<?php echo $horario->getSaidaSaturday(); ?>">
        </div>
    </div>

    <div class="row" style="margin-top: 1em;">
        <div class="col-sm" style="margin-top: 2em;">
            <p class="text"><b>Domingo</b></p>
        </div>
        <div class="col-sm">
            <label class="text">Entrada</label>
            <input type="time" name="entrada_sunday" id="entrada_sunday" class="all-input" value="<?php echo $horario->getEntradaSunday(); ?>">
        </div>
        <div class="col-sm">
            <label class="text">Pausa (almoço/lanche)</label>
            <input type="time" name="pausa_sunday" id="pausa_sunday" class="all-input" value="<?php echo $horario->getPausaSunday(); ?>">
        </div>
        <div class="col-sm">
            <label class="text">Retorno pausa</label>
            <input type="time" name="retorno_sunday" id="retorno_sunday" class="all-input" value="<?php echo $horario->getRetornoSunday(); ?>">
        </div>
        <div class="col-sm">
            <label class="text">Saída</label>
            <input type="time" name="saida_sunday" id="saida_sunday" class="all-input" value="<?php echo $horario->getSaidaSunday(); ?>">
        </div>
    </div>

    <div class="row" style="margin-top: 1.5em; text-align: center;">
        <div class="col-sm" style="margin-top: 2em;">
            <input type="checkbox" value="1" name="ponto_site" id="ponto_site" <?php if($horario->getPontoSite() == 1) echo 'checked'; ?>> Ponto no site <img src="img/help.png" width="18" onclick="alert('Com este parâmetro marcado, o funcionário será autorizado a registrar seu ponto usando o site do Staffast. \n Funcionários sem este parâmetro marcado poderão utilizar apenas o aplicativo para registrarem seus pontos. \n ATENÇÃO: o site do Staffast ainda não oferece a localização do funcionário, portanto recomendamos que, se a localização for importante para a empresa, utilizem apenas o aplicativo.');">
        </div>

        <div class="col-sm" style="margin-top: 2em;">
            <input type="checkbox" value="1" name="noturno" id="noturno" <?php if($horario->getNoturno() == 1) echo 'checked'; ?>> Horário noturno <img src="img/help.png" width="18" onclick="alert('Com este parâmetro marcado, o sistema não bloqueará divergências de pontos entre os dias. \nPor exemplo, ele permite que o funcionário dê entrada no dia e saia no outro');">
        </div>

        <div class="col-sm" style="margin-top: 2em;">
            <input type="checkbox" value="1" name="horario_flexivel" id="horario_flexivel" <?php if($horario->getHorarioFlexivel() == 1) echo 'checked'; ?>> Horário flexível <img src="img/help.png" width="18" onclick="alert('Com este parâmetro marcado, o sistema não contabilizará atrasos de nenhum tipo.');">
        </div>

        <div class="col-sm" style="margin-top: 2em;">
            <input type="checkbox" value="1" name="hora_extra" id="hora_extra" <?php if($horario->getHoraExtra() == 1) echo 'checked'; ?>> Hora extra <img src="img/help.png" width="18" onclick="alert('Com este parâmetro marcado, o sistema exibirá o tempo passado após o horário de saída como hora extra. Se não marcá-lo, o sistema ignorará esse tempo extra.');">
        </div>

        <div class="col-sm" style="margin-top: 2em;">
            <input type="checkbox" value="1" name="pausa_flexivel" id="pausa_flexivel" <?php if($horario->getPausaFlexivel() == 1) echo 'checked'; ?>> Pausa flexível <img src="img/help.png" width="18" onclick="alert('Com este parâmetro marcado, o sistema não considerará como atraso quando o funcionário sair para a pausa/almoço/lanche após o horário definido.');">
        </div>

        <div class="col-sm">
            <label class="text">Tolerância de atraso</label> <span><img src="img/help.png" width="18" onclick="alert('Por padrão, o tempo de tolerância geral informado pela empresa será usado. Você pode definir um específico para o funcionário');"></span>
            <input type="number" class="all-input" name="tolerancia" id="tolerancia" value="<?php echo $tolerancia; ?>">
            <small class="text">tempo sempre em minutos</small>
        </div>
    </div>

    <hr class="hr-divide-super-light">

    <div class="row" style="margin-top: 1.5em; text-align: center;">
        <div class="col-sm">
            <input type="button" class="button button2" value="Salvar" onclick="atualizarHorario();">
        </div>
    </div>
    

