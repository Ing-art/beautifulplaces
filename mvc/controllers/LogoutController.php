<?php

/** LogoutController
 *
 * Controlador para la operación de logout
 *
 * updated on: 21/03/2023
 * 
 * FastLight Framework by @author Robert Sallent <robertsallent@gmail.com>
 */
    
class LogoutController extends Controller{
    
    /** Gestiona la operación de logout. */
    public function index(){
        Auth::check();   // solo para usuarios identificados
        Login::clear();  // elimina los datos de sesión y desvincula el usuario      
        redirect('/');   // redirige a la portada 
    } 
}
    
    
    