<?php use function \App\Soporte\e; ?>
<h2>Crear cuenta</h2>
<?php if ($error !== null):?>
    <p><?= e($error) ?></p>
<?php endif;?>
<h1>Crear Cuenta</h1>
<form action="" method="post">
    <label for="nombre">Nombre</label>
    <input type="text" name="nombre" id="nombre">

    <label for="email">Correo</label>
    <input type="email" name="email" id="email">

    <label for="clave">Contraseña</label>
    <input type="password" name="clave" id="clave">

    <button>Crear cuenta</button>
</form>