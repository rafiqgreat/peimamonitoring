<?php
defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<?php include viewPath('includes/header'); ?>

<!-- Content Header (Page header) -->
<section class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1><?php echo lang('tehsils') ?></h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="<?php echo url('/') ?>"><?php echo lang('home') ?></a></li>
          <li class="breadcrumb-item"><a href="<?php echo url('/tehsils') ?>"> <?php echo lang('tehsils') ?></a></li>
          <li class="breadcrumb-item active"> <?php echo lang('add_tehsil') ?></li>
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
      <h3 class="card-title"> <?php echo lang('add_tehsil') ?></h3>

      <div class="card-tools pull-right">
        <a href="<?php echo url('tehsils') ?>" class="btn btn-flat btn-default btn-sm"><i class="fa fa-arrow-left"></i> &nbsp;&nbsp;  <?php echo lang('tehsils') ?></a>
      </div>

    </div>

    <?php echo form_open('tehsils/save', [ 'class' => 'form-validate' ]); ?>
    <div class="card-body">

      <div class="form-group">
        <label for="tehsil-code"> <?php echo lang('tehsil_code') ?></label>
        <input type="text" class="form-control" name="tehsil_code" id="tehsil-code" required placeholder="<?php echo lang('tehsil_code') ?>" autofocus />
      </div>

      <div class="form-group">
        <label for="tehsil-name-en"> <?php echo lang('tehsil_name_en') ?></label>
        <input type="text" class="form-control" name="tehsil_name_en" id="tehsil-name-en" required placeholder="<?php echo lang('tehsil_name_en') ?>" />
      </div>

      <div class="form-group">
        <label for="tehsil-name-ur"> <?php echo lang('tehsil_name_ur') ?></label>
        <input type="text" class="form-control" name="tehsil_name_ur" id="tehsil-name-ur" required placeholder="<?php echo lang('tehsil_name_ur') ?>" />
      </div>

      <div class="form-group">
        <label for="tehsil-district-id"> <?php echo lang('tehsil_district') ?></label>
        <select name="tehsil_district_id" id="tehsil-district-id" class="form-control" required>
          <option value=""><?php echo lang('select_district') ?></option>
          <?php foreach ($districts as $district): ?>
            <option value="<?php echo $district->district_id ?>"><?php echo $district->district_name_en ?></option>
          <?php endforeach ?>
        </select>
      </div>

      <div class="form-group">
        <label for="tehsil-state-id"> <?php echo lang('tehsil_state_id') ?> (<?php echo lang('optional') ?>)</label>
        <input type="number" class="form-control" name="tehsil_state_id" id="tehsil-state-id" placeholder="<?php echo lang('tehsil_state_id') ?>" />
      </div>

      <div class="form-group">
        <label for="tehsil-order"> <?php echo lang('tehsil_order') ?></label>
        <input type="number" class="form-control" name="tehsil_order" id="tehsil-order" min="0" value="0" />
      </div>

      <div class="form-group">
        <label for="tehsil-status"> <?php echo lang('tehsil_status') ?></label>
        <select name="tehsil_status" id="tehsil-status" class="form-control">
          <option value="1"><?php echo lang('user_active') ?></option>
          <option value="0"><?php echo lang('user_inactive') ?></option>
        </select>
      </div>

    </div>
    <!-- /.card-body -->

    <div class="card-footer">
      <div class="row">
        <div class="col"><a href="<?php echo url('/tehsils') ?>" onclick="return confirm('Are you sure you want to leave?')" class="btn btn-flat btn-danger"> <?php echo lang('cancel') ?></a></div>
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
