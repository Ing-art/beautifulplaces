<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Places | <?=APP_NAME ?></title>
        <!--META-->
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="Place search <?=APP_NAME ?>">
        <meta name="author" content="Ingrid A.">

        <!--FAVICON -->
        <link rel="shortcut icon" href="/favicon.ico" type="image/png">

        <!--CSS-->
        <?= (TEMPLATE)::getCss() ?>

        <!--JS-->
		<?= (TEMPLATE)::getJs() ?>

    </head>
    <body>
        <?= (TEMPLATE)::getLogin() ?>
        <?= (TEMPLATE)::getHeader('Places') ?>
        <?= (TEMPLATE)::getMenu() ?>
        <?= (TEMPLATE)::getFlashes() ?>

        <main>

        <h1 class="centrado">Places search</h1>
            <div>
            <?php if(!empty($filtro)){ ?>
                <form class="filtro derecha" method="POST" action="/Place/list">
                    <label><?=$filtro ?></label>
                    <input class="button" style="display:inline" type="submit"
                                name="quitarFiltro" value="Remove filter">
                </form>
                <?php } else{ ?>
                    <form method="POST" class="filtro derecha" action="/Place/list">
                        <input type="text" name="texto" placeholder="Search...">
                        <select name="campo">
                            <option value="name">Name</option>
                            <option value="type">Type</option>
                            <option value="location">Location</option>
                            <option value="description">Description</option>
                        </select>
                        <label>Order By:</label>
                        <select name="campoOrden">
                            <option value="name">Name</option>
                            <option value="location">Location</option>
                        </select>
                        <input type="radio" name="sentidoOrden" value="ASC" checked>
                        <label class="labelcheck">Asc</label>
                        <input type="radio" name="sentidoOrden" value="DESC">
                        <label class="labelcheck">Desc</label>
                        <input class="button" type="submit" name="filtrar" value="Filter">
                    </form>
                <?php } ?>
                </div>
            <div clas="flex1 container">
                <?=$paginator->stats()?>
            </div>
            <table>
                <tr>
                    <th class="centrado">Cover</th><th class="centrado">Name</th><th class="centrado">Type</th><th class="centrado">Location</th><th class="centrado">Description</th><th class="centrado">Operations</th>
                </tr>
                <?php

                if(!$places){ ?>
                    <p class="centered">No results found</p> <?php 
                }

                ?>
                <?php foreach($places as $place) { ?>
                    <tr>
                        <td class="centrado">
                            <img src="<?=PLACE_IMAGE_FOLDER.'/'.($place->cover ?? DEFAULT_PLACE_IMAGE ) ?>"class="cover-mini" alt="picture of <?=$place->name ?>">
                        </td>
                        <td class="centrado"><?=$place->name?></td>
                        <td class="centrado"><?=$place->type?></td>
                        <td class="centrado"><?=$place->location?></td>
                        <td class="centrado"><?=$place->description?></td>
                        <td class="centrado">
                            <a class="button" href='/Place/show/<?=$place->id ?>'>Show</a> <!--FIXME -->
                        <?php if(!is_null($place->belongsTo('User')->id) && $loggeduserid === $place->belongsTo('User')->id){ ?>
                            <a class='button' href='/Place/edit/<?=$place->id ?>'>Edit</a>
                        <?php } ?>      
                        <?php 
                         if(!is_null($place->belongsTo('User')->id) && ($loggeduserid === $place->belongsTo('User')->id || Login::oneRole(['ROLE_ADMIN', 'ROLE_MODERATOR']))) { ?>
                            <a class='button' href='/Place/delete/<?=$place->id ?>'>Delete</a>
                        <?php } ?>
                        </td>
                    </tr>
               <?php } ?>
            </table>
            <?=$paginator->ellipsisLinks()?>	

        </main>
        <?= (TEMPLATE)::getFooter() ?>
    </body>
    </head>
</html>