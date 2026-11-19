# 📋 Registro de cambios

Todos los cambios importantes del tema Grupsity. Formato basado en [Keep a Changelog](https://keepachangelog.com/es-ES/1.1.0/).

> ✍️ Antes de publicar, agrega arriba una sección `## [Sin publicar]` con los cambios. `npm run push-v -- patch|minor|major` la renombra a `## [X.Y.Z] - fecha`, y no publica si esa sección no existe.

## [1.0.5] - 2026-11-19

### ✨ Añadido
- **Diseño V1** (Apariencia → Grupsity → General → Diseño): la estructura del tema TeDotamos V1 con los colores y fuentes de Grupsity (verde `#93b700`, Work Sans).
  - Franja superior oscura con 3 avisos con ícono (en el celular, solo el primero).
  - Header blanco: logo, buscador con borde y botón «Buscar» verde, deseos, carrito y cuenta.
  - Barra de menú gris clara: «Categorías» con desplegable de categorías de producto (ícono + panel de subcategorías con conteo y «Ver todo»), enlaces y teléfono.
  - Todo el contenido en un container de 1320 px, también la portada de Elementor (las secciones «estiradas» se quedan dentro).
  - Footer oscuro con acentos verdes; se oculta el bloque del boletín si su shortcode (`[mc4wp_form]`) no tiene plugin activo, y la caja de información vacía.
  - Textos de la franja, enlaces del menú y teléfono configurables en la pestaña General.
  - Sin theme_mods, cambia «Shop By Department», «I'm shopping for…» y «Search» por «Categorías», «Busca tu marca favorita» y «Buscar».
- Ganchos `grupsity_header_nav` (en `template-parts/headers/layout-1.php`) y `grupsity_department_menu` (en `martfury_extra_department()`).

## [1.0.4] - 2026-11-19

### 🎨 Cambiado
- El diseño JomaShop ahora usa los **colores de Grupsity** (verde `#93b700`, tinta y blanco) con la forma de jomashop.com: franja superior oscura, cabecera verde (layout 4) o blanca (layout 1), buscador de 52 px, menú en mayúsculas, filtros laterales sin cajas, tarjetas de producto sin bordes con el precio en negrita y footer oscuro con acentos verdes. Contraste AA: sobre verde el texto va en tinta.
- Se respeta el logo subido a Medios; solo se cambia por el nombre del sitio cuando aparece el logo genérico de Martfury.

### 🐛 Corregido
- La fuente Inter nunca se cargaba (el CSS la pedía pero no se encolaba): ahora se carga desde Google Fonts con el diseño JomaShop.
- Wordmark invisible (Martfury oculta `.site-title` con `text-indent: -9999px`).
- Las tarjetas de producto no recibían estilo en categorías y marcas (los selectores dependían de un `.woocommerce` padre que ahí no existe); títulos en azul.
- Botón «Exportar catálogo en PDF» fuera de la columna, encima del sidebar; campos de precio descolocados; líneas sueltas dentro de los widgets; separadores negros en la franja superior.
- Precio desplazado por el `&nbsp;` que WooCommerce pone entre `<del>` e `<ins>`.
- Footer: línea oscura bajo los widgets, bloques vacíos con separadores y fuente distinta.
- Martfury volvía al amarillo `#fcb800` si faltaba la configuración del Customizer: el diseño fija el verde de la marca.
- Migración: el estado se guarda por carpeta (`grupsity` / `grupsity-theme-master`) y un aviso en el escritorio indica si a la carpeta activa le falta la configuración de Martfury (es lo que hace salir el header, logo y textos por defecto). El aviso no copia nada solo.

## [1.0.3] - 2026-11-18

### 🐛 Corregido
- En el diseño JomaShop se oculta la imagen de logo heredada de Martfury (idéntica en las carpetas `martfury` y `grupsity-theme-master`) y se usa el nombre del sitio como wordmark: Inter 30 px en negrita, eslogan en gris y cabecera de 104 px.

## [1.0.2] - 2026-11-18

### 🐛 Corregido
- El diseño JomaShop se cargaba (body class y CSS 200) pero apenas se veía en la home de producción: se añadieron los selectores reales de ese montaje (`.products-cats-menu`, `.header-bar.topbar`, precios/etiquetas del loop y títulos/botones de Elementor) para que el cambio sea visible.

## [1.0.1] - 2026-11-18

### ✨ Añadido
- Página **Apariencia → Grupsity** con pestañas de actualizaciones, general, migración e información, avisos de versión nueva y configuración del intervalo de caché de GitHub.
- Selector de diseño en General con dos opciones: `martfury` (por defecto) y `jomashop`, un estilo inspirado en jomashop.com aplicado sobre el HTML existente de Martfury (`css/design-jomashop.css`, `body.grupsity-design-jomashop`).
- Al cambiar de diseño se regeneran los estilos en línea del tema y se purga la caché de LiteSpeed.

### ⚙️ Estructura inicial
- Fork de Martfury 3.2.8 con `Theme Name: Grupsity`, `Update URI` y `Version: 1.0.0`.
- Bootstrap propio (`inc/grupsity/`) con hoja de estilos, updater desde GitHub Releases y migración del Customizer.
- Workflow de release que construye `grupsity.zip` al subir un tag.
