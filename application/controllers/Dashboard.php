<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('schools_model');
		$this->load->model('districts_model');
		$this->load->model('tehsils_model');
	}

	private function school_info_for_role3()
	{
		if ((int) logged('role') !== 3 || empty(logged('school_id'))) {
			return null;
		}

		$has_districts = $this->db->table_exists('districts');
		$has_tehsils = $this->db->table_exists('tehsils');

		$select = [
			'schools.school_id',
			'schools.school_code',
			'schools.school_name',
			'schools.school_license_name',
			'schools.school_license_orgname',
		];
		$select[] = $has_districts ? 'districts.district_name_en' : 'NULL as district_name_en';
		$select[] = $has_tehsils ? 'tehsils.tehsil_name_en' : 'NULL as tehsil_name_en';

		$this->db->select(implode(', ', $select));
		$this->db->from('schools');
		if ($has_districts) {
			$this->db->join('districts', 'districts.district_id = schools.school_district_id', 'left');
		}
		if ($has_tehsils) {
			$this->db->join('tehsils', 'tehsils.tehsil_id = schools.school_tehsil_id', 'left');
		}
		$this->db->where('schools.school_id', (int) logged('school_id'));

		$db_debug = $this->db->db_debug;
		$this->db->db_debug = false;
		$query = $this->db->get();
		$this->db->db_debug = $db_debug;

		if ($query) {
			return $query->row();
		}

		// Fallback: fetch without joins if joins/tables missing.
		return $this->schools_model->getById((int) logged('school_id'));
	}

	public function index()
	{
		$this->page_data['school_info'] = $this->school_info_for_role3();
		$this->load->view('dashboard', $this->page_data);
	}

}

/* End of file Dashboard.php */
/* Location: ./application/controllers/Dashboard.php */
