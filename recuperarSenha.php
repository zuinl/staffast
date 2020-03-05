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
	<div class="row" style="margin-top: 0.8em;">
		<div class="col-sm-8 offset-sm-4" >
			<h1 class="high-text">Recuperação de <i><span class="destaque-text">acesso</span></i></h1>
		</div>
	</div>
	
	<hr class="hr-divide">

	<div class="container">
		<div class="row">
            <div class="col-sm-3">
            <form action="database/recuperarSenha.php" method="POST">
                <label class="text" for="email">E-mail</label>
                <input type="email" name="email" class="all-input" required="">
            </div>
            <div class="col-sm-3">
                <input type="submit" value="Mande-me uma nova senha" class="button button1">
            </div>
            </form>
		</div>

		<?php
			if(isset($_SESSION['msg'])) {
				?>
				<div class="row">
					<div class="alert alert-info alert-dismissible fade show" role="alert">
						<?php echo $_SESSION['msg']; unset($_SESSION['msg']); ?>
					</div>
				</div>
				<?php
			}
    ?>
    </div>
</html>