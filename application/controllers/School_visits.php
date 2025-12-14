<?php
defined('BASEPATH') or exit('No direct script access allowed');

class School_visits extends MY_Controller
{

	private $permissions = [
		'school_visits_list' => 'School Visits List',
		'school_visits_add' => 'School Visits Add',
		'school_visits_edit' => 'School Visits Edit',
		'school_visits_delete' => 'School Visits Delete',
		'school_visits_view' => 'School Visits View',
	];

	private $status_options = ['Open', 'Closed'];

	public function __construct()
	{
		parent::__construct();

		$this->load->model('school_visit_reports_model');
		$this->load->model('schools_model');

		$this->page_data['page']->title = 'School Visit Reports';
		$this->page_data['page']->menu = 'school_visits';
		$this->page_data['page']->submenu = 'school_visits';

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

	private function allowed_schools()
	{
		if ((int) logged('role') === 3 && !empty(logged('school_id'))) {
			return $this->schools_model->getByWhere(['school_id' => logged('school_id')]);
		}

		return $this->schools_model->get();
	}

	private function find_allowed_school($school_id)
	{
		if ((int) logged('role') === 3) {
			return ((int) logged('school_id') === (int) $school_id);
		}
		return true;
	}

	public function index()
	{
		$this->authorize('school_visits_list');

		$this->db->select('school_visit_reports.*, schools.school_name, users.name as visitor_name');
		$this->db->from('school_visit_reports');
		$this->db->join('schools', 'schools.school_id = school_visit_reports.school_id', 'left');
		$this->db->join('users', 'users.id = school_visit_reports.visited_by', 'left');

		/*
		if ((int) logged('role') === 3 && !empty(logged('school_id'))) {
			$this->db->where('school_visit_reports.school_id', (int) logged('school_id'));
		}
*/
		$this->db->order_by('visit_date', 'desc');
		$this->page_data['visits'] = $this->db->get()->result();

		$this->load->view('school_visits/list', $this->page_data);
	}

	public function add()
	{
		$this->authorize('school_visits_add');

		$this->page_data['schools'] = $this->allowed_schools();
		$this->page_data['status_options'] = $this->status_options;

		$this->load->view('school_visits/add', $this->page_data);
	}

	private function validated_status($status)
	{
		return in_array($status, $this->status_options, true) ? $status : 'Open';
	}

	public function save()
	{
		postAllowed();
		$this->authorize('school_visits_add');

		$school_id = post('school_id');

		if ((int) logged('role') === 3 && !empty(logged('school_id'))) {
			$school_id = logged('school_id');
		}

		if (empty($school_id) || !$this->find_allowed_school($school_id)) {
			$this->session->set_flashdata('alert-type', 'danger');
			$this->session->set_flashdata('alert', 'Invalid school selection');
			redirect('school_visits');
			return;
		}

		$data = [
			'school_id' => (int) $school_id,
			'visit_date' => post('visit_date') ? date('Y-m-d', strtotime(post('visit_date'))) : date('Y-m-d'),
			'visited_by' => logged('id'),
			'is_open' => $this->validated_status(post('is_open')),
			'main_gate_condition' => post('main_gate_condition') ? 1 : 0,
			'classrooms_count' => post('classrooms_count') !== '' ? (int) post('classrooms_count') : null,
			'washrooms_count' => post('washrooms_count') !== '' ? (int) post('washrooms_count') : null,
			'teachers_count' => post('teachers_count') !== '' ? (int) post('teachers_count') : null,
			'students_by_class' => post('students_by_class'),
			'remarks' => post('remarks'),
			'created_at' => date('Y-m-d H:i:s'),
			'updated_at' => date('Y-m-d H:i:s'),
		];

		$id = $this->school_visit_reports_model->create($data);

		$this->activity_model->add("School visit #$id Created by User: #" . logged('id'), logged('id'));

		$this->session->set_flashdata('alert-type', 'success');
		$this->session->set_flashdata('alert', 'School visit report created successfully');

		redirect('school_visits');
	}

	public function edit($id)
	{
		$this->authorize('school_visits_edit');

		$visit = $this->school_visit_reports_model->getById($id);

		if (empty($visit) || !$this->find_allowed_school($visit->school_id)) {
			$this->session->set_flashdata('alert-type', 'danger');
			$this->session->set_flashdata('alert', 'Visit report not found or not allowed');
			redirect('school_visits');
			return;
		}

		$this->page_data['visit'] = $visit;
		$this->page_data['schools'] = $this->allowed_schools();
		$this->page_data['status_options'] = $this->status_options;

		$this->load->view('school_visits/edit', $this->page_data);
	}

	public function update($id)
	{
		postAllowed();
		$this->authorize('school_visits_edit');

		$visit = $this->school_visit_reports_model->getById($id);

		if (empty($visit) || !$this->find_allowed_school($visit->school_id)) {
			$this->session->set_flashdata('alert-type', 'danger');
			$this->session->set_flashdata('alert', 'Visit report not found or not allowed');
			redirect('school_visits');
			return;
		}

		$school_id = post('school_id');
		if ((int) logged('role') === 3 && !empty(logged('school_id'))) {
			$school_id = logged('school_id');
		}

		if (empty($school_id) || !$this->find_allowed_school($school_id)) {
			$this->session->set_flashdata('alert-type', 'danger');
			$this->session->set_flashdata('alert', 'Invalid school selection');
			redirect('school_visits');
			return;
		}

		$data = [
			'school_id' => (int) $school_id,
			'visit_date' => post('visit_date') ? date('Y-m-d', strtotime(post('visit_date'))) : date('Y-m-d'),
			'is_open' => $this->validated_status(post('is_open')),
			'main_gate_condition' => post('main_gate_condition') ? 1 : 0,
			'classrooms_count' => post('classrooms_count') !== '' ? (int) post('classrooms_count') : null,
			'washrooms_count' => post('washrooms_count') !== '' ? (int) post('washrooms_count') : null,
			'teachers_count' => post('teachers_count') !== '' ? (int) post('teachers_count') : null,
			'students_by_class' => post('students_by_class'),
			'remarks' => post('remarks'),
			'updated_at' => date('Y-m-d H:i:s'),
		];

		$this->school_visit_reports_model->update($id, $data);

		$this->activity_model->add("School visit #$id Updated by User: #" . logged('id'), logged('id'));

		$this->session->set_flashdata('alert-type', 'success');
		$this->session->set_flashdata('alert', 'School visit report updated successfully');

		redirect('school_visits');
	}

	public function view($id)
	{
		$this->authorize('school_visits_view');

		$this->db->select('school_visit_reports.*, schools.school_name, users.name as visitor_name');
		$this->db->from('school_visit_reports');
		$this->db->join('schools', 'schools.school_id = school_visit_reports.school_id', 'left');
		$this->db->join('users', 'users.id = school_visit_reports.visited_by', 'left');
		$this->db->where('school_visit_reports.id', $id);
		/*
		if ((int) logged('role') === 3 && !empty(logged('school_id'))) {
			$this->db->where('school_visit_reports.school_id', (int) logged('school_id'));
		}
*/
		$visit = $this->db->get()->row();

		if (empty($visit)) {
			$this->session->set_flashdata('alert-type', 'danger');
			$this->session->set_flashdata('alert', 'Visit report not found or not allowed');
			redirect('school_visits');
			return;
		}

		$this->page_data['visit'] = $visit;
		$this->load->view('school_visits/view', $this->page_data);
	}

	public function delete($id)
	{
		$this->authorize('school_visits_delete');

		$visit = $this->school_visit_reports_model->getById($id);

		if (empty($visit) || !$this->find_allowed_school($visit->school_id)) {
			$this->session->set_flashdata('alert-type', 'danger');
			$this->session->set_flashdata('alert', 'Visit report not found or not allowed');
			redirect('school_visits');
			return;
		}

		$this->school_visit_reports_model->delete($id);

		$this->activity_model->add("School visit #$id Deleted by User: #" . logged('id'), logged('id'));

		$this->session->set_flashdata('alert-type', 'success');
		$this->session->set_flashdata('alert', 'School visit report deleted successfully');

		redirect('school_visits');
	}
}

/* End of file School_visits.php */
/* Location: ./application/controllers/School_visits.php */
