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
          <li class="breadcrumb-item active"><?php echo lang('districts') ?></li>
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
      <h3 class="card-title"><?php echo lang('list_all_districts') ?></h3>

      <div class="card-tools pull-right">
        <?php if (logged('role') == 1 || hasPermissions('districts_add')): ?>
          <a href="<?php echo url('districts/add') ?>" class="btn btn-primary btn-sm"><i class="fa fa-plus"></i> <?php echo lang('add_district') ?></a>
        <?php endif ?>
      </div>

    </div>
    <div class="card-body">
      <table id="dataTable1" class="table table-bordered table-striped">
        <thead>
          <tr>
            <th><?php echo lang('id') ?></th>
            <th><?php echo lang('district_code') ?></th>
            <th><?php echo lang('district_name_en') ?></th>
            <th><?php echo lang('district_name_ur') ?></th>
            <th><?php echo lang('district_sort') ?></th>
            <th><?php echo lang('district_status') ?></th>
            <th><?php echo lang('action') ?></th>
          </tr>
        </thead>
        <tbody>

          <?php foreach ($districts as $row): ?>
            <tr>
              <td width="60"><?php echo $row->district_id ?></td>
              <td><?php echo $row->district_code ?></td>
              <td><?php echo $row->district_name_en ?></td>
              <td><?php echo $row->district_name_ur ?></td>
              <td><?php echo (int) $row->district_sort ?></td>
              <td>
                <?php if ($row->district_status): ?>
                  <span class="badge badge-success"><?php echo lang('user_active') ?></span>
                <?php else: ?>
                  <span class="badge badge-secondary"><?php echo lang('user_inactive') ?></span>
                <?php endif ?>
              </td>
              <td>
                <?php if (logged('role') == 1 || hasPermissions('districts_edit')): ?>
                  <a href="<?php echo url('districts/edit/'.$row->district_id) ?>" class="btn btn-sm btn-default" title="<?php echo lang('edit_district') ?>" data-toggle="tooltip"><i class="fas fa-edit"></i></a>
                <?php endif ?>
                <?php if (logged('role') == 1 || hasPermissions('districts_delete')): ?>
                  <a href="<?php echo url('districts/delete/'.$row->district_id) ?>" class="btn btn-sm btn-default" onclick='return confirm("<?php echo lang('delete_district_confirm') ?>")' title="<?php echo lang('delete_district') ?>" data-toggle="tooltip"><i class="fa fa-trash"></i></a>
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
