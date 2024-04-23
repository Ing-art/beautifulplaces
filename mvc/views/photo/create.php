<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Add photo | <?=APP_NAME ?></title>
        <!--META-->
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="Add photo | <?=APP_NAME ?>">
        <meta name="author" content="Ingrid A.">

        <!--FAVICON -->
        <link rel="shortcut icon" href="/favicon.ico" type="image/png">

        <!--CSS-->
        <?= (TEMPLATE)::getCss() ?>

        <!--JS-->
		<?= (TEMPLATE)::getJs() ?>

        <!--JS SCRIPT FOR PREVIEW-->
        <script src="/js/Preview.js"></script>
        		
    </head>
    <body>
        <?= (TEMPLATE)::getLogin() ?>
        <?= (TEMPLATE)::getHeader('Add photo') ?>
        <?= (TEMPLATE)::getMenu() ?>
        <?= (TEMPLATE)::getFlashes() ?>
        <main>
            <div class="flex-container">
                <section class="flex1">
                    <h2>Details</h2>
                    <form method="POST" action="/Photo/store" enctype = "multipart/form-data">
                        <input type="hidden" name="iduser" value="<?=$iduser ?>">
                        <input type="hidden" name="idplace" value="<?=$idplace ?>">
                        <label>Name</label>
                        <input type="text" name="name" value="<?= old('name') ?>">
                        <br>
                        <label>Date</label>
                        <input type="date" name="date" value="<?= old('date') ?>">
                        <br>
                        <label>Time</label>
                        <input type="time" name="time" value="<?= old('time') ?>">
                        <br>
                        <label>Description</label>
                        <textarea name="description"><?= old('description') ?></textarea>
                        <br>
                        <label>Photo</label>
                        <input type="file" name="picture" accept="image/*" id="file-with-preview">
                        <br>
                        <input type="submit" class="button" name="save" value="Save">
                    </form>
            </section>
            <figure class="flex1 centrado">
                <img src="<?=PLACE_IMAGE_FOLDER.'/'.DEFAULT_PLACE_IMAGE ?>" id="preview-image"
                class="cover" alt="Image preview">
                <figcaption>Image preview</figcaption>
            </figure>
            </div> 
            <div class="centrado">
                <a class="button" onclick="history.back()">Back</a>
                <a class="button" href="/Photo/create">New Photo</a>
            </div>
        </main>
        <?= (TEMPLATE)::getFooter() ?>
    </body>
    </head>
</html>