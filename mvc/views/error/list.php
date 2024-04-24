    <!DOCTYPE html>
    <html lang="es">
    	<head>
    		<meta charset="UTF-8">
			<title>Error list | <?= APP_NAME ?></title>
		
    		<!-- META -->
    		<meta name="viewport" content="width=device-width, initial-scale=1.0">
    		<meta name="description" content="Error list <?= APP_NAME ?>">
    		<meta name="author" content="Ingrid A.">
    		
    		<!-- FAVICON -->
    		<link rel="shortcut icon" href="/favicon.ico" type="image/png">	
    		
    		<!-- CSS -->
    		<?= (TEMPLATE)::getCss() ?>
    	</head>
    	<body>
    		<?= (TEMPLATE)::getLogin() ?>
    		<?= (TEMPLATE)::getHeader('Error log') ?>
    		<?= (TEMPLATE)::getMenu() ?>
    		<?= (TEMPLATE)::getFlashes() ?>

			        <!--JS-->
			<?= (TEMPLATE)::getJs() ?>


    		
    		<main>
        		<h1><?= APP_NAME ?></h1>
        		
        		<?php if(DB_ERRORS){ ?>
        		<section>
        		
            		<h2>Error log</h2>
        			
        			<p>Use the filter tool to search errors.</p>
        			   
        			<?php 
        			
        			// coloca el formulario de filtro
        			echo isset($filtro) ?
        			     (TEMPLATE)::removeFilterForm($filtro, '/Error/list'):
        			     
        			     (TEMPLATE)::filterForm(
            			     '/Error/list',
            			     [
            			         'Tipo' => 'level',
            			         'URL' => 'url',
            			         'Mensaje' => 'message',
            			         'Usuario' => 'user'
            			     ],[
            			         'Tipo' => 'level',
            			         'URL' => 'url',
            			         'Mensaje' => 'message',
            			         'Usuario' => 'user',
            			         'Fecha' => 'date'
            			     ], 
            			     'Mensaje',
            			     'Fecha'
		            );

        			     
        			if($errores) { ?>
     	
         				<div class="flex-container">
         					<div class="flex1">
            					<a class="button" href="/Error/clear">Empty list</a>
            				</div>
            				<div class="flex1 derecha">
            					<?= $paginator->stats()?>
            				</div>
            			</div>
            			
            			
            			<table>
                			<tr>
                				<th>Date</th>
                				<th>Type</th>
                				<th>URL</th>
                				<th>Message</th>
                				<th>User</th>
                				<th>IP</th>
                				<th>Operations</th>
                			</tr>
                    		<?php foreach($errores as $error){ ?>
                				<tr>
                    				<td><?=$error->date?></td>
                    				<td class='negrita'><?=$error->level?></td>
                    				<td class='cursiva'><?=$error->url?></td>
                    				<td><?=$error->message?></td>
                    				<td><?=$error->user ?? " -- "?></td>
                    				<td><?=$error->ip?></td>
                    				<td><a class="button" href="/Error/destroy/<?= $error->id ?>">Delete</a></td>
                			   </tr>
                    		<?php } ?>
                		</table>
                		
                		<?= $paginator->ellipsisLinks() ?>
            		
            		<?php }else{ ?>
            			<p class="success">No errors to show.</p>
            		<?php } ?>
            	</section>
            	<?php } ?>
        		
        		<?php if(LOG_ERRORS || LOG_LOGIN_ERRORS){ ?>
        		<section>
            		<h2>LOG files</h2>
            		<p>Files to store the error log in disk.</p>
            		
            		<h3>Download</h3>
            		
            		<p>Download links 
            		   (only if there are LOG files).</p>
            		   
            		<?php if(LOG_ERRORS && is_readable(ERROR_LOG_FILE)){ ?>
            		<a class="button" href="/Error/download">Download LOG</a>
            		<?php } ?>
            		
            		<?php if(LOG_LOGIN_ERRORS && is_readable(LOGIN_ERRORS_FILE)){ ?>
        			<a class="button" href="/Error/download/login">Download LogIn errors</a>
        			<?php } ?>
        			     			
            		<h3>Delete</h3>
            		<p>Delete links 
					(only if there are LOG files).</p>
            		   
            		<?php if(LOG_ERRORS && is_readable(ERROR_LOG_FILE)){ ?>
            		<a class="button" href="/Error/erase">Delete LOG</a>
            		<?php } ?>
            		
            		<?php if(LOG_LOGIN_ERRORS && is_readable(LOGIN_ERRORS_FILE)){ ?>
        			<a class="button" href="/Error/erase/login">Delete Login LOG</a>
        			<?php } ?>
        		</section>
        		<?php } ?>
        		
        		<nav class="enlaces centrado">
        			<a class="button" onclick="history.back()">Back</a>
        		</nav>
        		
    		</main>
    		<?= (TEMPLATE)::getFooter() ?>
    	</body>
    </html>

