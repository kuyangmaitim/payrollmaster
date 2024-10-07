<?php include_once('header.php'); ?>
<?php
  $settings      = new Settings;
  $employee      = new Employee;
  $files         = new EmpFiles;
  $memo          = new Memo;
  // $qualification = new Qualification;

  $user_data    = $employee->get_user( intval( $_GET['uid'] ) );
  $user_leaves  = $employee->get_user_leaves( intval( $_GET['uid'] ) );
  $requested_sched  = $employee->get_user_requested_sched( intval( $_GET['uid'] ) );
  $user_files   = $files->user_files( $user_data->id );
  $user_memos   = $memo->get_user_memo( $user_data->id );

  $file_type    = isset($_GET['type']) && !empty($_GET['type']) ? $_GET['type'] : false;

  $calendar_data = array();
  if( !empty( $user_leaves ) ){
    foreach ( $user_leaves as $leave ) {
      $status = $leave->status ? 'Approved' : 'Pending Approval';
      $calendar_data[] = array(
        'id'        => $leave->id,
        'uid'       => $leave->uid,
        'start'     => $leave->date,
        'type'      => $leave->type,
        'title'     => work_status()[$leave->type],
        'status'    => $status,
        'dataType'  => 'leave',
        'color'     => '#1eb8de',
        'timeSchedule'  => ''
      );
    }
  }
  if( !empty( $requested_sched ) ){
    foreach ( $requested_sched as $shed ) {
      $userSchedule     = $settings->get_settings_data( $shed->schedule, true );
      $timeSchedule     = 'Open';
      $scheduleName     = '';
      if( !empty( $userSchedule ) ){
        $userSchedule  = unserialize( $userSchedule );
        $scheduleName  = $userSchedule['name'];
        $timeSchedule  = $userSchedule['ftimein'].' to '.$userSchedule['stimeout'];
      }
      if( $shed->schedule == 0 ){
        $scheduleName = 'Open Schedule';
      }
      $status = $shed->status ? 'Approved' : 'Pending Approval';
      $calendar_data[] = array(
        'id'        => $shed->id,
        'uid'       => $shed->uid,
        'start'     => $shed->date,
        'type'      => $shed->schedule,
        'title'     => $scheduleName,
        'status'    => $status,
        'dataType' => 'schedule',
        'color'     => '#c0eb3c',
        'timeSchedule'  => $timeSchedule
      );
    }
  }
?>
<div class="container-fluid">
  <!-- Breadcrumbs-->
  <ol class="breadcrumb">
    <li class="breadcrumb-item">
      <a href="#">Dashboard</a>
    </li>
    <li class="breadcrumb-item active">Update Employee</li>
  </ol>
  <!-- Icon Cards-->
  <div id="adduser-form" class="row">
      <?php include_once(ABSPATH.'/templates/update-user/qualifications/quali-header.php'); ?>
  </div>
</div>


<?php include_once('footer.php'); ?>