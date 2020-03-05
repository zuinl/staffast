<?php
    include('../include/auth.php');
    include('../src/meta.php');
    include('../classes/class_conexao_empresa.php');
    include('../classes/class_queryHelper.php');

    $conexao = new ConexaoEmpresa($_SESSION['empresa']['database']);
    $conn = $conexao->conecta();
    $helper = new QueryHelper($conn);

    $select = "SELECT DISTINCT ges_cpf as cpf, ges_nome_completo as gestor 
    FROM tbl_gestor WHERE ges_ativo = 1 ORDER BY ges_nome_completo ASC";
    $query_gestor = $helper->select($select, 1);

    $select = "SELECT DISTINCT col_cpf as cpf, col_nome_completo as colaborador 
    FROM tbl_colaborador WHERE col_ativo = 1 ORDER BY col_nome_completo ASC";
    $query_colaborador = $helper->select($select, 1);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Nova DR</title>    
</head>
<body>
<?php
    include('../include/navbar.php');
?>
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-4 offset-sm-2">
            <h1 class="high-text">Nova <span class="destaque-text">DR</span></h1>
        </div>
        <div class="col-sm">
            <input type="button" class="button button1" data-toggle="modal" data-target="#modal" value="Dicas para criar uma DR">
        </div>
    </div>

    <hr class="hr-divide">

    <?php
    if(isset($_SESSION['msg'])) {
        ?>
		<div class="row">
            <div class="col-sm-6">
                <div class="alert alert-info alert-dismissible fade show" role="alert">
                    <?php echo $_SESSION['msg']; unset($_SESSION['msg']); ?>
                </div>
            </div>
		</div>
        <?php
    }
    ?>
</div>
<div class="container">
    <div class="row" style="margin-top: 1em;">
        <div class="col-sm">
        <form method="POST" action="../database/dr.php?nova=true" id="form">
            <label for="data" class="text">Data da DR *</label>
            <input type="date" name="data" id="data" class="all-input" required value="<?php echo date('Y-m-d'); ?>">
        </div>
        <div class="col-sm">
            <label for="hora" class="text">Hora da DR *</label>
            <input type="time" name="hora" id="hora" class="all-input" required>
        </div>
    </div>

    <div class="row" style="margin-top: 1em;">
        <div class="col-sm">
            <label for="pessoa_1" class="text">A DR será entre este funcionário...</label>
            <select name="pessoa_1" id="pessoa_1" class="all-input" required>
                <option value="">- Selecione -</option>
                <option value="" disabled>GESTORES</option>
                <?php
                    while($f = mysqli_fetch_assoc($query_gestor)) {
                        ?>
                        <option value="<?php echo $f['cpf'] ?>"><?php echo $f['gestor'] ?></option>
                        <?php
                    }
                    ?>
                    <option value="" disabled>COLABORADORES</option>
                    <?php
                    while($f = mysqli_fetch_assoc($query_colaborador)) {
                        ?>
                        <option value="<?php echo $f['cpf'] ?>"><?php echo $f['colaborador'] ?></option>
                        <?php
                    }
                ?>
            </select>
        </div>

        <div class="col-sm">
            <label for="pessoa_2" class="text">... e entre este colaborador/gestor.</label>
            <select name="pessoa_2" id="pessoa_2" class="all-input" required>
                <option value="">- Selecione -</option>
                <option value="" disabled>GESTORES</option>
                <?php
                    $select = "SELECT DISTINCT ges_cpf as cpf, ges_nome_completo as gestor 
                    FROM tbl_gestor WHERE ges_ativo = 1 ORDER BY ges_nome_completo ASC";
                    $query_gestor = $helper->select($select, 1);
                
                    $select = "SELECT DISTINCT col_cpf as cpf, col_nome_completo as colaborador 
                    FROM tbl_colaborador WHERE col_ativo = 1 ORDER BY col_nome_completo ASC";
                    $query_colaborador = $helper->select($select, 1);
                    while($f = mysqli_fetch_assoc($query_gestor)) {
                        ?>
                        <option value="<?php echo $f['cpf'] ?>"><?php echo $f['gestor'] ?></option>
                        <?php
                    }
                    ?>
                    <option value="" disabled>COLABORADORES</option>
                    <?php
                    while($f = mysqli_fetch_assoc($query_colaborador)) {
                        ?>
                        <option value="<?php echo $f['cpf'] ?>"><?php echo $f['colaborador'] ?></option>
                        <?php
                    }
                ?>
            </select>
        </div>
    </div>

    <hr class="hr-divide-super-light">

    <div class="row">
        <div class="col-sm">
            <input type="checkbox" name="enviarEmail" id="enviarEmail" value="1"> <span class="text">Enviar e-mail aos funcionários</span>
        </div>
    </div>

    <hr class="hr-divide-super-light">

    <div class="row">
        <div class="col-sm-2 offset-sm-4">
            <input type="submit" value="Cadastrar" class="button button1">
        </div>
        <div class="col-sm-2">
            <input type="reset" value="Limpar" class="button button1">
        </div>
    </div>
    </form>
