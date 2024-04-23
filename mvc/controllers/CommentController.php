<?php

class CommentController extends Controller{


    public function store(){

        if(!Login::oneRole(['ROLE_USER','ROLE_MODERATOR']) && Login::isAdmin()){
            Session::error("Unauthorised operation!");
            redirect('/Login');
        }

        $user = Login::user();
        $iduser = $user->id;

        if(empty($_POST['save']))
            throw new Exception("Form not received");

        $comment = new Comment(); // create a new comment


        $comment->text                 =$this->request->post('text');
        $comment->iduser               =$iduser;
        $comment->idplace              =$this->request->post('idplace');
        $comment->idphoto              =$this->request->post('idphoto');


        try{
            $comment->save(); // save the comment
            if(!$comment->idphoto)
                redirect("/place/show/$comment->idplace");

            redirect("/photo/show/$comment->idphoto");

        }catch(SQLException $e){
            Session::error("Error occurred trying to save comment");

            if(DEBUG)
                throw new Exception($e->getMessage());
            else
                redirect("/");
        }
    }
    
    // delete a comment
    public function destroy(int $id=0){
        
        
        $comment = Comment::findOrFail(intval($id));


        // check if the form is received
        if(!$comment = Comment::findOrFail($id)){
            throw new Exception('Comment not found');
        }

        if(!(Login::oneRole(['ROLE_ADMIN','ROLE_MODERATOR'])) && (intval($comment->iduser) != intval(Login::user()->id))){
            Session::error("Unauthorised operation!");
            redirect(isset($comment->idplace) ? "/place/show/{$comment->idplace}" : "/photo/show/{$comment->idphoto}");
        }


        try{
            $comment->deleteObject();
                
            Session::success("Comment deleted");
            redirect(isset($comment->idplace) ? "/place/show/{$comment->idplace}" : "/photo/show/{$comment->idphoto}");


        }catch(SQLException $e){
            Session::error("Comment could not be deleted");
        if(DEBUG)
            throw new Exception($e->getMessage());
        else
            redirect("/"); 
        }
    }

}