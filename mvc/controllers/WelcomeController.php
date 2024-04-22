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
        view('welcome');
    }
    
    public function list(){
        // Get the place list and loads the view
        // Within the view there will be a variable named $places

        $places = Place::all();



        $this->loadView('welcome',[
            'places' => $places
        ]);

    }
}

