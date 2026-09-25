<?php
    declare(strict_types=1);
    $paginas = "501";
    
    function esLarga(int $numPag):bool{
        if($numPag>500){
            return true;
        }else{
            return false;
        }
    }

    //echo esLarga($paginas);
    // Lo que ocurre es que como ahora las variables son estrictas para que funcione tendria que 
    // hacer un casting para que no de error

?>