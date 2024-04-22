<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Create a new place | <?=APP_NAME ?></title>
        <!--META-->
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="Create a new place | <?=APP_NAME ?>">
        <meta name="author" content="Ingrid A.">

        <!--FAVICON -->
        <link rel="shortcut icon" href="/favicon.ico" type="image/png">

        <!--CSS-->
        <?= (TEMPLATE)::getCss() ?>

        <!--JS SCRIPT FOR PREVIEW-->
        <script src="/js/Preview.js"></script>

    </head>
    <body>
        <?= (TEMPLATE)::getLogin() ?>
        <?= (TEMPLATE)::getHeader('Create a new place') ?>
        <?= (TEMPLATE)::getMenu() ?>
        <?= (TEMPLATE)::getFlashes() ?>
        <main>
            <div class="flex-container">
                <section class="flex1">
                    <h2>Details</h2>
                    <form method="POST" action="/Place/store" enctype = "multipart/form-data">
                        <input type="hidden" name="userid" value="<?=$userid ?>">
                        <label>Name</label>
                        <input type="text" name="name" value="<?= old('name') ?>">
                        <br>
                        <label>Type</label>
                        <select name="type">
                            <option value = "Select an option" disabled selected>Select an option</option>
                            <option value="Buildings" <?= oldSelected('type', 'Buildings') ?>>Buildings</option>
                            <option value="Cities" <?= oldSelected('type', 'Cities') ?>>Cities</option>
                            <option value="Landscape" <?= oldSelected('type', 'Landscape') ?>>Landscape</option>                 
                            <option value="Waterscape" <?= oldSelected('type', 'Waterscape') ?>>Waterscape</option>
                            <option value="Skyscape" <?= oldSelected('type', 'Skyscape') ?>>Skyscape</option>                   
                            <option value="Misc" <?= oldSelected('type', 'Misc') ?>>Misc</option>
                        </select>
                        <br>
                        <label>Location</label>
                        <input type="text" name="location" value="<?= old('location') ?>">
                        <br>
                        <label>Description</label>
                        <textarea name="description"><?= old('description') ?></textarea>
                        <br>
                        <label>Cover file</label>
                        <input type="file" name="picture" accept="image/*" id="file-with-preview">
                        <br>
                        <input type="submit" class="button" name="save" value="Save">
                    </form>
            </section>
            <figure class="flex5 centrado">
                <img src="<?=PLACE_IMAGE_FOLDER.'/'.DEFAULT_PLACE_IMAGE ?>" id="preview-image"
                class="cover" alt="Image preview">
                <figcaption>Image preview</figcaption>
            </figure>
            </div> 
            <div class="centrado">
                <a class="button" onclick="history.back()">Back</a>
                <a class="button" href="/Place/list">Place List</a>
            </div>
        </main>
        <?= (TEMPLATE)::getFooter() ?>
    </body>
    </head>
</html>