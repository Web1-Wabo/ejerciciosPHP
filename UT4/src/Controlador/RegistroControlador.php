<?php
    declare(strict_types=1);

    namespace App\Controlador;

use App\Service\AuthService;
use DomainException;

use function App\Soporte\redirigir;
use function App\Soporte\soloMetodos;

    final class RegistroControlador extends Controlador{
        public function mostrar():void{
            $error = null;
            if(soloMetodos(["GET", "POST"]) === "POST"){
                $nombre = is_string($_POST["nombre"] ?? null) ? trim($_POST["nombre"]) : "";
                $email = is_string($_POST["email"] ?? null) ? trim($_POST["email"]) : "";
                $clave = $_POST["clave"] ?? null;
                if($nombre === "" || filter_var($email, FILTER_VALIDATE_EMAIL) === false ||
                !is_string($clave) || strlen($clave) < 8 || strlen($clave) > 64){
                    $error = "Error en nomnbre, contraseña o correo";
                }else{
                    try{
                        $auth = new AuthService($this->app["usuarios"]);
                        $auth->registrar($nombre, $email, $clave);
                        $_SESSION["flash"] = "cuentaa creada";
                        redirigir("index.php");
                    }catch(DomainException $e){
                        $error = $e->getMessage();
                    }
                }
            }
            $this->vista("registro", compact("error"));
        }
    }
?>