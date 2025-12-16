<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class School_visit_dangerous_photos_model extends MY_Model {

	public $table = 'school_visit_dangerous_photos';

	public $table_key = 'id';

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

	public function delete_by_visit($visit_id)
	{
		$this->db->where('visit_id', $visit_id);
		$this->db->delete($this->table);
		return true;
	}
}

/* End of file School_visit_dangerous_photos_model.php */
/* Location: ./application/models/School_visit_dangerous_photos_model.php */
