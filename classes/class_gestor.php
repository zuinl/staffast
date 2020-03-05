<?php 

class Gestor {

    private $cpf;
    private $cpfFormatado;
    private $primeiroNome;
    private $nomeCompleto;
    private $dataNascimento;
    private $dataNascimento_format;
    private $sexo;
    private $cep;
    private $endereco;
    private $numero;
    private $bairro;
    private $cidade;
    private $ctps; 
    private $nis; 
    private $planoMedico; 
    private $tipoSanguineo;  
    private $deficiencia; 
    private $hipertenso;
    private $diabetico; 
    private $cartaoSus;
    private $alergias;
    private $medicamentos;
    private $rg;
    private $cnh;
    private $tipoCnh;
    private $filhos;
    private $estadoCivil;
    private $formacao;
    private $apresentacao;
    private $cargo;
    private $linkedin;
    private $telefone;
    private $telefoneProfissional;
    private $ramal;
    private $IDInterno;
    private $IDUser;
    private $dataCadastro;
    private $dataAlteracao;
    private $ativo;
    private $tipo;
    private $foto;

    function cadastrar($database_empresa) {

        require_once('class_conexao_empresa.php');
        require_once('class_queryHelper.php');

        $conexao = new ConexaoEmpresa($database_empresa);
        $conexao = $conexao->conecta();
        $this->cpf = $this->reduzirCpf($this->cpf);
        if($this->gestorExiste($this->cpf, $conexao)) {
            echo '<br>CPF já cadastrado';
            return false;
        }
        if(!$this->isCpfValido($this->cpf)) {
            echo '<br>CPF inválido';
            return false;
        }

        $helper = new QueryHelper($conexao);

        $insert = "INSERT INTO tbl_gestor (ges_cpf, ges_primeiro_nome, ges_nome_completo, 
        ges_cargo, ges_linkedin, ges_telefone, ges_telefone_profissional, ges_ramal, ges_data_nascimento,
        ges_cep, ges_endereco, ges_numero, ges_bairro, ges_cidade, ges_sexo,
        ges_ctps, ges_nis, ges_plano_medico, ges_tipo_sanguineo, ges_deficiencia, ges_rg, ges_cnh,
        ges_cnh_tipo, ges_filhos, ges_estado_civil, ges_formacao, ges_apresentacao, ges_tipo,
        ges_cartao_sus, ges_alergias, ges_medicamentos, ges_hipertenso, ges_diabetico, ges_id_interno,
        usu_id, ges_foto) VALUES ('$this->cpf', '$this->primeiroNome', '$this->nomeCompleto', 
        '$this->cargo', '$this->linkedin', '$this->telefone', '$this->telefoneProfissional', '$this->ramal', '$this->dataNascimento',
        '$this->cep', '$this->endereco', '$this->numero', '$this->bairro', '$this->cidade', '$this->sexo',
        '$this->ctps', '$this->nis', '$this->planoMedico', '$this->tipoSanguineo', '$this->deficiencia',
        '$this->rg', '$this->cnh', '$this->tipoCnh', '$this->filhos', '$this->estadoCivil',
        '$this->formacao', '$this->apresentacao', '$this->tipo', 
        '$this->cartaoSus', '$this->alergias', '$this->medicamentos', '$this->hipertenso', '$this->diabetico',
        '$this->IDInterno', '$this->IDUser', '$this->foto')";

