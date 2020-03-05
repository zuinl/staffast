<?php
    function numCompetencias() {
        $competencias = 4;
        if($_SESSION['empresa']['compet_cinco'] != "") $competencias = 5;
        if($_SESSION['empresa']['compet_seis'] != "") $competencias = 6;
        if($_SESSION['empresa']['compet_sete'] != "") $competencias = 7;
        if($_SESSION['empresa']['compet_oito'] != "") $competencias = 8;
        if($_SESSION['empresa']['compet_nove'] != "") $competencias = 9;
        if($_SESSION['empresa']['compet_dez'] != "") $competencias = 10;
        if($_SESSION['empresa']['compet_onze'] != "") $competencias = 11;
        if($_SESSION['empresa']['compet_doze'] != "") $competencias = 12;
        if($_SESSION['empresa']['compet_treze'] != "") $competencias = 13;
        if($_SESSION['empresa']['compet_quatorze'] != "") $competencias = 14;
        if($_SESSION['empresa']['compet_quinze'] != "") $competencias = 15;
        if($_SESSION['empresa']['compet_dezesseis'] != "") $competencias = 16;
        if($_SESSION['empresa']['compet_dezessete'] != "") $competencias = 17;
        if($_SESSION['empresa']['compet_dezoito'] != "") $competencias = 18;
        if($_SESSION['empresa']['compet_dezenove'] != "") $competencias = 19;
        if($_SESSION['empresa']['compet_vinte'] != "") $competencias = 20;

        return $competencias;
    }
?>