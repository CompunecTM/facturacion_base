<?php
/*
 * This file is part of facturacion_base
 * Copyright (C) 2013-2017  Carlos Garcia Gomez  neorazorx@gmail.com
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Lesser General Public License for more details.
 * 
 * You should have received a copy of the GNU Lesser General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */
namespace FacturaScripts\model;

/**
 * Factura de un cliente.
 * 
 * @author Carlos García Gómez <neorazorx@gmail.com>
 */
class factura_cliente extends \fs_model
{

    /**
     * Clave primaria.
     * @var integer 
     */
    public $idfactura;

    /**
     * ID del asiento relacionado, si lo hay.
     * @var integer 
     */
    public $idasiento;

    /**
     * ID del asiento de pago relacionado, si lo hay.
     * @var integer 
     */
    public $idasientop;

    /**
     * ID de la factura que rectifica.
     * @var integer 
     */
    public $idfacturarect;

    /**
     * Código único de la factura. Para humanos.
     * @var string 
     */
    public $codigo;

    /**
     * Número de la factura.
     * Único dentro de la serie+ejercicio.
     * @var string 
     */
    public $numero;

    /**
     * Número opcional a disposición del usuario.
     * @var string 
     */
    public $numero2;

    /**
     * Código de la factura que rectifica.
     * @var string 
     */
    public $codigorect;

    /**
     * Ejercicio relacionado. El que corresponde a la fecha.
     * @var string 
     */
    public $codejercicio;

    /**
     * Serie relacionada.
     * @var string 
     */
    public $codserie;

    /**
     * Almacén del que sale la mercancía.
     * @var string 
     */
    public $codalmacen;

    /**
     * Forma de pago.
     * @var string 
     */
    public $codpago;

    /**
     * Divisa de la factura.
     * @var string 
     */
    public $coddivisa;
    public $fecha;
    public $hora;

    /**
     * Código identificador del cliente de la factura.
     * @var string 
     */
    public $codcliente;
    public $nombrecliente;
    public $cifnif;
    public $direccion;
    public $ciudad;
    public $provincia;
    public $apartado;
    /// datos de transporte
    public $envio_codtrans;
    public $envio_codigo;
    public $envio_nombre;
    public $envio_apellidos;
    public $envio_apartado;
    public $envio_direccion;
    public $envio_codpostal;
    public $envio_ciudad;
    public $envio_provincia;
    public $envio_codpais;

    /**
     * ID de la dirección en dirclientes.
     * Modelo direccion_cliente.
     * @var string 
     */
    public $coddir;
    public $codpostal;
    public $codpais;

    /**
     * Empleado que ha creado la factura.
     * Modelo agente.
     * @var string 
     */
    public $codagente;

    /**
     * Suma de los pvptotal de las líneas.
     * Es el total antes de impuestos.
     * @var double 
     */
    public $neto;

    /**
     * Suma total del IVA de las líneas.
     * @var double 
     */
    public $totaliva;

    /**
     * Suma total de la factura, con impuestos.
     * @var double 
     */
    public $total;

    /**
     * Total expresado en euros, por si no fuese la divisa de la factura.
     * totaleuros = total/tasaconv
     * No hace falta rellenarlo, al hacer save() se calcula el valor.
     * @var double 
     */
    public $totaleuros;

    /**
     * % de retención IRPF de la factura.
     * Puede variar en cada línea.
     * @var double 
     */
    public $irpf;

    /**
     * Suma total de retenciones IRPF de las líneas.
     * @var double 
     */
    public $totalirpf;

    /**
     * % comisión del empleado (agente).
     * @var double 
     */
    public $porcomision;

    /**
     * Tasa de conversión a Euros de la divisa de la factura.
     * @var double 
     */
    public $tasaconv;

    /**
     * Suma del recargo de equivalencia de las líneas.
     * @var double 
     */
    public $totalrecargo;
    public $observaciones;
    public $pagada;
    public $anulada;

    /**
     * Fecha de vencimiento de la factura.
     * @var string 
     */
    public $vencimiento;

    /**
     * Fecha en la que se envió la factura por email.
     * @var string 
     */
    public $femail;

    /**
     * Identificador opcional para la impresión. Todavía sin uso.
     * Se puede usar para identificar una forma de impresión y usar siempre
     * esa en esta factura.
     * @var integer 
     */
    public $idimprenta;

    /**
     * Número de documentos adjuntos.
     * @var integer 
     */
    public $numdocs;

