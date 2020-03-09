<?php

include('../../include/auth.php');
include('../../src/meta.php');
require_once '../../classes/class_modelo_avaliacao.php';

if(!isset($_GET['modelo_id'])) die();

$modelo_id = $_GET['modelo_id'];
$num_competencias = 0;

if($modelo_id == 0) {
?>

    <div class="row" style="text-align: center;">
        <div class="col-sm">
            <h4 class="text">Modelo de avaliação: padrão da empresa</h4>
        </div>
    </div>
<table class="table-site">
        <tr>
            <th></th>
            <th>Competência avaliada</th>
            <th>Nota</th>
            <th>Observação</th>
        </tr>
        <tr>
            <td><b>1ª</b></td>
            <td><?php echo $_SESSION['empresa']['compet_um']; $num_competencias++; ?></td>
            <td>
                <input type="radio" name="compet_um" id="compet_um" value="1" class="radioMy" required> <img src="img/unhappy.png" width="30">
                <input type="radio" name="compet_um" id="compet_um" value="2" required class="radioMy" style="margin-left: 2em;"> <img src="img/sad.png" width="30">
                <input type="radio" name="compet_um" id="compet_um" value="3" class="radioMy" required style="margin-left: 2em;"> <img src="img/confused.png" width="30">
                <input type="radio" name="compet_um" id="compet_um" value="4" class="radioMy" required style="margin-left: 2em;"> <img src="img/smiling.png" width="30">
                <input type="radio" name="compet_um" id="compet_um" value="5" class="radioMy" required style="margin-left: 2em;"> <img src="img/happy.png" width="30">
            </td>
            <td>
                <textarea name="compet_um_obs" id="compet_um_obs" class="all-input"></textarea>
            </td>
        </tr>
        <tr>
            <td><b>2ª</b></td>
            <td><?php echo $_SESSION['empresa']['compet_dois']; $num_competencias++; ?></td>
            <td>
                <input type="radio" name="compet_dois" id="compet_dois" value="1" class="radioMy" required> <img src="img/unhappy.png" width="30">
                <input type="radio" name="compet_dois" id="compet_dois" value="2" required class="radioMy" style="margin-left: 2em;"> <img src="img/sad.png" width="30">
                <input type="radio" name="compet_dois" id="compet_dois" value="3" class="radioMy" required style="margin-left: 2em;"> <img src="img/confused.png" width="30">
                <input type="radio" name="compet_dois" id="compet_dois" value="4" class="radioMy" required style="margin-left: 2em;"> <img src="img/smiling.png" width="30">
                <input type="radio" name="compet_dois" id="compet_dois" value="5" class="radioMy" required style="margin-left: 2em;"> <img src="img/happy.png" width="30">
            </td>
            <td>
                <textarea name="compet_dois_obs" id="compet_dois_obs" class="all-input"></textarea>
            </td>
        </tr>
        <tr>
            <td><b>3ª</b></td>
            <td><?php echo $_SESSION['empresa']['compet_tres']; $num_competencias++; ?></td>
            <td>
                <input type="radio" name="compet_tres" id="compet_tres" value="1" class="radioMy" required> <img src="img/unhappy.png" width="30">
                <input type="radio" name="compet_tres" id="compet_tres" value="2" required class="radioMy" style="margin-left: 2em;"> <img src="img/sad.png" width="30">
                <input type="radio" name="compet_tres" id="compet_tres" value="3" class="radioMy" required style="margin-left: 2em;"> <img src="img/confused.png" width="30">
                <input type="radio" name="compet_tres" id="compet_tres" value="4" class="radioMy" required style="margin-left: 2em;"> <img src="img/smiling.png" width="30">
                <input type="radio" name="compet_tres" id="compet_tres" value="5" class="radioMy" required style="margin-left: 2em;"> <img src="img/happy.png" width="30">
            </td>
            <td>
                <textarea name="compet_tres_obs" id="compet_tres_obs" class="all-input"></textarea>
            </td>
        </tr>
        <tr>
            <td><b>4ª</b></td>
            <td><?php echo $_SESSION['empresa']['compet_quatro']; $num_competencias++; ?></td>
            <td>
                <input type="radio" name="compet_quatro" id="compet_quatro" value="1" class="radioMy" required> <img src="img/unhappy.png" width="30">
                <input type="radio" name="compet_quatro" id="compet_quatro" value="2" required class="radioMy" style="margin-left: 2em;"> <img src="img/sad.png" width="30">
                <input type="radio" name="compet_quatro" id="compet_quatro" value="3" class="radioMy" required style="margin-left: 2em;"> <img src="img/confused.png" width="30">
                <input type="radio" name="compet_quatro" id="compet_quatro" value="4" class="radioMy" required style="margin-left: 2em;"> <img src="img/smiling.png" width="30">
                <input type="radio" name="compet_quatro" id="compet_quatro" value="5" class="radioMy" required style="margin-left: 2em;"> <img src="img/happy.png" width="30">
            </td>
            <td>
                <textarea name="compet_quatro_obs" id="compet_quatro_obs" class="all-input"></textarea>
            </td>
        </tr>
        <?php if($_SESSION['empresa']['compet_cinco'] != "") { ?>
        <tr>
            <td><b>5ª</b></td>
            <td><?php echo $_SESSION['empresa']['compet_cinco']; $num_competencias++; ?></td>
            <td>
                <input type="radio" name="compet_cinco" id="compet_cinco" value="1" class="radioMy" required> <img src="img/unhappy.png" width="30">
                <input type="radio" name="compet_cinco" id="compet_cinco" value="2" required class="radioMy" style="margin-left: 2em;"> <img src="img/sad.png" width="30">
                <input type="radio" name="compet_cinco" id="compet_cinco" value="3" class="radioMy" required style="margin-left: 2em;"> <img src="img/confused.png" width="30">
                <input type="radio" name="compet_cinco" id="compet_cinco" value="4" class="radioMy" required style="margin-left: 2em;"> <img src="img/smiling.png" width="30">
                <input type="radio" name="compet_cinco" id="compet_cinco" value="5" class="radioMy" required style="margin-left: 2em;"> <img src="img/happy.png" width="30">
            </td>
            <td>
                <textarea name="compet_cinco_obs" id="compet_cinco_obs" class="all-input"></textarea>
            </td>
        </tr>
        <?php } ?>
        <?php if($_SESSION['empresa']['compet_seis'] != "") { ?>
        <tr>
            <td><b>6ª</b></td>
            <td><?php echo $_SESSION['empresa']['compet_seis']; $num_competencias++; ?></td>
            <td>
                <input type="radio" name="compet_seis" id="compet_seis" id="compet_seis" value="1" class="radioMy" required> <img src="img/unhappy.png" width="30">
                <input type="radio" name="compet_seis" id="compet_seis" id="compet_seis" value="2" required class="radioMy" style="margin-left: 2em;"> <img src="img/sad.png" width="30">
                <input type="radio" name="compet_seis" id="compet_seis" id="compet_seis" value="3" class="radioMy" required style="margin-left: 2em;"> <img src="img/confused.png" width="30">
                <input type="radio" name="compet_seis" id="compet_seis" id="compet_seis" value="4" class="radioMy" required style="margin-left: 2em;"> <img src="img/smiling.png" width="30">
                <input type="radio" name="compet_seis" id="compet_seis" id="compet_seis" value="5" class="radioMy" required style="margin-left: 2em;"> <img src="img/happy.png" width="30">
            </td>
            <td>
                <textarea name="compet_seis_obs" id="compet_seis_obs" class="all-input"></textarea>
            </td>
        </tr>
        <?php } ?>
        <?php if($_SESSION['empresa']['compet_sete'] != "") { ?>
        <tr>
            <td><b>7ª</b></td>
            <td><?php echo $_SESSION['empresa']['compet_sete']; $num_competencias++; ?></td>
            <td>
                <input type="radio" name="compet_sete" id="compet_sete" value="1" class="radioMy" required> <img src="img/unhappy.png" width="30">
                <input type="radio" name="compet_sete" id="compet_sete" value="2" required class="radioMy" style="margin-left: 2em;"> <img src="img/sad.png" width="30">
                <input type="radio" name="compet_sete" id="compet_sete" value="3" class="radioMy" required style="margin-left: 2em;"> <img src="img/confused.png" width="30">
                <input type="radio" name="compet_sete" id="compet_sete" value="4" class="radioMy" required style="margin-left: 2em;"> <img src="img/smiling.png" width="30">
                <input type="radio" name="compet_sete" id="compet_sete" value="5" class="radioMy" required style="margin-left: 2em;"> <img src="img/happy.png" width="30">
            </td>
            <td>
                <textarea name="compet_sete_obs" id="compet_sete_obs" class="all-input"></textarea>
            </td>
        </tr>
        <?php } ?>
        <?php if($_SESSION['empresa']['compet_oito'] != "") { ?>
        <tr>
            <td><b>8ª</b></td>
            <td><?php echo $_SESSION['empresa']['compet_oito']; $num_competencias++; ?></td>
            <td>
                <input type="radio" name="compet_oito" id="compet_oito" value="1" class="radioMy" required> <img src="img/unhappy.png" width="30">
                <input type="radio" name="compet_oito" id="compet_oito" value="2" required class="radioMy" style="margin-left: 2em;"> <img src="img/sad.png" width="30">
                <input type="radio" name="compet_oito" id="compet_oito" value="3" class="radioMy" required style="margin-left: 2em;"> <img src="img/confused.png" width="30">
                <input type="radio" name="compet_oito" id="compet_oito" value="4" class="radioMy" required style="margin-left: 2em;"> <img src="img/smiling.png" width="30">
                <input type="radio" name="compet_oito" id="compet_oito" value="5" class="radioMy" required style="margin-left: 2em;"> <img src="img/happy.png" width="30">
            </td>
            <td>
                <textarea name="compet_oito_obs" id="compet_oito_obs" class="all-input"></textarea>
            </td>
        </tr>
        <?php } ?>
        <?php if($_SESSION['empresa']['compet_nove'] != "") { ?>
        <tr>
            <td><b>9ª</b></td>
            <td><?php echo $_SESSION['empresa']['compet_nove']; $num_competencias++; ?></td>
            <td>
                <input type="radio" name="compet_nove" id="compet_nove" value="1" class="radioMy" required> <img src="img/unhappy.png" width="30">
                <input type="radio" name="compet_nove" id="compet_nove" value="2" required class="radioMy" style="margin-left: 2em;"> <img src="img/sad.png" width="30">
                <input type="radio" name="compet_nove" id="compet_nove" value="3" class="radioMy" required style="margin-left: 2em;"> <img src="img/confused.png" width="30">
                <input type="radio" name="compet_nove" id="compet_nove" value="4" class="radioMy" required style="margin-left: 2em;"> <img src="img/smiling.png" width="30">
                <input type="radio" name="compet_nove" id="compet_nove" value="5" class="radioMy" required style="margin-left: 2em;"> <img src="img/happy.png" width="30">
            </td>
            <td>
                <textarea name="compet_nove_obs" id="compet_nove_obs" class="all-input"></textarea>
            </td>
        </tr>
        <?php } ?>
        <?php if($_SESSION['empresa']['compet_dez'] != "") { ?>
        <tr>
            <td><b>10ª</b></td>
            <td><?php echo $_SESSION['empresa']['compet_dez']; $num_competencias++; ?></td>
            <td>
                <input type="radio" name="compet_dez" id="compet_dez" value="1" class="radioMy" required> <img src="img/unhappy.png" width="30">
                <input type="radio" name="compet_dez" id="compet_dez" value="2" required class="radioMy" style="margin-left: 2em;"> <img src="img/sad.png" width="30">
                <input type="radio" name="compet_dez" id="compet_dez" value="3" class="radioMy" required style="margin-left: 2em;"> <img src="img/confused.png" width="30">
                <input type="radio" name="compet_dez" id="compet_dez" value="4" class="radioMy" required style="margin-left: 2em;"> <img src="img/smiling.png" width="30">
                <input type="radio" name="compet_dez" id="compet_dez" value="5" class="radioMy" required style="margin-left: 2em;"> <img src="img/happy.png" width="30">
            </td>
            <td>
                <textarea name="compet_dez_obs" id="compet_dez_obs" class="all-input"></textarea>
            </td>
        </tr>
        <?php } ?>
        <?php if($_SESSION['empresa']['compet_onze'] != "") { ?>
        <tr>
            <td><b>11ª</b></td>
            <td><?php echo $_SESSION['empresa']['compet_onze']; $num_competencias++; ?></td>
            <td>
                <input type="radio" name="compet_onze" id="compet_onze" value="1" class="radioMy" required> <img src="img/unhappy.png" width="30">
                <input type="radio" name="compet_onze" id="compet_onze" value="2" required class="radioMy" style="margin-left: 2em;"> <img src="img/sad.png" width="30">
                <input type="radio" name="compet_onze" id="compet_onze" value="3" class="radioMy" required style="margin-left: 2em;"> <img src="img/confused.png" width="30">
                <input type="radio" name="compet_onze" id="compet_onze" value="4" class="radioMy" required style="margin-left: 2em;"> <img src="img/smiling.png" width="30">
                <input type="radio" name="compet_onze" id="compet_onze" value="5" class="radioMy" required style="margin-left: 2em;"> <img src="img/happy.png" width="30">
            </td>
            <td>
                <textarea name="compet_onze_obs" id="compet_onze_obs" class="all-input"></textarea>
            </td>
        </tr>
        <?php } ?>
        <?php if($_SESSION['empresa']['compet_doze'] != "") { ?>
        <tr>
            <td><b>12ª</b></td>
            <td><?php echo $_SESSION['empresa']['compet_doze']; $num_competencias++; ?></td>
            <td>
                <input type="radio" name="compet_doze" id="compet_doze" value="1" class="radioMy" required> <img src="img/unhappy.png" width="30">
                <input type="radio" name="compet_doze" id="compet_doze" value="2" required class="radioMy" style="margin-left: 2em;"> <img src="img/sad.png" width="30">
                <input type="radio" name="compet_doze" id="compet_doze" value="3" class="radioMy" required style="margin-left: 2em;"> <img src="img/confused.png" width="30">
                <input type="radio" name="compet_doze" id="compet_doze" value="4" class="radioMy" required style="margin-left: 2em;"> <img src="img/smiling.png" width="30">
                <input type="radio" name="compet_doze" id="compet_doze" value="5" class="radioMy" required style="margin-left: 2em;"> <img src="img/happy.png" width="30">
            </td>
            <td>
                <textarea name="compet_doze_obs" id="compet_doze_obs" class="all-input"></textarea>
            </td>
        </tr>
        <?php } ?>
        <?php if($_SESSION['empresa']['compet_treze'] != "") { ?>
        <tr>
            <td><b>13ª</b></td>
            <td><?php echo $_SESSION['empresa']['compet_treze']; $num_competencias++; ?></td>
            <td>
                <input type="radio" name="compet_treze" id="compet_treze" value="1" class="radioMy" required> <img src="img/unhappy.png" width="30">
                <input type="radio" name="compet_treze" id="compet_treze" value="2" required class="radioMy" style="margin-left: 2em;"> <img src="img/sad.png" width="30">
                <input type="radio" name="compet_treze" id="compet_treze" value="3" class="radioMy" required style="margin-left: 2em;"> <img src="img/confused.png" width="30">
                <input type="radio" name="compet_treze" id="compet_treze" value="4" class="radioMy" required style="margin-left: 2em;"> <img src="img/smiling.png" width="30">
                <input type="radio" name="compet_treze" id="compet_treze" value="5" class="radioMy" required style="margin-left: 2em;"> <img src="img/happy.png" width="30">
            </td>
            <td>
                <textarea name="compet_treze_obs" id="compet_treze_obs" class="all-input"></textarea>
            </td>
        </tr>
        <?php } ?>
        <?php if($_SESSION['empresa']['compet_quatorze'] != "") { ?>
        <tr>
            <td><b>14ª</b></td>
            <td><?php echo $_SESSION['empresa']['compet_quatorze']; $num_competencias++; ?></td>
            <td>
                <input type="radio" name="compet_quatorze" id="compet_quatorze" value="1" class="radioMy" required> <img src="img/unhappy.png" width="30">
                <input type="radio" name="compet_quatorze" id="compet_quatorze" value="2" required class="radioMy" style="margin-left: 2em;"> <img src="img/sad.png" width="30">
                <input type="radio" name="compet_quatorze" id="compet_quatorze" value="3" class="radioMy" required style="margin-left: 2em;"> <img src="img/confused.png" width="30">
                <input type="radio" name="compet_quatorze" id="compet_quatorze" value="4" class="radioMy" required style="margin-left: 2em;"> <img src="img/smiling.png" width="30">
                <input type="radio" name="compet_quatorze" id="compet_quatorze" value="5" class="radioMy" required style="margin-left: 2em;"> <img src="img/happy.png" width="30">
            </td>
            <td>
                <textarea name="compet_quatorze_obs" id="compet_quatorze_obs" class="all-input"></textarea>
            </td>
        </tr>
        <?php } ?>
        <?php if($_SESSION['empresa']['compet_quinze'] != "") { ?>
        <tr>
            <td><b>15ª</b></td>
            <td><?php echo $_SESSION['empresa']['compet_quinze']; $num_competencias++; ?></td>
            <td>
                <input type="radio" name="compet_quinze" id="compet_quinze" value="1" class="radioMy" required> <img src="img/unhappy.png" width="30">
                <input type="radio" name="compet_quinze" id="compet_quinze" value="2" required class="radioMy" style="margin-left: 2em;"> <img src="img/sad.png" width="30">
                <input type="radio" name="compet_quinze" id="compet_quinze" value="3" class="radioMy" required style="margin-left: 2em;"> <img src="img/confused.png" width="30">
                <input type="radio" name="compet_quinze" id="compet_quinze" value="4" class="radioMy" required style="margin-left: 2em;"> <img src="img/smiling.png" width="30">
                <input type="radio" name="compet_quinze" id="compet_quinze" value="5" class="radioMy" required style="margin-left: 2em;"> <img src="img/happy.png" width="30">
            </td>
            <td>
                <textarea name="compet_quinze_obs" id="compet_quinze_obs" class="all-input"></textarea>
            </td>
        </tr>
        <?php } ?>
        <?php if($_SESSION['empresa']['compet_dezesseis'] != "") { ?>
        <tr>
            <td><b>16ª</b></td>
            <td><?php echo $_SESSION['empresa']['compet_dezesseis']; $num_competencias++; ?></td>
            <td>
                <input type="radio" name="compet_dezesseis" id="compet_dezesseis" value="1" class="radioMy" required> <img src="img/unhappy.png" width="30">
                <input type="radio" name="compet_dezesseis" id="compet_dezesseis" value="2" required class="radioMy" style="margin-left: 2em;"> <img src="img/sad.png" width="30">
                <input type="radio" name="compet_dezesseis" id="compet_dezesseis" value="3" class="radioMy" required style="margin-left: 2em;"> <img src="img/confused.png" width="30">
                <input type="radio" name="compet_dezesseis" id="compet_dezesseis" value="4" class="radioMy" required style="margin-left: 2em;"> <img src="img/smiling.png" width="30">
                <input type="radio" name="compet_dezesseis" id="compet_dezesseis" value="5" class="radioMy" required style="margin-left: 2em;"> <img src="img/happy.png" width="30">
            </td>
            <td>
                <textarea name="compet_dezesseis_obs" id="compet_dezesseis_obs" class="all-input"></textarea>
            </td>
        </tr>
        <?php } ?>
        <?php if($_SESSION['empresa']['compet_dezessete'] != "") { ?>
        <tr>
            <td><b>17ª</b></td>
            <td><?php echo $_SESSION['empresa']['compet_dezessete']; $num_competencias++; ?></td>
            <td>
                <input type="radio" name="compet_dezessete" id="compet_dezessete" id="compet_dezessete" value="1" class="radioMy" required> <img src="img/unhappy.png" width="30">
                <input type="radio" name="compet_dezessete" id="compet_dezessete" id="compet_dezessete" value="2" required class="radioMy" style="margin-left: 2em;"> <img src="img/sad.png" width="30">
                <input type="radio" name="compet_dezessete" id="compet_dezessete" id="compet_dezessete" value="3" class="radioMy" required style="margin-left: 2em;"> <img src="img/confused.png" width="30">
                <input type="radio" name="compet_dezessete" id="compet_dezessete" id="compet_dezessete" value="4" class="radioMy" required style="margin-left: 2em;"> <img src="img/smiling.png" width="30">
                <input type="radio" name="compet_dezessete" id="compet_dezessete" id="compet_dezessete" value="5" class="radioMy" required style="margin-left: 2em;"> <img src="img/happy.png" width="30">
            </td>
            <td>
                <textarea name="compet_dezessete_obs" id="compet_dezessete_obs" class="all-input"></textarea>
            </td>
        </tr>
        <?php } ?>
        <?php if($_SESSION['empresa']['compet_dezoito'] != "") { ?>
        <tr>
            <td><b>18ª</b></td>
            <td><?php echo $_SESSION['empresa']['compet_dezoito']; $num_competencias++; ?></td>
            <td>
                <input type="radio" name="compet_dezoito" id="compet_dezoito" value="1" class="radioMy" required> <img src="img/unhappy.png" width="30">
                <input type="radio" name="compet_dezoito" id="compet_dezoito" value="2" required class="radioMy" style="margin-left: 2em;"> <img src="img/sad.png" width="30">
                <input type="radio" name="compet_dezoito" id="compet_dezoito" value="3" class="radioMy" required style="margin-left: 2em;"> <img src="img/confused.png" width="30">
                <input type="radio" name="compet_dezoito" id="compet_dezoito" value="4" class="radioMy" required style="margin-left: 2em;"> <img src="img/smiling.png" width="30">
                <input type="radio" name="compet_dezoito" id="compet_dezoito" value="5" class="radioMy" required style="margin-left: 2em;"> <img src="img/happy.png" width="30">
            </td>
            <td>
                <textarea name="compet_dezoito_obs" id="compet_dezoito_obs" class="all-input"></textarea>
            </td>
        </tr>
        <?php } ?>
        <?php if($_SESSION['empresa']['compet_dezenove'] != "") { ?>
        <tr>
            <td><b>19ª</b></td>
            <td><?php echo $_SESSION['empresa']['compet_dezenove']; $num_competencias++; ?></td>
            <td>
                <input type="radio" name="compet_dezenove" id="compet_dezenove" value="1" class="radioMy" required> <img src="img/unhappy.png" width="30">
                <input type="radio" name="compet_dezenove" id="compet_dezenove" value="2" required class="radioMy" style="margin-left: 2em;"> <img src="img/sad.png" width="30">
                <input type="radio" name="compet_dezenove" id="compet_dezenove" value="3" class="radioMy" required style="margin-left: 2em;"> <img src="img/confused.png" width="30">
                <input type="radio" name="compet_dezenove" id="compet_dezenove" value="4" class="radioMy" required style="margin-left: 2em;"> <img src="img/smiling.png" width="30">
                <input type="radio" name="compet_dezenove" id="compet_dezenove" value="5" class="radioMy" required style="margin-left: 2em;"> <img src="img/happy.png" width="30">
            </td>
            <td>
                <textarea name="compet_dezenove_obs" id="compet_dezenove_obs" class="all-input"></textarea>
            </td>
        </tr>
        <?php } ?>
        <?php if($_SESSION['empresa']['compet_vinte'] != "") { ?>
        <tr>
            <td><b>20ª</b></td>
            <td><?php echo $_SESSION['empresa']['compet_vinte']; $num_competencias++; ?></td>
            <td>
                <input type="radio" name="compet_vinte" id="compet_vinte" value="1" class="radioMy" required> <img src="img/unhappy.png" width="30">
                <input type="radio" name="compet_vinte" id="compet_vinte" value="2" required class="radioMy" style="margin-left: 2em;"> <img src="img/sad.png" width="30">
                <input type="radio" name="compet_vinte" id="compet_vinte" value="3" class="radioMy" required style="margin-left: 2em;"> <img src="img/confused.png" width="30">
                <input type="radio" name="compet_vinte" id="compet_vinte" value="4" class="radioMy" required style="margin-left: 2em;"> <img src="img/smiling.png" width="30">
                <input type="radio" name="compet_vinte" id="compet_vinte" value="5" class="radioMy" required style="margin-left: 2em;"> <img src="img/happy.png" width="30">
            </td>
            <td>
                <textarea name="compet_vinte_obs" id="compet_vinte_obs" class="all-input"></textarea>
            </td>
        </tr>
        <?php } ?>
    </table> 
    <!-- //fim do if de modelo geral/padrão  -->
<?php } else { // se for modelo de avaliação
    $modelo = new ModeloAvaliacao();
    $modelo->setID($modelo_id);
    $modelo = $modelo->retornarModeloAvaliacao($_SESSION['empresa']['database']);
    ?>

    <div class="row" style="text-align: center;">
        <div class="col-sm">
            <h4 class="text">Modelo de avaliação: <?php echo $modelo->getTitulo(); ?></h4>
        </div>
    </div>

    <table class="table-site">
        <tr>
            <th></th>
            <th>Competência avaliada</th>
            <th>Nota</th>
            <th>Observação</th>
        </tr>
        <tr>
            <td><b>1ª</b></td>
            <td><?php echo $modelo->getUm(); $num_competencias++; ?></td>
            <td>
                <input type="radio" name="compet_um" id="compet_um" value="1" class="radioMy" required> <img src="img/unhappy.png" width="30">
                <input type="radio" name="compet_um" id="compet_um" value="2" required class="radioMy" style="margin-left: 2em;"> <img src="img/sad.png" width="30">
                <input type="radio" name="compet_um" id="compet_um" value="3" class="radioMy" required style="margin-left: 2em;"> <img src="img/confused.png" width="30">
                <input type="radio" name="compet_um" id="compet_um" value="4" class="radioMy" required style="margin-left: 2em;"> <img src="img/smiling.png" width="30">
                <input type="radio" name="compet_um" id="compet_um" value="5" class="radioMy" required style="margin-left: 2em;"> <img src="img/happy.png" width="30">
            </td>
            <td>
                <textarea name="compet_um_obs" id="compet_um_obs" class="all-input"></textarea>
            </td>
        </tr>
        <tr>
            <td><b>2ª</b></td>
            <td><?php echo $modelo->getDois(); $num_competencias++; ?></td>
            <td>
                <input type="radio" name="compet_dois" id="compet_dois" value="1" class="radioMy" required> <img src="img/unhappy.png" width="30">
                <input type="radio" name="compet_dois" id="compet_dois" value="2" required class="radioMy" style="margin-left: 2em;"> <img src="img/sad.png" width="30">
                <input type="radio" name="compet_dois" id="compet_dois" value="3" class="radioMy" required style="margin-left: 2em;"> <img src="img/confused.png" width="30">
                <input type="radio" name="compet_dois" id="compet_dois" value="4" class="radioMy" required style="margin-left: 2em;"> <img src="img/smiling.png" width="30">
                <input type="radio" name="compet_dois" id="compet_dois" value="5" class="radioMy" required style="margin-left: 2em;"> <img src="img/happy.png" width="30">
            </td>
            <td>
                <textarea name="compet_dois_obs" id="compet_dois_obs" class="all-input"></textarea>
            </td>
        </tr>
        <tr>
            <td><b>3ª</b></td>
            <td><?php echo $modelo->getTres(); $num_competencias++; ?></td>
            <td>
                <input type="radio" name="compet_tres" id="compet_tres" value="1" class="radioMy" required> <img src="img/unhappy.png" width="30">
                <input type="radio" name="compet_tres" id="compet_tres" value="2" required class="radioMy" style="margin-left: 2em;"> <img src="img/sad.png" width="30">
                <input type="radio" name="compet_tres" id="compet_tres" value="3" class="radioMy" required style="margin-left: 2em;"> <img src="img/confused.png" width="30">
                <input type="radio" name="compet_tres" id="compet_tres" value="4" class="radioMy" required style="margin-left: 2em;"> <img src="img/smiling.png" width="30">
                <input type="radio" name="compet_tres" id="compet_tres" value="5" class="radioMy" required style="margin-left: 2em;"> <img src="img/happy.png" width="30">
            </td>
            <td>
                <textarea name="compet_tres_obs" id="compet_tres_obs" class="all-input"></textarea>
            </td>
        </tr>
        <tr>
            <td><b>4ª</b></td>
            <td><?php echo $modelo->getQuatro(); $num_competencias++; ?></td>
            <td>
                <input type="radio" name="compet_quatro" id="compet_quatro" value="1" class="radioMy" required> <img src="img/unhappy.png" width="30">
                <input type="radio" name="compet_quatro" id="compet_quatro" value="2" required class="radioMy" style="margin-left: 2em;"> <img src="img/sad.png" width="30">
                <input type="radio" name="compet_quatro" id="compet_quatro" value="3" class="radioMy" required style="margin-left: 2em;"> <img src="img/confused.png" width="30">
                <input type="radio" name="compet_quatro" id="compet_quatro" value="4" class="radioMy" required style="margin-left: 2em;"> <img src="img/smiling.png" width="30">
                <input type="radio" name="compet_quatro" id="compet_quatro" value="5" class="radioMy" required style="margin-left: 2em;"> <img src="img/happy.png" width="30">
            </td>
            <td>
                <textarea name="compet_quatro_obs" id="compet_quatro_obs" class="all-input"></textarea>
            </td>
        </tr>
        <?php if($modelo->getCinco() != "") { ?>
        <tr>
            <td><b>5ª</b></td>
            <td><?php echo $modelo->getCinco(); $num_competencias++; ?></td>
            <td>
                <input type="radio" name="compet_cinco" id="compet_cinco" value="1" class="radioMy" required> <img src="img/unhappy.png" width="30">
                <input type="radio" name="compet_cinco" id="compet_cinco" value="2" required class="radioMy" style="margin-left: 2em;"> <img src="img/sad.png" width="30">
                <input type="radio" name="compet_cinco" id="compet_cinco" value="3" class="radioMy" required style="margin-left: 2em;"> <img src="img/confused.png" width="30">
                <input type="radio" name="compet_cinco" id="compet_cinco" value="4" class="radioMy" required style="margin-left: 2em;"> <img src="img/smiling.png" width="30">
                <input type="radio" name="compet_cinco" id="compet_cinco" value="5" class="radioMy" required style="margin-left: 2em;"> <img src="img/happy.png" width="30">
            </td>
            <td>
                <textarea name="compet_cinco_obs" id="compet_cinco_obs" class="all-input"></textarea>
            </td>
        </tr>
        <?php } ?>
        <?php if($modelo->getSeis() != "") { ?>
        <tr>
            <td><b>6ª</b></td>
            <td><?php echo $modelo->getSeis(); $num_competencias++; ?></td>
            <td>
                <input type="radio" name="compet_seis" id="compet_seis" id="compet_seis" value="1" class="radioMy" required> <img src="img/unhappy.png" width="30">
                <input type="radio" name="compet_seis" id="compet_seis" id="compet_seis" value="2" required class="radioMy" style="margin-left: 2em;"> <img src="img/sad.png" width="30">
                <input type="radio" name="compet_seis" id="compet_seis" id="compet_seis" value="3" class="radioMy" required style="margin-left: 2em;"> <img src="img/confused.png" width="30">
                <input type="radio" name="compet_seis" id="compet_seis" id="compet_seis" value="4" class="radioMy" required style="margin-left: 2em;"> <img src="img/smiling.png" width="30">
                <input type="radio" name="compet_seis" id="compet_seis" id="compet_seis" value="5" class="radioMy" required style="margin-left: 2em;"> <img src="img/happy.png" width="30">
            </td>
            <td>
                <textarea name="compet_seis_obs" id="compet_seis_obs" class="all-input"></textarea>
            </td>
        </tr>
        <?php } ?>
        <?php if($modelo->getSete() != "") { ?>
        <tr>
            <td><b>7ª</b></td>
            <td><?php echo $modelo->getSete(); $num_competencias++; ?></td>
            <td>
                <input type="radio" name="compet_sete" id="compet_sete" value="1" class="radioMy" required> <img src="img/unhappy.png" width="30">
                <input type="radio" name="compet_sete" id="compet_sete" value="2" required class="radioMy" style="margin-left: 2em;"> <img src="img/sad.png" width="30">
                <input type="radio" name="compet_sete" id="compet_sete" value="3" class="radioMy" required style="margin-left: 2em;"> <img src="img/confused.png" width="30">
                <input type="radio" name="compet_sete" id="compet_sete" value="4" class="radioMy" required style="margin-left: 2em;"> <img src="img/smiling.png" width="30">
                <input type="radio" name="compet_sete" id="compet_sete" value="5" class="radioMy" required style="margin-left: 2em;"> <img src="img/happy.png" width="30">
            </td>
            <td>
                <textarea name="compet_sete_obs" id="compet_sete_obs" class="all-input"></textarea>
            </td>
        </tr>
        <?php } ?>
        <?php if($modelo->getOito() != "") { ?>
        <tr>
            <td><b>8ª</b></td>
            <td><?php echo $modelo->getOito(); $num_competencias++; ?></td>
            <td>
                <input type="radio" name="compet_oito" id="compet_oito" value="1" class="radioMy" required> <img src="img/unhappy.png" width="30">
                <input type="radio" name="compet_oito" id="compet_oito" value="2" required class="radioMy" style="margin-left: 2em;"> <img src="img/sad.png" width="30">
                <input type="radio" name="compet_oito" id="compet_oito" value="3" class="radioMy" required style="margin-left: 2em;"> <img src="img/confused.png" width="30">
                <input type="radio" name="compet_oito" id="compet_oito" value="4" class="radioMy" required style="margin-left: 2em;"> <img src="img/smiling.png" width="30">
                <input type="radio" name="compet_oito" id="compet_oito" value="5" class="radioMy" required style="margin-left: 2em;"> <img src="img/happy.png" width="30">
            </td>
            <td>
                <textarea name="compet_oito_obs" id="compet_oito_obs" class="all-input"></textarea>
            </td>
        </tr>
        <?php } ?>
        <?php if($modelo->getNove() != "") { ?>
        <tr>
            <td><b>9ª</b></td>
            <td><?php echo $modelo->getNove(); $num_competencias++; ?></td>
            <td>
                <input type="radio" name="compet_nove" id="compet_nove" value="1" class="radioMy" required> <img src="img/unhappy.png" width="30">
                <input type="radio" name="compet_nove" id="compet_nove" value="2" required class="radioMy" style="margin-left: 2em;"> <img src="img/sad.png" width="30">
                <input type="radio" name="compet_nove" id="compet_nove" value="3" class="radioMy" required style="margin-left: 2em;"> <img src="img/confused.png" width="30">
                <input type="radio" name="compet_nove" id="compet_nove" value="4" class="radioMy" required style="margin-left: 2em;"> <img src="img/smiling.png" width="30">
                <input type="radio" name="compet_nove" id="compet_nove" value="5" class="radioMy" required style="margin-left: 2em;"> <img src="img/happy.png" width="30">
            </td>
            <td>
                <textarea name="compet_nove_obs" id="compet_nove_obs" class="all-input"></textarea>
            </td>
        </tr>
        <?php } ?>
        <?php if($modelo->getDez() != "") { ?>
        <tr>
            <td><b>10ª</b></td>
            <td><?php echo $modelo->getDez(); $num_competencias++; ?></td>
            <td>
                <input type="radio" name="compet_dez" id="compet_dez" value="1" class="radioMy" required> <img src="img/unhappy.png" width="30">
                <input type="radio" name="compet_dez" id="compet_dez" value="2" required class="radioMy" style="margin-left: 2em;"> <img src="img/sad.png" width="30">
                <input type="radio" name="compet_dez" id="compet_dez" value="3" class="radioMy" required style="margin-left: 2em;"> <img src="img/confused.png" width="30">
                <input type="radio" name="compet_dez" id="compet_dez" value="4" class="radioMy" required style="margin-left: 2em;"> <img src="img/smiling.png" width="30">
                <input type="radio" name="compet_dez" id="compet_dez" value="5" class="radioMy" required style="margin-left: 2em;"> <img src="img/happy.png" width="30">
            </td>
            <td>
                <textarea name="compet_dez_obs" id="compet_dez_obs" class="all-input"></textarea>
            </td>
        </tr>
        <?php } ?>
        <?php if($modelo->getOnze() != "") { ?>
        <tr>
            <td><b>11ª</b></td>
            <td><?php echo $modelo->getOnze(); $num_competencias++; ?></td>
            <td>
                <input type="radio" name="compet_onze" id="compet_onze" value="1" class="radioMy" required> <img src="img/unhappy.png" width="30">
                <input type="radio" name="compet_onze" id="compet_onze" value="2" required class="radioMy" style="margin-left: 2em;"> <img src="img/sad.png" width="30">
                <input type="radio" name="compet_onze" id="compet_onze" value="3" class="radioMy" required style="margin-left: 2em;"> <img src="img/confused.png" width="30">
                <input type="radio" name="compet_onze" id="compet_onze" value="4" class="radioMy" required style="margin-left: 2em;"> <img src="img/smiling.png" width="30">
                <input type="radio" name="compet_onze" id="compet_onze" value="5" class="radioMy" required style="margin-left: 2em;"> <img src="img/happy.png" width="30">
            </td>
            <td>
                <textarea name="compet_onze_obs" id="compet_onze_obs" class="all-input"></textarea>
            </td>
        </tr>
        <?php } ?>
        <?php if($modelo->getDoze() != "") { ?>
        <tr>
            <td><b>12ª</b></td>
            <td><?php echo $modelo->getDoze(); $num_competencias++; ?></td>
            <td>
                <input type="radio" name="compet_doze" id="compet_doze" value="1" class="radioMy" required> <img src="img/unhappy.png" width="30">
                <input type="radio" name="compet_doze" id="compet_doze" value="2" required class="radioMy" style="margin-left: 2em;"> <img src="img/sad.png" width="30">
                <input type="radio" name="compet_doze" id="compet_doze" value="3" class="radioMy" required style="margin-left: 2em;"> <img src="img/confused.png" width="30">
                <input type="radio" name="compet_doze" id="compet_doze" value="4" class="radioMy" required style="margin-left: 2em;"> <img src="img/smiling.png" width="30">
                <input type="radio" name="compet_doze" id="compet_doze" value="5" class="radioMy" required style="margin-left: 2em;"> <img src="img/happy.png" width="30">
            </td>
            <td>
                <textarea name="compet_doze_obs" id="compet_doze_obs" class="all-input"></textarea>
            </td>
        </tr>
        <?php } ?>
        <?php if($modelo->getTreze() != "") { ?>
        <tr>
            <td><b>13ª</b></td>
            <td><?php echo $modelo->getTreze(); $num_competencias++; ?></td>
            <td>
                <input type="radio" name="compet_treze" id="compet_treze" value="1" class="radioMy" required> <img src="img/unhappy.png" width="30">
                <input type="radio" name="compet_treze" id="compet_treze" value="2" required class="radioMy" style="margin-left: 2em;"> <img src="img/sad.png" width="30">
                <input type="radio" name="compet_treze" id="compet_treze" value="3" class="radioMy" required style="margin-left: 2em;"> <img src="img/confused.png" width="30">
                <input type="radio" name="compet_treze" id="compet_treze" value="4" class="radioMy" required style="margin-left: 2em;"> <img src="img/smiling.png" width="30">
                <input type="radio" name="compet_treze" id="compet_treze" value="5" class="radioMy" required style="margin-left: 2em;"> <img src="img/happy.png" width="30">
            </td>
            <td>
                <textarea name="compet_treze_obs" id="compet_treze_obs" class="all-input"></textarea>
            </td>
        </tr>
        <?php } ?>
        <?php if($modelo->getQuatorze() != "") { ?>
        <tr>
            <td><b>14ª</b></td>
            <td><?php echo $modelo->getQuatorze(); $num_competencias++; ?></td>
            <td>
                <input type="radio" name="compet_quatorze" id="compet_quatorze" value="1" class="radioMy" required> <img src="img/unhappy.png" width="30">
                <input type="radio" name="compet_quatorze" id="compet_quatorze" value="2" required class="radioMy" style="margin-left: 2em;"> <img src="img/sad.png" width="30">
                <input type="radio" name="compet_quatorze" id="compet_quatorze" value="3" class="radioMy" required style="margin-left: 2em;"> <img src="img/confused.png" width="30">
                <input type="radio" name="compet_quatorze" id="compet_quatorze" value="4" class="radioMy" required style="margin-left: 2em;"> <img src="img/smiling.png" width="30">
                <input type="radio" name="compet_quatorze" id="compet_quatorze" value="5" class="radioMy" required style="margin-left: 2em;"> <img src="img/happy.png" width="30">
            </td>
            <td>
                <textarea name="compet_quatorze_obs" id="compet_quatorze_obs" class="all-input"></textarea>
            </td>
        </tr>
        <?php } ?>
        <?php if($modelo->getQuinze() != "") { ?>
        <tr>
            <td><b>15ª</b></td>
            <td><?php echo $modelo->getQuinze(); $num_competencias++; ?></td>
            <td>
                <input type="radio" name="compet_quinze" id="compet_quinze" value="1" class="radioMy" required> <img src="img/unhappy.png" width="30">
                <input type="radio" name="compet_quinze" id="compet_quinze" value="2" required class="radioMy" style="margin-left: 2em;"> <img src="img/sad.png" width="30">
                <input type="radio" name="compet_quinze" id="compet_quinze" value="3" class="radioMy" required style="margin-left: 2em;"> <img src="img/confused.png" width="30">
                <input type="radio" name="compet_quinze" id="compet_quinze" value="4" class="radioMy" required style="margin-left: 2em;"> <img src="img/smiling.png" width="30">
                <input type="radio" name="compet_quinze" id="compet_quinze" value="5" class="radioMy" required style="margin-left: 2em;"> <img src="img/happy.png" width="30">
            </td>
            <td>
                <textarea name="compet_quinze_obs" id="compet_quinze_obs" class="all-input"></textarea>
            </td>
        </tr>
        <?php } ?>
        <?php if($modelo->getDezesseis() != "") { ?>
        <tr>
            <td><b>16ª</b></td>
            <td><?php echo $modelo->getDezesseis(); $num_competencias++; ?></td>
            <td>
                <input type="radio" name="compet_dezesseis" id="compet_dezesseis" value="1" class="radioMy" required> <img src="img/unhappy.png" width="30">
                <input type="radio" name="compet_dezesseis" id="compet_dezesseis" value="2" required class="radioMy" style="margin-left: 2em;"> <img src="img/sad.png" width="30">
                <input type="radio" name="compet_dezesseis" id="compet_dezesseis" value="3" class="radioMy" required style="margin-left: 2em;"> <img src="img/confused.png" width="30">
                <input type="radio" name="compet_dezesseis" id="compet_dezesseis" value="4" class="radioMy" required style="margin-left: 2em;"> <img src="img/smiling.png" width="30">
                <input type="radio" name="compet_dezesseis" id="compet_dezesseis" value="5" class="radioMy" required style="margin-left: 2em;"> <img src="img/happy.png" width="30">
            </td>
            <td>
                <textarea name="compet_dezesseis_obs" id="compet_dezesseis_obs" class="all-input"></textarea>
            </td>
        </tr>
        <?php } ?>
        <?php if($modelo->getDezessete() != "") { ?>
        <tr>
            <td><b>17ª</b></td>
            <td><?php echo $modelo->getDezessete(); $num_competencias++; ?></td>
            <td>
                <input type="radio" name="compet_dezessete" id="compet_dezessete" id="compet_dezessete" value="1" class="radioMy" required> <img src="img/unhappy.png" width="30">
                <input type="radio" name="compet_dezessete" id="compet_dezessete" id="compet_dezessete" value="2" required class="radioMy" style="margin-left: 2em;"> <img src="img/sad.png" width="30">
                <input type="radio" name="compet_dezessete" id="compet_dezessete" id="compet_dezessete" value="3" class="radioMy" required style="margin-left: 2em;"> <img src="img/confused.png" width="30">
                <input type="radio" name="compet_dezessete" id="compet_dezessete" id="compet_dezessete" value="4" class="radioMy" required style="margin-left: 2em;"> <img src="img/smiling.png" width="30">
                <input type="radio" name="compet_dezessete" id="compet_dezessete" id="compet_dezessete" value="5" class="radioMy" required style="margin-left: 2em;"> <img src="img/happy.png" width="30">
            </td>
            <td>
                <textarea name="compet_dezessete_obs" id="compet_dezessete_obs" class="all-input"></textarea>
            </td>
        </tr>
        <?php } ?>
        <?php if($modelo->getDezoito() != "") { ?>
        <tr>
            <td><b>18ª</b></td>
            <td><?php echo $modelo->getDezoito(); $num_competencias++; ?></td>
            <td>
                <input type="radio" name="compet_dezoito" id="compet_dezoito" value="1" class="radioMy" required> <img src="img/unhappy.png" width="30">
                <input type="radio" name="compet_dezoito" id="compet_dezoito" value="2" required class="radioMy" style="margin-left: 2em;"> <img src="img/sad.png" width="30">
                <input type="radio" name="compet_dezoito" id="compet_dezoito" value="3" class="radioMy" required style="margin-left: 2em;"> <img src="img/confused.png" width="30">
                <input type="radio" name="compet_dezoito" id="compet_dezoito" value="4" class="radioMy" required style="margin-left: 2em;"> <img src="img/smiling.png" width="30">
                <input type="radio" name="compet_dezoito" id="compet_dezoito" value="5" class="radioMy" required style="margin-left: 2em;"> <img src="img/happy.png" width="30">
            </td>
            <td>
                <textarea name="compet_dezoito_obs" id="compet_dezoito_obs" class="all-input"></textarea>
            </td>
        </tr>
        <?php } ?>
        <?php if($modelo->getDezenove() != "") { ?>
        <tr>
            <td><b>19ª</b></td>
            <td><?php echo $modelo->getDezenove(); $num_competencias++; ?></td>
            <td>
                <input type="radio" name="compet_dezenove" id="compet_dezenove" value="1" class="radioMy" required> <img src="img/unhappy.png" width="30">
                <input type="radio" name="compet_dezenove" id="compet_dezenove" value="2" required class="radioMy" style="margin-left: 2em;"> <img src="img/sad.png" width="30">
                <input type="radio" name="compet_dezenove" id="compet_dezenove" value="3" class="radioMy" required style="margin-left: 2em;"> <img src="img/confused.png" width="30">
                <input type="radio" name="compet_dezenove" id="compet_dezenove" value="4" class="radioMy" required style="margin-left: 2em;"> <img src="img/smiling.png" width="30">
                <input type="radio" name="compet_dezenove" id="compet_dezenove" value="5" class="radioMy" required style="margin-left: 2em;"> <img src="img/happy.png" width="30">
            </td>
            <td>
                <textarea name="compet_dezenove_obs" id="compet_dezenove_obs" class="all-input"></textarea>
            </td>
        </tr>
        <?php } ?>
        <?php if($modelo->getVinte() != "") { ?>
        <tr>
            <td><b>20ª</b></td>
            <td><?php echo $modelo->getVinte(); $num_competencias++; ?></td>
            <td>
                <input type="radio" name="compet_vinte" id="compet_vinte" value="1" class="radioMy" required> <img src="img/unhappy.png" width="30">
                <input type="radio" name="compet_vinte" id="compet_vinte" value="2" required class="radioMy" style="margin-left: 2em;"> <img src="img/sad.png" width="30">
                <input type="radio" name="compet_vinte" id="compet_vinte" value="3" class="radioMy" required style="margin-left: 2em;"> <img src="img/confused.png" width="30">
                <input type="radio" name="compet_vinte" id="compet_vinte" value="4" class="radioMy" required style="margin-left: 2em;"> <img src="img/smiling.png" width="30">
                <input type="radio" name="compet_vinte" id="compet_vinte" value="5" class="radioMy" required style="margin-left: 2em;"> <img src="img/happy.png" width="30">
            </td>
            <td>
                <textarea name="compet_vinte_obs" id="compet_vinte_obs" class="all-input"></textarea>
            </td>
        </tr>
        <?php } ?>
    </table> 
    <?php
} 


?>

<!-- FINAL DO FORM -->
<h6 class="text" style="margin-top: 1em;">O prazo pré definido para liberação desta avaliação ao colaborador é de 30 dias. 
    Você poderá liberá-la antes.</h6>

    <hr class="hr-divide-super-light">

    <div class="row" style="text-align: center;">
        <div class="col-sm">
            <input type="button" onclick="validaForm('<?php echo $num_competencias; ?>');" value="Finalizar avaliação" class="button button2">
        </div>
    </div>
</form>