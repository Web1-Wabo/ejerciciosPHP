<?php
    $fecha1 = new DateTime("1-09-2026");
    $fecha2 = new DateTime("18-09-2026");

    $diferencia = $fecha1->diff($fecha2);

    echo $diferencia->days;
?>