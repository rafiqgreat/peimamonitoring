<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Schools extends MY_Controller {

	private $permissions = [
		'schools_list' => 'Schools List',
		'schools_add' => 'Schools Add',
		'schools_edit' => 'Schools Edit',
		'schools_delete' => 'Schools Delete',
		'schools_view' => 'Schools View',
	];

	private $levels = ['Primary','Elementary 6th','Elementary 7th','Elementary 8th','High','Higher Secondary'];
	private $genders = ['Male','Female','Both'];
	private $statuses = ['Open','Closed'];
	private $categories = ['Young Entrepreneur','Individual','ED-Tech Firm','Education Chain','NGOs/CSOs','Other'];

	public function __construct()
	{
		parent::__construct();

		$this->load->model('schools_model');
		$this->load->model('districts_model');
		$this->load->model('tehsils_model');

		$this->page_data['page']->title = 'Schools Management';
		$this->page_data['page']->menu = 'schools';
		$this->page_data['page']->submenu = 'schools';

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

	private function loadDistricts()
	{
		$this->db->order_by('district_sort', 'asc');
		$this->db->order_by('district_name_en', 'asc');
		return $this->districts_model->get();
	}

	private function loadTehsils()
	{
		$this->db->order_by('tehsil_order', 'asc');
		$this->db->order_by('tehsil_name_en', 'asc');
		return $this->tehsils_model->get();
	}

	private function validatedEnum($value, $allowed, $default)
	{
		return in_array($value, $allowed, true) ? $value : $default;
	}

	public function index()
	{
		$this->authorize('schools_list');

		$this->db->select('schools.*, districts.district_name_en, tehsils.tehsil_name_en');
		$this->db->from('schools');
		$this->db->join('districts', 'districts.district_id = schools.school_district_id', 'left');
		$this->db->join('tehsils', 'tehsils.tehsil_id = schools.school_tehsil_id', 'left');
		$this->db->order_by('school_name', 'asc');
		$this->page_data['schools'] = $this->db->get()->result();

		$this->load->view('schools/list', $this->page_data);
	}

	public function add()
	{
		$this->authorize('schools_add');

		$this->page_data['districts'] = $this->loadDistricts();
		$this->page_data['tehsils'] = $this->loadTehsils();
		$this->page_data['levels'] = $this->levels;
		$this->page_data['genders'] = $this->genders;
		$this->page_data['statuses'] = $this->statuses;
		$this->page_data['categories'] = $this->categories;

		$this->load->view('schools/add', $this->page_data);
	}

	public function save()
	{
		postAllowed();
		$this->authorize('schools_add');

		$level = $this->validatedEnum(post('school_level'), $this->levels, 'Primary');
		$gender = $this->validatedEnum(post('school_gender'), $this->genders, 'Male');
		$status = $this->validatedEnum(post('school_status'), $this->statuses, 'Open');
		$category = $this->validatedEnum(post('school_allocated_category'), $this->categories, 'Young Entrepreneur');

		$data = [
			'school_code' => post('school_code'),
			'school_name' => post('school_name'),
			'school_address' => post('school_address'),
			'school_district_id' => post('school_district_id') !== '' ? (int) post('school_district_id') : null,
			'school_tehsil_id' => post('school_tehsil_id') !== '' ? (int) post('school_tehsil_id') : null,
			'school_level' => $level,
			'school_gender' => $gender,
			'school_email' => post('school_email'),
			'school_mobile' => post('school_mobile'),
			'school_whatapp' => post('school_whatapp'),
			'school_lat' => post('school_lat') !== '' ? post('school_lat') : null,
			'school_long' => post('school_long') !== '' ? post('school_long') : null,
			'school_contact_name' => post('school_contact_name'),
			'school_contact_mobile' => post('school_contact_mobile'),
			'school_status' => $status,
			'school_total_students' => post('school_total_students') !== '' ? (int) post('school_total_students') : null,
			'school_total_teachers' => post('school_total_teachers') !== '' ? (int) post('school_total_teachers') : null,
			'school_remarks' => post('school_remarks'),
			'school_allocated_category' => $category,
			'school_others' => post('school_others'),
			'school_license_name' => post('school_license_name'),
			'school_license_mobile' => post('school_license_mobile'),
			'school_license_email' => post('school_license_email'),
			'school_license_orgname' => post('school_license_orgname'),
		];

		$id = $this->schools_model->create($data);

		$this->activity_model->add("School #$id Created by User: #".logged('id'), logged('id'));

		$this->session->set_flashdata('alert-type', 'success');
		$this->session->set_flashdata('alert', 'School Created Successfully');
		
		redirect('schools');
	}

	public function edit($id)
	{
		$this->authorize('schools_edit');

		$school = $this->schools_model->getById($id);

		if (empty($school)) {
			$this->session->set_flashdata('alert-type', 'danger');
			$this->session->set_flashdata('alert', 'School not found');
			redirect('schools');
			return;
		}

		$this->page_data['school'] = $school;
		$this->page_data['districts'] = $this->loadDistricts();
		$this->page_data['tehsils'] = $this->loadTehsils();
		$this->page_data['levels'] = $this->levels;
		$this->page_data['genders'] = $this->genders;
		$this->page_data['statuses'] = $this->statuses;
		$this->page_data['categories'] = $this->categories;

		$this->load->view('schools/edit', $this->page_data);
	}

	public function update($id)
	{
		postAllowed();
		$this->authorize('schools_edit');

		$school = $this->schools_model->getById($id);

		if (empty($school)) {
			$this->session->set_flashdata('alert-type', 'danger');
			$this->session->set_flashdata('alert', 'School not found');
			redirect('schools');
			return;
		}

		$level = $this->validatedEnum(post('school_level'), $this->levels, $school->school_level);
		$gender = $this->validatedEnum(post('school_gender'), $this->genders, $school->school_gender);
		$status = $this->validatedEnum(post('school_status'), $this->statuses, $school->school_status);
		$category = $this->validatedEnum(post('school_allocated_category'), $this->categories, $school->school_allocated_category);

		$data = [
			'school_code' => post('school_code'),
			'school_name' => post('school_name'),
			'school_address' => post('school_address'),
			'school_district_id' => post('school_district_id') !== '' ? (int) post('school_district_id') : null,
			'school_tehsil_id' => post('school_tehsil_id') !== '' ? (int) post('school_tehsil_id') : null,
			'school_level' => $level,
			'school_gender' => $gender,
			'school_email' => post('school_email'),
			'school_mobile' => post('school_mobile'),
			'school_whatapp' => post('school_whatapp'),
			'school_lat' => post('school_lat') !== '' ? post('school_lat') : null,
			'school_long' => post('school_long') !== '' ? post('school_long') : null,
			'school_contact_name' => post('school_contact_name'),
			'school_contact_mobile' => post('school_contact_mobile'),
			'school_status' => $status,
			'school_total_students' => post('school_total_students') !== '' ? (int) post('school_total_students') : null,
			'school_total_teachers' => post('school_total_teachers') !== '' ? (int) post('school_total_teachers') : null,
			'school_remarks' => post('school_remarks'),
			'school_allocated_category' => $category,
			'school_others' => post('school_others'),
			'school_license_name' => post('school_license_name'),
			'school_license_mobile' => post('school_license_mobile'),
			'school_license_email' => post('school_license_email'),
			'school_license_orgname' => post('school_license_orgname'),
		];

		$this->schools_model->update($id, $data);

		$this->activity_model->add("School #$id Updated by User: #".logged('id'), logged('id'));

		$this->session->set_flashdata('alert-type', 'success');
		$this->session->set_flashdata('alert', 'School Updated Successfully');
		
		redirect('schools');
	}

	public function view($id)
	{
		$this->authorize('schools_view');

		$this->db->select('schools.*, districts.district_name_en, tehsils.tehsil_name_en');
		$this->db->from('schools');
		$this->db->join('districts', 'districts.district_id = schools.school_district_id', 'left');
		$this->db->join('tehsils', 'tehsils.tehsil_id = schools.school_tehsil_id', 'left');
		$this->db->where('schools.school_id', $id);
		$school = $this->db->get()->row();

		if (empty($school)) {
			$this->session->set_flashdata('alert-type', 'danger');
			$this->session->set_flashdata('alert', 'School not found');
			redirect('schools');
			return;
		}

		$this->page_data['school'] = $school;
		$this->load->view('schools/view', $this->page_data);
	}

	public function delete($id)
	{
		$this->authorize('schools_delete');

		$school = $this->schools_model->getById($id);

		if (empty($school)) {
			$this->session->set_flashdata('alert-type', 'danger');
			$this->session->set_flashdata('alert', 'School not found');
			redirect('schools');
			return;
		}

		$this->schools_model->delete($id);

		$this->activity_model->add("School #$id Deleted by User: #".logged('id'), logged('id'));

		$this->session->set_flashdata('alert-type', 'success');
		$this->session->set_flashdata('alert', 'School Deleted Successfully');
		
		redirect('schools');
	}

}

/* End of file Schools.php */
/* Location: ./application/controllers/Schools.php */
