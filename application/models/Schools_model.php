<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Schools_model extends MY_Model {

	public $table = 'schools';

	public $table_key = 'school_id';

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

/* End of file Schools_model.php */
/* Location: ./application/models/Schools_model.php */
