<?php
 
class Colaborador {

    private $cpf;
    private $cpfFormatado;
    private $primeiroNome;
    private $nomeCompleto;
    private $cargo;
    private $telefone;
    private $telefoneProfissional;
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
    private $IDInterno;
    private $IDUser;
    private $ativo;
    private $dataCadastro;
    private $dataAlteracao;
    private $importado;
    private $foto;

    function cadastrar($database_empresa) {

        require_once('class_conexao_empresa.php');
        require_once('class_queryHelper.php');

        $conexao = new ConexaoEmpresa($database_empresa);
        $conexao = $conexao->conecta();
        $this->cpf = $this->reduzirCpf($this->cpf);
        if($this->colaboradorExiste($this->cpf, $conexao)) {
            echo '<br>CPF já cadastrado';
            return false;
        }
        if(!$this->isCpfValido($this->cpf)) {
            echo '<br>CPF inválido';
            return false;
        }

        $helper = new QueryHelper($conexao);

        $insert = "INSERT INTO tbl_colaborador 
        (col_cpf, col_primeiro_nome, col_nome_completo, col_cargo, col_telefone, col_telefone_profissional, col_data_nascimento,
        col_cep, col_endereco, col_numero, col_bairro, col_cidade, col_rg, col_cnh, col_cnh_tipo,
        col_ctps, col_nis, col_tipo_sanguineo, col_deficiencia, col_plano_medico, col_hipertenso,
        col_diabetico, col_cartao_sus, col_alergias, col_medicamentos, col_filhos,col_estado_civil,
        col_formacao, col_apresentacao, col_id_interno, usu_id, ges_importado, col_foto, col_sexo) VALUES 
        ('$this->cpf', '$this->primeiroNome', '$this->nomeCompleto', '$this->cargo', '$this->telefone',
        '$this->telefoneProfissional',
        '$this->dataNascimento', '$this->cep', '$this->endereco', '$this->numero', '$this->bairro',
        '$this->cidade', '$this->rg', '$this->cnh', '$this->tipoCnh', '$this->ctps', '$this->nis',
        '$this->tipoSanguineo', '$this->deficiencia', '$this->planoMedico', '$this->hipertenso',
        '$this->diabetico', '$this->cartaoSus', '$this->alergias', '$this->medicamentos','$this->filhos',
        '$this->estadoCivil', '$this->formacao', '$this->apresentacao', '$this->IDInterno', 
        '$this->IDUser', 0, '$this->foto', '$this->sexo')";

        if($helper->insert($insert)) {
            echo '<br>Cadastrado com sucesso';
            return true;
        }
        else {
            echo 'Erro: '.mysqli_error($conexao);
            return false;
        }
    }

