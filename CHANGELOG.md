# 📋 Registro de cambios

Todos los cambios importantes del tema Grupsity. Formato basado en [Keep a Changelog](https://keepachangelog.com/es-ES/1.1.0/).

> ✍️ Antes de publicar, agrega arriba una sección `## [Sin publicar]` con los cambios. `npm run push-v -- patch|minor|major` la renombra a `## [X.Y.Z] - fecha`, y no publica si esa sección no existe.

## [1.0.1] - 2026-11-18

### ✨ Añadido
- Página **Apariencia → Grupsity** con pestañas de actualizaciones, general, migración e información, avisos de versión nueva y configuración del intervalo de caché de GitHub.
- Selector de diseño en General con dos opciones: `martfury` (por defecto) y `jomashop`, un estilo inspirado en jomashop.com aplicado sobre el HTML existente de Martfury (`css/design-jomashop.css`, `body.grupsity-design-jomashop`).
- Al cambiar de diseño se regeneran los estilos en línea del tema y se purga la caché de LiteSpeed.

### ⚙️ Estructura inicial
- Fork de Martfury 3.2.8 con `Theme Name: Grupsity`, `Update URI` y `Version: 1.0.0`.
- Bootstrap propio (`inc/grupsity/`) con hoja de estilos, updater desde GitHub Releases y migración del Customizer.
- Workflow de release que construye `grupsity.zip` al subir un tag.
