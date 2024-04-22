<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Edit place | <?=APP_NAME ?></title>
        <!--META-->
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="Edit place <?=APP_NAME ?>">
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
        <?= (TEMPLATE)::getHeader('Edit place') ?>
        <?= (TEMPLATE)::getMenu() ?>
        <?= (TEMPLATE)::getFlashes() ?>

        <main>
            <h1><?= APP_NAME ?></h1>
            <div class="flex-container">  
            <section class="flex1">
            <h2>Edit place</h2>
            <form method="POST" action="/Place/update" class="flex1" enctype="multipart/form-data">
                <!--hidden input with the user id to edit-->
                <input type="hidden" name="id" value="<?=$place->id?>">
                <!--form-->
                <label>Name</label>
                <input type="text" name="name" value="<?= $place->name?>">
                <br>
                <label>Type</label>
                    <select name="type">
                        <option value="<?=$place->type?>" <?= oldSelected('type', $place->type) ?>selected><?=$place->type?></option> 
                        <option value="Buildings" <?= oldSelected('type', 'Buildings') ?>>Buildings</option>
                        <option value="Cities" <?= oldSelected('type', 'Cities') ?>>Cities</option>
                        <option value="Landscape" <?= oldSelected('type', 'Landscape') ?>>Landscape</option>                 
                        <option value="Waterscape" <?= oldSelected('type', 'Waterscape') ?>>Waterscape</option>
                        <option value="Skyscape" <?= oldSelected('type', 'Skyscape') ?>>Skyscape</option>                   
                        <option value="Misc" <?= oldSelected('type', 'Misc') ?>>Misc</option>
                    </select>
                <br>
                <label>Location</label>
                <input type="text" name="location" value="<?= $place->location?>">
                <br>
                <label>Description</label>
                <textarea name="description"><?=$place->description?></textarea>
                <br>
                <label>Picture</label>
                <input type="file" name="picture" accept="image/*" id="file-with-preview">
                <br>
                <input type="submit" class="button" name="edit" value="Update">
            </form>
            </section>>
            <figure class="flex1 centrado">
                    <img src="<?=PLACE_IMAGE_FOLDER.'/'.($place->cover ?? DEFAULT_PLACE_IMAGE) ?>"
                        class="cover" id="preview-image" alt="<?=$place->cover ?>">
                    <figcaption>Picture</figcaption>
            </figure>
            </div>
            <div class="centrado">
                <a class="button" onclick="history.back()">Back</a>
                <a class="button"href='/Place/show/<?=$place->id ?>'>Show</a>
            </div>
        </main>
        <?= (TEMPLATE)::getFooter() ?>
    </body>
    </head>
</html>