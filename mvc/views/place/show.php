<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Place details | <?=APP_NAME ?></title>
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

        <!--JS-->
		<?= (TEMPLATE)::getJs() ?>

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
                    if($userid === $loggeduserid){ ?>
                        <a class='button' href='/Place/edit/<?=$place->id ?>'>Edit</a>
                        <?php } ?>      
                       <?php 
                    if(Login::isAdmin() || $userid === $loggeduserid) { ?>
                        <a class='button' href='/Place/delete/<?=$place->id ?>'>Delete</a>
                        <?php }

                    if(Login::oneRole(['ROLE_USER','ROLE_MODERATOR']) && !Login::isAdmin()){ 
                        if(sizeof($photos) == 0){
                            $bigbuttonclass = 'big-button';
                            $buttontext= "Add first photo";
                        }else{
                            $buttontext="Add photo";
                        }?>
                        <a class="button <?=$bigbuttonclass?>" href='/Photo/create/<?=$place->id ?>'><?=$buttontext?></a>
                        <?php } ?>
                </div>
                <section>
                <h2>Photos</h2>
                <hr>
                <br>
                <?php 
                
                if(sizeof($photos) >=1){?> 
                    <p>Click for comments and details</p>
                <?php }else{ ?>
                    <p>No photos posted yet</p>
                <?php } ?>

                <div class="flex-container">
                <?php
                    if(sizeof($photos) == 1)
                        $class = 'big-centered';
                    else
                        $class = ''; 

                    foreach($photos as $photo){?>
                        <figure class="flex1 centrado <?=$class?>">
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
                    <?php
                        if(!($comments)){ ?>
                            <p>No comments yet</p>
                        <?php } ?>
                    <div>
                        <?php       
                            foreach($comments as $comment){?>
                                    <p style="font-weight:bold; display:block"><?= $comment->iduser ? $comment->belongsTo('User')->displayname :'Unknown'?> on <?=$comment->created_at?></p>
                                    <p style="display:block;"><?=$comment->text?></p>
                                    <?php 
                                    if(Login::oneRole(['ROLE_ADMIN','ROLE_MODERATOR']) || Login::user()->id == ($comment->iduser ? $comment->iduser : 'Unknown')){ ?>
                                        <p  style="display:block;"><a onclick="if(confirm('Are you sure?')) location.href='/Comment/destroy/<?=$comment->id?>'" style="text-decoration: underline; cursor:pointer;">Delete</a></p>                               
                       
                            <?php   } ?>
                                
                                <p>---------</p>

                            <?php }
                            ?>
                    </div>
                    </section>
                    <?php 
                    if(Login::oneRole(['ROLE_MODERATOR','ROLE_USER']) && !Login::isAdmin()){ ?> 
                        <section class="flex1">
                        <form method="POST" action="/Comment/store" enctype = "multipart/form-data">
                            <!--hidden input with the place id to edit-->
                            <input type="hidden" name="idplace" value="<?=$place->id?>">
                            <textarea type="text" name="text" value="<?= old('text') ?>">Add a comment..</textarea>
                            <br>
                            <input type="submit" class="button" name="save" value="Submit">
                        </form>
                        </section>
                    <?php } ?>                    
        </main>
        <?= (TEMPLATE)::getFooter() ?>
    </body>
</html>