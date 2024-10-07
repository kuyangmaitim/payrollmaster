<?php include_once('header.php'); ?>
<?php
  $employee =  new Employee;
  $settings =  new Settings;
?>
<div class="container-fluid">
  <!-- Breadcrumbs-->
  <ol class="breadcrumb">
    <li class="breadcrumb-item">
      <a href="#">Dashboard</a>
    </li>
    <li class="breadcrumb-item active">Payroll List</li>
  </ol>
  <!-- Icon Cards-->
  <div id="payroll-list-wrapper" class="row">
    <div class="col-12 mb-3">
        <div class="card mb-3">
          <div class="card-header">
            Payroll List
          </div> <!-- card Header -->
          <div class="card-body">
          </div> <!--  card-body -->
      </div> <!-- Card wrapper -->
    </div> <!-- Content Wrapper -->
  </div> <!-- payroll-list-wrapper -->
</div><!-- /.container-fluid-->
<?php include_once('footer.php'); ?>