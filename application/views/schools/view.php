<?php
defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<?php include viewPath('includes/header'); ?>

<!-- Content Header (Page header) -->
<section class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1><?php echo lang('schools') ?></h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="<?php echo url('/') ?>"><?php echo lang('home') ?></a></li>
          <li class="breadcrumb-item"><a href="<?php echo url('/schools') ?>"> <?php echo lang('schools') ?></a></li>
          <li class="breadcrumb-item active"> <?php echo lang('view_school') ?></li>
        </ol>
      </div>
    </div>
  </div><!-- /.container-fluid -->
</section>

<!-- Main content -->
<section class="content">

  <div class="card">
    <div class="card-header with-border">
      <h3 class="card-title"><?php echo lang('view_school') ?></h3>
      <div class="card-tools pull-right">
        <a href="<?php echo url('schools') ?>" class="btn btn-flat btn-default btn-sm"><i class="fa fa-arrow-left"></i> &nbsp;&nbsp;  <?php echo lang('schools') ?></a>
        <?php if (logged('role') == 1 || hasPermissions('schools_edit')): ?>
          <a href="<?php echo url('schools/edit/'.$school->school_id) ?>" class="btn btn-flat btn-primary btn-sm"><i class="fa fa-edit"></i> &nbsp;&nbsp;  <?php echo lang('edit_school') ?></a>
        <?php endif ?>
      </div>
    </div>
    <div class="card-body">
      <div class="row">
        <div class="col-md-6">
          <table class="table table-bordered">
            <tr><th><?php echo lang('school_code') ?></th><td><?php echo $school->school_code ?></td></tr>
            <tr><th><?php echo lang('school_name') ?></th><td><?php echo $school->school_name ?></td></tr>
            <tr><th><?php echo lang('school_address') ?></th><td><?php echo $school->school_address ?></td></tr>
            <tr><th><?php echo lang('school_district') ?></th><td><?php echo !empty($school->district_name_en) ? $school->district_name_en : '-' ?></td></tr>
            <tr><th><?php echo lang('school_tehsil') ?></th><td><?php echo !empty($school->tehsil_name_en) ? $school->tehsil_name_en : '-' ?></td></tr>
            <tr><th><?php echo lang('school_level') ?></th><td><?php echo $school->school_level ?></td></tr>
            <tr><th><?php echo lang('school_gender') ?></th><td><?php echo $school->school_gender ?></td></tr>
            <tr><th><?php echo lang('school_status') ?></th><td><?php echo $school->school_status ?></td></tr>
            <tr><th><?php echo lang('school_remarks') ?></th><td><?php echo $school->school_remarks ?></td></tr>
            <tr><th><?php echo lang('school_allocated_category') ?></th><td><?php echo $school->school_allocated_category ?></td></tr>
            <tr><th><?php echo lang('school_others') ?></th><td><?php echo $school->school_others ?></td></tr>
          </table>
        </div>
        <div class="col-md-6">
          <table class="table table-bordered">
            <tr><th><?php echo lang('school_email') ?></th><td><?php echo $school->school_email ?></td></tr>
            <tr><th><?php echo lang('school_mobile') ?></th><td><?php echo $school->school_mobile ?></td></tr>
            <tr><th><?php echo lang('school_whatapp') ?></th><td><?php echo $school->school_whatapp ?></td></tr>
            <tr><th><?php echo lang('school_lat') ?></th><td><?php echo $school->school_lat ?></td></tr>
            <tr><th><?php echo lang('school_long') ?></th><td><?php echo $school->school_long ?></td></tr>
            <tr><th><?php echo lang('school_total_students') ?></th><td><?php echo $school->school_total_students ?></td></tr>
            <tr><th><?php echo lang('school_total_teachers') ?></th><td><?php echo $school->school_total_teachers ?></td></tr>
            <tr><th><?php echo lang('school_contact_name') ?></th><td><?php echo $school->school_contact_name ?></td></tr>
            <tr><th><?php echo lang('school_contact_mobile') ?></th><td><?php echo $school->school_contact_mobile ?></td></tr>
            <tr><th><?php echo lang('school_license_name') ?></th><td><?php echo $school->school_license_name ?></td></tr>
            <tr><th><?php echo lang('school_license_mobile') ?></th><td><?php echo $school->school_license_mobile ?></td></tr>
            <tr><th><?php echo lang('school_license_email') ?></th><td><?php echo $school->school_license_email ?></td></tr>
            <tr><th><?php echo lang('school_license_orgname') ?></th><td><?php echo $school->school_license_orgname ?></td></tr>
          </table>
        </div>
      </div>
    </div>
  </div>

</section>
<!-- /.content -->

<?php include viewPath('includes/footer'); ?>
