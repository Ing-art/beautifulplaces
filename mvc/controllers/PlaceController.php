<?php

class PlaceController extends Controller{

    // Redirect to the method $list
    public function index(){
        $this->list(); 
    }

    // List Place method 
    public function list(int $page = 1, string $lang = 'en'){  
        // Get the place list and loads the view
        // Within the view there will be a variable named $places

        $limit = RESULTS_PER_PAGE; // max number of results per page
        $filtro = Filter::apply('filtroplaces');

        $total = $filtro ?  // check if there is any filter in place
                        Place::filteredResults($filtro) : // total of filtered results
                        Place::total(); // total results

        $paginator = new Paginator('/Place/list', $page, $limit, $total, $lang);

    
        $places = $filtro ?  // is there a filter?
                Place::filter($filtro, $limit, $paginator->getOffset()) : // filtered
                Place::orderBy('created_at', 'DESC', $limit, $paginator->getOffset());

        if(!Login::guest()){
            $loggeduserid = Login::user()->id;
                    
        }else{
            $loggeduserid = NULL;
        }
                

    
        $this->loadView('/place/list', [
            'places' => $places,
            'paginator' => $paginator, // pass the Paginator object to the view
            'filtro' => $filtro,
            'loggeduserid'=> $loggeduserid
        ]);

    }

    // Method to show the place details
    public function show(int $id = 0){  
        // Check if the id is received as a parameter
        if(!$id){
            throw new Exception("No place id received");         
        }

        $place = Place::find($id); // get the place by the id
        
        
        if(is_null($place->iduser)){ // if user is null (deleted)
            $creator = "Unknown"; 
            $userid = 0;
               
        }else{
            $creator = $place->belongsTo('User')-> displayname;
            $userid = $place->belongsTo('User')->id; // belongsTo method (See MODEL)
        }

      
        if(!Login::guest()){
            $loggeduserid = Login::user()->id;
            
        }else{
            $loggeduserid = NULL;
        }
        

        if(!$place){
            throw new NotFoundException("The place was not found");
        } 

        // Check if the id is received as a parameter
        if(!$id){
            throw new Exception("No place id received");         
        }

        $photos = Place::findOrFail($id)->hasMany('Photo');
        $comments = Place::findOrFail($id)->hasMany('Comment');



        //Loads the view and pass the place 
        $this->loadView('place/show',[
            'place' => $place,
            'loggeduserid'=> $loggeduserid,
            'userid' => $userid,
            'creator' => $creator,
            'photos' => $photos,
            'comments' => $comments

        ]);


    }


    // Method to show the input form
    public function create(){

        if(!Login::oneRole(['ROLE_USER','ROLE_MODERATOR'])){
            Session::error("Unauthorised operation!");
            redirect('/Login'); 
        }

        if(!Login::guest() && !Login::isAdmin()){
            $user = Login::user();
            $userid = $user->id;
        
        }else{
            $userid = NULL;
        }


        $this->loadView('place/create',[
            'userid' => $userid]
        );
    }

    // Method to save the place
    public function store(){

        if(!Login::oneRole(['ROLE_USER','ROLE_MODERATOR'])){
            Session::error("Unauthorised operation!");
            redirect('/Login'); 
        }
        // check if the request comes from the form
        if(!$this->request->has('save')){
            throw new Exception("No form received");
        }

        if(!Upload::arrive('picture')){
            Session::error("Please provide a cover image!");
            redirect('/place/create');
        }


        $place = new Place(); // create a new place
        $place->name                     =$this->request->post('name');
        $place->description              =$this->request->post('description');
        $place->type                     =$this->request->post('type');
        $place->location                 =$this->request->post('location');
        $place->iduser                   =$this->request->post('userid');

       
        // with the try-catch the redirection to the error page is avoided
        // when an place cannot be saved and the debug mode is desabled
        try{
            $place->save(); //save the place

            if(Upload::arrive('picture')){ // If there is a place picture

                $place->cover = Upload::save(
                    'picture',
                    '../public/'.PLACE_IMAGE_FOLDER,
                    true,
                    2524000,
                    'image/*',
                    'place_'
                );
                $place->update();
            }

            Session::success("A new place: $place->name has been saved");
            redirect("/place/show/$place->id");// redirect to the show page

        }catch(SQLException $e){
            Session::error("The place: $place->name could not be saved");
            // when debug mode is enabled, the error page is shown
            if(DEBUG)
                throw new Exception($e->getMessage());

                //Else redirect to the form page
                // The old values are restored with the helpers old()
            else
                redirect("/place/create"); // redirect to the detail page

        }catch(UploadException $e){

                Session::warning("The place has been saved but the picture could not be uploaded");

                if(DEBUG)
                    throw new Exception($e->getMessage());
                else
                    redirect("/");  // TODO redirect as per user case

        }
    }      


