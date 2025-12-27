<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>

<ul class="nav nav-pills nav-sidebar flex-column nav-legacy" data-widget="treeview" role="menu" data-accordion="false">

  <li class="nav-header text-center">
    <div class="py-3">
      <img src="<?php echo base_url('assets/img/peima-logo.png'); ?>" alt="PEIMA Logo" style="max-width:120px; height:auto;">
    </div>
  </li>

  <li class="nav-item">
    <a href="<?php echo url('dashboard') ?>" class="nav-link <?php echo ($page->menu == 'dashboard') ? 'active' : '' ?>">
      <i class="nav-icon fas fa-tachometer-alt"></i>
      <p>
        <?php echo lang('dashboard') ?>
      </p>
    </a>
  </li>

  <?php if (hasPermissions('users_list')): ?>
    <li class="nav-item">
      <a href="<?php echo url('users') ?>" class="nav-link <?php echo ($page->menu == 'users') ? 'active' : '' ?>">
        <i class="nav-icon fas fa-user"></i>
        <p>
          <?php echo lang('users') ?>
        </p>
      </a>
    </li>
  <?php endif ?>

  <?php if (hasPermissions('activity_log_list')): ?>
    <li class="nav-item">
      <a href="<?php echo url('activity_logs') ?>" class="nav-link <?php echo ($page->menu == 'activity_logs') ? 'active' : '' ?>">
        <i class="nav-icon fas fa-history"></i>
        <p>
          <?php echo lang('activity_logs') ?>
        </p>
      </a>
    </li>
  <?php endif ?>

  <?php if (hasPermissions('roles_list')): ?>
    <li class="nav-item">
      <a href="<?php echo url('roles') ?>" class="nav-link <?php echo ($page->menu == 'roles') ? 'active' : '' ?>">
        <i class="nav-icon fas fa-lock"></i>
        <p>
          <?php echo lang('manage_roles') ?>
        </p>
      </a>
    </li>
  <?php endif ?>

  <?php if (hasPermissions('permissions_list')): ?>
    <li class="nav-item">
      <a href="<?php echo url('permissions') ?>" class="nav-link <?php echo ($page->menu == 'permissions') ? 'active' : '' ?>">
        <i class="nav-icon fas fa-user"></i>
        <p>
          <?php echo lang('manage_permissions') ?>
        </p>
      </a>
    </li>
  <?php endif ?>


  <?php if (hasPermissions('backup_db')): ?>
    <li class="nav-item">
      <a href="<?php echo url('backup') ?>" class="nav-link <?php echo ($page->menu == 'backup') ? 'active' : '' ?>">
        <i class="nav-icon fas fa-user"></i>
        <p>
          <?php echo lang('backup') ?>
        </p>
      </a>
    </li>
  <?php endif ?>

  <?php if (logged('role') == 1 || hasPermissions('districts_list') || hasPermissions('tehsils_list') || hasPermissions('schools_list')): ?>
    <?php $locationActive = ($page->menu == 'locations') || in_array($this->uri->segment(1), ['districts', 'tehsils', 'schools']); ?>
    <li class="nav-item has-treeview <?php echo $locationActive ? 'menu-open' : '' ?>">
      <a href="#" class="nav-link <?php echo $locationActive ? 'active' : '' ?>">
        <i class="nav-icon fas fa-map-marker-alt"></i>
        <p>
          <?php echo lang('location_management') ?>
          <i class="right fas fa-angle-left"></i>
        </p>
      </a>
      <ul class="nav nav-treeview">
        <li class="nav-item">
          <a href="<?php echo url('districts') ?>" class="nav-link <?php echo ($page->submenu == 'districts' || $this->uri->segment(1) === 'districts') ? 'active' : '' ?>">
            <i class="far fa-circle nav-icon"></i>
            <p><?php echo lang('districts') ?></p>
          </a>
        </li>
        <?php if ((int) logged('role') === 1): ?>
          <li class="nav-item">
            <a href="<?php echo url('districts/report_summary') ?>" class="nav-link <?php echo ($this->uri->segment(1) === 'districts' && $this->uri->segment(2) === 'report_summary') ? 'active' : '' ?>">
              <i class="far fa-circle nav-icon"></i>
              <p>District Report Summary</p>
            </a>
          </li>
        <?php endif; ?>
        <li class="nav-item">
          <a href="<?php echo url('tehsils') ?>" class="nav-link <?php echo ($page->submenu == 'tehsils' || $this->uri->segment(1) === 'tehsils') ? 'active' : '' ?>">
            <i class="far fa-circle nav-icon"></i>
            <p><?php echo lang('tehsils') ?></p>
          </a>
        </li>
        <li class="nav-item">
          <a href="<?php echo url('schools') ?>" class="nav-link <?php echo ($page->submenu == 'schools' || $this->uri->segment(1) === 'schools') ? 'active' : '' ?>">
            <i class="far fa-circle nav-icon"></i>
            <p><?php echo lang('schools') ?></p>
          </a>
        </li>
      </ul>
    </li>
  <?php endif ?>

  <?php if (logged('role') == 1 || hasPermissions('school_visits_list')): ?>
    <?php $visitActive = ($page->menu == 'school_visits') || ($this->uri->segment(1) === 'school_visits'); ?>
    <li class="nav-item has-treeview <?php echo $visitActive ? 'menu-open' : '' ?>">
      <a href="#" class="nav-link <?php echo $visitActive ? 'active' : '' ?>">
        <i class="nav-icon fas fa-clipboard-check"></i>
        <p>
          <?php echo ((int) logged('role') === 3) ? 'Inspection Report' : lang('school_visits'); ?>
          <i class="right fas fa-angle-left"></i>
        </p>
      </a>
      <ul class="nav nav-treeview">
        <li class="nav-item">
          <a href="<?php echo url('school_visits') ?>" class="nav-link <?php echo ($this->uri->segment(1) === 'school_visits' && $this->uri->segment(2) === '') ? 'active' : '' ?>">
            <i class="far fa-circle nav-icon"></i>
            <p><?php echo ((int) logged('role') === 3) ? 'List' : lang('list_all_school_visits'); ?></p>
          </a>
        </li>
        <li class="nav-item">
          <a href="<?php echo url('school_visits/add') ?>" class="nav-link <?php echo ($this->uri->segment(1) === 'school_visits' && $this->uri->segment(2) === 'add') ? 'active' : '' ?>">
            <i class="far fa-circle nav-icon"></i>
            <p><?php echo ((int) logged('role') === 3) ? 'Add' : lang('add_school_visit'); ?></p>
          </a>
        </li>
        <?php if ((int) logged('role') === 1): ?>
          <li class="nav-item">
            <a href="<?php echo url('school_visits/heads_information') ?>" class="nav-link <?php echo ($this->uri->segment(1) === 'school_visits' && $this->uri->segment(2) === 'heads_information') ? 'active' : '' ?>">
              <i class="far fa-circle nav-icon"></i>
              <p>Heads Information</p>
            </a>
          </li>
        <?php endif; ?>
      </ul>
    </li>
  <?php endif ?>

  <?php if (hasPermissions('company_settings')): ?>
    <li class="nav-item has-treeview <?php echo ($page->menu == 'settings') ? 'menu-open' : '' ?>">
      <a href="#" class="nav-link  <?php echo ($page->menu == 'settings') ? 'active' : '' ?>">
        <i class="nav-icon fas fa-cog"></i>
        <p>
          <?php echo lang('settings') ?>
          <i class="right fas fa-angle-left"></i>
        </p>
      </a>
      <ul class="nav nav-treeview">
        <li class="nav-item">
          <a href="<?php echo url('settings/general') ?>" class="nav-link <?php echo ($page->submenu == 'general') ? 'active' : '' ?>">
            <i class="far fa-circle nav-icon"></i>
            <p> <?php echo lang('general_setings') ?> </p>
          </a>
        </li>

        <li class="nav-item">
          <a href="<?php echo url('settings/company') ?>" class="nav-link <?php echo ($page->submenu == 'company') ? 'active' : '' ?>">
            <i class="far fa-circle nav-icon"></i>
            <p> <?php echo lang('company_setings') ?> </p>
          </a>
        </li>

        <li class="nav-item">
          <a href="<?php echo url('settings/email_templates') ?>" class="nav-link <?php echo ($page->submenu == 'email_templates') ? 'active' : '' ?>">
            <i class="far fa-circle nav-icon"></i>
            <p> <?php echo lang('manage_email_template') ?></p>
          </a>
        </li>
      </ul>
    </li>
  <?php endif ?>


</ul>
