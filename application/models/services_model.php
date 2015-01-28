<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by Britone Mwasaru.
 * Date: 1/28/15
 * Time: 11:31 AM
 */

class Services_model extends CI_Model {

    public function __construct()
    {
        $this->load->database();
    }

    public $table = 'services';

    public function get_all_services()
    {
        return $this->db->get($this->table)->result();
    }

    public function get_service($id)
    {
        return $this->db->where('id', $id)->get($this->table)->row();
    }

    public function add_service($data)
    {
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }

    public function update_service($id, $data)
    {
        return $this->db->where('id', $id)->update($this->table, $data);
    }

    public function delete_service($id)
    {
        return $this->db->where('id', $id)->delete($this->table);
        return $this->db->affected_rows();
    }

    public function count_services($search_string=null, $order=null)
    {
        $this->db->select('*');
        $this->db->from('services');

        if ($search_string) {
            $this->db->like('description', $search_string);
        }

        if ($order) {
            $this->db->order_by($order, 'Asc');
        } else {
            $this->db->order_by($order, 'Desc');
        }

        $query = $this->db->get();
        return $query->num_rows();
    }

    /* for the admin */
    public function get_services($search_string=null, $order=null, $order_type='Asc', $limit_start, $limit_end)
    {
        $this->db->select('services.id');
        $this->db->select('services.name');
        $this->db->select('services.description');
        $this->db->select('services.lat');
        $this->db->select('services.long');
        $this->db->from('services');

        if($search_string){
            $this->db->like('description', $search_string);
        }

        $this->db->group_by('services.id');

        if($order){
            $this->db->order_by($order, $order_type);
        }else{
            $this->db->order_by('id', $order_type);
        }


        $this->db->limit($limit_start, $limit_end);
        //$this->db->limit('4', '4');

        $query = $this->db->get();
        return $query->result_array();
    }

}