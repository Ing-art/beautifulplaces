<?php

class PhotoController extends Controller{

        // Method to show the place details
        public function show(int $id = 0){  //FIXME error en voler veure una foto sense creador (usuari eliminat)
            // Check if the id is received as a parameter
            if(!$id){
                throw new Exception("No photo id received");         
            }
    
            $photo = Photo::find($id); // get the photo by the id
            $userid = $photo->belongsTo('User')->id; // belongsTo method (See MODEL)
            
    
            if(!$photo->belongsTo('User')->id){
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
    
            //Loads the view and pass the photo 
            $this->loadView('photo/show',[
                'photo' => $photo,
                'loggeduserid'=> $loggeduserid,
                'userid' => $userid,
                'creator' => $creator
            ]);
        }



















    // Method to show the input form
    public function create(int $id=0){

        $idplace = Place::findOrFail($id)->id;

        $user = Login::user();
        $iduser = $user->id;

        if(!Login::oneRole(['ROLE_USER','ROLE_MODERATOR'])){
            Session::error("Unauthorised operation!");
            redirect('/');
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
            redirect("/");// TODO redirect as per use case

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
                    redirect("/");  // TODO redirect as per user case

        }
    }      
}
