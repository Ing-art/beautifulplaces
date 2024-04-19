<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Place details |<?=APP_NAME ?></title>
        <!--META-->
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="Place details | <?=APP_NAME ?>">
        <meta name="author" content="Ingrid A.">

        <!--FAVICON -->
        <link rel="shortcut icon" href="/favicon.ico" type="image/png">

        <!--CSS-->
        <?= (TEMPLATE)::getCss() ?>

        <!--SCRIPT FOR IMAGE PREVIEW-->

        <script src="/js/Preview.js"></script>
    </head>
    <body>
        <?= (TEMPLATE)::getLogin() ?>
        <?= (TEMPLATE)::getHeader('Place details') ?>
        <?= (TEMPLATE)::getMenu() ?>
        <?= (TEMPLATE)::getFlashes() ?>

        <main>
            <div class="flex-container">
                <section class="flex1"> 
                             
                    <h2><?=$place->name ?></h2>
                    <p><b>Type:</b>                 <?=$place->type?></p>
                    <p><b>Description:</b>          <?=$place->description ?></p>
                    <p><b>Location:</b>             <?=$place->location ?></p>
                    <p><b>Created on:</b>           <?=$place->created_at ?></p>
                    <p><b>Updated on:</b>           <?=$place->updated_at ?></p>
 
                </section>
                    <figure class="flex1 centrado">
                        <img src="<?=PLACE_IMAGE_FOLDER.'/'.($place->picture ?? DEFAULT_PLACE_IMAGE) ?>"
                        class = "cover" alt="Picture of <?=$place->title ?>">
                        <figcaption>Cover of <?="$place->name" ?></figcaption>
                    </figure>
                    </div>
                    <div class="centrado">
                        <a class="button" onclick="history.back()">Back</a>
                        <?php 
                        if($userid == $loggeduserid){ ?>
                            <a class='button' href='/Place/edit/<?=$place->id ?>'>Edit</a>
                        <?php } ?>      
                       <?php 
                       if(Login::isAdmin() || $userid == $loggeduserid) { ?>
                            <a class='button' href='/Place/delete/<?=$place->id ?>'>Delete</a>
                        <?php } ?>
                    </div>
                    
        </main>
        <?= (TEMPLATE)::getFooter() ?>
    </body>
</html>