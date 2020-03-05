<?php

    date_default_timezone_set('America/Sao_Paulo');
    require_once '../../classes/class_ponto.php';

    $email = $_GET['email'];
    $tipo = $_GET['tipo'];

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
                <strong>Atenção:</strong> Não foi encontrado o cadastro do funcionário na empresa. Isso geralmente acontece 
                quando o funcionário está desativado. Entre em contato com o setor de RH e, se necessário, com o suporte 
                do Staffast.
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
              </div>';
        die();
    }

    $ret = $ponto->registrarPonto($tipo, date('Y-m-d H:i:s'), $funcionario['cpf'], $funcionario['database'], '', '');

    if($ret === true) {
        if(!isset($_COOKIE['staffast_ponto_email'])) setcookie('staffast_ponto_email', $email, time() + (86400 * 365), "/"); // 86400 = 1 day
        echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
                <h4 class="text">'.$funcionario['nome'].' - '.$funcionario['empresa'].'</h4>
                <strong>Tudo certo!</strong> Seu registro de ponto foi salvo.
                <br><input type="text" class="all-input" name="anotacao" id="anotacao" placeholder="(Opcional) Insira uma anotação sobre este ponto">
                <input type="button" class="button button3" value="Enviar anotação" onClick="enviarAnotacao();">
                <input type="button" class="button button2" value="Fechar" data-dismiss="alert" aria-label="Close">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
              </div>';
        die();
    } else if($ret === 2) {
        echo '<div class="alert alert-warning alert-dismissible fade show" role="alert">
                <h4 class="text">'.$funcionario['nome'].' - '.$funcionario['empresa'].'</h4>
                <strong>Ops...</strong> já existe um registro de entrada para o dia de hoje.
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
              </div>';
        die();
    } else if($ret === 3) {
        echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                <h4 class="text">'.$funcionario['nome'].' - '.$funcionario['empresa'].'</h4>
                <strong>Desculpe!</strong> Houve um erro ao salvar sua entrada.
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
              </div>';
        die();
    } else if($ret === 4) {
        echo '<div class="alert alert-warning alert-dismissible fade show" role="alert">
                <h4 class="text">'.$funcionario['nome'].' - '.$funcionario['empresa'].'</h4>
                <strong>Ops...</strong> já existe um registro de saída para pausa para o dia de hoje.
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
              </div>';
        die();
    } else if($ret === 5) {
        echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                <h4 class="text">'.$funcionario['nome'].' - '.$funcionario['empresa'].'</h4>
                <strong>Desculpe!</strong> Houve um erro ao salvar sua saída para pausa.
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
              </div>';
        die();
    } else if($ret === 6) {
        echo '<div class="alert alert-warning alert-dismissible fade show" role="alert">
                <h4 class="text">'.$funcionario['nome'].' - '.$funcionario['empresa'].'</h4>
                <strong>Ops...</strong> já existe um registro de retorno da pausa para o dia de hoje.
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
              </div>';
        die();
    } else if($ret === 7) {
        echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                <h4 class="text">'.$funcionario['nome'].' - '.$funcionario['empresa'].'</h4>
                <strong>Desculpe!</strong> Houve um erro ao salvar seu retorno da pausa.
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
              </div>';
        die();
    } else if($ret === 8) {
        echo '<div class="alert alert-warning alert-dismissible fade show" role="alert">
                <h4 class="text">'.$funcionario['nome'].' - '.$funcionario['empresa'].'</h4>
                <strong>Ops...</strong> já existe um registro de saída para o dia de hoje.
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
              </div>';
        die();
    } else if($ret === 9) {
        echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                <h4 class="text">'.$funcionario['nome'].' - '.$funcionario['empresa'].'</h4>
                <strong>Desculpe!</strong> Houve um erro ao salvar sua saída.
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
              </div>';
        die();
    } else if($ret === 10) {
        echo '<div class="alert alert-warning alert-dismissible fade show" role="alert">
                <h4 class="text">'.$funcionario['nome'].' - '.$funcionario['empresa'].'</h4>
                <strong>Ops...</strong> parece que você já registrou uma entrada menos de uma hora atrás.
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
              </div>';
        die();
    } else if($ret === 11) {
        echo '<div class="alert alert-warning alert-dismissible fade show" role="alert">
                <h4 class="text">'.$funcionario['nome'].' - '.$funcionario['empresa'].'</h4>
                <strong>Ops...</strong> parece que você já registrou uma saída menos de uma hora atrás.
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
              </div>';
        die();
    }

?>