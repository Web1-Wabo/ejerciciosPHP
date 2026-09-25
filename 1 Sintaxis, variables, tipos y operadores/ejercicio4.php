<?php
    $a = (5 == "5"); //Esto es true, porque hace la comparacion no estricta
    $b = (5 === "5"); // Aqui daria false, porque hace un comparacion estricta
    $c = (10 > 5 && 3 < 2); //Saldria false, porque no se cumplen las 2 conduciones del AND
    $d = !$b || $c; //Aqui saldria true, porque el negativo de b es true y c es false, pero es un OR
?>