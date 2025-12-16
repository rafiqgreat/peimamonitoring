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
          <li class="breadcrumb-item active"> <?php echo lang('view_school_visit') ?></li>
        </ol>
      </div>
    </div>
  </div><!-- /.container-fluid -->
</section>

<!-- Main content -->
<section class="content">

  <div class="card">
    <div class="card-header with-border">
      <h3 class="card-title"><?php echo lang('view_school_visit') ?></h3>
      <div class="card-tools pull-right">
        <a href="<?php echo url('school_visits') ?>" class="btn btn-flat btn-default btn-sm"><i class="fa fa-arrow-left"></i> &nbsp;&nbsp; <?php echo lang('school_visits') ?></a>
        <?php if (logged('role') == 1 || hasPermissions('school_visits_edit')): ?>
          <a href="<?php echo url('school_visits/edit/' . $visit->id) ?>" class="btn btn-flat btn-primary btn-sm"><i class="fa fa-edit"></i> &nbsp;&nbsp; <?php echo lang('edit_school_visit') ?></a>
        <?php endif ?>
      </div>
    </div>
    <div class="card-body">

      <div class="alert alert-info">
        <h5 class="mb-2">Mandatory Compliance Requirements for All PEIMA Schools</h5>
        <p class="mb-2">Indicators to check during the visit or inspection:</p>
        <ul class="mb-0">
          <li><strong>Boundary Wall &amp; Main Gate:</strong> Repaired, painted, secure; school name and EMIS code visible.</li>
          <li><strong>Drinking Water:</strong> Clean, safe, potable, always available; source maintained.</li>
          <li><strong>Washrooms:</strong> Tiled floors, functional handwashing tap, soap available, cleaned daily.</li>
          <li><strong>Classrooms:</strong> Repaired/painted floors, walls, verandas, roofs; board available; ventilation/safety; working electricity, fans, lights; adequate furniture; no broken/unused material.</li>
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
              <table class="table table-bordered">
                <tr>
                  <th><?php echo lang('school') ?></th>
                  <td><?php echo $visit->school_name ?></td>
                </tr>
                <tr>
                  <th><?php echo lang('school_code') ?></th>
                  <td><?php echo $visit->school_code ?></td>
                </tr>
                <tr>
                  <th><?php echo lang('school_contact_name') ?></th>
                  <td><?php echo $visit->school_contact_name ?></td>
                </tr>
                <tr>
                  <th><?php echo lang('school_contact_mobile') ?></th>
                  <td><?php echo $visit->school_contact_mobile ?></td>
                </tr>
                <tr>
                  <th><?php echo lang('school_district') ?></th>
                  <td><?php echo !empty($visit->district_name_en) ? $visit->district_name_en : '-' ?></td>
                </tr>
                <tr>
                  <th><?php echo lang('school_tehsil') ?></th>
                  <td><?php echo !empty($visit->tehsil_name_en) ? $visit->tehsil_name_en : '-' ?></td>
                </tr>


              </table>
            </div>
            <div class="col-md-6">
              <table class="table table-bordered">
                <tr>
                  <th><?php echo lang('visited_by') ?></th>
                  <td><?php echo ((int) logged('role') === 3) ? 'Head of School' : $visit->visitor_name ?></td>
                </tr>
                <tr>
                  <th><?php echo lang('created_at') ?></th>
                  <td><?php echo $visit->created_at ?></td>
                </tr>
                <tr>
                  <th><?php echo lang('updated_at') ?></th>
                  <td><?php echo $visit->updated_at ?></td>
                </tr>
                <tr>
                  <th><?php echo lang('school_status') ?></th>
                  <td><?php echo $visit->is_open ?></td>
                </tr>
                <tr>
                  <th><?php echo lang('visit_date') ?></th>
                  <td><?php echo date('Y-m-d', strtotime($visit->visit_date)) ?></td>
                </tr>
                <tr>
                  <th><?php echo lang('visit_time') ?></th>
                  <td><?php echo !empty($visit->visit_time) ? date('H:i', strtotime($visit->visit_time)) : '-'; ?></td>
                </tr>
                <tr>
                  <th>School Gate Photo</th>
                  <td>
                    <?php if (!empty($visit->gate_photo)): ?>
                      <a href="javascript:void(0)" class="popup-photo" data-full="<?php echo base_url('uploads/' . $visit->gate_photo); ?>">
                        <img src="<?php echo base_url('uploads/' . $visit->gate_photo); ?>" alt="Gate Photo" style="height:60px" />
                      </a>
                    <?php else: ?>
                      -
                    <?php endif; ?>
                  </td>
                </tr>
              </table>
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
            <div class="col-md-6">
              <table class="table table-bordered">
                <tr>
                  <th>Head Name</th>
                  <td><?php echo $visit->head_name; ?></td>
                </tr>
                <tr>
                  <th>Gender</th>
                  <td><?php echo $visit->head_gender; ?></td>
                </tr>
                <tr>
                  <th>Head Contact</th>
                  <td><?php echo $visit->head_contact; ?></td>
                </tr>
              </table>
            </div>
            <div class="col-md-6">
              <table class="table table-bordered">
                <tr>
                  <th>Whatsapp No</th>
                  <td><?php echo $visit->head_whatsapp; ?></td>
                </tr>
                <tr>
                  <th>Head Email</th>
                  <td><?php echo $visit->head_email; ?></td>
                </tr>
                <tr>
                  <th>Head Photo</th>
                  <td>
                    <?php if (!empty($visit->head_photo)): ?>
                      <a href="javascript:void(0)" class="popup-photo" data-full="<?php echo base_url('uploads/' . $visit->head_photo); ?>">
                        <img src="<?php echo base_url('uploads/' . $visit->head_photo); ?>" alt="Head Photo" style="height:60px" />
                      </a>
                    <?php else: ?>
                      -
                    <?php endif; ?>
                  </td>
                </tr>
              </table>
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
            <div class="col-md-6">
              <table class="table table-bordered">
                <tr>
                  <th>Teachers as per SIS</th>
                  <td><?php echo $visit->teachers_as_per_sis; ?></td>
                </tr>
                <tr>
                  <th>Teachers as per Register</th>
                  <td><?php echo $visit->teachers_as_per_register; ?></td>
                </tr>
                <tr>
                  <th>Teachers Present</th>
                  <td><?php echo $visit->teachers_present; ?></td>
                </tr>
                <tr>
                  <th>Teachers Gap (SIS - Present)</th>
                  <td><?php echo $visit->teachers_gap; ?></td>
                </tr>
              </table>
            </div>
            <div class="col-md-6">
              <table class="table table-bordered">
                <tr>
                  <th>Enrollment as per SIS</th>
                  <td><?php echo $visit->students_enrollment_sis; ?></td>
                </tr>
                <tr>
                  <th>Total Enrollment as per Register</th>
                  <td><?php echo $visit->students_enrollment_register; ?></td>
                </tr>
                <tr>
                  <th>Total Present (Head count)</th>
                  <td><?php echo $visit->students_present; ?></td>
                </tr>
                <tr>
                  <th>Enrollment Gap (SIS - Present)</th>
                  <td><?php echo $visit->students_enrollment_gap; ?></td>
                </tr>
                <tr>
                  <th><?php echo lang('remarks') ?></th>
                  <td><?php echo nl2br(htmlspecialchars($visit->remarks, ENT_QUOTES, 'UTF-8')); ?></td>
                </tr>
              </table>
            </div>
          </div>
        </div>
      </div>
      <div class="card card-outline card-secondary mb-3">
        <div class="card-header">
          <h3 class="card-title">Dangerous Building</h3>
        </div>
        <div class="card-body">
          <table class="table table-bordered">
            <tr>
              <th>Exists</th>
              <td><?php echo $visit->dangerous_exists ? 'Yes' : 'No'; ?></td>
            </tr>
          </table>
          <?php if ($visit->dangerous_exists): ?>
            <div class="table-responsive mt-2">
              <table class="table table-bordered">
                <thead>
                  <tr>
                    <th>Type</th>
                    <th>Photo</th>
                  </tr>
                </thead>
                <tbody>
                  <?php if (!empty($dangerous_photos)): ?>
                    <?php foreach ($dangerous_photos as $dp): ?>
                      <tr>
                        <td><?php echo $dp->building_type; ?></td>
                        <td>
                          <?php if (!empty($dp->file_name)): ?>
                            <a href="javascript:void(0)" class="dangerous-photo-thumb" data-full="<?php echo base_url('uploads/' . $dp->file_name); ?>">
                              <img src="<?php echo base_url('uploads/' . $dp->file_name); ?>" alt="Dangerous Photo" style="height:60px" />
                            </a>
                          <?php else: ?>
                            -
                          <?php endif; ?>
                        </td>
                      </tr>
                    <?php endforeach; ?>
                  <?php else: ?>
                    <tr>
                      <td colspan="2" class="text-center">No dangerous building photos uploaded.</td>
                    </tr>
                  <?php endif; ?>
                </tbody>
              </table>
            </div>
          <?php endif; ?>
        </div>
      </div>

      <div class="card card-outline card-secondary mb-3">
        <div class="card-header">
          <h3 class="card-title">Flood Affected Areas</h3>
        </div>
        <div class="card-body">
          <table class="table table-bordered">
            <tr>
              <th>Exists</th>
              <td><?php echo !empty($visit->flood_exists) ? 'Yes' : 'No'; ?></td>
            </tr>
          </table>
          <?php if (!empty($visit->flood_exists)): ?>
            <div class="table-responsive mt-2">
              <table class="table table-bordered">
                <thead>
                  <tr>
                    <th>Type</th>
                    <th>Photo</th>
                  </tr>
                </thead>
                <tbody>
                  <?php if (!empty($flood_photos)): ?>
                    <?php foreach ($flood_photos as $fp): ?>
                      <tr>
                        <td><?php echo $fp->building_type; ?></td>
                        <td>
                          <?php if (!empty($fp->file_name)): ?>
                            <a href="javascript:void(0)" class="popup-photo" data-full="<?php echo base_url('uploads/' . $fp->file_name); ?>">
                              <img src="<?php echo base_url('uploads/' . $fp->file_name); ?>" alt="Flood Photo" style="height:60px" />
                            </a>
                          <?php else: ?>
                            -
                          <?php endif; ?>
                        </td>
                      </tr>
                    <?php endforeach; ?>
                  <?php else: ?>
                    <tr>
                      <td colspan="2" class="text-center">No flood photos uploaded.</td>
                    </tr>
                  <?php endif; ?>
                </tbody>
              </table>
            </div>
          <?php endif; ?>
        </div>
      </div>
      <div class="row">
        <div class="col-md-12">
          <?php $photos = isset($photos) ? $photos : []; ?>
          <?php
          $renderBeforeAfter = function ($beforeKey, $afterKey, $photos) {
            $parts = [];
            if (!empty($photos[$beforeKey])) {
              $parts[] = '<a href="javascript:void(0)" class="popup-photo" data-full="' . base_url('uploads/' . $photos[$beforeKey]->file_name) . '"><img src="' . base_url('uploads/' . $photos[$beforeKey]->thumb_name) . '" alt="Before" style="height:60px" /></a>';
            }
            if (!empty($photos[$afterKey])) {
              $parts[] = '<a href="javascript:void(0)" class="popup-photo" data-full="' . base_url('uploads/' . $photos[$afterKey]->file_name) . '"><img src="' . base_url('uploads/' . $photos[$afterKey]->thumb_name) . '" alt="After" style="height:60px" /></a>';
            }
            return !empty($parts) ? implode(' ', $parts) : '-';
          };
          ?>
          <h4 class="mt-3">Compliance Checklist</h4>
          <table class="table table-bordered table-striped" id="compliance-table">
            <thead>
              <tr>
                <th>Item</th>
                <th>Status</th>
                <th>Photos (Before / After)</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <th>Boundary wall &amp; main gate secure/repaired/painted; name/EMIS displayed</th>
                <td><?php echo $visit->boundary_wall_main_gate ? 'Yes' : 'No'; ?></td>
                <td><?php echo $renderBeforeAfter('boundary_wall_main_gate_before', 'boundary_wall_main_gate_after', $photos); ?>
                </td>
              </tr>
              <tr>
                <th>Drinking water clean, safe, available</th>
                <td><?php echo $visit->drinking_water_available ? 'Yes' : 'No'; ?></td>
                <td><?php echo $renderBeforeAfter('drinking_water_available_before', 'drinking_water_available_after', $photos); ?></td>
              </tr>
              <tr>
                <th>Washrooms (Before/After Photos)</th>
                <td>-</td>
                <td><?php echo $renderBeforeAfter('washrooms_before', 'washrooms_after', $photos); ?></td>
              </tr>
              <tr>
                <th>Washrooms have tiled floors</th>
                <td><?php echo $visit->washrooms_tiled_floors ? 'Yes' : 'No'; ?></td>
                <td><?php echo $renderBeforeAfter('washrooms_tiled_floors_before', 'washrooms_tiled_floors_after', $photos); ?></td>
              </tr>
              <tr>
                <th>Handwashing station with functional tap</th>
                <td><?php echo $visit->washrooms_handwashing_tap ? 'Yes' : 'No'; ?></td>
                <td><?php echo $renderBeforeAfter('washrooms_handwashing_tap_before', 'washrooms_handwashing_tap_after', $photos); ?></td>
              </tr>
              <tr>
                <th>Soap available</th>
                <td><?php echo $visit->washrooms_soap_available ? 'Yes' : 'No'; ?></td>
                <td><?php echo $renderBeforeAfter('washrooms_soap_available_before', 'washrooms_soap_available_after', $photos); ?></td>
              </tr>
              <tr>
                <th>Washrooms cleaned daily</th>
                <td><?php echo $visit->washrooms_clean_daily ? 'Yes' : 'No'; ?></td>
                <td><?php echo $renderBeforeAfter('washrooms_clean_daily_before', 'washrooms_clean_daily_after', $photos); ?></td>
              </tr>
              <tr>
                <th>Classrooms repaired/painted (floors, walls, verandas, roofs)</th>
                <td><?php echo $visit->classrooms_repaired_painted ? 'Yes' : 'No'; ?></td>
                <td><?php echo $renderBeforeAfter('classrooms_repaired_painted_before', 'classrooms_repaired_painted_after', $photos); ?></td>
              </tr>
              <tr>
                <th>Whiteboard/blackboard available</th>
                <td><?php echo $visit->classrooms_board_available ? 'Yes' : 'No'; ?></td>
                <td><?php echo $renderBeforeAfter('classrooms_board_available_before', 'classrooms_board_available_after', $photos); ?></td>
              </tr>
              <tr>
                <th>Proper ventilation and safety</th>
                <td><?php echo $visit->classrooms_ventilation_safety ? 'Yes' : 'No'; ?></td>
                <td><?php echo $renderBeforeAfter('classrooms_ventilation_safety_before', 'classrooms_ventilation_safety_after', $photos); ?></td>
              </tr>
              <tr>
                <th>Electricity/fans/lights functional</th>
                <td><?php echo $visit->classrooms_electricity_working ? 'Yes' : 'No'; ?></td>
                <td><?php echo $renderBeforeAfter('classrooms_electricity_working_before', 'classrooms_electricity_working_after', $photos); ?></td>
              </tr>
              <tr>
                <th>Sufficient student furniture</th>
                <td><?php echo $visit->classrooms_furniture_sufficient ? 'Yes' : 'No'; ?></td>
                <td><?php echo $renderBeforeAfter('classrooms_furniture_sufficient_before', 'classrooms_furniture_sufficient_after', $photos); ?></td>
              </tr>
              <tr>
                <th>No broken/unused material present</th>
                <td><?php echo $visit->classrooms_no_broken_material ? 'Yes' : 'No'; ?></td>
                <td><?php echo $renderBeforeAfter('classrooms_no_broken_material_before', 'classrooms_no_broken_material_after', $photos); ?></td>
              </tr>
              <tr>
                <th>School grounds clean and maintained</th>
                <td><?php echo $visit->school_grounds_clean ? 'Yes' : 'No'; ?></td>
                <td><?php echo $renderBeforeAfter('school_grounds_clean_before', 'school_grounds_clean_after', $photos); ?></td>
              </tr>
              <tr>
                <th>Grass/trees planted and maintained</th>
                <td><?php echo $visit->school_grounds_plants ? 'Yes' : 'No'; ?></td>
                <td><?php echo $renderBeforeAfter('school_grounds_plants_before', 'school_grounds_plants_after', $photos); ?></td>
              </tr>
              <tr>
                <th>Pathways with bricks or tuff tiles</th>
                <td><?php echo $visit->school_grounds_pathways ? 'Yes' : 'No'; ?></td>
                <td><?php echo $renderBeforeAfter('school_grounds_pathways_before', 'school_grounds_pathways_after', $photos); ?></td>
              </tr>
              <tr>
                <th>Secondary (optional): ECC room with LED</th>
                <td><?php echo $visit->secondary_ecc_room ? 'Yes' : 'No'; ?></td>
                <td><?php echo $renderBeforeAfter('secondary_ecc_room_before', 'secondary_ecc_room_after', $photos); ?></td>
              </tr>
              <tr>
                <th>Secondary (optional): Swings and slides</th>
                <td><?php echo $visit->secondary_swings_slides ? 'Yes' : 'No'; ?></td>
                <td><?php echo $renderBeforeAfter('secondary_swings_slides_before', 'secondary_swings_slides_after', $photos); ?></td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

</section>
<!-- /.content -->

<div class="modal fade" id="photoModal" tabindex="-1" role="dialog" aria-labelledby="photoModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="photoModalLabel">Checklist Photo</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body text-center">
        <img src="" alt="Checklist Photo" id="photoModalImg" class="img-fluid">
      </div>
    </div>
  </div>
</div>

<script>
  $('#compliance-table').on('click', 'a', function(e) {
    var full = $(this).data('full') || $(this).attr('href');
    if (!full || full === 'javascript:void(0)') {
      return;
    }
    e.preventDefault();
    $('#photoModalImg').attr('src', full);
    $('#photoModal').modal('show');
  });
  $('.dangerous-photo-thumb').on('click', function() {
    var full = $(this).data('full');
    $('#photoModalImg').attr('src', full);
    $('#photoModal').modal('show');
  });
  $('.popup-photo').on('click', function() {
    var full = $(this).data('full');
    $('#photoModalImg').attr('src', full);
    $('#photoModal').modal('show');
  });
</script>

<?php include viewPath('includes/footer'); ?>