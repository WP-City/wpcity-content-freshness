# Seguimiento de la frescura

Cómo decide el plugin si un contenido está fresco, próximo a vencer o atrasado.

## El cálculo

Para cada contenido controlado:

1. Toma el **intervalo efectivo**. Es el intervalo propio de la entrada si lo tiene, y si no el del sitio.
2. Toma la **fecha de referencia**. Es `wpcity_cf_last_reviewed` si la entrada se ha revisado, y si no su fecha de última modificación.
3. La fecha límite es la de referencia más el intervalo efectivo.
4. El estado sale de los días que quedan hasta esa fecha límite.

| Días restantes | Color | Etiqueta |
|----------------|-------|----------|
| Más de 30 | Verde | Revisado el [fecha] |
| 30 o menos | Naranja | Vence en [n] días |
| 0 o menos | Rojo | Con [n] días de retraso |

Una entrada con el intervalo en "No hacer seguimiento" queda gris y se omite en todas partes.

## Por qué las entradas nunca revisadas usan la fecha de modificación

Sin fecha de referencia, todas las entradas del sitio estarían atrasadas en el momento de activar el plugin, y el widget del escritorio no serviría de nada el primer día. Recurrir a la fecha de modificación significa que una página editada la semana pasada se lee como fresca, y una página intacta desde hace tres años como atrasada, que es la respuesta que realmente buscabas.

Marcar una entrada como revisada sustituye ese recurso por una fecha de revisión real. A partir de ahí, editarla ya no reinicia su frescura, y de eso se trata: una edición no es una revisión.

## Marcar como revisado

El botón **Marcar como revisado** escribe la hora actual del sitio en `wpcity_cf_last_reviewed` por AJAX y actualiza la línea de estado sobre la marcha. La petición está protegida con un nonce y exige `edit_post` sobre esa entrada.

## Ordenar la lista

La columna **Frescura del contenido** se puede ordenar. La ordenación va por fecha de revisión y mantiene en la lista las entradas nunca revisadas, así que pulsar en la cabecera nunca te oculta contenido.

## El widget del escritorio

El widget cuenta todas las entradas controladas y publicadas que estén en naranja o rojo, muestra las cinco más urgentes y enlaza a la lista completa. Las entradas en "No hacer seguimiento" no aparecen nunca.