        if($helper->insert($insert)) return true;
        else return false;

    }

    function atualizar($database_empresa) {

        require_once('class_conexao_empresa.php');
        require_once('class_queryHelper.php');

        $conexao = new ConexaoEmpresa($database_empresa);
        $conexao = $conexao->conecta();
        $helper = new QueryHelper($conexao);

        $update = "UPDATE tbl_gestor SET ges_primeiro_nome = '$this->primeiroNome', ges_nome_completo = '$this->nomeCompleto', 
        ges_cargo = '$this->cargo', ges_linkedin = '$this->linkedin', ges_telefone = '$this->telefone', 
        ges_telefone_profissional = '$this->telefoneProfissional', ges_ramal = '$this->ramal',
        ges_cep = '$this->cep', ges_endereco = '$this->endereco', ges_numero = '$this->numero', ges_bairro = '$this->bairro',
        ges_cidade = '$this->cidade', ges_ctps = '$this->ctps', ges_nis = '$this->nis', ges_plano_medico = '$this->planoMedico',
        ges_tipo_sanguineo = '$this->tipoSanguineo', ges_deficiencia = '$this->deficiencia',
        ges_rg = '$this->rg', ges_cnh = '$this->cnh', ges_cnh_tipo = '$this->tipoCnh', ges_tipo = '$this->tipo',
        ges_filhos = '$this->filhos', ges_estado_civil = '$this->estadoCivil', ges_formacao = '$this->formacao', 
        ges_apresentacao = '$this->apresentacao', ges_data_nascimento = '$this->dataNascimento',
        ges_alergias = '$this->alergias', ges_cartao_sus = '$this->cartaoSus', ges_medicamentos = '$this->medicamentos',
        ges_hipertenso = '$this->hipertenso', ges_diabetico = '$this->diabetico', ges_id_interno = '$this->IDInterno',  
        ges_data_alteracao = NOW(), ges_foto = '$this->foto', ges_sexo = '$this->sexo' WHERE ges_cpf = '$this->cpf'";

        if($helper->update($update)) {
            if($this->isImportado($database_empresa)) {
                $update = "UPDATE tbl_colaborador SET col_foto = '$this->foto' WHERE col_cpf = '$this->cpf'";
                $helper->update($update);
            }
            return true;
        } else {
            return false;
        }

    }

    function deletar($database_empresa) {

        require_once('class_conexao_empresa.php');
        require_once('class_conexao_padrao.php');
        require_once('class_queryHelper.php');

        $conexao = new ConexaoEmpresa($database_empresa);
        $conexao = $conexao->conecta();
        $helper = new QueryHelper($conexao);

        $ges = new Gestor();
        $ges->setCpf($this->cpf);
        $ges = $ges->retornarGestor($database_empresa);
        $usu_id = $ges->getIDUser();

        $conexao_p = new ConexaoPadrao();
        $conexao_p = $conexao_p->conecta();
        $helper_p = new QueryHelper($conexao_p);

        //DELETANDO USUÁRIO
        $delete = "DELETE FROM tbl_log_alteracao WHERE usu_id = '$usu_id'";
        $helper_p->delete($delete);

        $delete = "DELETE FROM tbl_acesso WHERE usu_id = '$usu_id'";
        $helper_p->delete($delete);

        $delete = "DELETE FROM tbl_usuario WHERE usu_id = '$usu_id'";
        $helper_p->delete($delete);
        //
        //DELETANDO COLABORADOR
        $delete = "DELETE FROM tbl_setor_funcionario WHERE col_cpf = '$this->cpf' OR ges_cpf = '$this->cpf'";
        $helper->delete($delete);

        $delete = "DELETE FROM tbl_avaliacao WHERE col_cpf = '$this->cpf'";
        $helper->delete($delete);

        $delete = "DELETE FROM tbl_autoavaliacao WHERE col_cpf = '$this->cpf'";
        $helper->delete($delete);

        $delete = "DELETE FROM tbl_colaborador WHERE col_cpf = '$this->cpf'";
        $helper->delete($delete);

        $delete = "DELETE FROM tbl_gestor WHERE ges_cpf = '$this->cpf'";

        if($helper->delete($delete)) {
            echo 'Deletado com sucesso';
            return true;
        } else {
            echo 'Erro ao deletar';
            return false;
        }

    }

    function retornarGestor($database_empresa) {

        require_once('class_conexao_empresa.php');
        require_once('class_queryHelper.php');

        $conexao = new ConexaoEmpresa($database_empresa);
        $conexao = $conexao->conecta();
        $helper = new QueryHelper($conexao);

        $select = "SELECT ges_cpf as cpf, ges_primeiro_nome as nome, ges_nome_completo as nomeC, ges_cargo as cargo, 
        ges_linkedin as linkedin, ges_telefone as telefone, ges_telefone_profissional as telefoneP, ges_ramal as ramal, usu_id as IDUser, ges_ativo as ativo,
        ges_cep as cep, ges_endereco as endereco, ges_numero as numero, ges_bairro as bairro, ges_cidade as cidade,
        ges_ctps as ctps, ges_nis as nis, ges_plano_medico as plano_medico, ges_tipo_sanguineo as tipo_sanguineo, ges_deficiencia as deficiencia,
        ges_rg as rg, ges_cnh as cnh, ges_cnh_tipo as tipo_cnh, ges_filhos as filhos, ges_estado_civil as estado_civil,
        ges_cartao_sus as cartao_sus, ges_alergias as alergias, ges_hipertenso as hipertenso, ges_diabetico as diabetico,
        ges_medicamentos as medicamentos, ges_formacao as formacao, ges_apresentacao as apresentacao, 
        DATE_FORMAT(ges_data_nascimento, '%d/%m/%Y') as nascimento, ges_id_interno as interno,
        DATE_FORMAT(ges_data_nascimento, '%Y-%m-%d') as nascimento_format, ges_foto as foto,
        DATE_FORMAT(ges_data_cadastro, '%d/%m/%Y %H:%i:%s') as cadastro, ges_tipo as tipo, ges_sexo as sexo,
        DATE_FORMAT(ges_data_alteracao, '%d/%m/%Y %H:%i:%s')
        as alteracao FROM tbl_gestor WHERE ges_cpf = '$this->cpf'";
        
        $fetch = $helper->select($select, 2);

        if ($fetch['linkedin'] == "") $fetch['linkedin'] = "Não informado";
        if ($fetch['ramal'] == "") $fetch['ramal'] = "Não informado";

        $gestor = new Gestor();
        $gestor->setCpf($fetch['cpf']);
        $gestor->setCpfFormatado($fetch['cpf']);
        $gestor->setPrimeiroNome($fetch['nome']);
        $gestor->setNomeCompleto($fetch['nomeC']);
        $gestor->setDataNascimento($fetch['nascimento']);
        $gestor->setDataNascimento_format($fetch['nascimento_format']);
        $gestor->setCargo($fetch['cargo']);
        $gestor->setCep($fetch['cep']);
        $gestor->setEndereco($fetch['endereco']);
        $gestor->setNumero($fetch['numero']);
        $gestor->setBairro($fetch['bairro']);
        $gestor->setCidade($fetch['cidade']);
            if($fetch['rg'] == "") $fetch['rg'] = 'Não informado';
        $gestor->setRg($fetch['rg']);
            if($fetch['ctps'] == "") $fetch['ctps'] = 'Não informado';
        $gestor->setCtps($fetch['ctps']);
            if($fetch['nis'] == "") $fetch['nis'] = 'Não informado';
        $gestor->setNis($fetch['nis']);
            if($fetch['plano_medico'] == "") $fetch['plano_medico'] = 'Não informado';
        $gestor->setPlanoMedico($fetch['plano_medico']);
        $gestor->setTipoSanguineo($fetch['tipo_sanguineo']);
            if($fetch['deficiencia'] == "") $fetch['deficiencia'] = 'Não informado';
        $gestor->setDeficiencia($fetch['deficiencia']);
            if($fetch['cnh'] == "") $fetch['cnh'] = 'Não informado';
        $gestor->setCnh($fetch['cnh']);
            if($fetch['tipo_cnh'] == "") $fetch['tipo_cnh'] = 'Não informado';
        $gestor->setTipoCnh($fetch['tipo_cnh']);
            if($fetch['filhos'] == "") $fetch['filhos'] = 0;
        $gestor->setFilhos($fetch['filhos']);
            if($fetch['estado_civil'] == "") $fetch['estado_civil'] = 'Não informado';
        $gestor->setEstadoCivil($fetch['estado_civil']);
            if($fetch['formacao'] == "") $fetch['formacao'] = 'Não informado';
        $gestor->setFormacao($fetch['formacao']);
            if($fetch['apresentacao'] == "") $fetch['apresentacao'] = 'Não informado';
        $gestor->setApresentacao($fetch['apresentacao']);
            if($fetch['cartao_sus'] == "") $fetch['cartao_sus'] = 'Não informado';
        $gestor->setCartaoSus($fetch['cartao_sus']);
            if($fetch['hipertenso'] == "") $fetch['hipertenso'] = 0;
        $gestor->setHipertenso($fetch['hipertenso']);
            if($fetch['diabetico'] == "") $fetch['diabetico'] = 0;
        $gestor->setDiabetico($fetch['diabetico']);
            if($fetch['medicamentos'] == "") $fetch['medicamentos'] = 'Não informado';
        $gestor->setMedicamentos($fetch['medicamentos']);
            if($fetch['alergias'] == "") $fetch['alergias'] = 'Não informado';
        $gestor->setAlergias($fetch['alergias']);
            if($fetch['alergias'] == "") $fetch['alergias'] = 'Não informado';
        $gestor->setLinkedin($fetch['linkedin']);
            if($fetch['telefone'] == "") $fetch['telefone'] = 'Não informado';
        $gestor->setTelefone($fetch['telefone']);
        $gestor->setTelefoneProfissional($fetch['telefoneP']);
            if($fetch['ramal'] == "") $fetch['ramal'] = 'Sem ramal';
        $gestor->setRamal($fetch['ramal']);
        $gestor->setIDUser($fetch['IDUser']);
        $gestor->setDataCadastro($fetch['cadastro']);
        $gestor->setDataAlteracao($fetch['alteracao']);
            $fetch['ativo'] == 1 ? $fetch['ativo'] = 'Ativo' : $fetch['ativo'] = 'Inativo';
        $gestor->setAtivo($fetch['ativo']);
        $gestor->setTipo($fetch['tipo']);
        $gestor->setIDInterno($fetch['interno']);
        $gestor->setFoto($fetch['foto']);
        $gestor->setSexo($fetch['sexo']);

        return $gestor;

    }

    function popularSelect($database_empresa, $documento = false) {

        require_once('class_conexao_empresa.php');
        require_once('class_queryHelper.php');

        $conexao = new ConexaoEmpresa($database_empresa);
        $conexao = $conexao->conecta();
        $helper = new QueryHelper($conexao);

        $select = "SELECT ges_cpf as cpf, ges_nome_completo as nome, ges_id_interno as interno FROM tbl_gestor WHERE ges_ativo = 1 ORDER BY ges_nome_completo ASC";

        $query = $helper->select($select, 1);

        while($f = mysqli_fetch_assoc($query)) {
            $ultimo = '';
            if($documento) {
                $cpf = $f['cpf'];
                $select2 = "SELECT DATE_FORMAT(t2.doc_data_upload, '%d/%m/%Y') as data_upload 
                FROM tbl_documento_dono t1 INNER JOIN tbl_documento t2 ON t2.doc_id = t1.doc_id WHERE t1.cpf = '$cpf' 
                AND t2.doc_tipo = 'Holerite' ORDER BY t2.doc_data_upload DESC LIMIT 1";

                $f2 = $helper->select($select2, 2);
                $ultimo = ' - Último holerite: '.$f2['data_upload'];
            }

            echo '<option value='.$f['cpf'].'>'.$f['interno'].' '.strtoupper($f['nome']).$ultimo.'</option>';
        }

    }

    function popularSelectMultiple($database_empresa, $documento = false) {

        require_once('class_conexao_empresa.php');
        require_once('class_queryHelper.php');

        $conexao = new ConexaoEmpresa($database_empresa);
        $conexao = $conexao->conecta();
        $helper = new QueryHelper($conexao);

        $select = "SELECT ges_cpf as cpf, ges_nome_completo as nome, ges_id_interno as interno FROM tbl_gestor WHERE ges_ativo = 1 ORDER BY ges_nome_completo ASC";

        $query = $helper->select($select, 1);

        while($f = mysqli_fetch_assoc($query)) {
            $ultimo = '';
            if($documento) {
                $cpf = $f['cpf'];
                $select2 = "SELECT DATE_FORMAT(t2.doc_data_upload, '%d/%m/%Y') as data_upload 
                FROM tbl_documento_dono t1 INNER JOIN tbl_documento t2 ON t2.doc_id = t1.doc_id WHERE t1.cpf = '$cpf' 
                AND t2.doc_tipo = 'Holerite' ORDER BY t2.doc_data_upload DESC LIMIT 1";

                $f2 = $helper->select($select2, 2);
                $ultimo = ' - Último holerite: '.$f2['data_upload'];
            }

            echo '<input type="checkbox" id="gestores[]" name="gestores[]" value='.$f['cpf'].'>'.$f['interno'].' '.strtoupper($f['nome']).$ultimo.'<br>';
        }

    }

    function desativarGestor($database_empresa) {

        require_once('class_conexao_empresa.php');
        require_once('class_queryHelper.php');

        $conexao = new ConexaoEmpresa($database_empresa);
        $conexao = $conexao->conecta();
        $helper = new QueryHelper($conexao);

        $delete = "DELETE FROM tbl_setor_funcionario WHERE ges_cpf = '$this->cpf'";
        $helper->delete($delete);

        $update = "UPDATE tbl_gestor SET ges_ativo = 0 WHERE ges_cpf = '$this->cpf'";

        $helper->update($update);

    }

    function reativarGestor($database_empresa) {

        require_once('class_conexao_empresa.php');
        require_once('class_queryHelper.php');

        $conexao = new ConexaoEmpresa($database_empresa);
        $conexao = $conexao->conecta();
        $helper = new QueryHelper($conexao);

        $update = "UPDATE tbl_gestor SET ges_ativo = 1 WHERE ges_cpf = '$this->cpf'";

        $helper->update($update);

    }

    function isImportado($database_empresa) {

        require_once('class_conexao_empresa.php');
        require_once('class_queryHelper.php');

        $conexao = new ConexaoEmpresa($database_empresa);
        $conexao = $conexao->conecta();
        $helper = new QueryHelper($conexao);

        $select = "SELECT col_cpf FROM tbl_colaborador WHERE col_cpf = '$this->cpf' AND ges_importado = 1";

        $query = $helper->select($select, 1);

        if(mysqli_num_rows($query) == 0) return false;
        else return true;

    }

    function gestorExiste($cpf, $conexao) {

        require_once('class_queryHelper.php');

        $helper = new QueryHelper($conexao);

        $select = "SELECT ges_cpf FROM tbl_gestor WHERE ges_cpf = '$cpf'";

        $query = $helper->select($select, 1);

        if(mysqli_num_rows($query) == 0) return false;
        else return true;

    }

    function reduzirCpf($cpf) {

        $cpf = str_replace('.', '', $cpf);
        $cpf = str_replace('-', '', $cpf);

        return $cpf;
    }

    function isCpfValido($cpf) {
        
        if(!is_numeric($cpf)) return false;
        else return true;
    
    }

    function setCpf($cpf) {
        $this->cpf = $this->reduzirCpf($cpf);
    } 

    function getCpf() {
        return $this->cpf;
    } 

    function setPrimeiroNome($primeiroNome) {
        $this->primeiroNome = $primeiroNome;
    }

    function getPrimeiroNome() {
        return $this->primeiroNome;
    }

    function setNomeCompleto($nomeCompleto) {
        $this->nomeCompleto = $nomeCompleto;
    }

    function getNomeCompleto() {
        return $this->nomeCompleto;
    }

    function setCargo($cargo) {
        $this->cargo = $cargo;
    }

    function getCargo() {
        return $this->cargo;
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

    function setRamal($ramal) {
        $this->ramal = $ramal;
    }

    function getRamal() {
        return $this->ramal;
    }

    function setIDUser($IDUser) {
        $this->IDUser = $IDUser;
    }

    function getIDUser() {
        return $this->IDUser;
    }

    function setDataCadastro($dataCadastro) {
        $this->dataCadastro = $dataCadastro;
    }

    function getDataCadastro() {
        return $this->dataCadastro;
    }

    function setDataAlteracao($dataAlteracao) {
        $this->dataAlteracao = $dataAlteracao;
    }

    function getDataAlteracao() {
        return $this->dataAlteracao;
    }

    function setAtivo($ativo) {
        $this->ativo = $ativo;
    }

    function getAtivo() {
        return $this->ativo;
    }

    /**
     * Get the value of dataNascimento
     */ 
    public function getDataNascimento()
    {
        return $this->dataNascimento;
    }

    /**
     * Set the value of dataNascimento
     *
     * @return  self
     */ 
    public function setDataNascimento($dataNascimento)
    {
        $this->dataNascimento = $dataNascimento;

        return $this;
    }

    /**
     * Get the value of cep
     */ 
    public function getCep()
    {
        return $this->cep;
    }

    /**
     * Set the value of cep
     *
     * @return  self
     */ 
    public function setCep($cep)
    {
        $this->cep = $cep;

        return $this;
    }

    /**
     * Get the value of endereco
     */ 
    public function getEndereco()
    {
        return $this->endereco;
    }

    /**
     * Set the value of endereco
     *
     * @return  self
     */ 
    public function setEndereco($endereco)
    {
        $this->endereco = $endereco;

        return $this;
    }

    /**
     * Get the value of numero
     */ 
    public function getNumero()
    {
        return $this->numero;
    }

    /**
     * Set the value of numero
     *
     * @return  self
     */ 
    public function setNumero($numero)
    {
        $this->numero = $numero;

        return $this;
    }

    /**
     * Get the value of bairro
     */ 
    public function getBairro()
    {
        return $this->bairro;
    }

    /**
     * Set the value of bairro
     *
     * @return  self
     */ 
    public function setBairro($bairro)
    {
        $this->bairro = $bairro;

        return $this;
    }

    /**
     * Get the value of cidade
     */ 
    public function getCidade()
    {
        return $this->cidade;
    }

    /**
     * Set the value of cidade
     *
     * @return  self
     */ 
    public function setCidade($cidade)
    {
        $this->cidade = $cidade;

        return $this;
    }

    /**
     * Get the value of rg
     */ 
    public function getRg()
    {
        return $this->rg;
    }

    /**
     * Set the value of rg
     *
     * @return  self
     */ 
    public function setRg($rg)
    {
        $this->rg = $rg;

        return $this;
    }

    /**
     * Get the value of cnh
     */ 
    public function getCnh()
    {
        return $this->cnh;
    }

    /**
     * Set the value of cnh
     *
     * @return  self
     */ 
    public function setCnh($cnh)
    {
        $this->cnh = $cnh;

        return $this;
    }

    /**
     * Get the value of tipoCnh
     */ 
    public function getTipoCnh()
    {
        return $this->tipoCnh;
    }

    /**
     * Set the value of tipoCnh
     *
     * @return  self
     */ 
    public function setTipoCnh($tipoCnh)
    {
        $this->tipoCnh = $tipoCnh;

        return $this;
    }

    /**
     * Get the value of filhos
     */ 
    public function getFilhos()
    {
        return $this->filhos;
    }

    /**
     * Set the value of filhos
     *
     * @return  self
     */ 
    public function setFilhos($filhos)
    {
        $this->filhos = $filhos;

        return $this;
    }

    /**
     * Get the value of estadoCivil
     */ 
    public function getEstadoCivil()
    {
        return $this->estadoCivil;
    }

    /**
     * Set the value of estadoCivil
     *
     * @return  self
     */ 
    public function setEstadoCivil($estadoCivil)
    {
        $this->estadoCivil = $estadoCivil;

        return $this;
    }

    /**
     * Get the value of formacao
     */ 
    public function getFormacao()
    {
        return $this->formacao;
    }

    /**
     * Set the value of formacao
     *
     * @return  self
     */ 
    public function setFormacao($formacao)
    {
        $this->formacao = $formacao;

        return $this;
    }

    /**
     * Get the value of apresentacao
     */ 
    public function getApresentacao()
    {
        return $this->apresentacao;
    }

    /**
     * Set the value of apresentacao
     *
     * @return  self
     */ 
    public function setApresentacao($apresentacao)
    {
        $this->apresentacao = $apresentacao;

        return $this;
    }



    /**
     * Get the value of ctps
     */ 
    public function getCtps()
    {
        return $this->ctps;
    }

    /**
     * Set the value of ctps
     *
     * @return  self
     */ 
    public function setCtps($ctps)
    {
        $this->ctps = $ctps;

        return $this;
    }

    /**
     * Get the value of nis
     */ 
    public function getNis()
    {
        return $this->nis;
    }

    /**
     * Set the value of nis
     *
     * @return  self
     */ 
    public function setNis($nis)
    {
        $this->nis = $nis;

        return $this;
    }

    /**
     * Get the value of planoMedico
     */ 
    public function getPlanoMedico()
    {
        return $this->planoMedico;
    }

    /**
     * Set the value of planoMedico
     *
     * @return  self
     */ 
    public function setPlanoMedico($planoMedico)
    {
        $this->planoMedico = $planoMedico;

        return $this;
    }

    /**
     * Get the value of tipoSanguineo
     */ 
    public function getTipoSanguineo()
    {
        return $this->tipoSanguineo;
    }

    /**
     * Set the value of tipoSanguineo
     *
     * @return  self
     */ 
    public function setTipoSanguineo($tipoSanguineo)
    {
        $this->tipoSanguineo = $tipoSanguineo;

        return $this;
    }

    /**
     * Get the value of deficiencia
     */ 
    public function getDeficiencia()
    {
        return $this->deficiencia;
    }

    /**
     * Set the value of deficiencia
     *
     * @return  self
     */ 
    public function setDeficiencia($deficiencia)
    {
        $this->deficiencia = $deficiencia;

        return $this;
    }

    /**
     * Get the value of hipertenso
     */ 
    public function getHipertenso()
    {
        return $this->hipertenso;
    }

    /**
     * Set the value of hipertenso
     *
     * @return  self
     */ 
    public function setHipertenso($hipertenso)
    {
        $this->hipertenso = $hipertenso;

        return $this;
    }

    /**
     * Get the value of diabetico
     */ 
    public function getDiabetico()
    {
        return $this->diabetico;
    }

    /**
     * Set the value of diabetico
     *
     * @return  self
     */ 
    public function setDiabetico($diabetico)
    {
        $this->diabetico = $diabetico;

        return $this;
    }

    /**
     * Get the value of cartaoSus
     */ 
    public function getCartaoSus()
    {
        return $this->cartaoSus;
    }

    /**
     * Set the value of cartaoSus
     *
     * @return  self
     */ 
    public function setCartaoSus($cartaoSus)
    {
        $this->cartaoSus = $cartaoSus;

        return $this;
    }

    /**
     * Get the value of alergias
     */ 
    public function getAlergias()
    {
        return $this->alergias;
    }

    /**
     * Set the value of alergias
     *
     * @return  self
     */ 
    public function setAlergias($alergias)
    {
        $this->alergias = $alergias;

        return $this;
    }

    /**
     * Get the value of medicamentos
     */ 
    public function getMedicamentos()
    {
        return $this->medicamentos;
    }

    /**
     * Set the value of medicamentos
     *
     * @return  self
     */ 
    public function setMedicamentos($medicamentos)
    {
        $this->medicamentos = $medicamentos;

        return $this;
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
     * Get the value of IDInterno
     */ 
    public function getIDInterno()
    {
        return $this->IDInterno;
    }

    /**
     * Set the value of IDInterno
     *
     * @return  self
     */ 
    public function setIDInterno($IDInterno)
    {
        $this->IDInterno = $IDInterno;

        return $this;
    }

    /**
     * Get the value of dataNascimento_format
     */ 
    public function getDataNascimento_format()
    {
        return $this->dataNascimento_format;
    }

    /**
     * Set the value of dataNascimento_format
     *
     * @return  self
     */ 
    public function setDataNascimento_format($dataNascimento_format)
    {
        $this->dataNascimento_format = $dataNascimento_format;

        return $this;
    }

    /**
     * Get the value of telefoneProfissional
     */ 
    public function getTelefoneProfissional()
    {
        return $this->telefoneProfissional;
    }

    /**
     * Set the value of telefoneProfissional
     *
     * @return  self
     */ 
    public function setTelefoneProfissional($telefoneProfissional)
    {
        $this->telefoneProfissional = $telefoneProfissional;

        return $this;
    }

    /**
     * Get the value of cpfFormatado
     */ 
    public function getCpfFormatado()
    {
        return $this->cpfFormatado;
    }

    /**
     * Set the value of cpfFormatado
     *
     * @return  self
     */ 
    public function setCpfFormatado($cpfFormatado)
    {
        $this->cpfFormatado = substr($cpfFormatado, 0, 3).'.'.substr($cpfFormatado, 4, 3).'.'.substr($cpfFormatado, 6, 3).'-'.substr($cpfFormatado, -2);
    }

    /**
     * Get the value of foto
     */ 
    public function getFoto()
    {
        return $this->foto;
    }

    /**
     * Set the value of foto
     *
     * @return  self
     */ 
    public function setFoto($foto)
    {
        $this->foto = $foto;

        return $this;
    }

    /**
     * Get the value of sexo
     */ 
    public function getSexo($traduzir = false)
    {
        if($traduzir) {
            switch($this->sexo) {
                case 'F':
                    return 'Feminino'; break;
                case 'M':
                    return 'Masculino'; break;
                case 'N':
                    return 'Outro/Prefere não declarar'; break;
                default:
                    return 'Não informado'; break;
            }
        } else {
            return $this->sexo;
        }
    }

    /**
     * Set the value of sexo
     *
     * @return  self
     */ 
    public function setSexo($sexo)
    {
        $this->sexo = $sexo;

        return $this;
    }
}

?>