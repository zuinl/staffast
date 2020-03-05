<?php
    include('../src/meta.php');
?>
<div class="row">
    <div class="col-sm-12">
        <h2 class="text">Desculpe, <?php echo $_SESSION['user']['primeiro_nome']; ?>, 
        mas parece que você <i><span class="destaque-text">não tem permissão</span></i> para acessar esta página</h2>
    </div>
</div>