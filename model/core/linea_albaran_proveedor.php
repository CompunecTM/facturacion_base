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
 * Línea de un albarán de proveedor.
 * 
 * @author Carlos García Gómez <neorazorx@gmail.com>
 */
class linea_albaran_proveedor extends \fs_model
{

    /**
     * Clave primaria.
     * @var integer 
     */
    public $idlinea;

    /**
     * ID de la línea del pedido relacionada, si la hay.
     * @var integer 
     */
    public $idlineapedido;

    /**
     * ID del albarán de esta línea.
     * @var integer 
     */
    public $idalbaran;

    /**
     * ID del pedido relacionado con el albarán, si lo hay.
     * @var integer 
     */
    public $idpedido;

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
    public $descripcion;
    public $cantidad;

    /**
     * % de descuento.
     * @var double 
     */
    public $dtopor;

    /**
     * Código del impuesto relacionado.
     * @var string
     */
    public $codimpuesto;

    /**
     * % del impuesto relacionado.
     * @var double 
     */
    public $iva;

    /**
     * Importe neto de la línea, sin impuestos.
     * @var double 
     */
    public $pvptotal;

    /**
     * Importe neto sin descuentos.
     * @var double 
     */
    public $pvpsindto;

    /**
     * Precio del artículo, una unidad.
     * @var double 
     */
    public $pvpunitario;

    /**
     * % de IRPF de la línea.
     * @var double 
     */
    public $irpf;

    /**
     * % de recargo de equivalencia de la línea.
     * @var double 
     */
    public $recargo;
    private $codigo;
    private $fecha;
    private static $albaranes;

    public function __construct($data = FALSE)
    {
        parent::__construct('lineasalbaranesprov');

        if (!isset(self::$albaranes)) {
            self::$albaranes = array();
        }

        if ($data) {
            $this->idlinea = $this->intval($data['idlinea']);
            $this->idlineapedido = $this->intval($data['idlineapedido']);
            $this->idalbaran = $this->intval($data['idalbaran']);
            $this->idpedido = $this->intval($data['idpedido']);
            $this->referencia = $data['referencia'];
            $this->codcombinacion = $data['codcombinacion'];
            $this->descripcion = $data['descripcion'];
            $this->cantidad = floatval($data['cantidad']);
            $this->dtopor = floatval($data['dtopor']);
            $this->codimpuesto = $data['codimpuesto'];
            $this->iva = floatval($data['iva']);
            $this->pvptotal = floatval($data['pvptotal']);
            $this->pvpsindto = floatval($data['pvpsindto']);
            $this->pvpunitario = floatval($data['pvpunitario']);
            $this->irpf = floatval($data['irpf']);
            $this->recargo = floatval($data['recargo']);
        } else {
            $this->idlinea = NULL;
            $this->idlineapedido = NULL;
            $this->idalbaran = NULL;
            $this->idpedido = NULL;
            $this->referencia = NULL;
            $this->codcombinacion = NULL;
            $this->descripcion = '';
            $this->cantidad = 0.0;
            $this->dtopor = 0.0;
            $this->codimpuesto = NULL;
            $this->iva = 0.0;
            $this->pvptotal = 0.0;
            $this->pvpsindto = 0.0;
            $this->pvpunitario = 0.0;
            $this->irpf = 0.0;
            $this->recargo = 0.0;
        }
    }

    protected function install()
    {
        return '';
    }

    /**
     * Completa con los datos del albarán.
     */
    private function fill()
    {
        $encontrado = FALSE;
        foreach (self::$albaranes as $a) {
            if ($a->idalbaran == $this->idalbaran) {
                $this->codigo = $a->codigo;
                $this->fecha = $a->fecha;
                $encontrado = TRUE;
                break;
            }
        }
        if (!$encontrado) {
            $alb = new \albaran_proveedor();
            $alb = $alb->get($this->idalbaran);
            if ($alb) {
                $this->codigo = $alb->codigo;
                $this->fecha = $alb->fecha;
                self::$albaranes[] = $alb;
            }
        }
    }

    public function pvp_iva()
    {
        return $this->pvpunitario * (100 + $this->iva) / 100;
    }

    public function total_iva()
    {
        return $this->pvptotal * (100 + $this->iva - $this->irpf + $this->recargo) / 100;
    }

    /// Devuelve el precio total por unidad (con descuento incluido e iva aplicado)
    public function total_iva2()
    {
        if ($this->cantidad == 0) {
            return 0;
        }

        return $this->pvptotal * (100 + $this->iva) / 100 / $this->cantidad;
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

    public function show_nombre()
    {
        $nombre = 'desconocido';

        foreach (self::$albaranes as $a) {
            if ($a->idalbaran == $this->idalbaran) {
                $nombre = $a->nombre;
                break;
            }
        }

        return $nombre;
    }

    public function url()
    {
        return 'index.php?page=compras_albaran&id=' . $this->idalbaran;
    }

    public function articulo_url()
    {
        if (is_null($this->referencia) || $this->referencia == '') {
            return "index.php?page=ventas_articulos";
        }

        return "index.php?page=ventas_articulo&ref=" . urlencode($this->referencia);
    }

    /**
     * Devuelve los datos de una linea
     * @param integer $idlinea
     * @return boolean|\linea_albaran_proveedor
     */
    public function get($idlinea)
    {
        $data = $this->db->select("SELECT * FROM " . $this->table_name . " WHERE idlinea = " . $this->var2str($idlinea) . ";");
        if ($data) {
            return new \linea_albaran_proveedor($data[0]);
        }

        return FALSE;
    }

    public function exists()
    {
        if (is_null($this->idlinea)) {
            return false;
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
                . " del " . FS_ALBARAN . ". Valor correcto: " . $total);
            return FALSE;
        } else if (!$this->floatcmp($this->pvpsindto, $totalsindto, FS_NF0, TRUE)) {
            $this->new_error_msg("Error en el valor de pvpsindto de la línea " . $this->referencia
                . " del " . FS_ALBARAN . ". Valor correcto: " . $totalsindto);
            return FALSE;
        }

        return TRUE;
    }

