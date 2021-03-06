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
 * Línea de una factura de cliente.
 * 
 * @author Carlos García Gómez <neorazorx@gmail.com>
 */
class linea_factura_cliente extends \fs_model
{

    /**
     * Clave primaria.
     * @var type 
     */
    public $idlinea;

    /**
     * ID de la linea del albarán relacionado, si lo hay.
     * @var integer 
     */
    public $idlineaalbaran;

    /**
     * ID de la factura de esta línea.
     * @var integer 
     */
    public $idfactura;

    /**
     * ID del albarán relacionado con esta factura, si lo hay.
     * @var integer 
     */
    public $idalbaran;

    /**
     * Importe neto de la línea, sin impuestos.
     * @var double 
     */
    public $pvptotal;

    /**
     * % de descuento.
     * @var double 
     */
    public $dtopor;

    /**
     * % de recargo de equivalencia.
     * @var double 
     */
    public $recargo;

    /**
     * % de IRPF.
     * @var double 
     */
    public $irpf;

    /**
     * Importe neto sin descuentos.
     * @var double 
     */
    public $pvpsindto;
    public $cantidad;

    /**
     * Impuesto del artículo.
     * @var string
     */
    public $codimpuesto;

    /**
     * Precio del artículo, una unidad.
     * @var double 
     */
    public $pvpunitario;
    public $descripcion;

    /**
     * Referencia del artículo.
     * @var string 
     */
    public $referencia;

    /**
     * Código de la combinación seleccionada, en el caso de los artículos con atributos.
     * @var string 
     */
    public $codcombinacion;

    /**
     * % de IVA de la línea, el que corresponde al impuesto.
     * @var double 
     */
    public $iva;

    /**
     * Posición de la linea en el documento. Cuanto más alto más abajo.
     * @var integer 
     */
    public $orden;

    /**
     * False -> no se muestra la columna cantidad al imprimir.
     * @var boolean
     */
    public $mostrar_cantidad;

    /**
     * False -> no se muestran las columnas precio, descuento, impuestos y total al imprimir.
     * @var boolean
     */
    public $mostrar_precio;
    private $codigo;
    private $fecha;
    private $albaran_codigo;
    private $albaran_numero;
    private $albaran_fecha;
    private static $facturas;
    private static $albaranes;

    public function __construct($data = FALSE)
    {
        parent::__construct('lineasfacturascli');

        if (!isset(self::$facturas)) {
            self::$facturas = array();
            self::$albaranes = array();
        }

        if ($data) {
            $this->idlinea = $this->intval($data['idlinea']);
            $this->idlineaalbaran = $this->intval($data['idlineaalbaran']);
            $this->idfactura = $this->intval($data['idfactura']);
            $this->idalbaran = $this->intval($data['idalbaran']);
            $this->referencia = $data['referencia'];
            $this->codcombinacion = $data['codcombinacion'];
            $this->descripcion = $data['descripcion'];
            $this->cantidad = floatval($data['cantidad']);
            $this->pvpunitario = floatval($data['pvpunitario']);
            $this->pvpsindto = floatval($data['pvpsindto']);
            $this->dtopor = floatval($data['dtopor']);
            $this->pvptotal = floatval($data['pvptotal']);
            $this->codimpuesto = $data['codimpuesto'];
            $this->iva = floatval($data['iva']);
            $this->recargo = floatval($data['recargo']);
            $this->irpf = floatval($data['irpf']);
            $this->orden = intval($data['orden']);
            $this->mostrar_cantidad = $this->str2bool($data['mostrar_cantidad']);
            $this->mostrar_precio = $this->str2bool($data['mostrar_precio']);
        } else {
            $this->idlinea = NULL;
            $this->idlineaalbaran = NULL;
            $this->idfactura = NULL;
            $this->idalbaran = NULL;
            $this->referencia = NULL;
            $this->codcombinacion = NULL;
            $this->descripcion = '';
            $this->cantidad = 0.0;
            $this->pvpunitario = 0.0;
            $this->pvpsindto = 0.0;
            $this->dtopor = 0.0;
            $this->pvptotal = 0.0;
            $this->codimpuesto = NULL;
            $this->iva = 0.0;
            $this->recargo = 0.0;
            $this->irpf = 0.0;
            $this->orden = 0;
            $this->mostrar_cantidad = TRUE;
            $this->mostrar_precio = TRUE;
        }
    }

