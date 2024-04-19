<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>New Account | <?=APP_NAME ?></title>
        <!--META-->
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="New account | <?=APP_NAME ?>">
        <meta name="author" content="Ingrid A.">

        <!--FAVICON -->
        <link rel="shortcut icon" href="/favicon.ico" type="image/png">

        <!--CSS-->
        <?= (TEMPLATE)::getCss() ?>

        <!--JS SCRIPT FOR PREVIEW-->
        <script src="/js/Preview.js"></script>

        <script>
            window.addEventListener('load', function(){
               inputEmail.addEventListener('change', function(){
                    fetch("/User/registered/"+this.value, {
                        "method":"GET"  
                    })
                    .then(function(response){
                        return response.json();
                    })
                    .then(function(json){
                        if(json.status == 'OK')
                            checkemail.innerHTML = json.registered ? 'mail already exists' : '';
                        else
                            checkemail.innerHTML = 'mail could not be checked';
                    });
                })
            })
            </script>
    </head>
    <body>
        <?= (TEMPLATE)::getLogin() ?>
        <?= (TEMPLATE)::getHeader('New account') ?>
        <?= (TEMPLATE)::getMenu() ?>
        <?= (TEMPLATE)::getFlashes() ?>
        <main>
            <div class="flex-container">
                <section class="flex1">
                <h1>Create a new account</h1>
                <h2>Personal details</h2>
                    <form method="POST" action="/User/store" enctype = "multipart/form-data">
                        <label>Name</label>
                        <input type="text" name="displayname" value="<?= old('displayname') ?>">
                        <br>
                        <label>E-mail</label>
                        <input type="email" name="email" id="inputEmail" value="<?= old('email') ?>">
                        <span id="checkemail" class='info'></span>
                        <br>
                        <label>Phone</label>
                        <input type="text" name="phone" value="<?= old('phone') ?>">
                        <br>
                        <label>Password</label>
                        <input type="password" name="password">
                        <br>
                        <label>Repeat Password</label>
                        <input type="password" name="repeatpassword">
                        <br>
                        <label>Picture</label>
                        <input type="file" name="picture" accept="image/*" id="file-with-preview">
                        <br>
                        <br>
                        <input type="submit" class="button" name="save" value="Submit">
                </form>
            </section> 
            <figure class="flex1 centrado">
                <img src="<?=USER_IMAGE_FOLDER.'/'.DEFAULT_USER_IMAGE ?>" id="preview-image"
                class="cover" alt="Photo preview">
                <figcaption>Photo preview</figcaption>
            </figure>
            </div> 
            <div class="centrado">
                <a class="button" onclick="history.back()">Back</a>
                <a class="button" href="/Add/list">Ad list</a>
            </div>
        </main>
        <?= (TEMPLATE)::getFooter() ?>
    </body>

    </head>
</html>