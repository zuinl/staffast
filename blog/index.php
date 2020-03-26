<?php
    require_once '../src/meta.php';
    require_once '../classes/class_conexao_blog.php';
    require_once '../classes/class_queryHelper.php';

    $conexao = new ConexaoBlog();
    $conn = $conexao->conecta();
    $helper = new QueryHelper($conn);

    $select = "SELECT 
                t1.id as id,
                t1.titulo as titulo,
                t1.texto as texto,
                t1.capa as capa,
                DATE_FORMAT(t1.data, '%d/%m/%Y às %H:%i') as data,
                t2.nome as autor
               FROM tbl_artigo t1
                INNER JOIN tbl_autor t2
                    ON t2.id = t1.id_autor
               ORDER BY t1.data DESC";
    $query = $helper->select($select, 1);
?>
<html>
<head>
    <title>Blog do Staffast</title>
</head>
<body style="margin-top: 4em; padding-left: 8em; padding-right: 8em;">
<?php require_once 'bars.php'; ?>

<div class="container">
    <!-- NAV DE CAMINHO DE TELA -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="../">Staffast</a></li>
            <li class="breadcrumb-item active" aria-current="page">Blog do Staffast</li>
        </ol>
    </nav>
    <!-- FIM DA NAV DE CAMINHO DE TELA -->

    <?php 
    if(mysqli_num_rows($query) != 0) {
        while($f = mysqli_fetch_assoc($query)) {
    ?>
    <div id="artigo" style="margin-bottom: 5em;">
        <div class="row">
            <div class="col-sm">
                <h1 class="high-text"><a href="artigo.php?id=<?php echo $f['id']; ?>"><?php echo $f['titulo']; ?></a></h1>
                <h6 class="text"><?php echo $f['data']; ?></h6>

                <hr class="hr-divide-super-light">
            </div>
        </div>

        <?php if($f['capa'] != "") { ?>
        <div class="row" style="text-align: center;">
            <div class="col-sm">
                <img src="capas/<?php echo $f['capa']; ?>" width="300">
            </div>
        </div>
        <?php } ?>

        <div class="row" style="padding-left: 10em; padding-right: 10em; margin-top: 2em;">
            <div class="col-sm">
                <h6 class="text"><?php echo substr($f['texto'], 0, 150); ?></h6>
            </div>
        </div>

        <div class="row" style="text-align: center;">
            <div class="col-sm">
                <a href="artigo.php?id=<?php echo $f['id']; ?>"><button class="button button1">Ler o resto</button></a>
            </div>
        </div>
    </div>
    <?php }
    } else {
        ?>
        <div id="artigo" style="margin-bottom: 5em;">
            <div class="row">
                <div class="col-sm">
                    <h1 class="high-text">Ainda não temos nenhum artigo para ler :/</h1>

                    <hr class="hr-divide-super-light">
                </div>
            </div>
        </div>
        <?php
    }
    ?>

        <hr class="hr-divide-super-light">

        <div class="row" style="text-align: center;">
            <div class="col-sm">
                <h6 class="text">Nos encontre nas redes</h6>
            </div>
            </div>
        <div class="row" style="text-align: center; padding-left: 20%; padding-right: 20%;">
            <div class="col-sm">
                <!-- For Facebook -->
                <a class="fb-xfbml-parse-ignore" target="_blank" href="https://www.instagram.com/staffast_/">
                    <img src="/staffast/empresa/img/instagram.png" width="30">
                </a>
            </div>
            <div class="col-sm">
                <!-- For Twitter -->
                <a data-size="large" href="https://www.youtube.com/channel/UCFOx-xf2Iyv4kwkxekZcUaw" target="_blank">
                    <img src="/staffast/empresa/img/youtube.png" width="30">
                </a>
            </div>
        </div>
</div>
</body>
</html>