# 📋 Registro de cambios

Todos los cambios importantes del tema Grupsity. Formato basado en [Keep a Changelog](https://keepachangelog.com/es-ES/1.1.0/).

> ✍️ Antes de publicar, agrega arriba una sección `## [Sin publicar]` con los cambios. `npm run push-v -- patch|minor|major` la renombra a `## [X.Y.Z] - fecha`, y no publica si esa sección no existe.

## [1.0.30] - 2026-11-26

### 🐛 Corregido
- Diseño V1, ficha de producto: el **bloque de compra salía desordenado** en los productos que no usan la plantilla de Elementor (p. ej. Joyas: «Michael Kors Joyas Aretes MKJ8655040»). En esos productos «Añadir al carrito» salía negro, con «Comprar ahora» al lado y favoritos y comparar sueltos. Ahora llevan el mismo bloque que la plantilla: caja gris, cantidad + favoritos + comparar arriba y los dos botones 50/50 (100 % en celular).

## [1.0.29] - 2026-11-25

### 🔄 Cambiado
- Diseño V1, ficha de producto: **nuevo orden del bloque de compra**.
  - Arriba, cantidad, favoritos y comparar en una sola fila al 100 %. La cantidad se estira para llenar la fila.
  - Debajo, «Añadir al carrito» y «Comprar ahora» al 50/50. En celular va cada uno a todo el ancho.
  - «Comprar por WhatsApp» siempre a todo el ancho.

### 🐛 Corregido
- Ficha de producto en celular: la fila de cantidad salía corrida 10 px y «Comparar» bajaba a otra línea. La causa era el clearfix de `.actions-button`, que sumaba un hueco vacío en la fila.

## [1.0.28] - 2026-11-25

### ✨ Añadido
- Diseño V1, ficha de producto: botón **«Comprar ahora»** debajo de «Añadir al carrito». Añade el producto y lleva directo al checkout. Es el botón de Martfury (Personalizar → WooCommerce → Buy Now), activado y con el texto en español.

### 🔄 Cambiado
- Diseño V1, ficha de producto en celular: **«Añadir al carrito» ya no flota** al pie de la pantalla. Va dentro del bloque de compra: cantidad, «Añadir al carrito» y «Comprar ahora», cada uno a todo el ancho, y debajo favoritos, comparar y WhatsApp. Se apaga la opción de Martfury «Add to cart fixed».
- Ficha de producto en celular: los títulos dentro de la descripción («Beneficios principales», «Especificaciones técnicas»…) bajan de 30 a 21 px, y el texto a 15 px.

### 🐛 Corregido
- Versión móvil: textos en inglés → español. La cabecera de la ficha pasa de «Back to Category» a «Volver a <categoría>». La barra inferior pasa a Inicio, Categorías, Buscar y Carrito. También se traducen los títulos «Description» y «Specification» y el formulario de reseñas («Be the first to review…», «Your rating of this product», «Write your review here…», «Name»).

## [1.0.27] - 2026-11-23

### 🔄 Cambiado
- Diseño V1, portada: las **tarjetas de los logos de marcas** (borde, sombra y «Ver ›» en verde) ahora también se ven **en computadora**, no solo en celular y tablet. Al pasar el ratón la tarjeta sube, crece un poco y el borde se pone verde.

## [1.0.26] - 2026-11-23

### 🔄 Cambiado
- Diseño V1, portada, **logos de marcas en celular y tablet** (pantallas sin hover): cada logo va en una tarjeta con borde y sombra, con «Ver ›» en verde debajo, para que se entienda que se puede tocar. Al tocarla se hunde un poco y se pone verde. En escritorio no cambia nada.

### 🐛 Corregido
- Diseño V1, portada: el «Ver todo» de las secciones por categoría ya no sale subrayado. En la 1.0.24, esa regla se aplicó por error al panel del menú «Categorías».

## [1.0.25] - 2026-11-23

### 🔄 Cambiado
- Diseño V1, portada: la franja **«Compra ahora, paga después»** vuelve a salir **después del primer slider**, como hasta la 1.0.23. El orden de la portada se ajusta en Elementor.

## [1.0.24] - 2026-11-23

### ✨ Añadido
- Diseño V1, portada: **secciones por categoría** (p. ej. Perfumería y, más adelante, Accesorios). Cada una repite lo de los relojes: título con «Ver todo», carrusel de logos de marcas y carrusel de banners. Van antes de «Ofertas del día». Se configuran en Apariencia → Grupsity → General → «Secciones de la portada»: `# Título|enlace` y, debajo, `marca|imagen|enlace` o `banner|imagen|enlace`. La imagen es el ID de la biblioteca de medios o su URL. Los carruseles tienen flechas y se deslizan con el dedo, y los logos avanzan solos.

### 🔄 Cambiado
- Diseño V1, portada: la franja **«Compra ahora, paga después»** pasa de encima a **debajo del banner principal**.
- Diseño V1, portada: los **logos del carrusel de marcas** se ven un poco más pequeños: 140 × 70 px como máximo (100 × 50 en celular), centrados.

## [1.0.23] - 2026-11-21

### 🐛 Corregido
- Diseño V1, ficha del producto: **miniaturas de la galería apiladas en una columna** debajo de la foto. Pasaba solo en los productos con más fotos que «Product Thumbnail Numbers» de Martfury (5 en el sitio). En esos productos el JS de Martfury armaba un slider vertical, que chocaba con la cuadrícula del diseño. Ahora en V1 ese número es 100, así que el slider no se arma y las miniaturas van siempre en la cuadrícula de 5 por fila; con más fotos, en varias filas.

## [1.0.22] - 2026-11-21

### ✨ Añadido
- Diseño V1, **aviso «añadido al carrito»** (el de Martfury, arriba a la derecha) con el estilo del sitio: tarjeta blanca con borde verde a la izquierda, ícono verde y botón «Ver carrito». Textos en español: «se ha añadido a tu carrito.» y «Ver carrito». El aviso se activa en Personalizar → WooCommerce → Product Notifications → «Added to Cart Notification», que viene apagado.

## [1.0.21] - 2026-11-21

### 🐛 Corregido
- Diseño V1, portada, franja **«Compra ahora, paga después»**: en escritorio (1280 px o más) las 7 opciones van **en una sola línea** al lado del título. Antes «Tarjetas» bajaba sola a una segunda fila. Las cajas se reparten el ancho, y Bancolombia, con su texto más largo, ocupa un poco más. Entre 992 y 1279 px, el título va arriba y las opciones en una línea debajo.

## [1.0.20] - 2026-11-21

### ✨ Añadido
- Diseño V1, portada, franja **«Compra ahora, paga después»**: se suman **PSE** y **Tarjetas** (con ícono de tarjeta), y el texto pasa a «Financiación y medios de pago:».

### 🐛 Corregido
- Diseño V1, portada: la franja de financiación ahora ocupa el **100 % del container**, alineada con el header y las demás secciones. Antes quedaba más angosta por el ancho «en caja» y el relleno de la sección de Elementor. En pantallas medianas el título va arriba y las opciones en una fila. En tablet van 4 por fila y en celular 2, con Bancolombia al final para no dejar huecos.

## [1.0.19] - 2026-11-21

### ✨ Añadido
- Diseño V1, **página del carrito** como el checkout:
  - Productos en una tarjeta. Miniatura en caja redondeada, nombre en negro, precio actual en negrita con el anterior en rojo tachado, cantidad en una caja con − y +, y la X para quitar en un círculo que se pone rojo.
  - «Seguir comprando» y «Actualizar carrito» con borde redondeado. El cupón va en una línea, con su botón oscuro.
  - A la derecha, el resumen en otra tarjeta que acompaña el scroll en escritorio. El total va en negro (antes en rojo) y «Finalizar compra» en verde a todo el ancho.
  - La calculadora de envío queda plegada en su tarjeta y se abre con «Calcular envío» (Martfury la dejaba siempre abierta).
  - En tablet y celular va todo en una columna.

### 🐛 Corregido
- **Textos en inglés del carrito**: «Product», «Price», «Quantity», «Back To Shop», «Coupon Discount», «Shipping to…», «Remove this item» y los avisos de envío de las plantillas de Martfury.
- **Shortcodes de WPBakery como texto** (`[vc_row][vc_column]…`) en páginas armadas con ese plugin, que ya no está activo (p. ej. el carrito). Ahora se quitan al mostrar la página (`inc/grupsity/class-content.php`). Si WPBakery se vuelve a activar, no se tocan.

## [1.0.18] - 2026-11-20

### 🐛 Corregido
- Diseño V1, **desplegable del carrito** (header y panel del celular), como en TeDotamos:
  - Caja redondeada con sombra y miniaturas de ancho fijo.
  - Nombre del producto en 2 líneas como máximo.
  - Precio actual en negrita, sin el subrayado que le ponía el plugin de descuentos, con el anterior en rojo tachado.
  - Subtotal en negro, más grande (antes en rojo).
  - Botones a todo el ancho, uno debajo del otro: «Finalizar compra» en verde y primero, «Ver carrito» con borde. Antes «Finalizar compra» salía naranja, más corto y desalineado.
  - La X para quitar el producto se pone roja al pasar el ratón.

## [1.0.17] - 2026-11-20

### ✨ Añadido
- Diseño V1, **checkout como el de TeDotamos**: datos de facturación y «Tu pedido» en tarjetas, con campos redondeados y borde verde al escribir. El aviso del cupón va en verde suave. Envío y medios de pago son tarjetas con su círculo de selección (Martfury lo dibujaba con una fuente de íconos) y el logo a la derecha. El elegido lleva borde verde. Subtotal y total van en negro (antes en rojo), con casillas dibujadas con CSS y el botón «Realizar el pedido» verde a todo el ancho. En escritorio, el pedido acompaña el scroll.
- Diseño V1, **página «Pedido recibido»** como la de TeDotamos: tarjeta centrada con el aviso «Gracias…» en verde y el resumen (número, fecha, email, total y medio de pago) en cuadrícula. Las instrucciones del pago van destacadas, y los datos bancarios (si los hay), el detalle del pedido y las direcciones de facturación y envío, en cajas. En el celular, todo en una columna.

### 🐛 Corregido
- **Textos en inglés** (cualquier diseño), con la clase de traducciones de TeDotamos (`inc/grupsity/class-translations.php`; solo actúa si la cadena no tiene traducción):
  - Checkout: «Your order», «Have a coupon? Click here to enter your code», «Coupon code», «Apply coupon».
  - Otras cadenas de Martfury y WCBoost: favoritos, comparar, visor de fotos, mi cuenta…
  - Valores guardados en inglés: el título del envío «Shipment» → «Envío», la casilla «I have read and agree to the website términos y condiciones» → «He leído y acepto los términos y condiciones», y la descripción de Wompi «Pay via Wompi gateway.» → «Paga de forma segura a través de Wompi.».
  - Si en sus ajustes se pone otro texto, se respeta.

## [1.0.16] - 2026-11-20

### ✨ Añadido
- **Portada: espacio entre secciones configurable** (Apariencia → Grupsity → General → «Portada: espacio entre secciones»), un valor para escritorio y otro para celular (por defecto 24 y 16 px). Antes cada sección de Elementor traía sus propios márgenes y rellenos, y en el celular quedaba mucho hueco desigual. Ahora se quitan los de arriba y abajo de cada sección (salvo las que tienen fondo, que conservan su relleno) y queda solo ese espacio. Funciona con cualquier diseño.

## [1.0.15] - 2026-11-20

### 🐛 Corregido
- Diseño V1, **precio anterior**: el precio anterior viene con su propio `<ins>` dentro del `<del>`, y la regla del precio final (grande, negro, sin tachar) también lo alcanzaba. Por eso, en la ficha, salía igual de grande que el precio final y sin tachar. Ahora en la ficha sale **rojo, tachado y más pequeño** (18 px; 16 px en el celular) junto al precio final. En las tarjetas de la tienda y de relacionados vuelve a verse pequeño, gris y tachado (antes salía en negrita).

## [1.0.14] - 2026-11-20

### ✨ Añadido
- Diseño V1, **franja superior en el celular como slider**: los avisos («Entrega gratuita…», «Productos 100% originales», «Pago seguro…») van rotando uno a uno cada 3,5 s, deslizándose hacia arriba. Antes solo se veía el primero. En escritorio se siguen viendo todos a la vez. Sin JavaScript o con «reducir movimiento» activado en el teléfono, se queda el primero.

### 🐛 Corregido
- Diseño V1, ficha de producto: **el descuento salía doble y tachado** («-23%» de Grupsity y «(-23%)» de Martfury, metidos dentro del precio anterior porque otro filtro de precio reutiliza el HTML ya filtrado). Ahora sale una sola etiqueta «-23%», después de los precios y sin tachar.

## [1.0.13] - 2026-11-20

### 🔄 Cambiado
- Diseño V1, portada: la franja «Compra ahora, paga después» ya no lleva enlaces (Addi, Sistecrédito, su+Pay, Bancolombia y Nequi son solo etiquetas con sus colores). Se pueden añadir en Apariencia → Grupsity → General (`nombre|enlace`).
- Diseño V1, portada: se oculta el **slider de banners** (Sistecrédito/Addi) que quedaba encima de la franja, y la franja ocupa su lugar debajo del menú. Se hace desde el tema, sin borrarlo de Elementor: casilla «Ocultar el slider de banners de la portada» en Apariencia → Grupsity → General (activa por defecto; al desmarcarla vuelve a salir). En el editor de Elementor se sigue viendo.

## [1.0.12] - 2026-11-20

### ✨ Añadido
- Diseño V1, **header fijo solo en la fila del buscador**: al hacer scroll, la fila del logo, el buscador y los íconos (cuenta, wishlist, carrito) se queda arriba; la franja superior y la barra de menú se van con la página. En el celular se queda el logo con el buscador. Tiene en cuenta la barra de administración. El header fijo de Martfury (activo en el Personalizador, fijaba el header entero en la portada y el catálogo) se desactiva con V1.
- Diseño V1, **portada: franja «Compra ahora, paga después»** justo debajo del slider de banners (Sistecrédito/Addi): Addi, Sistecrédito, su+Pay (sumas.co), Compra y paga después Bancolombia (Wompi) y Nequi (créditos), cada una con los colores de su marca; las que tienen enlace abren en otra pestaña. Se edita en Apariencia → Grupsity → General (`nombre|enlace`, una por línea).

### 🐛 Corregido
- **Título de la portada** (cualquier diseño): la pestaña, Google y las redes mostraban «Home - Grupsity.Com» porque la página de inicio se llama «Home». Ahora sale solo «Grupsity.Com» (o «Grupsity.Com - eslogan», si el sitio tiene uno propio en Ajustes → Generales). Si en Rank Math se pone un título SEO personalizado a la portada, se respeta.

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
