<?php
// Operations with users
class UserController extends Controller{

    public function home(){

        Auth::check(); // only logged users
        $user = Login::user();
        $useradds = $user->getAdds(); // get the user's adds

        $this->loadView('user/home', [
            'user'=>Login::user(),
            'useradds'=>$useradds
        ]);
    }

    public function list(int $page = 1, string $lang = 'ca'){
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
            throw new Exception("No s'ha indicat l'usuari a mostrar.");         
        }
        $user = User::find($id); // get the user
        $roles = $user->getRoles(); // get the user's roles
        $useradds = $user->getAdds(); // get the user's adds

        if(!$user){
            throw new NotFoundException("No s'ha trobat l'usuari indicat");
        }

        //Loads the view and pass the member
        $this->loadView('user/show',[
            'user' => $user,
            'roles' => $roles,
            'useradds'=> $useradds
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

            // send email with the user's details

            // prepare the message
            $to       = $user->email;
            $from     = "hello@fastlight.com";
            $name     = "New account";
            $subject  = "Welcome to Dynamo Classified Adds";
            $message  = "Welcome";
            
            // send email
            (new Email($to, $from, $name, $subject, $message))->send();
            Session::success("New user created. Confirmation email sent");
            redirect('/Login/index');

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
        }catch(EmailException $e){
            Session::error("Email not sent, contact with the webmaster.");
            
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

            $secondUpdate = false;
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
                $secondUpdate = true;
            }

            if(isset($_POST['eliminarfoto']) && $oldPhoto && !Upload::arrive('picture')){
                $user->picture = NULL;
                $secondUpdate = true;
            }

            if($secondUpdate){
                $user->update();
                @unlink('../public/'.USER_IMAGE_FOLDER.'/'.$oldPhoto); 
            }

            if($_POST['block'] == "block"){
                $current_date = new DateTime('now');
                $stringDate = $current_date->format('Y-m-d H:i:s');
                $user->blocked_at = $stringDate;
                $user->update();
            }else {
                $user->blocked_at = NULL;
                $user->update();
            }

            Session::success("Actualitzat l'usuari '$user->displayname' correctament");
            redirect("/User/show/$id");

        }catch(SQLException $e){
            Session::error("No s'ha pogut actualitzar l'usuari '$user->displayname'");
            if(DEBUG) // if debug mode is enabled
                throw new Exception($e->getMessage());
            else
                redirect("/User/edit/$id");
        }catch(UploadException $e){

            Session::warning("L'usuari s'ha actualitzat correctament, però no s'ha pogut pujar la imatge");

            if(DEBUG)
                throw new Exception($e->getMessage());
            else
                redirect("/User/edit/$user->id");
        }
    }

    // delete the user
    public function destroy(int $id=0){

        

        if(!$user = User::find($id))
            throw new Exception("User with id $id not found");

        try{
            User::delete($id);
            if($user->picture)
                    @unlink('../public/'.USER_IMAGE_FOLDER.'/'.$user->picture);
            Session::flash('success', 'User deleted');
            redirect("/User/list");
        }catch(SQLException $e){
            Session::flash('error', 'User could not be deleted');
            if(DEBUG)
                throw new Exception($e->getMessage());
            else
                redirect("/User/edit/$user->id");
        }
    }

    public function addNewRole(){
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