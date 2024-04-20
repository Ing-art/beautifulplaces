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
                    <p><b>Created by:</b>           <?=$creator?></p>
                    <p><b>Created on:</b>           <?=$place->created_at ?></p>
                    <p><b>Updated on:</b>           <?=$place->updated_at ?></p>
                    
                </section>
                <figure class="flex1 centrado">
                    <img src="<?=PLACE_IMAGE_FOLDER.'/'.($place->cover ?? DEFAULT_PLACE_IMAGE) ?>"
                    class = "cover" alt="<?=$place->name ?>">
                    <figcaption>Cover picture of <?="$place->name" ?></figcaption>
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
                        <?php }

                    if(!Login::guest()){ ?>
                        <a class='button' href='/Photo/create/<?=$place->id ?>'>Add Photo</a>
                        <?php } ?>
                </div>

                <section>
                <h2>Photos</h2>
                <p>Click for comments and details</p>
                <div class="flex-container">
                <?php       
                    foreach($photos as $photo){?>
                        <figure class="flex1 centrado">
                        <a href='/Photo/show/<?=$photo->id?>'>
                            <img src="/images/places/<?=$photo->file?>"
                                class="cover" alt="Photo"></a>
                            <figcaption><?=$photo->name?></figcaption>
                        </figure>      
                    <?php }
                     ?>
                </div>
                </section>
                <section>
                    <h2>Comments</h2>
                </section>

                    
        </main>
        <?= (TEMPLATE)::getFooter() ?>
    </body>
</html>