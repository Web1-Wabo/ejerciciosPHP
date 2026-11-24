<?php use function App\Soporte\e; ?>
<h2>Catálogo</h2>
<form action="preferencias.php" method="post" class="<?= $espaciado ?>">
    <label for="tema">Tema</label>
    <select name="tema" id="tema">
        <option value="claro">Claro</option>
        <option value="oscuro">Oscuro</option>
    </select>
    <button>Guardar tema</button>
</form>

<form action="preferenciasEspaciado.php" method="post" class="<?= $espaciado ?>">
    <label for="espaciado">Espaciado</label>
    <select name="espaciado" id="espaciado">
        <option value="normal">Normal</option>
        <option value="mucho">Mucho</option>
        <option value="moderado">Moderado</option>
    </select>
    <button>Guardar espaciado</button>
</form>

<form method="get" action="index.php" class="<?= $espaciado ?>">
    <label for="q">Buscar por título</label>
    <input id="q" name="q" value="<?= e($q) ?>">
    <button>Buscar</button>
    <a href="index.php">Ver todos</a>
</form>
<?php if ($libros === []): ?>
    <p>Sin resultados.</p>
<?php else: ?>
    <ul class="libros">
        <?php foreach ($libros as $libro): ?>
            
            <li><?= e($libro->titulo) ?>    </li>
            <?php if($favoritos !== null && in_array($libro->id, $favoritos, true)):?>
                <p>Favoritos</p>
                <form action="add-favs.php" method="post">
                    <input type="hidden" name="id" value="<?= $libro->id ?>">
                    <button>Quitar favoritos</button>
                </form>
            <?php else:?>
                <p>No favorito</p>
                <form action="add-favs.php" method="post">
                    <input type="hidden" name="id" value="<?= $libro->id ?>">
                    <button>Añadir a favoritos</button>
                </form>
            <?php endif?>
            
        <?php endforeach; ?>
    </ul>
<?php endif; ?>
