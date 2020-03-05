<?php

class Candidato {

    private $ID;
    private $nome;
    private $linkedin;
    private $email;
    private $telefone;
    private $apresentacao;
    private $dataCadastro;
    private $curriculo;
    private $selID;

    function cadastrar($database_empresa) {

        require_once('class_conexao_empresa.php');
        require_once('class_queryHelper.php');

        $conexao = new ConexaoEmpresa($database_empresa);
        $conexao = $conexao->conecta();

        $helper = new QueryHelper($conexao);

        $insert = "INSERT INTO tbl_candidato (can_nome, can_linkedin, can_email, can_telefone, can_apresentacao, 
        can_curriculo, sel_id) VALUES ('$this->nome', '$this->linkedin', '$this->email', '$this->telefone', 
        '$this->apresentacao', '$this->curriculo', '$this->selID')";

        if($helper->insert($insert)) {
            echo '<br>Cadastrado com sucesso';
            return true;
        }
        else {
            echo 'Erro: '.mysqli_error($conexao);
            return false;
        }
    }

    function uploadCurriculo($database_empresa) {

        require_once('class_conexao_empresa.php');
        require_once('class_queryHelper.php');

        $conexao = new ConexaoEmpresa($database_empresa);
        $conexao = $conexao->conecta();
        $helper = new QueryHelper($conexao);

        $can_id = $this->retornarUltimo($database_empresa);

        $update = "UPDATE tbl_candidato SET can_curriculo = '$this->curriculo' WHERE can_id = '$can_id'";

        $fetch = $helper->update($update);

    }

    function retornarCandidato($database_empresa) {

        require_once('class_conexao_empresa.php');
        require_once('class_queryHelper.php');

        $conexao = new ConexaoEmpresa($database_empresa);
        $conexao = $conexao->conecta();
        $helper = new QueryHelper($conexao);

        $select = "SELECT can_id as id, can_nome as nome, can_linkedin as linkedin, can_email as email,
        can_telefone as telefone, can_apresentacao as apresentacao, 
        DATE_FORMAT(can_data_cadastro, '%d/%m/%Y %H:%i:%s') as cadastro, 
        can_curriculo as curriculo, sel_id as sel FROM tbl_candidato WHERE can_id = '$this->ID'";

        $fetch = $helper->select($select, 2);

        $candidato = new Candidato();
        $candidato->setID($fetch['id']);
        $candidato->setNome($fetch['nome']);
        $candidato->setApresentacao($fetch['apresentacao']);
        $candidato->setCurriculo($fetch['curriculo']);
        $candidato->setLinkedin($fetch['linkedin']);
        $candidato->setEmail($fetch['email']);
        $candidato->setTelefone($fetch['telefone']);
        $candidato->setDataCadastro($fetch['cadastro']);

        return $candidato;

    }

    function retornarUltimo($database_empresa) {

        require_once('class_conexao_empresa.php');
        require_once('class_queryHelper.php');

        $conexao = new ConexaoEmpresa($database_empresa);
        $conexao = $conexao->conecta();
        $helper = new QueryHelper($conexao);

        $select = "SELECT can_id as id FROM tbl_candidato ORDER BY can_id DESC LIMIT 1";

        $fetch = $helper->select($select, 2);

        return $fetch['id'];

    }

    function popularSelect($database_empresa) {

        require_once('class_conexao_empresa.php');
        require_once('class_queryHelper.php');

        $conexao = new ConexaoEmpresa($database_empresa);
        $conexao = $conexao->conecta();
        $helper = new QueryHelper($conexao);

        $select = "SELECT can_id as id, can_nome as nome FROM tbl_candidato ORDER BY ges_nome ASC";

        $query = $helper->select($select, 1);

        while($f = mysqli_fetch_assoc($query)) {
            echo '<option value='.$f['id'].'>'.$f['nome'].'</option>';
        }

    }

    function setID($ID) {
        $this->ID = $ID;
    } 

    function getID() {
        return $this->ID;
    } 

    function setNome($nome) {
        $this->nome = $nome;
    }

    function getNome() {
        return $this->nome;
    }

    function setNomeCompleto($nomeCompleto) {
        $this->nomeCompleto = $nomeCompleto;
    }

    function setLinkedin($linkedin) {
        $this->linkedin = $linkedin;
    }

    function getLinkedin() {
        return $this->linkedin;
    }

    function setTelefone($telefone) {
        $this->telefone = $telefone;
    }

    function getTelefone() {
        return $this->telefone;
    }

    function setApresentacao($apresentacao) {
        $this->apresentacao = $apresentacao;
    }

    function getApresentacao() {
        return $this->apresentacao;
    }

    function setCurriculo($curriculo) {
        $this->curriculo = $curriculo;
    }

    function getCurriculo() {
        return $this->curriculo;
    }

    function setIDSel($selID) {
        $this->selID = $selID;
    }

    function getIDSel() {
        return $this->IDSel;
    }

    function setDataCadastro($dataCadastro) {
        $this->dataCadastro = $dataCadastro;
    }

    function getDataCadastro() {
        return $this->dataCadastro;
    }

    /**
     * Get the value of email
     */ 
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set the value of email
     *
     * @return  self
     */ 
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }
}

?>