<?php

/* Clase Template
 *
 * Se usa para generar las partes comunes de todas las vistas
 *
 * FastLight framework by Robert Sallent
 * updated on: 16/03/2023
 *
 */

class Template implements TemplateInterface{
    
    // ficheros CSS para usar con este template
    protected static array $css = ['/css/base.css'];
    
    /*****************************************************************************
     * CSS
     *****************************************************************************/
    // método que prepara los links a todos los ficheros CSS configurados arriba
    public static function getCss(){
        $html = "";
        
        foreach(get_called_class()::$css as $file)
            $html .= "<link rel='stylesheet' type='text/css' href='$file'>\n";
            
            
        return $html;
    }

    
    /*****************************************************************************
     * LOGIN / LOGOUT
     *****************************************************************************/
    // retorna los enlaces a login/logout
    public static function getLogin(){
        
        // si el usuario no está identificado, retorna el botón de LogIn
        if(Login::guest())
            return "
               <div class='derecha'>
                    <a class='button' href='/Login'>LogIn</a>
               </div>
        ";
        
        $user = Login::user(); // recupera el usuario identificado
          
        // si el usuario es administrador...
        if(Login::isAdmin())
            return "
                 <div class='derecha'>
                    <span>Welcome <a class='negrita' href='/User/home'>$user->displayname</a> 
                    (<span class='cursiva'>$user->email</span>)
                    , you are <a class='negrita' href='/Admin'>admin</a>.</span> 
                    <a class='button' href='/Logout'>LogOut</a>
                 </div>
            ";  
        
        // si el usuario no es administrador...
        if(Login::check())
            return "
                 <div class='derecha'>
                    <span>Welcome <a href='/User/home'>$user->displayname</a>
                    (<span class='cursiva'>$user->email</span>).</span> 
                    <a class='button' href='/Logout'>LogOut</a>
                 </div>
            ";    
    
    }
        
        
    /*****************************************************************************
     * HEADER
     *****************************************************************************/
    // retorna el header
    public static function getHeader(
        string $titulo    = '', 
        string $subtitulo = NULL
    ){ 
        return "
            <header class='primary'>
                <figure>
                    <a href='/'>
                        <img style='width:100%;' alt='Logo' src='/images/template/logo.jpg'>
                    </a>
                </figure>
                <hgroup>
            	   <h1>$titulo <span class='small italic'> - ".APP_NAME."</small></h1>
                   <p>".($subtitulo ?? '')."</p>
                </hgroup>  
            </header>
        ";}
    
        
    /*****************************************************************************
     * MENÚ
     *****************************************************************************/
    // retorna el menú principal
    public static function getMenu(){ 
        $html  = "<ul class='navBar'>";
        $html  = "<menu>";
        $html .=   "<li><a href='/'>Start</a></li>";

        if(Login::check())
        $html .= "<li><a href='/User/home'>Account</a></li>";
        
        // enlace a la gestión de errores (solamente administrador)
        if(Login::isAdmin() && (DB_ERRORS || LOG_ERRORS || LOG_LOGIN_ERRORS))
            $html .=   "<li><a href='/Error/list'>Error Log</a></li>";
        
        // enlace a los tests de ejemplo (solamente administrador)    
        if(Login::isAdmin() && (DEBUG))
            $html .=   "<li><a href='/test'>Tests</a></li>";
    
        // Links to the places list 
        $html .= "<li><a href='/Place'>Places List</a></li>";

        if(Login::oneRole(['ROLE_USER']))
            $html .= "<li><a href='/Place/create'>New Place</a></li>";
        if(Login::guest())
            $html.="<li><a href='/User/create'>New Account</a></li>";

        $html .= "<li><a href='/Contact'>Contact</a></li>";

        $html .= "</ul>";
        
        $html .= "</menu>";

        return $html;
    } 
        
    /*****************************************************************************
     * MIGAS
     *****************************************************************************/
    // retorna el elementos migas
    public static function getBreadCrumbs(array $migas = []):string{
        // asegura que esté el enlace a Inicio
        $migas = ["Start"=>"/"]+$migas; 
        
        // preparamos el migas a partir del array 
        $html = "<nav aria-label='Breadcrumb' class='breadcrumbs'>";
        $html .= "<ul>";
        
        foreach($migas as $miga => $ruta){
            $html .= "<li>";
            $html .= $ruta ? "<a href='$ruta'>$miga</a>" : $miga;
            $html .= "</li>"; 
        }
        
        $html .= "</ul>";
        $html .= "</nav>";
        return $html;
    } 
    
    
        
          
    /*****************************************************************************
     * MENSAJES FLASHEADOS DE ÉXITO Y ERROR
     *****************************************************************************/
    // muestra mensajes de éxito flasheados
    public static function getSuccess(){
        
        return ($mensaje = Session::getFlash('success')) ?
        "<div class='mensajeExito' onclick='this.remove()'>
        	<div>
        		<h2>Operation successful</h2>
        		<p>$mensaje</p>
        		<p class='mini cursiva'>-- Click to close --</p>
    		</div>
        </div>": '';} 

    // muestra mensajes de warning flasheados
    public static function getWarning(){
            
        return ($mensaje = Session::getFlash('warning')) ?
        "<div class='mensajeWarning' onclick='this.remove()'>
        	<div>
        		<h2>Warnings:</h2>
        		<p>$mensaje</p>
        		<p class='mini cursiva'>-- Click to close --</p>
    		</div>
        </div>": '';}
                
    // muestra mensajes de error flasheados
    public static function getError(){

        return ($mensaje = Session::getFlash('error')) ?
        "<div class='mensajeError' onclick='this.remove()'>
        	<div>
        		<h2>Error!</h2>
        		<p>$mensaje</p>
        		<p class='mini cursiva'>-- Click to close --</p>
    		</div>
        </div>": '';} 
	
        
    // muestra los mensajes de success, error y warning flasheados
    public static function getFlashes(){
        return self::getSuccess().self::getWarning().self::getError();
    }
        
    
    /*****************************************************************************
     * FILTROS DE BÚSQUEDA
     *****************************************************************************/
    // retorna el formulario para realizar filtros y búsquedas
    public static function filterForm(
        string $action = '/',   // URL donde se enviará el formulario
        array $fields = [],     // lista de campos para el desplegable campo de búsqueda
        array $orders = [],      // lista de campos para el desplegable orden
        string $selectedField = '',
        string $selectedOrder = ''
        
    ){
        $html = "<form method='POST' class='filtro derecha' action='$action'>";
        $html .= "<input type='text' name='texto' placeholder='Search...'> ";
        $html .= "<select name='campo'>";
        
        foreach($fields as $nombre=>$valor){
            $html .= "<option value='$valor' ";
            $html .= $selectedField == $nombre ? 'selected' : '';
            $html .= ">$nombre</option>";
        }
        
        $html .= "</select>";
        
        $html .= "<label>Order by:</label>";
        $html .= "<select name='campoOrden'>";
        
        foreach($orders as $nombre=>$valor){
            $html .= "<option value='$valor' ";
            $html .= $selectedOrder == $nombre ? 'selected' : '';
            $html .= ">$nombre</option>";
        }
        
        return $html."</select>
    				<input type='radio' name='sentidoOrden' value='ASC'>
    				<label>Ascending</label>
    				<input type='radio' name='sentidoOrden' value='DESC' checked>
    				<label>Descending</label>
    				<input class='button' type='submit' name='filtrar' value='Filter'>
    			</form>";
    }
    
    // retorna el formulario de "quitar filtro"
    public static function removeFilterForm(
        Filter $filtro,
        string $action = '/'
    ){
        
        return "<form class='filtro derecha' method='POST' action='$action'>
					<label>$filtro</label>
					<input class='button' style='display:inline' type='submit' 
					       name='quitarFiltro' value='Remove Filter'>
				</form>";
    }
    
    
    
    /*****************************************************************************
     * FOOTER
     *****************************************************************************/
    // retorna el footer
    public static function getFooter(){
        return "
        <footer class='primary'>
            
            <p>FastLight Framework by Robert Sallent
                <a href='https://github.com/robertsallent/fastlight'>
                    <img src='/images/template/github.png' alt='GitHub'>
                </a>
            </p>
        </footer>";
    }       
}

