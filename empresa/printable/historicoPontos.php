<?php
    include('../../include/auth.php');
    include('../../src/meta.php');
    require_once('../../classes/class_conexao_empresa.php');
    require_once('../../classes/class_queryHelper.php');
    require_once('../../classes/class_ponto.php');
    require_once('../../classes/class_gestor.php');
    require_once('../../classes/class_colaborador.php');

    $conexao = new ConexaoEmpresa($_SESSION['empresa']['database']);
    $conn = $conexao->conecta();
    $helper = new QueryHelper($conn);
    
    $cpf = base64_decode($_GET['cpf']);
    $ano = $_GET['ano'];
    $mes = $_GET['mes'];

    $ponto = new Ponto();
    $historico = $ponto->retornarHistorico($_SESSION['empresa']['database'], $_SESSION['empresa']['fechamento'], $cpf, $mes, $ano);
   
    $colaborador = new Colaborador();
    $gestor = new Gestor();

    $colaborador->setCpf($cpf);
    $col = $colaborador->retornarColaborador($_SESSION['empresa']['database']);

    if($col->getNomeCompleto() != "") {
        $nome = $col->getNomeCompleto();
    } else {
        $gestor->setCpf($cpf);
        $ges = $gestor->retornarGestor($_SESSION['empresa']['database']);
        $nome = $ges->getNomeCompleto();
    }

    //Coletando horários atuais
    $select = "SELECT * FROM tbl_funcionario_horario WHERE cpf = '$cpf' AND dt_final IS NULL";
    $f_horario_atual = $helper->select($select, 2);

    $hoje = date('w', strtotime(date('Y-m-d')));
    switch($hoje) {
        case 0: //Sunday
            $stringSemana = 'Domingo';
            $entradaHoje = $f_horario_atual['entrada_sunday'] != "" && $f_horario_atual['entrada_sunday'] != "00:00" ? $f_horario_atual['entrada_sunday'] : "Sem jornada definida";
            $pausaHoje = $f_horario_atual['pausa_sunday'] != "" && $f_horario_atual['pausa_sunday'] != "00:00" ? $f_horario_atual['pausa_sunday'] : "Sem jornada definida";
            $retornoHoje = $f_horario_atual['retorno_sunday'] != "" && $f_horario_atual['retorno_sunday'] != "00:00" ? $f_horario_atual['retorno_sunday'] : "Sem jornada definida";
            $saidaHoje = $f_horario_atual['saida_sunday'] != "" && $f_horario_atual['saida_sunday'] != "00:00" ? $f_horario_atual['saida_sunday'] : "Sem jornada definida";
        break;
        case 1: //Monday
            $stringSemana = 'Segunda-feira';
            $entradaHoje = $f_horario_atual['entrada_monday'] != "" && $f_horario_atual['entrada_monday'] != "00:00" ? $f_horario_atual['entrada_monday'] : "Sem jornada definida";
            $pausaHoje = $f_horario_atual['pausa_monday'] != "" && $f_horario_atual['pausa_monday'] != "00:00" ? $f_horario_atual['pausa_monday'] : "Sem jornada definida";
            $retornoHoje = $f_horario_atual['retorno_monday'] != "" && $f_horario_atual['retorno_monday'] != "00:00" ? $f_horario_atual['retorno_monday'] : "Sem jornada definida";
            $saidaHoje = $f_horario_atual['saida_monday'] != "" && $f_horario_atual['saida_monday'] != "00:00" ? $f_horario_atual['saida_monday'] : "Sem jornada definida";
        break;
        case 2: //Tuesday
            $stringSemana = 'Terça-feira';
            $entradaHoje = $f_horario_atual['entrada_tuesday'] != "" && $f_horario_atual['entrada_tuesday'] != "00:00" ? $f_horario_atual['entrada_tuesday'] : "Sem jornada definida";
            $pausaHoje = $f_horario_atual['pausa_tuesday'] != "" && $f_horario_atual['pausa_tuesday'] != "00:00" ? $f_horario_atual['pausa_tuesday'] : "Sem jornada definida";
            $retornoHoje = $f_horario_atual['retorno_tuesday'] != "" && $f_horario_atual['retorno_tuesday'] != "00:00" ? $f_horario_atual['retorno_tuesday'] : "Sem jornada definida";
            $saidaHoje = $f_horario_atual['saida_tuesday'] != "" && $f_horario_atual['saida_tuesday'] != "00:00" ? $f_horario_atual['saida_tuesday'] : "Sem jornada definida";
        break;
        case 3: //Wednesday
            $stringSemana = 'Quarta-feira';
            $entradaHoje = $f_horario_atual['entrada_wednesday'] != "" && $f_horario_atual['entrada_wednesday'] != "00:00" ? $f_horario_atual['entrada_wednesday'] : "Sem jornada definida";
            $pausaHoje = $f_horario_atual['pausa_wednesday'] != "" && $f_horario_atual['pausa_wednesday'] != "00:00" ? $f_horario_atual['pausa_wednesday'] : "Sem jornada definida";
            $retornoHoje = $f_horario_atual['retorno_wednesday'] != "" && $f_horario_atual['retorno_wednesday'] != "00:00" ? $f_horario_atual['retorno_wednesday'] : "Sem jornada definida";
            $saidaHoje = $f_horario_atual['saida_wednesday'] != "" && $f_horario_atual['saida_wednesday'] != "00:00" ? $f_horario_atual['saida_wednesday'] : "Sem jornada definida";
        break;
        case 4: //Thursday
            $stringSemana = 'Quinta-feira';
            $entradaHoje = $f_horario_atual['entrada_thursday'] != "" && $f_horario_atual['entrada_thursday'] != "00:00" ? $f_horario_atual['entrada_thursday'] : "Sem jornada definida";
            $pausaHoje = $f_horario_atual['pausa_thursday'] != "" && $f_horario_atual['pausa_thursday'] != "00:00" ? $f_horario_atual['pausa_thursday'] : "Sem jornada definida";
            $retornoHoje = $f_horario_atual['retorno_thursday'] != "" && $f_horario_atual['retorno_thursday'] != "00:00" ? $f_horario_atual['retorno_thursday'] : "Sem jornada definida";
            $saidaHoje = $f_horario_atual['saida_thursday'] != "" && $f_horario_atual['saida_thursday'] != "00:00" ? $f_horario_atual['saida_thursday'] : "Sem jornada definida";
        break;
        case 5: //Friday
            $stringSemana = 'Sexta-feira';
            $entradaHoje = $f_horario_atual['entrada_friday'] != "" && $f_horario_atual['entrada_friday'] != "00:00" ? $f_horario_atual['entrada_friday'] : "Sem jornada definida";
            $pausaHoje = $f_horario_atual['pausa_friday'] != "" && $f_horario_atual['pausa_friday'] != "00:00" ? $f_horario_atual['pausa_friday'] : "Sem jornada definida";
            $retornoHoje = $f_horario_atual['retorno_friday'] != "" && $f_horario_atual['retorno_friday'] != "00:00" ? $f_horario_atual['retorno_friday'] : "Sem jornada definida";
            $saidaHoje = $f_horario_atual['saida_friday'] != "" && $f_horario_atual['saida_friday'] != "00:00" ? $f_horario_atual['saida_friday'] : "Sem jornada definida";
        break;
        case 6: //Saturday
            $stringSemana = 'Sábado';
            $entradaHoje = $f_horario_atual['entrada_saturday'] != "" && $f_horario_atual['entrada_saturday'] != "00:00" ? $f_horario_atual['entrada_saturday'] : "Sem jornada definida";
            $pausaHoje = $f_horario_atual['pausa_saturday'] != "" && $f_horario_atual['pausa_saturday'] != "00:00" ? $f_horario_atual['pausa_saturday'] : "Sem jornada definida";
            $retornoHoje = $f_horario_atual['retorno_saturday'] != "" && $f_horario_atual['retorno_saturday'] != "00:00" ? $f_horario_atual['retorno_saturday'] : "Sem jornada definida";
            $saidaHoje = $f_horario_atual['saida_saturday'] != "" && $f_horario_atual['saida_saturday'] != "00:00" ? $f_horario_atual['saida_saturday'] : "Sem jornada definida";
        break;
    }
