<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>User list | <?=APP_NAME ?></title>
        <!--META-->
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="User list <?=APP_NAME ?>">
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
        <?= (TEMPLATE)::getHeader('User list') ?>
        <?= (TEMPLATE)::getMenu() ?>
        <?= (TEMPLATE)::getFlashes() ?>

        <main>
            <h2>User list</h2>
            <div>
            <?php if(!empty($filtro)){ ?>
                <form class="filtro derecha" method="POST" action="/User/list">
                    <label><?=$filtro ?></label>
                    <input class="button" style="display:inline" type="submit"
                                name="quitarFiltro" value="Remove filter">
                </form>
                <?php } else{ ?>
                    <form method="POST" class="filtro derecha" action="/User/list">
                        <input type="text" name="texto" placeholder="Search...">
                        <select name="campo">
                            <option value="displayname">Username</option>
                            <option value="email">E-mail</option>
                            <option value="roles">Role</option>
                        </select>
                        <label>Order by:</label>
                        <select name="campoOrden">
                            <option value="displayname">Username</option>
                            <option value="roles">Role</option>
                        </select>
                        <input type="radio" name="sentidoOrden" value="ASC" checked>
                        <label>Asc</label>
                        <input type="radio" name="sentidoOrden" value="DESC">
                        <label>Desc</label>
                        <input class="button" type="submit" name="filtrar" value="Filter">
                    </form>
                <?php } ?>
                </div>
            <div clas="flex1 container">
                <?=$paginator->stats()?>
            </div>
            <table>
                <tr>
                    <th>Picture</th><th>Username</th><th>E-mail</th><th>Roles</th><th>Created</th><th>Updated</th><th>Blocked</th><th>Operations</th>
                </tr>
                <?php foreach($users as $user){ ?>
                    <tr>
                        <td class="centrado">
                            <img src="<?=USER_IMAGE_FOLDER.'/'.($user->picture ?? DEFAULT_USER_IMAGE) ?>"
                            class="cover-mini" alt="<?=$user->displayname ?> profile picture">
                        </td>
                        <td><?=$user->displayname?></td>
                        <td><?=$user->email?></td>
                        <td><?php 
                        
                        $array = $user->roles;  // get the roles array and convert values to string
                        foreach ($array as $value){
                            echo $value." / ";
                        }     
                        ?></td>
                        <td><?=$user->created_at?></td>
                        <td><?=$user->updated_at?></td> 
                        <td><?=$user->blocked_at?></td>            
                        <td>

                        </td>
                    </tr>
               <?php } ?>
            </table>
            <?=$paginator->ellipsisLinks()?>
            <div class="centrado">
                <a class="button" onclick="history.back()">Back</a>
            </div>
        </main>
        <?= (TEMPLATE)::getFooter() ?>
    </body>
    </head>
</html>