<?php
defined('BASEPATH') or exit('No direct script access allowed'); ?>

<?php include viewPath('includes/header'); ?>

<!-- Content Header (Page header) -->
<section class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1><?php echo lang('school_visits') ?></h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="<?php echo url('/') ?>"><?php echo lang('home') ?></a></li>
          <li class="breadcrumb-item"><a href="<?php echo url('/school_visits') ?>"> <?php echo lang('school_visits') ?></a></li>
          <li class="breadcrumb-item active"> <?php echo lang('edit_school_visit') ?></li>
        </ol>
      </div>
    </div>
  </div><!-- /.container-fluid -->
</section>

<!-- Main content -->
<section class="content">

  <!-- Default card -->
  <div class="card">
    <div class="card-header with-border">
      <h3 class="card-title"> <?php echo lang('edit_school_visit') ?></h3>

      <div class="card-tools pull-right">
        <a href="<?php echo url('school_visits') ?>" class="btn btn-flat btn-default btn-sm"><i class="fa fa-arrow-left"></i> &nbsp;&nbsp; <?php echo lang('school_visits') ?></a>
      </div>

    </div>

    <?php echo form_open_multipart('school_visits/update/' . $visit->id, ['class' => 'form-validate']); ?>
    <div class="card-body">

      <div class="alert alert-info">
        <h5 class="mb-2">Mandatory Compliance Requirements for All PEIMA Schools</h5>
        <p class="mb-2">Please verify these indicators during every visit or inspection:</p>
        <ul class="mb-0">
          <li><strong>Boundary Wall &amp; Main Gate:</strong> Fully repaired, painted, secure; school name and EMIS code displayed.</li>
          <li><strong>Drinking Water:</strong> Clean, safe, potable, and always available; source maintained and operational.</li>
          <li><strong>Washrooms:</strong> Tiled floors, handwashing station with functional tap, soap available, cleaned daily.</li>
          <li><strong>Classrooms:</strong> Repaired/painted floors, walls, verandas, roofs; board present; ventilation/safety; functional electricity, fans, lights; sufficient furniture; remove broken/unused items.</li>
          <li><strong>School Grounds:</strong> Clean and maintained; planted grass/trees; pathways with bricks or tuff tiles.</li>
          <li><strong>Secondary Facilities (Optional):</strong> ECC room with LED, swings, and slides.</li>
        </ul>
      </div>

      <div class="card card-outline card-secondary mb-3">
        <div class="card-header">
          <h3 class="card-title">Basic School Information</h3>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label for="visit-school"><?php echo lang('school') ?></label>
                <select name="school_id" id="visit-school" class="form-control select2" <?php echo (int) logged('role') === 3 ? 'disabled' : '' ?> required>
                  <option value=""><?php echo lang('select_school') ?></option>
                  <?php foreach ($schools as $school): ?>
                    <option value="<?php echo $school->school_id ?>" <?php echo ((int) $school->school_id === (int) $visit->school_id) ? 'selected' : '' ?>><?php echo $school->school_name ?></option>
                  <?php endforeach ?>
                </select>
                <?php if ((int) logged('role') === 3): ?>
                  <input type="hidden" name="school_id" value="<?php echo $visit->school_id ?>">
                <?php endif ?>
              </div>
            </div>
            <div class="col-md-2">
              <div class="form-group">
                <label for="visit-date"><?php echo lang('visit_date') ?></label>
                <input type="date" class="form-control" id="visit-date" value="<?php echo date('Y-m-d', strtotime($visit->visit_date)) ?>" readonly />
              </div>
            </div>
            <div class="col-md-2">
              <div class="form-group">
                <label for="visit-time"><?php echo lang('visit_time') ?></label>
                <input type="time" class="form-control" id="visit-time" value="<?php echo !empty($visit->visit_time) ? date('H:i', strtotime($visit->visit_time)) : date('H:i'); ?>" readonly />
              </div>
            </div>
            <div class="col-md-2">
              <div class="form-group">
                <label for="visit-status"><?php echo lang('school_status') ?></label>
                <select name="is_open" id="visit-status" class="form-control">
                  <?php foreach ($status_options as $status): ?>
                    <option value="<?php echo $status ?>" <?php echo ($visit->is_open === $status) ? 'selected' : '' ?>><?php echo $status ?></option>
                  <?php endforeach ?>
                </select>
              </div>
            </div>

          </div>
        </div>
      </div>

      <div class="card card-outline card-secondary mb-3">
        <div class="card-header">
          <h3 class="card-title">Compliance Requirements</h3>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label>Boundary Wall &amp; Main Gate secure, repaired, painted; name/EMIS displayed</label>
                <select name="boundary_wall_main_gate" class="form-control">
                  <option value="1" <?php echo $visit->boundary_wall_main_gate ? 'selected' : '' ?>>Yes</option>
                  <option value="0" <?php echo !$visit->boundary_wall_main_gate ? 'selected' : '' ?>>No</option>
                </select>
                <input type="file" name="photo_boundary_wall_main_gate" accept="image/*" class="form-control-file mt-1" />
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label>Drinking water clean, safe, and available</label>
                <select name="drinking_water_available" class="form-control">
                  <option value="1" <?php echo $visit->drinking_water_available ? 'selected' : '' ?>>Yes</option>
                  <option value="0" <?php echo !$visit->drinking_water_available ? 'selected' : '' ?>>No</option>
                </select>
                <input type="file" name="photo_drinking_water_available" accept="image/*" class="form-control-file mt-1" />
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label>Washrooms have tiled floors</label>
                <select name="washrooms_tiled_floors" class="form-control">
                  <option value="1" <?php echo $visit->washrooms_tiled_floors ? 'selected' : '' ?>>Yes</option>
                  <option value="0" <?php echo !$visit->washrooms_tiled_floors ? 'selected' : '' ?>>No</option>
                </select>
                <input type="file" name="photo_washrooms_tiled_floors" accept="image/*" class="form-control-file mt-1" />
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label>Handwashing station with functional tap</label>
                <select name="washrooms_handwashing_tap" class="form-control">
                  <option value="1" <?php echo $visit->washrooms_handwashing_tap ? 'selected' : '' ?>>Yes</option>
                  <option value="0" <?php echo !$visit->washrooms_handwashing_tap ? 'selected' : '' ?>>No</option>
                </select>
                <input type="file" name="photo_washrooms_handwashing_tap" accept="image/*" class="form-control-file mt-1" />
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label>Soap available</label>
                <select name="washrooms_soap_available" class="form-control">
                  <option value="1" <?php echo $visit->washrooms_soap_available ? 'selected' : '' ?>>Yes</option>
                  <option value="0" <?php echo !$visit->washrooms_soap_available ? 'selected' : '' ?>>No</option>
                </select>
                <input type="file" name="photo_washrooms_soap_available" accept="image/*" class="form-control-file mt-1" />
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label>Washrooms cleaned daily</label>
                <select name="washrooms_clean_daily" class="form-control">
                  <option value="1" <?php echo $visit->washrooms_clean_daily ? 'selected' : '' ?>>Yes</option>
                  <option value="0" <?php echo !$visit->washrooms_clean_daily ? 'selected' : '' ?>>No</option>
                </select>
                <input type="file" name="photo_washrooms_clean_daily" accept="image/*" class="form-control-file mt-1" />
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label>Classrooms repaired/painted (floors, walls, verandas, roofs)</label>
                <select name="classrooms_repaired_painted" class="form-control">
                  <option value="1" <?php echo $visit->classrooms_repaired_painted ? 'selected' : '' ?>>Yes</option>
                  <option value="0" <?php echo !$visit->classrooms_repaired_painted ? 'selected' : '' ?>>No</option>
                </select>
                <input type="file" name="photo_classrooms_repaired_painted" accept="image/*" class="form-control-file mt-1" />
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label>Whiteboard/blackboard available</label>
                <select name="classrooms_board_available" class="form-control">
                  <option value="1" <?php echo $visit->classrooms_board_available ? 'selected' : '' ?>>Yes</option>
                  <option value="0" <?php echo !$visit->classrooms_board_available ? 'selected' : '' ?>>No</option>
                </select>
                <input type="file" name="photo_classrooms_board_available" accept="image/*" class="form-control-file mt-1" />
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label>Proper ventilation and safety</label>
                <select name="classrooms_ventilation_safety" class="form-control">
                  <option value="1" <?php echo $visit->classrooms_ventilation_safety ? 'selected' : '' ?>>Yes</option>
                  <option value="0" <?php echo !$visit->classrooms_ventilation_safety ? 'selected' : '' ?>>No</option>
                </select>
                <input type="file" name="photo_classrooms_ventilation_safety" accept="image/*" class="form-control-file mt-1" />
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label>Electricity/fans/lights functional</label>
                <select name="classrooms_electricity_working" class="form-control">
                  <option value="1" <?php echo $visit->classrooms_electricity_working ? 'selected' : '' ?>>Yes</option>
                  <option value="0" <?php echo !$visit->classrooms_electricity_working ? 'selected' : '' ?>>No</option>
                </select>
                <input type="file" name="photo_classrooms_electricity_working" accept="image/*" class="form-control-file mt-1" />
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label>Sufficient student furniture</label>
                <select name="classrooms_furniture_sufficient" class="form-control">
                  <option value="1" <?php echo $visit->classrooms_furniture_sufficient ? 'selected' : '' ?>>Yes</option>
                  <option value="0" <?php echo !$visit->classrooms_furniture_sufficient ? 'selected' : '' ?>>No</option>
                </select>
                <input type="file" name="photo_classrooms_furniture_sufficient" accept="image/*" class="form-control-file mt-1" />
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label>No broken/unused material present</label>
                <select name="classrooms_no_broken_material" class="form-control">
                  <option value="1" <?php echo $visit->classrooms_no_broken_material ? 'selected' : '' ?>>Yes</option>
                  <option value="0" <?php echo !$visit->classrooms_no_broken_material ? 'selected' : '' ?>>No</option>
                </select>
                <input type="file" name="photo_classrooms_no_broken_material" accept="image/*" class="form-control-file mt-1" />
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label>School grounds clean and maintained</label>
                <select name="school_grounds_clean" class="form-control">
                  <option value="1" <?php echo $visit->school_grounds_clean ? 'selected' : '' ?>>Yes</option>
                  <option value="0" <?php echo !$visit->school_grounds_clean ? 'selected' : '' ?>>No</option>
                </select>
                <input type="file" name="photo_school_grounds_clean" accept="image/*" class="form-control-file mt-1" />
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label>Grass/trees planted and maintained</label>
                <select name="school_grounds_plants" class="form-control">
                  <option value="1" <?php echo $visit->school_grounds_plants ? 'selected' : '' ?>>Yes</option>
                  <option value="0" <?php echo !$visit->school_grounds_plants ? 'selected' : '' ?>>No</option>
                </select>
                <input type="file" name="photo_school_grounds_plants" accept="image/*" class="form-control-file mt-1" />
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label>Pathways with bricks or tuff tiles</label>
                <select name="school_grounds_pathways" class="form-control">
                  <option value="1" <?php echo $visit->school_grounds_pathways ? 'selected' : '' ?>>Yes</option>
                  <option value="0" <?php echo !$visit->school_grounds_pathways ? 'selected' : '' ?>>No</option>
                </select>
                <input type="file" name="photo_school_grounds_pathways" accept="image/*" class="form-control-file mt-1" />
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label>Secondary (optional): ECC room with LED</label>
                <select name="secondary_ecc_room" class="form-control">
                  <option value="1" <?php echo $visit->secondary_ecc_room ? 'selected' : '' ?>>Yes</option>
                  <option value="0" <?php echo !$visit->secondary_ecc_room ? 'selected' : '' ?>>No</option>
                </select>
                <input type="file" name="photo_secondary_ecc_room" accept="image/*" class="form-control-file mt-1" />
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label>Secondary (optional): Swings and slides</label>
                <select name="secondary_swings_slides" class="form-control">
                  <option value="1" <?php echo $visit->secondary_swings_slides ? 'selected' : '' ?>>Yes</option>
                  <option value="0" <?php echo !$visit->secondary_swings_slides ? 'selected' : '' ?>>No</option>
                </select>
                <input type="file" name="photo_secondary_swings_slides" accept="image/*" class="form-control-file mt-1" />
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label>School Gate Photo (optional)</label>
                <input type="file" name="gate_photo" accept="image/*" class="form-control-file" />
                <?php if (!empty($visit->gate_photo)): ?>
                  <div class="mt-2">
                    <a href="<?php echo base_url('uploads/'.$visit->gate_photo); ?>" target="_blank">
                      <img src="<?php echo base_url('uploads/'.$visit->gate_photo); ?>" alt="Gate Photo" style="height:60px" />
                    </a>
                  </div>
                <?php endif; ?>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="card card-outline card-secondary mb-3">
        <div class="card-header">
          <h3 class="card-title">Infrastructure &amp; Resources</h3>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                <label for="remarks"><?php echo lang('remarks') ?></label>
                <textarea name="remarks" id="remarks" class="form-control" rows="3" placeholder="<?php echo lang('remarks_optional') ?>"><?php echo htmlspecialchars($visit->remarks, ENT_QUOTES, 'UTF-8'); ?></textarea>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="card card-outline card-secondary mb-3">
        <div class="card-header">
          <h3 class="card-title">Teachers Inspection</h3>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-md-3">
              <div class="form-group">
                <label for="teachers-sis">Teachers as per SIS</label>
                <input type="number" class="form-control" name="teachers_as_per_sis" id="teachers-sis" min="0" value="<?php echo htmlspecialchars($visit->teachers_as_per_sis, ENT_QUOTES, 'UTF-8'); ?>" />
              </div>
            </div>
            <div class="col-md-3">
              <div class="form-group">
                <label for="teachers-register">Teachers as per Register</label>
                <input type="number" class="form-control" name="teachers_as_per_register" id="teachers-register" min="0" value="<?php echo htmlspecialchars($visit->teachers_as_per_register, ENT_QUOTES, 'UTF-8'); ?>" />
              </div>
            </div>
            <div class="col-md-3">
              <div class="form-group">
                <label for="teachers-present">Teachers Present</label>
                <input type="number" class="form-control" name="teachers_present" id="teachers-present" min="0" value="<?php echo htmlspecialchars($visit->teachers_present, ENT_QUOTES, 'UTF-8'); ?>" />
              </div>
            </div>
            <div class="col-md-3">
              <div class="form-group">
                <label for="teachers-gap">Teachers Gap (SIS - Present)</label>
                <input type="number" class="form-control" name="teachers_gap_display" id="teachers-gap" min="0" value="<?php echo htmlspecialchars($visit->teachers_gap, ENT_QUOTES, 'UTF-8'); ?>" readonly />
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="card card-outline card-secondary mb-3">
        <div class="card-header">
          <h3 class="card-title">School Head Detail</h3>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-md-4">
              <div class="form-group">
                <label for="head-name">Head Name</label>
                <input type="text" class="form-control" name="head_name" id="head-name" value="<?php echo htmlspecialchars($visit->head_name, ENT_QUOTES, 'UTF-8'); ?>" />
              </div>
            </div>
            <div class="col-md-2">
              <div class="form-group">
                <label for="head-gender">Gender</label>
                <select name="head_gender" id="head-gender" class="form-control">
                  <option value="">Select</option>
                  <option value="Male" <?php echo ($visit->head_gender === 'Male') ? 'selected' : ''; ?>>Male</option>
                  <option value="Female" <?php echo ($visit->head_gender === 'Female') ? 'selected' : ''; ?>>Female</option>
                </select>
              </div>
            </div>
            <div class="col-md-3">
              <div class="form-group">
                <label for="head-contact">Head Contact</label>
                <input type="text" class="form-control" name="head_contact" id="head-contact" value="<?php echo htmlspecialchars($visit->head_contact, ENT_QUOTES, 'UTF-8'); ?>" />
              </div>
            </div>
            <div class="col-md-3">
              <div class="form-group">
                <label for="head-whatsapp">Whatsapp No</label>
                <input type="text" class="form-control" name="head_whatsapp" id="head-whatsapp" value="<?php echo htmlspecialchars($visit->head_whatsapp, ENT_QUOTES, 'UTF-8'); ?>" />
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label for="head-email">Head Email</label>
                <input type="email" class="form-control" name="head_email" id="head-email" value="<?php echo htmlspecialchars($visit->head_email, ENT_QUOTES, 'UTF-8'); ?>" />
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label>Head Photo (optional)</label>
                <input type="file" name="head_photo" accept="image/*" class="form-control-file" />
                <?php if (!empty($visit->head_photo)): ?>
                  <div class="mt-2">
                    <a href="<?php echo base_url('uploads/'.$visit->head_photo); ?>" target="_blank">
                      <img src="<?php echo base_url('uploads/'.$visit->head_photo); ?>" alt="Head Photo" style="height:60px" />
                    </a>
                  </div>
                <?php endif; ?>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="card card-outline card-secondary mb-3">
        <div class="card-header">
          <h3 class="card-title">Students Enrollment Information</h3>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-md-3">
              <div class="form-group">
                <label for="students-enrollment-sis">Enrollment as per SIS</label>
                <input type="number" class="form-control" name="students_enrollment_sis" id="students-enrollment-sis" min="0" value="<?php echo htmlspecialchars($visit->students_enrollment_sis, ENT_QUOTES, 'UTF-8'); ?>" />
              </div>
            </div>
            <div class="col-md-3">
              <div class="form-group">
                <label for="students-enrollment-register">Total Enrollment as per Register</label>
                <input type="number" class="form-control" name="students_enrollment_register" id="students-enrollment-register" min="0" value="<?php echo htmlspecialchars($visit->students_enrollment_register, ENT_QUOTES, 'UTF-8'); ?>" />
              </div>
            </div>
            <div class="col-md-3">
              <div class="form-group">
                <label for="students-present">Total Present (Head count)</label>
                <input type="number" class="form-control" name="students_present" id="students-present" min="0" value="<?php echo htmlspecialchars($visit->students_present, ENT_QUOTES, 'UTF-8'); ?>" />
              </div>
            </div>
            <div class="col-md-3">
              <div class="form-group">
                <label for="students-gap">Enrollment Gap (SIS - Present)</label>
                <input type="number" class="form-control" name="students_enrollment_gap_display" id="students-gap" min="0" value="<?php echo htmlspecialchars($visit->students_enrollment_gap, ENT_QUOTES, 'UTF-8'); ?>" readonly />
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="card card-outline card-secondary mb-3">
        <div class="card-header">
          <h3 class="card-title">Dangerous Building</h3>
        </div>
        <div class="card-body">
          <div class="form-group">
            <label>Dangerous building exists?</label>
            <select name="dangerous_exists" id="dangerous-exists" class="form-control">
              <option value="0" <?php echo !$visit->dangerous_exists ? 'selected' : ''; ?>>No</option>
              <option value="1" <?php echo $visit->dangerous_exists ? 'selected' : ''; ?>>Yes</option>
            </select>
          </div>

          <div id="dangerous-wrap" class="d-none">
            <div class="table-responsive">
              <table class="table table-bordered" id="dangerous-table">
                <thead>
                  <tr>
                    <th>Type</th>
                    <th>Photo</th>
                    <th style="width:70px;">Action</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td>
                      <select name="dangerous_type[]" class="form-control">
                        <option value="Rooms">Rooms</option>
                        <option value="Washrooms">Washrooms</option>
                        <option value="Wall">Wall</option>
                        <option value="Gate">Gate</option>
                        <option value="Branda">Branda</option>
                        <option value="Other">Other</option>
                      </select>
                    </td>
                    <td><input type="file" name="dangerous_photo[]" accept="image/*" class="form-control-file" /></td>
                    <td><button type="button" class="btn btn-sm btn-danger dangerous-remove">&times;</button></td>
                  </tr>
                </tbody>
              </table>
            </div>
            <button type="button" class="btn btn-sm btn-primary" id="dangerous-add-row"><i class="fa fa-plus"></i> Add Building</button>
          </div>
        </div>
      </div>

      <div class="card card-outline card-secondary">
        <div class="card-header">
          <h3 class="card-title">Remarks</h3>
        </div>
        <div class="card-body">
          <div class="form-group">
            <label for="remarks"><?php echo lang('remarks') ?></label>
            <textarea name="remarks" id="remarks" class="form-control" rows="3" placeholder="<?php echo lang('remarks_optional') ?>"><?php echo htmlspecialchars($visit->remarks, ENT_QUOTES, 'UTF-8'); ?></textarea>
          </div>
        </div>
      </div>

    </div>
    <!-- /.card-body -->

    <div class="card-footer">
      <div class="row">
        <div class="col"><a href="<?php echo url('/school_visits') ?>" onclick="return confirm('Are you sure you want to leave?')" class="btn btn-flat btn-danger"> <?php echo lang('cancel') ?></a></div>
        <div class="col text-right"><button type="submit" class="btn btn-flat btn-primary"> <?php echo lang('submit') ?></button></div>
      </div>
    </div>
    <!-- /.card-footer-->

    <?php echo form_close(); ?>

  </div>
  <!-- /.card -->

