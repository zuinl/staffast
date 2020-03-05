<?php

    require_once '../include/auth.php';
    require_once '../src/meta.php';
    require_once '../classes/class_horario.php';

    if(isset($_REQUEST['atualizar'])) {
        $horario = new Horario();

        $horario->setCpf($_REQUEST['cpf']);

        $horario->setEntradaMonday($_REQUEST['entrada_monday']);
        $horario->setPausaMonday($_REQUEST['pausa_monday']);
        $horario->setRetornoMonday($_REQUEST['retorno_monday']);
        $horario->setSaidaMonday($_REQUEST['saida_monday']);

        $horario->setEntradaTuesday($_REQUEST['entrada_tuesday']);
        $horario->setPausaTuesday($_REQUEST['pausa_tuesday']);
        $horario->setRetornoTuesday($_REQUEST['retorno_tuesday']);
        $horario->setSaidaTuesday($_REQUEST['saida_tuesday']);

        $horario->setEntradaWednesday($_REQUEST['entrada_wednesday']);
        $horario->setPausaWednesday($_REQUEST['pausa_wednesday']);
        $horario->setRetornoWednesday($_REQUEST['retorno_wednesday']);
        $horario->setSaidaWednesday($_REQUEST['saida_wednesday']);

        $horario->setEntradaThursday($_REQUEST['entrada_thursday']);
        $horario->setPausaThursday($_REQUEST['pausa_thursday']);
        $horario->setRetornoThursday($_REQUEST['retorno_thursday']);
        $horario->setSaidaThursday($_REQUEST['saida_thursday']);

        $horario->setEntradaFriday($_REQUEST['entrada_friday']);
        $horario->setPausaFriday($_REQUEST['pausa_friday']);
        $horario->setRetornoFriday($_REQUEST['retorno_friday']);
        $horario->setSaidaFriday($_REQUEST['saida_friday']);

        $horario->setEntradaSaturday($_REQUEST['entrada_saturday']);
        $horario->setPausaSaturday($_REQUEST['pausa_saturday']);
        $horario->setRetornoSaturday($_REQUEST['retorno_saturday']);
        $horario->setSaidaSaturday($_REQUEST['saida_saturday']);

        $horario->setEntradaSunday($_REQUEST['entrada_sunday']);
        $horario->setPausaSunday($_REQUEST['pausa_sunday']);
        $horario->setRetornoSunday($_REQUEST['retorno_sunday']);
        $horario->setSaidaSunday($_REQUEST['saida_sunday']);

        $horario->setPausaFlexivel($_REQUEST['pausa_flexivel']);
        $horario->setTolerancia($_REQUEST['tolerancia']);
        $horario->setNoturno($_REQUEST['noturno']);

        if($horario->atualizarHorario($_SESSION['empresa']['database'])) {
            $msg = 'Horários do funcionário atualizados';
        } else {
            echo 'Houve um erro ao atualizar os horários do funcionário';
        }

        echo '<div class="row">
            <div class="col-sm">
                <div class="alert alert-info alert-dismissible fade show" role="alert">
                    '.$msg.'
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            </div>
		</div>';
    }

?>