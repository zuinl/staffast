<?php
include('../../include/auth.php');
include('../../src/meta.php');
require_once('../../classes/class_conexao_empresa.php');
require_once('../../classes/class_queryHelper.php');

if(!isset($_GET['cpf'])) die("Erro.");

$conexao = new ConexaoEmpresa($_SESSION['empresa']['database']);
    $conn = $conexao->conecta();
$helper = new QueryHelper($conn);

$cpf = $_GET['cpf'];

$select = "SELECT 
            DISTINCT(t1.modelo_id) as id,
            t2.titulo as titulo 
           FROM tbl_colaborador_modelo_avaliacao t1 
            INNER JOIN tbl_modelo_avaliacao t2 
                ON t2.id = t1.modelo_id 
                AND t2.ativo = 1 
           WHERE t1.col_cpf = '$cpf'";
$query = $helper->select($select, 1);

?>
<label for="modelo" class="text">Selecione o modelo de avaliação*</label>
    <select name="modelo" id="modelo" class="all-input" onchange="exibirAvaliacao(this.value);">
        <option value="" selected disabled>- Selecione -</option>
        <option value="0">Padrão/Geral</option>
        <?php while($f = mysqli_fetch_assoc($query)) { ?>
            <option value="<?php echo $f['id']; ?>"><?php echo $f['titulo']; ?></option>
        <?php } ?>
    </select>
