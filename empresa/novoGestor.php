<?php
    include('../include/auth.php');
    include('../src/meta.php');
    require_once('../classes/class_gestor.php');

    if($_SESSION['user']['permissao'] != "GESTOR-1" && !isset($_GET['editar'])) {
        include('../include/acessoNegado.php');
        die();
    } else if (isset($_GET['editar'])) {
        if($_SESSION['user']['permissao'] == "GESTOR-2" && $_SESSION['user']['cpf'] != base64_decode($_GET['editar'])){
            include('../include/acessoNegado.php');
            die();
        } else if ($_SESSION['user']['permissao'] == "COLABORADOR") {
            include('../include/acessoNegado.php');
            die();
        }
    }

    $ges = new Gestor();

    $dataNascimento = date_create(date('Y-m-d'));

    if(isset($_GET['editar'])) {
        $cpf = base64_decode($_GET['editar']);
        $ges->setCpf($cpf);
        $ges = $ges->retornarGestor($_SESSION['empresa']['database']);
        $action = "../database/gestor.php?atualiza=true";
        $btnValue = "Salvar alterações";
        $dataNascimento = date_create($ges->getDataNascimento_format());
    } else {
        $action = "../database/gestor.php?novoGestor=true";
        $cpf = "";
        $btnValue = "Cadastrar";
    }
?>
<!DOCTYPE html>
<html>
<head>
    <title>Novo gestor</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
	<script src="https://igorescobar.github.io/jQuery-Mask-Plugin/js/jquery.mask.min.js"></script>  
    <script type="text/javascript">
        $('#cpf').mask('000.000.000-00');
        $('#rg').mask('00.000.000-0');
        $('#telefone').mask('(00) 00000-0000');
        $('#telefoneP').mask('(00) 00000-0000');
    </script>
    <script>
        function meu_callback(conteudo) {
        if (!("erro" in conteudo)) {
            document.getElementById('endereco').value=(conteudo.logradouro);
            document.getElementById('bairro').value=(conteudo.bairro);
            document.getElementById('cidade').value=(conteudo.localidade);
        }
        else {
            //CEP não Encontrado.
            limpa_formulário_cep();
            alert("CEP não encontrado.");
        }
    }
        
    function pesquisacep(valor) {
        var cep = valor.replace(/\D/g, '');
        if (cep != "") {
            var validacep = /^[0-9]{8}$/;
            if(validacep.test(cep)) {
                document.getElementById('endereco').value="...";
                document.getElementById('bairro').value="...";
                document.getElementById('cidade').value="...";

                var script = document.createElement('script');

                script.src = 'https://viacep.com.br/ws/'+ cep + '/json/?callback=meu_callback';

                document.body.appendChild(script);

            } 
            else {
                limpa_formulário_cep();
                alert("Formato de CEP inválido.");
            }
        } 
        else {
            limpa_formulário_cep();
        }
    };

    function limpa_formulário_cep() {
            document.getElementById('endereco').value=("");
            document.getElementById('bairro').value=("");
            document.getElementById('cidade').value=("");
    }

    function infoIDInterno() {
        alert('O ID interno é de uso exclusivo da sua empresa. O Staffast não o usará para nada, mas o exibirá nos menus de seleção e listagens de colaboradores.');
        return;
    }

    function infoPermissao() {
        alert('PERMISSÃO ADMINISTRATIVA - TOTAL \n 1. Acesso completo ao Staffast \n 2. Gerenciamento de gestores e colaboradores \n 3. Gerenciamento de setores \n 4. Gerenciamento de processos seletivos, reuniões, eventos, metas OKR \n 5. Acesso aos Documentos \n 6. Gerenciamento de avaliações de setores e da gestão \n\n PERMISSÃO OPERACIONAL - AVALIAÇÕES \n 1. Acesso limitado ao Staffast \n 2. Gerencia apenas setores que está incluído \n 3. Pode criar reuniões e gerenciar as que criou \n 4. Não pode criar processos seletivos \n 5. Pode controlar as avaliações e avaliar colaboradores dos seus setores \n 6. Não tem acesso a documentos, apenas os seus');
        return;
    }

    function TestaCPF(strCPF) {
        strCPF = strCPF.replace(".", "");
        strCPF = strCPF.replace(".", ""); //não apagar, mesmo repetido
        strCPF = strCPF.replace("-", "");
        var Soma;
        var Resto;
        Soma = 0;
        if (strCPF == "00000000000") {
            document.getElementById('cpf').value = "";
            alert("O CPF inserido é inválido");
            return false;
        }
     
        for (i=1; i<=9; i++) Soma = Soma + parseInt(strCPF.substring(i-1, i)) * (11 - i);
        Resto = (Soma * 10) % 11;
   
        if ((Resto == 10) || (Resto == 11))  Resto = 0;
        if (Resto != parseInt(strCPF.substring(9, 10)) ) {
            document.getElementById('cpf').value = "";
            alert("O CPF inserido é inválido");
            return false;
        }
   
        Soma = 0;
        for (i = 1; i <= 10; i++) Soma = Soma + parseInt(strCPF.substring(i-1, i)) * (12 - i);
        Resto = (Soma * 10) % 11;
   
        if ((Resto == 10) || (Resto == 11))  Resto = 0;
        if (Resto != parseInt(strCPF.substring(10, 11) ) ) {
            document.getElementById('cpf').value = "";
            alert("O CPF inserido é inválido");
            return false;
        }
        return true;
    }
    </script>
