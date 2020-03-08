<?php 
    include('../include/auth.php');
    include('../src/meta.php');
    require_once('../classes/class_colaborador.php');

    if(!isset($_GET['editar'])) {
        if($_SESSION['user']['permissao'] != "GESTOR-1") {
            include('../include/acessoNegado.php');
            die();
        }
    } else if (isset($_GET['editar'])) {
        if($_SESSION['user']['permissao'] != "GESTOR-1" && $_SESSION['user']['cpf'] != base64_decode($_GET['editar'])) {
            include('../include/acessoNegado.php');
            die();
        }
    }

    $col = new Colaborador();

    $dataNascimento = date_create(date('Y-m-d'));

    if(isset($_GET['editar'])) {
        $cpf = base64_decode($_GET['editar']);
        $col->setCpf($cpf);
        $col = $col->retornarColaborador($_SESSION['empresa']['database']);
        $action = "../database/colaborador.php?atualiza=true";
        $btnValue = "Salvar alterações";
        $dataNascimento = date_create($col->getDataNascimento_format());
    } else {
        $action = "../database/colaborador.php?novoColaborador=true";
        $cpf = "";
        $btnValue = "Cadastrar";
    }
?>
<!DOCTYPE html> 
<html>
<head>
    <title>Novo colaborador</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
	<script src="https://igorescobar.github.io/jQuery-Mask-Plugin/js/jquery.mask.min.js"></script>  
    <script type="text/javascript">
        $('#cpf').mask('000.000.000-00');
        $('#rg').mask('00.000.000-0');
        $('#telefone').mask('(00) 00000-0000');
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

    <!-- NAV DE CAMINHO DE TELA -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="./">Início</a></li>
            <li class="breadcrumb-item active" aria-current="page"><?php if(isset($_GET['editar'])) echo 'Edição de colaborador'; else echo 'Cadastro de colaborador'; ?></li>
        </ol>
    </nav>
    <!-- FIM DA NAV DE CAMINHO DE TELA -->

    <div class="row">
        <?php if(!isset($_GET['editar'])) { ?>
        <div class="col-sm-10 offset-sm-2">
            <h2 class="high-text">Cadastro de <span class="destaque-text">colaborador</span></h2>
        </div>
        <?php } else { ?>
        <div class="col-sm-10 offset-sm-2">
            <h2 class="high-text">Atualização de <span class="destaque-text"><?php echo $col->getPrimeiroNome(); ?></span></h2>
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
            <input type="text" name="primeiroNome" id="primeiroNome" value="<?php echo $col->getPrimeiroNome(); ?>" class="all-input" maxlength="20" required="">
        </div>
        <div class="col-sm">
            <label for="sobrenome" class="text"><?php if(isset($_GET['editar'])) { echo 'Nome completo'; } else { echo 'Sobrenome';} ?> *</label>
            <input type="text" name="sobrenome" id="sobrenome" value="<?php echo $col->getNomeCompleto(); ?>" class="all-input" maxlength="60" required="">
        </div>
        <?php if(!isset($_GET['editar'])) { ?>
        <div class="col-sm">
            <label for="cpf" class="text">CPF *</label>
            <input type="text" name="cpf" id="cpf" value="<?php echo $col->getCpf(); ?>" class="all-input" maxlength="14" minlength="14" required="" onBlur="TestaCPF(this.value);">
        </div>
        <?php } ?>
        <div class="col-sm">
            <label for="dataNascimento" class="text">Data de nascimento *</label>
            <input type="date" name="dataNascimento" value="<?php echo date_format($dataNascimento,"Y-m-d"); ?>" id="dataNascimento" class="all-input" required="">
        </div>
        <div class="col-sm">
            <label for="telefone" class="text">Telefone</label>
            <input type="text" name="telefone" id="telefone" value="<?php echo $col->getTelefone(); ?>" class="all-input" maxlength="15">
        </div>
    </div>
    <div class="row" style="margin-top:0.8em;">
        <div class="col-sm">
            <label for="cep" class="text">CEP</label>
            <input type="text" name="cep" id="cep" value="<?php echo $col->getCep(); ?>" class="all-input" maxlength="9" onblur="pesquisacep(this.value);">
        </div>
        <div class="col-sm">
            <label for="endereco" class="text">Endereço *</label>
            <input type="text" name="endereco" id="endereco" value="<?php echo $col->getEndereco(); ?>" class="all-input" required>
        </div>
        <div class="col-sm">
            <label for="numero" class="text">Nº *</label>
            <input type="text" name="numero" id="numero" value="<?php echo $col->getNumero(); ?>" class="all-input" required>
        </div>
    </div>
    <div class="row" style="margin-top:0.8em;">
        <div class="col-sm">
            <label for="bairro" class="text">Bairro *</label>
            <input type="text" name="bairro" id="bairro" value="<?php echo $col->getBairro(); ?>" class="all-input" required>
        </div>
        <div class="col-sm">
            <label for="cidade" class="text">Cidade *</label>
            <input type="text" name="cidade" id="cidade" value="<?php echo $col->getCidade(); ?>" class="all-input" required>
        </div>
    </div>
    <div class="row" style="margin-top:0.8em;">
        <div class="col-sm">
            <label for="rg" class="text">R.G</label>
            <input type="text" name="rg" id="rg" value="<?php echo $col->getRg(); ?>" class="all-input" maxlength="12">
        </div>
        <div class="col-sm">
            <label for="cnh" class="text">C.N.H</label>
            <input type="text" name="cnh" id="cnh" value="<?php echo $col->getCnh(); ?>" class="all-input" maxlength="11" minlength="11">
        </div>
        <div class="col-sm">
            <label for="tipoCnh" class="text">Categoria da C.N.H</label>
            <select name="tipoCnh" id="tipoCnh" class="all-input">
                <option value="" selected>-- Selecione --</option>
                <option value="A/B" <?php if($col->getTipoCnh() == "A/B") echo 'selected'; ?>>A/B</option>
                <option value="B" <?php if($col->getTipoCnh() == "B") echo 'selected'; ?>>B</option>
                <option value="C" <?php if($col->getTipoCnh() == "C") echo 'selected'; ?>>C</option>
                <option value="D" <?php if($col->getTipoCnh() == "D") echo 'selected'; ?>>D</option>
                <option value="E" <?php if($col->getTipoCnh() == "E") echo 'selected'; ?>>E</option>
            </select>
        </div>
    </div>
    <div class="row" style="margin-top:0.8em;">
        <div class="col-sm">
            <label for="filhos" class="text">Filhos</label>
            <input type="number" name="filhos" id="filhos" value="<?php echo $col->getFilhos(); ?>" value="0" class="all-input">
        </div>
        <div class="col-sm">
            <label for="estadoCivil" class="text">Estado civil</label>
            <select name="estadoCivil" id="estadoCivil" class="all-input">
                <option value="" selected>-- Selecione --</option>
                <option value="Solteiro(a)" <?php if($col->getEstadoCivil() == "Solteiro(a)") echo 'selected'; ?>>Solteiro(a)</option>
                <option value="Casado(a)" <?php if($col->getEstadoCivil() == "Casado(a)") echo 'selected'; ?>>Casado(a)</option>
                <option value="União Estável" <?php if($col->getEstadoCivil() == "União Estável") echo 'selected'; ?>>União Estável</option>
                <option value="Viúvo(a)" <?php if($col->getEstadoCivil() == "Viúvo(a)") echo 'selected'; ?>>Viúvo(a)</option>
                <option value="Amigado(a)/Morando junto" <?php if($col->getEstadoCivil() == "Amigado(a)/Morando junto") echo 'selected'; ?>>Amigado(a)/Morando junto</option>
                <option value="Em um relacionamento" <?php if($col->getEstadoCivil() == "Em um relacionamento") echo 'selected'; ?>>Em um relacionamento</option>
            </select>
        </div>
        <div class="col-sm">
            <label for="sexo" class="text">Sexo</label>
            <select name="sexo" id="sexo" class="all-input">
                <option value="" selected>-- Selecione --</option>
                <option value="F" <?php if($col->getSexo() == "F") echo 'selected'; ?>>Feminino</option>
                <option value="M" <?php if($col->getSexo() == "M") echo 'selected'; ?>>Masculino</option>
                <option value="N" <?php if($col->getSexo() == "N") echo 'selected'; ?>>Outro/Prefere não declarar</option>
            </select>
        </div>
    </div> 
    <div class="row" style="margin-top:0.8em;">
        <div class="col-sm">
            <label for="foto" class="text">Foto</label>
            <input type="file" name="foto" id="foto" class="button button3">
            <small class="text">Formatos aceitos: JPG, JPEG</small>
            <?php if(isset($_GET['editar'])) { ?>
                <input type="hidden" name="foto_atual" value="<?php echo $col->getFoto(); ?>">
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
            <input type="text" name="cargo" id="cargo" value="<?php echo $col->getCargo(); ?>" class="all-input" maxlength="40" required="">
        </div>
        <div class="col-sm">
            <label for="telefoneP" class="text">Telefone profissional</label>
            <input type="text" name="telefoneP" id="telefoneP" value="<?php echo $col->getTelefoneProfissional(); ?>" class="all-input" maxlength="15">
        </div>
    </div>
    
    <div class="row" style="margin-top:0.8em;">
        <div class="col-sm">
            <label for="ctps" class="text">C.T.P.S</label>
            <input type="text" name="ctps" id="ctps" value="<?php echo $col->getCtps(); ?>" class="all-input">
        </div>
        <div class="col-sm">
            <label for="nis" class="text">N.I.S</label>
            <input type="text" name="nis" id="nis" value="<?php echo $col->getNis(); ?>" class="all-input">
        </div>
    </div>
    <div class="row" style="margin-top:0.8em;">
        <div class="col-sm">
            <label for="formacao" class="text">Formação / Título</label>
            <input type="text" name="formacao" id="formacao" value="<?php echo $col->getFormacao(); ?>" class="all-input">
        </div>
        <div class="col-sm">
            <label for="apresentacao" class="text">Apresentação / Resumo profissional</label>
            <textarea name="apresentacao" id="apresentacao" class="all-input"><?php echo $col->getApresentacao(); ?></textarea>
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
            <input type="text" name="deficiencia" id="deficiencia" value="<?php echo $col->getDeficiencia(); ?>" class="all-input">
        </div>
        <div class="col-sm">
            <label for="planoMedico" class="text">Plano médico</label>
            <input type="text" name="planoMedico" id="planoMedico" value="<?php echo $col->getPlanoMedico(); ?>" class="all-input">
        </div>
        <div class="col-sm">
            <label for="tipoSanguineo" class="text">Tipo sanguíneo</label>
            <select name="tipoSanguineo" id="tipoSanguineo" class="all-input">
                <option value="" selected>-- Selecione --</option>
                <option value="Não sabe" <?php if($col->getTipoSanguineo() == "Não sabe") echo 'selected'; ?>>Não sabe</option>
                <option value="A+" <?php if($col->getTipoSanguineo() == "A+") echo 'selected'; ?>>A+</option>
                <option value="A-" <?php if($col->getTipoSanguineo() == "A-") echo 'selected'; ?>>A-</option>
                <option value="B+" <?php if($col->getTipoSanguineo() == "B+") echo 'selected'; ?>>B+</option>
                <option value="B-" <?php if($col->getTipoSanguineo() == "B-") echo 'selected'; ?>>B-</option>
                <option value="O+" <?php if($col->getTipoSanguineo() == "O+") echo 'selected'; ?>>O+</option>
                <option value="O-" <?php if($col->getTipoSanguineo() == "O-") echo 'selected'; ?>>O-</option>
                <option value="AB+" <?php if($col->getTipoSanguineo() == "AB+") echo 'selected'; ?>>AB+</option>
                <option value="AB-" <?php if($col->getTipoSanguineo() == "AB-") echo 'selected'; ?>>AB-</option>
            </select>
        </div>
        <div class="col-sm">
            <label for="sus" class="text">Cartão SUS</label>
            <input type="text" name="sus" id="sus" value="<?php echo $col->getCartaoSus(); ?>" class="all-input">
        </div>
    </div>
    <div class="row" style="margin-top:0.8em;">
        <div class="col-sm">
            <label for="medicamentos" class="text">Medicamentos contínuos</label>
            <input type="text" name="medicamentos" id="medicamentos" value="<?php echo $col->getMedicamentos(); ?>" class="all-input">
        </div>
        <div class="col-sm">
            <label for="alergias" class="text">Alergias</label>
            <input type="text" name="alergias" id="alergias" value="<?php echo $col->getAlergias(); ?>" class="all-input">
        </div>
        <div class="col-sm" style="margin-top: 2em;">
            <label for="diabetico" class="text">É diabético</label>
            <input type="checkbox" name="diabetico" id="diabetico" value="1" <?php if($col->getDiabetico() == 1) echo 'checked'; ?>>
        </div>
        <div class="col-sm" style="margin-top: 2em;">
            <label for="hipertenso" class="text">É hipertenso</label>
            <input type="checkbox" name="hipertenso" id="hipertenso" value="1" <?php if($col->getHipertenso() == 1) echo 'checked'; ?>>
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
            <input type="text" name="idInterno" id="idInterno" value="<?php echo $col->getIDInterno(); ?>" class="all-input">
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
                <input type="checkbox" name="senhaPadrao" id="senhaPadrao" onclick="document.getElementById('senha').value = 'bemvindo123';"> <span class="text">Usar senha padrão ("bemvindo123"). O colaborador <b>deverá alterá-la depois</b></span>
            </div>
        </div>
    <?php } ?>

    <hr class="hr-divide-light">

    <div class="row">
        <div class="col-sm-2 offset-sm-4">
            <input type="hidden" name="id" value="<?php echo $cpf; ?>">
            <input type="submit" value="<?php echo $btnValue; ?>" class="button button2" onclick="">
        </div>
        <div class="col-sm-2">
            <input type="reset" value="Limpar" class="button button2">
        </div>
    </div>
    </form>
</div>
</body>
</html>