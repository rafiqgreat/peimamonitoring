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
          <li class="breadcrumb-item active"><?php echo lang('schools') ?></li>
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
      <h3 class="card-title"><?php echo lang('list_all_schools') ?></h3>

      <div class="card-tools pull-right">
        <?php if (logged('role') == 1 || hasPermissions('schools_add')): ?>
          <a href="<?php echo url('schools/add') ?>" class="btn btn-primary btn-sm"><i class="fa fa-plus"></i> <?php echo lang('add_school') ?></a>
        <?php endif ?>
      </div>

    </div>
    <div class="card-body">
      <table id="dataTable1" class="table table-bordered table-striped">
        <thead>
          <tr>
            <th><?php echo lang('id') ?></th>
            <th><?php echo lang('school_code') ?></th>
            <th><?php echo lang('school_name') ?></th>
            <th><?php echo lang('school_district') ?></th>
            <th><?php echo lang('school_tehsil') ?></th>
            <th><?php echo lang('school_level') ?></th>
            <th><?php echo lang('school_status') ?></th>
            <th><?php echo lang('school_visits') ?></th>
            <th><?php echo lang('action') ?></th>
          </tr>
        </thead>
        <tbody>

          <?php foreach ($schools as $row): ?>
            <tr>
              <td width="60"><?php echo $row->school_id ?></td>
              <td><?php echo $row->school_code ?></td>
              <td><?php echo $row->school_name ?></td>
              <td><?php echo !empty($row->district_name_en) ? $row->district_name_en : '-' ?></td>
              <td><?php echo !empty($row->tehsil_name_en) ? $row->tehsil_name_en : '-' ?></td>
              <td><?php echo $row->school_level ?></td>
              <td>
                <?php if ($row->school_status === 'Open'): ?>
                  <span class="badge badge-success"><?php echo lang('school_status_open') ?></span>
                <?php else: ?>
                  <span class="badge badge-secondary"><?php echo lang('school_status_closed') ?></span>
                <?php endif ?>
              </td>
              <td>
                <a href="<?php echo url('school_visits?school_id='.$row->school_id) ?>">
                  <?php echo isset($row->visit_count) ? (int) $row->visit_count : 0; ?>
                </a>
              </td>
              <td>
                <?php if (logged('role') == 1 || hasPermissions('schools_view')): ?>
                  <a href="<?php echo url('schools/view/'.$row->school_id) ?>" class="btn btn-sm btn-default" title="<?php echo lang('view_school') ?>" data-toggle="tooltip"><i class="fas fa-eye"></i></a>
                <?php endif ?>
                <?php if (logged('role') == 1 || hasPermissions('schools_edit')): ?>
                  <a href="<?php echo url('schools/edit/'.$row->school_id) ?>" class="btn btn-sm btn-default" title="<?php echo lang('edit_school') ?>" data-toggle="tooltip"><i class="fas fa-edit"></i></a>
                <?php endif ?>
                <?php if (logged('role') == 1 || hasPermissions('schools_delete')): ?>
                  <a href="<?php echo url('schools/delete/'.$row->school_id) ?>" class="btn btn-sm btn-default" onclick='return confirm("<?php echo lang('delete_school_confirm') ?>")' title="<?php echo lang('delete_school') ?>" data-toggle="tooltip"><i class="fa fa-trash"></i></a>
                <?php endif ?>
              </td>
            </tr>
          <?php endforeach ?>

        </tbody>
      </table>
    </div>
    <!-- /.card-body -->
  </div>
  <!-- /.card -->

</section>
<!-- /.content -->

<?php include viewPath('includes/footer'); ?>

<script>
  $('#dataTable1').DataTable()
</script>
