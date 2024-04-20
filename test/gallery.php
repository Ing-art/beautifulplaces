<?php
    echo "<h1>Test - Gallery</h1>";

    echo "<h2>Pruebas de los métodos para el modelo User.</h2>";
        
    echo "<h3>find photos with idplace = 4:</h3>";
    dump(Place::findOrFail(4)->hasMany('Photo'));

    $photos = Place::findOrFail(4)->hasMany('Photo');
    $files = [];
    $filenames = [];
    $testarray = [];
    foreach($photos as $photo){
        $files[] = $photo->file;
    }

    foreach($photos as $photo){
        $filenames[] = $photo->name;
    }



    dump($files);
    dump(empty($files));
    dump($filenames);
    dump(empty($filenames));
    dump(empty($testarray));

    echo "<h3>find photo details id = 4:</h3>";

    $photo = Photo::find(4); // get the photo by the id
    $userid = $photo->belongsTo('User')->id; // belongsTo method (See MODEL)
            
    
    if(!$photo->belongsTo('User')->id){
        $creator = "Unknown";    
    }else{
        $creator = $photo->belongsTo('User')-> displayname;
    }
    
          
    