    // Show the edit form
    public function edit(int $id=0){

        // get the userid of the logged user

        $place = Place::find($id); // get the place by the id
        if(!$id){
            throw new Exception("Place id not received");
        }

        if(!$place){
            throw new NotFoundException("Place not found");
        }


        if(!Login::guest()){
            $userid = $place->belongsTo('User')->id; // get the place creator id
            $loggeduserid = Login::user()->id;
            if($userid != $loggeduserid){ // if the user is not the creator 
                Session::error("Unauthorised operation!");
                redirect("/place/show/$place->id");
            }

        }else{
            Session::error("Unauthorised operation!");
            redirect("/place/show/$place->id");
        }


        $this->loadView('place/edit',[
            'place' => $place,
            'userid' => $userid
        ]);
    }

    // Update the place details from the edit form
    public function update(){
        Auth::check();

        if(!$this->request->has('edit')) // if the form is not received 
        throw new Exception("Data not received");

        $id = intval($this->request->post('id')); // get the id via POST
        $place = Place::findOrFail($id); // get the id from the DB

        if(Login::user()->id != $place->iduser || Login::guest()){
            Session::error("Unauthorised operation!");
            redirect("/place/show/$place->id"); // redirect to show
        }

       
        if(!$place) //if the place is not found in the DB
            throw new NotFoundException("The place with id $id could not be found");
        // get the rest of the fields
        $currentDateTime = date('Y-m-d H:i:s');
        $place->name                   = $this->request->post('name');
        $place->description             = $this->request->post('description');
        $place->location                = $this->request->post('location');
        $place->type                   = $this->request->post('type');
        $place->updated_at              = $currentDateTime;


        try{
            $place->update();

            if(Upload::arrive('picture')){
                $place->cover = Upload::save(
                'picture', '../public/'.PLACE_IMAGE_FOLDER, true, 0, 'image/*', 'place_');
            }
            $place->update();

            Session::success("Place: '$place->name' successfully updated");
            redirect("/place/edit/$place->id"); // redirect as per use case

        }catch(SQLException $e){
            Session::error("Place: '$place->name' could not be updated");
            if(DEBUG) // if debug mode is enabled
                throw new Exception($e->getMessage());
            else
                redirect("/place/edit/$place->id"); // redirect to show
        }
    }

    // show the delete confirmation form
    public function delete(int $id=0){
        Auth::check();

        $loggeduser = Login::user();

        // check if the id is received
        if(!$id)
            throw new Exception("Place to delete not specified");

        $place = Place::findOrFail($id); // get the id from the DB

        //check if the place exists
        if(!$place)
            throw new NotFoundException("Place with id $id does not exist");
     
        // check if the user is authorised
        if(!Login::isAdmin() && Login::user()->id != $place->iduser && !$loggeduser->oneRole(['ROLE_MODERATOR','ROLE_ADMIN'])){
            Session::error("Unauthorised operation!");
            redirect('/');
        }

        $this->loadView('place/delete', ['place' => $place]);
    }
    
    // delete the place
    public function destroy(){
        // check if the form is received
        if(!$this->request->has('delete'))
            throw new Exception("No confirmation received");



        $id = intval($this->request->post('id')); // get the id
        $place = Place::find($id); // get the place

        // check if the user is authorised
        if(!Login::isAdmin() && Login::user()->id != $place->iduser && !$Login::user()->oneRole(['ROLE_MODERATOR','ROLE_ADMIN'])){
            Session::error("Unauthorised operation!");
            redirect('/');
        }

        // check if the place exist
        if(!$place)
            throw new NotFoundException("The place with $id could not be found");

            try{
                $place->deleteObject();

                if($place->cover)
                    @unlink('../public/'.PLACE_IMAGE_FOLDER.'/'.$place->cover);
                
                Session::success("Place '$place->name' has been deleted");
                redirect("/place/list"); 

            }catch(SQLException $e){
                
                Session::error("Place '$place->name' could not be deleted");
                if(DEBUG)
                    throw new Exception($e->getMessage());
                else
                    redirect("/place/show/$place->id"); 
            }
    }

}