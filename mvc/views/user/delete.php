<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Delete account | <?=APP_NAME ?></title>
        <!--META-->
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="Delete account <?=APP_NAME ?>">
        <meta name="author" content="Ingrid A.">

        <!--FAVICON -->
        <link rel="shortcut icon" href="/favicon.ico" type="image/png">

        <!--CSS-->
        <?= (TEMPLATE)::getCss() ?>
    </head>
    <body>
        <?= (TEMPLATE)::getLogin() ?>
        <?= (TEMPLATE)::getHeader('Delete account') ?>
        <?= (TEMPLATE)::getMenu() ?>
        <?= (TEMPLATE)::getFlashes() ?>

        <main>
            <h1><?= APP_NAME ?></h1>
            <h2>Delete account</h2>
            <form method="POST" action="/User/destroy">
                <p><b>Are you sure you want to delete your account <?=$usertodelete->displayname?>?</b></p>
                <!--hidden input with the user id to edit-->
                <input type="hidden" name="id" value="<?=$usertodelete->id?>">

                <!--form-->        
                <input class="button" type="submit" name="delete" value="Delete account">
            </form> 
            <div class="centrado">
                <a class="button" onclick="history.back()">Back</a>
                <a class="button" href='/User/home'>Account</a>
                <a class="button" href='/User/edit/<?=$usertodelete->id ?>'>Edit</a>
            </div>
        </main>
        <?= (TEMPLATE)::getFooter() ?>
    </body>

    </head>
</html>