</head>
<body>
<?php
    include('../include/navbar.php');
?>
<div class="container-fluid"> 

        <div class="row">
            <div class="col-sm">
                <div class="alert alert-info alert-dismissible fade show" role="alert">
                    <p class="text"><b>DICA RÁPIDA:</b> você não precisa preencher todos os campos do cadastro do(a) Gestor(a) agora. Se 
                    preferir, preencha apenas os campos obrigatórios (aqueles que aparecem com *) e depois o(a) próprio(a) gestor(a) 
                    pode finalizar o cadastro usando o acesso dele(a) ;)</p>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            </div>
		</div>

    <!-- NAV DE CAMINHO DE TELA -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="./">Início</a></li>
            <li class="breadcrumb-item"><a href="gestores.php">Gestores</a></li> 
            <?php if(isset($_GET['editar'])) { ?> 
                <li class="breadcrumb-item"><a href="perfilGestor.php?id=<?php echo base64_encode($ges->getCpf()) ?>"><?php echo $ges->getNomeCompleto(); ?></a></li> 
            <?php } ?>
            <li class="breadcrumb-item active" aria-current="page"><?php if(isset($_GET['editar'])) echo 'Editar gestor'; else echo 'Cadastrar gestor'; ?></li>
        </ol>
    </nav>
    <!-- FIM DA NAV DE CAMINHO DE TELA -->

    <div class="row">
    <?php if(!isset($_GET['editar'])) { ?>
        <div class="col-sm-10 offset-sm-2">
            <h2 class="high-text">Cadastro de <span class="destaque-text">gestor</span></h2>
        </div>
        <?php } else { ?>
        <div class="col-sm-10 offset-sm-2">
            <h2 class="high-text">Atualização de <span class="destaque-text"><?php echo $ges->getPrimeiroNome(); ?></span></h2>
        </div>
        <?php } ?>
    </div>

    <hr class="hr-divide">

    <?php
    if(isset($_SESSION['msg'])) {
        ?>
		<div class="row">
            <div class="col-sm">
                <div class="alert alert-info alert-dismissible fade show" role="alert">
                    <?php echo $_SESSION['msg']; unset($_SESSION['msg']); ?>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            </div>
		</div>
        <?php
    }
    ?>
