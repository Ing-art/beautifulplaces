<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<title>New Password |- <?= APP_NAME ?></title>
		
		<!-- META -->
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="description" content="New password <?= APP_NAME ?>">
		<meta name="author" content="Ingrid A.">
		
		<!-- FAVICON -->
		<link rel="shortcut icon" href="/favicon.ico" type="image/png">	
		
		<!-- CSS -->
		<?= (TEMPLATE)::getCss() ?>

		<!--JS-->
		<?= (TEMPLATE)::getJs() ?>
	</head>
	<body>
		<?= (TEMPLATE)::getLogin() ?>
		<?= (TEMPLATE)::getHeader('New pasword') ?>
		<?= (TEMPLATE)::getMenu() ?>
		<?= (TEMPLATE)::getFlashes() ?>
		
		<main>
			
    			
        		<form class="w50 bloque-centrado" method="POST" autocomplete="off" id="login" action="/Forgotpassword/send">
        			
        			<h2>Get a new password</h2>
    				<p class="justificado">Enter your details and you will get a new password to access the app.  
    					Remember to change it as soon as possible.</p>
    		
    				<div style="margin: 10px;">
            			<label for="email">E-mail:</label>
            			<input type="email" name="email" id="email" value="<?= old('email') ?>" required>
            			<br>
            			<label for="phone">Phone:</label>
            			<input type="text" name="phone" id="phone" value="<?= old('phone') ?>" required>
        			</div>
        			
        			<div class="centrado">
        				<input type="submit" class="button" name="nueva" value="New password">
        			</div>  
        			<div class="derecha">
    				<a href="/Login">Back to Login</a>
    			</div>      			
        		</form>
        		
        		
    		
		</main>
		
		<?= (TEMPLATE)::getFooter() ?>
	</body>
</html>