    public function __construct($data = FALSE)
    {
        parent::__construct('facturascli');
        if ($data) {
            $this->idfactura = $this->intval($data['idfactura']);
            $this->idasiento = $this->intval($data['idasiento']);
            $this->idasientop = $this->intval($data['idasientop']);
            $this->idfacturarect = $this->intval($data['idfacturarect']);
            $this->codigo = $data['codigo'];
            $this->numero = $data['numero'];
            $this->numero2 = $data['numero2'];
            $this->codigorect = $data['codigorect'];
            $this->codejercicio = $data['codejercicio'];
            $this->codserie = $data['codserie'];
            $this->codalmacen = $data['codalmacen'];
            $this->codpago = $data['codpago'];
            $this->coddivisa = $data['coddivisa'];
            $this->fecha = Date('d-m-Y', strtotime($data['fecha']));

            $this->hora = '00:00:00';
            if (!is_null($data['hora'])) {
                $this->hora = date('H:i:s', strtotime($data['hora']));
            }

            $this->codcliente = $data['codcliente'];
            $this->nombrecliente = $data['nombrecliente'];
            $this->cifnif = $data['cifnif'];
            $this->direccion = $data['direccion'];
            $this->ciudad = $data['ciudad'];
            $this->provincia = $data['provincia'];
            $this->apartado = $data['apartado'];
            $this->coddir = $data['coddir'];
            $this->codpostal = $data['codpostal'];
            $this->codpais = $data['codpais'];
            $this->codagente = $data['codagente'];
            $this->neto = floatval($data['neto']);
            $this->totaliva = floatval($data['totaliva']);
            $this->total = floatval($data['total']);
            $this->totaleuros = floatval($data['totaleuros']);
            $this->irpf = floatval($data['irpf']);
            $this->totalirpf = floatval($data['totalirpf']);
            $this->porcomision = floatval($data['porcomision']);
            $this->tasaconv = floatval($data['tasaconv']);
            $this->totalrecargo = floatval($data['totalrecargo']);
            $this->observaciones = $this->no_html($data['observaciones']);
            $this->pagada = $this->str2bool($data['pagada']);
            $this->anulada = $this->str2bool($data['anulada']);

            $this->vencimiento = Date('d-m-Y', strtotime($data['fecha'] . ' +1 day'));
            if (!is_null($data['vencimiento'])) {
                $this->vencimiento = Date('d-m-Y', strtotime($data['vencimiento']));
            }

            $this->femail = NULL;
            if (!is_null($data['femail'])) {
                $this->femail = Date('d-m-Y', strtotime($data['femail']));
            }

            $this->envio_codtrans = $data['codtrans'];
            $this->envio_codigo = $data['codigoenv'];
            $this->envio_nombre = $data['nombreenv'];
            $this->envio_apellidos = $data['apellidosenv'];
            $this->envio_apartado = $data['apartadoenv'];
            $this->envio_direccion = $data['direccionenv'];
            $this->envio_codpostal = $data['codpostalenv'];
            $this->envio_ciudad = $data['ciudadenv'];
            $this->envio_provincia = $data['provinciaenv'];
            $this->envio_codpais = $data['codpaisenv'];

            $this->idimprenta = $this->intval($data['idimprenta']);
            $this->numdocs = intval($data['numdocs']);
        } else {
            $this->idfactura = NULL;
            $this->idasiento = NULL;
            $this->idasientop = NULL;
            $this->idfacturarect = NULL;
            $this->codigo = NULL;
            $this->numero = NULL;
            $this->numero2 = NULL;
            $this->codigorect = NULL;
            $this->codejercicio = NULL;
            $this->codserie = $this->default_items->codserie();
            $this->codalmacen = $this->default_items->codalmacen();
            $this->codpago = $this->default_items->codpago();
            $this->coddivisa = NULL;
            $this->fecha = Date('d-m-Y');
            $this->hora = Date('H:i:s');
            $this->codcliente = NULL;
            $this->nombrecliente = '';
            $this->cifnif = '';
            $this->direccion = NULL;
            $this->provincia = NULL;
            $this->ciudad = NULL;
            $this->apartado = NULL;
            $this->coddir = NULL;
            $this->codpostal = NULL;
            $this->codpais = NULL;
            $this->codagente = NULL;
            $this->neto = 0.0;
            $this->totaliva = 0.0;
            $this->total = 0.0;
            $this->totaleuros = 0.0;
            $this->irpf = 0.0;
            $this->totalirpf = 0.0;
            $this->porcomision = 0.0;
            $this->tasaconv = 1.0;
            $this->totalrecargo = 0.0;
            $this->observaciones = NULL;
            $this->pagada = FALSE;
            $this->anulada = FALSE;
            $this->vencimiento = Date('d-m-Y', strtotime('+1 day'));
            $this->femail = NULL;

            $this->envio_codtrans = NULL;
            $this->envio_codigo = NULL;
            $this->envio_nombre = NULL;
            $this->envio_apellidos = NULL;
            $this->envio_apartado = NULL;
            $this->envio_direccion = NULL;
            $this->envio_codpostal = NULL;
            $this->envio_ciudad = NULL;
            $this->envio_provincia = NULL;
            $this->envio_codpais = NULL;

            $this->idimprenta = NULL;
            $this->numdocs = 0;
        }
    }

    protected function install()
    {
        new \serie();
        new \asiento();

        return '';
    }

    public function observaciones_resume()
    {
        if ($this->observaciones == '') {
            return '-';
        } else if (strlen($this->observaciones) < 60) {
            return $this->observaciones;
        }

        return substr($this->observaciones, 0, 50) . '...';
    }

    public function vencida()
    {
        if ($this->pagada) {
            return FALSE;
        }

        return ( strtotime($this->vencimiento) < strtotime(Date('d-m-Y')) );
    }

    /**
     * Establece la fecha y la hora, pero respetando la numeración, el ejercicio
     * y las regularizaciones de IVA.
     * Devuelve TRUE si se asigna una fecha distinta a los solicitados.
     * @param string $fecha
     * @param string $hora
     * @return boolean
     */
    public function set_fecha_hora($fecha, $hora)
    {
        $cambio = FALSE;

        if (is_null($this->numero)) { /// nueva factura
            /// buscamos la última fecha usada en una factura en esta serie y ejercicio
            $sql = "SELECT MAX(fecha) as fecha FROM " . $this->table_name
                . " WHERE codserie = " . $this->var2str($this->codserie)
                . " AND codejercicio = " . $this->var2str($this->codejercicio) . ";";

            $data = $this->db->select($sql);
            if ($data) {
                if (strtotime($data[0]['fecha']) > strtotime($fecha)) {
                    $fecha_old = $fecha;
                    $fecha = date('d-m-Y', strtotime($data[0]['fecha']));

                    $this->new_error_msg('Ya hay facturas posteriores a la fecha seleccionada (' . $fecha_old . ').'
                        . ' Nueva fecha asignada: ' . $fecha);
                    $cambio = TRUE;
                }
            }

            /// ahora buscamos la última hora usada para esa fecha, serie y ejercicio
            $sql = "SELECT MAX(hora) as hora FROM " . $this->table_name
                . " WHERE codserie = " . $this->var2str($this->codserie)
                . " AND codejercicio = " . $this->var2str($this->codejercicio)
                . " AND fecha = " . $this->var2str($fecha) . ";";

            $data = $this->db->select($sql);
            if ($data) {
                if (strtotime($data[0]['hora']) > strtotime($hora) || $cambio) {
                    $hora = date('H:i:s', strtotime($data[0]['hora']));
                    $cambio = TRUE;
                }
            }

            $this->fecha = $fecha;
            $this->hora = $hora;
        } else if ($fecha != $this->fecha) { /// factura existente y cambiamos fecha
            $cambio = TRUE;

            $eje0 = new \ejercicio();
            $ejercicio = $eje0->get($this->codejercicio);
            if ($ejercicio) {
                if (!$ejercicio->abierto()) {
                    $this->new_error_msg('El ejercicio ' . $ejercicio->nombre . ' está cerrado. No se puede modificar la fecha.');
                } else if ($fecha == $ejercicio->get_best_fecha($fecha)) {
                    $regiva0 = new \regularizacion_iva();
                    if ($regiva0->get_fecha_inside($fecha)) {
                        $this->new_error_msg('No se puede asignar la fecha ' . $fecha . ' porque ya hay'
                            . ' una regularización de ' . FS_IVA . ' para ese periodo.');
                    } else if ($regiva0->get_fecha_inside($this->fecha)) {
                        $this->new_error_msg('La factura se encuentra dentro de una regularización de '
                            . FS_IVA . '. No se puede modificar la fecha.');
                    } else {
                        $this->fecha = $fecha;
                        $this->hora = $hora;
                        $cambio = FALSE;
                    }
                } else {
                    $this->new_error_msg('La fecha está fuera del rango del ejercicio ' . $ejercicio->nombre);
                }
            } else {
                $this->new_error_msg('Ejercicio no encontrado.');
            }
        } else if ($hora != $this->hora) { /// factura existente y cambiamos hora
            $this->hora = $hora;
        }

        return $cambio;
    }

