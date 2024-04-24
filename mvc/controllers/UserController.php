<?php
// Operations with users
class UserController extends Controller{

    public function home(){

        Auth::check(); // only logged users
        $user = Login::user();
        $userplaces = $user->hasMany('Place'); // get the user's places

        $this->loadView('user/home', [
            'user'=>Login::user(),
            'userplaces'=>$userplaces
        ]);
    }

    public function list(int $page = 1, string $lang = 'en'){
        if(!Login::isAdmin()){
            throw new Exception('Unauthorised operation!');
        }
        // Get the user list and loads the view
        // Within the view there will be a variable named $users
        $limit = RESULTS_PER_PAGE; // max number of results per page
        $filtro = Filter::apply('fitrousers');
        $total = $filtro ? // is there a filter?
                User::filteredResults($filtro) :  // total filtered results 
                User::total(); // total results

        $paginator = new Paginator('/User/list', $page, $limit, $total, $lang);

        $users = $filtro ?
                User::filter($filtro, $limit, $paginator->getOffset()) : // filtered     
                User::orderBy('displayname', 'ASC', $limit, $paginator->getOffset());

        $this->loadView('user/list', [
            'users' => $users,
            'paginator' => $paginator,  // pass the Paginator object to the view
            'filtro' => $filtro
        ]);
    }

    public function show(int $id = 0){
        // Check if the id is received as a parameter
        if(!$id){
            throw new Exception("No user to show");         
        }
        $user = User::find($id); // get the user
        $roles = $user->getRoles(); // get the user's roles

        if(!$user){
            throw new NotFoundException("User not found");
        }

        //Loads the view and pass the member
        $this->loadView('user/show',[
            'user' => $user,
            'roles' => $roles
        ]);
    }

    public function create(){

        $this->loadView('user/create');
    }

    public function store(){

        if(empty($_POST['save']))
            throw new Exception("Form not received");

        $user = new User(); // create a new user

        $user->password = md5($_POST['password']);
        $repeat = md5($_POST['repeatpassword']);

        if($user->password !=$repeat)
            throw new Exception("Passwords do not match");

        $user->displayname        =$this->request->post('displayname');
        $user->email              =$this->request->post('email');
        $user->phone              =$this->request->post('phone');
        $user->roles              ='["ROLE_USER"]';  //default role


        try{
            $user->save(); // save the user

            // update the user details with picture

            if(Upload::arrive('picture')){  
                $user->picture = Upload::save(
                    'picture', 
                    '../public/'.USER_IMAGE_FOLDER,
                    true,
                    0,  // max size
                    'image/*', // mime type 
                    'user_'
                );
                $user->update(); 
            }

            Session::success("Account for $user->displayname successfully created");
            redirect("/Login/index");

        }catch(SQLException $e){
            Session::error("Error occurred trying to save user $user->displayname");

            if(DEBUG)
                throw new Exception($e->getMessage());
            else
                redirect("/User/create");
        }catch(UploadException $e){
            Session::warning("Account successfully created. Picture not uploaded.");

            if(DEBUG)
                throw new Exception($e->getMessage());
            else
                redirect("/Login/index");
        }
    }  
 
    // edit user 
    public function edit(int $id=0){
        if(!$id){
            throw new Exception("User not received");
        }
        $user = User::find($id); // get the user from the DB
        $roles = $user->getRoles(); // get the user's roles

        if(!$user){
            throw new NotFoundException("User not found");
        }
        $this->loadView('user/edit', [
            'user' => $user,
            'roles' => $roles
        ]);
    }

    // update user details from the edit form
    public function update(){
        if(!$this->request->has('update')) // if the form is not received
            throw new Exception("Data not received");
        
        $id = intval($this->request->post('id')); // get the id via POST
        $user = User::find($id); // get the user from the DB

        

        if(!$user) //if the user is not found in the DB
            throw new NotFoundException("User with id $id not found");
        // get the rest of the fields

        $user->displayname                =$this->request->post('displayname');
        $user->phone                      =$this->request->post('phone');
        $user->email                      =$this->request->post('email');
                  

        try{
            $current_date = new DateTime('now');
            $stringDate = $current_date->format('Y-m-d H:i:s');
            $user->updated_at = $stringDate;
            $user->update();

            //$secondUpdate = false;
            $oldPhoto = $user->picture;

            if(Upload::arrive('picture')){ 
                $user->picture = Upload::save(
                    'picture',
                    '../public/'.USER_IMAGE_FOLDER,
                    true,
                    0,
                    'image/*',
                    'user_'
                );
                
            }

            $user->update();
            @unlink('../public/'.USER_IMAGE_FOLDER.'/'.$oldPhoto); 

            Session::success("User details of '$user->displayname' successfuly updated");
            Login::set(User::findOrFail($id));
            redirect("/User/home"); 
           

        }catch(SQLException $e){
            Session::error("Unable to update User details of '$user->displayname'");
            if(DEBUG) // if debug mode is enabled
                throw new Exception($e->getMessage());
            else
                redirect("/User/edit/$id");
        }catch(UploadException $e){

            Session::warning("User details updated. Unable to upload profile picture");

            if(DEBUG)
                throw new Exception($e->getMessage());
            else
                redirect("/User/edit/$user->id");
        }
    }


