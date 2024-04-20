<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>User home | <?=APP_NAME ?></title>
        <!--META-->
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="User homepage <?=APP_NAME ?>">
        <meta name="author" content="Ingrid A.">

        <!--FAVICON -->
        <link rel="shortcut icon" href="/favicon.ico" type="image/png">

        <!--CSS-->
        <?= (TEMPLATE)::getCss() ?>

    </head>
    <body>
        <?= (TEMPLATE)::getLogin() ?>
        <?= (TEMPLATE)::getHeader('My Account') ?>
        <?= (TEMPLATE)::getMenu() ?>
        <?= (TEMPLATE)::getFlashes() ?>
        <main>
            <div class="flex-container">
                <section class="flex2">
                    <h2><?="$user->displayname" ?></h2>
                    <p><b>Username: </b><?=$user->displayname ?></p>
                    <p><b>Email: </b><?=$user->email ?></p>
                    <p><b>Phone: </b><?=$user->phone ?></p>
                    <p><b>Joined: </b><?=$user->created_at ?></p>
                    <p><b>Updated: </b><?=$user->updated_at ?? '--' ?></p>
                </section>
                <figure class="flex1 centrado">
                    <img src="<?=USER_IMAGE_FOLDER.'/'.($user->picture ?? DEFAULT_USER_IMAGE) ?>"
                        class="cover" alt="Profile picture <?=$user->displayname ?>">
                    <figcaption><?=$user->displayname?> profile picture </figcaption>
                </figure>
            </div>

            <!-- Edit user's details -->
            <div class="centrado">
                    <a class="button" href="/User/edit/<?=$user->id?>">Edit</a>
                    <a class="button" href="/User/delete/<?=$user->id?>">Delete account</a>
            </div>

            <?php
            if(Login::oneRole(['ROLE_USER'])){ ?>  
            <section>
                <h2>Your places</h2>
                <?php
                    if(!$userplaces){ 
                        echo "<p>You have no places yet. Go to 'New Place' to create a new place.</p>";
                    }else{ ?>
                        <table>
                            <tr>
                                <th class="centrado">Name</th><th class="centrado">Type</th><th class="centrado">Location</th><th class="centrado">Description</th><th class="centrado">Created</th><th class="centrado">Operations</th>
                            </tr>
                            <?php
                            foreach($userplaces as $place){?>
                               <tr><td class="centrado"><?=$place->name ?></td>
                                <td class="centrado"><?=$place->type?></td>
                                <td class="centrado"><?=$place->location?></td>
                                <td class="centrado"><?=$place->description ?></td>
                                <td class="centrado"><?=$place->created_at ?></td>
                                <td class="centrado">
                                    <a class='buttonLight' href='/Place/show/<?=$place->id ?>'>Show</a>
                                    <a class='buttonLight' onclick="if(confirm('Delete?'))
                                            location.href='/Place/delete/<?=$place->id?>'">Delete</a>
                                    <a class='buttonLight' href='/Place/edit/<?=$place->id?>'>Edit</a>
                                </td>
                                </tr>               
                            <?php } 
                            ?>
                        </table>
                    <?php 
                    } 
                }?>
            </section>

        </main>
        <?= (TEMPLATE)::getFooter() ?>
    </body>
    </head>
</html>