    function importarGestor($database_empresa, $gestorCpf) { 

        require_once("class_conexao_empresa.php");
        require_once("class_queryHelper.php");
        require_once("class_gestor.php");

        $gestor = new Gestor();
        $conexao = new ConexaoEmpresa($database_empresa);
        $conn = $conexao->conecta();
        $helper = new QueryHelper($conn);

        $gestorCpf = $this->reduzirCpf($gestorCpf);
        $gestor->setCpf($gestorCpf);
        $gestor = $gestor->retornarGestor($database_empresa);
        $primeiroNome = $gestor->getPrimeiroNome();
        $nomeCompleto = $gestor->getNomeCompleto();
        $cargo = $gestor->getCargo();
        $telefone = $gestor->getTelefone();
        $telefoneProfissional = $gestor->getTelefoneProfissional();
        $dataNascimento = $gestor->getDataNascimento_format();
        $cep = $gestor->getCep();
        $endereco = $gestor->getEndereco();
        $numero = $gestor->getNumero();
        $bairro = $gestor->getBairro();
        $cidade = $gestor->getCidade();
        $ctps = $gestor->getCtps();
        $nis = $gestor->getNis();
        $planoMedico = $gestor->getPlanoMedico();
        $tipoSanguineo = $gestor->getTipoSanguineo();
        $deficiencia = $gestor->getDeficiencia();
        $cartaoSus = $gestor->getCartaoSus();
        $medicamentos = $gestor->getMedicamentos();
        $alergias = $gestor->getAlergias();
        $rg = $gestor->getRg();
        $cnh = $gestor->getCnh();
        $tipoCnh = $gestor->getTipoCnh();
        $filhos = $gestor->getFilhos();
        $estadoCivil = $gestor->getEstadoCivil();
        $formacao = $gestor->getFormacao();
        $apresentacao = $gestor->getApresentacao();
        $IDInterno = $gestor->getIDInterno();
        $usu_id = $gestor->getIDUser();
        $foto = $gestor->getFoto();
        $sexo = $gestor->getSexo();

        $insert = "INSERT INTO tbl_colaborador (col_cpf, col_primeiro_nome, col_nome_completo, col_cargo, col_telefone, col_telefone_profissional, 
        col_data_nascimento, col_cep, col_endereco, col_numero, col_bairro, col_cidade, col_rg, col_cnh, col_cnh_tipo,
        col_ctps, col_nis, col_tipo_sanguineo, col_deficiencia, col_plano_medico, col_hipertenso,
        col_diabetico, col_cartao_sus, col_alergias, col_medicamentos, col_filhos,col_estado_civil,
        col_formacao, col_apresentacao, col_id_interno, usu_id, ges_importado, col_foto, col_sexo) VALUES ('$gestorCpf', '$primeiroNome', '$nomeCompleto', 
        '$cargo', '$telefone', '$telefoneProfissional', '$dataNascimento', '$cep', '$endereco', '$numero', '$bairro', '$cidade', '$rg', '$cnh',
        '$tipoCnh', '$ctps', '$nis', '$tipoSanguineo', '$deficiencia', '$planoMedico', '$hipertenso', '$diabetico',
        '$cartaoSus', '$alergias', '$medicamentos', '$filhos', '$estadoCivil', '$formacao', '$apresentacao', '$IDInterno', '$usu_id', 1, '$foto', '$sexo')";

