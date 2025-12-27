<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Districts extends MY_Controller
{

	private $permissions = [
		'districts_list' => 'Districts List',
		'districts_add' => 'Districts Add',
		'districts_edit' => 'Districts Edit',
		'districts_delete' => 'Districts Delete',
	];

	public function __construct()
	{
		parent::__construct();


		$this->load->model('districts_model');

		$this->page_data['page']->title = 'Districts Management';
		$this->page_data['page']->menu = 'locations';
		$this->page_data['page']->submenu = 'districts';

		$this->ensure_permissions();
	}

	private function ensure_permissions()
	{
		foreach ($this->permissions as $code => $title) {
			$permission = $this->permissions_model->getByWhere(['code' => $code]);
			if (empty($permission)) {
				$this->permissions_model->create([
					'title' => $title,
					'code' => $code,
				]);
			}

			if (logged('role') == 1 && empty($this->role_permissions_model->getByWhere(['role' => 1, 'permission' => $code]))) {
				$this->role_permissions_model->create([
					'role' => 1,
					'permission' => $code,
				]);
			}
		}
	}

	private function authorize($permission)
	{
		if (logged('role') == 1) {
			return true;
		}

		ifPermissions($permission);
	}

	public function index()
	{
		$this->authorize('districts_list');

		$this->db->order_by('district_sort', 'asc');
		$this->db->order_by('district_name_en', 'asc');
		$this->page_data['districts'] = $this->districts_model->get();

		$this->load->view('districts/list', $this->page_data);
	}

	public function add()
	{
		$this->authorize('districts_add');

		$this->load->view('districts/add', $this->page_data);
	}

	public function save()
	{
		postAllowed();
		$this->authorize('districts_add');

		$data = [
			'district_code' => post('district_code'),
			'district_name_ur' => post('district_name_ur'),
			'district_name_en' => post('district_name_en'),
			'district_sort' => post('district_sort') !== false ? (int) post('district_sort') : 0,
			'district_status' => post('district_status') ? 1 : 0,
			'district_created' => date('Y-m-d H:i:s'),
			'district_createdby' => logged('id'),
			'district_updated' => date('Y-m-d H:i:s'),
			'district_updatedby' => logged('id'),
			'district_state_id' => post('district_state_id') !== false && post('district_state_id') !== '' ? (int) post('district_state_id') : null,
		];

		$id = $this->districts_model->create($data);

		$this->activity_model->add("District #$id Created by User: #" . logged('id'), logged('id'));

		$this->session->set_flashdata('alert-type', 'success');
		$this->session->set_flashdata('alert', 'District Created Successfully');

		redirect('districts');
	}

	public function edit($id)
	{
		$this->authorize('districts_edit');

		$district = $this->districts_model->getById($id);

		if (empty($district)) {
			$this->session->set_flashdata('alert-type', 'danger');
			$this->session->set_flashdata('alert', 'District not found');
			redirect('districts');
			return;
		}

		$this->page_data['district'] = $district;

		$this->load->view('districts/edit', $this->page_data);
	}

	public function update($id)
	{
		postAllowed();
		$this->authorize('districts_edit');

		$district = $this->districts_model->getById($id);

		if (empty($district)) {
			$this->session->set_flashdata('alert-type', 'danger');
			$this->session->set_flashdata('alert', 'District not found');
			redirect('districts');
			return;
		}

		$data = [
			'district_code' => post('district_code'),
			'district_name_ur' => post('district_name_ur'),
			'district_name_en' => post('district_name_en'),
			'district_sort' => post('district_sort') !== false ? (int) post('district_sort') : 0,
			'district_status' => post('district_status') ? 1 : 0,
			'district_updated' => date('Y-m-d H:i:s'),
			'district_updatedby' => logged('id'),
			'district_state_id' => post('district_state_id') !== false && post('district_state_id') !== '' ? (int) post('district_state_id') : null,
		];

		$this->districts_model->update($id, $data);

		$this->activity_model->add("District #$id Updated by User: #" . logged('id'), logged('id'));

		$this->session->set_flashdata('alert-type', 'success');
		$this->session->set_flashdata('alert', 'District Updated Successfully');

		redirect('districts');
	}

	public function delete($id)
	{
		$this->authorize('districts_delete');

		$district = $this->districts_model->getById($id);

		if (empty($district)) {
			$this->session->set_flashdata('alert-type', 'danger');
			$this->session->set_flashdata('alert', 'District not found');
			redirect('districts');
			return;
		}

		$this->districts_model->delete($id);

		$this->activity_model->add("District #$id Deleted by User: #" . logged('id'), logged('id'));

		$this->session->set_flashdata('alert-type', 'success');
		$this->session->set_flashdata('alert', 'District Deleted Successfully');

		redirect('districts');
	}

	/**
	 * District-level summary of school visit reports.
	 * Admin only (role 1).
	 */
	public function report_summary()
	{
		if ((int) logged('role') !== 1) {
			show_error('Not allowed', 403);
			return;
		}

		// Ensure required tables exist.
		$has_districts = $this->db->table_exists('districts');
		$has_schools = $this->db->table_exists('schools');
		$has_visits = $this->db->table_exists('school_visit_reports');

		if (!$has_districts || !$has_schools) {
			$this->page_data['summary'] = [];
			$this->page_data['totals'] = [
				'total_schools' => 0,
				'visited_schools' => 0,
				'reports_received' => 0,
				'pending_schools' => 0,
			];
			$this->load->view('districts/report_summary', $this->page_data);
			return;
		}

		$this->db->select('districts.district_id, districts.district_name_en');
		$this->db->select('COUNT(DISTINCT schools.school_id) as total_schools', false);
		$has_dangerous_flag = $has_visits && $this->db->field_exists('dangerous_exists', 'school_visit_reports');
		$has_flood_flag = $has_visits && $this->db->field_exists('flood_exists', 'school_visit_reports');

		if ($has_visits) {
			$this->db->select('COUNT(school_visit_reports.id) as reports_received', false);
			if ($has_dangerous_flag) {
				$this->db->select('COUNT(DISTINCT CASE WHEN school_visit_reports.dangerous_exists = 1 THEN school_visit_reports.school_id END) as dangerous_schools', false);
			} else {
				$this->db->select('0 as dangerous_schools', false);
			}
			if ($has_flood_flag) {
				$this->db->select('COUNT(DISTINCT CASE WHEN school_visit_reports.flood_exists = 1 THEN school_visit_reports.school_id END) as flood_schools', false);
			} else {
				$this->db->select('0 as flood_schools', false);
			}
		} else {
			$this->db->select('0 as reports_received', false);
			$this->db->select('0 as dangerous_schools', false);
			$this->db->select('0 as flood_schools', false);
		}
		$this->db->from('districts');
		$this->db->join('schools', 'schools.school_district_id = districts.district_id', 'left');
		if ($has_visits) {
			$this->db->join('school_visit_reports', 'school_visit_reports.school_id = schools.school_id', 'left');
		}
		$this->db->group_by('districts.district_id, districts.district_name_en');
		$this->db->order_by('districts.district_sort', 'asc');
		$this->db->order_by('districts.district_name_en', 'asc');

		$db_debug = $this->db->db_debug;
		$this->db->db_debug = false;
		$query = $this->db->get();
		$error = $this->db->error();
		$this->db->db_debug = $db_debug;

		$summary = [];
		$totals = [
			'total_schools' => 0,
			'reports_received' => 0,
			'pending_schools' => 0,
			'dangerous_schools' => 0,
			'flood_schools' => 0,
		];

		if (empty($error['message']) && $query) {
			foreach ($query->result() as $row) {
				$visited_schools = isset($row->reports_received) && $row->reports_received > 0 ? (int) $row->reports_received : 0;
				$pending = ((int) $row->total_schools) - $visited_schools;
				$pending = $pending > 0 ? $pending : 0;
				$summary[] = [
					'district_name' => $row->district_name_en ?: 'Unknown',
					'total_schools' => (int) $row->total_schools,
					'reports_received' => (int) $row->reports_received,
					'dangerous_schools' => isset($row->dangerous_schools) ? (int) $row->dangerous_schools : 0,
					'flood_schools' => isset($row->flood_schools) ? (int) $row->flood_schools : 0,
					'pending_schools' => $pending,
				];

				$totals['total_schools'] += (int) $row->total_schools;
				$totals['reports_received'] += (int) $row->reports_received;
				$totals['dangerous_schools'] += isset($row->dangerous_schools) ? (int) $row->dangerous_schools : 0;
				$totals['flood_schools'] += isset($row->flood_schools) ? (int) $row->flood_schools : 0;
				$totals['pending_schools'] += $pending;
			}
		}

		$this->page_data['summary'] = $summary;
		$this->page_data['totals'] = $totals;
		$this->load->view('districts/report_summary', $this->page_data);
	}
}

/* End of file Districts.php */
/* Location: ./application/controllers/Districts.php */
