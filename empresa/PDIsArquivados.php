<?php
    include('../include/auth.php');
    include('../src/meta.php');
    require_once('../classes/class_conexao_empresa.php');
    require_once('../classes/class_queryHelper.php');
    require_once('../classes/class_pdi.php');

    $conexao = new ConexaoEmpresa($_SESSION['empresa']['database']);
    $conn = $conexao->conecta();
    $helper = new QueryHelper($conn);
    $cpf = $_SESSION['user']['cpf'];

    $select = "SELECT t1.pdi_id as id, t1.pdi_titulo as titulo,
    t1.pdi_status as status,
    CASE 
        WHEN t3.col_nome_completo IS NOT NULL THEN t3.col_nome_completo
        ELSE t4.ges_nome_completo 
    END as dono,
    CASE 
        WHEN t5.ges_nome_completo IS NOT NULL THEN t5.ges_nome_completo
        ELSE 'Nenhum'
    END as orientador
    FROM tbl_pdi t1 
    LEFT JOIN tbl_colaborador t3 
        ON t3.col_cpf = t1.pdi_cpf
    LEFT JOIN tbl_gestor t4 
        ON t4.ges_cpf = t1.pdi_cpf
    LEFT JOIN tbl_gestor t5
        ON t5.ges_cpf = t1.ges_cpf
    WHERE t1.pdi_arquivado = 1 AND (t1.pdi_cpf = '$cpf' OR t1.ges_cpf = '$cpf')";
    $query = $helper->select($select, 1);
    
?>
<!DOCTYPE html>
<html>
<head>
    <title>PDIs</title>
</head>
<body>
<?php
    include('../include/navbar.php');
?>
<div class="container-fluid">

    <!-- NAV DE CAMINHO DE TELA -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="home.php">Início</a></li>
            <li class="breadcrumb-item"><a href="PDIs.php">Planos de Desenvolvimento Individual (PDIs)</a></li>
            <li class="breadcrumb-item active" aria-current="page">Planos de Desenvolvimento Individual (PDIs) - Arquivados</li>
        </ol>
    </nav>
    <!-- FIM DA NAV DE CAMINHO DE TELA -->

    <div class="row">
        <div class="col-sm-1">
            <img src="img/pdi.png" width="60">
        </div>
        <div class="col-sm">
            <h3 class="high-text">Planos de Desenvolvimento Individual arquivados</h3>
        </div>
    </div>

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

    <hr class="hr-divide">
</div>
<div class="container">

    <?php
    if(mysqli_num_rows($query) == 0) {
        ?>
        <div class="row">
            <div class="col-sm-2 offset-sm-3">
                <img src="img/pdi.png" width="110">
            </div>
            <div class="col-sm-7" style="margin-top: 2em;">
                <h4 class="text">Sem PDIs arquivados por enquanto.</h4>
            </div>
         </div>
        <?php
        } else {
    ?>
    
    <table class="table-site">
        <tr>
            <th>Título</th>
            <th>Dono</th>
            <th>Orientador</th>
            <th>Status</th>
            <th>Ver</th>
        </tr>
        <?php
            while($f = mysqli_fetch_assoc($query)) {
                $pdi = new PDI();
        ?>
        <tr>
            <td><b><?php echo $f['titulo']; ?></b></td>
            <td><?php echo $f['dono']; ?></td>
            <td><?php echo $f['orientador']; ?></td>
            <td><?php echo $pdi->traduzStatus($f['status']); ?></td>
            <td><a href="verPDI.php?id=<?php echo $f['id']; ?>"><input type="button" class="button button3" value="Ver"></a></td>
    <?php } ?>   
    </table>
 <?php } ?>
</div>
</body>
</html>