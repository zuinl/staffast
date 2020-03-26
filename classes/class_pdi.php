<?php

class PDI {

    private $ID;
    private $dataCriacao;
    private $prazo;
    private $prazo_format;
    private $titulo;
    private $cpf;
    private $cpfGestor;
    private $dono;
    private $orientador;
    private $status;
    private $arquivado;
    private $publico;

    function cadastrar($database_empresa) {

        require_once('class_conexao_empresa.php');
        require_once('class_queryHelper.php');
        require_once('class_codigoPS.php');

        $conexao = new ConexaoEmpresa($database_empresa);
        $conexao = $conexao->conecta();
        $helper = new QueryHelper($conexao);

        $insert = "INSERT INTO tbl_pdi (pdi_titulo, pdi_prazo, pdi_cpf, ges_cpf) 
        VALUES ('$this->titulo', '$this->prazo', '$this->cpf', '$this->cpfGestor')";
        
        if($helper->insert($insert)) {
            if($this->cpfGestor != "") {
                $this->avisarGestor($database_empresa, $this->cpfGestor);
            }

            return true;
        } else {
            return false;
        } 
    }

    function avisarGestor($database_empresa, $cpf) {
        require_once('class_conexao_empresa.php');
        require_once('class_conexao_padrao.php');
        require_once('class_email.php');
        require_once('class_queryHelper.php');
        require_once('class_codigoPS.php');

        $conexaoE = new ConexaoEmpresa($database_empresa);
        $conexaoE = $conexaoE->conecta();
        $helperE = new QueryHelper($conexaoE);

        $conexaoP = new ConexaoPadrao();
        $conexaoP = $conexaoP->conecta();
        $helperP = new QueryHelper($conexaoP);

        $select = "SELECT usu_id as id, ges_primeiro_nome as nome FROM tbl_gestor WHERE ges_cpf = '$cpf'";
        $fetch_usu = $helperE->select($select, 2);
        $usu_id = $fetch_usu['id'];
        $nome = $fetch_usu['nome'];

        $select = "SELECT usu_email as email FROM tbl_usuario WHERE usu_id = $usu_id";
        $fetch_email = $helperP->select($select, 2);
        $email = $fetch_email['email'];

        $mail = new Email();
        $mail->setEmailTo($email);
        $mail->setAssunto("Orientação no PDI");
        $mail->setMensagem('<h1 class="high-text">Olá, '.$nome.'</h1>
                            <h2 class="text">Um colaborador criou um Plano de Desenvolvimento Individual (PDI) 
                            e te adicionou como orientador(a).</h2>
                            <a href="https://sistemastaffast.com.br" target="_blank"><button class="button button1">Acesse o Staffast e veja</button></a>
                            <h6 class="text">Por enquanto é só :D</h6>
                            <small class="text">Equipe do Staffast</small>');
        $mail->enviar();

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

    function arquivar($database_empresa) {
        require_once('class_conexao_empresa.php');
        require_once('class_queryHelper.php');
        require_once('class_codigoPS.php');

        $conexao = new ConexaoEmpresa($database_empresa);
        $conexao = $conexao->conecta();
        $helper = new QueryHelper($conexao);

        $update = "UPDATE tbl_pdi SET pdi_arquivado = 1 WHERE pdi_id = $this->ID";
        if($helper->update($update)) return true;
        else return false;
    }

    function desarquivar($database_empresa) {
        require_once('class_conexao_empresa.php');
        require_once('class_queryHelper.php');
        require_once('class_codigoPS.php');

        $conexao = new ConexaoEmpresa($database_empresa);
        $conexao = $conexao->conecta();
        $helper = new QueryHelper($conexao);

        $update = "UPDATE tbl_pdi SET pdi_arquivado = 0 WHERE pdi_id = $this->ID";
        if($helper->update($update)) return true;
        else return false;
    }

    function tornarPublico($database_empresa) {
        require_once('class_conexao_empresa.php');
        require_once('class_queryHelper.php');
        require_once('class_codigoPS.php');

        $conexao = new ConexaoEmpresa($database_empresa);
        $conexao = $conexao->conecta();
        $helper = new QueryHelper($conexao);

        $update = "UPDATE tbl_pdi SET pdi_publico = 1 WHERE pdi_id = $this->ID";
        if($helper->update($update)) return true;
        else return false;
    }

    function reverterPublico($database_empresa) {
        require_once('class_conexao_empresa.php');
        require_once('class_queryHelper.php');
        require_once('class_codigoPS.php');

        $conexao = new ConexaoEmpresa($database_empresa);
        $conexao = $conexao->conecta();
        $helper = new QueryHelper($conexao);

        $update = "UPDATE tbl_pdi SET pdi_publico = 0 WHERE pdi_id = $this->ID";
        if($helper->update($update)) return true;
        else return false;
    }

    function atualizar($database_empresa) {

        require_once('class_conexao_empresa.php');
        require_once('class_queryHelper.php');
        require_once('class_codigoPS.php');

        $conexao = new ConexaoEmpresa($database_empresa);
        $conexao = $conexao->conecta();
        $helper = new QueryHelper($conexao);

        $update = "UPDATE tbl_pdi SET pdi_titulo = '$this->titulo', pdi_prazo = '$this->prazo' WHERE pdi_id = $this->ID";

        if($helper->update($update)) return true;
        else return false;

    }

    function retornarPDI($database_empresa) {

        require_once('class_conexao_empresa.php');
        require_once('class_queryHelper.php');

        $conexao = new ConexaoEmpresa($database_empresa);
        $conexao = $conexao->conecta();
        $helper = new QueryHelper($conexao);

        $select = "SELECT 
                    t1.pdi_id as id, 
                    t1.pdi_status as status,
                    t1.pdi_arquivado as arquivado,
                    t1.pdi_publico as publico,
                    t1.ges_cpf as cpf_orientador, 
                    DATE_FORMAT(t1.pdi_data_criacao, '%d/%m/%Y %H:%i') as criacao, 
                    t1.pdi_titulo as titulo, 
                    DATE_FORMAT(t1.pdi_prazo, '%d/%m/%Y') as prazo, 
                    DATE_FORMAT(t1.pdi_prazo, '%Y-%m-%d') as prazo_format,
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
        $pdi->setPrazo_format($fetch['prazo_format']);
        $pdi->setCpfGestor($fetch['cpf_orientador']);
        $pdi->setCpf($fetch['cpf_dono']);
        $pdi->setDono($fetch['dono']);
        $pdi->setOrientador($fetch['orientador']);
        $pdi->setStatus($fetch['status']);
        $pdi->setArquivado($fetch['arquivado']);
        $pdi->setPublico($fetch['publico']);

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
     * Set the value of prazo_format
     *
     * @return  self
     */ 
    public function setPrazo_format($prazo_format)
    {
        $this->prazo_format = $prazo_format;

        return $this;
    }

    /**
     * Get the value of prazo_format
     */ 
    public function getPrazo_format()
    {
        return $this->prazo_format;
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

    /**
     * Get the value of arquivado
     */ 
    public function getArquivado()
    {
        return $this->arquivado;
    }

    /**
     * Set the value of arquivado
     *
     * @return  self
     */ 
    public function setArquivado($arquivado)
    {
        $this->arquivado = $arquivado;

        return $this;
    }

    /**
     * Get the value of publico
     */ 
    public function getPublico()
    {
        return $this->publico;
    }

    /**
     * Set the value of publico
     *
     * @return  self
     */ 
    public function setPublico($publico)
    {
        $this->publico = $publico;

        return $this;
    }
}

?>