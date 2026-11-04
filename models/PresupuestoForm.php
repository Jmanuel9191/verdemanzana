<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\web\UploadedFile;

/**
 * Paso 1 de la reserva nueva: "Presupuesto".
 *
 * Junta en un solo formulario:
 *  - datos básicos del cliente y del evento  → tabla reservas1 (columnas que YA existen)
 *  - el paquete (1 línea) y los ítems/proveedores (N líneas) → reserva_cotizacion(_detalle)
 *  - el archivo de presupuesto (opcional)     → imagen/usuarios/, como hoy
 *
 * Todo se guarda en una transacción: si algo falla, no queda nada a medias.
 */
class PresupuestoForm extends Model
{
    /** Columnas de reservas1 que se cargan en este paso (ninguna otra se toca). */
    public const CAMPOS_RESERVA = [
        'nombre', 'telefono', 'email', 'fecha_reserva', 'sucursal',
        'hora_inicio', 'tipoevento', 'tipo_evento',
    ];

    public const SUCURSALES = [
        'Doble via a la guardia' => 'Doble via a la guardia',
        'Garden'                 => 'Garden',
        'Parque Urbano'          => 'Parque Urbano',
    ];

    public const TIPOS_EVENTO = [
        'matrimonio' => 'Matrimonio',
        'quinceanos' => 'Quince años',
        'otros'      => 'Otros',
    ];

