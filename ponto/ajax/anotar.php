<?php

    require_once '../../classes/class_ponto.php';

    $email = $_GET['email'];
    $anotacao = addslashes($_GET['anotacao']);

    $ponto = new Ponto();

    $funcionario = $ponto->identificarFuncionario($email);

    if($funcionario == 2) {
        echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Atenção:</strong> Não foi encontrado usuário com este e-mail
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
              </div>';
        die();
    } else if ($funcionario == 3) {
        echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Atenção:</strong> Não foi possível encontrar o cadastro para realizar anotação.
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
              </div>';
        die();
    }

    $ret = $ponto->anotar($funcionario['cpf'], $anotacao, $funcionario['database']);

    if($ret === true) {
        echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
                <strong>Beleza!</strong> Sua anotação foi salva.
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
              </div>';
        die();
    } else {
        echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Atenção:</strong> houve um erro ao salvar sua anotação
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
              </div>';
        die();
    }

?>