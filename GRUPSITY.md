# Tema Grupsity

Copia independiente (fork) de **Martfury 3.2.8** (DrFuri), que es el tema activo en
grupsity.com según `theme/martfury/style.css`. Al tener otro nombre y otra carpeta, las
actualizaciones de Martfury (Envato Market, panel de WordPress) **no** lo sobrescriben.
Se actualiza solo desde nuestro repo: `https://github.com/franciscoblancojn/grupsity-theme`
(**crear el repo antes de publicar: requiere confirmación**).

> Arranca idéntica a Martfury 3.2.8 salvo la infraestructura de abajo. Incluye la página
> **Apariencia → Grupsity** (actualizaciones, selector de diseño y migración) y el diseño
> **JomaShop**, inspirado en https://www.jomashop.com/, que se activa sin tocar el contenido
> ni la configuración del Customizer. Cada cambio se anota en la tabla.

## Qué cambió respecto a Martfury 3.2.8

| Archivo | Cambio |
|---|---|
| `style.css` | Cabecera: `Theme Name: Grupsity`, `Update URI` a GitHub, `Version: 1.0.0` |
| `functions.php` | Al final: `require` de `inc/grupsity/bootstrap.php` |
| `inc/grupsity/bootstrap.php` | Carga el código propio |
| `inc/grupsity/class-assets.php` + `css/grupsity.css` | Hoja de estilos propia, cargada con prioridad 20 |
| `inc/grupsity/class-updater.php` | Actualizaciones desde GitHub Releases (token desde `wp-config.php`, nunca en el código); el intervalo de caché se configura en la página de admin |
| `inc/grupsity/class-admin.php` | Página Apariencia → Grupsity: actualizaciones, diseño, migración, información y avisos |
| `inc/grupsity/class-designs.php` | Registro del selector de diseño, `body_class`, carga condicional del CSS y purga de caché |
| `css/design-jomashop.css` | Diseño con la forma de JomaShop y los colores de Grupsity (verde `#93b700`, tinta, blanco; Inter; footer oscuro) |
| `inc/grupsity/class-design-v1.php` + `css/design-v1.css` | Diseño V1: estructura de TeDotamos V1 (franja, header, buscador, barra de menú, desplegable de categorías, container de 1320 px) con colores y fuentes de Grupsity; header fijo solo en la fila del buscador; en la portada, franja «Compra ahora, paga después» debajo del banner principal (primer slider que se imprime; gancho `elementor/frontend/widget/after_render`) y logos de marcas más pequeños |
| `inc/grupsity/class-home-sections.php` | Diseño V1, portada: secciones por categoría (título + carrusel de marcas + carrusel de banners) antes de «Ofertas del día» (gancho `elementor/frontend/widget/before_render`), configuradas en la opción `v1_home_sections` |
| `template-parts/headers/layout-1.php` | Gancho `grupsity_header_nav`: un diseño (V1) puede reemplazar el menú y el Header Bar |
| `inc/functions/header.php` | Filtro `grupsity_department_menu` en `martfury_extra_department()`: un diseño (V1) puede reemplazar la lista del desplegable «Categorías» |
| `inc/grupsity/class-catalog.php` | Navegación del catálogo (`catalog_nav`: scroll infinito / números / Customizer) vía `martfury_get_option`, y script que sincroniza los campos del filtro de precios con el slider |
| `woocommerce/loop/pagination.php` | Scroll infinito: solo el enlace a la página siguiente (`nav` vacío en la última) y `%_%` de WooCommerce 11 expandido. Mismo arreglo que TeDotamos |
| `css/grupsity.css` | Botón «Cargando más productos» del scroll infinito (con cualquier diseño) |
| `inc/grupsity/class-product.php` | Ficha de producto: pestañas y «Marca:» en español, oculta Instagram sin token, «Productos relacionados»; con V1, botón de WhatsApp y % de descuento junto al precio |
| `inc/grupsity/class-seo.php` | Portada: título «Home - Grupsity.Com» (Rank Math o WordPress) → nombre del sitio (+ eslogan si tiene uno propio). Un título SEO personalizado no se toca |
| `inc/grupsity/class-translations.php` | Textos en inglés → español (gettext solo si la cadena no tiene traducción; como en TeDotamos) + «Shipment», casilla de términos y descripción de Wompi guardados en inglés |
| `inc/grupsity/class-content.php` | Quita del contenido los shortcodes de WPBakery (`[vc_row]`…) que se veían como texto porque el plugin ya no está activo. Con WPBakery activo no hace nada |
| `css/design-v1.css` (bloques «Mini carrito», «Carrito (página)», «Checkout» y «Pedido recibido») | Desplegable del carrito, página del carrito, checkout y gracias por tu compra como en TeDotamos, con los colores de Grupsity |
| `woocommerce/single-product/add-to-cart/simple.php` | Sin stock imprime «Agotado» (`wc_get_stock_html`) y lanza `grupsity_add_to_cart_unavailable` (antes el bloque salía vacío en la plantilla de Elementor) |
| `inc/grupsity/class-migration.php` | Copia la configuración del Customizer de Martfury al activar y botones con nonce en la pestaña Migración |
| `.github/workflows/release.yml` | Construye `grupsity.zip` y crea la release al subir un tag |
| `CHANGELOG.md` | Registro de cambios (Keep a Changelog) |
| `GRUPSITY.md` | Este archivo |

