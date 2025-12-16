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
	private $checklist_keys = [
		'boundary_wall_main_gate',
		'boundary_wall_main_gate_before',
		'boundary_wall_main_gate_after',
		'drinking_water_available',
		'drinking_water_available_before',
		'drinking_water_available_after',
		'washrooms_before',
		'washrooms_after',
		'washrooms_tiled_floors',
		'washrooms_tiled_floors_before',
		'washrooms_tiled_floors_after',
		'washrooms_handwashing_tap',
		'washrooms_handwashing_tap_before',
		'washrooms_handwashing_tap_after',
		'washrooms_soap_available',
		'washrooms_soap_available_before',
		'washrooms_soap_available_after',
		'washrooms_clean_daily',
		'washrooms_clean_daily_before',
		'washrooms_clean_daily_after',
		'classrooms_repaired_painted',
		'classrooms_repaired_painted_before',
		'classrooms_repaired_painted_after',
		'classrooms_board_available',
		'classrooms_board_available_before',
		'classrooms_board_available_after',
		'classrooms_ventilation_safety',
		'classrooms_ventilation_safety_before',
		'classrooms_ventilation_safety_after',
		'classrooms_electricity_working',
		'classrooms_electricity_working_before',
		'classrooms_electricity_working_after',
		'classrooms_furniture_sufficient',
		'classrooms_furniture_sufficient_before',
		'classrooms_furniture_sufficient_after',
		'classrooms_no_broken_material',
		'classrooms_no_broken_material_before',
		'classrooms_no_broken_material_after',
		'school_grounds_clean',
		'school_grounds_clean_before',
		'school_grounds_clean_after',
		'school_grounds_plants',
		'school_grounds_plants_before',
		'school_grounds_plants_after',
		'school_grounds_pathways',
		'school_grounds_pathways_before',
		'school_grounds_pathways_after',
		'secondary_ecc_room',
		'secondary_ecc_room_before',
		'secondary_ecc_room_after',
		'secondary_swings_slides',
		'secondary_swings_slides_before',
		'secondary_swings_slides_after',
	];

	public function __construct()
	{
		parent::__construct();

		$this->load->model('school_visit_reports_model');
		$this->load->model('schools_model');
		$this->load->model('districts_model');
		$this->load->model('tehsils_model');
		$this->load->model('school_visit_photos_model');
		$this->load->model('school_visit_dangerous_photos_model');
		$this->load->model('school_visit_flood_photos_model');
		$this->load->library('uploadlib');

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

	private function loadDistricts()
	{
		if (!$this->db->table_exists('districts')) {
			return [];
		}

		$this->db->order_by('district_sort', 'asc');
		$this->db->order_by('district_name_en', 'asc');
		return $this->districts_model->get();
	}

	private function loadTehsils()
	{
		if (!$this->db->table_exists('tehsils')) {
			return [];
		}

		$this->db->order_by('tehsil_order', 'asc');
		$this->db->order_by('tehsil_name_en', 'asc');
		return $this->tehsils_model->get();
	}

	private function allowed_schools()
	{
		// Prefer enriched school info, but fall back gracefully if joins/tables are missing.
		$has_districts = $this->db->table_exists('districts');
		$has_tehsils = $this->db->table_exists('tehsils');

		$select = ['schools.*'];
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

		if ((int) logged('role') === 3 && !empty(logged('school_id'))) {
			$this->db->where('schools.school_id', (int) logged('school_id'));
		}

		// Suppress DB errors and fall back to simple school list if something goes wrong.
		$db_debug = $this->db->db_debug;
		$this->db->db_debug = false;
		$query = $this->db->get();
		$this->db->db_debug = $db_debug;

		if ($query) {
			return $query->result();
		}

		// Fallback: bare schools query without joins/extra columns.
		if ((int) logged('role') === 3 && !empty(logged('school_id'))) {
			return $this->schools_model->getByWhere(['school_id' => (int) logged('school_id')]);
		}

		return $this->schools_model->get();
	}

	private function ensure_upload_paths()
	{
		$base = func_get_arg(0);
		if (!is_dir($base)) {
			@mkdir($base, 0755, true);
		}
	}

	private function optimize_and_thumb($file_path, $thumb_path, $max_width = 1400, $thumb_width = 400)
	{
		if (!file_exists($file_path)) {
			return;
		}

		$info = getimagesize($file_path);
		if (!$info) {
			return;
		}

		$mime = $info['mime'];
		switch ($mime) {
			case 'image/jpeg':
				$src = imagecreatefromjpeg($file_path);
				$save_original = function ($img, $path) {
					imagejpeg($img, $path, 80);
				};
				$save_thumb = function ($img, $path) {
					imagejpeg($img, $path, 75);
				};
				break;
			case 'image/png':
				$src = imagecreatefrompng($file_path);
				imagealphablending($src, true);
				imagesavealpha($src, true);
				$save_original = function ($img, $path) {
					imagepng($img, $path, 6);
				};
				$save_thumb = function ($img, $path) {
					imagepng($img, $path, 6);
				};
				break;
			case 'image/gif':
				$src = imagecreatefromgif($file_path);
				$save_original = function ($img, $path) {
					imagegif($img, $path);
				};
				$save_thumb = function ($img, $path) {
					imagegif($img, $path);
				};
				break;
			default:
				return;
		}

		$w = imagesx($src);
		$h = imagesy($src);

		// Optimize original size if wider than max_width
		if ($w > $max_width) {
			$new_w = $max_width;
			$new_h = (int) round($h * ($new_w / $w));
			$dst = imagecreatetruecolor($new_w, $new_h);
			imagealphablending($dst, true);
			imagesavealpha($dst, true);
			imagecopyresampled($dst, $src, 0, 0, 0, 0, $new_w, $new_h, $w, $h);
			$save_original($dst, $file_path);
			imagedestroy($dst);
		}

		// Create thumb
		$thumb_w = min($thumb_width, $w);
		$thumb_h = (int) round($h * ($thumb_w / $w));
		$thumb = imagecreatetruecolor($thumb_w, $thumb_h);
		imagealphablending($thumb, true);
		imagesavealpha($thumb, true);
		imagecopyresampled($thumb, $src, 0, 0, 0, 0, $thumb_w, $thumb_h, $w, $h);
		$save_thumb($thumb, $thumb_path);
		imagedestroy($thumb);
		imagedestroy($src);
	}

	private function save_photo($visit_id, $school_code, $checklist_key, $field_name)
	{
		if (!isset($_FILES[$field_name]) || (int) $_FILES[$field_name]['error'] === 4) {
			return;
		}

		$school_code = trim($school_code) !== '' ? $school_code : 'unknown';
		$base_path = FCPATH . 'uploads/' . $school_code . '/' . $visit_id;
		$this->ensure_upload_paths($base_path);
		// ensure base uploads dir exists
		$this->ensure_upload_paths(FCPATH . 'uploads');

		$this->uploadlib->initialize([
			'upload_path' => './uploads/' . $school_code . '/' . $visit_id,
			'allowed_types' => 'gif|jpg|png|jpeg',
			'overwrite' => true,
			'remove_spaces' => true,
		]);

		// Path already set above; pass empty to avoid double-appending.
		$upload = $this->uploadlib->uploadImage($field_name, '');
		if (!$upload['status']) {
			return;
		}

		$file_name = $upload['data']['file_name'];
		$source_path = $base_path . '/' . $file_name;
		$thumb_name = 'thumb_' . $file_name;
		$thumb_path = $base_path . '/' . $thumb_name;

		$this->optimize_and_thumb($source_path, $thumb_path);

		// Remove old photo if exists
		$existing = $this->school_visit_photos_model->getByWhere([
			'visit_id' => $visit_id,
			'checklist_key' => $checklist_key,
		]);
		if (!empty($existing)) {
			foreach ($existing as $old) {
				if (!empty($old->file_name) && file_exists(FCPATH . 'uploads/' . $old->file_name)) {
					@unlink(FCPATH . 'uploads/' . $old->file_name);
				}
				if (!empty($old->thumb_name) && file_exists(FCPATH . 'uploads/' . $old->thumb_name)) {
					@unlink(FCPATH . 'uploads/' . $old->thumb_name);
				}
				$this->school_visit_photos_model->delete($old->id);
			}
		}

		$this->school_visit_photos_model->create([
			'visit_id' => $visit_id,
			'checklist_key' => $checklist_key,
			'file_name' => $school_code . '/' . $visit_id . '/' . $file_name,
			'thumb_name' => $school_code . '/' . $visit_id . '/' . $thumb_name,
			'created_at' => date('Y-m-d H:i:s'),
		]);
	}

	private function save_all_photos($visit_id, $school_code)
	{
		foreach ($this->checklist_keys as $key) {
			$this->save_photo($visit_id, $school_code, $key, 'photo_' . $key);
		}
	}

	private function save_gate_photo($visit_id, $school_code, $field_name = 'gate_photo')
	{
		if (!isset($_FILES[$field_name]) || (int) $_FILES[$field_name]['error'] === 4) {
			return;
		}

		$school_code = trim($school_code) !== '' ? $school_code : 'unknown';
		$base_path = FCPATH . 'uploads/' . $school_code . '/' . $visit_id . '/gate';
		// ensure base dirs exist
		$this->ensure_upload_paths(FCPATH . 'uploads');
		$this->ensure_upload_paths(FCPATH . 'uploads/' . $school_code);
		$this->ensure_upload_paths(FCPATH . 'uploads/' . $school_code . '/' . $visit_id);
		$this->ensure_upload_paths($base_path);

		$this->uploadlib->initialize([
			'upload_path' => './uploads/' . $school_code . '/' . $visit_id . '/gate',
			'allowed_types' => 'gif|jpg|png|jpeg',
			'overwrite' => true,
			'remove_spaces' => true,
		]);

		$upload = $this->uploadlib->uploadImage($field_name, '');
		if (!$upload['status']) {
			return;
		}

		$file_name = $upload['data']['file_name'];
		$source_path = $base_path . '/' . $file_name;
		$thumb_name = 'gate_thumb_' . $file_name;
		$thumb_path = $base_path . '/' . $thumb_name;

		$this->optimize_and_thumb($source_path, $thumb_path);

		$existing = $this->school_visit_reports_model->getById($visit_id);
		if (!empty($existing) && !empty($existing->gate_photo)) {
			$oldPath = FCPATH . 'uploads/' . $existing->gate_photo;
			if (file_exists($oldPath)) {
				@unlink($oldPath);
			}
		}

		$this->school_visit_reports_model->update($visit_id, [
			'gate_photo' => $school_code . '/' . $visit_id . '/gate/' . $file_name,
			'updated_at' => date('Y-m-d H:i:s'),
		]);
	}

	private function save_dangerous_photos($visit_id, $school_code)
	{
		$exists = post('dangerous_exists') === '1';
		$types = $this->input->post('dangerous_type');

		// clear existing entries
		$existing = $this->school_visit_dangerous_photos_model->getByWhere(['visit_id' => $visit_id]);
		foreach ($existing as $old) {
			if (!empty($old->file_name) && file_exists(FCPATH . 'uploads/' . $old->file_name)) {
				@unlink(FCPATH . 'uploads/' . $old->file_name);
			}
		}
		$this->school_visit_dangerous_photos_model->delete_by_visit($visit_id);

		if (!$exists || empty($types) || !isset($_FILES['dangerous_photo'])) {
			return;
		}

		$school_code = trim($school_code) !== '' ? $school_code : 'unknown';
		$base_dir = FCPATH . 'uploads/' . $school_code . '/' . $visit_id . '/dangerous';
		$this->ensure_upload_paths(FCPATH . 'uploads');
		$this->ensure_upload_paths(FCPATH . 'uploads/' . $school_code);
		$this->ensure_upload_paths(FCPATH . 'uploads/' . $school_code . '/' . $visit_id);
		$this->ensure_upload_paths($base_dir);

		$files = $_FILES['dangerous_photo'];
		$count = count($types);

		for ($i = 0; $i < $count; $i++) {
			if (!isset($files['name'][$i]) || $files['error'][$i] === 4) {
				continue;
			}

			$_FILES['single_dangerous']['name'] = $files['name'][$i];
			$_FILES['single_dangerous']['type'] = $files['type'][$i];
			$_FILES['single_dangerous']['tmp_name'] = $files['tmp_name'][$i];
			$_FILES['single_dangerous']['error'] = $files['error'][$i];
			$_FILES['single_dangerous']['size'] = $files['size'][$i];

			$this->uploadlib->initialize([
				'upload_path' => './uploads/' . $school_code . '/' . $visit_id . '/dangerous',
				'allowed_types' => 'gif|jpg|png|jpeg',
				'overwrite' => false,
				'remove_spaces' => true,
			]);

			// Path already configured above, keep empty here to avoid duplicating the path.
			$upload = $this->uploadlib->uploadImage('single_dangerous', '');
			if (!$upload['status']) {
				continue;
			}

			$file_name = $upload['data']['file_name'];
			$this->school_visit_dangerous_photos_model->create([
				'visit_id' => $visit_id,
				'building_type' => isset($types[$i]) ? $types[$i] : '',
				'file_name' => $school_code . '/' . $visit_id . '/dangerous/' . $file_name,
				'created_at' => date('Y-m-d H:i:s'),
			]);
		}
	}

	private function save_head_photo($visit_id, $school_code, $field_name = 'head_photo')
	{
		if (!isset($_FILES[$field_name]) || (int) $_FILES[$field_name]['error'] === 4) {
			return;
		}

		$school_code = trim($school_code) !== '' ? $school_code : 'unknown';
		$base_path = FCPATH . 'uploads/' . $school_code . '/' . $visit_id . '/head';
		// ensure base dirs exist
		$this->ensure_upload_paths(FCPATH . 'uploads');
		$this->ensure_upload_paths(FCPATH . 'uploads/' . $school_code);
		$this->ensure_upload_paths(FCPATH . 'uploads/' . $school_code . '/' . $visit_id);
		$this->ensure_upload_paths($base_path);

		$this->uploadlib->initialize([
			'upload_path' => './uploads/' . $school_code . '/' . $visit_id . '/head',
			'allowed_types' => 'gif|jpg|png|jpeg',
			'overwrite' => true,
			'remove_spaces' => true,
		]);

		$upload = $this->uploadlib->uploadImage($field_name, '');
		if (!$upload['status']) {
			return;
		}

		$file_name = $upload['data']['file_name'];
		$source_path = $base_path . '/' . $file_name;
		$thumb_name = 'head_thumb_' . $file_name;
		$thumb_path = $base_path . '/' . $thumb_name;

		$this->optimize_and_thumb($source_path, $thumb_path);

		// Delete old head photo if exists
		$existing = $this->school_visit_reports_model->getById($visit_id);
		if (!empty($existing) && !empty($existing->head_photo)) {
			$oldPath = FCPATH . 'uploads/' . $existing->head_photo;
			if (file_exists($oldPath)) {
				@unlink($oldPath);
			}
		}

		$this->school_visit_reports_model->update($visit_id, [
			'head_photo' => $school_code . '/' . $visit_id . '/head/' . $file_name,
			'updated_at' => date('Y-m-d H:i:s'),
		]);
	}

	private function save_flood_photos($visit_id, $school_code)
	{
		$exists = post('flood_exists') === '1';
		$types = $this->input->post('flood_type');

		// clear existing entries
		$existing = $this->school_visit_flood_photos_model->getByWhere(['visit_id' => $visit_id]);
		foreach ($existing as $old) {
			if (!empty($old->file_name) && file_exists(FCPATH . 'uploads/' . $old->file_name)) {
				@unlink(FCPATH . 'uploads/' . $old->file_name);
			}
		}
		$this->school_visit_flood_photos_model->delete_by_visit($visit_id);

		if (!$exists || empty($types) || !isset($_FILES['flood_photo'])) {
			return;
		}

		$school_code = trim($school_code) !== '' ? $school_code : 'unknown';
		$base_dir = FCPATH . 'uploads/' . $school_code . '/' . $visit_id . '/flood';
		$this->ensure_upload_paths(FCPATH . 'uploads');
		$this->ensure_upload_paths(FCPATH . 'uploads/' . $school_code);
		$this->ensure_upload_paths(FCPATH . 'uploads/' . $school_code . '/' . $visit_id);
		$this->ensure_upload_paths($base_dir);

		$files = $_FILES['flood_photo'];
		$count = count($types);

		for ($i = 0; $i < $count; $i++) {
			if (!isset($files['name'][$i]) || $files['error'][$i] === 4) {
				continue;
			}

			$_FILES['single_flood']['name'] = $files['name'][$i];
			$_FILES['single_flood']['type'] = $files['type'][$i];
			$_FILES['single_flood']['tmp_name'] = $files['tmp_name'][$i];
			$_FILES['single_flood']['error'] = $files['error'][$i];
			$_FILES['single_flood']['size'] = $files['size'][$i];

			$this->uploadlib->initialize([
				'upload_path' => './uploads/' . $school_code . '/' . $visit_id . '/flood',
				'allowed_types' => 'gif|jpg|png|jpeg',
				'overwrite' => false,
				'remove_spaces' => true,
			]);

			$upload = $this->uploadlib->uploadImage('single_flood', '');
			if (!$upload['status']) {
				continue;
			}

			$file_name = $upload['data']['file_name'];
			$this->school_visit_flood_photos_model->create([
				'visit_id' => $visit_id,
				'building_type' => isset($types[$i]) ? $types[$i] : '',
				'file_name' => $school_code . '/' . $visit_id . '/flood/' . $file_name,
				'created_at' => date('Y-m-d H:i:s'),
			]);
		}
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

		$has_districts = $this->db->table_exists('districts');
		$has_tehsils = $this->db->table_exists('tehsils');

		$select = [
			'school_visit_reports.*',
			'schools.school_name',
			'schools.school_code',
			'schools.school_contact_name',
			'schools.school_contact_mobile',
			'users.name as visitor_name',
		];
		$select[] = $has_districts ? 'districts.district_name_en' : 'NULL as district_name_en';
		$select[] = $has_tehsils ? 'tehsils.tehsil_name_en' : 'NULL as tehsil_name_en';

		$this->db->select(implode(', ', $select));
		$this->db->from('school_visit_reports');
		$this->db->join('schools', 'schools.school_id = school_visit_reports.school_id', 'left');
		if ($has_districts) {
			$this->db->join('districts', 'districts.district_id = schools.school_district_id', 'left');
		}
		if ($has_tehsils) {
			$this->db->join('tehsils', 'tehsils.tehsil_id = schools.school_tehsil_id', 'left');
		}
		$this->db->join('users', 'users.id = school_visit_reports.visited_by', 'left');

		$filter_school_id = $this->input->get('school_id');
		if (!empty($filter_school_id)) {
			$this->db->where('school_visit_reports.school_id', (int) $filter_school_id);
		}

		/*
		if ((int) logged('role') === 3 && !empty(logged('school_id'))) {
			$this->db->where('school_visit_reports.school_id', (int) logged('school_id'));
		}
*/
		$this->db->order_by('visit_date', 'desc');
		$this->db->order_by('visit_time', 'desc');

		$this->page_data['visits'] = $this->db->get()->result();

		$this->load->view('school_visits/list', $this->page_data);
	}

	public function add()
	{
		$this->authorize('school_visits_add');

		$this->page_data['schools'] = $this->allowed_schools();
		$this->page_data['districts'] = $this->loadDistricts();
		$this->page_data['tehsils'] = $this->loadTehsils();

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
			'visit_date' => date('Y-m-d'),
			'visit_time' => date('H:i:s'),
			'visited_by' => logged('id'),
			'is_open' => $this->validated_status(post('is_open')),
			'teachers_as_per_sis' => post('teachers_as_per_sis') !== '' ? (int) post('teachers_as_per_sis') : null,
			'teachers_as_per_register' => post('teachers_as_per_register') !== '' ? (int) post('teachers_as_per_register') : null,
			'teachers_present' => post('teachers_present') !== '' ? (int) post('teachers_present') : null,
			'teachers_gap' => (post('teachers_as_per_sis') !== '' && post('teachers_present') !== '') ? ((int) post('teachers_as_per_sis') - (int) post('teachers_present')) : null,
			'students_enrollment_sis' => post('students_enrollment_sis') !== '' ? (int) post('students_enrollment_sis') : null,
			'students_enrollment_register' => post('students_enrollment_register') !== '' ? (int) post('students_enrollment_register') : null,
			'students_present' => post('students_present') !== '' ? (int) post('students_present') : null,
			'students_enrollment_gap' => (post('students_enrollment_sis') !== '' && post('students_present') !== '') ? ((int) post('students_enrollment_sis') - (int) post('students_present')) : null,
			'dangerous_exists' => post('dangerous_exists') === '1' ? 1 : 0,
			'flood_exists' => post('flood_exists') === '1' ? 1 : 0,
			'head_name' => post('head_name'),
			'head_gender' => post('head_gender'),
			'head_contact' => post('head_contact'),
			'head_whatsapp' => post('head_whatsapp'),
			'head_email' => post('head_email'),
			'remarks' => post('remarks'),
			'boundary_wall_main_gate' => post('boundary_wall_main_gate') ? 1 : 0,
			'drinking_water_available' => post('drinking_water_available') ? 1 : 0,
			'washrooms_tiled_floors' => post('washrooms_tiled_floors') ? 1 : 0,
			'washrooms_handwashing_tap' => post('washrooms_handwashing_tap') ? 1 : 0,
			'washrooms_soap_available' => post('washrooms_soap_available') ? 1 : 0,
			'washrooms_clean_daily' => post('washrooms_clean_daily') ? 1 : 0,
			'classrooms_repaired_painted' => post('classrooms_repaired_painted') ? 1 : 0,
			'classrooms_board_available' => post('classrooms_board_available') ? 1 : 0,
			'classrooms_ventilation_safety' => post('classrooms_ventilation_safety') ? 1 : 0,
			'classrooms_electricity_working' => post('classrooms_electricity_working') ? 1 : 0,
			'classrooms_furniture_sufficient' => post('classrooms_furniture_sufficient') ? 1 : 0,
			'classrooms_no_broken_material' => post('classrooms_no_broken_material') ? 1 : 0,
			'school_grounds_clean' => post('school_grounds_clean') ? 1 : 0,
			'school_grounds_plants' => post('school_grounds_plants') ? 1 : 0,
			'school_grounds_pathways' => post('school_grounds_pathways') ? 1 : 0,
			'secondary_ecc_room' => post('secondary_ecc_room') ? 1 : 0,
			'secondary_swings_slides' => post('secondary_swings_slides') ? 1 : 0,
			'created_at' => date('Y-m-d H:i:s'),
			'updated_at' => date('Y-m-d H:i:s'),
		];

		$id = $this->school_visit_reports_model->create($data);
		$dbErr = $this->db->error();
		if (!$id || (!empty($dbErr['message']))) {
			$this->session->set_flashdata('alert-type', 'danger');
			$this->session->set_flashdata('alert', 'Save failed: ' . $dbErr['message']);
			redirect('school_visits/add');
			return;
		}

		$school = $this->schools_model->getById($data['school_id']);
		$school_code = !empty($school) ? $school->school_code : 'unknown';

		$this->save_all_photos($id, $school_code);
		$this->save_head_photo($id, $school_code);
		$this->save_gate_photo($id, $school_code);
		$this->save_dangerous_photos($id, $school_code);
		$this->save_flood_photos($id, $school_code);

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
			'is_open' => $this->validated_status(post('is_open')),
			'teachers_as_per_sis' => post('teachers_as_per_sis') !== '' ? (int) post('teachers_as_per_sis') : null,
			'teachers_as_per_register' => post('teachers_as_per_register') !== '' ? (int) post('teachers_as_per_register') : null,
			'teachers_present' => post('teachers_present') !== '' ? (int) post('teachers_present') : null,
			'teachers_gap' => (post('teachers_as_per_sis') !== '' && post('teachers_present') !== '') ? ((int) post('teachers_as_per_sis') - (int) post('teachers_present')) : null,
			'students_enrollment_sis' => post('students_enrollment_sis') !== '' ? (int) post('students_enrollment_sis') : null,
			'students_enrollment_register' => post('students_enrollment_register') !== '' ? (int) post('students_enrollment_register') : null,
			'students_present' => post('students_present') !== '' ? (int) post('students_present') : null,
			'students_enrollment_gap' => (post('students_enrollment_sis') !== '' && post('students_present') !== '') ? ((int) post('students_enrollment_sis') - (int) post('students_present')) : null,
			'dangerous_exists' => post('dangerous_exists') === '1' ? 1 : 0,
			'flood_exists' => post('flood_exists') === '1' ? 1 : 0,
			'head_name' => post('head_name'),
			'head_gender' => post('head_gender'),
			'head_contact' => post('head_contact'),
			'head_whatsapp' => post('head_whatsapp'),
			'head_email' => post('head_email'),
			'remarks' => post('remarks'),
			'boundary_wall_main_gate' => post('boundary_wall_main_gate') ? 1 : 0,
			'drinking_water_available' => post('drinking_water_available') ? 1 : 0,
			'washrooms_tiled_floors' => post('washrooms_tiled_floors') ? 1 : 0,
			'washrooms_handwashing_tap' => post('washrooms_handwashing_tap') ? 1 : 0,
			'washrooms_soap_available' => post('washrooms_soap_available') ? 1 : 0,
			'washrooms_clean_daily' => post('washrooms_clean_daily') ? 1 : 0,
			'classrooms_repaired_painted' => post('classrooms_repaired_painted') ? 1 : 0,
			'classrooms_board_available' => post('classrooms_board_available') ? 1 : 0,
			'classrooms_ventilation_safety' => post('classrooms_ventilation_safety') ? 1 : 0,
			'classrooms_electricity_working' => post('classrooms_electricity_working') ? 1 : 0,
			'classrooms_furniture_sufficient' => post('classrooms_furniture_sufficient') ? 1 : 0,
			'classrooms_no_broken_material' => post('classrooms_no_broken_material') ? 1 : 0,
			'school_grounds_clean' => post('school_grounds_clean') ? 1 : 0,
			'school_grounds_plants' => post('school_grounds_plants') ? 1 : 0,
			'school_grounds_pathways' => post('school_grounds_pathways') ? 1 : 0,
			'secondary_ecc_room' => post('secondary_ecc_room') ? 1 : 0,
			'secondary_swings_slides' => post('secondary_swings_slides') ? 1 : 0,
			'updated_at' => date('Y-m-d H:i:s'),
		];

		$this->school_visit_reports_model->update($id, $data);
		$dbErr = $this->db->error();
		if (!empty($dbErr['message'])) {
			$this->session->set_flashdata('alert-type', 'danger');
			$this->session->set_flashdata('alert', 'Update failed: ' . $dbErr['message']);
			redirect('school_visits/edit/' . $id);
			return;
		}

		$school = $this->schools_model->getById($school_id);
		$school_code = !empty($school) ? $school->school_code : 'unknown';

		$this->save_all_photos($id, $school_code);
		$this->save_head_photo($id, $school_code);
		$this->save_gate_photo($id, $school_code);
		$this->save_dangerous_photos($id, $school_code);
		$this->save_flood_photos($id, $school_code);

		$this->activity_model->add("School visit #$id Updated by User: #" . logged('id'), logged('id'));

		$this->session->set_flashdata('alert-type', 'success');
		$this->session->set_flashdata('alert', 'School visit report updated successfully');

		redirect('school_visits');
	}

	public function view($id)
	{
		$this->authorize('school_visits_view');

		$has_districts = $this->db->table_exists('districts');
		$has_tehsils = $this->db->table_exists('tehsils');

		$select = [
			'school_visit_reports.*',
			'schools.school_name',
			'schools.school_code',
			'schools.school_contact_name',
			'schools.school_contact_mobile',
			'users.name as visitor_name',
		];
		$select[] = $has_districts ? 'districts.district_name_en' : 'NULL as district_name_en';
		$select[] = $has_tehsils ? 'tehsils.tehsil_name_en' : 'NULL as tehsil_name_en';

		$this->db->select(implode(', ', $select));
		$this->db->from('school_visit_reports');
		$this->db->join('schools', 'schools.school_id = school_visit_reports.school_id', 'left');
		if ($has_districts) {
			$this->db->join('districts', 'districts.district_id = schools.school_district_id', 'left');
		}
		if ($has_tehsils) {
			$this->db->join('tehsils', 'tehsils.tehsil_id = schools.school_tehsil_id', 'left');
		}
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
		$photos = $this->school_visit_photos_model->getByWhere(['visit_id' => $id]);
		$photo_map = [];

		foreach ($photos as $photo) {
			$photo_map[$photo->checklist_key] = $photo;
		}
		$this->page_data['photos'] = $photo_map;
		$this->page_data['dangerous_photos'] = $this->school_visit_dangerous_photos_model->getByWhere(['visit_id' => $id]);
		$this->page_data['flood_photos'] = $this->school_visit_flood_photos_model->getByWhere(['visit_id' => $id]);
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