    // show the delete confirmation form
    public function delete(int $id=0){
        // check if the id is received
        if(!$id)
            throw new Exception("No user account to delete has been received");

        // get the book with the id
        $usertodelete = User::find($id);

        //check if the book exists
        if(!$usertodelete)
            throw new NotFoundException("User account with id $id does not exist");

        if($usertodelete->hasRole('ROLE_ADMIN')){
            throw new Exception("You are admin, you cannot delete your account");
        }

        $this->loadView('user/delete', ['usertodelete' => $usertodelete]);
    }

    // delete the user
    public function destroy(int $id=0){

        $id                =$this->request->post('id');

        $usertodelete = User::findOrFail($id);

        // check if the form is received
        if(!$this->request->has('delete'))
            throw new Exception("No confirmation received");

        if($usertodelete->hasRole('ROLE_ADMIN')){
                throw new Exception("You are admin, you cannot delete your account");
            }

        $id = intval($this->request->post('id')); // get the id
        $usertodelete = User::findOrFail($id); // get the user

        if(!$usertodelete)
            throw new Exception("User account with id $id not found");

        try{
            User::delete($id);
            if($usertodelete->picture)
                    @unlink('../public/'.USER_IMAGE_FOLDER.'/'.$usertodelete->picture);
            Session::success("User account of '$usertodelete->displayname' successfuly deleted");
            Session::destroy();
            redirect("/");
        }catch(SQLException $e){
            Session::flash('error', 'User account could not be deleted');
            if(DEBUG)
                throw new Exception($e->getMessage());
            else
                redirect("/User/edit/$usertodelete->id");
        }
    }

    public function addNewRole(){  //TODO add functionality to user management panel
        if(empty($_POST['add']))
            throw new Exception("form not received");

        $userid = intval($_POST['userid']); // get data from the edit form
        $newrole = $_POST['newrole'];
        $user = User::find($userid);
        

        if(!$userid || !$newrole){ 
            throw new Exception("User and role are mandatory");
        }
        else if(in_array($newrole, $user->getRoles())){ 
            throw new Exception("User '$user->displayname' has already '$newrole' granted");
        }
        try{
        
            $user->addRoles($newrole);
            Session::flash('success', "the role $newrole has been granted to '$user->displayname' ");
            redirect("/User/show/$userid");
            
        }catch(SQLException $e){
            Session::flash('error', "The role $newrole could not be granted to '$user->displayname' ");
            
            if(DEBUG)
                throw new Exception($e->getMessage());
            else
                redirect("/User/edit/$userid");
        }
    }

    public function deleteRole(){
        if(empty($_POST['remove']))
            throw new Exception("Form not received");

        $userid    = intval($_POST['userid']);
        $rol       = $_POST['rol'];
        $user = User::find($userid);

    
        if(!$userid || !$rol)
            throw new Exception("User and role are mandatory");
    
        try{
            $user->removeRoles($rol);
            Session::flash(
                    'success',
                        "the role $rol has been removed from '$user->displayname'"
                    );
            redirect("/User/show/$user->id");
    
            }catch(SQLException $e){
                    Session::flash(
                        'error',
                        "the role $rol could not be removed from $user->displayname"
                    );
                if(DEBUG)
                    throw new Exception($e->getMessage());
                else
                    redirect("/User/show/$user->id");
            }
    }

    public function registered(string $email = ''){

        $response = new StdClass();
        if(!Login::isAdmin()){
            $response->status = 'NOT AUTHORIZED';
            $response->registered = 'UNKNOWN';
        }else{
            try{
                $response->status = 'OK';
                $response->registered = User::checkEmail($email);
            }catch(SQLException $e){
                $response->status = 'ERROR';
                $response->registered = 'UNKNOWN';
            }
        }
        header('Content-Type: application/json'); 
        echo json_encode($response);
    }
}