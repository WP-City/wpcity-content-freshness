# Primeros pasos

WPCity Content Freshness registra cuándo se revisó por última vez cada contenido y muestra de un vistazo qué páginas se han quedado desactualizadas.

## Instalación

1. Sube la carpeta `wpcity-content-freshness` a `/wp-content/plugins/`
2. Activa el plugin desde el menú **Plugins** de WordPress
3. Ve a **Ajustes > Frescura del contenido** para elegir qué tipos de contenido quieres controlar

## Primeros pasos

### 1. Elige tus tipos de contenido

Tras la activación, abre **Ajustes > Frescura del contenido**. Verás todos los tipos de contenido públicos de tu sitio. Marca los que necesiten un ciclo de revisión. Las entradas y las páginas vienen activadas.

### 2. Define un intervalo de revisión predeterminado

En la misma pantalla, elige cada cuánto hay que volver a mirar el contenido: 3, 6 o 12 meses. Ese intervalo se aplica a todo el contenido controlado, salvo que lo cambies en la propia entrada.

### 3. Revisa un contenido

Abre una entrada o página controlada. La caja **Frescura del contenido** está en la barra lateral, debajo del bloque Publicar, y muestra tres cosas:

- **Intervalo de revisión**: usar el valor predeterminado, elegir 3, 6 o 12 meses, o elegir "No hacer seguimiento" para dejar este contenido fuera por completo
- **Última revisión**: la fecha en que se confirmó por última vez que el contenido estaba al día
- Una línea de estado en color que indica cómo está este contenido

Pulsa **Marcar como revisado** y la fecha se guarda al instante, sin recargar la página.

### 4. Lee los colores

| Color | Significado |
|-------|-------------|
| Verde | Revisado hace poco, nada que hacer |
| Naranja | Vence en los próximos 30 días |
| Rojo | Con retraso, el intervalo ya pasó |
| Gris | Sin seguimiento, elegiste "No hacer seguimiento" |

El mismo punto aparece en la columna **Frescura del contenido** de cada lista, junto al título. Pulsa en la cabecera de la columna para ordenar por fecha de revisión; las entradas nunca revisadas se quedan en la lista.

### 5. Vigila el escritorio

El widget **Frescura del contenido** enumera las cinco entradas más urgentes, con un enlace a la lista completa. Cuando no hay nada atrasado, simplemente lo dice.

## Qué cuenta como "última revisión"

Una entrada que nunca se ha marcado como revisada usa su propia fecha de modificación. Así una página recién escrita no aparece atrasada el mismo día en que instalas el plugin, y la cifra significa algo desde el primer día.

## Para seguir

- [Ajustes](settings.md): cada opción explicada
- [Seguimiento de la frescura](freshness.md): cómo se calcula el estado
- [Hooks y filtros](hooks.md): ampliar el plugin desde el código
- [Preguntas frecuentes](faq.md)
