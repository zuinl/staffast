<?php

class PDI {

    private $ID;
    private $dataCriacao;
    private $prazo;
    private $titulo;
    private $cpf;
    private $cpfGestor;
    private $dono;
    private $orientador;
    private $status;

    function cadastrar($database_empresa) {

        require_once('class_conexao_empresa.php');
        require_once('class_queryHelper.php');
        require_once('class_codigoPS.php');

        $conexao = new ConexaoEmpresa($database_empresa);
        $conexao = $conexao->conecta();
        $helper = new QueryHelper($conexao);

        $insert = "INSERT INTO tbl_pdi (pdi_titulo, pdi_prazo, pdi_cpf, ges_cpf) 
        VALUES ('$this->titulo', '$this->prazo', '$this->cpf', '$this->cpfGestor')";

        $sucesso = $helper->insert($insert);
        
        if($sucesso) {
            echo '<br>Cadastrado com sucesso';
            return true;
        }
        else {
            echo 'Erro: '.mysqli_error($conexao);
            return false;
        }
    }

    function cadastrarCompetencia($database_empresa, $pdi_id, $competencia) {

        require_once('class_conexao_empresa.php');
        require_once('class_queryHelper.php');
        require_once('class_codigoPS.php');

        $conexao = new ConexaoEmpresa($database_empresa);
        $conexao = $conexao->conecta();
        $helper = new QueryHelper($conexao);

        $insert = "INSERT INTO tbl_pdi_competencia (pdi_id, descricao) 
        VALUES ('$pdi_id', '$competencia')";

        $sucesso = $helper->insert($insert);
        
        if($sucesso) {
            $select = "SELECT id FROM tbl_pdi_competencia ORDER BY id DESC LIMIT 1";
            $fetch = $helper->select($select, 2);
            return $fetch['id'];
        }
        else {
            return false;
        }
    }

    function cadastrarMeta($database_empresa, $competencia_id, $meta) {

        require_once('class_conexao_empresa.php');
        require_once('class_queryHelper.php');
        require_once('class_codigoPS.php');

        $conexao = new ConexaoEmpresa($database_empresa);
        $conexao = $conexao->conecta();
        $helper = new QueryHelper($conexao);

        $insert = "INSERT INTO tbl_pdi_competencia_meta (cpt_id, descricao) 
        VALUES ('$competencia_id', '$meta')";

        $sucesso = $helper->insert($insert);
        
        if($sucesso) {
            echo '<br>Cadastrado com sucesso';
            return true;
        }
        else {
            echo 'Erro: '.mysqli_error($conexao);
            return false;
        }
    }

    // function atualizar($database_empresa) {

    //     require_once('class_conexao_empresa.php');
    //     require_once('class_queryHelper.php');
    //     require_once('class_codigoPS.php');

    //     $conexao = new ConexaoEmpresa($database_empresa);
    //     $conexao = $conexao->conecta();
    //     $helper = new QueryHelper($conexao);

    //     $update = "UPDATE tbl_processo_seletivo SET sel_titulo = '$this->titulo', sel_descricao = '$this->descricao', 
    //     sel_vagas = '$this->vagas', sel_data_encerramento = '$this->dataEncerramento' WHERE sel_id = '$this->ID'";

    //     if($helper->update($update)) return true;
    //     else return false;

    // }

