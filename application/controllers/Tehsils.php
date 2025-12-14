<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Tehsils extends MY_Controller {

	private $permissions = [
		'tehsils_list' => 'Tehsils List',
		'tehsils_add' => 'Tehsils Add',
		'tehsils_edit' => 'Tehsils Edit',
		'tehsils_delete' => 'Tehsils Delete',
	];

	public function __construct()
	{
		parent::__construct();

		$this->load->model('tehsils_model');
		$this->load->model('districts_model');

		$this->page_data['page']->title = 'Tehsils Management';
		$this->page_data['page']->menu = 'locations';
		$this->page_data['page']->submenu = 'tehsils';

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

	public function index()
	{
		$this->authorize('tehsils_list');

		$this->db->select('tehsils.*, districts.district_name_en');
		$this->db->from('tehsils');
		$this->db->join('districts', 'districts.district_id = tehsils.tehsil_district_id', 'left');
		$this->db->order_by('tehsil_order', 'asc');
		$this->db->order_by('tehsil_name_en', 'asc');
		$this->page_data['tehsils'] = $this->db->get()->result();

		$this->load->view('tehsils/list', $this->page_data);
	}

	public function add()
	{
		$this->authorize('tehsils_add');

		$this->page_data['districts'] = $this->loadDistricts();

		$this->load->view('tehsils/add', $this->page_data);
	}

	public function save()
	{
		postAllowed();
		$this->authorize('tehsils_add');

		$districtId = post('tehsil_district_id') !== false ? (int) post('tehsil_district_id') : 0;

		$data = [
			'tehsil_code' => post('tehsil_code'),
			'tehsil_name_en' => post('tehsil_name_en'),
			'tehsil_name_ur' => post('tehsil_name_ur'),
			'tehsil_status' => post('tehsil_status') ? 1 : 0,
			'tehsil_state_id' => post('tehsil_state_id') !== false && post('tehsil_state_id') !== '' ? (int) post('tehsil_state_id') : null,
			'tehsil_district_id' => $districtId,
			'tehsil_order' => post('tehsil_order') !== false ? (int) post('tehsil_order') : 0,
			'tehsil_created' => date('Y-m-d H:i:s'),
			'tehsil_createdby' => logged('id'),
			'tehsil_updated' => date('Y-m-d H:i:s'),
			'tehsil_updatedby' => logged('id'),
		];

		$id = $this->tehsils_model->create($data);

		$this->activity_model->add("Tehsil #$id Created by User: #".logged('id'), logged('id'));

		$this->session->set_flashdata('alert-type', 'success');
		$this->session->set_flashdata('alert', 'Tehsil Created Successfully');
		
		redirect('tehsils');
	}

	public function edit($id)
	{
		$this->authorize('tehsils_edit');

		$tehsil = $this->tehsils_model->getById($id);

		if (empty($tehsil)) {
			$this->session->set_flashdata('alert-type', 'danger');
			$this->session->set_flashdata('alert', 'Tehsil not found');
			redirect('tehsils');
			return;
		}

		$this->page_data['tehsil'] = $tehsil;
		$this->page_data['districts'] = $this->loadDistricts();

		$this->load->view('tehsils/edit', $this->page_data);
	}

	public function update($id)
	{
		postAllowed();
		$this->authorize('tehsils_edit');

		$tehsil = $this->tehsils_model->getById($id);

		if (empty($tehsil)) {
			$this->session->set_flashdata('alert-type', 'danger');
			$this->session->set_flashdata('alert', 'Tehsil not found');
			redirect('tehsils');
			return;
		}

		$districtId = post('tehsil_district_id') !== false ? (int) post('tehsil_district_id') : 0;

		$data = [
			'tehsil_code' => post('tehsil_code'),
			'tehsil_name_en' => post('tehsil_name_en'),
			'tehsil_name_ur' => post('tehsil_name_ur'),
			'tehsil_status' => post('tehsil_status') ? 1 : 0,
			'tehsil_state_id' => post('tehsil_state_id') !== false && post('tehsil_state_id') !== '' ? (int) post('tehsil_state_id') : null,
			'tehsil_district_id' => $districtId,
			'tehsil_order' => post('tehsil_order') !== false ? (int) post('tehsil_order') : 0,
			'tehsil_updated' => date('Y-m-d H:i:s'),
			'tehsil_updatedby' => logged('id'),
		];

		$this->tehsils_model->update($id, $data);

		$this->activity_model->add("Tehsil #$id Updated by User: #".logged('id'), logged('id'));

		$this->session->set_flashdata('alert-type', 'success');
		$this->session->set_flashdata('alert', 'Tehsil Updated Successfully');
		
		redirect('tehsils');
	}

	public function delete($id)
	{
		$this->authorize('tehsils_delete');

		$tehsil = $this->tehsils_model->getById($id);

		if (empty($tehsil)) {
			$this->session->set_flashdata('alert-type', 'danger');
			$this->session->set_flashdata('alert', 'Tehsil not found');
			redirect('tehsils');
			return;
		}

		$this->tehsils_model->delete($id);

		$this->activity_model->add("Tehsil #$id Deleted by User: #".logged('id'), logged('id'));

		$this->session->set_flashdata('alert-type', 'success');
		$this->session->set_flashdata('alert', 'Tehsil Deleted Successfully');
		
		redirect('tehsils');
	}

}

/* End of file Tehsils.php */
/* Location: ./application/controllers/Tehsils.php */
