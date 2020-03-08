<?php
session_start();
include('src/meta.php');
?>
<!DOCTYPE html>
<html>
<head>
	<title>Recuperar senha</title>
</head>
<body style="margin-top: 0em;">
<div class="container" style="text-align: center;">
	<div class="row">
        <div class="col-sm" style="text-align: center;">
            <img src="img/logo_staffast.png" width="300">
        </div>
    </div>
	<div class="row" style="margin-top: 0.8em;">
		<div class="col-sm" >
			<h2 class="high-text">Recuperação de acesso</h2>
		</div>
	</div>
	
	<hr class="hr-divide-super-light">

		<div class="row">
            <div class="col-sm">
            <form action="database/recuperarSenha.php" method="POST">
                <label class="text" for="email">E-mail</label>
                <input type="email" name="email" class="all-input" required="">
            </div>
		</div>
		<div class="row">
            <div class="col-sm">
                <input type="submit" value="Mande-me uma nova senha" class="button button1">
            </div>
            </form>
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
    </div>
</html>