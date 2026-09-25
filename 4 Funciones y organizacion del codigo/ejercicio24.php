<?php
//    $contador = 0;
//    function incrementar() {
//        $contador++;
//    }
//    incrementar();  
//    echo $contador;

    // El codigo no funciona porque la funcion no tiene ningun valor de entrada ni de salida

    $contador = 0;
    //esto echo lo implemento para ver que funciona correctamente
    echo "$contador";

    function incrementar(int &$contador):void {
        $contador++;
    }
    incrementar($contador);
    echo $contador;


?>