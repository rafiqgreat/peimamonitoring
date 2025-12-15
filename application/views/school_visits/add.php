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
          <li class="breadcrumb-item active"> <?php echo lang('add_school_visit') ?></li>
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
      <h3 class="card-title"> <?php echo lang('add_school_visit') ?></h3>

      <div class="card-tools pull-right">
        <a href="<?php echo url('school_visits') ?>" class="btn btn-flat btn-default btn-sm"><i class="fa fa-arrow-left"></i> &nbsp;&nbsp; <?php echo lang('school_visits') ?></a>
      </div>

    </div>

    <?php echo form_open('school_visits/save', ['class' => 'form-validate']); ?>
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
                    <option
                      value="<?php echo $school->school_id ?>"
                      data-school_district_id="<?php echo htmlspecialchars($school->school_district_id, ENT_QUOTES, 'UTF-8'); ?>"
                      data-school_tehsil_id="<?php echo htmlspecialchars($school->school_tehsil_id, ENT_QUOTES, 'UTF-8'); ?>"
                      data-school_code="<?php echo htmlspecialchars($school->school_code, ENT_QUOTES, 'UTF-8'); ?>"
                      data-school_contact_name="<?php echo htmlspecialchars($school->school_contact_name, ENT_QUOTES, 'UTF-8'); ?>"
                      data-school_contact_mobile="<?php echo htmlspecialchars($school->school_contact_mobile, ENT_QUOTES, 'UTF-8'); ?>"
                      data-district_name_en="<?php echo htmlspecialchars($school->district_name_en, ENT_QUOTES, 'UTF-8'); ?>"
                      data-tehsil_name_en="<?php echo htmlspecialchars($school->tehsil_name_en, ENT_QUOTES, 'UTF-8'); ?>"
                        <?php echo ((int) logged('school_id') === (int) $school->school_id) ? 'selected' : '' ?>>
                      <?php echo trim($school->school_code) !== '' ? $school->school_code . ' - ' . $school->school_name : $school->school_name ?>
                    </option>
                  <?php endforeach ?>
                </select>
                <?php if ((int) logged('role') === 3): ?>
                  <input type="hidden" name="school_id" value="<?php echo logged('school_id') ?>">
                <?php endif ?>
              </div>
            </div>
            <div class="col-md-3">
              <div class="form-group">
                <label for="visit-date"><?php echo lang('visit_date') ?></label>
                <input type="date" class="form-control" name="visit_date" id="visit-date" value="<?php echo date('Y-m-d') ?>" required />
              </div>
            </div>
            <div class="col-md-3">
              <div class="form-group">
                <label for="visit-status"><?php echo lang('school_status') ?></label>
                <select name="is_open" id="visit-status" class="form-control">
                  <?php foreach ($status_options as $status): ?>
                    <option value="<?php echo $status ?>"><?php echo $status ?></option>
                  <?php endforeach ?>
                </select>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label for="visit-district"><?php echo lang('school_district') ?></label>
                <select id="visit-district" class="form-control select2">
                  <option value=""><?php echo lang('select_school') ?></option>
                  <?php foreach ($districts as $district): ?>
                    <option value="<?php echo $district->district_id ?>"><?php echo $district->district_name_en ?></option>
                  <?php endforeach ?>
                </select>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="visit-tehsil"><?php echo lang('school_tehsil') ?></label>
                <select id="visit-tehsil" class="form-control select2">
                  <option value=""><?php echo lang('select_school') ?></option>
                  <?php foreach ($tehsils as $tehsil): ?>
                    <option value="<?php echo $tehsil->tehsil_id ?>" data-district-id="<?php echo $tehsil->tehsil_district_id ?>"><?php echo $tehsil->tehsil_name_en ?></option>
                  <?php endforeach ?>
                </select>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-3">
              <div class="form-group">
                <label><?php echo lang('school_code') ?></label>
                <input type="text" class="form-control" id="visit-school-code" value="" readonly />
              </div>
            </div>
            <div class="col-md-3">
              <div class="form-group">
                <label><?php echo lang('school_contact_name') ?></label>
                <input type="text" class="form-control" id="visit-school-contact-name" value="" readonly />
              </div>
            </div>
            <div class="col-md-3">
              <div class="form-group">
                <label><?php echo lang('school_contact_mobile') ?></label>
                <input type="text" class="form-control" id="visit-school-contact-mobile" value="" readonly />
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
                  <option value="1">Yes</option>
                  <option value="0">No</option>
                </select>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label>Drinking water clean, safe, and available</label>
                <select name="drinking_water_available" class="form-control">
                  <option value="1">Yes</option>
                  <option value="0">No</option>
                </select>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label>Washrooms have tiled floors</label>
                <select name="washrooms_tiled_floors" class="form-control">
                  <option value="1">Yes</option>
                  <option value="0">No</option>
                </select>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label>Handwashing station with functional tap</label>
                <select name="washrooms_handwashing_tap" class="form-control">
                  <option value="1">Yes</option>
                  <option value="0">No</option>
                </select>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label>Soap available</label>
                <select name="washrooms_soap_available" class="form-control">
                  <option value="1">Yes</option>
                  <option value="0">No</option>
                </select>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label>Washrooms cleaned daily</label>
                <select name="washrooms_clean_daily" class="form-control">
                  <option value="1">Yes</option>
                  <option value="0">No</option>
                </select>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label>Classrooms repaired/painted (floors, walls, verandas, roofs)</label>
                <select name="classrooms_repaired_painted" class="form-control">
                  <option value="1">Yes</option>
                  <option value="0">No</option>
                </select>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label>Whiteboard/blackboard available</label>
                <select name="classrooms_board_available" class="form-control">
                  <option value="1">Yes</option>
                  <option value="0">No</option>
                </select>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label>Proper ventilation and safety</label>
                <select name="classrooms_ventilation_safety" class="form-control">
                  <option value="1">Yes</option>
                  <option value="0">No</option>
                </select>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label>Electricity/fans/lights functional</label>
                <select name="classrooms_electricity_working" class="form-control">
                  <option value="1">Yes</option>
                  <option value="0">No</option>
                </select>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label>Sufficient student furniture</label>
                <select name="classrooms_furniture_sufficient" class="form-control">
                  <option value="1">Yes</option>
                  <option value="0">No</option>
                </select>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label>No broken/unused material present</label>
                <select name="classrooms_no_broken_material" class="form-control">
                  <option value="1">Yes</option>
                  <option value="0">No</option>
                </select>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label>School grounds clean and maintained</label>
                <select name="school_grounds_clean" class="form-control">
                  <option value="1">Yes</option>
                  <option value="0">No</option>
                </select>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label>Grass/trees planted and maintained</label>
                <select name="school_grounds_plants" class="form-control">
                  <option value="1">Yes</option>
                  <option value="0">No</option>
                </select>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label>Pathways with bricks or tuff tiles</label>
                <select name="school_grounds_pathways" class="form-control">
                  <option value="1">Yes</option>
                  <option value="0">No</option>
                </select>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label>Secondary (optional): ECC room with LED</label>
                <select name="secondary_ecc_room" class="form-control">
                  <option value="1">Yes</option>
                  <option value="0">No</option>
                </select>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label>Secondary (optional): Swings and slides</label>
                <select name="secondary_swings_slides" class="form-control">
                  <option value="1">Yes</option>
                  <option value="0">No</option>
                </select>
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
            <div class="col-md-4">
              <div class="form-group">
                <label for="main-gate"><?php echo lang('main_gate_condition') ?></label>
                <select name="main_gate_condition" id="main-gate" class="form-control">
                  <option value="1"><?php echo lang('condition_good') ?></option>
                  <option value="0"><?php echo lang('condition_poor') ?></option>
                </select>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label for="classrooms-count"><?php echo lang('classrooms_count') ?></label>
                <input type="number" class="form-control" name="classrooms_count" id="classrooms-count" min="0" />
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label for="washrooms-count"><?php echo lang('washrooms_count') ?></label>
                <input type="number" class="form-control" name="washrooms_count" id="washrooms-count" min="0" />
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-4">
              <div class="form-group">
                <label for="teachers-count"><?php echo lang('teachers_count') ?></label>
                <input type="number" class="form-control" name="teachers_count" id="teachers-count" min="0" />
              </div>
            </div>
            <div class="col-md-8">
              <div class="form-group">
                <label for="students-by-class"><?php echo lang('students_by_class') ?></label>
                <textarea name="students_by_class" id="students-by-class" class="form-control" rows="3" placeholder="<?php echo lang('students_by_class_placeholder') ?>"></textarea>
              </div>
            </div>
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
            <textarea name="remarks" id="remarks" class="form-control" rows="3" placeholder="<?php echo lang('remarks_optional') ?>"></textarea>
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

    const allTehsilOptions = $('#visit-tehsil option').clone();

    function filterTehsilsByDistrict(districtId, presetTehsilId) {
      const tehsilSelect = $('#visit-tehsil');
      tehsilSelect.empty();
      tehsilSelect.append('<option value=""><?php echo lang('select_school') ?></option>');

      allTehsilOptions.each(function() {
        const opt = $(this);
        const optDistrict = opt.data('district-id');
        const isPlaceholder = opt.val() === '';
        if (isPlaceholder || !districtId || districtId === '' || optDistrict == districtId) {
          tehsilSelect.append(opt.clone());
        }
      });

      if (presetTehsilId) {
        tehsilSelect.val(presetTehsilId);
      }
      tehsilSelect.trigger('change.select2');
    }

    function fillSchoolMeta(option) {
      if (!option || !option.data('school_code')) {
        $('#visit-school-code').val('');
        $('#visit-school-contact-name').val('');
        $('#visit-school-contact-mobile').val('');
        $('#visit-school-district').val('');
        $('#visit-school-tehsil').val('');
        $('#visit-district').val('').trigger('change.select2');
        filterTehsilsByDistrict('', '');
        return;
      }

      $('#visit-school-code').val(option.data('school_code') || '');
      $('#visit-school-contact-name').val(option.data('school_contact_name') || '');
      $('#visit-school-contact-mobile').val(option.data('school_contact_mobile') || '');
      $('#visit-school-district').val(option.data('district_name_en') || '');
      $('#visit-school-tehsil').val(option.data('tehsil_name_en') || '');

      const districtId = option.data('school_district_id') || '';
      const tehsilId = option.data('school_tehsil_id') || '';
      $('#visit-district').val(districtId).trigger('change.select2');
      filterTehsilsByDistrict(districtId, tehsilId);
    }

    const selectedOption = $('#visit-school option:selected');
    fillSchoolMeta(selectedOption);

    $('#visit-school').on('change', function() {
      const opt = $(this).find('option:selected');
      fillSchoolMeta(opt);
    });

    $('#visit-district').on('change', function() {
      const districtId = $(this).val();
      filterTehsilsByDistrict(districtId, '');
    });
  })
</script>

<?php include viewPath('includes/footer'); ?>
