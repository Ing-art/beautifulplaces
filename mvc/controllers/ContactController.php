<?php

// Controller for the contact operation
class ContactController extends Controller{

    public function index(){
        //load the contact form
        $this->loadView('contact');
    }

    // method to send the email to the admin

    public function send(){
        if(empty($_POST['send']))
            throw new Exception("Contact form not received");

        // prepare the message

        $from               = $this->request->post('email');
        $name               = $this->request->post('name');
        $subject            = $this->request->post('subject');
        $message            = $this->request->post('message');

        $email = new Email(ADMIN_EMAIL, $from, $name, $subject, $message);
        $email->send();
        Session::success("Message sent. We will get back to you shortly");
        redirect('/contact');
    }
}