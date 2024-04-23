<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Delete photo | <?=APP_NAME ?></title>
        <!--META-->
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="Delete photo <?=APP_NAME ?>">
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
        <?= (TEMPLATE)::getHeader('Delete photo') ?>
        <?= (TEMPLATE)::getMenu() ?>
        <?= (TEMPLATE)::getFlashes() ?>

        <main>
            <h1>Confirm delete photo</h1>
            <h2>Do you want to delete the photo: <?=$photo->name?>?</h2>
            <form method="POST" action="/Photo/destroy">

                <!--hidden input with the photo id to delete-->
                <input type="hidden" name="id" value="<?=$photo->id?>">
                <!--form-->
                
                <input class="button" type="submit" name="delete" value="Delete">
            </form> 
            <div class="centrado">
                <a class="button" onclick="history.back()">Back</a>
                <a class="button" href='/Place/list'>List</a>
                <a class="button" href='/Photo/show/<?=$photo->id ?>'>Show</a>
                <a class="button" href='/Photo/edit/<?=$photo->id ?>'>Edit</a>
            </div>
        </main>
        <?= (TEMPLATE)::getFooter() ?>
    </body>

    </head>
</html>