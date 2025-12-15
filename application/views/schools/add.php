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
          <li class="breadcrumb-item active"> <?php echo lang('add_school') ?></li>
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
      <h3 class="card-title"> <?php echo lang('add_school') ?></h3>

      <div class="card-tools pull-right">
        <a href="<?php echo url('schools') ?>" class="btn btn-flat btn-default btn-sm"><i class="fa fa-arrow-left"></i> &nbsp;&nbsp;  <?php echo lang('schools') ?></a>
      </div>

    </div>

    <?php echo form_open('schools/save', [ 'class' => 'form-validate' ]); ?>
    <div class="card-body">

      <div class="row">
        <div class="col-md-6">
          <div class="form-group">
            <label for="school-code"> <?php echo lang('school_code') ?></label>
            <input type="text" class="form-control" name="school_code" id="school-code" placeholder="<?php echo lang('school_code') ?>" />
          </div>
        </div>
        <div class="col-md-6">
          <div class="form-group">
            <label for="school-name"> <?php echo lang('school_name') ?></label>
            <input type="text" class="form-control" name="school_name" id="school-name" required placeholder="<?php echo lang('school_name') ?>" />
          </div>
        </div>
      </div>

      <div class="form-group">
        <label for="school-address"> <?php echo lang('school_address') ?></label>
        <input type="text" class="form-control" name="school_address" id="school-address" placeholder="<?php echo lang('school_address') ?>" />
      </div>

      <div class="row">
        <div class="col-md-6">
          <div class="form-group">
            <label for="school-district-id"> <?php echo lang('school_district') ?></label>
            <select name="school_district_id" id="school-district-id" class="form-control">
              <option value=""><?php echo lang('select_district') ?></option>
              <?php foreach ($districts as $district): ?>
                <option value="<?php echo $district->district_id ?>"><?php echo $district->district_name_en ?></option>
              <?php endforeach ?>
            </select>
          </div>
        </div>
        <div class="col-md-6">
          <div class="form-group">
            <label for="school-tehsil-id"> <?php echo lang('school_tehsil') ?></label>
            <select name="school_tehsil_id" id="school-tehsil-id" class="form-control">
              <option value=""><?php echo lang('select_tehsil') ?></option>
              <?php foreach ($tehsils as $tehsil): ?>
                <option value="<?php echo $tehsil->tehsil_id ?>" data-district-id="<?php echo $tehsil->tehsil_district_id ?>"><?php echo $tehsil->tehsil_name_en ?></option>
              <?php endforeach ?>
            </select>
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col-md-6">
          <div class="form-group">
            <label for="school-level"> <?php echo lang('school_level') ?></label>
            <select name="school_level" id="school-level" class="form-control" required>
              <?php foreach ($levels as $level): ?>
                <option value="<?php echo $level ?>"><?php echo $level ?></option>
              <?php endforeach ?>
            </select>
          </div>
        </div>
        <div class="col-md-6">
          <div class="form-group">
            <label for="school-gender"> <?php echo lang('school_gender') ?></label>
            <select name="school_gender" id="school-gender" class="form-control" required>
              <?php foreach ($genders as $gender): ?>
                <option value="<?php echo $gender ?>"><?php echo $gender ?></option>
              <?php endforeach ?>
            </select>
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col-md-6">
          <div class="form-group">
            <label for="school-email"> <?php echo lang('school_email') ?> (<?php echo lang('optional') ?>)</label>
            <input type="email" class="form-control" name="school_email" id="school-email" placeholder="<?php echo lang('school_email') ?>" />
          </div>
        </div>
        <div class="col-md-3">
          <div class="form-group">
            <label for="school-mobile"> <?php echo lang('school_mobile') ?> (<?php echo lang('optional') ?>)</label>
            <input type="text" class="form-control" name="school_mobile" id="school-mobile" placeholder="<?php echo lang('school_mobile') ?>" />
          </div>
        </div>
        <div class="col-md-3">
          <div class="form-group">
            <label for="school-whatapp"> <?php echo lang('school_whatapp') ?> (<?php echo lang('optional') ?>)</label>
            <input type="text" class="form-control" name="school_whatapp" id="school-whatapp" placeholder="<?php echo lang('school_whatapp') ?>" />
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col-md-3">
          <div class="form-group">
            <label for="school-lat"> <?php echo lang('school_lat') ?> (<?php echo lang('optional') ?>)</label>
            <input type="text" class="form-control" name="school_lat" id="school-lat" placeholder="<?php echo lang('school_lat') ?>" />
          </div>
        </div>
        <div class="col-md-3">
          <div class="form-group">
            <label for="school-long"> <?php echo lang('school_long') ?> (<?php echo lang('optional') ?>)</label>
            <input type="text" class="form-control" name="school_long" id="school-long" placeholder="<?php echo lang('school_long') ?>" />
          </div>
        </div>
        <div class="col-md-3">
          <div class="form-group">
            <label for="school-total-students"> <?php echo lang('school_total_students') ?> (<?php echo lang('optional') ?>)</label>
            <input type="number" class="form-control" name="school_total_students" id="school-total-students" min="0" />
          </div>
        </div>
        <div class="col-md-3">
          <div class="form-group">
            <label for="school-total-teachers"> <?php echo lang('school_total_teachers') ?> (<?php echo lang('optional') ?>)</label>
            <input type="number" class="form-control" name="school_total_teachers" id="school-total-teachers" min="0" />
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col-md-6">
          <div class="form-group">
            <label for="school-status"> <?php echo lang('school_status') ?></label>
            <select name="school_status" id="school-status" class="form-control" required>
              <?php foreach ($statuses as $status): ?>
                <option value="<?php echo $status ?>"><?php echo $status ?></option>
              <?php endforeach ?>
            </select>
          </div>
        </div>
        <div class="col-md-6">
          <div class="form-group">
            <label for="school-allocated-category"> <?php echo lang('school_allocated_category') ?></label>
            <select name="school_allocated_category" id="school-allocated-category" class="form-control" required>
              <?php foreach ($categories as $cat): ?>
                <option value="<?php echo $cat ?>"><?php echo $cat ?></option>
              <?php endforeach ?>
            </select>
          </div>
        </div>
      </div>

      <div class="form-group">
        <label for="school-others"> <?php echo lang('school_others') ?> (<?php echo lang('optional') ?>)</label>
        <input type="text" class="form-control" name="school_others" id="school-others" placeholder="<?php echo lang('school_others') ?>" />
      </div>

      <div class="form-group">
        <label for="school-remarks"> <?php echo lang('school_remarks') ?> (<?php echo lang('optional') ?>)</label>
        <textarea class="form-control" name="school_remarks" id="school-remarks" rows="3" placeholder="<?php echo lang('school_remarks') ?>"></textarea>
      </div>

      <div class="card card-secondary">
        <div class="card-header">
          <h3 class="card-title"><?php echo lang('school_license') ?></h3>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label for="school-license-name"> <?php echo lang('school_license_name') ?></label>
                <input type="text" class="form-control" name="school_license_name" id="school-license-name" required placeholder="<?php echo lang('school_license_name') ?>" />
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="school-license-mobile"> <?php echo lang('school_license_mobile') ?></label>
                <input type="text" class="form-control" name="school_license_mobile" id="school-license-mobile" required placeholder="<?php echo lang('school_license_mobile') ?>" />
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label for="school-license-email"> <?php echo lang('school_license_email') ?></label>
                <input type="email" class="form-control" name="school_license_email" id="school-license-email" required placeholder="<?php echo lang('school_license_email') ?>" />
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="school-license-orgname"> <?php echo lang('school_license_orgname') ?></label>
                <input type="text" class="form-control" name="school_license_orgname" id="school-license-orgname" required placeholder="<?php echo lang('school_license_orgname') ?>" />
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="card card-secondary">
        <div class="card-header">
          <h3 class="card-title"><?php echo lang('school_contact') ?></h3>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label for="school-contact-name"> <?php echo lang('school_contact_name') ?></label>
                <input type="text" class="form-control" name="school_contact_name" id="school-contact-name" required placeholder="<?php echo lang('school_contact_name') ?>" />
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="school-contact-mobile"> <?php echo lang('school_contact_mobile') ?></label>
                <input type="text" class="form-control" name="school_contact_mobile" id="school-contact-mobile" required placeholder="<?php echo lang('school_contact_mobile') ?>" />
              </div>
            </div>
          </div>
        </div>
      </div>

    </div>
    <!-- /.card-body -->

    <div class="card-footer">
      <div class="row">
        <div class="col"><a href="<?php echo url('/schools') ?>" onclick="return confirm('Are you sure you want to leave?')" class="btn btn-flat btn-danger"> <?php echo lang('cancel') ?></a></div>
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

    const allTehsilOptions = $('#school-tehsil-id option').clone();

    function filterTehsils(districtId) {
      const tehsilSelect = $('#school-tehsil-id');
      tehsilSelect.empty();
      tehsilSelect.append('<option value=""><?php echo lang('select_tehsil') ?></option>');

      allTehsilOptions.each(function() {
        const opt = $(this);
        const optDistrict = opt.data('district-id');
        const isPlaceholder = opt.val() === '';
        if (isPlaceholder || !districtId || optDistrict == districtId) {
          tehsilSelect.append(opt.clone());
        }
      });
    }

    $('#school-district-id').on('change', function() {
      filterTehsils($(this).val());
    });

    // Initialize tehsils list on page load.
    filterTehsils($('#school-district-id').val());
  })

</script>

<?php include viewPath('includes/footer'); ?>
