<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Districts_model extends MY_Model {

	public $table = 'districts';

	public $table_key = 'district_id';

	public function __construct()
	{
		parent::__construct();
	}

	public function update($id, $data)
	{
		$this->db->where($this->table_key, $id);
		$this->db->update($this->table, $data);
		return $id;
	}

	public function delete($id)
	{
		$this->db->where($this->table_key, $id);
		$this->db->delete($this->table);
		return true;
	}

}

/* End of file Districts_model.php */
/* Location: ./application/models/Districts_model.php */