    function retornarPDI($database_empresa) {

        require_once('class_conexao_empresa.php');
        require_once('class_queryHelper.php');

        $conexao = new ConexaoEmpresa($database_empresa);
        $conexao = $conexao->conecta();
        $helper = new QueryHelper($conexao);

        $select = "SELECT 
                    t1.pdi_id as id, 
                    t1.pdi_status as status,
                    t1.ges_cpf as cpf_orientador, 
                    DATE_FORMAT(t1.pdi_data_criacao, '%d/%m/%Y %H:%i') as criacao, 
                    t1.pdi_titulo as titulo, 
                    DATE_FORMAT(t1.pdi_prazo, '%d/%m/%Y') as prazo, 
                    t1.pdi_cpf as cpf_dono,
                    CASE
                        WHEN t2.col_nome_completo IS NOT NULL THEN t2.col_nome_completo
                        WHEN t3.ges_nome_completo IS NOT NULL THEN t3.ges_nome_completo
                        ELSE 'Não encontrado'
                    END AS dono,
                    CASE
                        WHEN t1.ges_cpf IS NOT NULL AND t1.ges_cpf != 'Nenhum' THEN t4.ges_nome_completo
                        ELSE 'Sem orientador'
                    END as orientador
                   FROM tbl_pdi t1 
                        LEFT JOIN tbl_colaborador t2 
                            ON t2.col_cpf = t1.pdi_cpf OR t2.col_cpf IS NULL
                        LEFT JOIN tbl_gestor t3
                            ON t3.ges_cpf = t1.pdi_cpf OR t3.ges_cpf IS NULL
                        LEFT JOIN tbl_gestor t4
                            ON t4.ges_cpf = t1.ges_cpf 
                   WHERE t1.pdi_id = '$this->ID'";

        $fetch = $helper->select($select, 2);

        $pdi = new PDI();
        $pdi->setID($fetch['id']);
        $pdi->setDataCriacao($fetch['criacao']);
        $pdi->setTitulo($fetch['titulo']);
        $pdi->setPrazo($fetch['prazo']);
        $pdi->setCpfGestor($fetch['cpf_orientador']);
        $pdi->setCpf($fetch['cpf_dono']);
        $pdi->setDono($fetch['dono']);
        $pdi->setOrientador($fetch['orientador']);
        $pdi->setStatus($fetch['status']);

        return $pdi;

    }

    function retornarUltimo($database_empresa) {

        require_once('class_conexao_empresa.php');
        require_once('class_queryHelper.php');

        $conexao = new ConexaoEmpresa($database_empresa);
        $conexao = $conexao->conecta();
        $helper = new QueryHelper($conexao);

        $select = "SELECT pdi_id as id FROM tbl_pdi ORDER BY pdi_id DESC LIMIT 1";

        $fetch = $helper->select($select, 2);

        return $fetch['id'];

    }

    function popularSelect($database_empresa) {

        require_once('class_conexao_empresa.php');
        require_once('class_queryHelper.php');

        $conexao = new ConexaoEmpresa($database_empresa);
        $conexao = $conexao->conecta();
        $helper = new QueryHelper($conexao);

        $select = "SELECT pdi_id as id, pdi_titulo as titulo, 
        FROM tbl_pdi ORDER BY pdi_data_criacao DESC";

        $query = $helper->select($select, 1);

        while($f = mysqli_fetch_assoc($query)) {
            echo '<option value='.$f['id'].'>'.$f['titulo'].'</option>';
        }

    }

    function traduzStatus($status = 3) {
        switch($status) {
            case 0: return 'Cancelado'; break;
            case 1: return 'Concluído'; break;
            case 2: return 'Em andamento'; break;
            case 3: return 'Pendente'; break;
            default: return 'Pendente'; break;
        }
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

    function setTitulo($titulo) {
        $this->titulo = $titulo;
    }

    function getTitulo() {
        return $this->titulo;
    }

    function setCpfGestor($cpf) {
        $this->cpfGestor = $cpf;
    }

    function getCpfGestor() {
        return $this->cpfGestor;
    }

    /**
     * Get the value of prazo
     */ 
    public function getPrazo()
    {
        return $this->prazo;
    }

    /**
     * Set the value of prazo
     *
     * @return  self
     */ 
    public function setPrazo($prazo)
    {
        $this->prazo = $prazo;

        return $this;
    }

    /**
     * Get the value of cpf
     */ 
    public function getCpf()
    {
        return $this->cpf;
    }

    /**
     * Set the value of cpf
     *
     * @return  self
     */ 
    public function setCpf($cpf)
    {
        $this->cpf = $cpf;

        return $this;
    }

    /**
     * Get the value of dono
     */ 
    public function getDono()
    {
        return $this->dono;
    }

    /**
     * Set the value of dono
     *
     * @return  self
     */ 
    public function setDono($dono)
    {
        $this->dono = $dono;

        return $this;
    }

    /**
     * Get the value of orientador
     */ 
    public function getOrientador()
    {
        return $this->orientador;
    }

    /**
     * Set the value of orientador
     *
     * @return  self
     */ 
    public function setOrientador($orientador)
    {
        $this->orientador = $orientador;

        return $this;
    }

    /**
     * Get the value of status
     */ 
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set the value of status
     *
     * @return  self
     */ 
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }
}

?>