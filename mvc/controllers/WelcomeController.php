<?php

/** Welcome
 *
 * Controlador por defecto, según la configuración de config.php
 *
 * updated on: 07/03/2023
 * 
 * FastLight Framework by @author Robert Sallent <robertsallent@gmail.com>
 */
class WelcomeController extends Controller{
    
    /** Carga la vista de portada. */
    public function index(){
        
        $places = Place::orderBy('created_at','DESC'); 
       
        $this->loadView('welcome',[
            'places' => $places

        ]);

    }
}

