<?php
declare(strict_types=1);

namespace App\Controlador;

use function App\Soporte\redirigir;
use function App\Soporte\soloMetodos;

final class FavoritoControlador extends Controlador{
    public function cambiar() : void {
        soloMetodos(['POST']);

        $entrada = $_POST["id"] ?? null;
        $id = is_string($entrada) ? filter_var($entrada, FILTER_VALIDATE_INT, ["options" =>["min_range" => 1]]): false;
        if($id===false){
            http_response_code(400);
            exit;
        }
        if($this->app["catalogo"]->buscarPorId($id) === null){
            http_response_code(400);
            exit;
        }

        $favoritos = $_SESSION["favoritos"] ?? [];
        if(!in_array($id, $favoritos, true)){
            $favoritos[] = $id;
            $_SESSION["flash"]="Libro añadido a favoritos";
        }else{
            $key = array_search($id, $favoritos, true);
            unset($favoritos[$key]);
        }


        $_SESSION["favoritos"] = $favoritos;
        redirigir("index.php");
    }
}