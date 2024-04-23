<?php
    echo "<h1>Test - Comments</h1>";

        
    echo "<h3>find comments with idplace = 4:</h3>";
    dump(Place::findOrFail(8)->hasMany('Comment'));

    $comments = Place::findOrFail(8)->hasMany('Comment'); 
    $texts = [];
    $usernames = [];
    foreach($comments as $comment){
        $texts[] = $comment->text;
    }

    
    foreach($comments as $comment){  // user not deleted
        if(!is_null($comment->iduser)){
            $username = User::findOrFail(3)->displayname;
            $usernames[] = $username;
        }
    }



    dump($texts);
    dump($usernames);

/**
 * Test with a deleted user
 */

 dump(Place::findOrFail(9)->hasMany('Comment'));

 $comments = Place::findOrFail(9)->hasMany('Comment'); 
 $texts = [];
 $usernames = [];
 foreach($comments as $comment){
     $texts[] = $comment->text;
 }

 
 foreach($comments as $comment){  // user deleted
     if(!is_null($comment->iduser)){
         $username = $comment->belongsTo('User')->displayname;
         $usernames[] = $username;
     }
 }

 dump($texts);
 dump($usernames); // return an empty array
 dump(empty($usernames)); // TRUE confirm the array is empty


 var_dump(Comment::find(5)->iduser); // return NULL
 echo "<h3>Logic if the iduser is NULL</h3>";
 var_dump(empty(Comment::find(5)->iduser));
 var_dump((Comment::find(5)->iduser) ?? 'unknown');

















    
          
    