/* ==========================================================================
   Verde Manzana · JavaScript del panel de administración
   Se carga en todas las páginas del admin (assets_b/AdminPropioAsset.php y
   AdminLoginAsset.php) en el <head>, justo después de jQuery: los buscadores
   Select2 de Kartik necesitan format() antes de que se arme la página.
   Por eso todo lo que toca la página va dentro de $(function () { ... }) o
   como evento delegado en document.

   - Las funciones globales (format, action, cambiarEstado) las llaman widgets
     o atributos onclick de las vistas.
   - Los eventos van "delegados" en document: siguen funcionando cuando una
     grilla se recarga con Pjax sin refrescar la página.
   - Cada bloque revisa si su pantalla está presente antes de hacer algo.
   ========================================================================== */

/* ---------- Funciones globales ---------- */

/** Select2: cómo se muestra cada opción (JsExpression('format') en los formularios). */
function format(state) {
    return state.text;
}

/** Grillas viejas: subir / bajar / estado por GET y recargar la grilla (#table). */
function action(url) {
    $.get(url, function () {
        $.pjax.reload({container: '#table'});
    });
}

/** Reservas · listado: activar / desactivar una reserva (POST) y recargar la grilla. */
function cambiarEstado(url) {
    $.post(url, {_csrf: yii.getCsrfToken()})
        .done(function () { $.pjax.reload({container: '#table', timeout: 5000}); })
        .fail(function (xhr) {
            alert(xhr.responseJSON && xhr.responseJSON.mensaje ? xhr.responseJSON.mensaje : 'No se pudo cambiar el estado.');
        });
}

