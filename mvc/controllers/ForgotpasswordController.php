<?php

/** ForgotpasswordController
 *
 * Se usa para regenerar la clave del usuario en caso de que lo solicite.
 *
 * updated on: 30/06/2023
 * 
 * FastLight Framework by @author Robert Sallent <robertsallent@gmail.com>
 */   
class ForgotpasswordController extends Controller{
    
    /** Muestra el formulario que solicita una nueva clave. */
    public function index(){
        view('forgotpassword');
    } 
    
    /**
     * Genera una nueva clave y la envía.
     * 
     * @throws Exception solamente en modo DEBUG, 
     * si no se puede generar y guardar la nueva clave o si no se
     * pudo enviar el email.
     */
    public function send(){
        
       if(empty($_POST['nueva']))
           throw new Exception("Form not received.");
           
       $email = $this->request->post('email'); // recupera el email
       $phone = $this->request->post('phone'); // recupera el teléfono
       
       $user = User::getByPhoneAndMail($phone, $email); // busca el usuario
       
       if(!$user){
            Session::error("Invalid data.");
            redirect('/Forgotpassword');
       }
       
       $password = uniqid();                // genera el nuevo password
       $user->password = md5($password);    // lo guarda en el user (encriptado)
       
       try{
            $user->update();    // actualiza el user
            
            // prepara el email
            $to       = $user->email;
            $from     = "passwordrecovery@fastlight.com";
            $name     = "Password generation system";
            $subject  = "Your new password";
            $message  = "Your new password is: <b>$password</b>, remember to change it as soon as possible.";
            
            // envía el email
            (new Email($to, $from, $name, $subject, $message))->send();
            Session::success("New password generated, check your mailbox.");
            redirect('/Login');
            
       // si no se pudo actualizar el password
       }catch(SQLException $e){
           Session::error("Password reset not possible.");
        
           if(DEBUG)
               throw new Exception($e->getMessage());
           else
               redirect("/Login");
           
       // si no se pudo enviar el email
       }catch(EmailException $e){
           Session::error("E-mail not sent, contact the administrator.");
           
           if(DEBUG)
               throw new Exception($e->getMessage());
           else
               redirect("/Login");       
       }
    }
}
    
    
    