    public function url()
    {
        if (is_null($this->idfactura)) {
            return 'index.php?page=ventas_facturas';
        }

        return 'index.php?page=ventas_factura&id=' . $this->idfactura;
    }

    public function asiento_url()
    {
        if (is_null($this->idasiento)) {
            return 'index.php?page=contabilidad_asientos';
        }

        return 'index.php?page=contabilidad_asiento&id=' . $this->idasiento;
    }

    public function asiento_pago_url()
    {
        if (is_null($this->idasientop)) {
            return 'index.php?page=contabilidad_asientos';
        }

        return 'index.php?page=contabilidad_asiento&id=' . $this->idasientop;
    }

    public function agente_url()
    {
        if (is_null($this->codagente)) {
            return "index.php?page=admin_agentes";
        }

        return "index.php?page=admin_agente&cod=" . $this->codagente;
    }

    public function cliente_url()
    {
        if (is_null($this->codcliente)) {
            return "index.php?page=ventas_clientes";
        }

        return "index.php?page=ventas_cliente&cod=" . $this->codcliente;
    }

    public function get_asiento()
    {
        $asiento = new \asiento();
        return $asiento->get($this->idasiento);
    }

    public function get_asiento_pago()
    {
        $asiento = new \asiento();
        return $asiento->get($this->idasientop);
    }

    /**
     * Devulve las líneas de la factura.
     * @return linea_factura_cliente
     */
    public function get_lineas()
    {
        $linea = new \linea_factura_cliente();
        return $linea->all_from_factura($this->idfactura);
    }

    /**
     * Devuelve las líneas de IVA de la factura.
     * Si no hay, las crea.
     * @return \linea_iva_factura_cliente
     */
    public function get_lineas_iva()
    {
        $linea_iva = new \linea_iva_factura_cliente();
        $lineasi = $linea_iva->all_from_factura($this->idfactura);
        /// si no hay lineas de IVA las generamos
        if (empty($lineasi)) {
            $lineas = $this->get_lineas();
            if (!empty($lineas)) {
                foreach ($lineas as $l) {
                    $i = 0;
                    $encontrada = FALSE;
                    while ($i < count($lineasi)) {
                        if ($l->iva == $lineasi[$i]->iva && $l->recargo == $lineasi[$i]->recargo) {
                            $encontrada = TRUE;
                            $lineasi[$i]->neto += $l->pvptotal;
                            $lineasi[$i]->totaliva += ($l->pvptotal * $l->iva) / 100.0;
                            $lineasi[$i]->totalrecargo += ($l->pvptotal * $l->recargo) / 100.0;
                        }
                        $i++;
                    }
                    if (!$encontrada) {
                        $lineasi[$i] = new \linea_iva_factura_cliente();
                        $lineasi[$i]->idfactura = $this->idfactura;
                        $lineasi[$i]->codimpuesto = $l->codimpuesto;
                        $lineasi[$i]->iva = $l->iva;
                        $lineasi[$i]->recargo = $l->recargo;
                        $lineasi[$i]->neto = $l->pvptotal;
                        $lineasi[$i]->totaliva = ($l->pvptotal * $l->iva) / 100.0;
                        $lineasi[$i]->totalrecargo = ($l->pvptotal * $l->recargo) / 100.0;
                    }
                }

                /// redondeamos y guardamos
                if (count($lineasi) == 1) {
                    $lineasi[0]->neto = round($lineasi[0]->neto, FS_NF0);
                    $lineasi[0]->totaliva = round($lineasi[0]->totaliva, FS_NF0);
                    $lineasi[0]->totaliva = round($lineasi[0]->totaliva, FS_NF0);
                    $lineasi[0]->totallinea = $lineasi[0]->neto + $lineasi[0]->totaliva + $lineasi[0]->totalrecargo;
                    $lineasi[0]->save();
                } else {
                    /*
                     * Como el neto y el iva se redondean en la factura, al dividirlo
                     * en líneas de iva podemos encontrarnos con un descuadre que
                     * hay que calcular y solucionar.
                     */
                    $t_neto = 0;
                    $t_iva = 0;
                    foreach ($lineasi as $li) {
                        $li->neto = bround($li->neto, FS_NF0);
                        $li->totaliva = bround($li->totaliva, FS_NF0);
                        $li->totallinea = $li->neto + $li->totaliva;

                        $t_neto += $li->neto;
                        $t_iva += $li->totaliva;
                    }

                    if (!$this->floatcmp($this->neto, $t_neto)) {
                        /*
                         * Sumamos o restamos un céntimo a los netos más altos
                         * hasta que desaparezca el descuadre
                         */
                        $diferencia = round(($this->neto - $t_neto) * 100);
                        usort($lineasi, function($a, $b) {
                            if ($a->totallinea == $b->totallinea) {
                                return 0;
                            } else if ($a->totallinea < 0) {
                                return ($a->totallinea < $b->totallinea) ? -1 : 1;
                            }
                            return ($a->totallinea < $b->totallinea) ? 1 : -1;
                        });

                        foreach ($lineasi as $i => $value) {
                            if ($diferencia > 0) {
                                $lineasi[$i]->neto += .01;
                                $diferencia--;
                            } else if ($diferencia < 0) {
                                $lineasi[$i]->neto -= .01;
                                $diferencia++;
                            } else
                                break;
                        }
                    }

                    if (!$this->floatcmp($this->totaliva, $t_iva)) {
                        /*
                         * Sumamos o restamos un céntimo a los importes más altos
                         * hasta que desaparezca el descuadre
                         */
                        $diferencia = round(($this->totaliva - $t_iva) * 100);
                        usort($lineasi, function($a, $b) {
                            if ($a->totaliva == $b->totaliva) {
                                return 0;
                            } else if ($a->totallinea < 0) {
                                return ($a->totaliva < $b->totaliva) ? -1 : 1;
                            }
                            return ($a->totaliva < $b->totaliva) ? 1 : -1;
                        });

                        foreach ($lineasi as $i => $value) {
                            if ($diferencia > 0) {
                                $lineasi[$i]->totaliva += .01;
                                $diferencia--;
                            } else if ($diferencia < 0) {
                                $lineasi[$i]->totaliva -= .01;
                                $diferencia++;
                            } else
                                break;
                        }
                    }

                    foreach ($lineasi as $i => $value) {
                        $lineasi[$i]->totallinea = $value->neto + $value->totaliva + $value->totalrecargo;
                        $lineasi[$i]->save();
                    }
                }
            }
        }

        return $lineasi;
    }

