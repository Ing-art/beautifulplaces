<?php

    /* Intarface TemplateInterface
     *
     * Métodos que deben implementar los Templates.
     *
     * FastLight framework by Robert Sallent
     * updated on: 163/03/2023
     *
     */
    
    interface TemplateInterface{
        
       public static function getCSS();
       public static function getJs();
       public static function getLogin();
       public static function getHeader();
       public static function getMenu();
       public static function getBreadCrumbs();
       public static function getSuccess();
       public static function getError();
       public static function getFooter();
    }

