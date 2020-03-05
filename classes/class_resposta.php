<?php

class Resposta {

    private $ID;
    private $opcUm;
    private $opcDois;
    private $opcTres;
    private $opcQuatro;
    private $perID;
    private $canID;
    private $selID;

    function cadastrar($database_empresa) {

        require_once('class_conexao_empresa.php');
        require_once('class_queryHelper.php');

        $conexao = new ConexaoEmpresa($database_empresa);
        $conexao = $conexao->conecta();
        $helper = new QueryHelper($conexao);

        $insert = "INSERT INTO tbl_pergunta_resposta (res_opc_um, res_opc_dois, res_opc_tres, res_opc_quatro, 
        per_id) VALUES ('$this->opcUm', '$this->opcDois', '$this->opcTres', '$this->opcQuatro', 
        '$this->perID')";

        $helper->insert($insert);
        $this->ID = $this->retornarUltima($database_empresa);

        $insert = "INSERT INTO tbl_resposta_candidato (can_id, res_id) VALUES ('$this->canID', 
        '$this->ID')";

        if($helper->insert($insert)) {
            echo '<br>Cadastrado com sucesso';
            return true;
        }
        else {
            echo 'Erro: '.mysqli_error($conexao);
            return false;
        }
    }

    function retornarUltima($database_empresa) {

        require_once('class_conexao_empresa.php');
        require_once('class_queryHelper.php');

        $conexao = new ConexaoEmpresa($database_empresa);
        $conn = $conexao->conecta();
        $helper = new QueryHelper($conn);

        $select = "SELECT per_id as id FROM tbl_pergunta_resposta ORDER BY per_id DESC LIMIT 1";

        $fetch = $helper->select($select, 2);

        return $fetch['id'];

    }

    function retornarResposta($database_empresa) {

        require_once('class_conexao_empresa.php');
        require_once('class_queryHelper.php');

        $conexao = new ConexaoEmpresa($database_empresa);
        $conn = $conexao->conecta();
        $helper = new QueryHelper($conn);

        $select = "SELECT res_id as id, res_opc_um as um, res_opc_dois as dois, res_opc_tres as tres,
        res_opc_quatro as quatro, per_id as per FROM tbl_pergunta_resposta WHERE res_id = '$this->ID'";

        $fetch = $helper->select($select, 2);
        $res = new Resposta();
        $res->setID($fetch['id']);
        $res->setOpcUm($fetch['um']);
        $res->setOpcDois($fetch['dois']);
        $res->setOpcTres($fetch['tres']);
        $res->setOpcQuatro($fetch['quatro']);
        $res->setPerID($fetch['per']);

        return $res;

    }

    function setID($id) {
        $this->ID = $id;
    }

    function getID($id) {
        return $this->ID;
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

    function setPerID($perID) {
        $this->perID = $perID;
    }

    function getPerID() {
        return $this->perID;
    }

    function setSelID($selID) {
        $this->selID = $selID;
    }

    function getSelID() {
        return $this->selID;
    }

    function setCanID($canID) {
        $this->canID = $canID;
    }

    function getCanID() {
        return $this->canID;
    }
}

?>