        $helper->insert($insert);

    }

    function atualizar($database_empresa) {

        require_once('class_conexao_empresa.php');
        require_once('class_queryHelper.php');

        $conexao = new ConexaoEmpresa($database_empresa);
        $conexao = $conexao->conecta();
        $helper = new QueryHelper($conexao);

        $update = "UPDATE tbl_colaborador SET col_primeiro_nome = '$this->primeiroNome', col_nome_completo = '$this->nomeCompleto', 
        col_cargo = '$this->cargo', col_telefone = '$this->telefone', col_telefone_profissional = '$this->telefoneProfissional', col_cep = '$this->cep', 
        col_endereco = '$this->endereco', col_numero = '$this->numero', col_bairro = '$this->bairro',
        col_cidade = '$this->cidade', col_rg = '$this->rg', col_cnh = '$this->cnh', col_cnh_tipo = '$this->tipoCnh',
        col_ctps = '$this->ctps', col_nis = '$this->nis', col_tipo_sanguineo = '$tipo->tipoSanguineo',
        col_deficiencia = '$this->deficiencia', col_plano_medico = '$this->planoMedico', col_hipertenso = '$this->hipertenso',
        col_diabetico = '$this->diabetico', col_cartao_sus = '$this->cartaoSus', col_alergias = '$this->alergias',
        col_medicamentos = '$this->medicamentos', col_filhos = '$this->filhos', col_estado_civil = '$this->estadoCivil',
        col_data_nascimento = '$this->dataNascimento', col_id_interno = '$this->IDInterno', col_sexo = '$this->sexo',
        col_formacao = '$this->formacao', col_apresentacao = '$this->apresentacao', col_data_alteracao = NOW(), col_foto = '$this->foto' 
        WHERE col_cpf = '$this->cpf'";

        if($helper->update($update)) {
            if($this->isImportado($database_empresa)) {
                $update = "UPDATE tbl_gestor SET ges_foto = '$this->foto' WHERE ges_cpf = '$this->cpf'";
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
        //DELETANDO AVALIAÇÕES
        $delete = "DELETE FROM tbl_avaliacao WHERE col_cpf = '$this->cpf'";
        $helper->delete($delete);

        $delete = "DELETE FROM tbl_autoavaliacao WHERE col_cpf = '$this->cpf'";
        $helper->delete($delete);
        //

        $col = new Colaborador();
        $col->setCpf($this->cpf);
        $col = $col->retornarColaborador($database_empresa);
        $usu_id = $col->getIDUser();

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
        
        $delete = "DELETE FROM tbl_gestor WHERE ges_cpf = '$this->cpf'";
        $helper->delete($delete);

        $delete = "DELETE FROM tbl_colaborador WHERE col_cpf = '$this->cpf'";

        if($helper->delete($delete)) {
            echo 'Deletado com sucesso';
            return true;
        } else {
            echo 'Erro ao deletar';
            return false;
        }

    }

    function desativarColaborador($database_empresa) {

        require_once('class_conexao_empresa.php');
        require_once('class_queryHelper.php');

        $conexao = new ConexaoEmpresa($database_empresa);
        $conexao = $conexao->conecta();
        $helper = new QueryHelper($conexao);

        $delete = "DELETE FROM tbl_setor_funcionario WHERE col_cpf = '$this->cpf'";
        $helper->delete($delete);

        $update = "UPDATE tbl_colaborador SET col_ativo = 0 WHERE col_cpf = '$this->cpf'";

        if($helper->update($update)) {
            return true;
        } else {
            return false;
        }

    }

    function reativarColaborador($database_empresa) {

        require_once('class_conexao_empresa.php');
        require_once('class_queryHelper.php');

        $conexao = new ConexaoEmpresa($database_empresa);
        $conexao = $conexao->conecta();
        $helper = new QueryHelper($conexao);

        $update = "UPDATE tbl_colaborador SET col_ativo = 1 WHERE col_cpf = '$this->cpf'";

        if($helper->update($update)) {
            return true;
        } else {
            return false;
        }

    }

    function retornarColaborador($database_empresa) { 

        require_once('class_conexao_empresa.php');
        require_once('class_queryHelper.php');

        $conexao = new ConexaoEmpresa($database_empresa);
        $conexao = $conexao->conecta();
        $helper = new QueryHelper($conexao);

        $select = "SELECT col_cpf as cpf, col_primeiro_nome as nome, col_nome_completo as nomeC, col_cargo as cargo, 
        col_telefone as telefone, col_telefone_profissional as telefoneP, col_cep as cep, col_endereco as endereco, col_numero as numero, col_bairro as bairro,
        col_cidade as cidade, col_rg as rg, col_cnh as cnh, col_cnh_tipo as tipo_cnh, col_ctps as ctps, col_nis as nis,
        col_plano_medico as plano_medico, col_cartao_sus as cartao_sus, col_tipo_sanguineo as tipo_sanguineo,
        col_diabetico as diabetico, col_hipertenso as hipertenso, col_alergias as alergias, col_medicamentos as medicamentos,
        col_deficiencia as deficiencia, col_filhos as filhos, col_estado_civil as estado_civil, 
        usu_id as IDUser, DATE_FORMAT(col_data_cadastro, '%d/%m/%Y %H:%i:%s') as cadastro, col_ativo as ativo, 
        DATE_FORMAT(col_data_nascimento, '%d/%m/%Y') as nascimento, col_id_interno as idInterno,
        DATE_FORMAT(col_data_nascimento, '%Y-%m-%d') as nascimento_format, col_foto as foto, col_sexo as sexo,
        DATE_FORMAT(col_data_alteracao, '%d/%m/%Y %H:%i:%s') as alteracao, ges_importado as importado 
        FROM tbl_colaborador WHERE col_cpf = '$this->cpf'";

        $fetch = $helper->select($select, 2);

        $colaborador = new Colaborador();
        $colaborador->setCpf($fetch['cpf']);
        $colaborador->setCpfFormatado($fetch['cpf']);
        $colaborador->setPrimeiroNome($fetch['nome']);
        $colaborador->setNomeCompleto($fetch['nomeC']);
        $colaborador->setDataNascimento($fetch['nascimento']);
        $colaborador->setDataNascimento_format($fetch['nascimento_format']);
        $colaborador->setCargo($fetch['cargo']);
        $colaborador->setTelefone($fetch['telefone']);
        $colaborador->setTelefoneProfissional($fetch['telefoneP']);
            $fetch['cep'] == "" ? $fetch['cep'] = "Não informado" : $fetch['cep'] = $fetch['cep'];
        $colaborador->setCep($fetch['cep']);
            $fetch['endereco'] == "" ? $fetch['endereco'] = "Não informado" : $fetch['endereco'] = $fetch['endereco'];
        $colaborador->setEndereco($fetch['endereco']);
        $colaborador->setNumero($fetch['numero']);
        $colaborador->setBairro($fetch['bairro']);
        $colaborador->setCidade($fetch['cidade']);
            $fetch['rg'] == "" ? $fetch['rg'] = "Não informado" : $fetch['rg'] = $fetch['rg'];
        $colaborador->setRg($fetch['rg']);
        $colaborador->setCnh($fetch['cnh']);
        $colaborador->setTipoCnh($fetch['tipo_cnh']);
        $colaborador->setCtps($fetch['ctps']);
        $colaborador->setNis($fetch['nis']);
        $colaborador->setPlanoMedico($fetch['plano_medico']);
        $colaborador->setCartaoSus($fetch['cartao_sus']);
        $colaborador->setTipoSanguineo($fetch['tipo_sanguineo']);
        $colaborador->setDiabetico($fetch['diabetico']);
        $colaborador->setHipertenso($fetch['hipertenso']);
        $colaborador->setAlergias($fetch['alergias']);
        $colaborador->setDeficiencia($fetch['deficiencia']);
        $colaborador->setMedicamentos($fetch['medicamentos']);
        $colaborador->setFilhos($fetch['filhos']);
        $colaborador->setEstadoCivil($fetch['estado_civil']);
        $colaborador->setIDInterno($fetch['idInterno']);
        $colaborador->setIDUser($fetch['IDUser']);
        $colaborador->setDataCadastro($fetch['cadastro']);
        $colaborador->setDataAlteracao($fetch['alteracao']);
        $colaborador->setImportado($fetch['importado']);
        $colaborador->setAtivo($fetch['ativo']);
        $colaborador->setFoto($fetch['foto']);
        $colaborador->setSexo($fetch['sexo']);

        return $colaborador;

    }

    function existeColaborador($database_empresa, $cpf) {
        require_once('class_conexao_empresa.php');
        require_once('class_queryHelper.php');

        $conexao = new ConexaoEmpresa($database_empresa);
        $conexao = $conexao->conecta();
        $helper = new QueryHelper($conexao);

        $select = "SELECT col_cpf as cpf FROM tbl_colaborador WHERE col_cpf = '$cpf'";
        $query = $helper->select($select, 1);

        if(mysqli_num_rows($query) == 0) return false;
        else return true;
    }

    function popularSelect($database_empresa, $documento = false) {

        require_once('class_conexao_empresa.php');
        require_once('class_queryHelper.php');

        $conexao = new ConexaoEmpresa($database_empresa);
        $conexao = $conexao->conecta();
        $helper = new QueryHelper($conexao);

        $select = "SELECT col_cpf as cpf, col_nome_completo as nome, col_id_interno as interno FROM tbl_colaborador WHERE col_ativo = 1 ORDER BY col_nome_completo ASC";

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

        $select = "SELECT col_cpf as cpf, col_nome_completo as nome, col_id_interno as interno FROM tbl_colaborador WHERE col_ativo = 1 ORDER BY col_nome_completo ASC";

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

            echo '<input type="checkbox" id="colaboradores[]" name="colaboradores[]" value='.$f['cpf'].'>'.$f['interno'].' '.strtoupper($f['nome']).$ultimo.'<br>';
        }

    }

    function popularSelectAvaliacao($database_empresa, $ges_cpf, $permissao = "GESTOR-2") {

        require_once('class_conexao_empresa.php');
        require_once('class_queryHelper.php');

        $conexao = new ConexaoEmpresa($database_empresa);
        $conexao = $conexao->conecta();
        $helper = new QueryHelper($conexao);

        if($permissao == "GESTOR-1") {
            $select = "SELECT col_cpf as cpf, col_nome_completo as nome FROM tbl_colaborador WHERE col_ativo = 1 ORDER BY col_nome_completo ASC";
        } else if ($permissao == "GESTOR-2") {
            $select = "SELECT 
                        DISTINCT t1.col_cpf as cpf,
                        t2.col_nome_completo as nome
                       FROM tbl_gestor_funcionario t1 
                        INNER JOIN tbl_colaborador t2
                            ON t2.col_cpf = t1.col_cpf 
                       WHERE t1.ges_cpf = '$ges_cpf'
                       ORDER BY t2.col_nome_completo ASC";
        }

        $query = $helper->select($select, 1);

        while($f = mysqli_fetch_assoc($query)) {
            echo '<option value='.$f['cpf'].'>'.$f['nome'].'</option>';
        }

    }

    function popularSelectAvaliacaoMultiple($database_empresa, $ges_cpf, $permissao = "GESTOR-2") {

        require_once('class_conexao_empresa.php');
        require_once('class_queryHelper.php');

        $conexao = new ConexaoEmpresa($database_empresa);
        $conexao = $conexao->conecta();
        $helper = new QueryHelper($conexao);

        if($permissao == "GESTOR-1") {
            $select = "SELECT col_cpf as cpf, col_nome_completo as nome FROM tbl_colaborador WHERE col_ativo = 1 ORDER BY col_nome_completo ASC";
        } else if ($permissao == "GESTOR-2") {
            $select = "SELECT 
                        DISTINCT t1.col_cpf as cpf,
                        t2.col_nome_completo as nome
                       FROM tbl_gestor_funcionario t1 
                        INNER JOIN tbl_colaborador t2
                            ON t2.col_cpf = t1.col_cpf 
                       WHERE t1.ges_cpf = '$ges_cpf'
                       ORDER BY t2.col_nome_completo ASC";
        }

        $query = $helper->select($select, 1);

        while($f = mysqli_fetch_assoc($query)) {
            echo '<input type="checkbox" id="colaboradores[]" name="colaboradores[]" value='.$f['cpf'].'> '.strtoupper($f['nome']).'<br>';
        }

    }

    function isImportado($database_empresa) {
        require_once('class_conexao_empresa.php');
        require_once('class_queryHelper.php');

        $conexao = new ConexaoEmpresa($database_empresa);
        $conexao = $conexao->conecta();
        $helper = new QueryHelper($conexao);

        $select = "SELECT ges_cpf as cpf FROM tbl_gestor WHERE ges_cpf = '$this->cpf'";
        $query = $helper->select($select, 1);

        if(mysqli_num_rows($query) == 0) return false;
        else return true;
    }

    function colaboradorExiste($cpf, $conexao) {

        require_once('class_queryHelper.php');

        $helper = new QueryHelper($conexao);

        $select = "SELECT col_cpf FROM tbl_colaborador WHERE col_cpf = '$cpf'";

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

    // function cryptCpf($cpf) {

    //     $cpf = str_replace('0', '*', $cpf);
	// 	$cpf = str_replace('1', 'L', $cpf);
	// 	$cpf = str_replace('2', 'A', $cpf);
	// 	$cpf = str_replace('3', '!', $cpf);
	// 	$cpf = str_replace('4', 'P', $cpf);
	// 	$cpf = str_replace('5', 'G', $cpf);
	// 	$cpf = str_replace('6', '@', $cpf);
	// 	$cpf = str_replace('7', '%', $cpf);
	// 	$cpf = str_replace('8', '=', $cpf);
    //     $cpf = str_replace('9', 'Z', $cpf);
        
    //     return $cpf;
	
    // }

    // function decryptCpf($cpf) {

    //     $cpf = str_replace('0', '*', $cpf);
	// 	$cpf = str_replace('1', 'L', $cpf);
	// 	$cpf = str_replace('2', 'A', $cpf);
	// 	$cpf = str_replace('3', '!', $cpf);
	// 	$cpf = str_replace('4', 'P', $cpf);
	// 	$cpf = str_replace('5', 'G', $cpf);
	// 	$cpf = str_replace('6', '@', $cpf);
	// 	$cpf = str_replace('7', '%', $cpf);
	// 	$cpf = str_replace('8', '=', $cpf);
    //     $cpf = str_replace('9', 'Z', $cpf);
        
    //     return $cpf;
	
    // }

    function setCpf($cpf) {
        $this->cpf = $cpf;
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

    function setTelefone($telefone) {
        $this->telefone = $telefone;
    }

    function getTelefone() {
        return $this->telefone;
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

    function setImportado($importado) {
        $this->importado = $importado;
    }

    function getImportado() {
        return $this->importado;
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
     * Get the value of ativo
     */ 
    public function getAtivo()
    {
        return $this->ativo;
    }

    /**
     * Set the value of ativo
     *
     * @return  self
     */ 
    public function setAtivo($ativo)
    {
        $this->ativo = $ativo;

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