<?php

class Documento {

    private $ID;
    private $titulo;
    private $tipo;
    private $dataUpload;
    private $caminhoArquivo;
    private $cpfGestor;

    function cadastrar($database_empresa) {

        require_once('class_conexao_empresa.php');
        require_once('class_queryHelper.php');

        $conexao = new ConexaoEmpresa($database_empresa);
        $conexao = $conexao->conecta();
        $helper = new QueryHelper($conexao);

        $insert = "INSERT INTO tbl_documento (doc_titulo, doc_tipo, doc_caminho, ges_cpf) 
        VALUES ('$this->titulo', '$this->tipo', '$this->caminhoArquivo', '$this->cpfGestor')";

        if($helper->insert($insert)) {
            echo '<br>Cadastrado com sucesso';
            return true;
        }
        else {
            echo 'Erro: '.mysqli_error($conexao);
            return false;
        }
    }

    function retornarDocumento($database_empresa) {

        require_once('class_conexao_empresa.php');
        require_once('class_queryHelper.php');

        $conexao = new ConexaoEmpresa($database_empresa);
        $conn = $conexao->conecta();
        $helper = new QueryHelper($conn);

        $select = "SELECT doc_id as id, doc_titulo as titulo, doc_tipo as tipo, 
        doc_caminho as caminho, DATE_FORMAT(doc_data_upload, '%d/%m/%Y às %H:%i') as upload, 
        ges_cpf as cpf FROM tbl_documento WHERE doc_id = '$this->ID'";

        $fetch = $helper->select($select, 2);
        $doc = new Documento();
        $doc->setID($fetch['id']);
        $doc->setTitulo($fetch['titulo']);
        $doc->setTipo($fetch['tipo']);
        $doc->setCaminhoArquivo($fetch['caminho']);
        $doc->setDataUpload($fetch['upload']);
        $doc->setCpfGestor($fetch['cpf']);

        return $doc;

    }

    function retornarUltimo($database_empresa) {

        require_once('class_conexao_empresa.php');
        require_once('class_queryHelper.php');

        $conexao = new ConexaoEmpresa($database_empresa);
        $conn = $conexao->conecta();
        $helper = new QueryHelper($conn);

        $select = "SELECT doc_id as id FROM tbl_documento ORDER BY doc_id DESC LIMIT 1";

        $fetch = $helper->select($select, 2);

        return $fetch['id'];

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

    

    /**
     * Get the value of tipo
     */ 
    public function getTipo()
    {
        return $this->tipo;
    }

    /**
     * Set the value of tipo
     *
     * @return  self
     */ 
    public function setTipo($tipo)
    {
        $this->tipo = $tipo;

        return $this;
    }

    /**
     * Get the value of dataUpload
     */ 
    public function getDataUpload()
    {
        return $this->dataUpload;
    }

    /**
     * Set the value of dataUpload
     *
     * @return  self
     */ 
    public function setDataUpload($dataUpload)
    {
        $this->dataUpload = $dataUpload;

        return $this;
    }

    /**
     * Get the value of caminhoArquivo
     */ 
    public function getCaminhoArquivo()
    {
        return $this->caminhoArquivo;
    }

    /**
     * Set the value of caminhoArquivo
     *
     * @return  self
     */ 
    public function setCaminhoArquivo($caminhoArquivo)
    {
        $this->caminhoArquivo = $caminhoArquivo;

        return $this;
    }

    /**
     * Get the value of cpfGestor
     */ 
    public function getCpfGestor()
    {
        return $this->cpfGestor;
    }

    /**
     * Set the value of cpfGestor
     *
     * @return  self
     */ 
    public function setCpfGestor($cpfGestor)
    {
        $this->cpfGestor = $cpfGestor;

        return $this;
    }
}

?>