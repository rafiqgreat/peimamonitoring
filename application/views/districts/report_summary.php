<?php
defined('BASEPATH') or exit('No direct script access allowed'); ?>

<?php include viewPath('includes/header'); ?>

<section class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1>District Report Summary</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="<?php echo url('/'); ?>">Home</a></li>
          <li class="breadcrumb-item active">District Report Summary</li>
        </ol>
      </div>
    </div>
  </div>
</section>

<section class="content">
  <div class="card">
    <div class="card-header with-border">
      <h3 class="card-title">Summary</h3>
    </div>
    <div class="card-body">
      <div class="table-responsive">
        <table class="table table-bordered table-striped mb-0">
          <thead>
            <tr>
              <th>Sr</th>
              <th>District</th>
              <th class="text-right">Total Schools</th>
              <th class="text-right">Reports Received</th>
              <th class="text-right">Dangerous Schools</th>
              <th class="text-right">Flood Affected</th>
              <th class="text-right">Pending Schools</th>
            </tr>
          </thead>
          <tbody>
            <?php if (!empty($summary)): ?>
              <?php $sr = 1;
              foreach ($summary as $row): ?>
                <tr>
                  <td><?php echo $sr++; ?></td>
                  <td><?php echo $row['district_name']; ?></td>
                  <td class="text-right"><?php echo $row['total_schools']; ?></td>
                  <td class="text-right"><?php echo $row['reports_received']; ?></td>
                  <td class="text-right"><?php echo $row['dangerous_schools']; ?></td>
                  <td class="text-right"><?php echo $row['flood_schools']; ?></td>
                  <td class="text-right"><?php echo $row['pending_schools']; ?></td>
                </tr>
              <?php endforeach; ?>
            <?php else: ?>
              <tr>
                <td colspan="5" class="text-center">No data available</td>
              </tr>
            <?php endif; ?>
          </tbody>
          <tfoot>
            <tr class="font-weight-bold">
              <td colspan="2">Total</td>
              <td class="text-right"><?php echo isset($totals['total_schools']) ? $totals['total_schools'] : 0; ?></td>
              <td class="text-right"><?php echo isset($totals['reports_received']) ? $totals['reports_received'] : 0; ?></td>
              <td class="text-right"><?php echo isset($totals['dangerous_schools']) ? $totals['dangerous_schools'] : 0; ?></td>
              <td class="text-right"><?php echo isset($totals['flood_schools']) ? $totals['flood_schools'] : 0; ?></td>
              <td class="text-right"><?php echo isset($totals['pending_schools']) ? $totals['pending_schools'] : 0; ?></td>
            </tr>
          </tfoot>
        </table>
      </div>
    </div>
  </div>
</section>

<?php include viewPath('includes/footer'); ?>