    protected function install()
    {
        /**
         * cargamos el modelo de factura_cliente simplemente para forzar
         * la comprobación de la tabla.
         */
        new \factura_cliente();

        return '';
    }

    private function fill()
    {
        $encontrado = FALSE;
        foreach (self::$facturas as $f) {
            if ($f->idfactura == $this->idfactura) {
                $this->codigo = $f->codigo;
                $this->fecha = $f->fecha;
                $encontrado = TRUE;
                break;
            }
        }
        if (!$encontrado) {
            $fac = new \factura_cliente();
            $fac = $fac->get($this->idfactura);
            if ($fac) {
                $this->codigo = $fac->codigo;
                $this->fecha = $fac->fecha;
                self::$facturas[] = $fac;
            }
        }

        if (!is_null($this->idalbaran)) {
            $encontrado = FALSE;
            foreach (self::$albaranes as $a) {
                if ($a->idalbaran == $this->idalbaran) {
                    $this->albaran_codigo = $a->codigo;
                    if (is_null($a->numero2) || $a->numero2 == '') {
                        $this->albaran_numero = $a->numero;
                    } else {
                        $this->albaran_numero = $a->numero2;
                    }
                    $this->albaran_fecha = $a->fecha;
                    $encontrado = TRUE;
                    break;
                }
            }
            if (!$encontrado) {
                $alb = new \albaran_cliente();
                $alb = $alb->get($this->idalbaran);
                if ($alb) {
                    $this->albaran_codigo = $alb->codigo;
                    if (is_null($alb->numero2) || $alb->numero2 == '') {
                        $this->albaran_numero = $alb->numero;
                    } else {
                        $this->albaran_numero = $alb->numero2;
                    }
                    $this->albaran_fecha = $alb->fecha;
                    self::$albaranes[] = $alb;
                }
            }
        }
    }

    public function total_iva()
    {
        return $this->pvptotal * (100 + $this->iva - $this->irpf + $this->recargo) / 100;
    }

    public function descripcion()
    {
        return nl2br($this->descripcion);
    }

    public function show_codigo()
    {
        if (!isset($this->codigo)) {
            $this->fill();
        }

        return $this->codigo;
    }

    public function show_fecha()
    {
        if (!isset($this->fecha)) {
            $this->fill();
        }

        return $this->fecha;
    }

    public function show_nombrecliente()
    {
        $nombre = 'desconocido';

        foreach (self::$facturas as $a) {
            if ($a->idfactura == $this->idfactura) {
                $nombre = $a->nombrecliente;
                break;
            }
        }

        return $nombre;
    }

    public function url()
    {
        return 'index.php?page=ventas_factura&id=' . $this->idfactura;
    }

    public function albaran_codigo()
    {
        if (!isset($this->albaran_codigo)) {
            $this->fill();
        }

        return $this->albaran_codigo;
    }

    public function albaran_url()
    {
        if (is_null($this->idalbaran)) {
            return 'index.php?page=ventas_albaranes';
        }

        return 'index.php?page=ventas_albaran&id=' . $this->idalbaran;
    }

    public function albaran_numero()
    {
        if (!isset($this->albaran_numero)) {
            $this->fill();
        }

        return $this->albaran_numero;
    }

    public function albaran_fecha()
    {
        if (!isset($this->albaran_fecha)) {
            $this->fill();
        }

        return $this->albaran_fecha;
    }

    public function articulo_url()
    {
        if (is_null($this->referencia) || $this->referencia == '') {
            return "index.php?page=ventas_articulos";
        }

        return "index.php?page=ventas_articulo&ref=" . urlencode($this->referencia);
    }

    /**
     * Devuelve los datos de una linea.
     * @param type $idlinea
     * @return boolean|\linea_factura_cliente
     */
    public function get($idlinea)
    {
        $data = $this->db->select("SELECT * FROM " . $this->table_name . " WHERE idlinea = " . $this->var2str($idlinea) . ";");
        if ($data) {
            return new \linea_factura_cliente($data[0]);
        }

        return FALSE;
    }