</div>
<div class="container">

    <div class="row">
        <div class="col-sm">
            <h4 class="high-text">Informações pessoais</h4>
        </div>
    </div>

    <hr class="hr-divide-super-light">

    <div class="row" style="margin-top:0.8em;">
        <div class="col-sm">
        <form method="POST" action="<?php echo $action; ?>" id="form" enctype="multipart/form-data">
            <label for="primeiroNome" class="text">Primeiro nome *</label>
            <input type="text" name="primeiroNome" id="primeiroNome" value="<?php echo $ges->getPrimeiroNome(); ?>" class="all-input" maxlength="20" required="">
        </div>
        <div class="col-sm">
            <label for="sobrenome" class="text"><?php if(isset($_GET['editar'])) { echo 'Nome completo'; } else { echo 'Sobrenome';} ?> *</label>
            <input type="text" name="sobrenome" id="sobrenome" value="<?php echo $ges->getNomeCompleto(); ?>" class="all-input" maxlength="60" required="">
        </div>
        <?php if(!isset($_GET['editar'])) { ?>
        <div class="col-sm">
            <label for="cpf" class="text">CPF *</label>
            <input type="text" name="cpf" id="cpf" value="<?php echo $ges->getCpf(); ?>" class="all-input" maxlength="14" minlength="14" required="" onBlur="TestaCPF(this.value);">
        </div>
        <?php } ?>
        <div class="col-sm">
            <label for="dataNascimento" class="text">Data de nascimento *</label>
            <input type="date" name="dataNascimento" value="<?php echo date_format($dataNascimento,"Y-m-d"); ?>" id="dataNascimento" class="all-input" required="">
        </div>
        <div class="col-sm">
            <label for="telefone" class="text">Telefone</label>
            <input type="text" name="telefone" id="telefone" value="<?php echo $ges->getTelefone(); ?>" class="all-input" maxlength="15">
        </div>
    </div>
    <div class="row" style="margin-top:0.8em;">
        <div class="col-sm">
            <label for="cep" class="text">CEP</label>
            <input type="text" name="cep" id="cep" value="<?php echo $ges->getCep(); ?>" class="all-input" maxlength="9" onblur="pesquisacep(this.value);">
        </div>
        <div class="col-sm">
            <label for="endereco" class="text">Endereço *</label>
            <input type="text" name="endereco" id="endereco" value="<?php echo $ges->getEndereco(); ?>" class="all-input" required>
        </div>
        <div class="col-sm">
            <label for="numero" class="text">Nº *</label>
            <input type="text" name="numero" id="numero" value="<?php echo $ges->getNumero(); ?>" class="all-input" required>
        </div>
    </div>
    <div class="row" style="margin-top:0.8em;">
        <div class="col-sm">
            <label for="bairro" class="text">Bairro *</label>
            <input type="text" name="bairro" id="bairro" value="<?php echo $ges->getBairro(); ?>" class="all-input" required>
        </div>
        <div class="col-sm">
            <label for="cidade" class="text">Cidade *</label>
            <input type="text" name="cidade" id="cidade" value="<?php echo $ges->getCidade(); ?>" class="all-input" required>
        </div> 
    </div>
    <div class="row" style="margin-top:0.8em;">
        <div class="col-sm">
            <label for="rg" class="text">R.G</label>
            <input type="text" name="rg" id="rg" value="<?php echo $ges->getRg(); ?>" class="all-input" maxlength="12">
        </div>
        <div class="col-sm">
            <label for="cnh" class="text">C.N.H</label>
            <input type="text" name="cnh" id="cnh" value="<?php echo $ges->getCnh(); ?>" class="all-input" maxlength="11" minlength="11">
        </div>
        <div class="col-sm">
            <label for="tipoCnh" class="text">Categoria da C.N.H</label>
            <select name="tipoCnh" id="tipoCnh" class="all-input">
                <option value="" selected>-- Selecione --</option>
                <option value="A/B" <?php if($ges->getTipoCnh() == "A/B") echo 'selected'; ?>>A/B</option>
                <option value="B" <?php if($ges->getTipoCnh() == "B") echo 'selected'; ?>>B</option>
                <option value="C" <?php if($ges->getTipoCnh() == "C") echo 'selected'; ?>>C</option>
                <option value="D" <?php if($ges->getTipoCnh() == "D") echo 'selected'; ?>>D</option>
                <option value="E" <?php if($ges->getTipoCnh() == "E") echo 'selected'; ?>>E</option>
            </select>
        </div>
    </div>
    <div class="row" style="margin-top:0.8em;">
        <div class="col-sm">
            <label for="filhos" class="text">Filhos</label>
            <input type="number" name="filhos" id="filhos" value="<?php echo $ges->getFilhos(); ?>" value="0" class="all-input">
        </div>
        <div class="col-sm">
            <label for="estadoCivil" class="text">Estado civil</label>
            <select name="estadoCivil" id="estadoCivil" class="all-input">
                <option value="" selected>-- Selecione --</option>
                <option value="Solteiro(a)" <?php if($ges->getEstadoCivil() == "Solteiro(a)") echo 'selected'; ?>>Solteiro(a)</option>
                <option value="Casado(a)" <?php if($ges->getEstadoCivil() == "Casado(a)") echo 'selected'; ?>>Casado(a)</option>
                <option value="União Estável" <?php if($ges->getEstadoCivil() == "União Estável") echo 'selected'; ?>>União Estável</option>
                <option value="Viúvo(a)" <?php if($ges->getEstadoCivil() == "Viúvo(a)") echo 'selected'; ?>>Viúvo(a)</option>
                <option value="Amigado(a)/Morando junto" <?php if($ges->getEstadoCivil() == "Amigado(a)/Morando junto") echo 'selected'; ?>>Amigado(a)/Morando junto</option>
                <option value="Em um relacionamento" <?php if($ges->getEstadoCivil() == "Em um relacionamento") echo 'selected'; ?>>Em um relacionamento</option>
            </select>
        </div>
        <div class="col-sm">
            <label for="sexo" class="text">Sexo</label>
            <select name="sexo" id="sexo" class="all-input">
                <option value="" selected>-- Selecione --</option>
                <option value="F" <?php if($ges->getSexo() == "F") echo 'selected'; ?>>Feminino</option>
                <option value="M" <?php if($ges->getSexo() == "M") echo 'selected'; ?>>Masculino</option>
                <option value="N" <?php if($ges->getSexo() == "N") echo 'selected'; ?>>Outro/Prefere não declarar</option>
            </select>
        </div>
    </div>
    <div class="row" style="margin-top:0.8em;">
        <div class="col-sm">
            <label for="foto" class="text">Foto</label>
            <input type="file" name="foto" id="foto" class="button button3">
            <small class="text">Formatos aceitos: JPG, JPEG</small>
            <?php if(isset($_GET['editar'])) { ?>
                <input type="hidden" name="foto_atual" value="<?php echo $ges->getFoto(); ?>">
           <?php } ?>
        </div>
    </div>

    <div class="row" style="margin-top: 2.5em;">
        <div class="col-sm">
            <h4 class="high-text">Informações profissionais</h4>
        </div>
    </div>

    <hr class="hr-divide-super-light">

    <div class="row" style="margin-top:0.8em;">
        <div class="col-sm">
            <label for="cargo" class="text">Cargo *</label>
            <input type="text" name="cargo" id="cargo" value="<?php echo $ges->getCargo(); ?>" class="all-input" maxlength="40" required="">
        </div>
        <div class="col-sm">
            <label for="linkedin" class="text">LinkedIn</label>
            <input type="text" name="linkedin" id="linkedin" value="<?php echo $ges->getLinkedin(); ?>" class="all-input" maxlength="120" placeholder="Link para o perfil">
        </div>
        <div class="col-sm">
            <label for="telefoneP" class="text">Telefone profissional</label>
            <input type="text" name="telefoneP" id="telefoneP" value="<?php echo $ges->getTelefoneProfissional(); ?>" class="all-input" maxlength="15">
        </div>
        <div class="col-sm">
            <label for="ramal" class="text">Ramal</label>
            <input type="text" name="ramal" id="ramal" value="<?php echo $ges->getRamal(); ?>" class="all-input" maxlength="6">
        </div>
    </div>
    <div class="row" style="margin-top:0.8em;">
        <?php if(!isset($_GET['editar'])) { ?>
        <div class="col-sm text" style="margin-top: 1em;">
            <input type="checkbox" name="isCol" id="isCol" value="1"> Cadastrar gestor como colaborador também (isso permite que ele seja avaliado)
        </div>
        <?php } ?>
    </div>
    <div class="row" style="margin-top:0.8em;">
        <div class="col-sm">
            <label for="ctps" class="text">C.T.P.S</label>
            <input type="text" name="ctps" id="ctps" value="<?php echo $ges->getCtps(); ?>" class="all-input">
        </div>
        <div class="col-sm">
            <label for="nis" class="text">N.I.S</label>
            <input type="text" name="nis" id="nis" value="<?php echo $ges->getNis(); ?>" class="all-input">
        </div>
    </div>
    <div class="row" style="margin-top:0.8em;">
        <div class="col-sm">
            <label for="formacao" class="text">Formação / Título</label>
            <input type="text" name="formacao" id="formacao" value="<?php echo $ges->getFormacao(); ?>" class="all-input">
        </div>
        <div class="col-sm">
            <label for="apresentacao" class="text">Apresentação / Resumo profissional</label>
            <textarea name="apresentacao" id="apresentacao" class="all-input"><?php echo $ges->getApresentacao(); ?></textarea>
        </div>
    </div>

    <div class="row" style="margin-top: 2.5em;">
        <div class="col-sm">
            <h4 class="high-text">Informações de saúde</h4>
        </div>
    </div>

    <hr class="hr-divide-super-light">

    <div class="row" style="margin-top:0.8em;">
        <div class="col-sm">
            <label for="deficiencia" class="text">Deficiência(s)</label>
            <input type="text" name="deficiencia" id="deficiencia" value="<?php echo $ges->getDeficiencia(); ?>" class="all-input">
        </div>
        <div class="col-sm">
            <label for="planoMedico" class="text">Plano médico</label>
            <input type="text" name="planoMedico" id="planoMedico" value="<?php echo $ges->getPlanoMedico(); ?>" class="all-input">
        </div>
        <div class="col-sm">
            <label for="tipoSanguineo" class="text">Tipo sanguíneo</label>
            <select name="tipoSanguineo" id="tipoSanguineo" class="all-input">
                <option value="" selected>-- Selecione --</option>
                <option value="Não sabe" <?php if($ges->getTipoSanguineo() == "Não sabe") echo 'selected'; ?>>Não sabe</option>
                <option value="A+" <?php if($ges->getTipoSanguineo() == "A+") echo 'selected'; ?>>A+</option>
                <option value="A-" <?php if($ges->getTipoSanguineo() == "A-") echo 'selected'; ?>>A-</option>
                <option value="B+" <?php if($ges->getTipoSanguineo() == "B+") echo 'selected'; ?>>B+</option>
                <option value="B-" <?php if($ges->getTipoSanguineo() == "B-") echo 'selected'; ?>>B-</option>
                <option value="O+" <?php if($ges->getTipoSanguineo() == "O+") echo 'selected'; ?>>O+</option>
                <option value="O-" <?php if($ges->getTipoSanguineo() == "O-") echo 'selected'; ?>>O-</option>
                <option value="AB+" <?php if($ges->getTipoSanguineo() == "AB+") echo 'selected'; ?>>AB+</option>
                <option value="AB-" <?php if($ges->getTipoSanguineo() == "AB-") echo 'selected'; ?>>AB-</option>
            </select>
        </div>
        <div class="col-sm">
            <label for="sus" class="text">Cartão SUS</label>
            <input type="text" name="sus" id="sus" value="<?php echo $ges->getCartaoSus(); ?>" class="all-input">
        </div>
    </div>
    <div class="row" style="margin-top:0.8em;">
        <div class="col-sm">
            <label for="medicamentos" class="text">Medicamentos contínuos</label>
            <input type="text" name="medicamentos" id="medicamentos" value="<?php echo $ges->getMedicamentos(); ?>" class="all-input">
        </div>
        <div class="col-sm">
            <label for="alergias" class="text">Alergias</label>
            <input type="text" name="alergias" id="alergias" value="<?php echo $ges->getAlergias(); ?>" class="all-input">
        </div>
        <div class="col-sm" style="margin-top: 2em;">
            <label for="diabetico" class="text">É diabético</label>
            <input type="checkbox" name="diabetico" id="diabetico" value="1" <?php if($ges->getDiabetico() == 1) echo 'checked'; ?>>
        </div>
        <div class="col-sm" style="margin-top: 2em;">
            <label for="hipertenso" class="text">É hipertenso</label>
            <input type="checkbox" name="hipertenso" id="hipertenso" value="1" <?php if($ges->getHipertenso() == 1) echo 'checked'; ?>>
        </div>
    </div> 

    <div class="row" style="margin-top: 2.5em;">
        <div class="col-sm">
            <h4 class="high-text">Informações empresariais</h4>
        </div>
    </div>

    <hr class="hr-divide-super-light">

    <div class="row" style="margin-top:0.8em;">
        <div class="col-sm">
            <label for="idInterno" class="text">ID interno</label>
            <input type="text" name="idInterno" id="idInterno" value="<?php echo $ges->getIDInterno(); ?>" class="all-input">
        </div>
        <div class="col-sm" style="margin-top: 2em;">
            <input type="button" class="button button3" value="O que é isso?" onClick="infoIDInterno();">
        </div>
    </div>

    <?php if(!isset($_GET['editar'])) { ?>
    <div class="row" style="margin-top: 2.5em;">
        <div class="col-sm">
            <h4 class="high-text">Informações de acesso</h4>
        </div>
    </div>

    <hr class="hr-divide-super-light">

    <div class="row" style="margin-top:0.8em;">
        <div class="col-sm">
            <label for="email" class="text">E-mail *</label>
            <input type="email" name="email" id="email" class="all-input" maxlength="120" required="">
        </div>
        <div class="col-sm">
            <label for="senha" class="text">Senha *</label>
            <input type="password" name="senha" id="senha" class="all-input" maxlength="30" minlength="8" required="">
        </div>
    </div>
    <div class="row" style="margin-top:0.8em;">
        <div class="col-sm">
            <input type="checkbox" name="senhaPadrao" id="senhaPadrao" onclick="document.getElementById('senha').value = 'bemvindo123';"> <span class="text">Usar senha padrão ("bemvindo123"). O gestor <b>deverá alterá-la depois</b></span>
        </div>
    </div>
    <?php } ?>
    
    <div class="row" style="margin-top:0.8em;">
        <?php if($_SESSION['user']['permissao'] == 'GESTOR-1') { ?>
        <div class="col-sm">
            <label for="permissao" class="text">Permissão de acesso *</label>
            <select name="permissao" id="permissao" class="all-input">
                <option value="1" <?php if($ges->getTipo() == 1) echo 'selected'; ?>>Permissão Administrativa - Total</option>
                <option value="2" <?php if(!isset($_GET['editar']) || $ges->getTipo() != 1) echo 'selected'; ?>>Permissão Operacional - Avaliações</option>
            </select>
        </div>
        <div class="col-sm" style="margin-top: 2em;">
            <input type="button" class="button button3" value="Como escolher?" onClick="infoPermissao();">
        </div>
        <?php } ?>
    </div>

    <hr class="hr-divide-light">

    <div class="row">
        <div class="col-sm-2 offset-sm-4">
            <input type="hidden" name="id" value="<?php echo $cpf; ?>">
            <input type="submit" value="<?php echo $btnValue; ?>" class="button button2" onclick="">
        </div>
        <div class="col-sm-2">
            <input type="reset" value="Limpar" class="button button2" onclick="">
        </div>
    </div>
    </form>
</div>
</body>
</html>