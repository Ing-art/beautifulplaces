<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Edit user details | <?=APP_NAME ?></title>
        <!--META-->
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="Edit user details <?=APP_NAME ?>">
        <meta name="author" content="Ingrid A.">

        <!--FAVICON -->
        <link rel="shortcut icon" href="/favicon.ico" type="image/png">

        <!--CSS-->
        <?= (TEMPLATE)::getCss() ?>

        <!--JS SCRIPT FOR PREVIEW-->
        <script src="/js/preview.js"></script>

        <!--JS-->
		<?= (TEMPLATE)::getJs() ?>
    </head>
    <body>
        <?= (TEMPLATE)::getLogin() ?>
        <?= (TEMPLATE)::getHeader('Edit account') ?>
        <?= (TEMPLATE)::getMenu() ?>
        <?= (TEMPLATE)::getFlashes() ?>
        <main>
        <div class="flex-container">
                <section class="flex1">
                    <h2>Edit details</h2>
                    <form method="POST" action="/User/update" class="flex1" enctype="multipart/form-data">
                        <!--hidden input with the user id to edit-->
                        <input type="hidden" name="id" value="<?=$user->id?>">
                        <!--form-->
                        <label>Username</label>
                        <input type="text" name="displayname" value="<?= $user->displayname?>">
                        <br>
                        <label>Phone</label>
                        <input type="text" name="phone" value="<?= $user->phone?>">
                        <br>
                        <label>E-mail</label>
                        <input type="email" name="email" value="<?= $user->email?>">
                        <br>
                        <label>Profile picture</label>
                        <input type="file" name="picture" accept="image/*" id="file-with-preview">
                        <br>  
                        <input type="submit" class="button" name="update" value="Update">
                    </form>
                </section>
                <figure class="flex1 centrado">
                    <img src="<?=USER_IMAGE_FOLDER.'/'.($user->picture ?? DEFAULT_USER_IMAGE) ?>"
                    class="cover" id="preview-image" alt="Foto de <?=$user->displayname ?>">
                    <figcaption>Profile picture - <?="$user->displayname"?></figcaption>
                 </figure>
        </div>
        <h2>User's role</h2>
            <?php if($roles){ ?>
                <ul class="listado">
                <?php foreach($roles as $rol){?>
                <li><span style="display: inline-block; min-width:150px;"><?=$rol?>
                </span>
                </li>
                <?php } ?>
                </ul>           
            <?php }else{
                echo "<p class='error'>No role granted</p>";
            } ?> 
        <div class="centrado">
            <a class="button" onclick="history.back()">Back</a>
            <a class="button"href='/User/show/<?=$user->id ?>'>Show</a>
            <a class='button' onclick="if(confirm('Are you sure?'))
                                                        location.href='/User/destroy/<?=$user->id ?>'">Delete</a>
        </div>
        </main>
        <?= (TEMPLATE)::getFooter() ?>
    </body>
    </head>
</html>