    /**
     * Devuelve un array con todas las facturas rectificativas de esta factura.
     * @return \factura_cliente
     */
    public function get_rectificativas()
    {
        $devoluciones = array();

        $data = $this->db->select("SELECT * FROM " . $this->table_name . " WHERE idfacturarect = " . $this->var2str($this->idfactura) . ";");
        if ($data) {
            foreach ($data as $d) {
                $devoluciones[] = new \factura_cliente($d);
            }
        }

        return $devoluciones;
    }

    /**
     * Devuelve la factura de venta con el id proporcionado.
     * @param string $id
     * @return boolean|\factura_cliente
     */
    public function get($id)
    {
        $data = $this->db->select("SELECT * FROM " . $this->table_name . " WHERE idfactura = " . $this->var2str($id) . ";");
        if ($data) {
            return new \factura_cliente($data[0]);
        }

        return FALSE;
    }

    public function get_by_codigo($cod)
    {
        $data = $this->db->select("SELECT * FROM " . $this->table_name . " WHERE codigo = " . $this->var2str($cod) . ";");
        if ($data) {
            return new \factura_cliente($data[0]);
        }

        return FALSE;
    }

    public function get_by_num_serie($num, $serie, $eje)
    {
        $sql = "SELECT * FROM " . $this->table_name . " WHERE numero = " . $this->var2str($num)
            . " AND codserie = " . $this->var2str($serie)
            . " AND codejercicio = " . $this->var2str($eje) . ";";

        $data = $this->db->select($sql);
        if ($data) {
            return new \factura_cliente($data[0]);
        }

        return FALSE;
    }

    public function exists()
    {
        if (is_null($this->idfactura)) {
            return FALSE;
        }

        return $this->db->select("SELECT * FROM " . $this->table_name . " WHERE idfactura = " . $this->var2str($this->idfactura) . ";");
    }

    /**
     * Genera el número y código de la factura.
     */
    public function new_codigo()
    {
        /// buscamos el número inicial para la serie
        $num = 1;
        $serie0 = new \serie();
        $serie = $serie0->get($this->codserie);
        /// ¿Se ha definido un nº de factura inicial para esta serie y ejercicio?
        if ($serie && $this->codejercicio == $serie->codejercicio) {
            $num = $serie->numfactura;
        }

        /// buscamos un hueco o el siguiente número disponible
        $encontrado = FALSE;
        $fecha = $this->fecha;
        $hora = $this->hora;
        $sql = "SELECT " . $this->db->sql_to_int('numero') . " as numero,fecha,hora FROM " . $this->table_name;
        if (FS_NEW_CODIGO != 'NUM' && FS_NEW_CODIGO != '0-NUM') {
            $sql .= " WHERE codejercicio = " . $this->var2str($this->codejercicio)
                . " AND codserie = " . $this->var2str($this->codserie);
        }
        $sql .= " ORDER BY numero ASC;";

        $data = $this->db->select($sql);
        if ($data) {
            foreach ($data as $d) {
                if (intval($d['numero']) < $num) {
                    /**
                     * El número de la factura es menor que el inicial.
                     * El usuario ha cambiado el número inicial después de hacer
                     * facturas.
                     */
                } else if (intval($d['numero']) == $num) {
                    /// el número es correcto, avanzamos
                    $num++;
                } else {
                    /// Hemos encontrado un hueco y debemos usar el número y la fecha.
                    $encontrado = TRUE;
                    $fecha = Date('d-m-Y', strtotime($d['fecha']));
                    $hora = Date('H:i:s', strtotime($d['hora']));
                    break;
                }
            }
        }

        $this->numero = $num;

        if ($encontrado) {
            $this->fecha = $fecha;
            $this->hora = $hora;
        } else {
            /// nos guardamos la secuencia para abanq/eneboo
            $sec0 = new \secuencia();
            $sec = $sec0->get_by_params2($this->codejercicio, $this->codserie, 'nfacturacli');
            if ($sec && $sec->valorout <= $this->numero) {
                $sec->valorout = 1 + $this->numero;
                $sec->save();
            }
        }

        $this->codigo = fs_documento_new_codigo(FS_FACTURA, $this->codejercicio, $this->codserie, $this->numero);
    }