(function ($) {
    'use strict';

    window.VM = window.VM || {};

    /* ---------- Login del admin: casillas con estilo iCheck ---------- */
    $(function () {
        if ($('body').hasClass('login-page') && $.fn.iCheck) {
            $('input').iCheck({
                checkboxClass: 'icheckbox_square-blue',
                radioClass: 'iradio_square-blue',
                increaseArea: '20%'
            });
        }
    });

    /* ---------- Grillas de Ítems y Paquetes: botón de estado (POST + Pjax) ---------- */
    function cambiarEstadoGrilla(selectorBoton, contenedor) {
        $(document).on('click', selectorBoton, function (e) {
            e.preventDefault();
            var btn = $(this);
            btn.prop('disabled', true);
            $.post(btn.data('url'), {_csrf: yii.getCsrfToken()})
                .done(function () { $.pjax.reload({container: contenedor, timeout: 5000}); })
                .fail(function () {
                    alert('No se pudo cambiar el estado. Recargá la página e intentá de nuevo.');
                    btn.prop('disabled', false);
                });
        });
    }
    cambiarEstadoGrilla('.btn-item-estado', '#grilla-items');
    cambiarEstadoGrilla('.btn-paquete-estado', '#grilla-paquetes');

    /* ---------- Ítems · formulario: mostrar los campos de proveedor según el tipo ---------- */
    $(function () {
        var bloque = $('#campos-proveedor');
        if (!bloque.length) { return; }
        var campo = $('#' + (bloque.data('campo') || 'items-tipo'));
        function actualizar() { bloque.toggle(campo.val() === 'proveedor'); }
        campo.on('change', actualizar);
        actualizar();
    });

    /* ---------- Reservas · listado: Excel con los filtros que estén puestos ---------- */
    // La grilla filtra con Pjax (sin recargar), así que los filtros se toman al hacer clic
    $(document).on('click', '#btn-excel-reservas', function () {
        this.href = this.href.split('?')[0] + window.location.search;
    });

    /* ---------- Reservas (formulario viejo) · ver / guía: galería de imágenes ---------- */
    $(function () {
        if (!$.fn.magnificPopup || !$('.image-popup').length) { return; }
        $('.image-popup').magnificPopup({
            type: 'image',
            closeOnContentClick: false,
            removalDelay: 600,
            mainClass: 'my-mfp-slide-bottom',
            image: {verticalFit: true},
            gallery: {
                enabled: true,
                navigateByImgClick: true,
                preload: [0, 1],
                tPrev: 'Anterior',
                tNext: 'Siguiente',
                tCounter: '%curr% de %total%',
                arrowMarkup: '<button title="%title%" type="button" class="mfp-arrow mfp-arrow-%dir%"></button>'
            }
        });
    });

    /* ---------- Reserva por pasos · contraseña del cliente: botón del ojito ---------- */
    $(document).on('click', '.btn-ver-contrasena', function () {
        var campo = $('#' + $(this).data('objetivo'));
        var mostrar = campo.attr('type') === 'password';
        campo.attr('type', mostrar ? 'text' : 'password');
        $(this).find('i').toggleClass('fa-eye', !mostrar).toggleClass('fa-eye-slash', mostrar);
        $(this).attr('title', mostrar ? 'Ocultar' : 'Mostrar');
        campo.focus();
    });

    /* ---------- Reserva por pasos · detalles: Enter no envía el formulario ---------- */
    $(document).on('keydown', '#form-detalles input', function (e) {
        if (e.key === 'Enter') { e.preventDefault(); }
    });

    /* ---------- Reserva por pasos · pagos ---------- */
    $(document).on('click', '#btn-nuevo-pago', function () {
        $('#panel-nuevo-pago').slideDown(150, function () { $('#reservapago-monto').focus(); });
    });
    $(document).on('click', '#btn-cancelar-pago', function () {
        $('#panel-nuevo-pago').slideUp(150);
    });
    // "Saldo": pone el saldo pendiente (viene en data-saldo del botón)
    $(document).on('click', '#btn-saldo', function () {
        $('#reservapago-monto').val($(this).data('saldo')).trigger('change');
    });
    // Evita doble clic (dos pagos iguales)
    $(document).on('beforeSubmit', '#form-pago', function () {
        $('#btn-guardar-pago').prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Guardando…');
        return true;
    });
    // Modal de anulación
    $(document).on('click', '.btn-anular', function () {
        $('#anular-idpago').val($(this).data('idpago'));
        $('#anular-detalle').text($(this).data('detalle'));
        $('#anular-motivo').val('');
        $('#modal-anular').modal('show');
    });
    $(document).on('shown.bs.modal', '#modal-anular', function () { $('#anular-motivo').focus(); });
    $(document).on('submit', '#form-anular', function () {
        if ($.trim($('#anular-motivo').val()).length < 5) {
            alert('Escribí el motivo de la anulación (mínimo 5 caracteres).');
            return false;
        }
        $('#btn-confirmar-anular').prop('disabled', true);
        return true;
    });

    /* ---------- Reserva por pasos · presupuesto (crear / editar / modificar) ----------
       Calendarios por sucursal, paquete, ítems/proveedores y cuentas en vivo.
       Los datos (catálogos, fechas ocupadas, lo pagado) vienen en data-config del formulario.
       Se arranca desde la vista con VM.iniciarPresupuesto(), DESPUÉS de que Kartik
       inicia sus calendarios (el orden importa). */
    VM.iniciarPresupuesto = function () {
        if (!$('#form-presupuesto').length) { return; }
        var D = $('#form-presupuesto').data('config');
        var fmt2 = new Intl.NumberFormat('de-DE', {minimumFractionDigits: 2, maximumFractionDigits: 2});

        // ---- números: acepta "25.200,50", "25200,5", "25,200.50", "25200.5"
        function parse(txt) {
            if (txt === null || txt === undefined) return 0;
            var s = String(txt).replace(/\s/g, '');
            if (s === '') return 0;
            if (/^\d{1,3}(\.\d{3})+(,\d{1,2})?$/.test(s)) { s = s.replace(/\./g, '').replace(',', '.'); }
            else if (/^\d{1,3}(,\d{3})+(\.\d{1,2})?$/.test(s)) { s = s.replace(/,/g, ''); }
            else { s = s.replace(',', '.'); }
            var n = parseFloat(s);
            return isNaN(n) ? 0 : n;
        }
        function bs(c) { return fmt2.format(c / 100); }                      // centavos -> "25.200,00"
        function pctTxt(p) { return (Math.round(p * 100) / 100).toString().replace('.', ','); }
        function cantTxt(n) { return (Math.round(n * 100) / 100).toString().replace('.', ','); }

        // ---- cálculo de una fila. origen: 'pct' | 'desc' | 'total'
        function calcularFila(fila, origen) {
            var cant = parse(fila.find('.campo-cantidad').val());
            var precio = parse(fila.find('.campo-precio').val());
            var subC = Math.round(Math.round(cant * 100) * Math.round(precio * 100) / 100);
            var descC;
            if (origen === 'desc') {
                descC = Math.round(parse(fila.find('.campo-desc').val()) * 100);
            } else if (origen === 'total') {
                descC = subC - Math.round(parse(fila.find('.campo-total').val()) * 100);
            } else {
                var p = Math.min(100, Math.max(0, parse(fila.find('.campo-pct').val())));
                descC = Math.round(subC * p / 100);
            }
            descC = Math.max(0, Math.min(descC, subC));
            var totC = subC - descC;
            var pct = subC > 0 ? descC * 100 / subC : 0;

            fila.data('sub', subC).data('desc', descC).data('tot', totC);
            fila.find('.campo-subtotal').text(bs(subC));
            if (origen !== 'pct') fila.find('.campo-pct').val(pctTxt(pct));
            if (origen !== 'desc') fila.find('.campo-desc').val(bs(descC));
            if (origen !== 'total') fila.find('.campo-total').val(bs(totC));
            calcularTotales();
        }

        function calcularTotales() {
            if (!$('#tabla-paquete').length) return;   // presupuesto solo para ver
            var sub = 0, desc = 0, tot = 0;
            $('.fila-linea').each(function () {
                var f = $(this);
                if (f.closest('template').length) return;
                if (f.hasClass('fila-paquete') && !$('#sel-paquete').val()) return;
                if (f.hasClass('fila-extra') && f.find('.sel-item').length && !f.find('.sel-item').val()) return;
                sub += f.data('sub') || 0; desc += f.data('desc') || 0; tot += f.data('tot') || 0;
            });
            var pagadoC = Math.round(D.pagado * 100);
            $('#tot-subtotal').text('Bs ' + bs(sub));
            $('#tot-descuento').text('− Bs ' + bs(desc));
            $('#tot-total').text('Bs ' + bs(tot));
            $('#tot-saldo').text('Bs ' + bs(tot - pagadoC));
            $('#res-total').text('Bs ' + bs(tot));
            $('#res-saldo').text('Bs ' + bs(tot - pagadoC));
        }

        // ---- eventos de edición (delegados: sirven también para filas nuevas)
        var tabla = $('#tabla-paquete, #tabla-extras');
        tabla.on('input', '.campo-cantidad, .campo-precio, .campo-pct', function () { calcularFila($(this).closest('tr'), 'pct'); });
        tabla.on('input', '.campo-desc', function () { calcularFila($(this).closest('tr'), 'desc'); });
        tabla.on('input', '.campo-total', function () { calcularFila($(this).closest('tr'), 'total'); });
        // Al salir del campo, se reescribe con formato prolijo
        tabla.on('blur', '.campo-precio, .campo-desc, .campo-total', function () {
            var c = Math.round(parse($(this).val()) * 100);
            $(this).val(bs(c));
        });
        tabla.on('blur', '.campo-cantidad', function () { $(this).val(cantTxt(parse($(this).val()))); });

        // ---- paquete
        function mostrarPaquete(id, cargarValores) {
            var p = D.paquetes[id];
            var fila = $('.fila-paquete');
            if (!p) {
                $('#detalle-paquete').hide();
                $('#res-paquete').text('Sin paquete'); $('#res-invitados').text('');
                fila.data('sub', 0).data('desc', 0).data('tot', 0);
                calcularTotales();
                return;
            }
            if (cargarValores) {
                fila.find('.campo-cantidad').val(p.invitados ? p.invitados : 1);
                fila.find('.campo-precio').val(bs(Math.round(parse(p.precio) * 100)));
                fila.find('.campo-pct').val('0');
            }
            var info = [];
            if (p.invitados) info.push('Base: ' + p.invitados + ' invitados');
            if (p.horas) info.push(p.horas + ' horas');
            $('#detalle-paquete-info').text(info.join(' · '));
            if (cargarValores) $('#detalle-paquete-html').html(p.detalle);
            $('#detalle-paquete').toggle(!!(info.length || p.detalle));
            $('#res-paquete').text(p.nombre.replace(' (inactivo)', ''));
            $('#res-invitados').text(cantTxt(parse(fila.find('.campo-cantidad').val())) + ' invitados');
            // Al elegir otro paquete se parte del %; al abrir la página se respeta el monto guardado
            calcularFila(fila, cargarValores ? 'pct' : 'desc');
        }
        $('#sel-paquete').on('change', function () { mostrarPaquete($(this).val(), true); });
        $('.fila-paquete').on('input', '.campo-cantidad', function () {
            var n = parse($(this).val());
            $('#res-invitados').text(n ? cantTxt(n) + ' invitados' : '');
        });

        // ---- ítems / proveedores
        function filaVacia() { $('#tabla-extras .fila-vacia').toggle($('#tabla-extras .fila-extra').length === 0); }

        $('#btn-agregar-linea').on('click', function () {
            var html = $('#plantilla-linea').html().replace(/__i__/g, D.siguienteIndice++);
            var fila = $(html);
            $('#tabla-extras tbody .fila-vacia').before(fila);
            filtrarPorTipo(fila);
            filaVacia();
            fila.find('.sel-item').focus();
        });

        $('#tabla-extras').on('change', '.sel-item', function () {
            var fila = $(this).closest('tr');
            var it = D.items[$(this).val()];
            if (!it) { fila.data('sub', 0).data('desc', 0).data('tot', 0); calcularTotales(); return; }
            fila.attr('data-seccion', it.tipo);
            fila.find('.campo-cantidad').val('1');
            fila.find('.unidad-linea').text(it.unidad.toLowerCase());
            fila.find('.campo-precio').val(bs(Math.round(parse(it.precio) * 100)));
            fila.find('.campo-pct').val('0');
            fila.find('.detalle-linea').text(it.detalle.length > 160 ? it.detalle.substring(0, 160) + '…' : it.detalle);
            calcularFila(fila, 'pct');
        });

        $('#tabla-extras').on('click', '.btn-quitar-linea', function () {
            var fila = $(this).closest('tr');
            var nombre = fila.find('b').first().text() || fila.find('.sel-item option:selected').text().trim();
            if (nombre && fila.find('.sel-item').length === 0 && !confirm('¿Quitar "' + nombre + '" del presupuesto?')) return;
            fila.remove();
            filaVacia();
            calcularTotales();
        });

        // ---- filtrar paquetes e ítems por tipo de evento
        function filtrarPorTipo(contexto) {
            // En "Modificar presupuesto" no está el selector: se usa el tipo de la reserva
            var tipo = $('#reservas-tipoevento').length ? $('#reservas-tipoevento').val() : D.tipoEvento;
            (contexto || $(document)).find('#sel-paquete option, .sel-item option').each(function () {
                var t = $(this).data('tipo-evento');
                if (!t) return;
                var visible = (t === 'todos' || t === tipo || this.selected);
                $(this).prop('hidden', !visible).prop('disabled', !visible);
            });
        }
        $('#reservas-tipoevento').on('change', function () { filtrarPorTipo(); });

        // ---- calendarios de sucursales
        function claveFecha(d) {
            var m = ('0' + (d.getMonth() + 1)).slice(-2), dia = ('0' + d.getDate()).slice(-2);
            return d.getFullYear() + '-' + m + '-' + dia;
        }
        function revisarFecha() {
            if (!$('#reservas-fecha_reserva').length) return;   // pantalla sin datos del cliente
            var f = $('#reservas-fecha_reserva').val(), s = $('#reservas-sucursal').val();
            var ocupada = f && D.ocupadas[s] && D.ocupadas[s].indexOf(f) !== -1;
            $('#aviso-fecha').toggle(!!ocupada)
                .html('<i class="fa fa-warning"></i> <b>Atención:</b> ' + s + ' ya tiene una reserva el ' + f.split('-').reverse().join('/') + '.');
            $('#res-fecha').text(f ? f.split('-').reverse().join('/') : 'Sin fecha');
            $('#res-sucursal').text(s || '');
        }
        $('.calendario-sucursal').each(function () {
            var cal = $(this), suc = cal.data('sucursal');
            var plugin = $.fn.kvDatepicker ? 'kvDatepicker' : 'datepicker';
            cal[plugin]({
                language: 'es', format: 'yyyy-mm-dd', startDate: new Date(), todayHighlight: true,
                beforeShowDay: function (d) {
                    if ((D.ocupadas[suc] || []).indexOf(claveFecha(d)) !== -1) {
                        return {classes: 'ocupado', tooltip: 'Reservado'};
                    }
                }
            }).on('changeDate', function (e) {
                if (!e.date) return;
                $('#reservas-fecha_reserva').val(claveFecha(e.date)).trigger('change');
                $('#reservas-sucursal').val(suc).trigger('change');
            });
        });
        $('#reservas-fecha_reserva, #reservas-sucursal').on('change', revisarFecha);

        // ---- al cargar la página
        filtrarPorTipo();
        $('.fila-linea').each(function () {
            var f = $(this);
            if (f.closest('template').length) return;
            calcularFila(f, 'desc');
        });
        if ($('#sel-paquete').val()) { mostrarPaquete($('#sel-paquete').val(), false); }
        revisarFecha();
        filaVacia();

        // Evitar enviar con Enter en los campos numéricos (envíos accidentales)
        tabla.on('keydown', 'input', function (e) { if (e.key === 'Enter') { e.preventDefault(); } });
    };
})(jQuery);