Todo lo demás es idéntico al original (`theme/martfury/` en este repo es la copia de
referencia y **no se modifica**).
De momento **no hay** cambios de diseño ni de comportamiento: cuando se añadan, se
marcan con `Grupsity:` y se listan aquí.

### Lo que se dejó igual a propósito

- **Text Domain `martfury`**: así siguen funcionando las traducciones existentes
  (`wp-content/languages/themes/martfury-*.mo` y las de Loco Translate).
- **Funciones, hooks, meta keys y opciones `martfury_*`**: los plugins del tema y la
  base de datos dependen de esos nombres. Renombrarlos rompería el sitio.
- La versión base es 3.2.8 (no 3.4.4): si se quiere traer un arreglo de otra versión,
  comparar con `theme/martfury/` y aplicarlo a mano.

## Actualizaciones

**Cómo funciona**

- `Update URI` apunta a GitHub. WordPress (6.1+) llama al filtro
  `update_themes_github.com`, y `Grupsity_Updater` responde con la última release
  (`/releases/latest`).
- La instalación la hace el actualizador estándar de temas de WordPress; el tema se
  excluye de la consulta a api.wordpress.org.
- **Paquete:** el asset `grupsity.zip` de la release. Si la release no lo tiene, se usa
  el código fuente del tag; al instalar, la carpeta se renombra siempre a `grupsity/`,
  porque si cambiara se perderían las opciones del tema.
- **Caché:** la consulta a GitHub se guarda en el transient `grupsity_latest_release`
  (6 h por defecto).
- **Token:** opcional, solo para el límite de la API. Se define en `wp-config.php`:
  `define( 'GRUPSITY_GITHUB_TOKEN', '...' );`. **Nunca** va en el código del tema.
- Solo funciona mientras Grupsity es el tema activo (o el padre del tema activo).

**Publicar una versión** (confirmar siempre antes: crea commit, tag y push)

Antes, anotar los cambios en `CHANGELOG.md` bajo `## [Sin publicar]`.

```bash
npm run push-v -- patch     # o minor | major
```

- `scripts/bump-version.sh` sube `Version` en `style.css`, cambia `## [Sin publicar]`
  por `## [X.Y.Z] - fecha` y llama a `npm run push-tag` (sincroniza `package.json`,
  commit, tag y push).
- Después, el workflow verifica que el tag coincida con `style.css`, construye
  `grupsity.zip` (sin `scripts/` ni `package.json`) y crea la release.

## Página de configuración

`Apariencia → Grupsity` tiene cuatro pestañas:

- **Actualizaciones**: versión instalada y publicada, estado, diagnóstico, notas de la release,
  botón para forzar la revisión y enlace al actualizador de WordPress.
- **General**: selector de **diseño**, frecuencia de consulta a GitHub (1–24 h) y avisos de versión nueva.
- **Migración**: estado de la copia `theme_mods_martfury` → `theme_mods_grupsity`, copiar y restaurar.
- **Información**: tema, carpeta, Update URI, diseño activo y documentación.

Todo se guarda en `grupsity_options` (`Grupsity_Admin::OPTION`). Opciones nuevas: añadirlas a
`DEFAULTS`, `sanitize()` y `render_general_tab()` de `class-admin.php`.

## Selector de diseño

`grupsity_options['design']` admite:

- `martfury` (por defecto): el sitio como está, sin CSS añadido.
- `jomashop`: la **forma** de https://www.jomashop.com/ (noviembre 2026) con los **colores de
  Grupsity**, aplicada sobre el HTML de Martfury. De JomaShop: Inter 14 px (se encola desde
  Google Fonts), franja superior oscura, buscador de 52 px con radio 10 px, menú en mayúsculas,
  filtros laterales abiertos con línea, tarjetas sin bordes con imagen sobre `#f7f7f7`, precio en
  negrita con el anterior tachado y footer oscuro. De Grupsity: verde `#93b700` (botones, página
  actual, etiquetas «Hot», cabecera del layout 4), tinta `#111`/`#1c2014` y blanco.
  Contraste: sobre verde el texto va en tinta (el blanco da 2,3:1); el verde como texto sobre
  blanco usa `#5c7300`. Cubre el layout 4 (configuración de Grupsity) y el layout 1 (el que sale
  si faltan los theme_mods).

