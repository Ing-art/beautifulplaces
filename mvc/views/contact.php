<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Contact | <?=APP_NAME ?></title>
        <!--META-->
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="Contact <?=APP_NAME ?>">
        <meta name="author" content="Ingrid A.">

        <!--FAVICON -->
        <link rel="shortcut icon" href="/favicon.ico" type="image/png">

        <!--CSS-->
        <?= (TEMPLATE)::getCss() ?>

        <!--CAPTCHA-->
        
    </head>
    <body>
        <?= (TEMPLATE)::getLogin() ?>
        <?= (TEMPLATE)::getHeader("Contact <?=APP_NAME ?>") ?>
        <?= (TEMPLATE)::getMenu() ?>
        <?= (TEMPLATE)::getFlashes() ?>

        <main>
            <h1>Send us a message</h1>
            <div class="flex-container">
            <section class="flex1">
            <h2>Contact form</h2>
            <form method="POST" action="/Contact/send">
                <label>E-mail</label>
                <input type="email" name="email" required value="<?= old('email') ?>">
                <br>
                <label>Name</label>
                <input type="text" name="name" required value="<?= old('name') ?>">
                <br>
                <label>Subject</label>
                <input type="text" name="subject" required value="<?= old('subject') ?>">
                <br>
                <label>Message</label>
                <textarea name="message"><?= old('message') ?></textarea>
                <br>
                <br>

                <input type="submit" class="button" name="send" value="Send">
            </form> 
            </section>
            </div>
        </main>
        <?= (TEMPLATE)::getFooter() ?>
    </body>
    </head>
</html>