<?php
defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<?php include viewPath('includes/header'); ?>

<!-- Content Header (Page header) -->
<section class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1><?php echo lang('districts') ?></h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="<?php echo url('/') ?>"><?php echo lang('home') ?></a></li>
          <li class="breadcrumb-item"><a href="<?php echo url('/districts') ?>"> <?php echo lang('districts') ?></a></li>
          <li class="breadcrumb-item active"> <?php echo lang('add_district') ?></li>
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
      <h3 class="card-title"> <?php echo lang('add_district') ?></h3>

      <div class="card-tools pull-right">
        <a href="<?php echo url('districts') ?>" class="btn btn-flat btn-default btn-sm"><i class="fa fa-arrow-left"></i> &nbsp;&nbsp;  <?php echo lang('districts') ?></a>
      </div>

    </div>

    <?php echo form_open('districts/save', [ 'class' => 'form-validate' ]); ?>
    <div class="card-body">

      <div class="form-group">
        <label for="district-code"> <?php echo lang('district_code') ?></label>
        <input type="text" class="form-control" name="district_code" id="district-code" required placeholder="<?php echo lang('district_code') ?>" autofocus />
      </div>

      <div class="form-group">
        <label for="district-name-en"> <?php echo lang('district_name_en') ?></label>
        <input type="text" class="form-control" name="district_name_en" id="district-name-en" required placeholder="<?php echo lang('district_name_en') ?>" />
      </div>

      <div class="form-group">
        <label for="district-name-ur"> <?php echo lang('district_name_ur') ?></label>
        <input type="text" class="form-control" name="district_name_ur" id="district-name-ur" required placeholder="<?php echo lang('district_name_ur') ?>" />
      </div>

      <div class="form-group">
        <label for="district-state-id"> <?php echo lang('district_state_id') ?> (<?php echo lang('optional') ?>)</label>
        <input type="number" class="form-control" name="district_state_id" id="district-state-id" placeholder="<?php echo lang('district_state_id') ?>" />
      </div>

      <div class="form-group">
        <label for="district-sort"> <?php echo lang('district_sort') ?></label>
        <input type="number" class="form-control" name="district_sort" id="district-sort" min="0" value="0" />
      </div>

      <div class="form-group">
        <label for="district-status"> <?php echo lang('district_status') ?></label>
        <select name="district_status" id="district-status" class="form-control">
          <option value="1"><?php echo lang('user_active') ?></option>
          <option value="0"><?php echo lang('user_inactive') ?></option>
        </select>
      </div>

    </div>
    <!-- /.card-body -->

    <div class="card-footer">
      <div class="row">
        <div class="col"><a href="<?php echo url('/districts') ?>" onclick="return confirm('Are you sure you want to leave?')" class="btn btn-flat btn-danger"> <?php echo lang('cancel') ?></a></div>
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
  })

</script>

<?php include viewPath('includes/footer'); ?>
