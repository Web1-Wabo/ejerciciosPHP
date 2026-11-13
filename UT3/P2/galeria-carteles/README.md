# Galería de carteles

Desde esta carpeta, inicia el servidor publicando solo `public`:

```powershell
php -d upload_max_filesize=4M -d post_max_size=8M -S localhost:8000 -t public
```

Abre `http://localhost:8000/index.php`. Las imágenes se guardan en `storage/carteles`, fuera del directorio público. `src/archivos.php` reúne las comprobaciones y rutas compartidas.

## Comprobaciones

```powershell
php pruebas/tamanos.php
php pruebas/generar.php
```

`generar.php` crea archivos de 2 097 152 y 2 097 153 bytes a partir de `pruebas/base.png`; los resultados generados están excluidos de Git.

Para comprobar los recorridos HTTP, usa las imágenes de `UT3/PF/pruebas-portadas`: sube un JPEG, PNG y WebP válidos; prueba `texto_falso.jpg`, `portada_png.txt`, `vacio.png` y `portada_grande.png`. La extensión del nombre y el MIME declarado por el navegador no deciden el formato aceptado.

La galería vuelve a leer `storage/carteles` en cada petición. En sustitución, una ausencia de imagen conserva el cartel anterior; un archivo inválido o un error de recepción también lo conserva, pero muestra el error. La sustitución solo se confirma cuando se guarda la nueva imagen y se retira la anterior.

Para probar el límite total, envía un cuerpo multipart mayor de 8 MiB. La página comprueba `CONTENT_LENGTH` antes de interpretar `$_FILES` vacío y muestra el error de tamaño de petición.
