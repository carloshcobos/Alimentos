# Buscador de Alimentos - Open Food Facts

## Solución Final Funcional

Esta aplicación usa **Open Food Facts**, la base de datos colaborativa de alimentos más grande del mundo con más de **3 millones de productos**, incluyendo miles de productos españoles.

---

## Características

✅ **API funcionando 100%** - Probada y verificada  
✅ **Productos españoles** - Miles de productos del mercado español  
✅ **Sin API key** - Totalmente gratuito  
✅ **JSON simple** - Fácil de usar  
✅ **Datos completos** - Ingredientes, nutrientes, alérgenos, fotos  
✅ **Nutri-Score y NOVA** - Indicadores de calidad nutricional  
✅ **Imágenes de productos** - Fotos reales de los envases  

---

## Instalación (3 pasos)

### Paso 1: Copiar archivos

Copia estos archivos en tu carpeta de XAMPP:

```
C:/xampp/htdocs/alimentos/
├── header.php
├── footer.php
├── index.php
└── item.php
```

### Paso 2: Iniciar Apache

1. Abre el Panel de Control de XAMPP
2. Click en "Start" junto a **Apache**
3. Verifica que esté en **verde**

### Paso 3: ¡Probar!

1. Abre tu navegador
2. Ve a: `http://localhost/alimentos/`
3. Busca productos: "Coca-Cola", "Danone", "Nutella", etc.

---

## Cómo Buscar

### Ejemplos de búsquedas que funcionan bien:

- **Por marca**: "Danone", "Nestlé", "Coca-Cola", "Hacendado"
- **Por producto**: "yogur", "queso", "chocolate", "galletas"
- **Por nombre comercial**: "Nutella", "Actimel", "Nesquik"
- **Productos españoles**: "jamón serrano", "manchego", "chorizo"

### Tips para mejores resultados:

- Usa nombres simples y genéricos
- Busca por marcas conocidas
- Puedes buscar por código de barras
- Los productos más populares tienen más información

---

## Información Disponible

### Datos nutricionales:
- ✅ Energía (kcal)
- ✅ Grasas (totales y saturadas)
- ✅ Carbohidratos (totales y azúcares)
- ✅ Proteínas
- ✅ Fibra
- ✅ Sal / Sodio
- ✅ Vitaminas (A, C, D cuando disponibles)
- ✅ Minerales (calcio, hierro cuando disponibles)

### Información adicional:
- Foto del producto
- Lista de ingredientes
- Alérgenos
- Etiquetas (Bio, Sin gluten, Vegano, etc.)
- Nutri-Score (A-E)
- Clasificación NOVA (procesamiento)
- Categorías
- Cantidad/Peso

---

## Qué es Nutri-Score

**Nutri-Score** es un sistema de etiquetado nutricional de 5 colores:

- 🟢 **A** (Verde) - Excelente calidad nutricional
- 🔵 **B** (Azul claro) - Buena calidad nutricional  
- 🟡 **C** (Amarillo) - Calidad nutricional aceptable
- 🟠 **D** (Naranja) - Baja calidad nutricional
- 🔴 **E** (Rojo) - Muy baja calidad nutricional

---

## Qué es NOVA

**NOVA** clasifica alimentos según su nivel de procesamiento:

- **Grupo 1** - Alimentos sin procesar o mínimamente procesados
- **Grupo 2** - Ingredientes culinarios procesados
- **Grupo 3** - Alimentos procesados
- **Grupo 4** - Productos ultraprocesados

---

## Solución de Problemas

### Error: "No se encontraron productos"

**Solución**:
- Prueba con marcas conocidas: "Danone", "Coca-Cola"
- Usa términos más genéricos: "yogur" en vez de "yogur desnatado"
- Verifica tu conexión a internet
- Algunos productos muy locales pueden no estar en la base de datos

### Error: "Error de conexión"

**Problema**: No hay conexión a internet o firewall bloqueando

**Solución**:
1. Verifica que tengas internet
2. Prueba abrir https://es.openfoodfacts.org en tu navegador
3. Si tienes firewall, permite conexiones a openfoodfacts.org
4. En XAMPP, verifica que Apache esté iniciado

### La página se queda en blanco

**Problema**: Error de PHP

**Solución**:
1. Verifica que todos los archivos estén en la carpeta correcta
2. Revisa que `header.php` y `footer.php` existan
3. Mira los logs de error en `C:\xampp\apache\logs\error.log`

### Las imágenes no cargan

**Problema**: Las URLs de Open Food Facts pueden fallar a veces

**Solución**:
- Es normal, algunos productos no tienen imágenes
- La aplicación muestra un placeholder (icono) cuando falta la imagen
- La funcionalidad sigue trabajando normalmente

---

## Personalización

### Cambiar número de resultados por defecto

En `index.php`, busca:
```php
$page_size = isset($_POST['pageSize']) ? intval($_POST['pageSize']) : 20;
```

Cambia `20` por el número que prefieras (máximo 100).

### Cambiar colores o estilos

Edita `header.php` en la sección `<style>` para personalizar:
- Colores del tema
- Tamaños de fuente
- Espaciados
- Efectos de hover

---

## Estadísticas de Open Food Facts

- **3+ millones** de productos
- **200+ países**
- **Más de 50,000** productos españoles
- **Base de datos colaborativa** - cualquiera puede contribuir
- **Actualización constante** - nuevos productos cada día
- **100% gratuito** - datos abiertos

---

## Sobre Open Food Facts

**Open Food Facts** es un proyecto colaborativo sin ánimo de lucro que recopila información sobre productos alimenticios de todo el mundo.

- **Web**: https://es.openfoodfacts.org
- **API**: Gratuita y abierta
- **Licencia**: Open Database License
- **Contribuir**: Cualquiera puede añadir productos con la app móvil

### ¿Cómo contribuir?

1. Descarga la app móvil (Android/iOS)
2. Escanea productos
3. Sube fotos (envase, ingredientes, nutrición)
4. Completa información
5. ¡Ayudas a millones de personas!

---

## Próximos Pasos

Una vez que tengas todo funcionando, puedes:

1. **Añadir filtros avanzados**
   - Por categoría
   - Por etiquetas (bio, sin gluten, etc.)
   - Por Nutri-Score
   - Por NOVA

2. **Implementar búsqueda por código de barras**
   - Usar librerías de escaneo
   - Búsqueda directa por EAN

3. **Crear listas de compra**
   - Guardar productos favoritos
   - Comparar productos

4. **Exportar a PDF**
   - Fichas nutricionales
   - Listas de compra

5. **Integrar con apps móviles**
   - Usar la misma API
   - Sincronización de datos

---

## Soporte

### Problemas con la aplicación:
- Revisa esta guía completa
- Verifica los logs de Apache en XAMPP
- Asegúrate de tener internet

### Problemas con datos de productos:
- Visita https://es.openfoodfacts.org
- Contribuye añadiendo/corrigiendo productos
- Contacta con la comunidad en su Slack

---

## Licencia

- **Código de esta aplicación**: Libre uso personal y educativo
- **Datos de Open Food Facts**: Open Database License
- **Imágenes de productos**: Creative Commons Attribution ShareAlike

---

## ¡Listo!

Tu buscador de alimentos está **completamente funcional** usando la API de Open Food Facts.

**Ventajas finales**:
- ✅ Funciona ahora mismo
- ✅ Miles de productos españoles
- ✅ Fotos reales
- ✅ Información completa
- ✅ Totalmente gratis
- ✅ No requiere configuración de base de datos
- ✅ Datos siempre actualizados

¡Disfruta tu aplicación!