</div>
</body>

<div class="modal" tabindex="-1" role="dialog" id="modal" data-target=".bd-example-modal-lg">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"><?php echo $gestor->getNomeCompleto(); ?></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="row">
            <div class="col-sm">
                <h3 class="high-text">Como criar uma OKR?</h3>
            </div>
        </div>

        <div class="row">
            <div class="col-sm">
                <h5 class="text">Objetivo</h5>
            </div>
        </div>
        <div class="row">
            <div class="col-sm">
                <p class="text">O objetivo ideal da uma meta OKR deve ser algo claro e direto, mas <b>inspirador</b>. 
                Evite citar números ou valores (deixe isto para os <i>Key Results</i>), um objetivo geralmente é <b>qualitativo</b>.
                Exemplos: Construir o time de vendas perfeito, Alcançar excelência em atendimento ao cliente, Dobrar nosso número de clientes</p>
            </div>
        </div>

        <div class="row">
            <div class="col-sm">
                <h5 class="text">Descrição do objetivo</h5>
            </div>
        </div>
        <div class="row">
            <div class="col-sm">
                <p class="text">Enquanto o objetivo deve ser resumido a uma frase, a descrição pode ser um pouco mais longa. Nela você pode citar a razão pela qual sua empresa deve atingir este objetivo, 
                ou a principal vantagem. </p>
            </div>
        </div>

        <div class="row">
            <div class="col-sm">
                <h5 class="text">Participantes</h5>
            </div>
        </div>
        <div class="row">
            <div class="col-sm">
                <p class="text">Você pode selecionar os colaboradores, gestores e setores envolvidos e/ou afetados pela sua meta OKR. Porém, eles só conseguirão visualizar a OKR se você selecionar "Todos verão" ou "gestores" no campo "Quem verá esta meta?". </p>
            </div>
        </div>

        <div class="row">
            <div class="col-sm">
                <h5 class="text"><i>Key Result</i></h5>
            </div>
        </div>
        <div class="row">
            <div class="col-sm">
                <p class="text">Os <i>Key Result</i>, do inglês "Objetivos chave", são os mais importantes de uma meta OKR! Você pode inserir de 1 a 5, mas as melhores OKRs tem pelo menos três Key Results. 
                Você precisa inserir um título (exemplos: "Contratar 2 novos vendedores", "Reduzir em 20% o número de reclamações de clientes", "Economizar R$800,00 com avarias"). As Key Results <b>precisam ter um valor quantitativo</b>, 
                pois desta forma será possível medir o avanço das mesmas. As Key Results são, em essência, os resultados que a empresa precisa atingir para concluir o objetivo da OKR.</p>
            </div>
        </div>
        
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
      </div>
    </div>
  </div>
</div>

</html>