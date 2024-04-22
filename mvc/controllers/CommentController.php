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

}