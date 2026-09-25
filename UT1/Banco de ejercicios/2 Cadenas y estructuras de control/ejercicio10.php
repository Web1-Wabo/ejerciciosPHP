<?php
    $diasRestraso = 0;

    if($diasRestraso == 0){
        echo "sin retraso";
    }elseif($diasRestraso >=1 && $diasRestraso <=7){
        echo "retraso leve";
    }elseif($diasRestraso >=8 && $diasRestraso <=30){
        echo "retraso grave";
    }else{
        echo "bloqueo temporal";
    }
?>