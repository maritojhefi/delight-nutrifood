# Guía Completa: Restaurar archivo .wpress SIN el plugin All-in-One WP Migration

## 📋 Índice
1. [Método 1: Extracción Manual (Windows/Linux/Mac)](#método-1-extracción-manual)
2. [Método 2: Script PHP](#método-2-script-php)
3. [Método 3: Script Bash/SSH](#método-3-script-bashssh)
4. [Método 4: Herramientas Online](#método-4-herramientas-online)
5. [Solución de Problemas](#solución-de-problemas)

---

## Método 1: Extracción Manual

### En Windows

1. **Descargar 7-Zip** (gratis): https://www.7-zip.org/

2. **Renombrar el archivo**:
   - Cambia `archivo.wpress` a `archivo.tar` o `archivo.tar.gz`

3. **Extraer**:
   - Clic derecho → 7-Zip → "Extraer aquí"
   - O arrastra el archivo a 7-Zip

4. **Estructura extraída**:
   ```
   extraido/
   ├── database.sql          (Base de datos)
   ├── wp-content/           (Temas, plugins, uploads)
   ├── wp-includes/          (Archivos core)
   ├── wp-admin/             (Panel admin)
   └── otros archivos PHP
   ```

5. **Subir archivos vía FTP**:
   - Conecta a tu servidor con FileZilla o similar
   - Sube las carpetas a la raíz de WordPress
   - **IMPORTANTE**: Haz backup antes de reemplazar

6. **Importar base de datos**:
   - Accede a phpMyAdmin
   - Selecciona tu base de datos
   - Ve a "Importar"
   - Selecciona `database.sql`
   - Ejecuta

### En Linux/Mac (o vía SSH)

```bash
# 1. Conectarse por SSH
ssh usuario@servidor.com

# 2. Ir a la carpeta donde está el archivo
cd /ruta/al/archivo

# 3. Extraer el archivo
tar -xzf archivo.wpress

# O si no funciona, renombrar primero:
mv archivo.wpress archivo.tar.gz
tar -xzf archivo.tar.gz

# 4. Ver qué se extrajo
ls -lah

# 5. Mover archivos a WordPress
cd /ruta/a/wordpress
cp -r /ruta/extraida/wp-content/* wp-content/
# etc...
```

---

## Método 2: Script PHP

### Usar el script `extract-wpress.php`

1. **Sube el script a tu servidor** (raíz de WordPress)

2. **Sube tu archivo .wpress** a la misma carpeta

3. **Ejecutar**:
   - **Desde navegador**: `http://tu-sitio.com/extract-wpress.php?file=archivo.wpress`
   - **Desde SSH**: `php extract-wpress.php archivo.wpress`

4. **Los archivos se extraerán en**: `extracted-backup/`

5. **Sigue los pasos del Método 1** para restaurar

---

## Método 3: Script Bash/SSH

### Usar el script `restore-wpress-ssh.sh`

1. **Sube el script a tu servidor**:
```bash
scp restore-wpress-ssh.sh usuario@servidor.com:/ruta/a/wordpress/
```

2. **Sube tu archivo .wpress**:
```bash
scp archivo.wpress usuario@servidor.com:/ruta/a/wordpress/
```

3. **Conectarse por SSH**:
```bash
ssh usuario@servidor.com
cd /ruta/a/wordpress
```

4. **Dar permisos de ejecución**:
```bash
chmod +x restore-wpress-ssh.sh
```

5. **Ejecutar el script**:
```bash
./restore-wpress-ssh.sh archivo.wpress
```

El script automáticamente:
- ✅ Extrae el archivo .wpress
- ✅ Crea un backup de tus archivos actuales
- ✅ Restaura wp-content, wp-includes, etc.
- ✅ Importa la base de datos
- ✅ Limpia archivos temporales

---

## Método 4: Herramientas Online

### Extractor Online de .wpress

Existen herramientas online que pueden extraer archivos .wpress:

1. **WPress Extractor** (si está disponible)
   - Sube tu archivo .wpress
   - Descarga los archivos extraídos
   - Restaura manualmente

⚠️ **Advertencia**: No subas archivos sensibles a herramientas online desconocidas.

---

## Pasos Adicionales Después de Restaurar

### 1. Actualizar URLs (si cambió el dominio)

**Vía WP-CLI**:
```bash
cd /ruta/a/wordpress
wp search-replace 'url-antigua.com' 'url-nueva.com'
```

**Vía phpMyAdmin**:
```sql
UPDATE wp_options SET option_value = 'https://nuevo-dominio.com' WHERE option_name = 'siteurl';
UPDATE wp_options SET option_value = 'https://nuevo-dominio.com' WHERE option_name = 'home';
```

### 2. Actualizar permisos de archivos

```bash
# En el servidor
cd /ruta/a/wordpress
find . -type d -exec chmod 755 {} \;
find . -type f -exec chmod 644 {} \;
chown -R www-data:www-data .
```

### 3. Limpiar caché

- Si usas WP Super Cache, W3 Total Cache, etc., limpia la caché
- O elimina manualmente: `wp-content/cache/`

### 4. Verificar que todo funcione

- Accede al panel de administración
- Revisa que las páginas se carguen
- Verifica que los plugins funcionen
- Comprueba los medios (imágenes)

---

## Solución de Problemas

### Error: "No se puede extraer el archivo"

**Solución**:
- Verifica que el archivo no esté corrupto
- Intenta con diferentes herramientas (7-Zip, WinRAR, tar)
- Descarga el archivo nuevamente si es posible

### Error: "Base de datos no encontrada"

**Solución**:
- Busca archivos `.sql` en la carpeta extraída
- Puede estar en una subcarpeta
- Usa: `find . -name "*.sql"`

### Error: "Permisos denegados"

**Solución**:
```bash
chmod -R 755 wp-content/
chown -R www-data:www-data wp-content/
```

### Error: "Tamaño de archivo excede límite"

**Solución**:
- Aumenta límites en `php.ini`:
  ```ini
  upload_max_filesize = 10G
  post_max_size = 10G
  memory_limit = 512M
  max_execution_time = 300
  ```

### El sitio muestra "Error al establecer conexión con la base de datos"

**Solución**:
1. Verifica credenciales en `wp-config.php`
2. Asegúrate de que la base de datos fue importada correctamente
3. Verifica que el usuario de MySQL tenga permisos

---

## Estructura Típica de un .wpress Extraído

```
extraido/
├── database.sql              # Base de datos completa
├── wp-content/
│   ├── themes/              # Temas instalados
│   ├── plugins/             # Plugins instalados
│   ├── uploads/             # Medios subidos
│   └── ...
├── wp-includes/             # Archivos core de WordPress
├── wp-admin/               # Panel de administración
├── index.php
├── wp-config.php           # ⚠️ Puede contener credenciales antiguas
└── otros archivos...
```

---

## ⚠️ Advertencias Importantes

1. **SIEMPRE haz backup** antes de restaurar
2. **Verifica las credenciales** de la base de datos en `wp-config.php`
3. **Actualiza las URLs** si restauras en un dominio diferente
4. **Revisa los permisos** de archivos después de restaurar
5. **Prueba el sitio** antes de ponerlo en producción

---

## Resumen Rápido

### Opción Más Rápida (Windows):
1. 7-Zip → Renombrar .wpress a .tar → Extraer
2. Subir archivos por FTP
3. Importar database.sql en phpMyAdmin
4. Actualizar URLs si es necesario

### Opción Más Rápida (SSH):
1. `tar -xzf archivo.wpress`
2. `./restore-wpress-ssh.sh archivo.wpress`
3. Listo ✅

---

¿Necesitas ayuda con algún paso específico? ¡Pregunta!


