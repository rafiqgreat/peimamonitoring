<?php
defined('BASEPATH') OR exit('No direct script access allowed'); ?>

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
        <a href="<?php echo url('school_visits') ?>" class="btn btn-flat btn-default btn-sm"><i class="fa fa-arrow-left"></i> &nbsp;&nbsp;  <?php echo lang('school_visits') ?></a>
        <?php if (logged('role') == 1 || hasPermissions('school_visits_edit')): ?>
          <a href="<?php echo url('school_visits/edit/'.$visit->id) ?>" class="btn btn-flat btn-primary btn-sm"><i class="fa fa-edit"></i> &nbsp;&nbsp;  <?php echo lang('edit_school_visit') ?></a>
        <?php endif ?>
      </div>
    </div>
    <div class="card-body">
      <div class="row">
        <div class="col-md-6">
          <table class="table table-bordered">
            <tr><th><?php echo lang('school') ?></th><td><?php echo $visit->school_name ?></td></tr>
            <tr><th><?php echo lang('visit_date') ?></th><td><?php echo date('Y-m-d', strtotime($visit->visit_date)) ?></td></tr>
            <tr><th><?php echo lang('school_status') ?></th><td><?php echo $visit->is_open ?></td></tr>
            <tr><th><?php echo lang('main_gate_condition') ?></th><td><?php echo $visit->main_gate_condition ? lang('condition_good') : lang('condition_poor') ?></td></tr>
            <tr><th><?php echo lang('classrooms_count') ?></th><td><?php echo $visit->classrooms_count ?></td></tr>
            <tr><th><?php echo lang('washrooms_count') ?></th><td><?php echo $visit->washrooms_count ?></td></tr>
            <tr><th><?php echo lang('teachers_count') ?></th><td><?php echo $visit->teachers_count ?></td></tr>
          </table>
        </div>
        <div class="col-md-6">
          <table class="table table-bordered">
            <tr><th><?php echo lang('students_by_class') ?></th><td><?php echo nl2br(htmlspecialchars($visit->students_by_class, ENT_QUOTES, 'UTF-8')); ?></td></tr>
            <tr><th><?php echo lang('remarks') ?></th><td><?php echo nl2br(htmlspecialchars($visit->remarks, ENT_QUOTES, 'UTF-8')); ?></td></tr>
            <tr><th><?php echo lang('visited_by') ?></th><td><?php echo $visit->visitor_name ?></td></tr>
            <tr><th><?php echo lang('created_at') ?></th><td><?php echo $visit->created_at ?></td></tr>
            <tr><th><?php echo lang('updated_at') ?></th><td><?php echo $visit->updated_at ?></td></tr>
          </table>
        </div>
      </div>
    </div>
  </div>

</section>
<!-- /.content -->

<?php include viewPath('includes/footer'); ?>
