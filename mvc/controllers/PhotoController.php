<?php

class PhotoController extends Controller{

        // Method to show the photo details
        public function show(int $id = 0){  //FIXME error en voler veure una foto sense creador (usuari eliminat)
            // Check if the id is received as a parameter
            if(!$id){
                throw new Exception("No photo id received");         
            }
    
            $photo = Photo::find($id); // get the photo by the id
            $userid = $photo->belongsTo('User')->id; // belongsTo method (See MODEL)
            
    
            if(is_null($photo->iduser)){ //if user is null (deleted)
                $creator = "Unknown";    
            }else{
                $creator = $photo->belongsTo('User')-> displayname;
            }       
            if(!Login::guest()){
                $loggeduserid = Login::user()->id;
                
            }else{
                $loggeduserid = NULL;
            }
            
    
            if(!$photo){
                throw new NotFoundException("Photo not found");
            } 
    
            // Check if the id is received as a parameter
            if(!$id){
                throw new Exception("Photo id not received");         
            }

            $comments = Photo::findOrFail($id)->hasMany('Comment');
    
            //Loads the view and pass the photo 
            $this->loadView('photo/show',[
                'photo' => $photo,
                'loggeduserid'=> $loggeduserid,
                'userid' => $userid,
                'creator' => $creator,
                'comments' => $comments
            ]);
        }


    // Method to show the input form
    public function create(int $id=0){

        $idplace = Place::findOrFail($id)->id;

        $user = Login::user();
        $iduser = $user->id;

        if(!Login::oneRole(['ROLE_USER','ROLE_MODERATOR'])){
            Session::error("Unauthorised operation!");
            redirect("/place/show/$id"); 
        }

        $this->loadView('photo/create',[
            'iduser' => $iduser,
            'idplace' => $idplace
            
        ]);
    }

    // Method to save the photo
    public function store(){
        // check if the request comes from the form
        if(!$this->request->has('save')){
            throw new Exception("No form received");
        }

        if(!Upload::arrive('picture')){
            Session::error("Please provide an image!");
            redirect('/photo/create'); //FIXME no redirecciona
        }

        $photo = new Photo(); // create a new photo
        $photo->name                     =$this->request->post('name');
        $photo->description              =$this->request->post('description');
        $photo->date                     =$this->request->post('date');
        $photo->time                     =$this->request->post('time');
        $photo->iduser                   =$this->request->post('iduser');
        $photo->idplace                  =$this->request->post('idplace');

       
        // with the try-catch the redirection to the error page is avoided
        // when an photo cannot be saved and the debug mode is desabled
        try{
            $photo->save(); //save the photo

            if(Upload::arrive('picture')){ // If there is a photo

                $photo->file = Upload::save(
                    'picture',
                    '../public/'.PLACE_IMAGE_FOLDER,
                    true,
                    2524000,
                    'image/*',
                    'photo_'
                );
                $photo->update();
            }

            Session::success("The photo: $photo->name has been saved");
            redirect("/place/show/$photo->idplace");// TODO redirect as per use case

        }catch(SQLException $e){
            Session::error("The photo: $photo->name could not be saved");
            // when debug mode is enabled, the error page is shown
            if(DEBUG)
                throw new Exception($e->getMessage());

                //Else redirect to the form page
                // The old values are restored with the helpers old()
            else
                redirect("/photo/create"); // redirect to the detail page

        }catch(UploadException $e){

                Session::warning("The photo has been saved but the picture could not be uploaded");

                if(DEBUG)
                    throw new Exception($e->getMessage());
                else
                    redirect("/photo/create");  // TODO redirect as per user case

        }
    }

    // Show the edit form
    public function edit(int $id=0){

        // get the userid of the logged user

        $photo = Photo::find($id); // get the photo by the id
        if(!$id){
            throw new Exception("Photo id not received");
        }

        if(!$photo){
            throw new NotFoundException("Photo not found");
        }


        if(!Login::guest()){
            $userid = $photo->belongsTo('User')->id; // get the photo creator id
            $loggeduserid = Login::user()->id;
            if($userid != $loggeduserid){ // if the user is not the creator 
                Session::error("Unauthorised operation!");
                redirect("/photo/show/$place->id");
            }

        }else{
            Session::error("Unauthorised operation!");
            redirect("/photo/show/$place->id");
        }


        $this->loadView('photo/edit',[
            'photo' => $photo,
            'userid' => $userid
        ]);
    }

    // Update the photo details from the edit form
    public function update(){
        Auth::check();

        if(!$this->request->has('edit')) // if the form is not received 
        throw new Exception("Data not received");

        $id = intval($this->request->post('id')); // get the id via POST
        $photo = Photo::findOrFail($id); // get the id from the DB


        if(Login::user()->id != $photo->iduser || Login::guest()){
            Session::error("Unauthorised operation!");
            redirect("/photo/show/$photo->id");
        }

       
        if(!$photo) //if the photo is not found in the DB
            throw new NotFoundException("Photo with id $id not found");
        // get the rest of the fields
        $currentDateTime = date('Y-m-d H:i:s');
        $photo->name                   = $this->request->post('name');
        $photo->description             = $this->request->post('description');
        $photo->updated_at              = $currentDateTime;


        try{
            $photo->update();
            Session::success("Photo: '$photo->name' successfully updated");
            redirect("/place/show/$photo->idplace"); 

        }catch(SQLException $e){
            Session::error("Photo: '$photo->name' could not be updated");
            if(DEBUG) // if debug mode is enabled
                throw new Exception($e->getMessage());
            else
                redirect("/photo/show/$id"); 
        }
    }

    // show the delete confirmation form
    public function delete(int $id=0){
        Auth::check();

        $loggeduser = Login::user();

        // check if the id is received
        if(!$id)
            throw new Exception("Photo to delete not specified");

        $photo = Photo::findOrFail($id); // get the id from the DB

        //check if the place exists
        if(!$photo)
            throw new NotFoundException("Photo with id $id does not exist");
     
        // check if the user is authorised
        if(!Login::isAdmin() && Login::user()->id != $photo->iduser && !$loggeduser->oneRole(['ROLE_MODERATOR'])){
            Session::error("Unauthorised operation!");
            redirect('/photo/show/$id'); // TODO redirect as per use case
        }

        $this->loadView('photo/delete', ['photo' => $photo]);
    }
    
    // delete the photo
    public function destroy(){
        // check if the form is received
        if(!$this->request->has('delete'))
            throw new Exception("No confirmation received");

        $id = intval($this->request->post('id')); // get the id
        $photo = Photo::findOrFail($id); // get the photo
        $place = $photo->belongsTo('Place');

        // check if the photo exists
        if(!$photo)
            throw new NotFoundException("Photo with $id not found");

            try{
                $photo->deleteObject();

                if($photo->file)
                    @unlink('../public/'.PLACE_IMAGE_FOLDER.'/'.$photo->file);
                
                Session::success("Photo '$photo->name' has been deleted");
                redirect("/place/show/$place->id"); 
            }catch(SQLException $e){
                Session::error("Photo '$photo->name' could not be deleted");
                if(DEBUG)
                    throw new Exception($e->getMessage());
                else
                    redirect("/photo/show/$photo->id"); //TODO redirect as per use case
            }
    }
    
}
