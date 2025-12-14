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
        <a href="<?php echo url('school_visits') ?>" class="btn btn-flat btn-default btn-sm"><i class="fa fa-arrow-left"></i> &nbsp;&nbsp;  <?php echo lang('school_visits') ?></a>
      </div>

    </div>

    <?php echo form_open('school_visits/update/'.$visit->id, [ 'class' => 'form-validate' ]); ?>
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
        <div class="col-md-3">
          <div class="form-group">
            <label for="visit-date"><?php echo lang('visit_date') ?></label>
            <input type="date" class="form-control" name="visit_date" id="visit-date" value="<?php echo date('Y-m-d', strtotime($visit->visit_date)) ?>" required />
          </div>
        </div>
        <div class="col-md-3">
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

      <div class="row">
        <div class="col-md-4">
          <div class="form-group">
            <label for="main-gate"><?php echo lang('main_gate_condition') ?></label>
            <select name="main_gate_condition" id="main-gate" class="form-control">
              <option value="1" <?php echo $visit->main_gate_condition ? 'selected' : '' ?>><?php echo lang('condition_good') ?></option>
              <option value="0" <?php echo !$visit->main_gate_condition ? 'selected' : '' ?>><?php echo lang('condition_poor') ?></option>
            </select>
          </div>
        </div>
        <div class="col-md-4">
          <div class="form-group">
            <label for="classrooms-count"><?php echo lang('classrooms_count') ?></label>
            <input type="number" class="form-control" name="classrooms_count" id="classrooms-count" min="0" value="<?php echo htmlspecialchars($visit->classrooms_count, ENT_QUOTES, 'UTF-8'); ?>" />
          </div>
        </div>
        <div class="col-md-4">
          <div class="form-group">
            <label for="washrooms-count"><?php echo lang('washrooms_count') ?></label>
            <input type="number" class="form-control" name="washrooms_count" id="washrooms-count" min="0" value="<?php echo htmlspecialchars($visit->washrooms_count, ENT_QUOTES, 'UTF-8'); ?>" />
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col-md-4">
          <div class="form-group">
            <label for="teachers-count"><?php echo lang('teachers_count') ?></label>
            <input type="number" class="form-control" name="teachers_count" id="teachers-count" min="0" value="<?php echo htmlspecialchars($visit->teachers_count, ENT_QUOTES, 'UTF-8'); ?>" />
          </div>
        </div>
        <div class="col-md-8">
          <div class="form-group">
            <label for="students-by-class"><?php echo lang('students_by_class') ?></label>
            <textarea name="students_by_class" id="students-by-class" class="form-control" rows="3" placeholder="<?php echo lang('students_by_class_placeholder') ?>"><?php echo htmlspecialchars($visit->students_by_class, ENT_QUOTES, 'UTF-8'); ?></textarea>
          </div>
        </div>
      </div>

      <div class="form-group">
        <label for="remarks"><?php echo lang('remarks') ?></label>
        <textarea name="remarks" id="remarks" class="form-control" rows="3" placeholder="<?php echo lang('remarks_optional') ?>"><?php echo htmlspecialchars($visit->remarks, ENT_QUOTES, 'UTF-8'); ?></textarea>
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
      errorPlacement: function (error, element) {
        error.addClass('invalid-feedback');
        element.closest('.form-group').append(error);
      },
      highlight: function (element, errorClass, validClass) {
        $(element).addClass('is-invalid');
      },
      unhighlight: function (element, errorClass, validClass) {
        $(element).removeClass('is-invalid');
      }
    });

    $('.select2').select2();
  })
</script>

<?php include viewPath('includes/footer'); ?>
