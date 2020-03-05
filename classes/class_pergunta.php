<?php

class Pergunta {

    private $ID;
    private $titulo;
    private $descricao;
    private $opcUm;
    private $competUm;
    private $opcDois;
    private $competDois;
    private $opcTres;
    private $competTres;
    private $opcQuatro;
    private $competQuatro;
    private $selID;

    function cadastrar($database_empresa) {

        require_once('class_conexao_empresa.php');
        require_once('class_queryHelper.php');

        $conexao = new ConexaoEmpresa($database_empresa);
        $conexao = $conexao->conecta();
        $helper = new QueryHelper($conexao);

        $insert = "INSERT INTO tbl_pergunta_processo (per_titulo, per_descricao, 
        per_opc_um, per_opc_um_competencia, per_opc_dois, per_opc_dois_competencia, 
        per_opc_tres, per_opc_tres_competencia, per_opc_quatro, per_opc_quatro_competencia, 
        sel_id) VALUES ('$this->titulo', '$this->descricao', '$this->opcUm', '$this->competUm', '$this->opcDois', 
        '$this->competDois', '$this->opcTres', '$this->competTres', '$this->opcQuatro', '$this->competQuatro', 
        '$this->selID')";

        if($helper->insert($insert)) {
            echo '<br>Cadastrado com sucesso';
            return true;
        }
        else {
            echo 'Erro: '.mysqli_error($conexao);
            return false;
        }
    }

    function retornarPergunta($database_empresa) {

        require_once('class_conexao_empresa.php');
        require_once('class_queryHelper.php');

        $conexao = new ConexaoEmpresa($database_empresa);
        $conn = $conexao->conecta();
        $helper = new QueryHelper($conn);

        $select = "SELECT per_id as id, per_titulo as titulo, 
        per_descricao as descricao, per_opc_um as um, per_opc_dois as dois,
        per_opc_tres as tres, per_opc_quatro as quatro, per_opc_um_competencia as c1, 
        per_opc_dois_competencia as c2, per_opc_tres_competencia as c3, 
        per_opc_quatro_competencia as c4, sel_id as sel FROM tbl_pergunta_processo WHERE 
        per_id = '$this->ID'";

        $fetch = $helper->select($select, 2);
        $per = new Pergunta();
        $per->setID($fetch['id']);
        $per->setTitulo($fetch['titulo']);
        $per->setDescricao($fetch['descricao']);
        $per->setOpcUm($fetch['um']);
        $per->setOpcDois($fetch['dois']);
        $per->setOpcTres($fetch['tres']);
        $per->setOpcQuatro($fetch['quatro']);
        $per->setCompetUm($fetch['c1']);
        $per->setCompetDois($fetch['c2']);
        $per->setCompetTres($fetch['c3']);
        $per->setCompetQuatro($fetch['c4']);
        $per->setSelID($fetch['sel']);

        return $per;

    }

    function setID($id) {
        $this->ID = $id;
    }

    function getID() {
        return $this->ID;
    }

    function setTitulo($titulo) {
        $this->titulo = $titulo;
    }

    function getTitulo() {
        return $this->titulo;
    }

    function setDescricao($descricao) {
        $this->descricao = $descricao;
    }

    function getDescricao() {
        return $this->descricao;
    }

    function setOpcUm($opcUm) {
        $this->opcUm = $opcUm;
    }

    function getOpcUm() {
        return $this->opcUm;
    }

    function setOpcDois($opcDois) {
        $this->opcDois = $opcDois;
    }

    function getOpcDois() {
        return $this->opcDois;
    }

    function setOpcTres($opcTres) {
        $this->opcTres = $opcTres;
    }

    function getOpcTres() {
        return $this->opcTres;
    }

    function setOpcQuatro($opcQuatro) {
        $this->opcQuatro = $opcQuatro;
    }

    function getOpcQuatro() {
        return $this->opcQuatro;
    }

    function setCompetUm($competUm) {
        $this->competUm = $competUm;
    }

    function getCompetUm() {
        return $this->competUm;
    }

    function setCompetDois($competDois) {
        $this->competDois = $competDois;
    }

    function getCompetDois() {
        return $this->competDois;
    }

    function setCompetTres($competTres) {
        $this->competTres = $competTres;
    }

    function getCompetTres() {
        return $this->competTres;
    }

    function setCompetQuatro($competQuatro) {
        $this->competQuatro = $competQuatro;
    }

    function getCompetQuatro() {
        return $this->competQuatro;
    }

    function setSelID($selID) {
        $this->selID = $selID;
    }

    function getSelID() {
        return $this->selID;
    }
}

?>