?>
<!DOCTYPE html>
<html>
<head>
    <title>Seus horários</title>
</head>
<body style="margin-top: 0em;">
<div class="container">

    <div class="row">
        <div class="col-sm-2">
            <img src="../../img/logo_staffast.png" width="110">
        </div>
        <?php if($_SESSION['empresa']['logotipo'] != '') { ?>
        <div class="col-sm-2 offset-sm-8">
            <img src="../img/logos/<?php echo $_SESSION['empresa']['logotipo'] ?>" width="110">
        </div>  
        <?php } ?>
    </div>

    <div class="row" style="text-align: center;">
        <div class="col-sm">
            <h2 class="high-text">Histórico de Pontos</h2>
        </div>
    </div>

    <hr class="hr-divide-super-light">

    <div class="row" style="text-align: center;">
        <div class="col-sm">
            <h5 class="text">Pontos de <?php echo $nome; ?></h5>
        </div>
    </div>
    
    <table class="table-site" style="font-size: 0.8em;">
        <tr>
            <th>Data</th>
            <th>Entrada</th>
            <th>Atraso entrada</th>
            <th>Pausa</th>
            <th>Atraso pausa</th>
            <th>Retorno</th>
            <th>Saída</th>
            <th>Extra</th>
            <th>Anotações</th>
        </tr>
        
        <?php for ($i = 0; $i < sizeof($historico); $i++) {
            $dataFormat = substr($historico[$i]["data"], 6, 4).'-'.substr($historico[$i]["data"], 3, 2).'-'.substr($historico[$i]["data"], 0, 2); 
            $dataInicial = $dataFormat.' 00:00:00';
            $dataFinal = $dataFormat.' 23:59:59';

            $select = "SELECT * FROM tbl_funcionario_horario WHERE cpf = '$cpf' 
            AND dt_inicial > '$dataInicial' AND ('$dataFinal' <= dt_final OR dt_final IS NULL) ORDER BY id ASC";
            
            $query = $helper->select($select, 1);

            if(mysqli_num_rows($query) == 0) $encontrou = false;
            else $encontrou = true;

            $f = mysqli_fetch_assoc($query);

            $diaSemana = date('w', strtotime($dataFormat));
            switch($diaSemana) {
                case 0: //Sunday
                    $horarioCorretoEntrada = $f['entrada_sunday'];
                    $horarioCorretoPausa = $f['pausa_sunday'];
                    //$horarioCorretoRetorno = $f['retorno_sunday'];
                    $horarioCorretoSaida = $f['saida_sunday'];
                break;
                case 1: //Monday
                    $horarioCorretoEntrada = $f['entrada_monday'];
                    $horarioCorretoPausa = $f['pausa_monday'];
                    //$horarioCorretoRetorno = $f['retorno_monday'];
                    $horarioCorretoSaida = $f['saida_monday'];
                break;
                case 2: //Tuesday
                    $horarioCorretoEntrada = $f['entrada_tuesday'];
                    $horarioCorretoPausa = $f['pausa_tuesday'];
                    //$horarioCorretoRetorno = $f['retorno_tuesday'];
                    $horarioCorretoSaida = $f['saida_tuesday'];
                break;
                case 3: //Wednesday
                    $horarioCorretoEntrada = $f['entrada_wednesday'];
                    $horarioCorretoPausa = $f['pausa_wednesday'];
                    //$horarioCorretoRetorno = $f['retorno_wednesday'];
                    $horarioCorretoSaida = $f['saida_wednesday'];
                break;
                case 4: //Thursday
                    $horarioCorretoEntrada = $f['entrada_thursday'];
                    $horarioCorretoPausa = $f['pausa_thursday'];
                    //$horarioCorretoRetorno = $f['retorno_thursday'];
                    $horarioCorretoSaida = $f['saida_thursday'];
                break;
                case 5: //Friday
                    $horarioCorretoEntrada = $f['entrada_friday'];
                    $horarioCorretoPausa = $f['pausa_friday'];
                    //$horarioCorretoRetorno = $f['retorno_friday'];
                    $horarioCorretoSaida = $f['saida_friday'];
                break;
                case 6: //Saturday
                    $horarioCorretoEntrada = $f['entrada_saturday'];
                    $horarioCorretoPausa = $f['pausa_saturday'];
                    //$horarioCorretoRetorno = $f['retorno_saturday'];
                    $horarioCorretoSaida = $f['saida_saturday'];
                break;
            }
            
            if(strlen($horarioCorretoEntrada) == 5) $horarioCorretoEntrada .= ':00';
            if(strlen($horarioCorretoPausa) == 5) $horarioCorretoPausa .= ':00';
            //if(strlen($horarioCorretoRetorno) == 5) $horarioCorretoRetorno .= ':00';
            if(strlen($horarioCorretoSaida) == 5) $horarioCorretoSaida .= ':00';

            $pausaFlexivel = (int)$f['pausa_flexivel'];
            $horarioFlexivel = (int)$f['horario_flexivel'];
            $horaExtra = (int)$f['hora_extra'];
            $tolerancia = (int)$f['tolerancia'];

            if($encontrou) $atrasoEntrada = $ponto->retornarAtraso($horarioCorretoEntrada, $historico[$i]["entrada"], $tolerancia);
            else $atrasoEntrada = 'Sem horário';

            if($pausaFlexivel === 0) {
                if($encontrou) $atrasoPausa = $ponto->retornarAtraso($horarioCorretoPausa, $historico[$i]["pausa"], $tolerancia);
                else $atrasoPausa = 'Sem horário';
            } else {
                $atrasoPausa = 'Flexível';
            }

            //Zerando atrasos caso o funcionário tenha horário flexível
            if($horarioFlexivel === 1) {
                $atrasoEntrada = 'Flexível';
                $atrasoPausa = 'Flexível';
            }
            
            if($encontrou) $extraSaida = $ponto->retornarExtra($horarioCorretoSaida, $historico[$i]["saida"], $tolerancia);
            else $extraSaida = 'Sem horário';

            //Zerando hora extra se o funcionário estiver com hora extra desabilitada
            if($horaExtra === 0) {
                $extraSaida = 'Não utiliza';
            }

            $anotacao = str_replace('<br>', '', $historico[$i]["anotacao"]) != "" ? $historico[$i]["anotacao"] : "Nenhuma";
        ?>
        <tr>
            <td><b><?php echo $historico[$i]["data"]; ?></b></td>
            <td><?php echo $historico[$i]["entrada"]; ?></td>
            <td><?php echo $atrasoEntrada; ?></td>
            <td><?php echo $historico[$i]["pausa"]; ?></td>
            <td><?php echo $atrasoPausa; ?></td>
            <td><?php echo $historico[$i]["retorno"]; ?></td>
            <td><?php echo $historico[$i]["saida"]; ?></td>
            <td><?php echo $extraSaida; ?></td>
            <td><?php echo $anotacao; ?></td>
        </tr>
        <?php } ?>

        <!-- <tr>
            <td><b>TOTAL</b></td>
            <td><b>XX horas</b></td>
            <td><b>XX de atraso</b></td>
            <td><b>XX extras</b></td>
            <td></td>
            <td></td>
        </tr> -->
        
    </table>

    <div class="row">
        <div class="col-sm">
            <small class="text">Os pontos são exibidos de acordo com a data de fechamento informada pela empresa ao Staffast.</small>
        </div>
    </div>
</div>
</body>
</html>

<script>
    window.print();
</script>