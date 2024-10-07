<?php include_once('header.php'); ?>

<div class="container-fluid">
  <!-- Breadcrumbs-->
  <ol class="breadcrumb">
    <li class="breadcrumb-item">
      <a href="#">Dashboard</a>
    </li>
    <li class="breadcrumb-item active">Update Employee</li>
  </ol>
  <!-- Icon Cards-->
  <div class="row">
    <div class="col-12">
      <?php include_once(ABSPATH.'/templates/update-user/header2.php'); ?>
        <div class="breadcrumb bg-info text-white">
          Salary/Benefits Information
        </div>
      <div class="row">
        <div class="col-md-12 wptf-section">
          <?php include_once(ABSPATH.'/templates/update-user/salary-info.php'); ?>
        </div>          
      </div>
    </div>
  </div>
</div>

<?php include_once('footer.php'); ?>