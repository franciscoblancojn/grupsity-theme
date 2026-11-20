# 📋 Registro de cambios

Todos los cambios importantes del tema Grupsity. Formato basado en [Keep a Changelog](https://keepachangelog.com/es-ES/1.1.0/).

> ✍️ Antes de publicar, agrega arriba una sección `## [Sin publicar]` con los cambios. `npm run push-v -- patch|minor|major` la renombra a `## [X.Y.Z] - fecha`, y no publica si esa sección no existe.

## [1.0.11] - 2026-11-20

### 🐛 Corregido
- Diseño V1, ficha de producto:
  - **Cantidad y «Añadir al carrito»:** la fila flex estaba en el `form`, pero Elementor mete todo en `.e-atc-qty-button-holder`, así que no se alineaban. Ahora: selector − 1 + en una sola caja (sin la doble caja de Martfury ni el «Quantity:» en inglés), botón verde a la misma altura (52 px) y Wishlist/Comparar como botones de ícono. En el celular, cantidad y botón en la misma fila.
  - **Etiqueta «¡Oferta!»:** salía como un óvalo deformado y fuera de la foto; ahora es un círculo verde dentro del marco.
  - **Productos relacionados:** usan la misma tarjeta y el mismo hover que la tienda (borde, subida con sombra, cápsula de acciones sobre la foto), todas a la misma altura y con flechas redondas. Antes, al pasar el ratón, se abría la caja larga de Martfury.

## [1.0.10] - 2026-11-19

### ✨ Añadido
- Diseño V1, **ficha de producto como la de TeDotamos** (plantilla de Elementor «Producto»):
  - **Disposición:** galería fija a la izquierda, con las miniaturas debajo (la activa con borde verde). A la derecha, en este orden: título, precio grande con el anterior tachado y el **% de descuento**, descripción corta, bloque de compra, garantía, envíos/pagos y SKU/categorías/marca. El orden se cambia con CSS, sin tocar la plantilla.
  - **Compra:** cantidad + «Añadir al carrito» en verde y botón **«Comprar por WhatsApp»** (si está agotado, «Preguntar disponibilidad por WhatsApp», con el nombre y el enlace del producto en el mensaje). El número se configura en Apariencia → Grupsity → General.
  - **Pestañas y FAQ:** pestañas con subrayado verde; títulos de la descripción más moderados y tablas con bordes suaves; preguntas frecuentes como tarjetas.
  - Todo alineado con el container de 1320 px; en el celular, en una columna.

### 🐛 Corregido
- **Ficha de producto sin stock** (cualquier diseño): el bloque de compra salía vacío, porque la plantilla de Martfury (`woocommerce/single-product/add-to-cart/simple.php`) no imprime el estado de stock. Ahora muestra «Agotado» y el gancho `grupsity_add_to_cart_unavailable`.
- Ficha de producto (cualquier diseño): pestañas «Description» / «Specification» → «Descripción» / «Especificaciones»; «Brand:» → «Marca:»; «Related products» → «Productos relacionados».
- El bloque «See It Styled On Instagram» de Martfury estaba activo sin token e imprimía «No access token» en todas las fichas: se oculta mientras no haya token.
- Diseño V1: las etiquetas del widget «Meta del producto» (la del SKU mostraba el propio código y «Categorías;» llevaba punto y coma) se muestran como «SKU» y «Categorías». También se pueden corregir en el widget de Elementor.

## [1.0.9] - 2026-11-19

### ✨ Añadido
- **Scroll infinito en el catálogo** (tienda, categorías, marcas y búsqueda), como en TeDotamos. Opción en Apariencia → Grupsity → General → **Navegación del catálogo**: scroll infinito (por defecto), números de página o lo que diga el Customizer de Martfury. Al llegar al final se cargan los productos de la página siguiente, con un botón «Cargando más productos» que también se puede tocar. Sin JavaScript, o para los buscadores, es un enlace normal a la página siguiente. Funciona con cualquier diseño.
  - `woocommerce/loop/pagination.php`: en scroll infinito solo se imprime el enlace a la página siguiente; en la última página, el `<nav>` vacío. Antes salían también los números. Corrige el enlace de WooCommerce 11 (`%_%`), que quedaba en `…/tienda/%_%` y no cargaba nada.

### 🐛 Corregido
- Diseño V1, **tarjetas de producto en tienda y categorías**: al pasar el ratón, Martfury abría una segunda caja desplazada con borde gris y una franja blanca que tapaba media foto. Ahora son tarjetas como las de «Ofertas del día», alineadas y de la misma altura por fila: la tarjeta sube con sombra, la foto hace un zoom suave y las acciones salen en una cápsula flotante. En el celular, título y precio siempre visibles (Martfury los ocultaba) y sin cápsula.
- **Filtro de precios** (diseños V1 y JomaShop): el texto «Precio: $x — $y» estaba encima de los campos y no dejaba usarlos. Ahora va en su propia línea, bajo el slider (verde), y los dos campos se pueden escribir: al cambiar un valor se mueve el slider (`inc/grupsity/class-catalog.php`).

## [1.0.8] - 2026-11-19

### 🎨 Cambiado
- Diseño V1: los íconos de la barra de servicios de la portada (Entrega gratuita, Devoluciones, Pago Seguro, Soporte) van en el verde de Grupsity `#93b700`; el color guardado en el widget de Elementor ya no manda. Títulos en tinta, descripción en gris y separadores suaves.

### 🐛 Corregido
- Diseño V1: en la portada el desplegable «Categorías» salía siempre abierto, encima del contenido. Martfury tiene la opción «Department Menu on Homepage» en `open` por defecto, y en la barra V1 la lista quedaba fija. Ahora en la home se abre al pasar el ratón, como en el resto de páginas, y se quita el relleno superior que acompañaba al menú abierto. No se cambia ninguna opción guardada del Customizer.

## [1.0.7] - 2026-11-19

### 🎨 Cambiado
- Portada, diseño V1: **hover en las imágenes con enlace** (categorías con texto debajo, como «Nuevos y Colecciones», y banners): la imagen hace un zoom suave dentro de su marco, el bloque sube un poco con sombra y el texto pasa a verde. Los logos del carrusel de marcas también suben y crecen un poco. Las imágenes sin enlace no cambian, y sin animación si el sistema tiene «reducir movimiento».

## [1.0.6] - 2026-11-19

### 🎨 Cambiado
- Portada, diseño V1: el carrusel **«Ofertas del día»** (sección de Elementor `6a0f3d03`) va sin fondo y con tarjetas como las de TeDotamos, todas de la misma altura: foto sobre gris claro, precio en negrita con el anterior tachado y barra de vendidos en verde. Al pasar el ratón la tarjeta sube con sombra y las acciones (carrito, vista rápida, deseos, comparar) salen en una cápsula flotante que ya no tapa media foto. El hover se ve completo, porque el slider ya no corta la sombra. Título con ícono, cuenta atrás en píldora verde, «Ver todo →» y flechas redondas.

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
