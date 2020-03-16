<?php
    include('../src/meta.php');
?>
<body>
    <div class="container" style="text-align: center;">

        <div class="row">
            <div class="col-sm">
                <img src="../img/logo_staffast.png" width="220">
            </div>
        </div>

        <hr class="hr-divide-super-light">

        <div class="row">
            <div class="col-sm">
                <h1 class="high-text">Acesso negado</h1>
            </div>
        </div>

        <div class="row">
            <div class="col-sm">
                <h2 class="text">Desculpe, <?php echo $_SESSION['user']['primeiro_nome']; ?>, 
                mas parece que você <i><span class="destaque-text">não tem permissão</span></i> para acessar esta página</h2>
            </div>
        </div>
    </div>
</body>