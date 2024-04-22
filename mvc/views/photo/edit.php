<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Edit photo | <?=APP_NAME ?></title>
        <!--META-->
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="Edit photo <?=APP_NAME ?>">
        <meta name="author" content="Ingrid A.">

        <!--FAVICON -->
        <link rel="shortcut icon" href="/favicon.ico" type="image/png">

        <!--CSS-->
        <?= (TEMPLATE)::getCss() ?>

        <!--JS FOR PREVIEW-->
        <script src="/js/Preview.js"></script>
    </head>
    <body>
        <?= (TEMPLATE)::getLogin() ?>
        <?= (TEMPLATE)::getHeader('Edit photo') ?>
        <?= (TEMPLATE)::getMenu() ?>
        <?= (TEMPLATE)::getFlashes() ?>

        <main>
            <h1><?= APP_NAME ?></h1>
            <div class="flex-container">  
            <section class="flex1">
            <h2>Edit photo</h2>
            <form method="POST" action="/Place/update" class="flex1" enctype="multipart/form-data">
                <!--hidden input with the user id to edit-->
                <input type="hidden" name="id" value="<?=$photo->id?>">
                <!--form-->
                <label>Name</label>
                <input type="text" name="name" value="<?= $photo->name?>">
                <br>
                <label>Description</label>
                <textarea name="description"><?=$photo->description?></textarea>
                <br>
                <input type="submit" class="button" name="edit" value="Update">
            </form>
            </section>>
            <figure class="flex1 centrado">
                    <img src="<?=PLACE_IMAGE_FOLDER.'/'.($photo->file ?? DEFAULT_PLACE_IMAGE) ?>"
                        class="cover" id="preview-image" alt="<?=$photo->file ?>">
                    <figcaption>Picture</figcaption>
            </figure>
            </div>
            <div class="centrado">
                <a class="button" onclick="history.back()">Back</a>
                <a class="button"href='/Place/show/<?=$photo->id ?>'>Show</a>
            </div>
        </main>
        <?= (TEMPLATE)::getFooter() ?>
    </body>
    </head>
</html>