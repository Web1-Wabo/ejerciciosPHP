<?php
    $hoy = new DateTimeImmutable("24-09-2026");
    echo $hoy->format("d/m/Y") . "<br>";


    $proxQuinceDias = new DateTimeImmutable("8-10-2026");
    echo $proxQuinceDias->format("d/m/Y");
?>