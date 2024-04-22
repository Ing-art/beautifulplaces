<!DOCTYPE html>
<html lang="es">
	<head>
		<meta charset="UTF-8">
		<title>Home | <?= APP_NAME ?></title>
		
		<!-- META -->
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="description" content="Home <?= APP_NAME ?>">
		<meta name="author" content="Ingrid A.">
		
		<!-- FAVICON -->
		<link rel="shortcut icon" href="/favicon.ico" type="image/png">	
		
		<!-- CSS -->
		<?= (TEMPLATE)::getCss() ?>
		<link href="/css/bootstrap.min.css" rel="stylesheet"> <!--TODO consolidar BS -->

		<?php

		$places = Place::all(); //TODO via controller??

		?>

	<body>
		<?= (TEMPLATE)::getLogin() ?>
		<?= (TEMPLATE)::getHeader('Welcome') ?>
		<?= (TEMPLATE)::getMenu() ?>
		<?= (TEMPLATE)::getFlashes() ?>
		
		<main>
    		<h1><?= APP_NAME ?></h1>
			<div class="row row-cols-1 row-cols-md-3 g-4">
				<?php foreach($places as $place){ ?> 
						<div class="col">
							<div class="card h-100">
    							<a href="/place/show/<?=$place->id?>"><img src="<?=PLACE_IMAGE_FOLDER.'/'.$place->cover?>" class="card-img-top"
      							alt="<?=$place->name?>"></a>
    							<div class="card-body">
      								<h5 class="card-title"><?=$place->name?></h5>
      									<p class="card-text"><?=$place->description?></p>
      									<p class="card-text">
        									<small class="text-muted">Published <?=$place->created_at?></small>
										</p>
    							</div>
							</div>
						</div>
				<?php } ?>
			</div>		
		</main>
		<?= (TEMPLATE)::getFooter() ?>
	</body>
</html>

