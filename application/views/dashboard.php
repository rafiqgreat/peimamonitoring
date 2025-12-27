<?php
defined('BASEPATH') or exit('No direct script access allowed'); ?>

<?php include viewPath('includes/header'); ?>
<!-- Content Header (Page header) -->
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0 text-dark"> <?php echo lang('dashboard'); ?>
        </h1>
      </div><!-- /.col -->
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="#"><?php echo lang('home'); ?></a></li>
          <li class="breadcrumb-item active"><?php echo lang('dashboard'); ?> v1</li>
        </ol>
      </div><!-- /.col -->
    </div><!-- /.row -->
  </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->

<!-- Main content -->
<section class="content">
  <div class="container-fluid">
    <?php if (!empty($school_info) && (int) logged('role') === 3): ?>
      <div class="row mb-3">
        <div class="col-12">
          <div class="alert alert-success mb-0">
            <?php echo lang('school_code'); ?> : <?php echo $school_info->school_code; ?>,
            <?php echo lang('school'); ?> : <?php echo $school_info->school_name; ?>,
            <?php echo lang('school_district'); ?> : <?php echo !empty($school_info->district_name_en) ? $school_info->district_name_en : '-'; ?>,
            <?php echo lang('school_tehsil'); ?> : <?php echo !empty($school_info->tehsil_name_en) ? $school_info->tehsil_name_en : '-'; ?>,
            <?php echo lang('school_license_name'); ?> : <?php echo $school_info->school_license_name; ?>,
            <?php echo lang('school_license_orgname'); ?> : <?php echo $school_info->school_license_orgname; ?>.
          </div>
        </div>
      </div>
    <?php endif; ?>

    <div class="row mb-3">
      <div class="col-12 text-right">
        <?php if (logged('role') == 1 || hasPermissions('school_visits_add')): ?>
          <a href="<?php echo url('school_visits/add'); ?>" class="btn btn-primary btn-sm">
            <i class="fa fa-plus"></i> Add School Visit
          </a>
        <?php endif; ?>
      </div>
    </div>

    <!-- Small boxes (Stat box) -->
    <div class="row">
      <div class="col-lg-3 col-6">
        <!-- small box -->
        <div class="small-box bg-info">
          <div class="inner">
            <h3><?php echo isset($visit_count) ? (int) $visit_count : 0; ?></h3>

            <p>Visits / Inspections</p>
          </div>
          <div class="icon">
            <i class="ion ion-clipboard"></i>
          </div>
          <a href="<?php echo url('school_visits'); ?>" class="small-box-footer"><?php echo lang('dashboard_more_info'); ?><i class="fas fa-arrow-circle-right"></i></a>
          <a href="<?php echo url('school_visits'); ?>" class="btn btn-light btn-sm w-100 d-lg-none mt-2">View Visits</a>
        </div>
      </div>
      <?php if ((int) logged('role') == 3): ?>
        <div class="col-lg-3 col-6">
          <div class="small-box bg-success">
            <div class="inner">
              <h3><a href="<?php echo url('school_visits/add'); ?>" style="color:#ffffff;">Add Inspection</a></h3>
              <p>School Licensee / Heads must send</p>
            </div>
            <div class="icon">
              <i class="ion ion-alert-circled"></i>
            </div>
            <a href="<?php echo url('school_visits/add'); ?>" class="small-box-footer"><?php echo lang('dashboard_more_info'); ?><i class="fas fa-arrow-circle-right"></i></a>
          </div>
        </div>
      <?php endif; ?>
      <?php if ((int) logged('role') !== 3): ?>
        <div class="col-lg-3 col-6">
          <div class="small-box bg-success">
            <div class="inner">
              <h3><?php echo isset($schools_count) ? (int) $schools_count : 0; ?></h3>
              <p>Total Schools</p>
            </div>
            <div class="icon">
              <i class="ion ion-university"></i>
            </div>
            <a href="<?php echo url('schools'); ?>" class="small-box-footer"><?php echo lang('dashboard_more_info'); ?><i class="fas fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <div class="col-lg-3 col-6">
          <div class="small-box bg-danger">
            <div class="inner">
              <h3><?php echo isset($pending_schools) ? (int) $pending_schools : 0; ?></h3>
              <p>Pending Schools </p>
            </div>
            <div class="icon">
              <i class="ion ion-alert-circled"></i>
            </div>
            <a href="<?php echo url('schools'); ?>" class="small-box-footer"><?php echo lang('dashboard_more_info'); ?><i class="fas fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <div class="col-lg-3 col-6">
          <div class="small-box bg-warning">
            <div class="inner">
              <h3><?php echo isset($dangerous_schools) ? (int) $dangerous_schools : 0; ?></h3>
              <p>Dangerous Schools</p>
            </div>
            <div class="icon">
              <i class="ion ion-flame"></i>
            </div>
            <a href="<?php echo url('school_visits'); ?>" class="small-box-footer"><?php echo lang('dashboard_more_info'); ?><i class="fas fa-arrow-circle-right"></i></a>
          </div>
        </div>
      <?php endif; ?>

    </div>
    <!-- /.row -->

    <?php if ((int) logged('role') !== 3): ?>
      <div class="row">
        <div class="col-lg-12 col-12">
          <div class="card card-outline card-primary">
            <div class="card-header">
              <h3 class="card-title">Last 5 Visits</h3>
            </div>
            <div class="card-body p-0">
              <table class="table mb-0 table-striped">
                <thead>
                  <tr>
                    <th>District</th>
                    <th>Tehsil</th>
                    <th>School</th>
                    <th>Date</th>
                    <th>Time</th>

                    <th>Visited By</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                  <?php if (!empty($recent_visits)): ?>
                    <?php foreach ($recent_visits as $rv): ?>
                      <tr>
                        <td><?php echo !empty($rv->district_name_en) ? $rv->district_name_en : '-'; ?></td>
                        <td><?php echo !empty($rv->tehsil_name_en) ? $rv->tehsil_name_en : '-'; ?></td>
                        <td><?php echo !empty($rv->school_code) ? $rv->school_code . ' - ' . $rv->school_name : $rv->school_name; ?></td>
                        <td><?php echo !empty($rv->visit_date) ? date('Y-m-d', strtotime($rv->visit_date)) : '-'; ?></td>
                        <td><?php echo !empty($rv->visit_time) ? date('H:i', strtotime($rv->visit_time)) : '-'; ?></td>

                        <td><?php echo ((int) logged('role') === 3) ? 'Head of School' : (!empty($rv->visitor_name) ? $rv->visitor_name : '-'); ?></td>
                        <td><a href="<?php echo url('school_visits/view/' . $rv->id); ?>" class="btn btn-sm btn-default">View</a></td>
                      </tr>
                    <?php endforeach; ?>
                  <?php else: ?>
                    <tr>
                      <td colspan="7" class="text-center">No visits yet</td>
                    </tr>
                  <?php endif; ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    <?php endif; ?>

  </div><!-- /.container-fluid -->
</section>
<!-- /.content -->

<?php include viewPath('includes/footer'); ?>

<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<script src="<?php echo $url->assets ?>js/pages/dashboard.js"></script>