    /**
     * Comprueba los datos de la factura, devuelve TRUE si está todo correcto
     * @return boolean
     */
    public function test()
    {
        $this->nombrecliente = $this->no_html($this->nombrecliente);
        if ($this->nombrecliente == '') {
            $this->nombrecliente = '-';
        }

        $this->direccion = $this->no_html($this->direccion);
        $this->ciudad = $this->no_html($this->ciudad);
        $this->provincia = $this->no_html($this->provincia);
        $this->envio_nombre = $this->no_html($this->envio_nombre);
        $this->envio_apellidos = $this->no_html($this->envio_apellidos);
        $this->envio_direccion = $this->no_html($this->envio_direccion);
        $this->envio_ciudad = $this->no_html($this->envio_ciudad);
        $this->envio_provincia = $this->no_html($this->envio_provincia);
        $this->numero2 = $this->no_html($this->numero2);
        $this->observaciones = $this->no_html($this->observaciones);

        /**
         * Usamos el euro como divisa puente a la hora de sumar, comparar
         * o convertir cantidades en varias divisas. Por este motivo necesimos
         * muchos decimales.
         */
        $this->totaleuros = round($this->total / $this->tasaconv, 5);

        if ($this->floatcmp($this->total, $this->neto + $this->totaliva - $this->totalirpf + $this->totalrecargo, FS_NF0, TRUE)) {
            return TRUE;
        }

        $this->new_error_msg("Error grave: El total está mal calculado. ¡Informa del error!");
        return FALSE;
    }