    public const ARCHIVO_EXTENSIONES = ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'jpg', 'jpeg', 'png', 'webp'];

    /** @var Reservas */
    public $reserva;

    /** @var ReservaCotizacion */
    public $cotizacion;

    /** @var ReservaCotizacionDetalle|null */
    public $paquete;

    /** @var ReservaCotizacionDetalle[] */
    public $extras = [];

    /** @var string|null */
    public $observaciones;

    /** @var UploadedFile|null archivo de presupuesto (opcional) */
    public $archivo;

    /** @var ReservaCotizacionDetalle[] líneas que estaban guardadas y se quitaron */
    private $extrasQuitados = [];

    /**
     * Qué parte del formulario se usa:
     *  - MODO_CREAR:       reserva nueva, todo junto (cliente + presupuesto)
     *  - MODO_DATOS:       editar reserva existente: solo cliente/evento y archivo (el presupuesto no se toca)
     *  - MODO_PRESUPUESTO: pantalla aparte "Modificar presupuesto": solo paquete, ítems y observaciones
     */
    public const MODO_CREAR = 'crear';
    public const MODO_DATOS = 'datos';
    public const MODO_PRESUPUESTO = 'presupuesto';

    /** @var string */
    public $modo = self::MODO_CREAR;

    /** Largo mínimo de la contraseña del cliente */
    public const CONTRASENA_MIN = 6;

    /** @var string|null contraseña del cliente (solo al crear; se guarda en MD5) */
    public $contrasena;

    /** @var string|null motivo del cambio (opcional, solo en MODO_PRESUPUESTO) */
    public $motivo;

    /** @var array foto de las líneas antes de modificar (para el historial) */
    private $fotoAntes = [];

    /** @var string|null observaciones antes de modificar */
    private $observacionesAntes;

    /** @var string|null total antes de modificar */
    private $totalAntes;

    /** @var array cambios detectados al guardar (para el mensaje de la pantalla) */
    public $cambios = [];

    // ---------------------------------------------------------------- creación

    /** Formulario vacío para una reserva nueva. */
    public static function nueva(): self
    {
        $form = new self();
        $form->reserva = new Reservas();
        $form->reserva->estado = 1;
        $form->reserva->tipoevento = 'matrimonio';
        $form->reserva->sucursal = array_key_first(self::SUCURSALES);
        $form->cotizacion = new ReservaCotizacion();
        return $form;
    }

    /** Formulario con los datos de una reserva que ya tiene presupuesto. */
    public static function deReserva(Reservas $reserva, ReservaCotizacion $cotizacion): self
    {
        $form = new self();
        $form->reserva = $reserva;
        $form->cotizacion = $cotizacion;
        $form->observaciones = $cotizacion->observaciones;
        $form->paquete = $cotizacion->lineaPaquete;
        $form->extras = $cotizacion->lineasExtras;
        $form->modo = self::MODO_DATOS;

        // Foto de cómo está el presupuesto ahora, para comparar al guardar
        $form->fotoAntes = ReservaCotizacionHistorial::foto($form->todasLasLineas());
        $form->observacionesAntes = $cotizacion->observaciones;
        $form->totalAntes = $cotizacion->total;
        return $form;
    }

    public function attributeLabels()
    {
        return [
            'observaciones' => 'Observaciones del presupuesto',
            'archivo'       => 'Presupuesto archivo',
            'motivo'        => 'Motivo del cambio',
            'contrasena'    => 'Contraseña del cliente',
        ];
    }

    // ---------------------------------------------------------------- carga del POST

    /**
     * Carga los datos enviados por el formulario.
     *
     * Formato esperado:
     *   Reservas[...]                      datos básicos (solo CAMPOS_RESERVA)
     *   Paquete[idorigen|cantidad|precio_unitario|descuento_porcentaje|descuento_monto|total]
     *   Lineas[n][iddetalle|idorigen|cantidad|precio_unitario|descuento_porcentaje|descuento_monto|total]
     *   PresupuestoForm[observaciones]
     */
    public function load($data, $formName = null)
    {
        $cargaReserva = $this->modo !== self::MODO_PRESUPUESTO;
        $cargaPresupuesto = $this->modo !== self::MODO_DATOS;

        if ($cargaReserva && !isset($data['Reservas'])) {
            return false;
        }
        if (!$cargaReserva && !isset($data['Paquete'])) {
            return false;
        }

        // 1) Reserva: solo las columnas de este paso (nada de contraseña, montos, etc.)
        if ($cargaReserva) {
            $datosReserva = array_intersect_key((array)$data['Reservas'], array_flip(self::CAMPOS_RESERVA));
            $this->reserva->setAttributes(array_map(function ($v) {
                return is_string($v) ? trim($v) : $v;
            }, $datosReserva), false);
            $this->archivo = UploadedFile::getInstanceByName('archivo_presupuesto');
            if ($this->modo === self::MODO_CREAR) {
                $this->contrasena = isset($data['PresupuestoForm']['contrasena'])
                    ? trim((string)$data['PresupuestoForm']['contrasena'])
                    : null;
            }
        }

        if (!$cargaPresupuesto) {
            return true;   // al editar datos, el presupuesto queda como está
        }

        $this->motivo = isset($data['PresupuestoForm']['motivo'])
            ? trim((string)$data['PresupuestoForm']['motivo'])
            : null;

        $this->observaciones = isset($data['PresupuestoForm']['observaciones'])
            ? trim((string)$data['PresupuestoForm']['observaciones'])
            : $this->observaciones;

        // 2) Paquete
        $this->paquete = $this->cargarPaquete((array)($data['Paquete'] ?? []));

        // 3) Ítems / proveedores
        $this->cargarExtras((array)($data['Lineas'] ?? []));

        return true;
    }

    private function cargarPaquete(array $datos): ?ReservaCotizacionDetalle
    {
        $idPaquete = (int)($datos['idorigen'] ?? 0);
        if ($idPaquete <= 0) {
            return null;
        }

        $actual = $this->paquete;
        if ($actual !== null && !$actual->isNewRecord && (int)$actual->idorigen === $idPaquete) {
            // Mismo paquete que ya estaba guardado: se conserva la copia (nombre/detalle) y se actualizan los números
            $linea = $actual;
        } else {
            $paquete = Paquetes::findOne($idPaquete);
            if ($paquete === null) {
                return null;
            }
            $linea = ReservaCotizacionDetalle::desdePaquete($paquete);
            if ($actual !== null && !$actual->isNewRecord) {
                // Cambiaron de paquete: se reutiliza la fila para no perder el id
                $actual->setAttributes($linea->getAttributes(null, ['iddetalle', 'idcotizacion']), false);
                $linea = $actual;
            }
        }

        $this->asignarNumeros($linea, $datos);
        return $linea;
    }

    private function cargarExtras(array $filas): void
    {
        $guardados = [];
        foreach ($this->extras as $linea) {
            if (!$linea->isNewRecord) {
                $guardados[(int)$linea->iddetalle] = $linea;
            }
        }

        $nuevas = [];
        foreach ($filas as $fila) {
            if (!is_array($fila)) {
                continue;
            }
            $iddetalle = (int)($fila['iddetalle'] ?? 0);

            if ($iddetalle > 0 && isset($guardados[$iddetalle])) {
                // Línea que ya existía: se mantiene su copia y se actualizan los números
                $linea = $guardados[$iddetalle];
                unset($guardados[$iddetalle]);
            } else {
                $item = Items::findOne((int)($fila['idorigen'] ?? 0));
                if ($item === null) {
                    continue; // fila vacía o ítem inexistente: se ignora
                }
                $linea = ReservaCotizacionDetalle::desdeItem($item);
            }

            $this->asignarNumeros($linea, $fila);
            $nuevas[] = $linea;
        }

        $this->extras = $nuevas;
        $this->extrasQuitados = array_values($guardados);
    }

    /** Copia los números editables de la pantalla a la línea. */
    private function asignarNumeros(ReservaCotizacionDetalle $linea, array $datos): void
    {
        foreach (['cantidad', 'precio_unitario', 'descuento_porcentaje', 'descuento_monto', 'total'] as $campo) {
            if (array_key_exists($campo, $datos) && $datos[$campo] !== '') {
                $linea->$campo = (string)$datos[$campo];
            }
        }
    }

    // ---------------------------------------------------------------- validación

    public function validate($attributeNames = null, $clearErrors = true)
    {
        if ($clearErrors) {
            $this->clearErrors();
            $this->reserva->clearErrors();
        }

        $r = $this->reserva;

        if ($this->modo !== self::MODO_PRESUPUESTO) {
            $this->validarReserva($r);
        }
        if ($this->modo === self::MODO_CREAR) {
            $error = self::errorContrasena($this->contrasena);
            if ($error !== null) {
                $this->addError('contrasena', $error);
            }
        }
        if ($this->modo !== self::MODO_DATOS) {
            $this->validarPresupuesto();
        }
        $this->validarArchivo();

        return !$this->hasErrors() && !$r->hasErrors();
    }

    private function validarReserva(Reservas $r): void
    {
        // Datos de la reserva (reglas del modelo Reservas para estos campos)
        $r->validate(self::CAMPOS_RESERVA, false);

        foreach (['fecha_reserva' => 'Fecha de reserva', 'sucursal' => 'Sucursal', 'tipoevento' => 'Tipo de evento'] as $campo => $etiqueta) {
            if ($r->$campo === null || $r->$campo === '') {
                $r->addError($campo, $etiqueta . ' no puede estar vacío.');
            }
        }
        if ($r->fecha_reserva && !preg_match('/^\d{4}-\d{2}-\d{2}$/', (string)$r->fecha_reserva)) {
            $r->addError('fecha_reserva', 'Fecha inválida.');
        }
        if ($r->sucursal && !isset(self::SUCURSALES[$r->sucursal])) {
            $r->addError('sucursal', 'Sucursal inválida.');
        }
        if ($r->tipoevento && !isset(self::TIPOS_EVENTO[$r->tipoevento])) {
            $r->addError('tipoevento', 'Tipo de evento inválido.');
        }
        if ($r->email) {
            // Es el usuario de login del cliente: puede ser un email o un nombre de usuario
            // (no se exige "@"). Solo se pide que no tenga espacios y que no se repita.
            if (preg_match('/\s/u', (string)$r->email)) {
                $r->addError('email', 'El usuario no puede tener espacios.');
            } elseif (mb_strlen((string)$r->email) > 250) {
                $r->addError('email', 'El usuario puede tener hasta 250 caracteres.');
            } else {
                $existe = Reservas::find()
                    ->where(['email' => $r->email])
                    ->andFilterWhere(['<>', 'idreservas', $r->idreservas])
                    ->exists();
                if ($existe) {
                    $r->addError('email', 'Ya existe otra reserva con este usuario/email (es el usuario de login del cliente).');
                }
            }
        }

    }

    private function validarPresupuesto(): void
    {
        // Paquete obligatorio
        if ($this->paquete === null) {
            $this->addError('paquete', 'Elegí un paquete.');
        } elseif (!$this->paquete->validate()) {
            $this->addError('paquete', 'Paquete: ' . implode(' ', $this->paquete->getFirstErrors()));
        }

        // Líneas extra
        foreach ($this->extras as $i => $linea) {
            if (!$linea->validate()) {
                $this->addError('extras', 'Línea ' . ($i + 1) . ' (' . $linea->nombre . '): ' . implode(' ', $linea->getFirstErrors()));
            }
        }

        // No se puede bajar el total por debajo de lo ya pagado
        if (!$this->hasErrors() && !$this->cotizacion->isNewRecord) {
            $totalC = ReservaCotizacionDetalle::aCentavos($this->calcularTotal());
            $pagadoC = ReservaCotizacionDetalle::aCentavos($this->cotizacion->getTotalPagado());
            if ($totalC < $pagadoC) {
                $this->addError('extras', 'El total del presupuesto no puede ser menor a lo ya pagado ('
                    . \app\helpers\FormatoHelper::formatoBs(ReservaCotizacionDetalle::deCentavos($pagadoC)) . ').');
            }
        }

        if ($this->motivo !== null && mb_strlen($this->motivo) > 500) {
            $this->addError('motivo', 'El motivo puede tener hasta 500 caracteres.');
        }
    }

    private function validarArchivo(): void
    {
        // Archivo de presupuesto (opcional)
        if ($this->archivo !== null) {
            $ext = strtolower($this->archivo->extension);
            if (!in_array($ext, self::ARCHIVO_EXTENSIONES, true)) {
                $this->addError('archivo', 'El archivo tiene que ser PDF, Word, Excel o imagen.');
            } elseif ($this->archivo->size > 20 * 1024 * 1024) {
                $this->addError('archivo', 'El archivo no puede pesar más de 20 MB.');
            } elseif ($this->archivo->error !== UPLOAD_ERR_OK) {
                $this->addError('archivo', 'No se pudo subir el archivo.');
            }
        }
    }

    /** Total con las líneas actuales (ya calculadas por validate()). */
    private function calcularTotal(): string
    {
        $tmp = new ReservaCotizacion();
        $tmp->recalcularTotales($this->todasLasLineas());
        return $tmp->total;
    }

    /** @return ReservaCotizacionDetalle[] */
    public function todasLasLineas(): array
    {
        return array_merge($this->paquete ? [$this->paquete] : [], $this->extras);
    }

    // ---------------------------------------------------------------- guardar

    public function guardar(): bool
    {
        if (!$this->validate()) {
            return false;
        }

        $r = $this->reserva;
        $esNueva = $r->isNewRecord;
        $guardaReserva = $this->modo !== self::MODO_PRESUPUESTO;
        $guardaPresupuesto = $this->modo !== self::MODO_DATOS;

        // ¿Qué cambió en el presupuesto? (si no cambió nada, no se toca la base)
        if ($guardaPresupuesto && !$esNueva) {
            $this->cambios = ReservaCotizacionHistorial::comparar(
                $this->fotoAntes,
                ReservaCotizacionHistorial::foto($this->todasLasLineas()),
                $this->observacionesAntes,
                $this->observaciones !== '' ? $this->observaciones : null
            );
            if ($this->modo === self::MODO_PRESUPUESTO && empty($this->cambios)) {
                return true;
            }
        }

        $archivoGuardado = null;
        $anterior = null;

        $transaccion = Yii::$app->db->beginTransaction();
        try {
            // 1) Reserva (reservas1)
            if ($guardaReserva) {
                if ($esNueva) {
                    $r->fecha_registro = date('Y-m-d H:i:s');
                    $r->estado = 1;
                    // Contraseña del cliente en MD5 (como el resto del sistema; bcrypt más adelante)
                    $r->contrasena = md5((string)$this->contrasena);
                    // reservas1 tiene columnas NOT NULL sin valor por defecto (ej. "presupuesto",
                    // que es LONGTEXT). MySQL estricto rechaza el INSERT si no van: se completan vacías.
                    // Después sincronizarReserva() llena "presupuesto" con el total real.
                    $this->completarColumnasObligatorias($r);
                }

                if ($this->archivo !== null) {
                    $nombre = Yii::$app->security->generateRandomString() . '_presupuesto.' . strtolower($this->archivo->extension);
                    $ruta = Yii::getAlias('@webroot') . '/imagen/usuarios/' . $nombre;
                    if (!$this->archivo->saveAs($ruta)) {
                        throw new \RuntimeException('No se pudo guardar el archivo de presupuesto.');
                    }
                    $archivoGuardado = $ruta;
                    $anterior = $r->presupuestofile;
                    $r->presupuestofile = $nombre;
                }

                if (!$r->save(false)) {
                    throw new \RuntimeException('No se pudo guardar la reserva.');
                }
            }

            if ($guardaPresupuesto) {
                // 2) Cotización
                $c = $this->cotizacion;
                $c->idreservas = $r->idreservas;
                $c->idpaquete = $this->paquete->idorigen;
                $c->observaciones = $this->observaciones !== '' ? $this->observaciones : null;
                $c->recalcularTotales($this->todasLasLineas());
                if (!$c->save(false)) {
                    throw new \RuntimeException('No se pudo guardar el presupuesto.');
                }

                // 3) Líneas
                foreach ($this->extrasQuitados as $linea) {
                    $linea->delete();
                }
                $orden = 0;
                foreach ($this->todasLasLineas() as $linea) {
                    $linea->idcotizacion = $c->idcotizacion;
                    $linea->orden = $orden++;
                    if (!$linea->save(false)) {
                        throw new \RuntimeException('No se pudo guardar la línea ' . $linea->nombre . '.');
                    }
                }

                // 4) Historial: quién, cuándo, qué cambió, total anterior → nuevo
                if ($esNueva) {
                    $this->cambios = ReservaCotizacionHistorial::comparar([], ReservaCotizacionHistorial::foto($this->todasLasLineas()));
                    ReservaCotizacionHistorial::registrar($c, ReservaCotizacionHistorial::ACCION_CREADO, null, $this->cambios, null);
                } elseif (!empty($this->cambios)) {
                    ReservaCotizacionHistorial::registrar($c, ReservaCotizacionHistorial::ACCION_MODIFICADO,
                        $this->totalAntes, $this->cambios, $this->motivo);
                }

                // 5) Copiar totales, paquete y límite de invitados a reservas1
                $c->sincronizarReserva();
            }

            $transaccion->commit();
        } catch (\Throwable $e) {
            $transaccion->rollBack();
            if ($archivoGuardado !== null) {
                @unlink($archivoGuardado);
            }
            if ($esNueva) {
                $r->setIsNewRecord(true);
                $r->idreservas = null;
            }
            Yii::error($e->getMessage() . "\n" . $e->getTraceAsString(), __METHOD__);
            $this->addError('reserva', 'No se pudo guardar: ' . $e->getMessage());
            return false;
        }

        // Archivo anterior reemplazado: se borra recién cuando todo salió bien
        if (!empty($anterior) && $archivoGuardado !== null) {
            $viejo = Yii::getAlias('@webroot') . '/imagen/usuarios/' . basename($anterior);
            if (is_file($viejo)) {
                @unlink($viejo);
            }
        }

        return true;
    }

    /**
     * Revisa una contraseña nueva del cliente. Devuelve el mensaje de error o null si está bien.
     * (Se usa al crear la reserva y en "Cambiar contraseña".)
     */
    public static function errorContrasena(?string $contrasena): ?string
    {
        $contrasena = trim((string)$contrasena);
        if ($contrasena === '') {
            return 'Escribí la contraseña del cliente.';
        }
        if (mb_strlen($contrasena) < self::CONTRASENA_MIN) {
            return 'La contraseña tiene que tener al menos ' . self::CONTRASENA_MIN . ' caracteres.';
        }
        if (mb_strlen($contrasena) > 100) {
            return 'La contraseña puede tener hasta 100 caracteres.';
        }
        return null;
    }

    /**
     * Pone un valor vacío ('' o 0) en cada columna de reservas1 que sea NOT NULL,
     * no tenga valor por defecto y haya quedado sin dato. Se lee de la estructura
     * real de la tabla, así funciona igual en Laragon (estricto) y en cPanel.
     */
    private function completarColumnasObligatorias(Reservas $r): void
    {
        $esquema = Reservas::getTableSchema();
        foreach ($esquema->columns as $nombre => $col) {
            if ($col->isPrimaryKey || $col->autoIncrement || $col->allowNull || $col->defaultValue !== null) {
                continue;
            }
            if ($r->$nombre !== null && $r->$nombre !== '') {
                continue;
            }
            switch ($col->phpType) {
                case 'integer':
                case 'double':
                    $r->$nombre = 0;
                    break;
                default:
                    if (in_array($col->type, ['date', 'datetime', 'timestamp', 'time'], true)) {
                        continue 2; // las fechas obligatorias (fecha_reserva, fecha_registro) ya vienen cargadas
                    }
                    $r->$nombre = '';
            }
        }
    }

    // ---------------------------------------------------------------- datos para la vista

    /** Paquetes activos para el selector (con tipo de evento para filtrar en pantalla). */
    public static function catalogoPaquetes(): array
    {
        $datos = [];
        foreach (Paquetes::find()->where(['estado' => Paquetes::ESTADO_ACTIVO])
                     ->orderBy(['posicion' => SORT_ASC, 'nombre' => SORT_ASC])->all() as $p) {
            $datos[(int)$p->idpaquete] = [
                'nombre'     => $p->nombre,
                'detalle'    => (string)$p->detalle,
                'precio'     => (string)$p->precio,
                'invitados'  => $p->invitados_incluidos !== null ? (int)$p->invitados_incluidos : null,
                'horas'      => $p->horas_incluidas !== null ? (int)$p->horas_incluidas : null,
                'tipoEvento' => $p->tipo_evento,
            ];
        }
        return $datos;
    }

    /** Ítems y proveedores activos para el selector. */
    public static function catalogoItems(): array
    {
        $datos = [];
        foreach (Items::find()->where(['estado' => Items::ESTADO_ACTIVO])
                     ->orderBy(['tipo' => SORT_ASC, 'posicion' => SORT_ASC, 'nombre' => SORT_ASC])->all() as $i) {
            $datos[(int)$i->iditem] = [
                'tipo'       => $i->tipo,
                'nombre'     => $i->nombre,
                'empresa'    => (string)$i->empresa,
                'detalle'    => trim(html_entity_decode(strip_tags((string)$i->detalle), ENT_QUOTES, 'UTF-8')),
                'unidad'     => $i->getUnidadTexto(),
                'precio'     => (string)$i->precio_unitario,
                'tipoEvento' => $i->tipo_evento,
            ];
        }
        return $datos;
    }

    /**
     * Fechas ocupadas por sucursal (reservas activas desde hoy), para pintar los calendarios.
     * @return array ['Garden' => ['2026-11-14', ...], ...]
     */
    public function fechasOcupadas(): array
    {
        $filas = Reservas::find()
            ->select(['idreservas', 'sucursal', 'fecha_reserva'])
            ->where(['estado' => 1])
            ->andWhere(['>=', 'fecha_reserva', date('Y-m-d', strtotime('-1 month'))])
            ->andFilterWhere(['<>', 'idreservas', $this->reserva->idreservas])
            ->asArray()
            ->all();

        $ocupadas = array_fill_keys(array_keys(self::SUCURSALES), []);
        foreach ($filas as $f) {
            if (isset($ocupadas[$f['sucursal']]) && $f['fecha_reserva']) {
                $ocupadas[$f['sucursal']][] = substr($f['fecha_reserva'], 0, 10);
            }
        }
        foreach ($ocupadas as $k => $fechas) {
            $ocupadas[$k] = array_values(array_unique($fechas));
        }
        return $ocupadas;
    }
}