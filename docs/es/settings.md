# Ajustes

Todos los ajustes están en **Ajustes > Frescura del contenido**.

## Tipos de contenido

Qué tipos de contenido reciben una caja de frescura, una columna en la lista y un lugar en el widget del escritorio.

Aparecen todos los tipos de contenido públicos del sitio, salvo los adjuntos. Las entradas y las páginas vienen marcadas.

Desactivar un tipo de contenido oculta la caja y la columna, pero conserva las fechas de revisión ya guardadas. Vuelve a activarlo y el historial sigue ahí.

## Intervalo de revisión predeterminado

Cuánto tiempo se considera fresco el contenido antes de volver a mirarlo: 3 meses (90 días), 6 meses (180 días) o 12 meses (365 días). El valor predeterminado es de 6 meses.

Es el intervalo que usa todo contenido controlado que no tenga el suyo propio. Para darle otro ciclo a una entrada concreta, cambia **Intervalo de revisión** en la caja lateral de esa entrada.

## Ajuste por entrada

El desplegable **Intervalo de revisión** de la barra lateral acepta:

| Opción | Valor guardado | Efecto |
|--------|----------------|--------|
| Usar el valor predeterminado | `0` | Sigue el valor del sitio, así que cambiarlo mueve también esta entrada |
| 3 meses | `90` | Ciclo fijo de 90 días |
| 6 meses | `180` | Ciclo fijo de 180 días |
| 12 meses | `365` | Ciclo fijo de 365 días |
| No hacer seguimiento | `-1` | Sin estado, sin color, fuera del widget del escritorio |

## Dónde se guardan los datos

| Clave | Tipo | Significado |
|-------|------|-------------|
| `wpcity_cf_post_types` | Opción | Array con los tipos de contenido controlados |
| `wpcity_cf_default_interval` | Opción | Intervalo predeterminado del sitio, en días |
| `wpcity_cf_review_interval` | Post meta | Intervalo propio de la entrada, en días |
| `wpcity_cf_last_reviewed` | Post meta | Fecha MySQL de la última revisión |

Al borrar el plugin desaparecen las cuatro, junto con cualquier otra clave del espacio de nombres `wpcity_cf_`.
