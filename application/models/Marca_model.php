<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Marca_model extends CI_Model {

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }
    
    var $table_marcas = 'marca';
    var $column_order =  array('id_marca','marca_nombre','marca_estad',null); //set column field database for datatable orderable
    var $column_search = array('id_marca','marca_nombre','marca_estad'); //set column field database for datatable searchable just firstname , lastname , address are searchable
    var $order = array('marca_nombre' => 'ASC'); // default order

    private function _get_datatables_query()
    {
        $this->db->select('*');
        $this->db->from($this->table_marcas);
        $i = 0;
        foreach ($this->column_search as $item) // loop column
        {
            if($_POST['search']['value']) // if datatable send POST for search
            {

                if($i===0) // first loop
                {
                    $this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
                    $this->db->like($item, $_POST['search']['value']);
                }
                else
                {
                    $this->db->or_like($item, $_POST['search']['value']);
                }

                if(count($this->column_search) - 1 == $i) //last loop
                    $this->db->group_end(); //close bracket
            }
            $i++;

        }
        $this->db->where('id_empresa', $this->session->userdata('id_empresa'));
        if(isset($_POST['order'])) // here order processing
        {
            $this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        }
        else if(isset($this->order))
        {
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }

    function get_datatables()
    {
        $this->_get_datatables_query();
        if($_POST['length'] != -1)
        $this->db->limit($_POST['length'], $_POST['start']);
        //$this->db->group_by('clientes.id_cliente');
        $query = $this->db->get();
         //echo "<pre>"; print_r($this->db->last_query()); echo "</pre>";
        return $query->result();
    }

    function count_filtered()
    {
        $this->_get_datatables_query();
        //$this->db->group_by('clientes.id_cliente');
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function count_all()
    {
        $this->db->where('id_empresa', $this->session->userdata('id_empresa'));
        $this->db->from($this->table_marcas);
        return $this->db->count_all_results();
    }

    public function get_marca_by_id($id)
    {
        $this->db->from($this->table_marcas);
        $this->db->where('id_marca',$id);
        $query = $this->db->get();
        return $query->row();
    }

    public function get_marcas()
    {
        $this->db->from($this->table_marcas);
        $query = $this->db->get();
        return $query->result();
    }

    public function save($data)
    {
        $this->db->insert($this->table_marcas, $data);
        return $this->db->insert_id();
    }

    public function update($where, $data)
    {
        $this->db->update($this->table_marcas, $data, $where);
        //echo $this->db->last_query();
        return $this->db->affected_rows();
    }

    public function delete_by_id($id)
    {
        $this->db->where('id_marca', $id);
        $this->db->delete($this->table_marcas);
    }


}
