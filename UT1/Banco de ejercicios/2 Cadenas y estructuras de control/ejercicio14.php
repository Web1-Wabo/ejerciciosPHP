<?php
    $texto = "PHP";
    $i = 0;
    while ($i < strlen($texto)) {
        if ($i === 1) {
            echo "-";
        }
        
        echo $texto[$i];
        $i++;
    }
    // Lo que hace es que mientras que i sea mayor que la longitud de texto
    // se repetira el bucle, si i es estrictamente igual a 1 imprimira un -
    // se imprime el contenido de texto, seguido del valor de i y despues se incrementa i
    // lo que imprimiria seria
    // PHP0  PHP-1  PHP2

?>