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
}

/* End of file Districts.php */
/* Location: ./application/controllers/Districts.php */
