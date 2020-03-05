<?php

    require_once '../../include/auth.php';
    require_once '../../src/meta.php';
    require_once '../../src/functions.php';
    require_once '../../classes/class_conexao_empresa.php';
    require_once '../../classes/class_queryHelper.php';
    require_once '../../classes/class_avaliacao.php';

    $conexao_e = new ConexaoEmpresa($_SESSION['empresa']['database']);
    $conn = $conexao_e->conecta();
    $helper = new QueryHelper($conn);

    $select = "SELECT DISTINCT col_cpf as cpf 
                    FROM tbl_setor_funcionario WHERE col_cpf NOT LIKE '00000000000'";

    if(isset($_GET['setor']) && $_GET['setor'] != 'Todos') {
        //query com filtro por setor
        $select .= " AND set_id = ".$_GET['setor'];

        $select_setor = "SELECT set_nome as nome FROM tbl_setor WHERE set_id = ".$_GET['setor'];
        $fetch = $helper->select($select_setor, 2);
        $setor = $fetch['nome'];
    } else {
        $setor = 'Todos';
    }
    $query = $helper->select($select, 1);

    $competencias = numCompetencias();
    $ava = new Avaliacao();

    $array_ranking = array();

    while($f = mysqli_fetch_assoc($query)) {
        $ava->setCpfColaborador($f['cpf']);
        
        if($_GET['periodo'] == 'all') $medias = $ava->calcularMedias($_SESSION['empresa']['database']);
        else if($_GET['periodo'] == 'curto') $medias = $ava->calcularMediasCurtoPrazo($_SESSION['empresa']['database']);
        else if($_GET['periodo'] == 'medio') $medias = $ava->calcularMediasMedioPrazo($_SESSION['empresa']['database']);
        else if($_GET['periodo'] == 'curto-medio') $medias = $ava->calcularMediasCurtoMedioPrazo($_SESSION['empresa']['database']);
        else if($_GET['periodo'] == 'longo') $medias = $ava->calcularMediasLongoPrazo($_SESSION['empresa']['database']);
        else if($_GET['periodo'] == 'quinzena') $medias = $ava->calcularMediasQuinzena($_SESSION['empresa']['database']);
        else if($_GET['periodo'] == 'semana') $medias = $ava->calcularMediasSemana($_SESSION['empresa']['database']);

        $mediaTotalAva = 0.0;
        for($a = 1; $a <= $competencias; $a++) {
            $mediaTotalAva += $medias[$a];
        }
        $mediaTotalAva = round(($mediaTotalAva / $competencias), 1);

        if($mediaTotalAva > 0) $array_ranking[$f['cpf']] = $mediaTotalAva;
        
    }

    arsort($array_ranking);

    switch($_GET['periodo']) {
        case 'curto':
            $periodo = 'Últimos 30 dias'; break;
        case 'medio':
            $periodo = 'Últimos 90 dias'; break;
        case 'curto-medio':
            $periodo = 'Últimos 180 dias'; break;
        case 'longo':
            $periodo = 'Últimos 365 dias'; break;
        case 'quinzena':
            $periodo = 'Últimos 15 dias'; break;
        case 'semana':
            $periodo = 'Últimos 7 dias'; break;
        case 'all':
            $periodo = 'Desde sempre'; break;
    }

    $ranking = 1;
?>
    <div class="row">
        <div class="col-sm">
            <h3 class="text">Ranking - <?php echo $setor.' - '.$periodo; ?></h3>
        </div>
    </div>
        <hr class="hr-divide-super-light">
    <?php foreach ($array_ranking as $key => $value) { 
        $select = "SELECT t1.col_nome_completo as nome, 
        DATE_FORMAT(MAX(t2.ava_data_criacao), '%d/%m/%Y') as data 
        FROM tbl_colaborador t1 
        INNER JOIN tbl_avaliacao t2 
            ON t2.col_cpf = t1.col_cpf 
        WHERE t1.col_cpf = '$key'";
        $fetch = $helper->select($select, 2);
        ?>
        <?php if ($ranking == 1) { ?>
        <div class="row">
            <div class="col-sm">
                <h2 class="text"><img src="img/gold.png" width="80"> <?php echo $ranking; ?>º <b><?php echo $fetch['nome'] ?></b> - Média <?php echo number_format($value, 1, ',', '') ?> <br><small class="text" style="font-size: 0.5em;">Última avaliação: <?php echo $fetch['data'] ?></small></h2>
            </div>
        </div>
        <hr class="hr-divide-super-light">
        <?php } else if ($ranking == 2) { ?>
        <div class="row">
            <div class="col-sm">
                <h3 class="text"><img src="img/silver.png" width="80"> <?php echo $ranking; ?>º <?php echo $fetch['nome'] ?> - Média <?php echo number_format($value, 1, ',', '') ?> <br><small class="text" style="font-size: 0.5em;">Última avaliação: <?php echo $fetch['data'] ?></small></h3>
            </div>
        </div>
        <hr class="hr-divide-super-light">
        <?php } else if ($ranking == 3) { ?>
        <div class="row">
            <div class="col-sm">
                <h4 class="text"><img src="img/bronze.png" width="80"> <?php echo $ranking; ?>º <?php echo $fetch['nome'] ?> - Média <?php echo number_format($value, 1, ',', '') ?> <br><small class="text" style="font-size: 0.5em;">Última avaliação: <?php echo $fetch['data'] ?></small></h4>
            </div>
        </div>
        <hr class="hr-divide-super-light">
        <?php } else { ?>
        <div class="row">
            <div class="col-sm">
                <h5 class="text"> <?php echo $ranking; ?>º <?php echo $fetch['nome']; ?> - Média <?php echo number_format($value, 1, ',', '') ?> <br><small class="text" style="font-size: 0.5em;">Última avaliação: <?php echo $fetch['data'] ?></small></h5>
            </div>
        </div>
        <hr class="hr-divide-super-light">
        <?php }
        $ranking++;
        ?>
    <?php } ?>
    <?php if(sizeof($array_ranking) == 0) { ?>
        <div class="row">
            <div class="col-sm">
                <h5 class="text"> Sem dados para montar o ranking com estes filtros</h5>
            </div>
        </div>
    <?php } ?>