</section>
<!-- /.content -->

<script>
  $(document).ready(function() {
    $('.form-validate').validate({
      errorElement: 'span',
      errorPlacement: function(error, element) {
        error.addClass('invalid-feedback');
        element.closest('.form-group').append(error);
      },
      highlight: function(element, errorClass, validClass) {
        $(element).addClass('is-invalid');
      },
      unhighlight: function(element, errorClass, validClass) {
        $(element).removeClass('is-invalid');
      }
    });

    $('.select2').select2();

    function recalcTeachersGap() {
      const sis = parseInt($('#teachers-sis').val(), 10);
      const present = parseInt($('#teachers-present').val(), 10);
      const gap = (!isNaN(sis) && !isNaN(present)) ? sis - present : '';
      $('#teachers-gap').val(gap === '' ? '' : gap);
    }

    $('#teachers-sis, #teachers-present').on('input', recalcTeachersGap);
    recalcTeachersGap();

    function recalcStudentsGap() {
      const sis = parseInt($('#students-enrollment-sis').val(), 10);
      const present = parseInt($('#students-present').val(), 10);
      const gap = (!isNaN(sis) && !isNaN(present)) ? sis - present : '';
      $('#students-gap').val(gap === '' ? '' : gap);
    }

    $('#students-enrollment-sis, #students-present').on('input', recalcStudentsGap);
    recalcStudentsGap();

    function toggleDangerous() {
      const show = $('#dangerous-exists').val() === '1';
      $('#dangerous-wrap').toggleClass('d-none', !show);
    }

    $('#dangerous-exists').on('change', toggleDangerous);
    toggleDangerous();

    $('#dangerous-add-row').on('click', function() {
      const row = $('#dangerous-table tbody tr:first').clone();
      row.find('select').val('Rooms');
      row.find('input[type="file"]').val('');
      $('#dangerous-table tbody').append(row);
    });

    $('#dangerous-table').on('click', '.dangerous-remove', function() {
      const rows = $('#dangerous-table tbody tr').length;
      if (rows > 1) {
        $(this).closest('tr').remove();
      }
    });
  })
</script>

<?php include viewPath('includes/footer'); ?>
