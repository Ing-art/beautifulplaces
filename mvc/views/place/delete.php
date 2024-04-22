<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Delete place | <?=APP_NAME ?></title>
        <!--META-->
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="Delete place <?=APP_NAME ?>">
        <meta name="author" content="Ingrid A.">

        <!--FAVICON -->
        <link rel="shortcut icon" href="/favicon.ico" type="image/png">

        <!--CSS-->
        <?= (TEMPLATE)::getCss() ?>

    </head>
    <body>
        <?= (TEMPLATE)::getLogin() ?>
        <?= (TEMPLATE)::getHeader('Delete place') ?>
        <?= (TEMPLATE)::getMenu() ?>
        <?= (TEMPLATE)::getFlashes() ?>

        <main>
            <h1>Confirm delete place</h1>
            <h2><?=$place->name?></h2>
            <form method="POST" action="/Place/destroy">

                <!--hidden input with the place id to delete-->
                <input type="hidden" name="id" value="<?=$place->id?>">
                <!--form-->
                
                <input class="button" type="submit" name="delete" value="Delete">
            </form> 
            <div class="centrado">
                <a class="button" onclick="history.back()">Back</a>
                <a class="button" href='/Place/list'>List</a>
                <a class="button" href='/Place/show/<?=$place->id ?>'>Show</a>
                <a class="button" href='/Place/edit/<?=$place->id ?>'>Edit</a>
            </div>
        </main>
        <?= (TEMPLATE)::getFooter() ?>
    </body>

    </head>
</html>