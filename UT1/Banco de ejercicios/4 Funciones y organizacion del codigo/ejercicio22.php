<?php
    $paginas = 501;
    
    function esLarga(int $numPag):bool{
        if($numPag>500){
            return true;
        }else{
            return false;
        }
    }

    // Para hacer la comprobacion
    //echo( esLarga($paginas));
    
?>