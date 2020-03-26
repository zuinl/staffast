<?php
session_start();
include('../src/meta.php');
?>
<!DOCTYPE html>
<html>
<head>
	<title>Termo de Uso e Política de Privacidade</title>
</head>
<nav class="navbar fixed-top navbar-expand-lg navbar-light bg-light" style="position: fixed;top: 0; width: 100%;">
    <img src="../img/logo_staffast.png" width="180">
</nav>

<body>

    <div class="container">

        <?php
        if(isset($_SESSION['msg'])) {
            ?>
            <div class="container-fluid">
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
            </div>
            <?php
        }
        ?>

        <div class="row" style="text-align: center;">
            <div class="col-sm">
                <h3 class="text">Aplicativo do Staffast</h3>
            </div>
        </div>

        <div class="row" style="text-align: center;">
            <div class="col-sm">
                <h4 class="text"><b>Termo de Uso</b></h4>
            </div>
        </div>

        <div class="row">
            <div class="col-sm">
                <p class="text">Nos termos a seguir descritos, considera-se USUÁRIO o colaborador de empresa que possui contrato de 
                    concessão de uso do <i>software</i> Staffast e que esteja devidamente cadastrado no mesmo e entende-se por APLICATIVO,  
                    a aplicação para dispositivos móveis, oferecida pelo <i>software</i> do Staffast e de criação da Adsumus Sistemas, inscrita 
                    sob o CNPJ 34.949.464/0001-60.
                </p>
                <p class="text">As imagens ilustrativas do APLICATIVO que não foram criadas pelo mesmo foram coletadas de bibliotecas públicas.</p>
            </div>
        </div>

        <div class="row">
            <div class="col-sm">
                <p class="text"><b>Definição de escopo</b></p>
                <p class="text">O aplicativo para dispositivos móveis Staffast, disponível para os sistemas operacionais Android e iOS, 
                    consiste em um serviço de registro de ponto eletrônico para colaboradores de empresas que possuem contrato de concessão 
                    de uso do <i>software</i> Staffast, disponível para acesso no link 
                    <a href="../" target="_blank">https://sistemastaffast.com</a>.
                </p>
                <p class="text">O aplicativo disponibiliza ao USUÁRIO as funcionalidades a seguir:
                    <ul>
                        <li class="text">Registro de ponto eletrônico: o USUÁRIO seleciona o tipo de registro que deseja fazer (entre "entrada", 
                            "pausa", "volta" e "saída"). O aplicativo enviará esta informação juntamente com os dados de identificação do USUÁRIO 
                            e os dados de localização do dispositivo (latitude e longitude). Estas informações são armazenadas, processadas e 
                            disponibilizadas para o próprio USUÁRIO e também para os gestores autorizados pela empresa;
                        </li>
                        <li class="text">Observação de ponto eletrônico: ao registrar o ponto eletrônico, o USUÁRIO terá a possibilidade de 
                            enviar uma observação livre, que também será armazenada e disponibilizada para os gestores autorizados pela empresa;
                        </li>
                        <li class="text">Consulta de histórico de pontos eletrônicos: o USUÁRIO poderá visualizar o histórico de registros de pontos 
                            eletrônicos feitos por ele, seja usando o aplicativo do Staffast ou usando o <i>website</i>, dentro do período aberto, o 
                            qual é definido pela empresa. No histórico exibido no aplicativo, apenas os horários e os tipos de registro serão informados. 
                            Para mais detalhes, o USUÁRIO deve acessar o <i>website</i> (disponível em <a href="../" target="_blank">https://sistemastaffast.com/</a>) 
                            do Staffast, realizar sua autenticação e acessar o histórico de pontos eletrônicos.
                        </li>
                    </ul>
                </p>

                <p class="text">Para que as funcionalidades supracitas possam ser utilizadas pelo USUÁRIO, os seguintes critérios devem ser 
                    atendidos:
                    <ul>
                        <li class="text">Autenticação do USUÁRIO: ao abrir o aplicativo do Staffast pela primeira vez, o USUÁRIO deverá inserir 
                            o e-mail e senha que estão cadastrados no sistema Staffast. Se o USUÁRIO souber o e-mail cadastrado, mas não se recordar 
                            da senha, ele pode recuprar a mesma clicando em "Esqueceu sua senha?" ou acessando o link 
                            <a href="https://sistemastaffast.com/staffast/recuperarSenha.php" target="_blank">https://sistemastaffast.com/staffast/recuperarSenha.php</a>. 
                            Caso o USUÁRIO não se recorde o e-mail utilizado, o mesmo deve procurar pelo gestor que realizou seu cadastro na empresa e perguntar 
                            pela informação. Em caso de ainda não conseguir acesso, o USUÁRIO deverá entrar em contato com o suporte usando um dos métodos descritos 
                            no link <a href="https://sistemastaffast.com/staffast/suporte/" target="_blank">https://sistemastaffast.com/staffast/suporte/</a>;
                        </li>
                        <li class="text">Acesso à localização: o aplicativo do Staffast irá coletar as coordenadas (latitude e longitude) do dispositivo do USUÁRIO 
                            todas as vezes que o mesmo solicitar o registro de um ponto eletrônico. Para isso, o aplicativo não permitirá o registro de ponto quando o 
                            GPS do dispositivo estiver desabilitado.
                        </li>
                    </ul>
                </p>
            </div>
        </div>

        <div class="row">
            <div class="col-sm">
                <p class="text"><b>Definição de responsabilidades</b></p>
                <p class="text">É de responsabilidade do <b>USUÁRIO</b>:
                    <ul>
                        <li class="text">Identificação verídica: o USUÁRIO deve usar seu e-mail e senha cadastrados no sistema do Staffast, jamais 
                            se identificando como outro colaborador. O nome completo do colaborador aparecerá no aplicativo todas as vezes que for 
                            registrar o ponto eletrônico, para que confirme sua identidade;
                        </li>
                        <li class="text">Autorização de acesso à localização: o USUÁRIO deve garantir que seu dispositivo conceda acesso à localização 
                            por parte do aplicativo do Staffast, a fim de garantir o bom funcionamente e a integridade dos dados enviados para o servidores 
                            do sistema. A forma de visualizar esta informação pode variar de acordo com a versão 
                            do sistema operacional do dispositivo, bem como o modelo do mesmo. Orientamos que procure informações específicas sobre o seu dispositivo, 
                            caso seja necessário. A equipe do Staffast <b>não se responsabilizará pela deficiência de informações sobre localização</b> nos casos em que 
                            o USUÁRIO negar acesso à localização do dispositivo.
                        </li>
                    </ul>
                </p>

                <p class="text">É de responsabilidade do <b>APLICATIVO</b>:
                    <ul>
                        <li class="text">Garantir o bom funcionamento do registro de ponto eletrônico, de todos os tipos, a qualquer hora e local;
                        </li>
                        <li class="text">Garantir a integridade e transparência das informações exibidas no histórico de registros de 
                            ponto eletrônico do aplicativo do Staffast;
                        </li>
                        <li class="text">Armazenar e disponibilizar, àqueles com autorização, todas as informações sobre os registros de 
                            pontos eletrônicos através do <i>website</i> do Staffast.
                        </li>
                    </ul>
                </p>
            </div>
        </div>

        <div class="row">
            <div class="col-sm">
                <p class="text"><b>Direitos autorais</b></p>
                <p class="text">Todas as funcionalidades, logomarca "Staffast" e o aplicativo Staffast como um todo pertecem ao APLICATIVO, bem como 
                    todos os direitos autorais dos mesmos.</p>
                <p class="text">As imagens ilustrativas do APLICATIVO que não foram criadas pelo mesmo foram coletadas de bibliotecas públicas.</p>
            </div>
        </div>

        <hr class="hr-divide-super-light">

        <div class="row" style="text-align: center;">
            <div class="col-sm">
                <h4 class="text"><b>Política de Privacidade</b></h4>
            </div>
        </div>

        <div class="row">
            <div class="col-sm">
                <p class="text"><b>Sobre as informações coletadas</b></p>
                <p class="text">Abaixo a listagem de informações coletadas pelo aplicativo do Staffast e a forma como as mesmas operam:</p>
                    <ul>
                        <li class="text">E-mail: o e-mail fornecido pelo usuário no momento da autenticação fica armazenado e é enviado para os servidores 
                            do sistema Staffast todas as vezes que uma consulta ou registro de ponto for realizado. Esta é a forma que o sistema identifica 
                            qual usuário está solicitando a operação;
                        </li>
                        <li class="text"><i>Token</i>: a senha inserida pelo usuário no momento da autenticação é transformada numa chave de identificação que 
                        também é enviada aos servidores do Staffast toda vez que o usuário usa o aplicativo. Esta é uma medida de segurança que protege 
                        as informações contra acesso indireto por pessoas ou máquinas má intencionadas;
                        </li>
                        <li class="text">Observação do ponto eletrônico: quando o usuário optar por deixar uma anotação no seu registro de ponto eletrônico, 
                            o aplicativo enviará esta informação para os servidores do Staffast;
                        </li>
                        <li class="text">Localização: toda vez que um novo registro de ponto for registrado, o aplicativo do Staffast coletará 
                            as coordenadas (latitude e longitude) do dispositivo, que são enviadas aos servidores do Staffast e traduzidas em 
                            um endereço que é disponibilizado no <i>website</i> do Staffast para o usuário e para os gestores autorizados na empresa.
                        </li>
                    </ul>
                </p>
            </div>
        </div>

        <div class="row">
            <div class="col-sm">
                <p class="text"><b>Disponibilação das informações coletadas</b></p>
                <p class="text">Todas as informações coletadas pelo aplicativo do Staffast e posteriormente processadas pelos servidores 
                    do Staffast são disponibilizadas no <i>website</i> do Staffast apenas para pessoas autorizadas.
                </p>
                <p class="text">Entende-se por pessoas autorizadas:
                </p>
                    <ul>
                        <li class="text">O próprio usuário: o colaborador que registrou o ponto, ou seja, o que deu origem à informação. 
                            Através do aplicativo (mais limitado) e do <i>website</i> (completo), o colaborador consegue visualizar todos 
                            os detalhes dos pontos eletrônicos registrados por ele;
                        </li>
                        <li class="text">Gestores administrativos: gestores apontados pela própria empresa como gestores administrativos 
                            podem acessar as informações dos colaboradores, incluindo o histórico de ponto eletrônico.
                        </li>
                    </ul>
                </p>
            </div>
        </div>

        <div class="row">
            <div class="col-sm">
                <p class="text"><b>Armazenamento das informações coletadas</b></p>
                <p class="text">Todas as informações coletadas e processadas pelo aplicativo e, posteriormente, pelos servidores do 
                    Staffast, são armazenadas na base de dados mantida pelo sistema Staffast.
                </p>
            </div>
        </div>

        <hr class="hr-divide-super-light">

        <div class="row" style="text-align: center;">
            <div class="col-sm">
                <small class="text">Adsumus Sistemas - <?php echo date('Y'); ?></small>
            </div>
        </div>
    </div>

    </div>
</body>