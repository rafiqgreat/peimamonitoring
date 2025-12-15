<?php
defined('BASEPATH') or exit('No direct script access allowed');

class School_visit_photos_model extends MY_Model
{

	public $table = 'school_visit_photos';

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

	public function delete_by_visit_and_key($visit_id, $checklist_key)
	{
		$this->db->where('visit_id', $visit_id);
		$this->db->where('checklist_key', $checklist_key);
		$this->db->delete($this->table);
		return true;
	}
}

/* End of file School_visit_photos_model.php */
/* Location: ./application/models/School_visit_photos_model.php */
