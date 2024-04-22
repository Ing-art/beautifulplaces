<?php
    echo "<h1>Test - Comments</h1>";

        
    echo "<h3>find comments with idplace = 4:</h3>";
    dump(Place::findOrFail(1)->hasMany('Comment'));

    $comments = Place::findOrFail(1)->hasMany('Comment');
    $texts = [];
    $usernames = [];
    foreach($comments as $comment){
        $texts[] = $comments->text;
    }

    foreach($comments as $comment){
        $username = User::findOrFail($comment->iduser)->displayname;
        $usernames[] = $username;
    }



    dump($texts);
    dump($usernames);



    
          
    