    public function full_test($duplicados = TRUE)
    {
        $status = TRUE;

        /// comprobamos la fecha de la factura
        $ejercicio = new \ejercicio();
        $eje0 = $ejercicio->get($this->codejercicio);
        if ($eje0) {
            if (strtotime($this->fecha) < strtotime($eje0->fechainicio) || strtotime($this->fecha) > strtotime($eje0->fechafin)) {
                $status = FALSE;
                $this->new_error_msg("La fecha de esta factura está fuera del rango del"
                    . " <a target='_blank' href='" . $eje0->url() . "'>ejercicio</a>.");
            }
        }
        $numero0 = intval($this->numero) - 1;
        if ($numero0 > 0) {
            $fac0 = $this->get_by_num_serie($numero0, $this->codserie, $this->codejercicio);
            if ($fac0) {
                if (strtotime($fac0->fecha) > strtotime($this->fecha)) {
                    $status = FALSE;
                    $this->new_error_msg("La fecha de esta factura es anterior a la fecha de <a href='" .
                        $fac0->url() . "'>la factura anterior</a>.");
                }
            }
        }
        $numero2 = intval($this->numero) + 1;
        $fac2 = $this->get_by_num_serie($numero2, $this->codserie, $this->codejercicio);
        if ($fac2) {
            if (strtotime($fac2->fecha) < strtotime($this->fecha)) {
                $status = FALSE;
                $this->new_error_msg("La fecha de esta factura es posterior a la fecha de <a href='" .
                    $fac2->url() . "'>la factura siguiente</a>.");
            }
        }

        /// comprobamos las líneas
        $neto = 0;
        $iva = 0;
        $irpf = 0;
        $recargo = 0;
        foreach ($this->get_lineas() as $l) {
            if (!$l->test()) {
                $status = FALSE;
            }

            $neto += $l->pvptotal;
            $iva += $l->pvptotal * $l->iva / 100;
            $irpf += $l->pvptotal * $l->irpf / 100;
            $recargo += $l->pvptotal * $l->recargo / 100;
        }

        $neto = round($neto, FS_NF0);
        $iva = round($iva, FS_NF0);
        $irpf = round($irpf, FS_NF0);
        $recargo = round($recargo, FS_NF0);
        $total = $neto + $iva - $irpf + $recargo;

        if (!$this->floatcmp($this->neto, $neto, FS_NF0, TRUE)) {
            $this->new_error_msg("Valor neto de la factura " . $this->codigo . " incorrecto. Valor correcto: " . $neto);
            $status = FALSE;
        } else if (!$this->floatcmp($this->totaliva, $iva, FS_NF0, TRUE)) {
            $this->new_error_msg("Valor totaliva de la factura " . $this->codigo . " incorrecto. Valor correcto: " . $iva);
            $status = FALSE;
        } else if (!$this->floatcmp($this->totalirpf, $irpf, FS_NF0, TRUE)) {
            $this->new_error_msg("Valor totalirpf de la factura " . $this->codigo . " incorrecto. Valor correcto: " . $irpf);
            $status = FALSE;
        } else if (!$this->floatcmp($this->totalrecargo, $recargo, FS_NF0, TRUE)) {
            $this->new_error_msg("Valor totalrecargo de la factura " . $this->codigo . " incorrecto. Valor correcto: " . $recargo);
            $status = FALSE;
        } else if (!$this->floatcmp($this->total, $total, FS_NF0, TRUE)) {
            $this->new_error_msg("Valor total de la factura " . $this->codigo . " incorrecto. Valor correcto: " . $total);
            $status = FALSE;
        }

        /// comprobamos las líneas de IVA
        $this->get_lineas_iva();
        $linea_iva = new \linea_iva_factura_cliente();
        if (!$linea_iva->factura_test($this->idfactura, $neto, $iva, $recargo)) {
            $status = FALSE;
        }

        /// comprobamos el asiento
        if (isset($this->idasiento)) {
            $asiento = $this->get_asiento();
            if ($asiento) {
                if ($asiento->tipodocumento != 'Factura de cliente' || $asiento->documento != $this->codigo) {
                    $this->new_error_msg("Esta factura apunta a un <a href='" . $this->asiento_url() . "'>asiento incorrecto</a>.");
                    $status = FALSE;
                } else if ($this->coddivisa == $this->default_items->coddivisa() && ( abs($asiento->importe) - abs($this->total + $this->totalirpf) >= .02)) {
                    $this->new_error_msg("El importe del asiento es distinto al de la factura.");
                    $status = FALSE;
                } else {
                    $asientop = $this->get_asiento_pago();
                    if ($asientop) {
                        if ($this->totalirpf != 0) {
                            /// excluimos la comprobación si la factura tiene IRPF
                        } else if (!$this->floatcmp($asiento->importe, $asientop->importe)) {
                            $this->new_error_msg('No coinciden los importes de los asientos.');
                            $status = FALSE;
                        }
                    }
                }
            } else {
                $this->new_error_msg("Asiento no encontrado.");
                $status = FALSE;
            }
        }

        if ($status && $duplicados) {
            /// comprobamos si es un duplicado
            $data = $this->db->select("SELECT * FROM " . $this->table_name . " WHERE fecha = " . $this->var2str($this->fecha)
                . " AND codcliente = " . $this->var2str($this->codcliente)
                . " AND total = " . $this->var2str($this->total)
                . " AND codagente = " . $this->var2str($this->codagente)
                . " AND numero2 = " . $this->var2str($this->numero2)
                . " AND observaciones = " . $this->var2str($this->observaciones)
                . " AND idfactura != " . $this->var2str($this->idfactura) . ";");
            if ($data) {
                foreach ($data as $fac) {
                    /// comprobamos las líneas
                    $aux = $this->db->select("SELECT referencia FROM lineasfacturascli WHERE
                  idfactura = " . $this->var2str($this->idfactura) . "
                  AND referencia NOT IN (SELECT referencia FROM lineasfacturascli
                  WHERE idfactura = " . $this->var2str($fac['idfactura']) . ");");
                    if (!$aux) {
                        $this->new_error_msg("Esta factura es un posible duplicado de
                     <a href='index.php?page=ventas_factura&id=" . $fac['idfactura'] . "'>esta otra</a>.
                     Si no lo es, para evitar este mensaje, simplemente modifica las observaciones.");
                        $status = FALSE;
                    }
                }
            }
        }

        return $status;
    }

    public function save()
    {
        if ($this->test()) {
            $this->clean_cache();

            if ($this->exists()) {
                $sql = "UPDATE " . $this->table_name . " SET idasiento = " . $this->var2str($this->idasiento) .
                    ", idasientop = " . $this->var2str($this->idasientop) .
                    ", idfacturarect = " . $this->var2str($this->idfacturarect) .
                    ", codigo = " . $this->var2str($this->codigo) .
                    ", numero = " . $this->var2str($this->numero) .
                    ", numero2 = " . $this->var2str($this->numero2) .
                    ", codigorect = " . $this->var2str($this->codigorect) .
                    ", codejercicio = " . $this->var2str($this->codejercicio) .
                    ", codserie = " . $this->var2str($this->codserie) .
                    ", codalmacen = " . $this->var2str($this->codalmacen) .
                    ", codpago = " . $this->var2str($this->codpago) .
                    ", coddivisa = " . $this->var2str($this->coddivisa) .
                    ", fecha = " . $this->var2str($this->fecha) .
                    ", codcliente = " . $this->var2str($this->codcliente) .
                    ", nombrecliente = " . $this->var2str($this->nombrecliente) .
                    ", cifnif = " . $this->var2str($this->cifnif) .
                    ", direccion = " . $this->var2str($this->direccion) .
                    ", ciudad = " . $this->var2str($this->ciudad) .
                    ", provincia = " . $this->var2str($this->provincia) .
                    ", apartado = " . $this->var2str($this->apartado) .
                    ", coddir = " . $this->var2str($this->coddir) .
                    ", codpostal = " . $this->var2str($this->codpostal) .
                    ", codpais = " . $this->var2str($this->codpais) .
                    ", codagente = " . $this->var2str($this->codagente) .
                    ", neto = " . $this->var2str($this->neto) .
                    ", totaliva = " . $this->var2str($this->totaliva) .
                    ", total = " . $this->var2str($this->total) .
                    ", totaleuros = " . $this->var2str($this->totaleuros) .
                    ", irpf = " . $this->var2str($this->irpf) .
                    ", totalirpf = " . $this->var2str($this->totalirpf) .
                    ", porcomision = " . $this->var2str($this->porcomision) .
                    ", tasaconv = " . $this->var2str($this->tasaconv) .
                    ", totalrecargo = " . $this->var2str($this->totalrecargo) .
                    ", observaciones = " . $this->var2str($this->observaciones) .
                    ", pagada = " . $this->var2str($this->pagada) .
                    ", anulada = " . $this->var2str($this->anulada) .
                    ", hora = " . $this->var2str($this->hora) .
                    ", vencimiento = " . $this->var2str($this->vencimiento) .
                    ", femail = " . $this->var2str($this->femail) .
                    ", codtrans = " . $this->var2str($this->envio_codtrans) .
                    ", codigoenv = " . $this->var2str($this->envio_codigo) .
                    ", nombreenv = " . $this->var2str($this->envio_nombre) .
                    ", apellidosenv = " . $this->var2str($this->envio_apellidos) .
                    ", apartadoenv = " . $this->var2str($this->envio_apartado) .
                    ", direccionenv = " . $this->var2str($this->envio_direccion) .
                    ", codpostalenv = " . $this->var2str($this->envio_codpostal) .
                    ", ciudadenv = " . $this->var2str($this->envio_ciudad) .
                    ", provinciaenv = " . $this->var2str($this->envio_provincia) .
                    ", codpaisenv = " . $this->var2str($this->envio_codpais) .
                    ", idimprenta = " . $this->var2str($this->idimprenta) .
                    ", numdocs = " . $this->var2str($this->numdocs) .
                    "  WHERE idfactura = " . $this->var2str($this->idfactura) . ";";

                return $this->db->exec($sql);
            }

            $this->new_codigo();
            $sql = "INSERT INTO " . $this->table_name . " (idasiento,idasientop,idfacturarect,codigo,numero,
               codigorect,codejercicio,codserie,codalmacen,codpago,coddivisa,fecha,codcliente,
               nombrecliente,cifnif,direccion,ciudad,provincia,apartado,coddir,codpostal,codpais,
               codagente,neto,totaliva,total,totaleuros,irpf,totalirpf,porcomision,tasaconv,
               totalrecargo,pagada,anulada,observaciones,hora,numero2,vencimiento,femail,codtrans,
               codigoenv,nombreenv,apellidosenv,apartadoenv,direccionenv,codpostalenv,ciudadenv,provinciaenv,
               codpaisenv,idimprenta,numdocs) VALUES (" . $this->var2str($this->idasiento) .
                "," . $this->var2str($this->idasientop) .
                "," . $this->var2str($this->idfacturarect) .
                "," . $this->var2str($this->codigo) .
                "," . $this->var2str($this->numero) .
                "," . $this->var2str($this->codigorect) .
                "," . $this->var2str($this->codejercicio) .
                "," . $this->var2str($this->codserie) .
                "," . $this->var2str($this->codalmacen) .
                "," . $this->var2str($this->codpago) .
                "," . $this->var2str($this->coddivisa) .
                "," . $this->var2str($this->fecha) .
                "," . $this->var2str($this->codcliente) .
                "," . $this->var2str($this->nombrecliente) .
                "," . $this->var2str($this->cifnif) .
                "," . $this->var2str($this->direccion) .
                "," . $this->var2str($this->ciudad) .
                "," . $this->var2str($this->provincia) .
                "," . $this->var2str($this->apartado) .
                "," . $this->var2str($this->coddir) .
                "," . $this->var2str($this->codpostal) .
                "," . $this->var2str($this->codpais) .
                "," . $this->var2str($this->codagente) .
                "," . $this->var2str($this->neto) .
                "," . $this->var2str($this->totaliva) .
                "," . $this->var2str($this->total) .
                "," . $this->var2str($this->totaleuros) .
                "," . $this->var2str($this->irpf) .
                "," . $this->var2str($this->totalirpf) .
                "," . $this->var2str($this->porcomision) .
                "," . $this->var2str($this->tasaconv) .
                "," . $this->var2str($this->totalrecargo) .
                "," . $this->var2str($this->pagada) .
                "," . $this->var2str($this->anulada) .
                "," . $this->var2str($this->observaciones) .
                "," . $this->var2str($this->hora) .
                "," . $this->var2str($this->numero2) .
                "," . $this->var2str($this->vencimiento) .
                "," . $this->var2str($this->femail) .
                "," . $this->var2str($this->envio_codtrans) .
                "," . $this->var2str($this->envio_codigo) .
                "," . $this->var2str($this->envio_nombre) .
                "," . $this->var2str($this->envio_apellidos) .
                "," . $this->var2str($this->envio_apartado) .
                "," . $this->var2str($this->envio_direccion) .
                "," . $this->var2str($this->envio_codpostal) .
                "," . $this->var2str($this->envio_ciudad) .
                "," . $this->var2str($this->envio_provincia) .
                "," . $this->var2str($this->envio_codpais) .
                "," . $this->var2str($this->idimprenta) .
                "," . $this->var2str($this->numdocs) . ");";

            if ($this->db->exec($sql)) {
                $this->idfactura = $this->db->lastval();
                return TRUE;
            }
        }

        return FALSE;
    }

    public function delete()
    {
        $bloquear = FALSE;

        $eje0 = new \ejercicio();
        $ejercicio = $eje0->get($this->codejercicio);
        if ($ejercicio) {
            if ($ejercicio->abierto()) {
                $reg0 = new \regularizacion_iva();
                if ($reg0->get_fecha_inside($this->fecha)) {
                    $this->new_error_msg('La factura se encuentra dentro de una regularización de '
                        . FS_IVA . '. No se puede eliminar.');
                    $bloquear = TRUE;
                } else if (count($this->get_rectificativas()) > 0) {
                    $this->new_error_msg('La factura ya tiene una rectificativa. No se puede eliminar.');
                    $bloquear = TRUE;
                }
            } else {
                $this->new_error_msg('El ejercicio ' . $ejercicio->nombre . ' está cerrado.');
                $bloquear = TRUE;
            }
        }

        /// desvincular albaranes asociados y eliminar factura
        $sql = "UPDATE albaranescli SET idfactura = NULL, ptefactura = TRUE WHERE idfactura = " . $this->var2str($this->idfactura) . ";"
            . "DELETE FROM " . $this->table_name . " WHERE idfactura = " . $this->var2str($this->idfactura) . ";";

        if ($bloquear) {
            return FALSE;
        } else if ($this->db->exec($sql)) {
            $this->clean_cache();

            if ($this->idasiento) {
                /**
                 * Delegamos la eliminación de los asientos en la clase correspondiente.
                 */
                $asiento = new \asiento();
                $asi0 = $asiento->get($this->idasiento);
                if ($asi0) {
                    $asi0->delete();
                }

                $asi1 = $asiento->get($this->idasientop);
                if ($asi1) {
                    $asi1->delete();
                }
            }

            $this->new_message(ucfirst(FS_FACTURA) . " de venta " . $this->codigo . " eliminada correctamente.");
            return TRUE;
        }

        return FALSE;
    }

    private function clean_cache()
    {
        $this->cache->delete('factura_cliente_huecos');
    }

    private function all_from($sql, $offset = 0, $limit = FS_ITEM_LIMIT)
    {
        $faclist = array();
        $data = $this->db->select_limit($sql, $limit, $offset);
        if ($data) {
            foreach ($data as $a) {
                $faclist[] = new \factura_cliente($a);
            }
        }

        return $faclist;
    }

    /**
     * Devuelve un array con las últimas facturas (con el orden por defecto).
     * Si alteras el orden puedes obtener lo que desees.
     * @param integer $offset
     * @param integer $limit
     * @param string $order
     * @return \factura_cliente
     */
    public function all($offset = 0, $limit = FS_ITEM_LIMIT, $order = 'fecha DESC, codigo DESC')
    {
        $sql = "SELECT * FROM " . $this->table_name . " ORDER BY " . $order;
        return $this->all_from($sql, $offset, $limit);
    }

    /**
     * Devuelve un array con las facturas sin pagar
     * @param integer $offset
     * @param integer $limit
     * @param string $order
     * @return \factura_cliente
     */
    public function all_sin_pagar($offset = 0, $limit = FS_ITEM_LIMIT, $order = 'vencimiento ASC, codigo ASC')
    {
        $sql = "SELECT * FROM " . $this->table_name . " WHERE pagada = false ORDER BY " . $order;
        return $this->all_from($sql, $offset, $limit);
    }

    /**
     * Devuelve un array con las facturas del agente/empleado
     * @param string $codagente
     * @param integer $offset
     * @return \factura_cliente
     */
    public function all_from_agente($codagente, $offset = 0)
    {
        $sql = "SELECT * FROM " . $this->table_name .
            " WHERE codagente = " . $this->var2str($codagente) .
            " ORDER BY fecha DESC, codigo DESC";

        return $this->all_from($sql, $offset);
    }

    /**
     * Devuelve un array con las facturas del cliente $codcliente
     * @param string $codcliente
     * @param integer $offset
     * @return \factura_cliente
     */
    public function all_from_cliente($codcliente, $offset = 0)
    {
        $sql = "SELECT * FROM " . $this->table_name .
            " WHERE codcliente = " . $this->var2str($codcliente) .
            " ORDER BY fecha DESC, codigo DESC";

        return $this->all_from($sql, $offset);
    }

    /**
     * Devuelve un array con las facturas comprendidas entre $desde y $hasta
     * @param string $desde
     * @param string $hasta
     * @param string $codserie código de la serie
     * @param string $codagente código del empleado
     * @param string $codcliente código del cliente
     * @param string $estado
     * @param string $codpago código de la forma de pago
     * @param string $codalmacen código del almacén
     * @return \factura_cliente
     */
    public function all_desde($desde, $hasta, $codserie = FALSE, $codagente = FALSE, $codcliente = FALSE, $estado = FALSE, $codpago = FALSE, $codalmacen = FALSE)
    {
        $faclist = array();

        $sql = "SELECT * FROM " . $this->table_name . " WHERE fecha >= " . $this->var2str($desde) . " AND fecha <= " . $this->var2str($hasta);
        if ($codserie) {
            $sql .= " AND codserie = " . $this->var2str($codserie);
        }
        if ($codagente) {
            $sql .= " AND codagente = " . $this->var2str($codagente);
        }
        if ($codcliente) {
            $sql .= " AND codcliente = " . $this->var2str($codcliente);
        }
        if ($estado) {
            if ($estado == 'pagada') {
                $sql .= " AND pagada = true";
            } else {
                $sql .= " AND pagada = false";
            }
        }
        if ($codpago) {
            $sql .= " AND codpago = " . $this->var2str($codpago);
        }
        if ($codalmacen) {
            $sql .= " AND codalmacen = " . $this->var2str($codalmacen);
        }
        $sql .= " ORDER BY fecha ASC, codigo ASC;";

        $data = $this->db->select($sql);
        if ($data) {
            foreach ($data as $f) {
                $faclist[] = new \factura_cliente($f);
            }
        }

        return $faclist;
    }

    /**
     * Devuelve un array con las facturas que coinciden con $query
     * @param string $query
     * @param integer $offset
     * @return \factura_cliente
     */
    public function search($query, $offset = 0)
    {
        $query = mb_strtolower($this->no_html($query), 'UTF8');

        $sql = "SELECT * FROM " . $this->table_name . " WHERE ";
        if (is_numeric($query)) {
            $sql .= "codigo LIKE '%" . $query . "%' OR numero2 LIKE '%" . $query . "%' OR observaciones LIKE '%" . $query . "%'";
        } else {
            $sql .= "lower(codigo) LIKE '%" . $query . "%' OR lower(numero2) LIKE '%" . $query . "%' "
                . "OR lower(observaciones) LIKE '%" . str_replace(' ', '%', $query) . "%'";
        }
        $sql .= " ORDER BY fecha DESC, codigo DESC";

        return $this->all_from($sql, $offset);
    }

    /**
     * Devuelve un array con las facturas del cliente $codcliente que coinciden con $query
     * @param string $codcliente
     * @param string $desde
     * @param string $hasta
     * @param string $serie
     * @param string $obs
     * @return \factura_cliente
     */
    public function search_from_cliente($codcliente, $desde, $hasta, $serie, $obs = '')
    {
        $faclist = array();
        $sql = "SELECT * FROM " . $this->table_name . " WHERE codcliente = " . $this->var2str($codcliente) .
            " AND fecha BETWEEN " . $this->var2str($desde) . " AND " . $this->var2str($hasta) .
            " AND codserie = " . $this->var2str($serie);

        if ($obs != '') {
            $sql .= " AND lower(observaciones) = " . $this->var2str(mb_strtolower($obs, 'UTF8'));
        }

        $sql .= " ORDER BY fecha DESC, codigo DESC;";

        $data = $this->db->select($sql);
        if ($data) {
            foreach ($data as $f) {
                $faclist[] = new \factura_cliente($f);
            }
        }

        return $faclist;
    }

    /**
     * Devuelve un array con los huecos en la numeración.
     * @return array
     */
    public function huecos()
    {
        $error = TRUE;
        $huecolist = $this->cache->get_array2('factura_cliente_huecos', $error);
        if ($error) {
            $huecolist = fs_huecos_facturas_cliente($this->db, $this->table_name);
            $this->cache->set('factura_cliente_huecos', $huecolist, 3600);
        }

        return $huecolist;
    }

    public function cron_job()
    {
        
    }
}
