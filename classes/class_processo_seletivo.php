<?php

class ProcessoSeletivo {

    private $ID;
    private $dataCriacao;
    private $dataEncerramento;
    private $dataEncerramento_format;
    private $titulo;
    private $vagas;
    private $descricao;
    private $cpfGestor;

    function cadastrar($database_empresa, $emp_id) {

        require_once('class_conexao_empresa.php');
        require_once('class_queryHelper.php');
        require_once('class_codigoPS.php');

        $conexao = new ConexaoEmpresa($database_empresa);
        $conexao = $conexao->conecta();
        $helper = new QueryHelper($conexao);

        $insert = "INSERT INTO tbl_processo_seletivo (sel_data_encerramento, sel_titulo, sel_vagas, sel_descricao, ges_cpf) 
        VALUES ('$this->dataEncerramento', '$this->titulo', '$this->vagas', '$this->descricao', '$this->cpfGestor')";

        $sucesso = $helper->insert($insert);

        $sel_id = $this->retornarUltimo($database_empresa);

        $codigo = new CodigoPS();
        $codigo->setEmpID($emp_id);
        $codigo->setSelID($sel_id);

        $codigo->criar();
        
        if($sucesso) {
            echo '<br>Cadastrado com sucesso';
            return true;
        }
        else {
            echo 'Erro: '.mysqli_error($conexao);
            return false;
        }
    }

    function atualizar($database_empresa) {

        require_once('class_conexao_empresa.php');
        require_once('class_queryHelper.php');
        require_once('class_codigoPS.php');

        $conexao = new ConexaoEmpresa($database_empresa);
        $conexao = $conexao->conecta();
        $helper = new QueryHelper($conexao);

        $update = "UPDATE tbl_processo_seletivo SET sel_titulo = '$this->titulo', sel_descricao = '$this->descricao', 
        sel_vagas = '$this->vagas', sel_data_encerramento = '$this->dataEncerramento' WHERE sel_id = '$this->ID'";

        if($helper->update($update)) return true;
        else return false;

    }

    function fechar($database_empresa) {

        require_once('class_conexao_empresa.php');
        require_once('class_queryHelper.php');

        $conexao = new ConexaoEmpresa($database_empresa);
        $conexao = $conexao->conecta();
        $helper = new QueryHelper($conexao);

        $update = "UPDATE tbl_processo_seletivo SET sel_data_encerramento = NOW() WHERE sel_id = '$this->ID'";

        if($helper->update($update)) {
            echo 'Atualizado com sucesso';
            return true;
        } else {
            echo 'Erro ao atualizar';
            return false;
        }

    }

    function reabrir($database_empresa) {

        require_once('class_conexao_empresa.php');
        require_once('class_queryHelper.php');

        $conexao = new ConexaoEmpresa($database_empresa);
        $conexao = $conexao->conecta();
        $helper = new QueryHelper($conexao);

        $update = "UPDATE tbl_processo_seletivo SET sel_data_encerramento = DATE_ADD(NOW(), INTERVAL 4 DAY) WHERE sel_id = '$this->ID'";

        if($helper->update($update)) {
            echo 'Atualizado com sucesso';
            return true;
        } else {
            echo 'Erro ao atualizar';
            return false;
        }

    }

