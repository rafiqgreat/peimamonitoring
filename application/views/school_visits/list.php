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
          <li class="breadcrumb-item active"><?php echo lang('school_visits') ?></li>
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
      <h3 class="card-title"><?php echo lang('list_all_school_visits') ?></h3>

      <div class="card-tools pull-right">
        <?php if (logged('role') == 1 || hasPermissions('school_visits_add')): ?>
          <a href="<?php echo url('school_visits/add') ?>" class="btn btn-primary btn-sm"><i class="fa fa-plus"></i> <?php echo lang('add_school_visit') ?></a>
        <?php endif ?>
      </div>

    </div>
    <div class="card-body">
      <table id="dataTable1" class="table table-bordered table-striped">
        <thead>
          <tr>
            <th><?php echo lang('id') ?></th>
            <th><?php echo lang('school_code') ?></th>
            <th><?php echo lang('school') ?></th>
            <th><?php echo lang('school_district') ?></th>
            <th><?php echo lang('school_tehsil') ?></th>
            <th><?php echo lang('visit_date') ?></th>
            <th><?php echo lang('visit_time') ?></th>
            <th><?php echo lang('school_status') ?></th>
            <th><?php echo lang('visited_by') ?></th>
            <th><?php echo lang('action') ?></th>
          </tr>
        </thead>
        <tbody>

          <?php foreach ($visits as $row): ?>
            <tr>
              <td width="60"><?php echo $row->id ?></td>
              <td><?php echo $row->school_code ?></td>
              <td><?php echo $row->school_name ?></td>
              <td><?php echo !empty($row->district_name_en) ? $row->district_name_en : '-'; ?></td>
              <td><?php echo !empty($row->tehsil_name_en) ? $row->tehsil_name_en : '-'; ?></td>
              <td><?php echo date('Y-m-d', strtotime($row->visit_date)) ?></td>
              <td><?php echo !empty($row->visit_time) ? date('H:i', strtotime($row->visit_time)) : '-'; ?></td>
              <td>
                <?php if ($row->is_open === 'Open'): ?>
                  <span class="badge badge-success"><?php echo lang('school_status_open') ?></span>
                <?php else: ?>
                  <span class="badge badge-secondary"><?php echo lang('school_status_closed') ?></span>
                <?php endif ?>
              </td>
              <td><?php echo ((int) logged('role') === 3) ? 'Head of School' : $row->visitor_name ?></td>
              <td>
                <?php if (logged('role') == 1 || hasPermissions('school_visits_view')): ?>
                  <a href="<?php echo url('school_visits/view/'.$row->id) ?>" class="btn btn-sm btn-default" title="<?php echo lang('view_school_visit') ?>" data-toggle="tooltip"><i class="fas fa-eye"></i></a>
                <?php endif ?>
                <?php if (logged('role') == 1 || hasPermissions('school_visits_edit')): ?>
                  <a href="<?php echo url('school_visits/edit/'.$row->id) ?>" class="btn btn-sm btn-default" title="<?php echo lang('edit_school_visit') ?>" data-toggle="tooltip"><i class="fas fa-edit"></i></a>
                <?php endif ?>
                <?php if (logged('role') == 1 || hasPermissions('school_visits_delete')): ?>
                  <a href="<?php echo url('school_visits/delete/'.$row->id) ?>" class="btn btn-sm btn-default" onclick='return confirm("<?php echo lang('delete_school_visit_confirm') ?>")' title="<?php echo lang('delete_school_visit') ?>" data-toggle="tooltip"><i class="fa fa-trash"></i></a>
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
