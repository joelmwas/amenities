<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by Britone Mwasaru.
 * Date: 1/28/15
 * Time: 11:31 AM
 */

class Services_model extends CI_Model {
    public $table = 'services';

    public function get_all()
    {
        return $this->db->get($this->table)->result();
    }

    public function get($id)
    {
        return $this->db->where('id', $id)->get($this->table)->row();
    }

    public function add($data)
    {
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }

    public function update($id, $data)
    {
        return $this->db->where('id', $id)->update($this->table, $data);
    }

    public function delete($id)
    {
        return $this->db->where('id', $id)->delete($this->table);
        return $this->db->affected_rows();
    }
}