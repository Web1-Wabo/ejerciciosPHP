<?php
declare(strict_types=1);
namespace App\Controlador;

use function App\Soporte\redirigir;

final class PreferenciasControlador extends Controlador{
    public function guardaTema():void{
        $tema = $_POST["tema"] ?? null;

        if(in_array($tema, ["claro", "oscuro"], true)){
            $this->guararCookie("tema", $tema);
            redirigir("index.php");
        }
        http_response_code(400);
        exit;
    }

    public function guardaEspaciado():void{
        $espaciado = $_POST["espaciado"] ?? null;

        if(in_array($espaciado, ["normal", "mucho", "moderado"], true)){
            $this->guararCookie("espaciado", $espaciado);
            redirigir("index.php");
        }
        http_response_code(400);
        exit;
    }

    public function guararCookie(string $cookie, string $valor):void{
        setcookie($cookie, $valor, [
            'expires' => time() + 30 * 86400,
            'path' => '/',
            'secure' => true,
            'httponly' => true,
            'samesite' => 'Lax',
        ]);
    }
}
?>