<?php
    $tipo = $_GET["tipo"] ?? "externo";
    $dias = $_GET["dias"] ?? 0;
    $renovacion = $_GET["renovacion"] ?? "no";

    $dias = (int)$dias;

    $maxDias = match($tipo){
        "alumno" => 15,
        "profesor" => 30,
        default => 7
    };

    if($tipo !== "externo" && $renovacion == "si"){
        $maxDias += 7;
    }

    const DIAS_RETRASO_LEVE = 3;
    $diasRetraso = max(0, $dias - $maxDias);

    function diasDeRetraso(int $dias, int $maxDias):void{
        $diasPenalizacion = $dias - $maxDias;
        if($dias<$maxDias){
            echo "correcta";
        }elseif($dias===$maxDias){
            echo "ultimo dia";
        }elseif($dias<$maxDias+DIAS_RETRASO_LEVE){
            echo "retraso leve , penalizacion de" . ($diasPenalizacion*0.5);
        }else{
            echo "retraso grave, penalizacion de " . ($diasPenalizacion*0.5) . "€";
        }
    }

    
    $retraso = diasDeRetraso($dias, $diasRetraso);
    echo $retraso;
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Document</title>
    </head>
    <body>
        <ul>
            <?php 
                for ($i = 1; $i <= $diasRetraso; $i++) {
                    if ($i > 10) {
                        echo "<li>...</li>";
                        break;
                    }
                    echo "<li>Día de retraso: $i</li>";
                }
            ?>
        </ul>
    </body>
</html>