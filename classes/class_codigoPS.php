<?php 

class CodigoPS {

    private $codigo;
    private $selID;
    private $empID;

    function criar() {

        require_once('class_conexao_padrao.php');
        require_once('class_queryHelper.php');

        $conexao = new ConexaoPadrao();
        $conexao = $conexao->conecta();
        $helper = new QueryHelper($conexao);

        $insert = "INSERT INTO tbl_codigo_processo_seletivo (emp_id, sel_id) VALUES ('$this->empID', '$this->selID')";

        $helper->insert($insert);

    }

    function retornarUltimo() {

        require_once('class_conexao_padrao.php');
        require_once('class_queryHelper.php');

        $conexao = new ConexaoPadrao($database_empresa);
        $conexao = $conexao->conecta();
        $helper = new QueryHelper($conexao);

        $select = "SELECT cod_id as codigo FROM tbl_codigo_processo_seletivo ORDER BY cod_id DESC LIMIT 1";

        $fetch = $helper->select($select, 2);

        return $fetch['codigo'];

    }

    function retornarCodigo() {

        require_once('class_conexao_padrao.php');
        require_once('class_queryHelper.php');

        $conexao = new ConexaoPadrao();
        $conexao = $conexao->conecta();
        $helper = new QueryHelper($conexao);

        $select = "SELECT cod_id as codigo FROM tbl_codigo_processo_seletivo 
        WHERE emp_id = '$this->empID' AND sel_id = '$this->selID'";

        $fetch = $helper->select($select, 2);

        return $fetch['codigo'];

    }

    function retornarDados() {

        require_once('class_conexao_padrao.php');
        require_once('class_queryHelper.php');

        $conexao = new ConexaoPadrao();
        $conexao = $conexao->conecta();
        $helper = new QueryHelper($conexao);

        $select = "SELECT cod_id as codigo, sel_id as sel, emp_id as emp FROM tbl_codigo_processo_seletivo 
        WHERE cod_id = '$this->codigo'";

        $fetch = $helper->select($select, 2);

        $codigo = new CodigoPS();
        $codigo->setEmpID($fetch['emp']);
        $codigo->setCodigo($fetch['codigo']);
        $codigo->setSelID($fetch['sel']);

        return $codigo;

    }

    function isValido() {

        require_once('class_conexao_padrao.php');
        require_once('class_queryHelper.php');

        $conexao = new ConexaoPadrao();
        $conexao = $conexao->conecta();
        $helper = new QueryHelper($conexao);

        $select = "SELECT cod_id as codigo FROM tbl_codigo_processo_seletivo WHERE cod_id = '$this->codigo'";

        $query = $helper->select($select, 1);

        if(mysqli_num_rows($query) == 0) return false;
        else return true;

    }

    function setCodigo($codigo) {
        $this->codigo = $codigo;
    }

    function getCodigo() {
        return $this->codigo;
    }

    function setSelID($selID) {
        $this->selID = $selID;
    }

    function getSelID() {
        return $this->selID;
    }

    function setEmpID($empID) {
        $this->empID = $empID;
    }

    function getEmpID() {
        return $this->empID;
    }

}

?>