    public function save()
    {
        if ($this->test()) {
            $this->clean_cache();

            if ($this->exists()) {
                $sql = "UPDATE " . $this->table_name . " SET idalbaran = " . $this->var2str($this->idalbaran)
                    . ", idpedido = " . $this->var2str($this->idpedido)
                    . ", idlineapedido = " . $this->var2str($this->idlineapedido)
                    . ", referencia = " . $this->var2str($this->referencia)
                    . ", codcombinacion = " . $this->var2str($this->codcombinacion)
                    . ", descripcion = " . $this->var2str($this->descripcion)
                    . ", cantidad = " . $this->var2str($this->cantidad)
                    . ", dtopor = " . $this->var2str($this->dtopor)
                    . ", codimpuesto = " . $this->var2str($this->codimpuesto)
                    . ", iva = " . $this->var2str($this->iva)
                    . ", pvptotal = " . $this->var2str($this->pvptotal)
                    . ", pvpsindto = " . $this->var2str($this->pvpsindto)
                    . ", pvpunitario = " . $this->var2str($this->pvpunitario)
                    . ", irpf = " . $this->var2str($this->irpf)
                    . ", recargo = " . $this->var2str($this->recargo)
                    . "  WHERE idlinea = " . $this->var2str($this->idlinea) . ";";

                return $this->db->exec($sql);
            }

            $sql = "INSERT INTO " . $this->table_name . " (idlineapedido,idalbaran,idpedido,referencia,codcombinacion,
               descripcion,cantidad,dtopor,codimpuesto,iva,pvptotal,pvpsindto,pvpunitario,irpf,recargo) VALUES
                     (" . $this->var2str($this->idlineapedido) .
                "," . $this->var2str($this->idalbaran) .
                "," . $this->var2str($this->idpedido) .
                "," . $this->var2str($this->referencia) .
                "," . $this->var2str($this->codcombinacion) .
                "," . $this->var2str($this->descripcion) .
                "," . $this->var2str($this->cantidad) .
                "," . $this->var2str($this->dtopor) .
                "," . $this->var2str($this->codimpuesto) .
                "," . $this->var2str($this->iva) .
                "," . $this->var2str($this->pvptotal) .
                "," . $this->var2str($this->pvpsindto) .
                "," . $this->var2str($this->pvpunitario) .
                "," . $this->var2str($this->irpf) .
                "," . $this->var2str($this->recargo) . ");";

            if ($this->db->exec($sql)) {
                $this->idlinea = $this->db->lastval();
                return TRUE;
            }
        }

        return FALSE;
    }

    public function delete()
    {
        $this->clean_cache();
        return $this->db->exec("DELETE FROM " . $this->table_name . " WHERE idlinea = " . $this->var2str($this->idlinea) . ";");
    }

    public function clean_cache()
    {
        $this->cache->delete('albpro_top_articulos');
    }

    public function all_from_albaran($id)
    {
        $linealist = array();
        $sql = "SELECT * FROM " . $this->table_name . " WHERE idalbaran = " . $this->var2str($id)
            . " ORDER BY idlinea ASC;";

        $data = $this->db->select($sql);
        if ($data) {
            foreach ($data as $l) {
                $linealist[] = new \linea_albaran_proveedor($l);
            }
        }

        return $linealist;
    }

    private function all_from($sql, $offset = 0, $limit = FS_ITEM_LIMIT)
    {
        $linealist = array();
        $data = $this->db->select_limit($sql, $limit, $offset);
        if ($data) {
            foreach ($data as $l) {
                $linealist[] = new \linea_albaran_proveedor($l);
            }
        }

        return $linealist;
    }

    public function all_from_articulo($ref, $offset = 0, $limit = FS_ITEM_LIMIT)
    {
        $sql = "SELECT * FROM " . $this->table_name . " WHERE referencia = " . $this->var2str($ref) . " ORDER BY idalbaran DESC";
        return $this->all_from($sql, $offset, $limit);
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
        $sql .= " ORDER BY idalbaran DESC, idlinea ASC";

        return $this->all_from($sql, $offset);
    }

    public function search_from_proveedor($codproveedor, $query = '', $offset = 0)
    {
        $query = mb_strtolower($this->no_html($query), 'UTF8');

        $sql = "SELECT * FROM " . $this->table_name . " WHERE idalbaran IN
         (SELECT idalbaran FROM albaranesprov WHERE codproveedor = " . $this->var2str($codproveedor) . ") AND ";
        if (is_numeric($query)) {
            $sql .= "(referencia LIKE '%" . $query . "%' OR descripcion LIKE '%" . $query . "%')";
        } else {
            $buscar = str_replace(' ', '%', $query);
            $sql .= "(lower(referencia) LIKE '%" . $buscar . "%' OR lower(descripcion) LIKE '%" . $buscar . "%')";
        }
        $sql .= " ORDER BY idalbaran DESC, idlinea ASC";

        return $this->all_from($sql, $offset);
    }

    public function count_by_articulo()
    {
        $lineas = $this->db->select("SELECT COUNT(DISTINCT referencia) as total FROM " . $this->table_name . ";");
        if ($lineas) {
            return intval($lineas[0]['total']);
        }

        return 0;
    }
}