    function retornarProcessoSeletivo($database_empresa) {

        require_once('class_conexao_empresa.php');
        require_once('class_queryHelper.php');

        $conexao = new ConexaoEmpresa($database_empresa);
        $conexao = $conexao->conecta();
        $helper = new QueryHelper($conexao);

        $select = "SELECT sel_id as id, ges_cpf as cpf, DATE_FORMAT(sel_data_criacao, '%d/%m/%Y %H:%i:%s') as criacao, 
        DATE_FORMAT(sel_data_encerramento, '%d/%m/%Y %H:%i:%s') as encerramento, 
        DATE_FORMAT(sel_data_encerramento, '%Y-%m-%d') as encerramento_format,
        sel_titulo as titulo, sel_vagas as vagas,
        sel_descricao as descricao FROM tbl_processo_seletivo WHERE sel_id = '$this->ID'";

        $fetch = $helper->select($select, 2);

        $ps = new ProcessoSeletivo();
        $ps->setID($fetch['id']);
        $ps->setDataCriacao($fetch['criacao']);
        $ps->setDataEncerramento($fetch['encerramento']);
        $ps->setDataEncerramento_format($fetch['encerramento_format']);
        $ps->setTitulo($fetch['titulo']);
        $ps->setDescricao($fetch['descricao']);
        $ps->setCpfGestor($fetch['cpf']);
        $ps->setVagas($fetch['vagas']);

        return $ps;

    }

    function retornarUltimo($database_empresa) {

        require_once('class_conexao_empresa.php');
        require_once('class_queryHelper.php');

        $conexao = new ConexaoEmpresa($database_empresa);
        $conexao = $conexao->conecta();
        $helper = new QueryHelper($conexao);

        $select = "SELECT sel_id as id FROM tbl_processo_seletivo ORDER BY sel_id DESC LIMIT 1";

        $fetch = $helper->select($select, 2);

        return $fetch['id'];

    }

    function popularSelect($database_empresa) {

        require_once('class_conexao_empresa.php');
        require_once('class_queryHelper.php');

        $conexao = new ConexaoEmpresa($database_empresa);
        $conexao = $conexao->conecta();
        $helper = new QueryHelper($conexao);

        $select = "SELECT sel_id as id, CONCAT(sel_titulo, ' - criado em ', DATE_FORMAT(sel_data_encerramento, '%d/%m/%Y %H:%i:%s')) as titulo, 
        FROM tbl_processo_seletivo ORDER BY sel_data_criacao ASC";

        $query = $helper->select($select, 1);

        while($f = mysqli_fetch_assoc($query)) {
            echo '<option value='.$f['id'].'>'.$f['titulo'].'</option>';
        }

    }

    function isEncerrado($database_empresa) {

        require_once('class_conexao_empresa.php');
        require_once('class_queryHelper.php');

        $conexao = new ConexaoEmpresa($database_empresa);
        $conexao = $conexao->conecta();
        $helper = new QueryHelper($conexao);

        $select = "SELECT sel_data_encerramento FROM tbl_processo_seletivo WHERE sel_id = '$this->ID' 
        AND sel_data_encerramento > NOW()";

        $query = $helper->select($select, 1);

        if(mysqli_num_rows($query) == 0) return true;
        else return false;

    }

    function retornaLink($emp_id) {

        return "localhost/staffast/processos_seletivos/candidatar_me.php?empresa=".$emp_id."&ps=".$this->getID();

    }

    function setID($ID) {
        $this->ID = $ID;
    } 

    function getID() {
        return $this->ID;
    }

    function setDataCriacao($dataCriacao) {
        $this->dataCriacao = $dataCriacao;
    }

    function getDataCriacao() {
        return $this->dataCriacao;
    }

    function setDataEncerramento($dataEncerramento) {
        $this->dataEncerramento = $dataEncerramento;
    }

    function getDataEncerramento() {
        return $this->dataEncerramento;
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

    function setVagas($vagas) {
        $this->vagas = $vagas;
    }

    function getVagas() {
        return $this->vagas;
    }

    function setCpfGestor($cpf) {
        $this->cpfGestor = $cpf;
    }

    function getCpfGestor() {
        return $this->cpfGestor;
    }

    /**
     * Get the value of dataEncerramento_format
     */ 
    public function getDataEncerramento_format()
    {
        return $this->dataEncerramento_format;
    }

    /**
     * Set the value of dataEncerramento_format
     *
     * @return  self
     */ 
    public function setDataEncerramento_format($dataEncerramento_format)
    {
        $this->dataEncerramento_format = $dataEncerramento_format;

        return $this;
    }
}

?>