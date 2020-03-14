<?php
    include('../include/auth.php');
    include('../src/meta.php');
    require_once('../classes/class_conexao_empresa.php');
    require_once('../classes/class_queryHelper.php');
    require_once('../classes/class_ponto.php');
    require_once('../classes/class_gestor.php');
    require_once('../classes/class_colaborador.php');

    $conexao = new ConexaoEmpresa($_SESSION['empresa']['database']);
    $conn = $conexao->conecta();
    $helper = new QueryHelper($conn);
    
    $cpf = isset($_POST['funcionario']) ? ($_SESSION['user']['permissao'] == 'GESTOR-1' ? $_POST['funcionario'] : $_SESSION['user']['cpf']) : $_SESSION['user']['cpf'];
    $ano = isset($_POST['ano']) ? $_POST['ano'] : date('Y');
    $mes = isset($_POST['mes']) ? $_POST['mes'] : date('m');

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
<body>
<?php
    include('../include/navbar.php');
?>
<div class="container-fluid">

    <!-- NAV DE CAMINHO DE TELA -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="./">Início</a></li>
            <li class="breadcrumb-item active" aria-current="page">Histórico de Pontos</li>
        </ol>
    </nav>
    <!-- FIM DA NAV DE CAMINHO DE TELA -->

    <div class="row" style="text-align: center;">
        <div class="col-sm">
            <h2 class="high-text">Histórico de Pontos</h2>
        </div>
    </div>

    <div class="row" style="text-align: center;">
        <div class="col-sm">
            <input type="button" class="button button3" data-toggle="modal" data-target="#modal" value="Ver horários de <?php echo $nome; ?>">       
        </div>
    </div>

    <?php
    if(isset($_SESSION['msg'])) {
        ?>
		<div class="row">
            <div class="col-sm-6">
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

    <div class="row">
        <div class="col-sm">
            <form action="historicoPontos.php" method="POST">
            <label class="text">Selecione um mês</label>
            <select name="mes" id="mes" class="all-input" required>
                <option value="" selected disabled>Selecione</option>
                <option value="01" <?php if($mes == '01') echo 'selected'; ?>>Janeiro</option>
                <option value="02" <?php if($mes == '02') echo 'selected'; ?>>Fevereiro</option>
                <option value="03" <?php if($mes == '03') echo 'selected'; ?>>Março</option>
                <option value="04" <?php if($mes == '04') echo 'selected'; ?>>Abril</option>
                <option value="05" <?php if($mes == '05') echo 'selected'; ?>>Maio</option>
                <option value="06" <?php if($mes == '06') echo 'selected'; ?>>Junho</option>
                <option value="07" <?php if($mes == '07') echo 'selected'; ?>>Julho</option>
                <option value="08" <?php if($mes == '08') echo 'selected'; ?>>Agosto</option>
                <option value="09" <?php if($mes == '09') echo 'selected'; ?>>Setembro</option>
                <option value="10" <?php if($mes == '10') echo 'selected'; ?>>Outubro</option>
                <option value="11" <?php if($mes == '11') echo 'selected'; ?>>Novembro</option>
                <option value="12" <?php if($mes == '12') echo 'selected'; ?>>Dezembro</option>
            </select>
        </div>
        <div class="col-sm">
            <label class="text">Selecione um ano</label>
            <select name="ano" id="ano" class="all-input" required>
                <option value="" selected disabled>Selecione</option>
                <?php 
                $anoI = 2020;
                $anoF = date('Y');

                for($a = $anoI; $a <= $anoF; $a++) {
                    echo '<option value="'.$a.'"';
                    if($ano == $a) echo ' selected';
                    echo '>'.$a.'</option>';
                }
                ?>
            </select>
        </div>
        <div class="col-sm">
            <label class="text">Selecione um funcionário</label>
            <select name="funcionario" id="funcionario" class="all-input" required>
                <option value="" disabled selected>- Selecione -</option>
                <?php 
                if($_SESSION['user']['permissao'] == 'GESTOR-1') {
                    ?>
                    <!-- <option value="<?php //echo $_SESSION['user']['cpf']; ?>" selected><?php //echo $_SESSION['user']['nome_completo']; ?></option> -->
                    <option value="" disabled>- COLABORADORES</option>
                    <?php
                    $colaborador->popularSelect($_SESSION['empresa']['database']);
                    ?>
                    <option value="" disabled>- GESTORES</option>
                    <?php
                    $gestor->popularSelect($_SESSION['empresa']['database']);
                } else {
                    ?>
                    <option value="<?php echo $_SESSION['user']['cpf']; ?>" selected><?php echo $_SESSION['user']['nome_completo']; ?></option>
                    <?php
                }
                ?>
            </select>
        </div>
        <div class="col-sm" style="margin-top: 1.5em;">
            <input type="submit" class="button button1" value="Visualizar">
            </form>
        </div>
        <div class="col-sm" style="margin-top: 1.5em;">
            <a href="printable/historicoPontos.php?cpf=<?php echo base64_encode($cpf); ?>&ano=<?php echo $ano; ?>&mes=<?php echo $mes; ?>" target="_blank"><input type="button" class="button button3" value="Imprimir esta tabela"></a>
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
            <th>Endereço entrada</th>
            <th>Pausa</th>
            <th>Atraso pausa</th>
            <th>Endereço pausa</th>
            <th>Retorno</th>
            <th>Endereço retorno</th>
            <th>Saída</th>
            <th>Extra</th>
            <th>Endereço saída</th>
            <th>Anotações</th>
            <th>Editar</th>
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

            //Jornada do dia e mensal
            $jornadaDiaria = 0;
                //Calculando intervalo entre entrada e pausa
                if($historico[$i]["entrada"] != 'Sem registro' && $historico[$i]["pausa"] != 'Sem registro') {
                    //$jornadaDiaria += ($historico[$i]["pausa"] - $historico[$i]["entrada"]);
                }
        ?>
        <tr>
            <td><b><?php echo $historico[$i]["data"]; ?></b></td>
            <td><?php echo $historico[$i]["entrada"]; ?></td>
            <td><?php echo $atrasoEntrada; ?></td>
            <td><?php echo $historico[$i]["entrada_endereco"]; ?></td>
            <td><?php echo $historico[$i]["pausa"]; ?></td>
            <td><?php echo $atrasoPausa; ?></td>
            <td><?php echo $historico[$i]["pausa_endereco"]; ?></td>
            <td><?php echo $historico[$i]["retorno"]; ?></td>
            <td><?php echo $historico[$i]["retorno_endereco"]; ?></td>
            <td><?php echo $historico[$i]["saida"]; ?></td>
            <td><?php echo $extraSaida; ?></td>
            <td><?php echo $historico[$i]["saida_endereco"]; ?></td>
            <td><?php echo $anotacao; ?></td>
            <td>
                <?php if($historico[$i]["data"] != "Sem registros") { ?>
                <a href="editarPonto.php?data=<?php echo $dataFormat; ?>&funcionario=<?php echo base64_encode($cpf); ?>&nome=<?php echo base64_encode($nome); ?>">-></a>
                <?php } ?>
            </td>
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

<div class="modal" tabindex="-1" role="dialog" id="modal" data-target=".bd-example-modal-lg">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Seus horários</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">

        <div class="row">
            <div class="col-sm">
                <h5 class="text">Hoje - <?php echo $stringSemana; ?></h5>
            </div>
        </div>
        <hr class="hr-divide-super-light">
        <div class="row">
            <div class="col-sm">
                <p class="text">Entrada: <?php echo $entradaHoje; ?>
                <p class="text">Pausa: <?php echo $pausaHoje; ?>
                <p class="text">Retorno: <?php echo $retornoHoje; ?>
                <p class="text">Saída: <?php echo $saidaHoje; ?>
            </div>
        </div>

        <div class="row">
            <div class="col-sm">
                <h5 class="text">Horários completos</h5>
            </div>
        </div>
        <hr class="hr-divide-super-light">
        <div class="row">
            <div class="col-sm">
                <ul>
                    <li>
                        <b>Segunda-feira</b>
                        <ul>
                            <li>Entrada: <?= $f_horario_atual['entrada_monday'] != "" && $f_horario_atual['entrada_monday'] != "00:00" ? $f_horario_atual['entrada_monday'] : "Sem jornada definida"; ?></li>
                            <li>Pausa: <?= $f_horario_atual['pausa_monday'] != "" && $f_horario_atual['pausa_monday'] != "00:00" ? $f_horario_atual['pausa_monday'] : "Sem jornada definida"; ?></li>
                            <li>Retorno: <?= $f_horario_atual['retorno_monday'] != "" && $f_horario_atual['retorno_monday'] != "00:00" ? $f_horario_atual['retorno_monday'] : "Sem jornada definida"; ?></li>
                            <li>Saída: <?= $f_horario_atual['saida_monday'] != "" && $f_horario_atual['saida_monday'] != "00:00" ? $f_horario_atual['saida_monday'] : "Sem jornada definida"; ?></li>
                        </ul>
                    </li>
                    <li>
                        <b>Terça-feira</b>
                        <ul>
                            <li>Entrada: <?= $f_horario_atual['entrada_tuesday'] != "" && $f_horario_atual['entrada_tuesday'] != "00:00" ? $f_horario_atual['entrada_tuesday'] : "Sem jornada definida"; ?></li>
                            <li>Pausa: <?= $f_horario_atual['pausa_tuesday'] != "" && $f_horario_atual['pausa_tuesday'] != "00:00" ? $f_horario_atual['pausa_tuesday'] : "Sem jornada definida"; ?></li>
                            <li>Retorno: <?= $f_horario_atual['retorno_tuesday'] != "" && $f_horario_atual['retorno_tuesday'] != "00:00" ? $f_horario_atual['retorno_tuesday'] : "Sem jornada definida"; ?></li>
                            <li>Saída: <?= $f_horario_atual['saida_tuesday'] != "" && $f_horario_atual['saida_tuesday'] != "00:00" ? $f_horario_atual['saida_tuesday'] : "Sem jornada definida"; ?></li>
                        </ul>
                    </li>
                    <li>
                        <b>Quarta-feira</b>
                        <ul>
                            <li>Entrada: <?= $f_horario_atual['entrada_wednesday'] != "" && $f_horario_atual['entrada_wednesday'] != "00:00" ? $f_horario_atual['entrada_wednesday'] : "Sem jornada definida"; ?></li>
                            <li>Pausa: <?= $f_horario_atual['pausa_wednesday'] != "" && $f_horario_atual['pausa_wednesday'] != "00:00" ? $f_horario_atual['pausa_wednesday'] : "Sem jornada definida"; ?></li>
                            <li>Retorno: <?= $f_horario_atual['retorno_wednesday'] != "" && $f_horario_atual['retorno_wednesday'] != "00:00" ? $f_horario_atual['retorno_wednesday'] : "Sem jornada definida"; ?></li>
                            <li>Saída: <?= $f_horario_atual['saida_wednesday'] != "" && $f_horario_atual['saida_wednesday'] != "00:00" ? $f_horario_atual['saida_wednesday'] : "Sem jornada definida"; ?></li>
                        </ul>
                    </li>
                    <li>
                        <b>Quinta-feira</b>
                        <ul>
                            <li>Entrada: <?= $f_horario_atual['entrada_thursday'] != "" && $f_horario_atual['entrada_thursday'] != "00:00" ? $f_horario_atual['entrada_thursday'] : "Sem jornada definida"; ?></li>
                            <li>Pausa: <?= $f_horario_atual['pausa_thursday'] != "" && $f_horario_atual['pausa_thursday'] != "00:00" ? $f_horario_atual['pausa_thursday'] : "Sem jornada definida"; ?></li>
                            <li>Retorno: <?= $f_horario_atual['retorno_thursday'] != "" && $f_horario_atual['retorno_thursday'] != "00:00" ? $f_horario_atual['retorno_thursday'] : "Sem jornada definida"; ?></li>
                            <li>Saída: <?= $f_horario_atual['saida_thursday'] != "" && $f_horario_atual['saida_thursday'] != "00:00" ? $f_horario_atual['saida_thursday'] : "Sem jornada definida"; ?></li>
                        </ul>
                    </li>
                    <li>
                        <b>Sexta-feira</b>
                        <ul>
                            <li>Entrada: <?= $f_horario_atual['entrada_friday'] != "" && $f_horario_atual['entrada_friday'] != "00:00" ? $f_horario_atual['entrada_friday'] : "Sem jornada definida"; ?></li>
                            <li>Pausa: <?= $f_horario_atual['pausa_friday'] != "" && $f_horario_atual['pausa_friday'] != "00:00" ? $f_horario_atual['pausa_friday'] : "Sem jornada definida"; ?></li>
                            <li>Retorno: <?= $f_horario_atual['retorno_friday'] != "" && $f_horario_atual['retorno_friday'] != "00:00" ? $f_horario_atual['retorno_friday'] : "Sem jornada definida"; ?></li>
                            <li>Saída: <?= $f_horario_atual['saida_friday'] != "" && $f_horario_atual['saida_friday'] != "00:00" ? $f_horario_atual['saida_friday'] : "Sem jornada definida"; ?></li>
                        </ul>
                    </li>
                    <li>
                        <b>Sábado</b>
                        <ul>
                            <li>Entrada: <?= $f_horario_atual['entrada_saturday'] != "" && $f_horario_atual['entrada_saturday'] != "00:00" ? $f_horario_atual['entrada_saturday'] : "Sem jornada definida"; ?></li>
                            <li>Pausa: <?= $f_horario_atual['pausa_saturday'] != "" && $f_horario_atual['pausa_saturday'] != "00:00" ? $f_horario_atual['pausa_saturday'] : "Sem jornada definida"; ?></li>
                            <li>Retorno: <?= $f_horario_atual['retorno_saturday'] != "" && $f_horario_atual['retorno_saturday'] != "00:00" ? $f_horario_atual['retorno_saturday'] : "Sem jornada definida"; ?></li>
                            <li>Saída: <?= $f_horario_atual['saida_saturday'] != "" && $f_horario_atual['saida_saturday'] != "00:00" ? $f_horario_atual['saida_saturday'] : "Sem jornada definida"; ?></li>
                        </ul>
                    </li>
                    <li>
                        <b>Domingo</b>
                        <ul>
                            <li>Entrada: <?= $f_horario_atual['entrada_sunday'] != "" && $f_horario_atual['entrada_sunday'] != "00:00" ? $f_horario_atual['entrada_sunday'] : "Sem jornada definida"; ?></li>
                            <li>Pausa: <?= $f_horario_atual['pausa_sunday'] != "" && $f_horario_atual['pausa_sunday'] != "00:00" ? $f_horario_atual['pausa_sunday'] : "Sem jornada definida"; ?></li>
                            <li>Retorno: <?= $f_horario_atual['retorno_sunday'] != "" && $f_horario_atual['retorno_sunday'] != "00:00" ? $f_horario_atual['retorno_sunday'] : "Sem jornada definida"; ?></li>
                            <li>Saída: <?= $f_horario_atual['saida_sunday'] != "" && $f_horario_atual['saida_sunday'] != "00:00" ? $f_horario_atual['saida_sunday'] : "Sem jornada definida"; ?></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>

        <div class="row">
            <div class="col-sm">
                <h5 class="text">Seus parâmetros</h5>
            </div>
        </div>
        <hr class="hr-divide-super-light">
        <div class="row">
            <div class="col-sm">
                <p class="text">Trabalhador noturno: <?= $f_horario_atual['noturno'] == '1' ? 'Sim' : 'Não'; ?>
                <p class="text">Pausa flexível: <?= $f_horario_atual['pausa_flexivel'] == '1' ? 'Sim' : 'Não'; ?>
                <p class="text">Tolerância de atraso: <?php echo $f_horario_atual['tolerancia'].' minuto(s)'; ?>
            </div>
        </div>

        <small class="text">Dúvidas ou problemas com os horários e parâmetros devem ser informados aos seus Gestores Administrativos</small>
        
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
      </div>
    </div>
  </div>
</div>

</html>