    public function exists()
    {
        if (is_null($this->idlinea)) {
            return FALSE;
        }

        return $this->db->select("SELECT * FROM " . $this->table_name . " WHERE idlinea = " . $this->var2str($this->idlinea) . ";");
    }

    public function test()
    {
        $this->descripcion = $this->no_html($this->descripcion);
        $total = $this->pvpunitario * $this->cantidad * (100 - $this->dtopor) / 100;
        $totalsindto = $this->pvpunitario * $this->cantidad;

        if (!$this->floatcmp($this->pvptotal, $total, FS_NF0, TRUE)) {
            $this->new_error_msg("Error en el valor de pvptotal de la línea " . $this->referencia
                . " de la factura. Valor correcto: " . $total);
            return FALSE;
        } else if (!$this->floatcmp($this->pvpsindto, $totalsindto, FS_NF0, TRUE)) {
            $this->new_error_msg("Error en el valor de pvpsindto de la línea " . $this->referencia
                . " de la factura. Valor correcto: " . $totalsindto);
            return FALSE;
        }

        return TRUE;
    }

    public function save()
    {
        if ($this->test()) {
            if ($this->exists()) {
                $sql = "UPDATE " . $this->table_name . " SET idfactura = " . $this->var2str($this->idfactura)
                    . ", idalbaran = " . $this->var2str($this->idalbaran)
                    . ", idlineaalbaran = " . $this->var2str($this->idlineaalbaran)
                    . ", referencia = " . $this->var2str($this->referencia)
                    . ", codcombinacion = " . $this->var2str($this->codcombinacion)
                    . ", descripcion = " . $this->var2str($this->descripcion)
                    . ", cantidad = " . $this->var2str($this->cantidad)
                    . ", pvpunitario = " . $this->var2str($this->pvpunitario)
                    . ", pvpsindto = " . $this->var2str($this->pvpsindto)
                    . ", dtopor = " . $this->var2str($this->dtopor)
                    . ", pvptotal = " . $this->var2str($this->pvptotal)
                    . ", codimpuesto = " . $this->var2str($this->codimpuesto)
                    . ", iva = " . $this->var2str($this->iva)
                    . ", recargo = " . $this->var2str($this->recargo)
                    . ", irpf = " . $this->var2str($this->irpf)
                    . ", orden = " . $this->var2str($this->orden)
                    . ", mostrar_cantidad = " . $this->var2str($this->mostrar_cantidad)
                    . ", mostrar_precio = " . $this->var2str($this->mostrar_precio)
                    . "  WHERE idlinea = " . $this->var2str($this->idlinea) . ";";

                return $this->db->exec($sql);
            }

            $sql = "INSERT INTO " . $this->table_name . " (idfactura,idalbaran,idlineaalbaran,referencia,
               codcombinacion,descripcion,cantidad,pvpunitario,pvpsindto,dtopor,pvptotal,codimpuesto,iva,
               recargo,irpf,orden,mostrar_cantidad,mostrar_precio) VALUES 
                      (" . $this->var2str($this->idfactura)
                . "," . $this->var2str($this->idalbaran)
                . "," . $this->var2str($this->idlineaalbaran)
                . "," . $this->var2str($this->referencia)
                . "," . $this->var2str($this->codcombinacion)
                . "," . $this->var2str($this->descripcion)
                . "," . $this->var2str($this->cantidad)
                . "," . $this->var2str($this->pvpunitario)
                . "," . $this->var2str($this->pvpsindto)
                . "," . $this->var2str($this->dtopor)
                . "," . $this->var2str($this->pvptotal)
                . "," . $this->var2str($this->codimpuesto)
                . "," . $this->var2str($this->iva)
                . "," . $this->var2str($this->recargo)
                . "," . $this->var2str($this->irpf)
                . "," . $this->var2str($this->orden)
                . "," . $this->var2str($this->mostrar_cantidad)
                . "," . $this->var2str($this->mostrar_precio) . ");";

            if ($this->db->exec($sql)) {
                $this->idlinea = $this->db->lastval();
                return TRUE;
            }
        }

        return FALSE;
    }

    public function delete()
    {
        return $this->db->exec("DELETE FROM " . $this->table_name . " WHERE idlinea = " . $this->var2str($this->idlinea) . ";");
    }

