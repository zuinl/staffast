<?php
    require_once '../src/meta.php';
    require_once '../classes/class_conexao_blog.php';
    require_once '../classes/class_queryHelper.php';

    $conexao = new ConexaoBlog();
    $conn = $conexao->conecta();
    $helper = new QueryHelper($conn);

    $id = $_GET['id'];

    $select = "SELECT 
                t1.id as id,
                t1.titulo as titulo,
                t1.texto as texto,
                t1.capa as capa,
                DATE_FORMAT(t1.data, '%d/%m/%Y às %H:%i') as data,
                t2.nome as autor,
                t2.cargo as cargo,
                t2.linkedin as linkedin,
                t2.foto as foto
               FROM tbl_artigo t1
                INNER JOIN tbl_autor t2
                    ON t2.id = t1.id_autor
               WHERE t1.id = $id
               ORDER BY t1.data DESC";
    $f = $helper->select($select, 2);
    
    $update = "UPDATE tbl_artigo SET visualizacoes = (visualizacoes + 1) WHERE id = $id";
    $helper->update($update);
?>
<html>
<head>
    <title>Blog do Staffast</title>
    <!-- For Facebook -->
    <meta property="og:url" content="https://sistemastaffast.com/staffast/blog/artigo.php?id=<?php echo $f['id']; ?>" />
    <meta property="og:type" content="website" />
    <meta property="og:title" content="Blog do Staffast" />
    <meta property="og:image" content="https://sistemastaffast.com/staffast/empresa/img/logos/logo_adsumus.png" />
    <!-- Load Facebook SDK for JavaScript -->
    <div id="fb-root"></div>
    <script>
    (function(d, s, id) { var js, fjs = d.getElementsByTagName(s)[0];if (d.getElementById(id)) return;js = d.createElement(s); js.id = id;js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1" fjs.parentNode.insertBefore(js, fjs);}(document, 'script', 'facebook-jssdk'));
    </script>
    <!-- facebook Script End -->
</head>
<body style="margin-bottom: 2em; margin-top: 4em;">
<?php require_once 'bars.php'; ?>

<div class="container">
    <!-- NAV DE CAMINHO DE TELA -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="../">Staffast</a></li>
            <li class="breadcrumb-item"><a href="./">Blog do Staffast</a></li>
            <li class="breadcrumb-item active" aria-current="page"><?php echo $f['titulo']; ?></li>
        </ol>
    </nav>
    <!-- FIM DA NAV DE CAMINHO DE TELA -->

    <div id="artigo" style="margin-bottom: 3em;">
        <div class="row">
            <div class="col-sm">
                <h1 class="high-text"><?php echo $f['titulo']; ?></h1>
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

        <div class="row">
            <div class="col-sm" style="margin-top: 2em;">
                <h6 class="text"><?php echo $f['texto']; ?></h6>
            </div>
        </div>

        <div class="row" style="margin-top: 2em;">
            <div class="col-sm">
                <img src="fotos/<?php echo $f['foto']; ?>" width="40" style="border-radius: 30%">
                <span class="text" style="margin-left: 0.8em;"><?php echo $f['autor']; ?></span>
                <br><span class="text" style="font-size: 0.7em; margin-left: 5em;"><?php echo $f['cargo']; ?> <a href="<?php echo $f['linkedin']; ?>" target="_blank"><img src="/staffast/empresa/img/linkedin.png" width="20" style="margin-left: 1em;"></span></a>
            </div>
        </div>

        <hr class="hr-divide-super-light">

        <div class="row" style="text-align: center;">
            <div class="col-sm">
                <h6 class="text">Compartile este artigo :)</h6>
            </div>
        </div>
        <div class="row" style="text-align: center; margin-bottom: 3em; padding-left: 20%; padding-right: 20%;">
            <div class="col-sm">
                <!-- For Facebook -->
                <a class="fb-xfbml-parse-ignore" target="_blank" href="https://www.facebook.com/sharer/sharer.php?u=sistemastaffast.com">
                    <img src="/staffast/empresa/img/facebook.png" width="30">
                </a>
            </div>
            <div class="col-sm">
                <!-- For Twitter -->
                <a data-size="large" href="https://twitter.com/intent/tweet?text=sistemastaffast.com" target="_blank">
                    <img src="/staffast/empresa/img/twitter.png" width="30">
                </a>
            </div>
            <div class="col-sm">
                <!-- For LinkedIn -->
                <script src="//platform.linkedin.com/in.js" type="text/javascript"> lang: en_US</script>
                <script type="IN/Share" data-url="sistemastaffast.com"></script>
            </div>
        </div>

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

</div>
</body>
</html>