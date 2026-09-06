# Preguntas frecuentes

### ¿Editar una entrada reinicia su frescura?

No, y es intencionado. Una edición no es una revisión. Solo el botón **Marcar como revisado**, o escribir tú mismo `wpcity_cf_last_reviewed`, mueve la fecha.

La única excepción es una entrada que nunca se ha revisado. No tiene ninguna fecha con la que contar, así que el plugin recurre a su fecha de última modificación hasta que la revises por primera vez.

### ¿Por qué está todo en verde justo después de instalarlo?

Porque todavía no se ha revisado nada y cada entrada se mide desde su propia fecha de modificación. El contenido editado hace poco se lee como fresco. Las páginas que llevas años sin tocar ya salen en naranja o rojo.

### ¿Puedo dejar fuera una sola página?

Sí. Pon su **Intervalo de revisión** en "No hacer seguimiento". El estado pasa a gris, la entrada desaparece del widget del escritorio y la columna muestra un guion.

### ¿Adónde fueron mis entradas al ordenar por la columna de frescura?

A ningún sitio. Las versiones anteriores sacaban de la lista todas las entradas nunca revisadas en cuanto ordenabas por esa columna. Está corregido: ordenar mantiene ahora la lista completa.

### He cambiado el intervalo predeterminado y no pasa nada.

El valor predeterminado solo se aplica a las entradas cuyo intervalo propio está en "Usar el valor predeterminado". Cualquier entrada a la que diste un ciclo fijo de 3, 6 o 12 meses conserva ese ciclo.

### ¿El plugin envía algo a alguna parte?

No. El plugin gratuito no hace ninguna petición externa. Los webhooks y los resúmenes por correo forman parte de la extensión Pro y están apagados hasta que los configuras.

### ¿Funciona con tipos de contenido personalizados?

Sí, siempre que el tipo de contenido sea público. Márcalo en **Ajustes > Frescura del contenido**, o añádelo por código con el filtro `wpcity_cf_post_types`.

### ¿Qué pasa con mis datos si borro el plugin?

Se eliminan las cuatro cosas que guarda: los dos ajustes, y el intervalo y la fecha de revisión de cada entrada. Nada más. Si además usas Pro, sus ajustes y su licencia se quedan donde están y se van cuando borras Pro. Desactivarlo no borra absolutamente nada.

### ¿La fecha de revisión se guarda en hora del sitio o en UTC?

En hora del sitio. Se escribe con `current_time( 'mysql' )` y se muestra con el formato de fecha del propio sitio.

### ¿Puedo marcar varias entradas como revisadas a la vez?

En el plugin gratuito no. Las acciones en lote, la API REST, el aplazamiento, los webhooks y los resúmenes por correo forman parte de WPCity Content Freshness Pro.