    private function all_from($sql, $offset = 0, $limit = FS_ITEM_LIMIT)
    {
        $linealist = array();
        $data = $this->db->select_limit($sql, $limit, $offset);
        if ($data) {
            foreach ($data as $a) {
                $linealist[] = new \linea_factura_cliente($a);
            }
        }
        return $linealist;
    }

    public function all_from_factura($id)
    {
        $linlist = array();
        $sql = "SELECT * FROM " . $this->table_name . " WHERE idfactura = " . $this->var2str($id)
            . " ORDER BY orden DESC, idlinea ASC;";

        $lineas = $this->db->select($sql);
        if ($lineas) {
            foreach ($lineas as $l) {
                $linlist[] = new \linea_factura_cliente($l);
            }
        }

        return $linlist;
    }

    public function all_from_articulo($ref, $offset = 0)
    {
        $sql = "SELECT * FROM " . $this->table_name .
            " WHERE referencia = " . $this->var2str($ref) .
            " ORDER BY idfactura DESC";

        return $this->all_from($sql, $offset);
    }

    public function search($query = '', $offset = 0)
    {
        $query = mb_strtolower($this->no_html($query), 'UTF8');

        $sql = "SELECT * FROM " . $this->table_name . " WHERE ";
        if (is_numeric($query)) {
            $sql .= "referencia LIKE '%" . $query . "%' OR descripcion LIKE '%" . $query . "%'";
        } else {
            $buscar = str_replace(' ', '%', $query);
            $sql .= "lower(referencia) LIKE '%" . $buscar . "%' OR lower(descripcion) LIKE '%" . $buscar . "%'";
        }
        $sql .= " ORDER BY idfactura DESC, idlinea ASC";

        return $this->all_from($sql, $offset);
    }

    public function search_from_cliente($codcliente, $query = '', $offset = 0)
    {
        $query = mb_strtolower($this->no_html($query), 'UTF8');

        $sql = "SELECT * FROM " . $this->table_name . " WHERE idfactura IN
         (SELECT idfactura FROM facturascli WHERE codcliente = " . $this->var2str($codcliente) . ") AND ";
        if (is_numeric($query)) {
            $sql .= "(referencia LIKE '%" . $query . "%' OR descripcion LIKE '%" . $query . "%')";
        } else {
            $buscar = str_replace(' ', '%', $query);
            $sql .= "(lower(referencia) LIKE '%" . $buscar . "%' OR lower(descripcion) LIKE '%" . $buscar . "%')";
        }
        $sql .= " ORDER BY idfactura DESC, idlinea ASC";

        return $this->all_from($sql, $offset);
    }

    public function search_from_cliente2($codcliente, $ref = '', $obs = '', $offset = 0)
    {
        $ref = mb_strtolower($this->no_html($ref), 'UTF8');
        $obs = mb_strtolower($this->no_html($obs), 'UTF8');

        $sql = "SELECT * FROM " . $this->table_name . " WHERE idfactura IN
         (SELECT idfactura FROM facturascli WHERE codcliente = " . $this->var2str($codcliente) . "
         AND lower(observaciones) LIKE '" . $obs . "%') AND ";
        if (is_numeric($ref)) {
            $sql .= "(referencia LIKE '%" . $ref . "%' OR descripcion LIKE '%" . $ref . "%')";
        } else {
            $ref = str_replace(' ', '%', $ref);
            $sql .= "(lower(referencia) LIKE '%" . $ref . "%' OR lower(descripcion) LIKE '%" . $ref . "%')";
        }
        $sql .= " ORDER BY idfactura DESC, idlinea ASC";

        return $this->all_from($sql, $offset);
    }

    public function facturas_from_albaran($id)
    {
        $facturalist = array();
        $sql = "SELECT DISTINCT idfactura FROM " . $this->table_name
            . " WHERE idalbaran = " . $this->var2str($id) . ";";

        $data = $this->db->select($sql);
        if ($data) {
            $factura = new \factura_cliente();
            foreach ($data as $l) {
                $fac = $factura->get($l['idfactura']);
                if ($fac) {
                    $facturalist[] = $fac;
                }
            }
        }

        return $facturalist;
    }
}
