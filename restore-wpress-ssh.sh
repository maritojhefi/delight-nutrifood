#!/bin/bash

# Script para restaurar archivo .wpress vía SSH sin plugin
# Uso: ./restore-wpress-ssh.sh archivo.wpress

# Colores para output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Verificar argumentos
if [ -z "$1" ]; then
    echo -e "${RED}Error: Debes especificar el archivo .wpress${NC}"
    echo "Uso: ./restore-wpress-ssh.sh archivo.wpress [ruta-wordpress]"
    exit 1
fi

WPRESS_FILE="$1"
WP_ROOT="${2:-$(pwd)}"

echo -e "${GREEN}=== Restaurar .wpress sin plugin ===${NC}\n"

# Verificar que el archivo existe
if [ ! -f "$WPRESS_FILE" ]; then
    echo -e "${RED}Error: El archivo '$WPRESS_FILE' no existe${NC}"
    exit 1
fi

# Verificar que estamos en un directorio de WordPress
if [ ! -f "$WP_ROOT/wp-config.php" ]; then
    echo -e "${YELLOW}Advertencia: wp-config.php no encontrado en $WP_ROOT${NC}"
    echo "¿Continuar de todas formas? (s/n)"
    read -r response
    if [[ ! "$response" =~ ^[Ss]$ ]]; then
        exit 1
    fi
fi

# Crear directorio temporal para extracción
EXTRACT_DIR="$WP_ROOT/restore-temp-$(date +%s)"
mkdir -p "$EXTRACT_DIR"

echo -e "${YELLOW}Paso 1: Extrayendo archivo .wpress...${NC}"
cd "$EXTRACT_DIR"

# Intentar extraer
if tar -xzf "$WPRESS_FILE" 2>/dev/null; then
    echo -e "${GREEN}✓ Extracción exitosa${NC}"
elif tar -xf "$WPRESS_FILE" 2>/dev/null; then
    echo -e "${GREEN}✓ Extracción exitosa${NC}"
else
    echo -e "${RED}✗ Error al extraer. El archivo puede estar corrupto o usar un formato diferente.${NC}"
    echo "Intenta extraer manualmente con: tar -xzf $WPRESS_FILE"
    exit 1
fi

# Buscar database.sql
DB_FILE=$(find "$EXTRACT_DIR" -name "database.sql" -o -name "*.sql" | head -1)

if [ -z "$DB_FILE" ]; then
    echo -e "${YELLOW}Advertencia: No se encontró archivo SQL${NC}"
else
    echo -e "${GREEN}✓ Base de datos encontrada: $DB_FILE${NC}"
fi

# Mostrar estructura extraída
echo -e "\n${YELLOW}Estructura extraída:${NC}"
ls -lah "$EXTRACT_DIR"

# Preguntar si continuar
echo -e "\n${YELLOW}¿Deseas continuar con la restauración? (s/n)${NC}"
read -r response
if [[ ! "$response" =~ ^[Ss]$ ]]; then
    echo "Restauración cancelada. Archivos extraídos en: $EXTRACT_DIR"
    exit 0
fi

# Hacer backup de los archivos actuales
echo -e "\n${YELLOW}Paso 2: Creando backup de archivos actuales...${NC}"
BACKUP_DIR="$WP_ROOT/backup-before-restore-$(date +%Y%m%d-%H%M%S)"
mkdir -p "$BACKUP_DIR"

if [ -d "$WP_ROOT/wp-content" ]; then
    cp -r "$WP_ROOT/wp-content" "$BACKUP_DIR/"
    echo -e "${GREEN}✓ Backup creado en: $BACKUP_DIR${NC}"
fi

# Restaurar archivos
echo -e "\n${YELLOW}Paso 3: Restaurando archivos...${NC}"

# Restaurar wp-content si existe
if [ -d "$EXTRACT_DIR/wp-content" ]; then
    echo "Restaurando wp-content..."
    rm -rf "$WP_ROOT/wp-content"
    cp -r "$EXTRACT_DIR/wp-content" "$WP_ROOT/"
    echo -e "${GREEN}✓ wp-content restaurado${NC}"
fi

# Restaurar otros archivos si existen
for dir in wp-includes wp-admin; do
    if [ -d "$EXTRACT_DIR/$dir" ]; then
        echo "Restaurando $dir..."
        rm -rf "$WP_ROOT/$dir"
        cp -r "$EXTRACT_DIR/$dir" "$WP_ROOT/"
        echo -e "${GREEN}✓ $dir restaurado${NC}"
    fi
done

# Restaurar archivos de la raíz
if [ -d "$EXTRACT_DIR" ]; then
    find "$EXTRACT_DIR" -maxdepth 1 -type f -name "*.php" -exec cp {} "$WP_ROOT/" \;
fi

# Restaurar base de datos
if [ -n "$DB_FILE" ]; then
    echo -e "\n${YELLOW}Paso 4: Restaurando base de datos...${NC}"
    
    # Obtener credenciales de wp-config.php
    DB_NAME=$(grep DB_NAME "$WP_ROOT/wp-config.php" | cut -d "'" -f 4)
    DB_USER=$(grep DB_USER "$WP_ROOT/wp-config.php" | cut -d "'" -f 4)
    DB_PASS=$(grep DB_PASSWORD "$WP_ROOT/wp-config.php" | cut -d "'" -f 4)
    DB_HOST=$(grep DB_HOST "$WP_ROOT/wp-config.php" | cut -d "'" -f 4)
    
    if [ -z "$DB_NAME" ] || [ -z "$DB_USER" ]; then
        echo -e "${RED}Error: No se pudieron obtener las credenciales de la base de datos${NC}"
        echo "Importa manualmente: mysql -u usuario -p base_datos < $DB_FILE"
    else
        echo "Importando base de datos..."
        if mysql -h "$DB_HOST" -u "$DB_USER" -p"$DB_PASS" "$DB_NAME" < "$DB_FILE" 2>/dev/null; then
            echo -e "${GREEN}✓ Base de datos restaurada${NC}"
        else
            echo -e "${YELLOW}Advertencia: Error al importar. Intenta manualmente:${NC}"
            echo "mysql -h $DB_HOST -u $DB_USER -p $DB_NAME < $DB_FILE"
        fi
    fi
fi

# Limpiar archivos temporales
echo -e "\n${YELLOW}Paso 5: Limpiando archivos temporales...${NC}"
rm -rf "$EXTRACT_DIR"
echo -e "${GREEN}✓ Limpieza completada${NC}"

echo -e "\n${GREEN}=== Restauración completada ===${NC}"
echo -e "Backup guardado en: ${YELLOW}$BACKUP_DIR${NC}"
echo -e "\n${YELLOW}IMPORTANTE:${NC}"
echo "1. Verifica que tu sitio funcione correctamente"
echo "2. Si cambió el dominio, actualiza las URLs:"
echo "   wp search-replace 'url-antigua.com' 'url-nueva.com'"
echo "3. Limpia la caché si usas algún plugin de caché"