- `v1`: la estructura del tema **TeDotamos V1** (repo `tedotamos`, `css/templates/v1.css`) con los
  colores y fuentes de Grupsity. Fuerza el header layout 1 de Martfury y desactiva su
  topbar/promoción en tiempo de ejecución (filtro `martfury_get_option`, sin tocar los theme_mods).
  Pinta la franja superior, la barra de menú (`grupsity_header_nav`) y el desplegable de
  categorías (`grupsity_department_menu`: categorías de producto con ícono; si solo hay una raíz,
  p. ej. «Relojes», muestra sus hijas). Container de 1320 px en todo el sitio y en la portada de
  Elementor. Textos, enlaces y teléfono en la pestaña General. Catálogo, tarjetas y footer: igual
  que JomaShop (copiado en `design-v1.css`).

`Grupsity_Designs` añade la clase `grupsity-design-<id>` al `body` y carga
`css/design-<id>.css` con prioridad 100 (después de Martfury, WooCommerce, Elementor y
vendors). Al cambiar de diseño se regeneran los estilos en línea del tema y se purga LiteSpeed.
No modifica `theme_mods_*`, así que volver a Martfury deja el sitio exactamente como estaba.

Para añadir un diseño: registrarlo en `Grupsity_Designs::DESIGNS`, crear su CSS y, si hace
falta lógica, una clase propia cargada desde `bootstrap.php`.

### Nota sobre el sitio en producción

En grupsity.com la carpeta activa es **`grupsity-theme-master`** (no `grupsity`). El body
tiene `wp-theme-grupsity-theme-master` y, con el diseño activo, `grupsity-design-jomashop`;
la hoja se sirve desde `/wp-content/themes/grupsity-theme-master/css/design-jomashop.css`.
Si el CSS no se ve, comprobar esos tres puntos antes de mirar la caché.

Cada carpeta guarda su propio `theme_mods_<carpeta>`. Si la home o las categorías salen con el
header por defecto de Martfury (layout 1, «I'm shopping for…», «Shop By Department»,
«Copyright © 2020», amarillo), a la carpeta activa le falta la configuración: Apariencia →
Grupsity → Migración lo indica y permite copiarla (escritura en el sitio: confirmar antes). La home es una
plantilla de Elementor: el diseño JomaShop es una capa de estilos sobre ese HTML, no una
sustitución de la portada.

## Personalizaciones del sitio

El tema es nuestro: las personalizaciones se hacen **directamente** en sus archivos.

- En PHP, marcar cada cambio con un comentario `Grupsity:` y anotarlo en la tabla.
- El CSS propio va en `css/grupsity.css`. Carga después de WooCommerce, Elementor y los
  colores del Customizer.
- La lógica nueva va en `inc/grupsity/`, un archivo por funcionalidad, cargado desde
  `inc/grupsity/bootstrap.php`.

## Reglas para modificar el tema

1. No tocar `theme/martfury/` (copia de referencia).
2. Si hay que tocar un archivo original del fork, marcarlo con `// Grupsity:` y
   anotarlo en la tabla de arriba.
3. Subir `Version` en `style.css` en cada entrega (también invalida caché).
4. Para traer un arreglo de una versión nueva de Martfury: descargar esa versión,
   compararla con `theme/martfury/` (3.2.8) y aplicar a mano solo lo que interese.
5. Empaquetar en local con `npm run build:theme` (raíz del harness). Subir o activar
   el zip en producción es una escritura en el sitio: mostrar antes el plan de abajo
   y pedir confirmación.

## Migración en producción (requiere confirmación, son escrituras en el sitio)

⚠ **Las opciones del Customizer se guardan por tema** (Kirki con `option_type: theme_mod`,
en la opción `theme_mods_martfury`). Si se activa Grupsity sin migrarlas, el sitio pierde
el header, el footer, los colores, el logo, la asignación de menús y demás ajustes.

1. Backup completo (archivos + BD) desde el panel de hosting.
2. Subir `dist/grupsity-<version>.zip` en Apariencia → Temas → Añadir → Subir, **sin activarlo**.
3. Las opciones se copian **solas** al activar Grupsity por primera vez
   (`theme_mods_martfury` → `theme_mods_grupsity`, ver `class-migration.php`). Antes de
   sobrescribir se guarda una copia de lo que tuviera Grupsity. También se pueden
   ejecutar las acciones desde **Apariencia → Grupsity → Migración** (con nonce incluido).
4. Previsualizar Grupsity en el Customizer (vista previa en vivo, sin activarlo) y revisar
   la home, una categoría, un producto, el carrito y el checkout.
5. Activar. Después, purgar la caché y regenerar el CSS del constructor de páginas si aplica.
6. Revisar menús (Apariencia → Menús → Gestionar ubicaciones) y widgets.
7. Rollback: reactivar Martfury (sus opciones siguen intactas en `theme_mods_martfury`).
8. Dejar Martfury instalado pero inactivo unas semanas; luego se puede borrar.
