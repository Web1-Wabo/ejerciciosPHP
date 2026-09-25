<?php
    $array1=[1,2,3];
    $array2=[4,5,6];

    unset($array1[1]);
    $newArray=array_merge($array1,$array2);
    sort($newArray);
    
    foreach($newArray as $num => $n){
        echo "$num: $n <br>";
    }


    //Esto es de prueba para ver que se ha realizado correctamente
    echo "<br>";
    foreach($array1 as $num => $n){
        echo "$num: $n <br>";
    }
?>