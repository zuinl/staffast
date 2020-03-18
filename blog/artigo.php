<?php
    require_once '../src/meta.php';
?>
<html>
<head>
    <title>Blog do Staffast</title>
    <!-- For Facebook -->
    <meta property="og:url" content="https://sistemastaffast.com" />
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
<body style="margin-bottom: 4em; margin-top: 1em;">
<?php require_once 'bars.php'; ?>

<div class="container">
    <!-- NAV DE CAMINHO DE TELA -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="../">Staffast</a></li>
            <li class="breadcrumb-item"><a href="./">Blog do Staffast</a></li>
            <li class="breadcrumb-item active" aria-current="page">Título do artigo</li>
        </ol>
    </nav>
    <!-- FIM DA NAV DE CAMINHO DE TELA -->

    <div id="artigo" style="margin-bottom: 5em;">
        <div class="row">
            <div class="col-sm">
                <h1 class="high-text">Título da matéria</h1>
                <h6 class="text">17/03/2020 às 18:00</h6>

                <hr class="hr-divide-super-light">
            </div>
        </div>

        <div class="row" style="text-align: center;">
            <div class="col-sm">
                <img src="/staffast/empresa/img/logos/logo_adsumus.png" width="300">
            </div>
        </div>

        <div class="row">
            <div class="col-sm">
                <h6 class="text">Texto completo do artigo Texto completo do artigo Texto completo do artigo Texto completo do artigo 
                Texto completo do artigo Texto completo do artigo Texto completo do artigo Texto completo do artigo Texto completo do artigo 
                Texto completo do artigo Texto completo do artigo Texto completo do artigo Texto completo do artigo Texto completo do artigo 
                Texto completo do artigo Texto completo do artigo Texto completo do artigo Texto completo do artigo Texto completo do artigo 
                Texto completo do artigo Texto completo do artigo Texto completo do artigo Texto completo do artigo Texto completo do artigo 
                Texto completo do artigo Texto completo do artigo Texto completo do artigo Texto completo do artigo Texto completo do artigo</h6>
            </div>
        </div>

        <div class="row" style="margin-top: 2em;">
            <div class="col-sm">
                <img src="/staffast/empresa/img/logos/logo_adsumus.png" width="40">
                <span class="text" style="margin-left: 0.8em;">Nome do autor do artigo</span>
                <br><span class="text" style="font-size: 0.7em; margin-left: 5em;">Cargo do autor <a href="https://linkedin.com.br/in/lzuin/" target="_blank"><img src="/staffast/empresa/img/linkedin.png" width="20" style="margin-left: 1em;"></span></a>
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