<?php
    session_start();
    include('src/meta.php');
?>
<!DOCTYPE html>
<html>
<head>
    <title>Central de Ajuda - Staffast</title>
</head>
<body style="margin-top: 0em;">
<div class="container-fluid" style="text-align: center;">
    <div class="row">
        <div class="col-sm">
            <img src="img/logo_staffast.png" width="200">
        </div>
    </div>

    <hr class="hr-divide">

    <div class="row">
        <div class="col-sm">
            <h1 class="high-text">Central de ajuda</h1>
            <a href="suporte/"><button class="button button1" style="font-size: 0.7em; margin-left: 1em;">Não encontrou o que precisa?</button></a>
        </div>
    </div>
</div>
<div class="container">

    <hr class="hr-divide-super-light">

    <div class="row">
        <div class="col-sm">
            <h5 class="high-text">1. <a href="#1">Gestores</a></h5>
        </div>
        <div class="col-sm">
            <h5 class="high-text">2. <a href="#2">Colaboradores</a></h5>
        </div>
        <div class="col-sm">
            <h5 class="high-text">3. <a href="#3">Setores</a></h5>
        </div>
        <div class="col-sm">
            <h5 class="high-text">4. <a href="#4">Avaliação da gestão</a></h5>
        </div>
    </div>
    <div class="row">
        <div class="col-sm">
            <h5 class="high-text">5. <a href="#5">Avaliação dos colaboradores</a></h5>
        </div>
        <div class="col-sm">
            <h5 class="high-text">6. <a href="#6">Autoavaliações</a></h5>
        </div>
        <div class="col-sm">
            <h5 class="high-text">7. <a href="#7">Processos seletivos</a></h5>
        </div>
        <div class="col-sm">
            <h5 class="high-text">8. <a href="#8">Reuniões</a></h5>
        </div>
    </div>
    <div class="row">
        <div class="col-sm">
            <h5 class="high-text">9. <a href="#9">Eventos</a></h5>
        </div>
        <div class="col-sm">
            <h5 class="high-text">10. <a href="#10">Metas OKR</a></h5>
        </div>
        <div class="col-sm">
            <h5 class="high-text">11. <a href="#11">Mensagens</a></h5>
        </div>
        <div class="col-sm">
            <h5 class="high-text">12. <a href="#12">Relatórios</a></h5>
        </div>
    </div>
    <div class="row">
        <div class="col-sm">
            <h5 class="high-text">13. <a href="#13">Documentos</a></h5>
        </div>
        <div class="col-sm">
            <h5 class="high-text">14. <a href="#14">Relatório de alterações</a></h5>
        </div>
    </div>

    <hr class="hr-divide-super-light">

        <div class="row" id="1">
            <h5 class="text">1. Sobre os <b>gestores</b></h5>
            <table class="table-site">
                <tr>
                    <th>Quem pode cadastrar?</th>
                    <th>Quem pode editar?</th>
                    <th>Quem pode desativar/reativar?</th>
                    <th>Quem pode torná-lo um colaborador?</th>
                    <th>Quem pode visualizar detalhes do perfil?</th>
                </tr>
                <tr>
                    <td>Gestores administrativos</td>
                    <td>Gestores administrativos e o próprio gestor</td>
                    <td>Gestores administrativos</td>
                    <td>Gestores administrativos</td>
                    <td>Gestores administrativos e o próprio gestor</td>
            </table>

            <p class="text">Os <b>gestores administrativos</b> são aqueles que têm acesso total ao Staffast e podem gerenciar o sistema como um todo. 
            Geralmente, gestores administrativos são funcionários do setor de Recursos Humanos da empresa.</p>
            <p class="text">Os <b>gestores operacionais</b> possuem acesso limitado ao Staffast, podendo atuar em alguns pontos e geralmente apenas com os colaboradores os quais estão inclusos em setores que o gestor é responsável</p>
            <p class="text">Uma vez que a empresa contrata o Staffast, o primeiro gestor administrativo é cadastrado e a equipe do Staffast não se responsabiliza por alterações de permissão feitas por gestores que possam causar visualização de conteúdo por pessoas não autorizadas.</p>
        </div>

        <hr class="hr-divide-super-light">

        <div class="row" id="2">
            <h5 class="text">2. Sobre os <b>colaboradores</b></h5>
            <table class="table-site">
                <tr>
                    <th>Quem pode cadastrar?</th>
                    <th>Quem pode editar?</th>
                    <th>Quem pode desativar/reativar?</th>
                    <th>Quem pode visualizar detalhes do perfil?</th>
                </tr>
                <tr>
                    <td>Gestores administrativos</td>
                    <td>Gestores administrativos e o próprio colaborador</td>
                    <td>Gestores administrativos</td>
                    <td>Gestores administrativos e o próprio colaborador</td>
            </table>

            <p class="text">Os <b>colaboradores</b> tem acesso restrito ao Staffast, podendo visualizar apenas informações sobre toda a empresa e as informações relacionadas a ele (como setores que está incluso, suas avalições, documentos, eventos, reuniões, etc.)</p>

        </div>

        <hr class="hr-divide-super-light">

        <div class="row" id="3">
            <h5 class="text">3. Sobre os <b>setores</b></h5>
            <table class="table-site">
                <tr>
                    <th>Quem pode cadastrar?</th>
                    <th>Quem pode editar?</th>
                    <th>Quem pode desativar/reativar?</th>
                    <th>Quem pode gerenciar os integrantes?</th>
                    <th>Quem pode liberar avaliação?</th>
                    <th>Quem pode visualizar resultados de avaliações?</th>
                </tr>
                <tr>
                    <td>Gestores administrativos</td>
                    <td>Gestores administrativos</td>
                    <td>Gestores administrativos</td>
                    <td>Gestores administrativos e os gestores responsáveis pelo setor</td>
                    <td>Gestores administrativos e os gestores responsáveis pelo setor</td>
                    <td>Gestores administrativos e os gestores responsáveis pelo setor (acesso total) e colaboradores (apenas gráficos, não os comentários)</td>
            </table>

            <p class="text">Os <b>setores</b> são os carros-chefe do Staffast, pois é através deles que definimos se os usuários possuem permissão para algumas ações no sistema. Por exemplo, se um gestor operacional não estiver incluso em nenhum setor, ele não conseguirá avaliar nenhum colaborador, pois o sistema só libera os gestores operacionais para avaliar colaboradores que estão inseridos nos setores em que eles também estão inseridos como gestores.</p>

        </div>

        <hr class="hr-divide-super-light">

        <div class="row" id="4">
            <h5 class="text">4. Sobre as <b>avaliações da gestão</b></h5>
            <table class="table-site">
                <tr>
                    <th>Quem pode liberar o código?</th>
                    <th>Quem pode invalidar o código?</th>
                    <th>Quem pode avaliar?</th>
                    <th>Quem pode visualizar resultados?</th>
                </tr>
                <tr>
                    <td>Gestores administrativos</td>
                    <td>Gestores administrativos</td>
                    <td>Qualquer colaborador com o código válido *</td>
                    <td>Todos os gestores e colaboradores (mas os comentários ficam visíveis apenas para os gestores administrativos)</td>
            </table>

            <p class="text">* Quando o gestor administrativo gera um código, ele á válido por 7 dias. De qualquer forma, qualquer colaborador e/ou gestor que possuir o código dentro da validade conseguirá fazer uma avaliação através do link <a href="avaliacao-empresa/" target="blank_">sistemastaffast.com/avaliacao-empresa/</a></p>

        </div>

        <hr class="hr-divide-super-light">

        <div class="row" id="5">
            <h5 class="text">5. Sobre as <b>avaliações dos colaboradores</b></h5>
            <table class="table-site">
                <tr>
                    <th>Quem pode avaliar?</th>
                    <th>Quem pode ver resultados?</th>
                    <th>Quem pode liberar os resultados?</th>
                </tr>
                <tr>
                    <td>Gestores administrativos e os gestores responsáveis *</td>
                    <td>Gestores administrativos, gestores responsáveis e o próprio colaborador</td>
                    <td>Gestores administrativos e os gestores responsáveis</td>
            </table>

            <p class="text">* Um gestor se torna responsável por um colaborador quando ele estiver incluído como gestor num setor que o colaborador também esteja inserido./</a></p>
        </div>

        <hr class="hr-divide-super-light">

        <div class="row" id="6">
            <h5 class="text">6. Sobre as <b>autoavaliações</b></h5>
            <table class="table-site">
                <tr>
                    <th>Quem pode se autoavaliar?</th>
                    <th>Quem pode liberar autoavaliação?</th>
                    <th>Quem pode ver os resultados?</th>
                </tr>
                <tr>
                    <td>Colaborador</td>
                    <td>Gestores administrativos, gestores responsáveis *</td>
                    <td>Gestores administrativos, gestores responsáveis e o próprio colaborador</td>
            </table>
            <p class="text">* Um gestor se torna responsável por um colaborador quando ele estiver incluído como gestor num setor que o colaborador também esteja inserido./</a></p>
        </div>

        <hr class="hr-divide-super-light">

        <div class="row" id="7">
            <h5 class="text">7. Sobre os <b>processos seletivos</b></h5>
            <table class="table-site">
                <tr>
                    <th>Quem pode criar?</th>
                    <th>Quem pode encerrar/prorrogar?</th>
                    <th>Quem pode visualizar?</th>
                </tr>
                <tr>
                    <td>Gestores administrativos</td>
                    <td>Gestores administrativos</td>
                    <td>Gestores administrativos</td>
            </table>
        </div>

        <hr class="hr-divide-super-light">

        <div class="row" id="8">
            <h5 class="text">8. Sobre as <b>reuniões</b></h5>
            <table class="table-site">
                <tr>
                    <th>Quem pode criar/editar?</th>
                    <th>Quem pode gerenciar integrantes?</th>
                    <th>Quem pode concluir?</th>
                    <th>Quem pode visualizar?</th>
                </tr>
                <tr>
                    <td>Gestores administrativos e gestores operacionais</td>
                    <td>Gestores administrativos e o gestor que criou a reunião</td>
                    <td>Gestor administrativo e o gestor que criou a reunião</td>
                    <td>Gestor administrativo e todos os integrantes da reunião</td>
            </table>
        </div>

        <hr class="hr-divide-super-light">

        <div class="row" id="9">
            <h5 class="text">9. Sobre os <b>eventos</b></h5>
            <table class="table-site">
                <tr>
                    <th>Quem pode criar/editar?</th>
                    <th>Quem pode cancelar?</th>
                    <th>Quem pode gerenciar participantes?</th>
                    <th>Quem pode visualizar?</th>
                </tr>
                <tr>
                    <td>Gestores administrativos</td>
                    <td>Apenas o gestor que criou o evento</td>
                    <td>Apenas o gestor que criou o evento</td>
                    <td>Gestores administrativos e todos os participantes do evento</td>
            </table>
        </div>

        <hr class="hr-divide-super-light">

        <div class="row" id="10">
            <h5 class="text">10. Sobre as <b>metas OKR</b></h5>
            <table class="table-site">
                <tr>
                    <th>Quem pode criar?</th>
                    <th>Quem pode atualizar o andamento?</th>
                    <th>Quem pode visualizar?</th>
                </tr>
                <tr>
                    <td>Gestores administrativos e gestores operacionais</td>
                    <td>Gestores administrativos e o gestor que criou a meta</td>
                    <td>Gestores administrativos e todos os participantes da meta OKR</td>
            </table>
        </div>

        <hr class="hr-divide-super-light">

        <div class="row" id="11">
            <h5 class="text">11. Sobre as <b>mensagens</b></h5>
            <table class="table-site">
                <tr>
                    <th>Quem pode criar?</th>
                    <th>Quem pode visualizar?</th>
                </tr>
                <tr>
                    <td>Qualquer usuário</td>
                    <td>Todos os que a mensagem for direcionada</td>
            </table>
        </div>

        <hr class="hr-divide-super-light">

        <div class="row" id="12">
            <h5 class="text">12. Sobre os <b>relatórios</b></h5>
            <table class="table-site">
                <tr>
                    <th>Gestores administrativos</th>
                    <th>Gestores operacionais</th>
                    <th>Colaboradores</th>
                </tr>
                <tr>
                    <td>Acesso total</td>
                    <td>Acesso a relatórios dos colaboradores os quais é responsável</td>
                    <td>Acesso a relatórios dele mesmo</td>
            </table>
        </div>

        <hr class="hr-divide-super-light">

        <div class="row" id="13">
            <h5 class="text">13. Sobre os <b>documentos</b></h5>
            <table class="table-site">
                <tr>
                    <th>Quem pode criar?</th>
                    <th>Quem pode visualizar?</th>
                </tr>
                <tr>
                    <td>Gestores administrativos</td>
                    <td>Gestores administrativos e o dono do documento</td>
            </table>
        </div>

        <hr class="hr-divide-super-light">

        <div class="row" id="14">
            <h5 class="text">14. Sobre o <b>relatório de alterações</b></h5>
            <table class="table-site">
                <tr>
                    <th>Quem pode visualizar?</th>
                </tr>
                <tr>
                    <td>Gestores administrativos</td>
            </table>
        </div>

        <hr class="hr-divide-super-light">

    </div>