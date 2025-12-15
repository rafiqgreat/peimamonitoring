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
                  <td><?php echo $visit->visitor_name ?></td>
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
                  <th><?php echo lang('visit_date') ?></th>
                  <td><?php echo date('Y-m-d', strtotime($visit->visit_date)) ?></td>
                </tr>
                <tr>
                  <th><?php echo lang('school_status') ?></th>
                  <td><?php echo $visit->is_open ?></td>
                </tr>
              </table>
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
            <div class="col-md-6">
              <table class="table table-bordered">
                <tr>
                  <th><?php echo lang('main_gate_condition') ?></th>
                  <td><?php echo $visit->main_gate_condition ? lang('condition_good') : lang('condition_poor') ?></td>
                </tr>
                <tr>
                  <th><?php echo lang('classrooms_count') ?></th>
                  <td><?php echo $visit->classrooms_count ?></td>
                </tr>
                <tr>
                  <th><?php echo lang('washrooms_count') ?></th>
                  <td><?php echo $visit->washrooms_count ?></td>
                </tr>
                <tr>
                  <th><?php echo lang('teachers_count') ?></th>
                  <td><?php echo $visit->teachers_count ?></td>
                </tr>
              </table>
            </div>
            <div class="col-md-6">
              <table class="table table-bordered">
                <tr>
                  <th><?php echo lang('students_by_class') ?></th>
                  <td><?php echo nl2br(htmlspecialchars($visit->students_by_class, ENT_QUOTES, 'UTF-8')); ?></td>
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
      <div class="row">
        <div class="col-md-12">
          <h4 class="mt-3">Compliance Checklist</h4>
          <table class="table table-bordered table-striped">
            <tr>
              <th>Boundary wall &amp; main gate secure/repaired/painted; name/EMIS displayed</th>
              <td><?php echo $visit->boundary_wall_main_gate ? 'Yes' : 'No'; ?></td>
            </tr>
            <tr>
              <th>Drinking water clean, safe, available</th>
              <td><?php echo $visit->drinking_water_available ? 'Yes' : 'No'; ?></td>
            </tr>
            <tr>
              <th>Washrooms have tiled floors</th>
              <td><?php echo $visit->washrooms_tiled_floors ? 'Yes' : 'No'; ?></td>
            </tr>
            <tr>
              <th>Handwashing station with functional tap</th>
              <td><?php echo $visit->washrooms_handwashing_tap ? 'Yes' : 'No'; ?></td>
            </tr>
            <tr>
              <th>Soap available</th>
              <td><?php echo $visit->washrooms_soap_available ? 'Yes' : 'No'; ?></td>
            </tr>
            <tr>
              <th>Washrooms cleaned daily</th>
              <td><?php echo $visit->washrooms_clean_daily ? 'Yes' : 'No'; ?></td>
            </tr>
            <tr>
              <th>Classrooms repaired/painted (floors, walls, verandas, roofs)</th>
              <td><?php echo $visit->classrooms_repaired_painted ? 'Yes' : 'No'; ?></td>
            </tr>
            <tr>
              <th>Whiteboard/blackboard available</th>
              <td><?php echo $visit->classrooms_board_available ? 'Yes' : 'No'; ?></td>
            </tr>
            <tr>
              <th>Proper ventilation and safety</th>
              <td><?php echo $visit->classrooms_ventilation_safety ? 'Yes' : 'No'; ?></td>
            </tr>
            <tr>
              <th>Electricity/fans/lights functional</th>
              <td><?php echo $visit->classrooms_electricity_working ? 'Yes' : 'No'; ?></td>
            </tr>
            <tr>
              <th>Sufficient student furniture</th>
              <td><?php echo $visit->classrooms_furniture_sufficient ? 'Yes' : 'No'; ?></td>
            </tr>
            <tr>
              <th>No broken/unused material present</th>
              <td><?php echo $visit->classrooms_no_broken_material ? 'Yes' : 'No'; ?></td>
            </tr>
            <tr>
              <th>School grounds clean and maintained</th>
              <td><?php echo $visit->school_grounds_clean ? 'Yes' : 'No'; ?></td>
            </tr>
            <tr>
              <th>Grass/trees planted and maintained</th>
              <td><?php echo $visit->school_grounds_plants ? 'Yes' : 'No'; ?></td>
            </tr>
            <tr>
              <th>Pathways with bricks or tuff tiles</th>
              <td><?php echo $visit->school_grounds_pathways ? 'Yes' : 'No'; ?></td>
            </tr>
            <tr>
              <th>Secondary (optional): ECC room with LED</th>
              <td><?php echo $visit->secondary_ecc_room ? 'Yes' : 'No'; ?></td>
            </tr>
            <tr>
              <th>Secondary (optional): Swings and slides</th>
              <td><?php echo $visit->secondary_swings_slides ? 'Yes' : 'No'; ?></td>
            </tr>
          </table>
        </div>
      </div>
    </div>
  </div>

</section>
<!-- /.content -->

<?php include viewPath('includes/footer'); ?>