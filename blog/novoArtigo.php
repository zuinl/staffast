<?php
    session_start();
    require_once '../src/meta.php';
    require_once '../classes/class_conexao_blog.php';
    require_once '../classes/class_queryHelper.php';
    require_once '../classes/class_email.php';
    ini_set('display_errors', 0);

    $conexao = new ConexaoBlog();
    $conn = $conexao->conecta();
    $helper = new QueryHelper($conn);

    if(isset($_GET['postar'])) {
        $codigo = $_POST['codigo'];

        $select = "SELECT nome, id, autorizado FROM tbl_autor WHERE codigo = '$codigo'";
        $fetch = $helper->select($select, 2);

        if($fetch['nome'] != "") {
            setcookie('staffast_blog_post_codigo', $codigo, time() + (86400 * 365), "/"); 
        }
    }

    if(isset($_GET['salvarArtigo'])) {
        ini_set('display_errors', 1);
        $titulo = addslashes($_POST['titulo']);
        $texto = addslashes($_POST['texto']);
        $texto = nl2br($texto);
        $id_autor = $_POST['id_autor'];
        $capa = "";

        //Handle the image
        if($_FILES['capa']['name'] != "") {
            $img_nome = $_FILES['capa']['name'];
            $img_tmp = $_FILES['capa']['tmp_name'];

            $nome_capa = (string)'capa_aritigo_'.md5(date('H')).$img_nome;
            $img_caminho = 'capas/'.$nome_capa;

            if(move_uploaded_file($img_tmp, $img_caminho)) {
                $capa = $nome_capa;
            } else {
                $capa = "";
            }
        }

        $insert = "INSERT INTO tbl_artigo 
                    (
                        titulo,
                        texto,
                        id_autor,
                        capa
                    ) VALUES 
                    (
                        '$titulo',
                        '$texto',
                        $id_autor,
                        '$capa'
                    )";
        $helper->insert($insert);

        $select = "SELECT id FROM tbl_artigo ORDER BY id DESC LIMIT 1";
        $fetch = $helper->select($select, 2);
        $id = $fetch['id'];

        //Enviar aos cadastrados na newsletter
        $mail = new Email();
        $mail->setEmailFrom();
        $mail->setAssunto('Blog do Staffast: '.$titulo);
        
        $mensagem = '';
        if($capa != "") {
            $mensagem .= '
                        <div class="row" style="text-align: center;">
                            <div class="col-sm">
                                <img src="https://sistemastaffast.com/staffast/blog/capas/'.$capa.' width="180">
                            </div>
                        </div>';
        }

        $mensagem .= '<div class="row" style="text-align: center;">
                        <div class="col-sm">
                            <h2 class="high-text">Novo post no Blog do Staffast: '.$titulo.'</h2>
                            <br>
                            <p class="text">'.substr($texto, 0, 400).'</p>
                            <br>
                            <a href="https://sistemastaffast.com/staffast/blog/artigo.php?id='.$id.'" target="_blank">
                                <button class="button button1">Ler o artigo completo no blog</button>
                            </a>
                        </div>
                      </div>';
        $mail->setMensagem($mensagem);
        $mail->dispararNewsletter();

        ?>
        <script> confirm('Seu artigo foi publicado com sucesso'); </script>
        <?php
    }

    $codigo = "";

    if(isset($_COOKIE['staffast_blog_post_codigo'])) $codigo = $_COOKIE['staffast_blog_post_codigo'];
?>
<html>
<head>
    <title>Postar artigo</title>
</head>
<body style="margin-top: 4em;">
<?php require_once 'bars.php'; ?>

<div class="container">

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

    <!-- NAV DE CAMINHO DE TELA -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="../">Staffast</a></li>
            <li class="breadcrumb-item"><a href="index.php">Blog do Staffast</a></li>
            <li class="breadcrumb-item active" aria-current="page"><a href="novoArtigo.php">Postar novo artigo</a></li>
        </ol>
    </nav>
    <!-- FIM DA NAV DE CAMINHO DE TELA -->

    <div id="artigo" style="margin-bottom: 5em;">
        <div class="row">
            <div class="col-sm">
                <h1 class="high-text">Postar seu artigo no Blog do Staffast</h1>
                <h6 class="text">Esta página é exclusiva para uso da equipe do blog do Staffast. Se você é cliente, usuário do Staffast 
                ou se está apenas nos visitando, você pode ver nosso blog <a href="./">aqui</a>.</h6>

                <hr class="hr-divide-super-light">
            </div>
        </div>

        <?php if(!isset($_GET['postar'])) { ?>
        <div class="row" style="text-align: center;">
            <div class="col-sm">
                <form action="novoArtigo.php?postar=true" method="POST" id="formPostar">
                <h5 class="high-text">Insira o seu código de postagem</h5>
            </div>
            <div class="col-sm">
                <input type="text" class="all-input" name="codigo" id="codigo" value="<?php echo $codigo; ?>" required>
            </div>
            <div class="col-sm"  style="text-align: left;">
                <input type="submit" class="button button1" value="Verificar">
                </form>
            </div>
        </div>
        <?php } ?>

        <script>
            if(document.getElementById('codigo').value != "") {
                document.getElementById('formPostar').submit();
            }
        </script>

        <?php if(isset($_GET['postar'])) { 
        
                if($fetch['nome'] == "") {
                    ?>
                    <div class="row">
                        <div class="col-sm">
                            <h5 class="text">Não conseguimos encontrar nenhum cadastro com este código. Se continuar tendo problema, por 
                            favor, contate a equipe do Staffast.</h5>
                        </div>  
                    </div>
                    <?php
                    die();
                }

                if((int)$fetch['autorizado'] === 0) {
                    ?>
                    <div class="row">
                        <div class="col-sm">
                            <h5 class="text">Infelizmente o seu cadastro consta como "não autorizado para postagens" no Blog do Staffast. 
                            Por favor, entre em contato com a equipe do Staffast.</h5>
                        </div>  
                    </div>
                    <?php
                    die();
                }
        
        ?>

            <div class="row">
                <div class="col-sm">
                    <h3 class="text">Olá, <?php echo $fetch['nome']; ?></h3>
                </div>
            </div>

            <div class="row">
                <div class="col-sm">
                    <form action="novoArtigo.php?salvarArtigo=true" method="POST" enctype="multipart/form-data">
                    <input type="text" class="all-input" name="titulo" id="titulo" required maxlength="80" placeholder="Título do artigo">
                </div>
            </div>

            <div class="row">
                <div class="col-sm">
                    <textarea class="all-input" name="texto" id="texto" rows="6" cols="50" required placeholder="Insira o texto do artigo" cols="20"></textarea>
                </div>
            </div>

            <div class="row">
                <div class="col-sm">
                    <small class="text"><b>ATENÇÃO:</b> o texto que você inserir acima será formatado apenas as quebras de linha (ou seja, quando pressionar "Enter", 
                    o texto será salvo com a quebra de linha normalmente). Se você for usar tamanhos diferentes de fonte, listas, negrito, itálico, etc., deverá usar <b>tags HTML</b></small>
                </div>
            </div>

            <div class="row">
                <div class="col-sm">
                    <label class="text">Capa do artigo (opcional)</label>
                    <input type="file" name="capa" class="button button2">
                </div>
            </div>

            <div class="row" style="text-align: center;">
                <div class="col-sm">
                    <input type="hidden" name="id_autor" value="<?php echo $fetch['id']; ?>">
                    <input type="submit" class="button button1" value="Postar">
                    </form>
                </div>
            </div>

        <?php } ?>

</div>

</div>
</body>
</html>