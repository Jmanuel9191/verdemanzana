/* ==========================================================================
   Verde Manzana · Ficha del cliente (/perfilreserva) · "Mis observaciones"
   Subir varias imágenes ahí mismo (una por una, con su estado) y borrarlas.
   Usa las rutas de siempre (site/upload2, upload3, delete, borrar); el servidor
   verifica que la reserva sea del cliente logueado.
   Se carga solo en la vista del cliente (views/site/reservas/item.php).
   ========================================================================== */
(function ($) {
    'use strict';

    var MAX_MB = 8;
    var TIPOS = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];

    function csrf() {
        var d = {};
        d[yii.getCsrfParam()] = yii.getCsrfToken();
        return d;
    }

    // Vuelve a armar el popup de la galería (para que incluya las imágenes nuevas)
    function reiniciarPopup(galeria) {
        if (!$.fn.magnificPopup) { return; }
        galeria.off('click.magnificPopup').removeData('magnificPopup');
        galeria.magnificPopup({
            delegate: 'a.vm-zoom',
            type: 'image',
            mainClass: 'mfp-with-zoom',
            zoom: {enabled: true, duration: 300},
            image: {titleSrc: 'title'},
            gallery: {enabled: true, tPrev: 'Anterior', tNext: 'Siguiente', tCounter: '%curr% de %total%'},
            tClose: 'Cerrar (Esc)',
            tLoading: 'Cargando...'
        });
    }

    function actualizarVacio(galeria) {
        galeria.siblings('.vm-sin-imagenes').toggle(galeria.children('.vm-miniatura').length === 0);
    }

    function miniatura(id, url, titulo) {
        var div = $('<div class="vm-miniatura"></div>').attr('data-id', id);
        var a = $('<a class="vm-zoom"></a>').attr('href', url).attr('title', titulo);
        a.append($('<img alt="">').attr('src', url));
        div.append(a).append('<button type="button" class="vm-borrar-img" title="Borrar imagen">&times;</button>');
        return div;
    }

    // ---- Subir
    $(document).on('change', '.vm-input-subir', function () {
        var input = this;
        var $in = $(input);
        var galeria = $($in.data('destino'));
        var estado = galeria.siblings('.vm-subida-estado');
        var archivos = Array.prototype.slice.call(input.files || []);
        input.value = '';            // permite volver a elegir el mismo archivo
        if (!archivos.length) { return; }

        var total = archivos.length, hechas = 0, errores = [];
        estado.removeClass('vm-aviso-error').addClass('vm-subiendo').text('Subiendo 0 de ' + total + '…').show();

        // Una por una (más estable en celulares y conexiones lentas)
        (function siguiente() {
            if (!archivos.length) {
                estado.removeClass('vm-subiendo');
                if (errores.length) {
                    estado.addClass('vm-aviso-error').html(errores.join('<br>'));
                } else {
                    estado.text(hechas === 1 ? 'Imagen subida.' : hechas + ' imágenes subidas.');
                    setTimeout(function () { estado.fadeOut(); }, 2500);
                }
                actualizarVacio(galeria);
                reiniciarPopup(galeria);
                return;
            }
            var f = archivos.shift();
            if (TIPOS.indexOf(f.type) === -1) {
                errores.push('“' + f.name + '”: solo JPG, PNG, WEBP o GIF.');
                return siguiente();
            }
            if (f.size > MAX_MB * 1024 * 1024) {
                errores.push('“' + f.name + '”: pesa más de ' + MAX_MB + ' MB.');
                return siguiente();
            }
            var datos = new FormData();
            datos.append($in.data('campo'), f);
            datos.append('id', $in.data('reserva'));
            $.each(csrf(), function (k, v) { datos.append(k, v); });

            $.ajax({url: $in.data('url'), type: 'POST', data: datos, processData: false, contentType: false, dataType: 'json'})
                .done(function (r) {
                    if (r && r.error) {
                        errores.push('“' + f.name + '”: ' + r.error);
                    } else if (r && r.id) {
                        galeria.append(miniatura(r.id, r.url, galeria.data('titulo')));
                        hechas++;
                    }
                })
                .fail(function () { errores.push('“' + f.name + '”: no se pudo subir. Probá de nuevo.'); })
                .always(function () {
                    estado.text('Subiendo ' + (total - archivos.length) + ' de ' + total + '…');
                    siguiente();
                });
        })();
    });

    // ---- Borrar
    $(document).on('click', '.vm-borrar-img', function (e) {
        e.preventDefault();
        var mini = $(this).closest('.vm-miniatura');
        var galeria = mini.closest('.vm-galeria-cliente');
        if (!confirm('¿Borrar esta imagen?')) { return; }
        mini.addClass('vm-borrando');
        $.post(galeria.data('borrar'), $.extend({key: mini.data('id')}, csrf()), null, 'json')
            .done(function (r) {
                if (r && r.error) {
                    mini.removeClass('vm-borrando');
                    alert(r.error);
                    return;
                }
                mini.remove();
                actualizarVacio(galeria);
                reiniciarPopup(galeria);
            })
            .fail(function () {
                mini.removeClass('vm-borrando');
                alert('No se pudo borrar la imagen. Probá de nuevo.');
            });
    });

    // Evita doble envío de las observaciones
    $(document).on('submit', '#form-observaciones', function () {
        $(this).find('button[type=submit]').prop('disabled', true).html('Guardando…');
    });
})(jQuery);
