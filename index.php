<?php include_once('header.php'); ?>
<div class="container-fluid">
  <!-- Breadcrumbs-->
  <ol class="breadcrumb">
    <li class="breadcrumb-item">
      <a href="index.php">Dashboard</a>
    </li>
  </ol>
  <!-- Icon Cards-->
  <div class="row">
    <div class="col-12 mb-3">
      <?php
        $employee =  new Employee;
        $settings =  new Settings;
        $access_level = $employee->get_user_data_by_id( $sessionUserId, 'access_level' );

        if( $access_level == 4 ){
          include_once( 'user-dashboard.php' );
        }else{
          include_once( 'admin-dashboard.php' );
        }

      ?>
    </div>
  </div>
</div><!-- /.container-fluid-->
<?php include_once('footer.php'); ?>