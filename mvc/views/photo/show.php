<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Photo details |<?=APP_NAME ?></title>
        <!--META-->
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="Photo details | <?=APP_NAME ?>">
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
        <?= (TEMPLATE)::getHeader('Photo details') ?>
        <?= (TEMPLATE)::getMenu() ?>
        <?= (TEMPLATE)::getFlashes() ?>

        <main>
            <div class="flex-container">
                <section class="flex1"> 
                             
                    <h2><?=$photo->name ?></h2>
                    <p><b>Description:</b>          <?=$photo->description ?></p>
                    <p><b>Photo by:</b>             <?=$creator?></p>
                    <p><b>Created on:</b>           <?=$photo->created_at ?></p>
                    <p><b>Updated on:</b>           <?=$photo->updated_at ?></p>
                    
                </section>
                <figure class="flex1 centrado">
                    <img src="<?=PLACE_IMAGE_FOLDER.'/'.($photo->file ?? DEFAULT_PLACE_IMAGE) ?>"
                    class = "cover" alt="<?=$photo->name ?>">
                    <figcaption><?="$photo->name" ?></figcaption>
                </figure>
            </div>
            <div class="centrado">
                <a class="button" onclick="history.back()">Back</a>
                <?php 
                if($userid === $loggeduserid){ ?>
                    <a class='button' href='/Photo/edit/<?=$photo->id ?>'>Edit</a>
                    <?php } ?>      
                <?php 
                if(Login::isAdmin() || $userid === $loggeduserid) { ?>
                    <a class='button' href='/Photo/delete/<?=$photo->id ?>'>Delete</a>
                    <?php }?>
            </div>
            <section>
                <h2>Comments</h2>
                    <div>
                        <?php                           
                            foreach($comments as $comment){?>
                                <p style="font-weight:bold;"><?=User::findOrFail($comment->iduser)->displayname?> on <?=$comment->created_at?></p>
                                <p style="list-style-type:none;"><?=$comment->text?></p>
                                <?php 
                                if(Login::oneRole(['ROLE_ADMIN','ROLE_MODERATOR']) || Login::user()->id == $comment->iduser){ ?>
                                <p  style="list-style-type:none;"><a onclick="if(confirm('Are you sure?')) location.href='/Comment/destroy/<?=$comment->id?>'" style="text-decoration: underline; cursor:pointer;">Delete</a></p>                           
                        <?php } ?>
                                <p>------------------------</p>
                            <?php } ?>                           
                    </div>
            </section>
            <section class="flex1">
                <form method="POST" action="/Comment/store" enctype = "multipart/form-data">
                    <input type="hidden" name="idphoto" value="<?=$photo->id?>">
                    <textarea type="text" name="text" value="<?= old('text') ?>">Add a comment..</textarea>
                    <br>
                    <input type="submit" class="button" name="save" value="Submit">
                </form>
            </section>              
        </main>
        <?= (TEMPLATE)::getFooter() ?>
    </body>
</html>